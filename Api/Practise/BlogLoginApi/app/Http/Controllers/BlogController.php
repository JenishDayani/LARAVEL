<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\DbBlog;
use App\Http\Resources\BlogResource;
use Illuminate\Support\Facades\Auth;


class BlogController extends Controller
{
    public function viewBlog($id)
    {
        if(DbBlog::where('user_id',$id)->exists())
        {
            $data = DbBlog::where('user_id',$id)->get();
            return response()->json($data);
        }
        else
        {
            return response()->json([
                "message" => "Blog Not Found"
            ],404);
        }
    }

    public function viewBlogs()
    {
        $user = Auth::guard()->user();
        $blogs = DbBlog::where('user_id',$user->id)->first();
        $blog = new BlogResource($blogs);
        return $blog;
    }

    public function addBlog(Request $req)
    
    {
            $user = Auth::guard()->user();
            $blog = new DbBlog;
            $blog->user_id = $user->id;
            $blog->title = $req->input('Title');
            $blog->des = $req->input('Description');
            $blog->tag = $req->input('Tag');
            if(is_null($req->blog_image))
            {
                $blog->blog_image = "Blog.jpg";
            }
            else
            {
                $imageName = time().'-'.rand(0000000000,9999999999).'-'.$req->blog_image->getClientOriginalName();
                $req->blog_image->storeAs('image/blog',$imageName);
                $blog->blog_image = $imageName;
            }
            // $blog->blog_image = is_null($req->input('BlogPhoto'))? "Blog.jpg" : $req->input('BlogPhoto');
            $blog->save();
            return response()->json([
                "message" => "Blog Created Successfully"
            ],201);
    }
    
    
    public function editBlog(Request $req,$id)
    {
        if(DbBlog::where('id',$id)->exists())
        {
            $blog = DbBlog::find($id);
            $blog->title = is_null($req->input('Title'))? $blog->title :$req->input('Title');
            $blog->des = is_null($req->input('Description'))? $blog->des :$req->input('Description');
            $blog->tag = is_null($req->input('Tag'))? $blog->tag :$req->input('Tag');
            $blog->blog_image = is_null($req->input('BlogPhoto'))? $blog->blog_image : $req->input('BlogPhoto');
            $blog->save();
            return response()->json([
                "message" => "Blog Updated Successfully"
            ]);
        }
        else
        {
            return response()->json([
                "message" => "Blog Not Found"
            ],404);
        }
    }
    
    public function deleteBlog($id)
    {
        if(DbBlog::where('id',$id)->exists())
        {
            $blog = DbBlog::find($id);
            $blog->delete();
            return response()->json([
                "message" => "Blog Deleted Successfully!"
            ]);
        }
        else
        {
            return response()->json([
                "message" => "Blog Not Found!"
            ],404);
        }
    }


    public function imageStore(Request $req)
    {
        // if($req->image)
        // {
        //     return response()->json([
        //         "message" => "Success"
        //     ]);
        // }
        // else
        // {
        //     return response()->json([
        //         "message" => "Failed"
        //     ]);
        // }

        // $imageName = $req->image->getClientOriginalExtension();
        // $imageName = time().'-'.rand(0000000000,9999999999).'-'.$req->blog_image->getClientOriginalName();
        // $time = time();
        // $abc = $req->blog_image->storeAs('image',$imageName);
        // return response()->json([
            // "message" => "Image",
            // "name" => $imageName,
            // "time" => $time,
            // "number" => rand(0000000000,9999999999)
            // "hello" => $abc
        // ]);


        echo asset('storage/1716182862-yellowGirl.jpg');
    }
}