<?php

use App\Models\Blogs;

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