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
        try {
            $product = Product::find($productId)->load('photos');
    
            if($product){
                return response()->json([
                    'success' => 1,
                    'message' => 'Successfully Fetch Product Photos',
                    'data' => $product->photos
                ]);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => 0 ,
                'message' => 'Failed to Fetch ProductPhotos or Product not Found'
            ],422);
        }

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
                $photo->storeAs('public/'.$photosFilepath, $filename);

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
    public function destroy($productId, $productPhotos)
    {
        $productPhoto = ProductPhotos::where('id','=',$productPhotos)->where('product_id',$productId);

        if($productPhoto){
            $productPhoto->delete();

            return response()->json([
                'success'=> 1,
                'message' => 'Successfully delete ProductPhoto'
            ]);
        }

        return response()->json([
            'success' => 0,
            'message' => 'Product Photo Not Found'
        ]);

        // dd($productPhoto, $productPhotos, $productId);
    }
}
