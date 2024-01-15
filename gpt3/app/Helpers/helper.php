<?php

use Darryldecode\Cart\Cart;
use Intervention\Image\Facades\Image;


function base_url_api()
{
    return env('API_URL');
}

function selectCountry()
{
    return \DB::table('city_provinces')->orderBy('default_name', 'ASC')->pluck('default_name','id');
}

function units()
{
    return  \DB::table('units')->orderBy('name', 'ASC')->pluck('name','id');
}

function memberType()
{
    $data = [
        0 => 'Buyer',
        1 => 'Retailer',
        2 => 'Wholsaler',
        3 => 'Distributor'
    ];
    return $data;
}

function getSubUnits($id)
{
    return \DB::table('units')->where('parent_id', $id)->orderBy('name', 'ASC')->pluck('name','id');
}

function product_unit()
{
    return \DB::table('units')->orderBy('id', 'ASC')->get(['id', 'parent_id', 'name']);
}

function getUnitName($id)
{
    return \DB::table('units')->find($id);
}

function getCountry($id)
{
    return \DB::table('country')->find($id);
}

function getCityName($id)
{
    return \DB::table('city_provinces')->find($id);
}

function getDistrict($id)
{
    return \DB::table('districts')->find($id);
}

function getUserName($id)
{
    return \DB::table('users')->find($id);
}

function getSupplier($id)
{
    return \DB::table('shops')->find($id);
}

function getMemberType($id)
{
    return \DB::table('memberships')->find($id);
}

function users()
{
    return \DB::table('users')->pluck('full_name', 'id');
}

function City()
{
    return \DB::table('city_provinces')->pluck('default_name', 'id');
}


// --------------------------------------------------------- //
//                     Image toolkit                         //
// --------------------------------------------------------- //

/**
 * Padding image keeping aspect radio
 * 
 */
function createPaddingImage($path, $src_file, $dst_file, $width, $height)
{
    $src_image = $path . $src_file;
    $dst_image = $path . $dst_file;
    
    $background = Image::canvas($width, $height);
    
    $img = Image::make($src_image)->resize($width, $height, function ($c) {
        $c->aspectRatio();
        $c->upsize();
    });
    
    /*
    $img = Image::make($src_image)->resize($width, $height, function ($c) {
        $c->aspectRatio();
    });
    */
    
    $background->insert($img, 'center');
    
    $background->save($dst_image);
    return true;
}

/**
 * resize image keeping aspect radio
 *
 * @param string $path path of image storage
 * @param string $src_file of source image filename
 * @param string $dst_file of destination image filename
 * @param int $width
 * @param int $height
 */
function createResizeImage($path, $src_file, $dst_file, $width, $height)
{
    $src_image = $path . $src_file;
    $dst_image = $path . $dst_file;
    
    $img = Image::make($src_image)->resize($width, $height, function ($constraint) {
        $constraint->aspectRatio();
    });
    
    $img->save($dst_image);
    return true;
}

/**
 * This is the resize image function not Create a thumbnail of specified size
 *
 * @param string $path path of thumbnail
 * @param int $width
 * @param int $height
 */
function createThumbnail($path, $filename)
{
  $iconfilename = $path . '/icon/icon_' . $filename;
  $smallfilename = $path . '/thumbnail/small_' . $filename;
  $mediumfilename = $path . '/thumbnail/medium_' . $filename;
  $largefilename = $path . '/thumbnail/large_' . $filename;
  
  $src_image = $path . '/' . $filename;
  list($width, $height) = getimagesize($src_image);
  $img = Image::make($src_image);
  if ($width > $height) {
      //echo "Landscape";
      $img->resize(null, 810, function ($constraint) { $constraint->aspectRatio();});
      $img->fit(1080, 810, function ($constraint) { $constraint->upsize();});
      $img->save($largefilename);
  } else {
      //echo "Portrait or Square";
      $img->fit(810, 1080, function ($constraint) { $constraint->upsize();});
      $img->save($largefilename);
  }
  $img->fit(400, 300)->save($mediumfilename);
  $img->fit(152, 114)->save($smallfilename);
  $img->fit(32, 24)->save($iconfilename);
  
  return true;
}

/**
 * Create a thumbnail of specified size
 *
 * @param string $path path of thumbnail
 * @param int $width
 * @param int $height
 */
function createThumbnailNoneCrop($path, $filename)
{
    // creat thumbnail
    $srcfilename = '/' . $filename;
    $iconfilename = '/icon/icon_' . $filename;
    $smallfilename = '/thumbnail/small_' . $filename;
    $mediumfilename = '/thumbnail/medium_' . $filename;
    $largefilename = '/thumbnail/large_' . $filename;
    
    // create resize files
    /*
    $src_image = $path . '/' . $filename;
    list($width, $height) = getimagesize($src_image);
    if ($width > $height) {
      createResizeImage($path, $srcfilename, $iconfilename, 32, null);
      createResizeImage($path, $srcfilename, $smallfilename, 152, null);
      createResizeImage($path, $srcfilename, $mediumfilename, 400, null);
      createResizeImage($path, $srcfilename, $largefilename, 1080, null);
    } else {
      createResizeImage($path, $srcfilename, $iconfilename, null, 24);
      createResizeImage($path, $srcfilename, $smallfilename, null, 114);
      createResizeImage($path, $srcfilename, $mediumfilename, null, 300);
      createResizeImage($path, $srcfilename, $largefilename, null, 810);
    }
    */
    createPaddingImage($path, $srcfilename, $iconfilename, 32, 24);
    createPaddingImage($path, $srcfilename, $smallfilename, 152, 114);
    createPaddingImage($path, $srcfilename, $mediumfilename, 400, 300);
    createPaddingImage($path, $srcfilename, $largefilename, 1080, 810);
    
    return true;
}

/**
 * delete thumbnails
 *
 * @param string $path path of thumbnail
 * @param int $width
 * @param int $height
 */
function deleteThumbnail($path, $filename)
{
    $iconfilename = $path . '/icon/icon_' . $filename;
    $smallfilename = $path . '/thumbnail/small_' . $filename;
    $mediumfilename = $path . '/thumbnail/medium_' . $filename;
    $largefilename = $path . '/thumbnail/large_' . $filename;
    
    if (file_exists($iconfilename)) {
        unlink($iconfilename);
    }
    if (file_exists($smallfilename)) {
        unlink($smallfilename);
    }
    if (file_exists($mediumfilename)) {
        unlink($mediumfilename);
    }
    if (file_exists($largefilename)) {
        unlink($largefilename);
    }
    
    return true;
}

// --------------------------------------------------------- //
//                     Frontend                              //
// --------------------------------------------------------- //

function countCart()
{
    return 0;
}

function getUnit($id)
{
    return \DB::table('units')->find($id);
}

function sumTotalProduct($id)
{
    return \DB::table('shopping_carts')->where('id', $id)->sum('total');
}
