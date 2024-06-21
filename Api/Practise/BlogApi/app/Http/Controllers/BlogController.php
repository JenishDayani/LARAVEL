<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\DbUser;
use App\Models\DbBlog;

class BlogController extends Controller
{
    public function viewBlog($id)
    {
        if(DbBlog::where('db_user_id',$id)->exists())
        {
            $data = DbBlog::where('db_user_id',$id)->get();
            return response()->json($data);
        }
        else
        {
            return response()->json([
                "message" => "Blog Not Found"
            ],404);
        }
    }

    public function addBlog(Request $req,$id)
    
    {
        if(DbUser::where('id',$id)->exists())
        {
            $blog = new DbBlog;
            $blog->db_user_id = $id;
            $blog->uuid = Str::uuid()->toString();
            $blog->title = $req->input('Title');
            $blog->des = $req->input('Description');
            $blog->tag = $req->input('Tag');
            if(is_null($req->BlogImage))
            {
                $blog->blogimage = "Blog.jpg";
            }
            else
            {
                $imageName = time().'-'.rand(0000000000,9999999999).'-'.$req->BlogImage->getClientOriginalName();
                $req->BlogImage->storeAs('image/blog',$imageName);
                $blog->blogimage = $imageName;
            }
            // $blog->blogimage = is_null($req->input('BlogPhoto'))? "Blog.jpg" : $req->input('BlogPhoto');
            $blog->save();
            return response()->json([
                "message" => "Blog Created Successfully"
            ],201);
        }
        else
        {
            return response()->json([
                "message" => "User Not Found"
            ],404);
        }
    }
    
    
    public function editBlog(Request $req,$id)
    {
        if(DbBlog::where('id',$id)->exists())
        {
            $blog = DbBlog::find($id);
            $blog->title = is_null($req->input('Title'))? $blog->title :$req->input('Title');
            $blog->des = is_null($req->input('Description'))? $blog->des :$req->input('Description');
            $blog->tag = is_null($req->input('Tag'))? $blog->tag :$req->input('Tag');
            $blog->blogimage = is_null($req->input('BlogPhoto'))? $blog->blogimage : $req->input('BlogPhoto');
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
        // $imageName = time().'-'.rand(0000000000,9999999999).'-'.$req->BlogImage->getClientOriginalName();
        // $time = time();
        // $abc = $req->BlogImage->storeAs('image',$imageName);
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