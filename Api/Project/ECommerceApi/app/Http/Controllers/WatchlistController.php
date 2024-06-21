<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Watchlist;

class WatchlistController extends Controller
{
    public function addWatch($id)
    {
        $user = Auth::guard()->user();
        $product = Product::find($id);
        if($product)
        {
            if(Watchlist::where('user_id',$user->id)->where('product_id',$product->id)->exists())
            {
                return response()->json([
                    'message' => 'Product Already Added'
                ]);
            }
            else
            {
                $data = [
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                ];
    
                Watchlist::create($data);
                return response()->json([
                    'message' => 'Product Added Successfully'
                ]);
            }
        }
        else
        {
            return response()->json([
                'message' => 'Product not Found',
            ]);
        }
    }

    public function viewWatch()
    {
        $user = Auth::guard()->user();
        
        if(Watchlist::where('user_id',$user->id)->exists())
        {
            $products = Watchlist::with('product')->where('user_id',$user->id)->get();
            return $products;
        }
        else
        {
            return response()->json([
                'message' => 'No Products Found',
            ]);
        }
    }

    public function deleteWatch($id)
    {
        $user = Auth::guard()->user();
        if(Product::find($id))
        {
            if(Watchlist::where('user_id',$user->id)->where('product_id',$id)->exists())
            {
                $watch = Watchlist::where('user_id',$user->id)->where('product_id',$id)->first();
                $watch->delete();
                return response()->json([
                    'message' => 'Product removed from Watchlist'
                ]);
            }
            else
            {
                return response()->json([
                    'message' => 'Product Not exists in Watchlist'
                ]);
            }
        }
        else
        {
            return response()->json([
                'message' => 'Product Not Found'
            ]);
        }
    }
}
