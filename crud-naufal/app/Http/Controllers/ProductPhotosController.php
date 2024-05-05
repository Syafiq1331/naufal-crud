<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductPhotos;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductPhotosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($productId)
    {
        $product = Product::find($productId)->load('photos');

        return response()->json([
            'success' => 1,
            'message' => 'Successfully Fetch Product Photos',
            'data' => $product->photos
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $productId)
    {
        $product = Product::find($productId);
        if ($product) {

            $request->validate([
                'photos' => 'required|array',
                'photos.*' => 'image' 
            ]);

            $photos = $request->file('photos');

            foreach ($photos as $photo) {
                $fileUuid = Str::uuid();
                $photosFilepath = 'products/photos';
        
                $fileExtensions = $photo->extension();
                        
                $filename =  $fileUuid . '.' . $fileExtensions;
                $photo->storeAs($photosFilepath, $filename);

                $product->photos()->create([
                    'photo_url' => $photosFilepath . '/' . $filename
                ]);
            }

            // dd("OK", $product->load('photos')->photos->toArray());

            return response()->json([
                'success' => 1,
                'message' => 'Photos Added to Product'
            ]);
        }

        return response()->json([
            'success' => 0,
            'message' => 'Product Not Found'
        ], 404);
        dd("NOT OK");
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductPhotos $productPhotos)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductPhotos $productPhotos)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductPhotos $productPhotos)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductPhotos $productPhotos)
    {
        //
    }
}
