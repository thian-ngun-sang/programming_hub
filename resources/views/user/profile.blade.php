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

		<div class="text-align-right">
			<a class="text-decoration-none" href="{{ route('user-edit-page') }}">Edit Profile</a>
		</div>

		<form class="form-group">
			<div class="d-flex user-cover justify-content-center" style="">
				<div class="user-profile rounded-circle">
				</div>
			</div>

			<label class="text-white">Name</label>
			<input disabled name="name" class="d-block w-100 cg-input cdisabled-input" type="text" placeholder="Name" value="{{ $user->name }}" />

			<label class="text-white">Email</label>
			<input disabled name="email" class="d-block w-100 cg-input cdisabled-input" type="email" placeholder="Email" value="{{ $user->email }}"/>

			<label class="text-white">Phone</label>
			<input disabled name="email" class="d-block w-100 cg-input cdisabled-input" type="email" placeholder="Phone" value="{{ $user->phone }}"/>

			<label class="text-white">Gender</label>
			<select disabled name="gender" class="d-block w-100 cg-input cdisabled-input">
				<option>Prefer not to say</option>
				<option value="male" @if($user->gender == 'male') selected @endif>Male</option>
				<option value="female" @if($user->gender == 'female') selected @endif>Female</option>
			</select>

			<label class="text-white">Address</label>
			<textarea disabled name="address" placeholder="Address" class="d-block w-100 cg-input cdisabled-input">{{ $user->address }}</textarea>
			
			
		</form>
		<div class="mt-5 text-end mb-2 me-2">
			<a class="text-decoration-none" href="{{ route('user-logout') }}">Logout</a>
		</div>

	</div>
</div>
@endsection