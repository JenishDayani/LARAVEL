<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DbUser;
use App\Models\Blog;

class BlogController extends Controller
{
    public function addBlog($id,Request $req)
    {
        if(DbUser::where('id',$id)->exists())
        {
            $blog = new Blog;
            $blog->db_user_id = $id;
            $blog->blog_title = $req->input('Title');
            $blog->blog_description = $req->input('Description');
            if(is_null($req->BlogImage))
            {
                $blog->blog_image = "Blog.jpg";
            }
            else
            {
                $imageName = time().'-'.rand(0000000000,9999999999).'-'.$req->BlogImage->getClientOriginalName();
                $req->BlogImage->storeAs('image/blog',$imageName);
                $blog->blog_image = $imageName;
            }
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

    public function addTag(Request $req)
    {
        return "hello";
        // if(Tag::where('tag_name',$req->)->exists())
    }
}
