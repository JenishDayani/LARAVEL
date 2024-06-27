@extends('Admin.master')

@section('content')




<!-- Floating error container -->
{{-- <div class="floating-error" id="floating-error">
    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif
</div> --}}
{{-- @if ($errors->any())
    @foreach ($errors->all() as $error)
        <div id="slider-alert" class="bg-danger slider-alert text-white px-4 py-2 rounded shadow-lg">
                {{ $error }}
        </div>
    @endforeach
@endif --}}



<h1>User </h1>
@if(session()->has('message'))
      <div class="alert alert-{{ session('alert-type', 'info') }}">
        {{ session('message') }}
      </div>
    @endif
<div class="card">
            <div class="card-body">
              <h5 class="card-title">Add User</h5>

              <!-- Vertical Form -->
              <form class="row g-3" method="POST" enctype="multipart/form-data">
              @csrf
              @method('POST')

                <div class="col-12">
                  {{-- @error('name')
                        <div class="alert alert-danger">{{ $message }}</div>
                  @enderror --}}

                  <label for="inputNanme4" class="form-label">User Name</label>
                  <input type="text" class="form-control" id="inputNanme4" name="name" value="{{old('name')}}" >
                </div>
                <div class="col-12">
                  {{-- @error('email')
                      <div class="alert alert-danger">{{ $message }}</div>
                  @enderror --}}

                  <label for="inputEmail4" class="form-label">Email</label>
                  <input type="email" class="form-control" id="inputEmail4" name="email" value="{{old('email')}}" >
                </div>
                <div class="col-12">
                  <label for="inputAddress" class="form-label">Address</label>
                  <input type="text" class="form-control" id="inputAddress" name="address" value="{{old('address')}}" >
                </div>
                <div class="col-12">
                  <label for="inputMobile" class="form-label">Mobile</label>
                  <input type="number" class="form-control" id="inputMobile" name="mobile" value="{{old('mobile')}}" >
                </div>

                <fieldset class="col-12">
                      <legend class="col-md-4 col-lg-3 col-form-label">Gender</legend>
                      <div class="col-md-8 col-lg-9">
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="gender" id="Gender1" value="Male" {{ old('gender') == 'Male' ? 'checked' : null }}>
                          <label class="form-check-label" for="Gender1">
                              Male
                          </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="gender" id="Gender2" value="Female" {{  old('gender')  == 'Female' ? 'checked' : null }}>
                            <label class="form-check-label" for="Gender2">
                                Female
                            </label>
                        </div>
                      </div>
                    </fieldset>



                <div class="col-12">
                  <label for="inputPassword4" class="form-label">Password</label>
                  <input type="password" class="form-control" id="inputPassword4" name="password" >
                </div>
                <div class="col-12">
                  <label for="inputFile" class="form-label">Profile</label>
                  <input type="file" class="form-control" name="profilePhoto">
                </div>
                <div class="text-center">
                  <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                  <button type="reset" class="btn btn-secondary">Reset</button>
                </div>
              </form><!-- Vertical Form -->

            </div>
          </div>

@endsection('content')