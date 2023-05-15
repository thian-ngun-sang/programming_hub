@extends('main')

@section('content')
	<div>
        <span class="mb-2 d-block cursor-pointer" onclick="history.back()">Back</span>
        <div class="share-container position-relative" id="view-post" data-post='{"post_id": {{ $post->id }}, "share_id": {{ $post->share_id }}, "user_id": {{Auth::user()->id}}, "related_to": "posts"}'>
			<div class="post-info mb-3">
				<div class="d-flex">
					<a href="#" class="mt-1">
						@if($post->share_profileImage != null)
							<img class="profile-list-view" src="/files/user/profile_images/{{ $post->share_userid }}/{{ $post->share_profileImage }}"/>
						@else
							<img class="profile-list-view" src="/files/images/future.jpg"/>
						@endif
					</a>
					<div class="ms-1">
						<div>{{ $post->username }}</div>
						<div>{{ Carbon\Carbon::parse($post->timeVariable)->diffForHumans() }}</div>
					</div>
				</div>
				<div></div>
				<div class="post-modifier text-align-right">
					<img onclick="postDropdown(event, 'share-container')" data-url="{{ route('share-post', $post->share_id) }}" class="c-icon" src="/icons/images/more.png"/>
				</div>
			</div>
			<!-- share body -->
			<p class="mb-2">
				{{ $post->share_body }}
			</p>
			<!-- post container -->
			<div class="share-post-ctn post-shell">
				<!-- post info -->
				<div class="d-flex mb-3 justify-content-between">
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
				</div>
				<div class="post-body">
					<p>{{ $post->body }}</p>
					@if($post->filename != null)
						@if($post->type == 'video')
							<div data-file-type="video" class="file-ctn">
								<div class="mt-2">
									<video class="newsfeed-file-ctn" controls>
										<!-- <source src="storage/user/posts/videos/{{ $post->user_id }}/{{$post->filename}}"/> -->
										<source src="/files/user/videos/{{$post->filename}}"/>
									</video>
								</div>
							</div>
						@elseif($post->type == 'image')
							<div data-file-type="image" class="file-ctn">
								<div class="my-2">
									<!-- <img style="width: 100%; height: 500px; object-fit: cover;" src="storage/user/posts/images/{{ $post->user_id }}/{{$post->filename}}"/> -->
									<img class="newsfeed-file-ctn" src="/files/user/images/{{$post->filename}}"/>
								</div>
							</div>
						@endif
					@else
						<div class="mb-3">
							<!-- Empty div if there is no file attached -->
						</div>
					@endif
				</div>
			</div>
			<!-- upshots -->
			<div class="row upshots text-align-center mt-2">
				<div onmouseleave="reactionMouseLeave(this)" class="col-4 position-relative">
					<div onmouseleave="reactionGroupMouseLeave(this)" class="position-absolute reaction-container d-none">
						<span class="cursor-pointer" onclick="chooseReaction(this, 'Like', 'share-container')">
							<span class="like-rct fw-800">Like</span>
						</span>
						<span class="cursor-pointer" onclick="chooseReaction(this, 'Unlike', 'share-container')">
							<span class="unlike-rct fw-800">Unlike</span>
						</span>
						<span class="cursor-pointer" onclick="chooseReaction(this, 'Sad', 'share-container')">
							<span class="sad-rct fw-800">Sad</span>
						</span>
						<span class="cursor-pointer" onclick="chooseReaction(this, 'Haha', 'share-container')">
							<span class="haha-rct fw-800">Haha</span>
						</span>
					</div>
					<span onmouseover="defaultReactionHover(this)" onclick="giveReaction(event, 'share-container')"
						class="like-react chover-btn cursor-pointer cbtn">
						@if($post->share_userReaction != null)
							<span class="fw-800 {{ $post->share_userReaction }}-rct gave-rct">{{ $post->share_userReaction }}</span>
						@else
							<span class="fw-800">Like</span>
						@endif
					</span>
				</div>
				<span onclick="openCommentForm(event, 'share-container')" class="comment-btn fw-800 cursor-pointer react-container col-4">
					Comment
				</span>
				<span class="share-react fw-800 text-white col-4 cursor-pointer">
					<span onclick="sharePopup(event, 'share-container')">Share</span>
				</span>
			</div>
			<!-- comment form -->
			<div class="comment-form mt-1 d-none">
				<textarea class="me-1" style="width: 90%"></textarea>
				<input style="height: 33px;" onclick="leaveComment(event, 'share-container')" type="button" value="Comment"/>
			</div>
			<!-- comments -->
			<div class="comments">
			</div>
			<!-- <hr> -->
		</div>
		<!-- newsfeed.js is used -->
	</div>
@endsection