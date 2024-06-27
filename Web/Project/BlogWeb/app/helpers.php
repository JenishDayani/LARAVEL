<?php

use App\Models\Blogs;
use App\Models\Profile;

// if($errors)
// {
//   if($errors->any())
//   {
//     foreach ($errors->all() as $error)
//     {
//       echo "<div id='slider-alert' class='bg-danger slider-alert text-white px-4 py-2 rounded shadow-lg'>";
//       echo $error;
//       echo "</div>";
//     }
//   }
// }


  if(!function_exists('isUser'))
  {
      function isUser()
      {
        return Auth::check() && Auth::user()->role == 0;
      }
  }

  if(!function_exists('recentBlogsGet'))
  {
    function recentBlogsGet()
    {
      return Blogs::latest()->take(4)->get();
    }
  }

  if(!function_exists('updateProfileInformation'))
  {
    function updateProfileInformation($req)
    {
      $user = Auth::user();
      $profile = Profile::where('user_id',$user->id)->first();
      $user->name = $req['name'];
      $user->email = $req['email'];

      $profile->address = $req['address'];
      $profile->mobile = $req['mobile'];
      $profile->gender = $req['gender'];

      $user->save();
      $profile->save();
      // return response()->json([
      //   'Request' => $req,
      //   'User' => $user,
      //   'Profile' => $profile,
      // ]);
    }
  }