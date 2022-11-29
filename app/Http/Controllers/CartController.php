<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    public function cart()
    {
        return view('cart.cart');
    }

    public function addToCart($id)
    {
        $product = Product::findOrFail($id);
        $cartItems = session()->get('cartItems', []);

        // Checks if the product is added to the cart or not
        if (isset($cartItems[$id])) {
            // If the item is already on the cart, you can increase the quantity
            $cartItems[$id]['quantity']++;
        } else {
            // Otherwise add the product to the cart if it is not already in the cart
            $cartItems[$id] = [
                "image_path" => $product->image_path,
                "name" => $product->name,
                "brand" => $product->brand,
                "details" => $product->details,
                "price" => $product->price,
                "quantity" => 1
            ];
        }

        session()->put('cartItems', $cartItems);
        return redirect()->back()->with('success', 'Product added to cart!');
    }

    public function delete(Request $request)
    {
        // Checks if the request ID exists
        if ($request->id) {
            $cartItems = session()->get('cartItems');

            // Checks if the request ID and cart ID are the same
            if (isset($cartItems[$request->id])) {
                unset($cartItems[$request->id]);
                session()->put('cartItems', $cartItems);
            }

            return redirect()->back()->with('success', 'Product deleted successfully');
        }
    }

    public function update(Request $request)
    {
        // Checks if the request ID and quantity are the same
        if ($request->id && $request->quantity) {
            $cartItems = session()->get('cartItems');
            $cartItems[$request->id]['quantity'] = $request->quantity;
            session()->put('cartItems', $cartItems);
        }

        return redirect()->back()->with('success', 'Product added to cart!');
    }
}
