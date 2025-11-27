<?php

namespace App\Http\Controllers;

use Log;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller {
    // ADD PRODUCT

    public function addToCart( Request $request ) {
        $cart = session()->get( 'cart', [] );

        $id = $request->id;
        $name = $request->name;
        $price = $request->price;

        if ( isset( $cart[ $id ] ) ) {
            $cart[ $id ][ 'quantity' ] += 1;
        } else {
            $cart[ $id ] = [
                'name' => $name,
                'price' => $price,
                'quantity' => 1,
            ];
        }

        session()->put( 'cart', $cart );

        // Hitung total
        $total = collect( $cart )->reduce( function ( $carry, $item ) {
            return $carry + ( $item[ 'price' ] * $item[ 'quantity' ] );
        }
        , 0 );

        // Tambahkan ini sebelum return:
        $filteredCart = array_filter( $cart, fn( $item ) => !empty( $item[ 'name' ] ) && $item[ 'name' ] !== 'null' );
        $total = collect( $filteredCart )->reduce( fn( $sum, $item ) => $sum + ( $item[ 'price' ] * $item[ 'quantity' ] ), 0 );
        return response()->json( [
            'cart' => $cart,
            'total' => $total,
        ] );
    }

    public function updateCart( Request $request ) {
        $cart = session()->get( 'cart', [] );
        $id = $request->id;
        $action = $request->action;

        if ( isset( $cart[ $id ] ) ) {
            if ( $action === 'increase' ) {
                $cart[ $id ][ 'quantity' ] += 1;
            } elseif ( $action === 'decrease' && $cart[ $id ][ 'quantity' ] > 1 ) {
                $cart[ $id ][ 'quantity' ] -= 1;
            }
        }

        session()->put( 'cart', $cart );
        // Tambahkan ini sebelum return:
        $filteredCart = array_filter( $cart, fn( $item ) => !empty( $item[ 'name' ] ) && $item[ 'name' ] !== 'null' );
        $total = collect( $filteredCart )->reduce( fn( $sum, $item ) => $sum + ( $item[ 'price' ] * $item[ 'quantity' ] ), 0 );
        return $this->cartResponse( $cart );
    }

    public function removeFromCart( Request $request ) {
        $cart = session()->get( 'cart', [] );
        unset( $cart[ $request->id ] );
        session()->put( 'cart', $cart );
        return $this->cartResponse( $cart );
    }

    private function cartResponse( $cart ) {
        $filtered = array_filter( $cart, fn( $item ) => !empty( $item[ 'name' ] ) && $item[ 'name' ] !== 'null' );
        $total = collect( $filtered )->reduce( fn( $sum, $item ) => $sum + ( $item[ 'price' ] * $item[ 'quantity' ] ), 0 );

        // Tambahkan ini sebelum return:
        $filteredCart = array_filter( $cart, fn( $item ) => !empty( $item[ 'name' ] ) && $item[ 'name' ] !== 'null' );
        $total = collect( $filteredCart )->reduce( fn( $sum, $item ) => $sum + ( $item[ 'price' ] * $item[ 'quantity' ] ), 0 );
        return response()->json( [
            'cart' => $filtered,
            'total' => $total,
        ] );
    }

   public function checkout(Request $request) {
    $cart = session()->get('cart', []);

    if (empty($cart)) {
        return response()->json([
            'success' => false,
            'message' => 'Keranjang kosong.'
        ], 400);
    }

    try {
        $receipt = [];  // Ini untuk menampung data struk

        foreach ($cart as $id => $item) {
            $product = Product::find($id);

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => "Produk ID $id tidak ditemukan."
                ], 404);
            }

            if ($product->stock < $item['quantity']) {
                return response()->json([
                    'success' => false,
                    'message' => "Stok tidak cukup untuk {$item['name']}."
                ], 400);
            }

            $product->stock -= $item['quantity'];
            $product->save();

            // Simpan ke struk
            $receipt['items'][] = [
                'name' => $item['name'],
                'quantity' => $item['quantity'],
                'subtotal' => $item['price'] * $item['quantity'],
            ];
        }

        // Total
        $total = collect($cart)->reduce(function ($carry, $item) {
            return $carry + ($item['price'] * $item['quantity']);
        }, 0);

        // Simpan data struk
        $receipt['order_id'] = 'INV-' . rand(1000, 9999);  // Generasi Order ID
        $receipt['tanggal'] = now()->toDateString();  // Tanggal
        $receipt['total'] = $total;

        session()->forget('cart');

        return response()->json([
            'success' => true,
            'message' => 'Checkout berhasil!',
            'receipt' => $receipt
        ]);
    } catch (\Exception $e) {
        Log::error('Checkout error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan saat checkout.'
        ], 500);
    }
}


}
// ...existing code...