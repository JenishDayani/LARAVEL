@extends('Admin.master')

@section('content')

<h1>Blog</h1>
@if(session()->has('message'))
      <div class="alert alert-{{ session('alert-type', 'info') }}">
        {{ session('message') }}
      </div>
    @endif
<div class="card">
            <div class="card-body">
              <h5 class="card-title">Add Blog</h5>

              <!-- Vertical Form -->
              <form class="row g-3" method="POST" enctype="multipart/form-data">
              @csrf
              @method('POST')

                <div class="col-12">
                  <label for="inputTitle1" class="form-label">Blog Title</label>
                  <input type="text" class="form-control" id="inputTitle1" name="title" value="{{old('title')}}" required>
                </div>
                <div class="col-12">
                  <label for="inputDesc1" class="form-label">Blog Description</label>
                  <textarea class="form-control" style="height: 100px" id="inputDesc1" name="desc" value="{{old('desc')}}" required></textarea>
                </div>
                <div class="col-12">
                  <label for="inputTag1" class="form-label">Tags</label>
                  <input type="text" class="form-control" id="inputTag1" name="tag" value="{{old('tag')}}" required>
                </div>
                <div class="col-12">
                  <label for="inputFile" class="form-label">Image</label>
                  <input type="file" class="form-control" name="blogPhoto">
                </div>
                <div class="text-center">
                  <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                  <button type="reset" class="btn btn-secondary">Reset</button>
                </div>
              </form><!-- Vertical Form -->

            </div>
          </div>

          
@endsection('content')