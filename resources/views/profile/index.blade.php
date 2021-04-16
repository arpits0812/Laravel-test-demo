@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="page-header" style="margin-top: 0">
            <h1>Profile</h1>
        </div>
                @if (!empty($users))
                  <form method="post" action="{{ action('InvitationsController@profileUpdate') }}" enctype="multipart/form-data">

                    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">


                    @foreach ($users as $user)
                      <div class="form-group">
                        <input type="hidden" name="id" value="{{ $user->id }}">
                        <label for="formGroupExampleInput">Email</label>
                        <input type="text" class="form-control" name="email" value="{{ $user->email }}" readonly>
                      </div>

                     <div class="form-group">
                        <label for="formGroupExampleInput2">User Role</label>
                        @if($user->role_id = 1)
                        <input type="text" class="form-control" name="role_id" value="ADMIN" readonly>
                        @else
                         <input type="text" class="form-control" name="role_id" value="USER" readonly>
                        @endif
                      </div>

                      <div class="form-group">
                        <label for="formGroupExampleInput2">Full Name</label>
                        <input type="text" class="form-control" name="name" value="{{ $user->name }}">
                      </div>

                      <div class="form-group">
                        <label for="formGroupExampleInput">Username</label>
                        <input type="text" class="form-control" name="username" value="{{ $user->username }}">
                      </div>

             
                      <div class="form-group">
                        <img src="{{url('/uploads/avatars/admin/' . $user->profileImg)}}" alt="Avatar" class="avatar">
                        <input type="file" name="profileImg" class="custom-file-input">
                      </div>

                     <button type="submit" class="btn btn-primary">Update</button>
                      @endforeach
                  </form>

                @else
                    <p>No profile data!</p>
                @endif
    </div>
@endsection
