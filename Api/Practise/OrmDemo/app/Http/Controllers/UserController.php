<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\BlogResource;

use App\Models\Member;
use App\Models\Group;
use App\Models\DbUser;
use App\Models\Blog;
use App\Models\Customer;
use App\Models\Product;

class UserController extends Controller
{
    public function oneToOne()
    {
        // $data = Member::find(1)->getGroup;
        // $data = Member::with('group')
        // ->where('member_id',1)
        // ->get();

        $data = Member::with('group')->get();
        // $data = Group::with('member')->get();
        return response()->json($data);
    }

    public function oneToMany()
    {
        $data = DbUser::with('getBlogs')->get();
        // $data = Blog::with('user')->get();
        return response()->json($data);
    }

    public function manyToMany()
    {
        // $data = Product::with('customer')->get();
        // $data = Customer::with('product')->where('age',20)->get();
        // $data = Customer::with('product')->where('price',20)->get();
        $data = Customer::with('productPrice500')->get();
        
        
        // return response()->json([
        //     "message" => "Hello"
        // ]);
        return response()->json($data);
    }


    public function jenish()
    {
        // $data = DbUser::with('blog')->get();
        // $data = Blog::with('user')->get();
        $data = Blog::with(['tag','user'])->get();
        $data1 = BlogResource::collection($data);
        return response()->json($data1);
    }
}