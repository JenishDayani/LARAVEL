<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\DbUser;
use App\Models\DbBlog;
use App\Http\Resources\BlogResource;

class UserController extends Controller
{
    public function viewUsers()
    {
        $data = DbUser::all();
        return response()->json($data);
    }

    public function viewUsers123()
    {
        $data = DbUser::all();
        $index = -1;
        foreach($data as $jenish)
        {
            $index++;
            $dayani[$index] =[ 
                'id' => $jenish->id,
                'profile' => [
                    'name' => $jenish->name,
                    'email' => $jenish->image
                ],
                'other' => [$jenish->phone,$jenish->email]
            ];
        }
        // echo $dayani;
        return response()->json($dayani);
        // return response()->json($data);
    }

    public function viewUser($id)
    {
        if(DbUser::where('id',$id)->exists())
        {
            $data = DbUser::find($id);
            return response()->json($data);
        }
        else
        {
            return response()->json([
                "message" => "User Not Found"
            ],404);
        }
    }

    public function addUser(Request $req)
    {
        if(!(DbUser::where('email',$req->input('Email'))->exists()))
        {
            $user = new DbUser;
            $user->name = $req->input('Name');
            $user->email = $req->input('Email');
            $user->password = $req->input('Password');
            $user->phone = $req->input('PhoneNumber');
            $user->uuid = Str::uuid()->toString();
            if(is_null($req->ProfilePhoto))
            {
                $user->image = "Profile.jpg";
            }
            else
            {
                $imageName = time().'-'.rand(0000000000,9999999999).'-'.$req->ProfilePhoto->getClientOriginalName();
                $req->ProfilePhoto->storeAs('image/profile',$imageName);
                $user->image = $imageName;
            }
            // $user->image = is_null($req->input('ProfilePhoto'))? "Profile.jpg" : $req->input('ProfilePhoto');
            $user->save();
            return response()->json([
                "message" => "User Created Successfully"
            ],201);
        }
        else{
            return response()->json([
                "message" => "Email is Already Registered"
            ]);
        }
    }
    
    public function editUser(Request $req,$id)
    {
        if(DbUser::where('id',$id)->exists())
        {
            $user = DbUser::find($id);
            $user->name =is_null($req->input('Name'))? $user->name : $req->input('Name');
            $user->email = is_null($req->input('Email'))? $user->email :$req->input('Email');
            $user->password = is_null($req->input('Password'))? $user->password :$req->input('Password');
            $user->phone =is_null($req->input('PhoneNumber'))? $user->phone : $req->input('PhoneNumber');
            $user->image = is_null($req->input('ProfilePhoto'))? $user->image : $req->input('ProfilePhoto');
            $user->save();
            return response()->json([
                "message" => "User Updated Successfully"
            ]);
        }
        else
        {
            return response()->json([
                "message" => "User Not Found"
            ],404);
        }
    }
    
    public function deleteUser($id)
    {
        if(DbUser::where('id',$id)->exists())
        {
            $user = DbUser::with('blog')->find($id);
            $blogs = $user->blog;
            foreach($blogs as $blog)
            {
                $blog->delete();
            }
            $user->delete();    
            // return response()->json($user);
            // $user->blog->delete();
            return response()->json([
                "message" => "User Deleted Successfully!"
            ]);
        }
        else
        {
            return response()->json([
                "message" => "User Not Found!"
            ],404);
        }
    }

    public function blogUser()
    {
        $blogs = DbBlog::select('db_blogs.title','db_blogs.des','db_blogs.blogimage','db_blogs.tag','db_blogs.created_at','db_blogs.updated_at','db_users.name','db_users.phone','db_users.email','db_users.image')
        ->join('db_users','db_users.id','db_blogs.db_user_id')
        // ->where('db_user_id',$id)
        ->get();

        $index=-1;
        foreach($blogs as $blog)
        {
            if($blog->tag!=null)
            {
                $tags = explode(',', $blog->tag);
            }
            else{
                $tags=null;
            }
            $index++;
            $data[$index] = [
                'title'=> $blog->title,
                'create'=> $blog->created_at,
                'update'=> $blog->updated_at,
                'des' => $blog->des,
                'tags' => $tags,
                'img' => $blog->blogimage,
                'userInfo'=> [
                    'name' => $blog->name,
                    'phoneNo' => $blog->phone,
                    'email' => $blog->email,
                    'profilePicture' => $blog->image
                ]
            ];
        }

        return response()->json($data);
    }

    public function userBlog()
    {
        $data = DbBlog::with('user')->get();
        $data1 = BlogResource::collection($data);
        return response()->json($data1);
    }
}



// {
//     title: "sdasdsa",
//     des: "csdcs",
//     tags: ["scxC" , "csxcx" , "Adada" , "ADadA"],
//     img: "https://www.example.com/example",
//     userInfo: {
//         name; "sxs",
//         phoneNo: "",
//         email: "",
//         profilePicture: ""
//     }
// }