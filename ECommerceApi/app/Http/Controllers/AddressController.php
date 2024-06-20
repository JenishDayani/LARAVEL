<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\Address;
use App\Models\Product;
use App\Models\Offer;
use App\Models\Order;
use App\Models\Cart;

class AddressController extends Controller
{
    public function addAddress(Request $req)
    {
        $user = Auth::guard()->user();
    
        $validateData = $req->validate([
            'Address',
        ]);

        $data = [
            'user_id' => $user->id,
            'address' => $req->input('Address'),
        ];

        Address::create($data);
        return response()->json([
            'message' => 'Address Added Successfully',
        ]);
    }

    public function viewAddress(Request $req)
    {
        $user = Auth::guard()->user();

        $add = Address::where('user_id',$user->id)->get();
        return response()->json($add);
    }

    public function editAddress(Request $req,$id)
    {
        if($req->input('Address'))
        {
            $user = Auth::guard()->user();
            $add = Address::find($id);
            if($add)
            {
                if($add->user_id == $user->id)
                {
                    $add->address = $req->input('Address');
                    $add->save();
                    return response()->json([
                        'message' => 'Address Updated Successfully'
                    ]);
                }
                else
                {
                    return response()->json([
                        'message' => 'Thats not your Address'
                    ]);
                }
            }
            else
            {
                return response()->json([
                    'message' => 'Address not Found'
                ]);
            }
        }
    }


    public function deleteAddress($id)
    {
        $user = Auth::guard()->user();
        $add = Address::find($id);
        if($add)
        {
            if($add->user_id == $user->id)
            {   
                $add->delete();

                return response()->json([
                    'message' => 'Address Deleted Successfully'
                ]);
            }
            else
            {
                return response()->json([
                    'message' => 'You cant delete Other Address'
                ]);
            }
        }
        else
        {
            return response()->json([
                'message' => 'Address not Found'
            ]);
        }
    }

    public function createOrder(Request $request)
{
    // Extracting items, authenticated user, and generating order ID
    $items = $request->items;
    $user = Auth::guard()->user();
    $order_id = Str::uuid()->toString();
    
    // Finding address based on input
    $address = Address::find($request->input('Address'));

    // Check if address exists and belongs to the user
    if ($address) {
        if ($address->user_id == $user->id) {
            // Calculate totals and prepare order details
            list($total, $totalTax, $payAmount, $orderDetails) = $this->calculateOrderTotals($items, $order_id);

            // Apply offer if coupon code is provided
            $discount = $this->applyOffer($request, $payAmount);

            // Construct order data
            $orderData = [
                "order_id" => $order_id,
                "user_id" => $user->id,
                "total" => $total,
                "total_tax" => $totalTax,
                "pay_amount" => $payAmount,
                "d_address" => $address->Address,
                "offer_id" => ($request->input('CouponCode')) ? $offer->id : null,
                "discount_amount" => $discount,
                "final_amount" => $payAmount - $discount,
            ];

            // Create order and insert into cart
            $this->placeOrder($orderData, $orderDetails);

            return response()->json([
                'message' => 'Order Successfully Placed',
                'OrderId' => $order_id,
            ]);
        } else {
            return response()->json([
                'message' => 'Unauthorized Address'
            ]);
        }
    } else {
        return response()->json([
            'message' => 'Address Not Found'
        ]);
    }
}

private function calculateOrderTotals($items, $order_id)
{
    $total = $totalTax = $payAmount = 0;
    $orderDetails = [];
    $currentTime = now();

    foreach ($items as $item) {
        $product = Product::find($item['product_id']);
        $amount = $product->price * $item['qty'];
        $tax = $amount * 0.18;
        $totalAmount = $amount + $tax;

        $total += $amount;
        $totalTax += $tax;
        $payAmount += $totalAmount;
        $orderDetails[] = [
            "order_id" => $order_id,
            "product_id" => $product->id,
            "qty" => $item['qty'],
            "amount" => $amount,
            "tax" => $tax,
            "total_amount" => $totalAmount,
            "created_at" => $currentTime,
            "updated_at" => $currentTime,
        ];
    }

    return [$total, $totalTax, $payAmount, $orderDetails];
}

private function applyOffer($request, $payAmount)
{
    $discount = 0;

    if ($request->input('CouponCode')) {
        if (Offer::where('coupon', $request->input('CouponCode'))->exists()) {
            $offer = Offer::where('coupon', $request->input('CouponCode'))->first();
            
            if ($offer->min_order <= $payAmount) {
                $discount = $payAmount * $offer->discount / 100;
                if ($discount > $offer->max) {
                    $discount = $offer->max;
                }
            } else {
                $remainingAmount = $offer->min_order - $payAmount;
                return response()->json([
                    'message' => "You have to order $remainingAmount more to use this offer"
                ]);
            }
        } else {
            return response()->json([
                'message' => 'Enter Valid Coupon Code'
            ]);
        }
    }

    return $discount;
}

private function placeOrder($orderData, $orderDetails)
{
    Order::create($orderData);
    Cart::insert($orderDetails);
}

}
