<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FacebookPosts;
use App\Models\FacebookComments;

class FacebookPostsController extends Controller
{
    public function createPost(Request $r)
    {
        $rating = (int)$r->input('Rating');
        if($rating >0 && $rating <6)
        {
            $posts = New FacebookPosts;
            $posts->title = $r->input('Title');
            $posts->description = $r->input('Description');
            $posts->rating = $rating;
            $posts->save();
            return response()->json([
                "message" => "Post Added Successfully!!"
            ]);
        }
        else{
            return response()->json([
                "message" => "Enter Rating between 1 to 5"
            ]);
        }
    }

    public function seePosts()
    {
        $allposts = FacebookPosts::all();
        return response()->json($allposts);
    }

    public function seePost($id)
    {
        if(FacebookPosts::where('id',$id)->exists())
        {
            $p = FacebookPosts::find($id);
            return response()->json($p);
        }
        else{
            
            return response()->json([
                "message" => "Post not Found"
            ],400);
        }
    }

    public function deletePosts($id)
    {
        if(FacebookPosts::where('id',$id)->exists())
        {
            $post = FacebookPosts::find($id);
            $post->delete();
            if(FacebookComments::where('post_id',$id)->exists())
            {
                $comments = FacebookComments::where('post_id',$id)->get();
                foreach($comments as $comment)
                {                 
                    $comment->delete();
                }
            }
            return response()->json([
                "message" => "Post Deleted Successfully!!"
            ]);
        }
        else
        {
            return response()->json([
                "message" => "Post Not Found!!"
            ]);
        }
    }



    public function cPost(Request $r)
    {
        if($r->input('Title') && $r->input('Description') && $r->input('Rating'))
        {
            $rating = (int)$r->input('Rating');
            if($rating >0 && $rating <6)
            {
                $posts = New FacebookPosts;
                $posts->title = $r->input('Title');
                $posts->description = $r->input('Description');
                $posts->rating = $rating;
                $posts->save();
                return response()->json([
                    "message" => "Post Added Successfully!!"
                ]);
            }
            else{
                return response()->json([
                    "message" => "Enter Rating between 1 to 5"
                ]);
            }
        }
        else
        {
            return response()->json([
                "message" => "Enter All Details" 
            ]);
        }
        
        
        
    }
}