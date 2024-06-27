@extends('User.master')

@section('content')

@if(session()->has('message'))
      <div class="alert alert-{{ session('alert-type', 'info') }}">
        {{ session('message') }}
      </div>
    @endif
{{-- <div class="card">

            <div class="card-body">
              <h5 class="card-title">Edit User</h5>

              <!-- Vertical Form -->
              <form class="row g-3" method="POST" enctype="multipart/form-data">
              @csrf
              @method('POST')

                <div class="col-12">
                  <label for="inputTitle1" class="form-label">Name</label>
                  <input type="text" class="form-control" id="inputTitle1" name="title" value="Name" required>
                </div>
                <div class="col-12">
                  <label for="inputTag1" class="form-label">Tags</label>
                  <input type="text" class="form-control" id="inputTag1" name="tag" value="Email" required>
                </div>
                <div class="col-12">
                  <label for="inputFile" class="form-label">Image</label>
                  <input type="file" class="form-control" name="blogPhoto">
                </div>
                <div class="text-center">
                  <button type="submit" class="btn btn-primary" name="update">Update</button>
                  <button type="reset" class="btn btn-secondary">Reset</button>
                </div>
              </form><!-- Vertical Form -->

            </div>
</div> --}}


<div class="card container">
            <div class="card-body">
              <h5 class="card-title">Edit User</h5>

              <!-- Horizontal Form -->
              <form method="POST" enctype="multipart/form-data">
                @csrf
                @method('POST')
                <div class="row mb-3">
                  <label for="inputName" class="col-sm-2 col-form-label">Your Name</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="inputName" value="{{$profile->name}}" name="name">
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                  <div class="col-sm-10">
                    <input type="email" class="form-control" id="inputEmail" value="{{$profile->email}}" name="email">
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="inputAddress" class="col-sm-2 col-form-label">Address</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="inputAddress" value="{{$profile->profiles->address}}" name="address">
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="inputMobile" class="col-sm-2 col-form-label">Mobile</label>
                  <div class="col-sm-10">
                    <input type="number" maxlength="10" class="form-control" name="mobile" value="{{$profile->profiles->mobile}}" id="inputMobile">
                  </div>
                </div>
                <fieldset class="row mb-3">
                  <legend class="col-form-label col-sm-2 pt-0">Gender</legend>
                  <div class="col-sm-10">
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="gender" id="Gender1" value="Male" {{ $profile->profiles->gender == 'Male' ? 'checked' : null }}>
                      <label class="form-check-label" for="Gender1">
                        Male
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="gender" id="Gender2" value="Female" {{ $profile->profiles->gender == 'Female' ? 'checked' : null }}>
                      <label class="form-check-label" for="Gender2">
                        Female
                      </label>
                    </div>
                  </div>
                </fieldset>
                <div class="row mb-3">
                  <label for="inputFile" class="col-sm-2 col-form-label">Profile</label>
                  <div class="col-sm-10">
                    <input type="file" class="form-control" name="profile">
                  </div>
                </div>
                <div class="text-center">
                  <input type="submit" name="update" class="btn btn-primary" value="Update">
                  <button type="reset" class="btn btn-secondary">Reset</button>
                </div>
              </form><!-- End Horizontal Form -->

            </div>
          </div>

          
@endsection('content')