@extends('main')

@section('content')
	<div>
		<div class="mt-3">
			<span class="mb-2 d-block cursor-pointer" onclick="history.back()">Back</span>
			<div class="post-container position-relative" id="view-post" data-post='{"post_id": {{ $post->id }}, "user_id": {{Auth::user()->id}}, "related_to": "posts"}'>
				<!-- post user info -->
				<div class="post-info mb-3">
					<div class="d-flex">
						<a href="#" class="mt-1">
							@if($post->profile_image != null)
								<img class="profile-list-view" src="/files/user/profile_images/{{ $post->user_id }}/{{ $post->profile_image }}"/>
							@else
								<img class="profile-list-view" src="/files/images/future.jpg"/>
							@endif
						</a>
						<div class="ms-1">
							<div>{{ $post->username }}</div>
							<div>{{ Carbon\Carbon::parse($post->created_at)->diffForHumans() }}</div>
						</div>
					</div>
					<div>

					</div>
					<div class="post-modifier text-align-right">
						<img onclick="postDropdown(event, 'post-container')" class="c-icon" data-url="{{ route('get-post', $post->id) }}" src="/icons/images/more.png"/>
					</div>
				</div>
				<!-- post body -->
				<div class="inner-post-container post-body">	<!-- inner post container -->
					<p>{{ $post->body }}</p>	<!-- post body -->
					@if($post->fileName != null)
						@if($post->fileType == 'video')
							<div data-file-type="video" class="file-ctn">
								<div class="mt-2">
									<video controls class="newsfeed-file-ctn">
										<source src="/files/user/videos/{{$post->fileName}}"/>
									</video>
								</div>
							</div>
						@elseif($post->fileType == 'image')
							<div data-file-type="image" class="file-ctn">
								<div class="my-2">
									<img class="newsfeed-file-ctn" src="/files/user/images/{{$post->fileName}}"/>
								</div>
							</div>
						@endif
					@else
						<div class="mb-3">
							<!-- Empty div if there is no file attached -->
						</div>
					@endif
				</div>
				<!-- upshots -->
				<div class="row upshots text-align-center">
					<div class="col-4 position-relative" onmouseleave="reactionMouseLeave(this)">
						<div class="position-absolute reaction-container d-none">
							<span class="cursor-pointer" onclick="chooseReaction(this, 'Like', 'post-container')">
								<span class="like-rct fw-800">Like</span>
							</span>
							<span class="cursor-pointer" onclick="chooseReaction(this, 'Unlike', 'post-container')">
								<span class="unlike-rct fw-800">Unlike</span>
							</span>
							<span class="cursor-pointer" onclick="chooseReaction(this, 'Sad', 'post-container')">
								<span class="sad-rct fw-800">Sad</span>
							</span>
							<span class="cursor-pointer" onclick="chooseReaction(this, 'Haha', 'post-container')">
								<span class="haha-rct fw-800">Haha</span>
							</span>
						</div>
						<span onmouseenter="defaultReactionHover(this)" onclick="giveReaction(event, 'post-container')"
							class="like-btn chover-btn cursor-pointer cbtn">
							@if($post->usr_rct != null)
								<span class="fw-800 {{ $post->usr_rct }}-rct gave-rct">{{ $post->usr_rct }}</span>
							@else
								<span class="fw-800">Like</span>
							@endif
						</span>
					</div>
					<span onclick="openCommentForm(event, 'post-container')" class="comment-btn fw-800 cursor-pointer col-4">
						Comment
					</span>
					<span class="share-btn fw-800 text-white col-4 cursor-pointer">
						<span onclick="sharePopup(event, 'post-container')">Share</span>
					</span>
				</div>
				<!-- comment form -->
				<div class="comment-form mt-1 d-none">
					<textarea class="me-1" style="width: 90%"></textarea>
					<input style="height: 33px;" onclick="leaveComment(event, 'post-container')" type="button" value="Comment"/>
				</div>
				<!-- comments -->
				<div class="comments">
				</div>
			</div>
		</div>
		<!-- newsfeed.js is used -->
	</div>
@endsection