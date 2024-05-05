<?php

namespace App\Http\Controllers;

use App\Models\Product;
use \Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Product::with('photos')->get();

        return response()->json([
            'success' => 1,
            'message' => 'products fetched successfully',
            'data' => $data->toArray(),
        ]);
    }
    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        dd('create ok');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        try {
            if($request->has('photos')){
                $request->validate([
                    'photos'=> 'array',
                    'photos.*' => 'image'
                ]);
            }

            $request->validate([
                'name' => 'required|string',
                'description' => 'required|string',
                'kode_product' => 'required|string',
                'price' => 'required|numeric',
                'price_discount' => 'nullable|numeric',
                'purchase_price' => 'required|numeric',
                'size' => 'nullable|integer',
                'stock' => 'required|integer',
                'thumbnail' => 'nullable|image',
            ]);

            if(!$request->hasFile('thumbnail')){

                $product = Product::create($request->except(['_csrf','status']));

                if($request->has('photos')){
                    // batch upload photo
                    $images = $request->file('photos');
        
                    foreach($images as $image){
                        $fileUuid = Str::uuid();
                        $photosFilepath = 'products/photos';
        
                        $fileExtensions = $image->extension();
                        
                        $filename =  $fileUuid . '.' . $fileExtensions;
                        $image->storeAs('public/'. $photosFilepath, $filename);

                        $product->photos()->create([
                            'photo_url' => $photosFilepath . '/' . $filename
                        ]);
                    }
                }
                
                return response()->json([
                    'success' => 1,
                    'message' => 'Product Successfully Added'
                ]);
            }
            
            $thumbnailFile = $request->file('thumbnail');
            
            $thumbnailNewFileName = Str::uuid();
            
            $filepath = 'products/thumbnail';
            $filename = $thumbnailNewFileName . '.' . $thumbnailFile->extension();
            
            $thumbnailFile->storeAs('public/'.$filepath, $filename);
            
            $product = Product::create(array_merge($request->except(['_csrf','status']), ['thumbnail' => $filepath . '/' . $filename]));

            if($request->has('photos')){
                // batch upload photo
                $images = $request->file('photos');
    
                foreach($images as $image){
                    $fileUuid = Str::uuid();
                    $photosFilepath = 'products/photos';
    
                    $fileExtensions = $image->extension();
                    
                    $filename =  $fileUuid . '.' . $fileExtensions;
                    $image->storeAs('public/'.$photosFilepath, $filename);
                    
                    $product->photos()->create([
                        'photo_url' => $photosFilepath . '/' . $filename
                    ]);
                }
            }
            return response()->json([
                'success' => 1,
                'message' => 'Product Successfully Added'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => 0,
                'message' => $th->getMessage()
            ],422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($product)
    {
        $product = Product::find($product)->load('photos');
    
        if(!$product){
            return response()->json([
                'success' => 0,
                'message' => 'Product Not Found',
                'data' => []
            ],404);
        }

        return response()->json([
            'success' => 1,
            'message' => 'Product Fetched Successfully',
            'data' => $product->toArray()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        dd('edit page');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $product)
    {
        try {
            $product = Product::findOrFail($product);

            if($product){
                $request->validate([
                    'name' => 'nullable|string',
                    'description' => 'nullable|string',
                    'kode_product' => 'nullable|string',
                    'price' => 'nullable|numeric',
                    'price_discount' => 'nullable|numeric',
                    'purchase_price' => 'nullable|numeric',
                    'size' => 'nullable|integer',
                    'stock' => 'nullable|integer',
                    'thumbnail' => 'nullable|image',
                ]);
                
                
                if(!$request->hasFile('thumbnail')){
                    $product->update($request->except(['_csrf','status','method']));

                    return response()->json([
                        'success' => 1,
                        'message' => 'Successfully Update Product',
                    ]);
                }

                $thumbnailFile = $request->file('thumbnail');
        
                $thumbnailNewFileName = Str::uuid();
                
                $filepath = 'products/thumbnail';
                $filename = $thumbnailNewFileName . '.' . $thumbnailFile->extension();
                
                $thumbnailFile->storeAs('public/'.$filepath, $filename);
                
                $product->update(array_merge($request->except(['_csrf','status','method']), ['thumbnail' => $filepath . '/' . $filename]));
                return response()->json([
                    'success' => 1,
                    'message' => 'Product Successfully Updated'
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json($th);
        }        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($product)
    {
        $product = Product::find($product);

        if($product){
            $product->delete();
            return response()->json([
                'success' => 1,
                'message' => 'Product Successfully Deleted'
            ]);
        }

        return response()->json([
            'success' => 0,
            'message' => 'Failed to Delete Product'
        ],422);
    }
}
