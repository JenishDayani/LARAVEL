@extends('Admin.master')

@section('content')

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
                  <label for="inputNanme4" class="form-label">User Name</label>
                  <input type="text" class="form-control" id="inputNanme4" name="name" value="{{old('name')}}" required>
                </div>
                <div class="col-12">
                  <label for="inputEmail4" class="form-label">Email</label>
                  <input type="email" class="form-control" id="inputEmail4" name="email" value="{{old('email')}}" required>
                </div>
                <div class="col-12">
                  <label for="inputAddress" class="form-label">Address</label>
                  <input type="text" class="form-control" id="inputAddress" name="address" value="{{old('address')}}" required>
                </div>
                <div class="col-12">
                  <label for="inputMobile" class="form-label">Mobile</label>
                  <input type="number" class="form-control" id="inputMobile" name="mobile" value="{{old('mobile')}}" required>
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
                  <input type="password" class="form-control" id="inputPassword4" name="password" required>
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