<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller {
    /**
    * Display a listing of the resource.
    */

    public function index() {
        $products = Product::all();
        return view( 'kasir-sistem.table-produk', compact( 'products' ) );
    }

    /**
    * Show the form for creating a new resource.
    */

    public function create() {
        //
    }

    /**
    * Store a newly created resource in storage.
    */

    public function store( Request $request ) {
        $validated = $request->validate( [
            'name' => 'required',
            'kategori' => 'required',
            'price' => 'required|integer',
            'stock' => 'required|integer',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            // 'discount' => 'nullable|integer'
        ] );
        if ( $request->hasFile( 'image' ) ) {
            $imagePath = $request->file( 'image' )->store( 'products', 'public' );
            $validated[ 'image' ] = $imagePath;
        }
        Product::create( $validated );
        return redirect()->back()->with( 'success', 'Produk berhasil di tambahkan' );
    }

    /**
    * Display the specified resource.
    */

    public function show( Produk $produk ) {
        //
    }

    /**
    * Show the form for editing the specified resource.
    */

    public function edit( $id ) {
        $data_old = Product::findOrFail( $id );
        return view( 'kasir-sistem.edit-produk', compact( 'data_old' ) );
    }

    /**
    * Update the specified resource in storage.
    */

    public function update( Request $request, $id ) {
        $validated = $request->validate( [
            'name' => 'required',
            'kategori' => 'required',
            'price' => 'required|integer',
            'stock' => 'required|integer',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'discount' => 'nullable|integer',
        ] );

        if ( $request->hasFile( 'image' ) ) {
            $imagePath = $request->file( 'image' )->store( 'products', 'public' );
            $validated[ 'image' ] = $imagePath;
        }

        $update_data = Product::findOrFail( $id );
        $update_data->update( $validated );

        return redirect()->route( 'show.table' )->with( 'success', 'Produk berhasil diupdate' );
    }

    /**
    * Remove the specified resource from storage.
    */

    public function delete( $id ) {
        $products = Product::findOrFail( $id );
        $products->delete();
        return redirect()->back()->with( 'success', 'Produk berhasil dihapus' );
    }
}
