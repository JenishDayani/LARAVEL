<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\Tax;
use Illuminate\Support\Facades\Auth;
use File;

class ProductController extends Controller
{
    public function addProduct(Request $req)
    {
        $user = Auth::guard()->user();
        if($user->role_id == 1)
        {
            $validateData = $req->validate([
                'Name' => 'required',
                'Description' => 'required',
                'Category' => 'required',
                'Price' => 'required|numeric',
                'Image' => 'required|image',
            ]);
            if(!(Tax::where('category',$req->input('Category'))->exists()))
            {
                $taxs = Tax::all();
                return response()->json([
                    'message' => 'No Such Category Found',
                    'availableTaxs'=> $taxs,
                ]);
            }
            $tax = Tax::where('category',$req->input('Category'))->first();
            $image = time().rand(000000000,999999999).$req->file('Image')->getClientOriginalName();
            $req->file('Image')->storeAs('image/product',$image,'public');
            $data = [
                'name' => $req->input('Name'),
                'desc' => $req->input('Description'),
                'price' => $req->input('Price'),
                'p_image' => $image,
                'tax_id' => $tax->id,
            ]; 
            $product = Product::create($data);
            return response()->json([
                'message' => 'Product Added Successfully'
            ]);
        }
        else
        {
            return response()->json([
                'message' => 'Only Admin can Add Product'
            ]);
        }
    }

    public function viewProduct()
    {
        $products = Product::all();
        return response()->json($products);
    }

    public function editProduct(Request $req,$id)
    {
        $user = Auth::guard()->user();
        if($user->role_id == 1)
        {
            $product = Product::find($id);
            if($product)
            {
                $product->name = ($req->input('Name'))? $req->input('Name') : $product->name;
                $product->desc = ($req->input('Description'))? $req->input('Description') : $product->desc;
                $product->price = ($req->input('Price'))? $req->input('Price') : $product->price;
                $product->save();
                return response()->json([
                    'message' => 'Product Updated Successfully'
                ]);
            }
            else
            {
                return response()->json([
                    'message' => 'Product Does not Exists'
                ]);
            }
        }
        else
        {
            return response()->json([
                'message' => 'Only Admin can Edit Product'
            ]);
        }
    }

    public function editProductPhoto(Request $req,$id)
    {
        $user = Auth::guard()->user();
        if($user->role_id == 1)
        {
            $product = Product::find($id);
            if($product)
            {
                $validateData = $req->validate([
                    'Image' => 'image',
                ]);
                if($req->file('Image'))
                {
                    if (File::exists(public_path("storage/image/product/$product->p_image"))) 
                    {
                        File::delete(public_path("storage/image/product/$product->p_image"));
                    }
                    $image = time().rand(000000000,999999999).$req->file('Image')->getClientOriginalName();
                    $req->Image->storeAs('image/product',$image,'public');
                    $product->p_image = $image;
                    $product->save();
                    return response()->json([
                        'message' => 'Product Image Updated Successfully'
                    ]);
                }
            }
            else
            {
                return response()->json([
                    'message' => 'Product Does not Exists'
                ]);
            }
        }
        else
        {
            return response()->json([
                'message' => 'Only Admin can Edit Product'
            ]);
        }
    }

    public function deleteProduct($id)
    {
        $user = Auth::guard()->user();
        if($user->role_id == 1)
        {
            $product = Product::find($id);
            if($product)
            {
                $product->delete();
                return response()->json([
                    'message' => 'Product Deleted Successfully'
                ]);
            }
            else
            {
                return response()->json([
                    'message' => 'Product Not Found'
                ]);
            }
        }
        else
        {
            return response()->json([
                'message' => 'Only Admin can Delete Product'
            ]);
        }
    }
}