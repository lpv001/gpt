<?php
namespace App\Helper;

use Intervention\Image\Facades\Image;

class ImageHandler {
    /**
     * Padding image keeping aspect radio
     * 
     */
    public function createPaddingImage($path, $src_file, $dst_file, $width, $height)
    {
        $src_image = $path . $src_file;
        $dst_image = $path . $dst_file;
        
        $background = Image::canvas($width, $height);
        
        $img = Image::make($src_image)->resize($width, $height, function ($c) {
            $c->aspectRatio();
            $c->upsize();
        });
        
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
    public function createResizeImage($path, $src_file, $dst_file, $width, $height)
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
    public function createThumbnail($path, $filename)
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
    public function createThumbnailNoneCrop($path, $filename)
    {
        // creat thumbnail
        $srcfilename = '/' . $filename;
        $iconfilename = '/icon/icon_' . $filename;
        $smallfilename = '/thumbnail/small_' . $filename;
        $mediumfilename = '/thumbnail/medium_' . $filename;
        $largefilename = '/thumbnail/large_' . $filename;
        
        // create resize files
        $this->createPaddingImage($path, $srcfilename, $iconfilename, 32, 24);
        $this->createPaddingImage($path, $srcfilename, $smallfilename, 152, 114);
        $this->createPaddingImage($path, $srcfilename, $mediumfilename, 400, 300);
        $this->createPaddingImage($path, $srcfilename, $largefilename, 1080, 810);
        
        return true;
    }

    /**
     * delete thumbnails
     *
     * @param string $path path of thumbnail
     * @param int $width
     * @param int $height
     */
    public function deleteThumbnail($path, $filename)
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













}
