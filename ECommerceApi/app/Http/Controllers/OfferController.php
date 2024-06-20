<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Offer;
use App\Models\Product;

class OfferController extends Controller
{
    public function viewOffer()
    {   
        $offers = Offer::all();
        return response()->json($offers);
    }

    public function addOffer(Request $req)
    {
        $user = Auth::guard()->user();
        if($user->role_id==1)
        {
            $validateData = $req->validate([
                'Coupon' => 'required',
                'MinOrder' => 'required|numeric',
                'DiscountPercentage' => 'required|numeric',
                'MaxDiscount' => 'required|numeric',
            ]);

            if(!(Offer::where('coupon',$req->input('Coupon'))->exists()))
            {
                $isProduct = false;
                if($req->input('Product'))
                {
                    $product = Product::find($req->input('Product'));
                    if($product)
                    {
                        $isProduct = true;
                    }
                    else
                    {
                        return response()->json([
                            'message' => 'Product Not Found'
                        ]);
                    }
                }
                $data = [
                    'coupon' => $req->input('Coupon'),
                    'discount' => $req->input('DiscountPercentage'),
                    'min_order' => $req->input('MinOrder'),
                    'max' => $req->input('MaxDiscount'),
                    'product_id' => ($isProduct)?$req->input('Product'):null,
                ];
    
                Offer::create($data);
    
                return response()->json([
                    'message' => 'Offer Added Successfully'
                ]);
            }
            else
            {
                return response()->json([
                    'message' => 'Offer Already Exists'
                ]);
            }
        }
        else
        {
            return response()->json([
                'message' => 'Only Admin can Add Offer'
            ]);
        }
    }
}