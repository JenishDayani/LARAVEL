<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\Address;
use App\Models\Offer;
use App\Models\User;
use App\Models\Tax;
use App\Http\Resources\OrderResource;

class OrderController extends Controller
{    

    // public function createOrder(Request $req)
    // {
    //     $items = $req->items;
    //     $user = Auth::guard()->user();
    //     $order_id = Str::uuid()->toString();
    //     $add = Address::find($req->input('Address'));
        
    //     if($add)
    //     {
    //         if($add->user_id == $user->id)
    //         {
    //             $total = $totalTax = $payAmount = $disc = 0;
    //             $data = [];
    //             $samay = now();
    //             $productOffer = false;
    //             $maxAmount = 0;

    //             // return count($items);

    //             foreach ($items as $item) 
    //             {
    //                 $product = Product::find($item['product_id']);
    //                 $findTax = Tax::find($product->tax_id);
    //                 $amount = $product->price * $item['qty'];
    //                 $tax = (($amount * $findTax->tax_percentage)/100);
    //                 $totalAmount = $amount + $tax;

    //                 if(Offer::where('product_id',$product->id)->exists())
    //                 {
    //                     $productOffer = true;
    //                     $offer = Offer::where('product_id',$product->id)->first();
    //                     if($offer->discount == 100)
    //                     {
    //                         if(count($items)<2)
    //                         {
    //                             return response()->json([
    //                                 'message' => 'Add One More Item to Avail This Buy One Get One Offer'
    //                             ]);
    //                         }
    //                         else
    //                         {
    //                             return response()->json([
    //                                 'message' => 'Hurray'
    //                             ]);
    //                         }
    //                         if($maxAmount<$amount)
    //                         {
    //                             $maxAmount = $amount;
    //                         }
    //                     }
    //                     $disc = $amount * $offer->discount / 100;
    //                 }

    //                 $total += $amount;
    //                 $totalTax += $tax;
    //                 $payAmount += $totalAmount;
    //                 $data[] = [
    //                     "order_id" => $order_id,
    //                     "product_id" => $product->id,
    //                     "qty" => $item['qty'],
    //                     "amount" => $amount,
    //                     "tax" => $tax,
    //                     "total_amount" => $totalAmount,
    //                     "created_at" => $samay,
    //                     "updated_at" => $samay,
    //                 ];
    //             }
    //             return response()->json([
    //                 'message' => $maxAmount
    //             ]);
    //             if(!$productOffer)
    //             {
    //                 if($req->input('CouponCode'))
    //                 {
    //                     if(Offer::where('coupon',$req->input('CouponCode'))->exists())
    //                     {
    //                         $offer = Offer::where('coupon',$req->input('CouponCode'))->first();
                            
    //                         if($offer->min_order <= $totalAmount)
    //                         {
    //                             $disc = $totalAmount * $offer->discount / 100;
    //                             if($disc > $offer->max)
    //                             {
    //                                 $disc = $offer->max;
    //                             }
    //                         }
    //                         else
    //                         {
    //                             $fail = $offer->min_order - $totalAmount;
    //                             return response()->json([
    //                                 'message' => "You have to order $fail more to use this offer"
    //                             ]);
    //                         }
                            
    //                     }else
    //                     {
    //                         return response()->json([
    //                             'message' => 'Enter Valid Coupon Code'
    //                         ]);
    //                     }
    //                 }
    //             }
                
                
    //             $orderData = [
    //                 "order_id" => $order_id,      
    //                 "user_id" => $user->id,
    //                 "total" => $total,
    //                 "total_tax" => $totalTax,
    //                 "pay_amount" => $payAmount,
    //                 "d_address" => $add->Address,
    //                 "offer_id" => ($req->input('CouponCode'))?$offer->id:null,
    //                 "discount_amount" => $disc,
    //                 "final_amount" => $payAmount - $disc,
    //             ];
    //             Order::create($orderData);
    //             Cart::insert($data);
    //             return response()->json([
    //                 'message' => 'Order Successfully Placed',
    //                 'OrderId' => $order_id,
    //             ]);
    //         }
    //         else
    //         {
    //             return response()->json([
    //                 'message' => 'Unauthorized Access!!'
    //             ]);
    //         }
    //     }
    //     else
    //     {
    //         return response()->json([
    //             'message' => 'Address Not Found'
    //         ]);
    //     }


    // }


    public function createOrder(Request $req)
    {
        $user = Auth::guard()->user();
        $items = $req->items;
        $productIds = [];


        $address = User::with(['add' => function ($query) use ($req) {
                $query->where('id', $req->Address);
            }])
            ->find($user->id);
        if(count($address->add) == 0)
        {
            return response()->json([
                'message' => 'No Address Found'    
            ]);
        }
        // return $address->add[0]->Address;
        $order_id = Str::uuid()->toString();

        foreach ($items as $item) {
            $productIds [] = $item['product_id'];
        }
        // $products = Product::with(['tax','offer'])->find([1,4,2]);
        $products = Product::with(['tax','offer'])->find($productIds);
        // return $productIds;

        // foreach($products as $product)
        // {
        //     $amount = $product->price * 
        // }

        $totalAmount = $totalDisc = $totalDiscAmount = $totalTax = $totalFinalAmount = $BillAmount = 0;
        // $totalAmount = $totalTax = $disc = $payAmount = 0;
        $data = [];
        $currentTime = now();
        $productOffer = false;
        for($i =0 ; $i < count($items); $i++)
        {
            // echo $items[$i]['qty'];

            $amount =  $products[$i]->price * $items[$i]['qty'];
            $disc = 0;
            // echo $products[$i]->offer;
            if($products[$i]->offer->isNotEmpty())
            {
                $productOffer = true;
                // if($products[$i]->offer->discount)
                // echo "Offer Available\n {{$products[$i]->offer[0]['discount']}} \n";
                // echo $products[$i]->offer->discount;
                
                $disc = (($amount * $products[$i]->offer[0]['discount'])/100);
            }
            $discAmount = $amount - $disc;
            $tax = (($discAmount * $products[$i]->tax->tax_percentage)/100);
            $finalAmount = $discAmount + $tax;



            $totalAmount += $amount;
            $totalDisc += $disc;
            $totalDiscAmount += $discAmount;
            $totalTax += $tax;
            $totalFinalAmount += $finalAmount;
            $BillAmount += $totalFinalAmount;

            $data[] = [
                "order_id" => $order_id,
                "product_id" => $products[$i]->id,
                "qty" => $items[$i]['qty'],
                "amount" => $amount,
                "tax" => $tax,
                "total_amount" => $finalAmount,
                "created_at" => $currentTime,
                "updated_at" => $currentTime,


                // "discount" => $disc,
                // "billAmount" => $BillAmount,
            ];

        }


        if(!$productOffer)
        {

            $totalAmount = $totalDisc = $totalDiscAmount = $totalTax = $totalFinalAmount = $BillAmount = 0;
            $data = [];
            $currentTime = now();

            if($req->input('CouponCode'))
            {
                if(Offer::where('coupon',$req->input('CouponCode'))->exists())
                {
                    $offer = Offer::where('coupon',$req->input('CouponCode'))->first();

                    for($i=0 ; $i<count($items) ; $i++)
                    {
                        $amount = $products[$i]->price * $items[$i]['qty'];
                        $disc = (($amount * $offer->discount)/100);
                        $discAmount = $amount - $disc;
                        $tax = (($discAmount * $products[$i]->tax->tax_percentage)/100);
                        $finalAmount = $discAmount + $tax;


                        $totalAmount += $amount;
                        $totalDisc += $disc;
                        $totalDiscAmount += $discAmount;
                        $totalFinalAmount += $finalAmount;
                        $totalTax += $tax;
                        $BillAmount += $totalFinalAmount;
                    }


                    if($offer->min_order > $totalAmount)
                    {
                        $remainingAmount = $offer->min_order - $totalAmount;
                        return response()->json([
                            'message' => "You have to order of $remainingAmount to use this Offer"
                        ]);
                    }
                }
                else
                {
                    return response()->json([
                        'message' => 'No Coupon Found'
                    ]);
                }
            }
            else
            {

            }
        }
        return response()->json($data);



        $orderData = [
            "order_id" => $order_id,
            "user_id" => $user->id,
            "d_address" => $address->add[0]->Address,
            "total" => $totalAmount,
            "total_tax" => $totalTax,
            "pay_amount" => $totalDiscAmount,
            "discount_amount" => $totalDisc,
            "final_amount" => $BillAmount
        ];

        Order::create($orderData);
        Cart::insert($data);
        return response()->json([
            'message' => 'Order Successfully Placed !!',
            'orderId' => $order_id,
        ]);
    }


    public function viewOrder()
    {
        $user = Auth::guard()->user();
        $orders = Order::with(['cart','cart.product'])
                ->where('user_id',$user->id)
                ->get();
        return response()->json(OrderResource::collection($orders));
    }
}