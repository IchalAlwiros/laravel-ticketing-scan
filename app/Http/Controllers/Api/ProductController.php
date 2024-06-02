<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
     //index
     public function index(Request $request)
     {
         // Tentukan jumlah item per halaman dari request, dengan nilai default 10
        $perPage = $request->get('limit', 10);

        // Tentukan halaman saat ini dari request, dengan nilai default 1
        $page = $request->get('page', 1);
        //  $products = Product::with('category')->when($request->status, function ($query) use ($request) {
        //      $query->where('status', 'like', "%{$request->status}%");
        //  })->orderBy('favorite', 'desc')

        //      ->get();

        $products = Product::with('category')
        ->when($request->status, function ($query) use ($request) {
            $query->where('status', 'like', "%{$request->status}%");
        })
        ->orderBy('favorite', 'desc')
        ->paginate($perPage, ['*'], 'page', $page);

        $paginateData = [
            'page' => $products->currentPage(),
            'limit' => $products->perPage(),
            'total' => $products->total(),
            'last_page' => $products->lastPage(),
            // 'from' => $products->firstItem(),
            // 'to' => $products->lastItem(),
        ];

        //  return response()->json(['status' => 'success', 'data' => $products], 200);
        return ResponseHelper::sendSuccessResponse('All Products Available', $products->items(), $paginateData);
     }

     //store
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required',
            'name' => 'required',
            'price' => 'required',
            // 'image' => 'required',
            'criteria' => 'required',
            // 'favorite' => 'required',
            // 'status' => 'required',
            // 'stock' => 'required',
        ]);

        $product = new Product;
        $product->category_id = $request->category_id;
        $product->name = $request->name;
        $product->description = '';
        $product->price = $request->price;
        $product->criteria = $request->criteria;
        $product->favorite = false;
        $product->status = 'published';
        $product->stock = 0;
        $product->save();

        //upload image
        if ($request->file('image')) {
            $image = $request->file('image');
            $image->storeAs('public/products', $product->id . '.png');
            $product->image = $product->id . '.png';
            $product->save();
        }

        //product with category
        $product = Product::with('category')->find($product->id);

        // return response()->json(['status' => 'success', 'data' => $product], 200);
        return ResponseHelper::sendSuccessResponse('Product Created', $product);
    }

    //show
    public function show($id)
    {
        $product = Product::find($id);
        if (!$product) {
            // return response()->json(['status' => 'error', 'message' => 'Product not found'], 404);
            return ResponseHelper::sendErrorResponse('Product not found', null, 404);
        }
        // return response()->json(['status' => 'success', 'data' => $product], 200);
        return ResponseHelper::sendSuccessResponse('Product Show', $product);
    }

    //update
    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        if (!$product) {
            // return response()->json(['status' => 'error', 'message' => 'Product not found'], 404);
            return ResponseHelper::sendErrorResponse('Product not found', null, 404);
        }

        //  dd($product);

        // dd($request->all());

        // $product->category_id = $request->category_id;
        $product->name = $request->name;
        // $product->description = $request->description;
        $product->price = $request->price;
        // $product->criteria = $request->criteria;
        // $product->favorite = $request->favorite;
        // $product->status = $request->status;
        // $product->stock = $request->stock;
        $product->save();

        //upload image
        // if ($request->file('image')) {
        //     $image = $request->file('image');
        //     $image->storeAs('public/products', $product->id . '.png');
        //     $product->image = $product->id . '.png';
        //     $product->save();
        // }

        //product with category
        $product = Product::with('category')->find($product->id);

        // return response()->json(['status' => 'success', 'data' => $product], 200);
        return ResponseHelper::sendSuccessResponse('Product Updated', $product);
    }

    //destroy
    public function destroy($id)
    {
        $product = Product::find($id);
        if (!$product) {
            // return response()->json(['status' => 'error', 'message' => 'Product not found'], 404);
            return ResponseHelper::sendErrorResponse('Product not found', null, 404);
        }

        $product->delete();
        // return response()->json(['status' => 'success', 'message' => 'Product deleted'], 200);
        return ResponseHelper::sendSuccessResponse('Product Deleted', $product);
    }
}
