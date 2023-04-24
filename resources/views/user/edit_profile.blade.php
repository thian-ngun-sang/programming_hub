@extends('main')

@section('content')
<div class="row">

	<style>
		.user-cover{
			background-image: url("{{ asset($coverImagePath)  }}");
		}
		.user-profile{
			background-image: url("{{ asset($profileImagePath)  }}");
		}
	</style>
	<div class="mt-3">
		<div class="mb-2">
			<span class="text-white cursor-pointer" onclick="history.back()">Back</span>
		</div>
		<form class="form-group" method="post" action="{{ route('user-update') }}" enctype="multipart/form-data">
			@csrf

			<div class="d-flex user-cover temp-cover-url justify-content-center" style="">
				<label for="coverImage" class="cover-label">
					<img class="sqicon bd-rd-5 cursor-pointer" src="/icons/icos/camera.ico">
					<input id="coverImage" onchange="changeCoverImage(event)" class="d-none" type="file" name="coverImage"/>
				</label>

				<div class="user-profile rounded-circle">
					<label for="profileImage" class="profile-label">
						<img class="sqicon bd-rd-5 cursor-pointer" src="/icons/icos/camera.ico">
						<input id="profileImage" onchange="changeProfileImage(event)" class="d-none" type="file" name="profileImage"/>
					</label>
				</div>
			</div>

			<div class="my-2">
				<label class="text-white">Name</label>
				<input name="name" class="d-block w-100 cg-input" type="text" placeholder="Name" value="{{ $user->name }}" />
			</div class="my-2">

			<div class="my-2">
				<label class="text-white">Email</label>
				<input name="email" class="d-block w-100 cg-input" type="email" placeholder="Email" value="{{ $user->email }}"/>
			</div>

			<div class="my-2">
				<label class="text-white">Phone</label>
				<input name="phone" class="d-block w-100 cg-input" type="text" placeholder="Phone" value="{{ $user->phone }}"/>
			</div>

			<div class="my-2">
			<label class="text-white">Gender</label>
				<select name="gender" class="d-block w-100 cg-input">
					<option>Prefer not to say</option>
					<option value="male" @if($user->gender == 'male') selected @endif>Male</option>
					<option value="female" @if($user->gender == 'female') selected @endif>Female</option>
				</select>
			</div>

			<div class="my-2">
				<label class="text-white">Address</label>
				<textarea name="address" placeholder="Address" class="d-block w-100">{{ $user->address }}</textarea>
			</div>
			
			<div class="text-align-right">
				<input type="submit" value="Update" class="cbtn">
			</div>
		</form>

		<div class="mb-3">
			<a href="{{ route('user-change-password-page') }}" class="text-decoration-none">Change Password</a>
		</div>
	</div>
	<!-- edit_profile.js is used -->
</div>
@endsection