
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Blogs - Index</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Blog Home Card -->

  <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://unpkg.com/bs-brain@2.0.4/components/blogs/blog-3/assets/css/blog-3.css"> 


  <!-- Favicons -->
  <link href="{{asset('storage/User')}}/img/favicon.png" rel="icon">
  <link href="{{asset('storage/User')}}/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=EB+Garamond:wght@400;500&family=Inter:wght@400;500&family=Playfair+Display:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{asset('storage/User')}}/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="{{asset('storage/User')}}/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="{{asset('storage/User')}}/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
  <link href="{{asset('storage/User')}}/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="{{asset('storage/User')}}/vendor/aos/aos.css" rel="stylesheet">

  <!-- Template Main CSS Files -->
  <link href="{{asset('storage/User')}}/css/variables.css" rel="stylesheet">
  <link href="{{asset('storage/User')}}/css/main.css" rel="stylesheet">
  

  <!-- Profile CSS Files -->
  <link href="{{asset('storage/User')}}/css/profile.css" rel="stylesheet">

  
  <!-- Profile Edit Card -->

  {{-- <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://unpkg.com/bs-brain@2.0.4/components/blogs/blog-3/assets/css/blog-3.css"> --}}


  <!-- =======================================================
  * Template Name: Blogs
  * Template URL: https://bootstrapmade.com/Blogs-bootstrap-blog-template/
  * Updated: Mar 17 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https:///bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl d-flex align-items-center justify-content-between">

      <a href="{{route('Home')}}" class="logo d-flex align-items-center">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <!-- <img src="{{asset('storage/User')}}/img/logo.png" alt=""> -->
        <h1>Blogs</h1>
      </a>

      <nav id="navbar" class="navbar">
        <ul>
          <li><a href="{{route('Home')}}">Blog</a></li>
          @if(Auth::check())
          <li><a href="{{route('LikeBlogView')}}">Liked Blog</a></li>
          {{-- @endif --}}
          {{-- <li class="dropdown"><a href="category.html"><span>Category</span> <i class="bi bi-chevron-down dropdown-indicator"></i></a>
            <ul>
              <li><a href="search-result.html">Search Result</a></li>
              <li><a href="#">Drop Down 1</a></li>
              <li class="dropdown"><a href="#"><span>Deep Drop Down</span> <i class="bi bi-chevron-down dropdown-indicator"></i></a>
                <ul>
                  <li><a href="#">Deep Drop Down 1</a></li>
                  <li><a href="#">Deep Drop Down 2</a></li>
                  <li><a href="#">Deep Drop Down 3</a></li>
                  <li><a href="#">Deep Drop Down 4</a></li>
                  <li><a href="#">Deep Drop Down 5</a></li>
                </ul>
              </li>
              <li><a href="#">Drop Down 2</a></li>
              <li><a href="#">Drop Down 3</a></li>
              <li><a href="#">Drop Down 4</a></li>
            </ul>
          </li>

          <li><a href="about.html">About</a></li> --}}
          {{-- @if(Session::has('client')) --}}
          <li><a href="{{route('UserViewProfile')}}">Profile</a></li>
          <li><a href="{{route('Logout')}}">Logout</a></li>
          @else
          <li><a href="{{route('Login')}}">Login</a></li>
          @endif
        </ul>
      </nav><!-- .navbar -->

      <div class="position-relative">
        {{-- <a href="#" class="mx-2"><span class="bi-facebook"></span></a>
        <a href="#" class="mx-2"><span class="bi-twitter"></span></a>
        <a href="#" class="mx-2"><span class="bi-instagram"></span></a> --}}

        <a href="#" class="mx-2 js-search-open"><span class="bi-search"></span></a>
        <i class="bi bi-list mobile-nav-toggle"></i>

        <!-- ======= Search Form ======= -->
        <div class="search-form-wrap js-search-form-wrap">
          <form action="#" class="search-form">
            <span class="icon bi-search"></span>
            <input type="text" name="search" placeholder="Search" class="form-control">
            <input type="submit" name="searchSubmit" hidden >
            <button class="btn js-search-close"><span class="bi-x"></span></button>
          </form>
        </div><!-- End Search Form -->

      </div>

    </div>

  </header><!-- End Header -->

  <main id="main">