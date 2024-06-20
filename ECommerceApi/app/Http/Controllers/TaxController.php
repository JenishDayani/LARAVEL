<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tax;

class TaxController extends Controller
{
    public function viewTax()
    {
        $tax = Tax::all();
        return response()->json($tax);
    }

    
    public function addTax(Request $req)
    {
        $user = Auth::guard()->user();
        if($user->role_id == 1)
        {
            $validateData = $req->validate([
                'Category' => 'required',
                'Tax' => 'required|numeric'
            ]);

            $data = [
                'category' => $req->input('Category'),
                'tax_percentage' => $req->input('Tax'),
            ];
            Tax::create($data);
            return response()->json([
                'message' => 'Tax Added Successfully'
            ]);
        }
        else
        {
            return response()->json([
                'message' => 'Only Admin can Add Tax'
            ]);
        }
    }


    public function editTax(Request $req)
    {
        $user = Auth::guard()->user();
        if($user->role_id == 1)
        {
            $validateData = $req->validate([
                'Category' => 'required',
                'Percentage' => 'required|numeric'
            ]);

            if(Tax::where('category',$req->input('Category'))->exists())
            {
                $tax = Tax::where('category',$req->input('Category'))->first();
                $tax->tax_percentage = $req->input('Percentage');
                $tax->save();
                return response()->json([
                    'message' => 'Tax Updated Successfully'
                ]);
            }
            else
            {
                return response()->json([
                    'message' => "Can't find Category"
                ]);
            }
        }
        else
        {
            return response()->json([
                'message' => 'Only Admin can Edit Tax'
            ]);
        }
    }

    
}
