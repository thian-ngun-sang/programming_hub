@extends('main')

@section('content')
<div class="row mt-5">
	<div class="offset-3 col-6 mt-3">
		<div>
			<i onclick="history.back()" class="fa-solid fa-arrow-left-long text-white"></i>
		</div>
		<form class="form-group" method="post" action="{{ route('user-change-password') }}" enctype="multipart/form-data">
			@csrf

            @if(session('credential_error'))
                <div class="d-flex justify-content-center">
                    <span class="text-danger">{{ session('credential_error') }}</span>
                </div>
            @endif
			<div class="cmt-10">
                <label class="text-white">Old Password</label>
			    <input name="oldPassword" class="form-control cg-input cmt-4" type="password" placeholder="Old Password"/>
                @error('oldPassword')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

			<div class="cmt-10">
                <label class="text-white">New Password</label>
			    <input name="newPassword" class="form-control cg-input cmt-4" type="password" placeholder="New Password"/>
                @error('newPassword')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
			
            <div class="cmt-10">
                <label class="text-white">Confirm Password</label>
			    <input name="confirmPassword" class="form-control cg-input cmt-4" type="password" placeholder="Confirm Password"/>
                @error('confirmPassword')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

			<div class="text-align-right cmt-10">
                <input type="submit" value="Update" class="csubmit  cg-input">
            </div>
		</form>

	</div>
</div>
@endsection