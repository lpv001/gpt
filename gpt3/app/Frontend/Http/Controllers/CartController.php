<?php

namespace App\Frontend\Http\Controllers;

use App\Admin\Models\ProductImage as ModelsProductImage;
use App\Admin\Models\ProductPrice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Frontend\Models\Product;
use App\Frontend\Models\ProductImage;
use App\Frontend\Models\ShoppingCarts;
use Illuminate\Support\Facades\Auth;
use Cart;
use Exception;

class CartController extends Controller
{
    public function index()
    {
        $shops = [];
        $carts = [];
        $shopping_cart = Cart::getContent();
        // foreach ($shopping_cart as $key => $value) {
        //     if (count($value['attributes'])) {
        //         // $shops 
        //         $shop_id = $value['attributes']['shop_id'];
        //         // if ($value['attributes'])
        //     }
        // }
        $carts = $shopping_cart;
        return view('frontend::carts.cart', compact('carts'));
    }

    public function addCart(Request $request)
    {
        $response = [];
        try {
            $product = $request->product;
            $selected_option_value = [];
            $cart_id = null;
            $option_image = null;

            if (array_key_exists('options', $product)) {
                $options = $product['options'];

                if (count($options) != count($request->option_value_id)) {
                    return response()->json(['status' => false, 'message' => 'Please provide information.']);
                }

                foreach ($options as $key => $option) {
                    $selected_id = $request->option_value_id[$key++];
                    if (count($option['values']) > 0) {
                        foreach ($option['values'] as $option_value) {
                            if ($option_value['id'] == $selected_id) {
                                $cart_id .= $option_value['id']; // init cart id
                                $option_image = isset($option_value['image_name']) ? $option_value['image_name'] : null;

                                $selected_option_value[$option['name']] = [
                                    'option_value_id'   =>  intval($option_value['id']),
                                    'name'              =>  $option_value['name'],
                                    'image'             =>  $option_value['image_name'],
                                ];
                            }
                        }
                    }
                }
            }

            $price = isset($request->variants) ? $request->variants[0]['variant_price']  :  $product['unit_price'];
            $qty = $request->qty ?? 1;
            $cart = [
                'id' => count($selected_option_value) > 0 ? $cart_id : $product['id'],
                'name' => $product['name'],
                'price' => $price,
                'quantity' => $qty,
                'attributes' => [
                    'qty'           =>  $qty,
                    'product_id'    =>  $product['id'],
                    'option'        =>  $selected_option_value,
                    'variants'      =>  isset($request->variants) ? $request->variants : [],
                    'unit_id'       =>  $product['unit_id'],
                    'shop_id'       =>  $product['shop_id'],
                    'shop'          =>  $product['shop_name'],
                    'image'         =>  isset($option_image) ? $option_image : $request->image,
                    'sale_price'    =>  $price,
                    'point_rate'    =>  $product['point_rate'],
                    'total'         =>  $price * $qty,
                ]
            ];

            Cart::add($cart);
            // $card = auth()->user() ? Cart::session(auth()->user()->id)->add($cart) : Cart::add($cart);

            return $response = [
                'status' => true,
                'message' => 'Product add to cart successfully.',
                'data'  =>  Cart::getContent()->count(),
                'redirect_to' => url('cart')
            ];
        } catch (Exception $e) {
            $response = ['status' => false, 'message' => $e->getMessage()];
        }

        return response()->json($response);
    }

    /**
     * Update item in cart by id
     */
    public function updateCart($id, Request $request)
    {
        $response = [];
        $total = 0;
        try {
            $cart = Cart::get($id);
            if (($cart)) {
                $cart['attributes']['qty']  = $request->qty ?? 1;
                Cart::update($id, $cart);
            }

            $response = ['status' => true, 'message' => 'Update successfully',];
        } catch (Exception $e) {
            $response = ['status' => false, 'message' =>  $e->getMessage()];
        }

        $total = $this->calulateTotal();
        $response['subtotal'] = $total;
        return response()->json($response);
    }

    /**
     * Remove item by id
     */
    public function destroy($id)
    {
        $total = 0;
        Cart::remove($id);

        foreach (Cart::getContent() as $key => $cart) {
            $total += $cart['attributes']['qty'] * $cart['price'];
        }

        $cart = Cart::getContent()->count();
        return response()->json([
            'status' => true,
            'message' => 'Delete successfully',
            'data' => $cart,
            'total' => $total,
            'currency' => ['US', '$'],
        ]);
    }

    /**
     * Remove all Item in Cart
     */
    public function clearCart()
    {
        session()->forget('mycart');
        return response()->json(200);
    }

    private function calulateTotal()
    {
        $shopping_carts = Cart::getContent();
        $total = 0;
        foreach ($shopping_carts as $key => $card) {
            $total += $card['attributes']['qty'] * $card['price'];
        }

        return $total;
    }
}
