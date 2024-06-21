<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FacebookComments;
use App\Models\FacebookPosts;

class FacebookController extends Controller
{
    public function createComment($id,Request $r)
    {
        if(FacebookPosts::where('id',$id)->exists())
        {
            $comment = new FacebookComments;
            $comment->post_id = $id;
            $comment->comments = $r->input('Comment');
            $comment->save();
            return response()->json([
                "message" => "Comment Added"
            ]);
        }else{
            return response()->json([
                "message" => "Post not Found"
            ],404);
        }
    }

    public function viewComment($id)
    {
        if(FacebookPosts::where('id',$id)->exists())
        {
            if(FacebookComments::where('post_id',$id)->exists())
            {
                $comment = FacebookComments::select('facebook_comments.comments')
                ->join('facebook_posts','facebook_posts.id','facebook_comments.post_id')
                ->where('post_id',$id)
                ->get();
                return response()->json($comment);
            }
            else
            {
                return response()->json([
                    "message" => "Comments Not Found"
                ],404);
            }
        }
        else
        {
            return response()->json([
                "message" => "Post Not Found"
            ],404);
        }
    }

    public function countComment($id)
    {
        if(FacebookPosts::where('id',$id)->exists())
        {
            if(FacebookComments::where('post_id',$id)->exists())
            {
                $comment = FacebookComments::select('facebook_comments.comments')
                ->join('facebook_posts','facebook_posts.id','facebook_comments.post_id')
                ->where('post_id',$id)
                ->count();
                return response()->json($comment);
            }
            else
            {
                return response()->json([
                    "message" => "Comments Not Found"
                ],404);
            }
        }
        else
        {
            return response()->json([
                "message" => "Post Not Found"
            ],404);
        }
    }






    public function demo($id,Request $r)
    {
        if(FacebookPosts::where('id',$id)->exists())
        {
            if($r->input('Comment'))
            {
                $comment = new FacebookComments;
                $comment->post_id = $id;
                $comment->comments = $r->input('Comment');
                $comment->save();
                return response()->json([
                    "message" => "Comment Added"
                ]);
            }
            else
            {
                return response()->json([
                    "message" => "Enter Comment"
                ]);
            }
        }else{
            return response()->json([
                "message" => "Post not Found"
            ],404);
        }
    }
}