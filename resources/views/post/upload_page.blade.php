@extends('main')

@section('content')
	<div class="mt-5 upload-page">
		<div>
			<div class="my-3">
				@if(session('post_error'))
					<div class="text-warning text-align-center">
						{{ session('post_error') }}
					</div>
				@endif
				@if(session('fileError'))
					<div class="text-warning text-align-center">
						{{ session('fileError') }}
					</div>
				@endif
			</div>

			<form class="post-upload-form" action="{{ url('upload-post') }}" method="post" enctype="multipart/form-data">
				@csrf
				<div>
					<textarea class="post-text-area col-12" name="body" placeholder="Write..."></textarea>
				</div>
				<div class="text-align-center file-input">
					<label for="file" class="file-input-label">Add image or videos</label>
					<div>
						@error('file')
							<span class="text-warning">{{ $message }}</span>
						@enderror
					</div>
					<input class="d-none" onchange="uploadPostFile(this)" type="file" name="file" id="file"/>
				</div>
				<div class="text-align-right cmt-4">
					<input class="csubmit" type="submit" value="Upload"><br/>
				</div>
			</form>

		</div>
		<!-- upload_post.js is used -->
	</div>
@endsection