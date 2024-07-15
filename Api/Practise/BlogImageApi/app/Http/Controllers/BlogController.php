<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Blog;
use App\Http\Resources\BlogResource;
use Illuminate\Support\Facades\File; 


class BlogController extends Controller
{
    public function addBlog(Request $req)
    {
        $validateData = $req->validate([
            'title' => 'required',
            'description' => 'required',
            'tags' => 'required',
            'blogImage' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $user = Auth::user();

        $title = $req->title;
        $description = $req->description;
        $tags = $req->tags;
        $blogImage = time(). '-' . rand(111111111111111,999999999999999) . '-' . $req->blogImage->getClientOriginalName();
        
        $req->blogImage->move(public_path('storage/images/Blog'), $blogImage);
        
        $data = [
            'user_id' => $user->id,
            'title' => $title,
            'description' => $description,
            'tags' => $tags,
            'blog_image' => $blogImage,
        ];

        Blog::create($data);

        return response()->json([
            'status' => 'Success',
            'message' => 'Blog Added Successfully',
        ],201);
    }

    public function viewBlog()
    {
        $user = Auth::guard()->user();
        $blogs = Blog::where('user_id',$user->id)->get();
        if($blogs->isEmpty())
        {
            return response()->json([
                'status' => 'Error',
                'message' => 'No Blog Found',
            ],404);
        }
        else
        {
            return response()->json([
                'status' => 'Success',
                'userBlogs' => BlogResource::collection($blogs),
            ]);
        }
    }

    public function viewAllBlog()
    {
        $blogs = Blog::with('user')->get();
        return response()->json([
            'status' => 'Success',
            'blogs' => BlogResource::collection($blogs),
            // 'blogs' => $blogs,
        ]);
    }

    public function editBlog(Request $req,$id)
    {
        $user = Auth::guard()->user();
        $blog = Blog::find($id);
        if($blog)
        {
            if($blog->user_id == $user->id)
            {
                // $blog->title = is_null($req->title)? $blog->title : $req->title;
                // $blog->description = is_null($req->description)? $blog->description : $req->description;
                // $blog->tags = is_null($req->tags)? $blog->tags : $req->tags;
                // $blog->save();
                // return response()->json([
                //     'status' => 'Success',  
                //     'message' => 'Blog Updated Successfully',
                // ]);


                $title = is_null($req->title)? $blog->title : $req->title;
                $description = is_null($req->description)? $blog->description : $req->description;
                $tags = is_null($req->tags)? $blog->tags : $req->tags;
                
                $data =[
                    'title' => $title,
                    'description' => $description,
                    'tags' => $tags,
                ];

                Blog::whereId($id)->update($data);

                return response()->json([
                    'status' => 'Success',  
                    'message' => 'Blog Updated Successfully',
                ]);
            }
            else
            {
                return response()->json([
                    'status' => 'Error',
                    'message' => 'Unauthorized Access',
                ],401);
            }
        }
        else
        {
            return response()->json([
                'status' => 'Error',
                'message' => 'Blog Not Found',
            ],404);
        }
    }

    public function editBlogPhoto(Request $req,$id)
    {
        $validateData = $req->validate([
            'blogImage' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]) ;

        $user = Auth::guard()->user();
        $blog = Blog::find($id);
        if($blog)
        {
            if($blog->user_id == $user->id)
            {
                $blogImage = time() . '-' . rand(111111111111111,999999999999999) . '-' . $req->file('blogImage')->getClientOriginalName();

                if(File::exists(public_path("storage/images/Blog/$blog->blog_image")))
                {
                    File::delete(public_path("storage/images/Blog/$blog->blog_image"));
                }

                $req->file('blogImage')->move(public_path('storage/images/Blog'),$blogImage);

                // $blog->blog_image = $blogImage;
                // $blog->save();

                $data = [
                    'blog_image' => $blogImage,
                ];

                Blog::whereId($id)->update($data);

                return response()->json([
                    'status' => 'Success',
                    'message' => 'Blog Image Updated Successfully',
                ]);
            }
            else
            {
                return response()->json([
                    'status' => 'Error',
                    'message' => 'Unauthorized Access', 
                ],401);
            }
        }
        else
        {
            return response()->json([
                'status' => 'Error',
                'message' => 'Blog Not Found',
            ],404);
        }
    }

    public function deleteBlog($id)
    {
        $user = Auth::guard()->user();
        $blog = Blog::find($id);
        if($blog)
        {
            if($blog->user_id == $user->id)
            {
                if(File::exists(public_path("storage/images/Blog/$blog->blog_image")))
                {
                    File::delete(public_path("storage/images/Blog/$blog->image"));
                }
                $blog->delete();

                return response()->json([
                    'status' => 'Success',
                    'message' => 'Blog Deleted Successfully',
                ]);
            }
            else
            {
                return response()->json([
                    'status' => 'Error',
                    'message' => 'Unauthorized Access',
                ],401);
            }
        }
        else
        {
            return response()->json([
                'status' => 'Error',
                'message' => 'Blog Not Found',
            ],404);
        }
    }


    public function viewTagBlog(Request $req)
    {
        $tag = $req->tag;
        $blogs = Blog::where('tags', 'LIKE' ,"%$tag%")->get();

        if($blogs->isEmpty())
        {
            return response()->json([
                'status' => 'Error',
                'message' => 'No Blog Found',
            ],404);
        }

        return BlogResource::collection($blogs);
    }

}