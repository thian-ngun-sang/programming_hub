@extends('main')

@section('content')
	<div>
		<div>
			@foreach($posts as $post)
				@if($post->share_id == null)
					<!-- post container -->
					<div class="post-container position-relative" data-post='{"post_id": {{ $post->id }}, "user_id": {{Auth::user()->id}}, "related_to": "posts"}'>
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
							<a href="{{ route('get-post', $post->id) }}">
								<!-- post link -->
							</a>
							<div class="post-modifier text-align-right">
								<img onclick="postDropdown(event, 'post-container')" data-url="{{ route('get-post', $post->id) }}" class="c-icon" src="/icons/images/more.png"/>
								<img onclick="hidePost(this, 'post-container')" class="c-icon" src="/icons/images/cancel.png"/>
							</div>
						</div>
						<div class="post-body position-relative">
							<!-- post body -->
							<p>{{ $post->body }}</p>
							<!-- file section -->
							@if($post->filename != null)
								@if($post->type == 'video')
									<div data-file-type="video" class="file-ctn">
										<div class="mt-2">
											<video class="newsfeed-file-ctn" controls>
												<source src="/files/user/videos/{{$post->filename}}"/>
											</video>
										</div>
									</div>
								@elseif($post->type == 'image')
									<div data-file-type="image" class="file-ctn">
										<div class="my-2">
											<img class="newsfeed-file-ctn" src="/files/user/images/{{$post->filename}}"/>
										</div>
									</div>
								@endif
							@else
								<div class="mb-3">
									<!-- Empty div if there is no file attached -->
								</div>
							@endif
							<!-- upshots -->
							<div class="row text-align-center">
								<div onmouseleave="reactionMouseLeave(this)" class="col-4 position-relative">
									<div onmouseleave="reactionGroupMouseLeave(this)" class="position-absolute reaction-container d-none">
										<span class="cursor-pointer" onclick="chooseReaction(this, 'Like', 'post-container')">
											<span class="like-rct fw-800">Like</span>
											<!-- <img class="rtn-opt" src="/icons/images/like.png"/> -->
										</span>
										<span class="cursor-pointer" onclick="chooseReaction(this, 'Unlike', 'post-container')">
											<span class="unlike-rct fw-800">Unlike</span>
											<!-- <img class="rtn-opt" src="/icons/images/dislike.png"/> -->
										</span>
										<span class="cursor-pointer" onclick="chooseReaction(this, 'Sad', 'post-container')">
											<span class="sad-rct fw-800">Sad</span>
											<!-- <img class="rtn-opt" src="/icons/icos/sad.ico"/> -->
										</span>
										<span class="cursor-pointer" onclick="chooseReaction(this, 'Haha', 'post-container')">
											<span class="haha-rct fw-800">Haha</span>
											<!-- <img class="rtn-opt" src="/icons/icos/haha.ico"/> -->
										</span>
									</div>
									<span onmouseover="defaultReactionHover(this)" onclick="giveReaction(event, 'post-container')"
										class="like-react chover-btn cursor-pointer cbtn">
										@if($post->usr_rct != null)
											<span class="fw-800 {{ $post->usr_rct }}-rct gave-rct">{{ $post->usr_rct }}</span>
										@else
											<span class="fw-800">Like</span>
										@endif
									</span>
								</div>
								<span onclick="openCommentForm(event, 'post-container')" class="comment-react fw-800 cursor-pointer react-container col-4">
									Comment
								</span>
								<span class="share-react fw-800 text-white col-4 cursor-pointer">
									<span onclick="sharePopup(event, 'post-container')">Share</span>
								</span>
							</div>
						</div>
						<!-- comment form -->
						<div class="comment-form mt-1 d-none">
							<textarea class="me-1" style="width: 90%"></textarea>
							<input style="height: 33px;" onclick="leaveComment(event, 'post-container')" type="button" value="Comment"/>
						</div>
						<!-- comments -->
						<div class="comments">
						</div>
						<hr>
					</div>
				@else
					<!-- share container -->
					<div class="share-container position-relative" data-post='{"post_id": {{ $post->id }}, "share_id": {{ $post->share_id }}, "user_id": {{Auth::user()->id}}, "related_to": "posts"}'>
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
							<a href="{{ route('share-post', $post->share_id) }}">
								<!-- share post link -->
							</a>
							<div class="post-modifier text-align-right">
								<img onclick="postDropdown(event, 'share-container')" data-url="{{ route('share-post', $post->share_id) }}" class="c-icon" src="/icons/images/more.png"/>
								<img onclick="hidePost(this, 'share-container')" class="c-icon" src="/icons/images/cancel.png"/>
							</div>
						</div>
						<!-- share body -->
						<p>{{ $post->share_body }}</p>
						<!-- post container -->
						<div class="share-post-ctn post-shell">
							<!-- post info -->
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
								<a href="{{ route('get-post', $post->id) }}">
									<!-- post link -->
								</a>
								<div>
									<!-- post modifier -->
								</div>
							</div>
							<div class="post-body">
								<p>{{ $post->body }}</p>
								@if($post->filename != null)
									@if($post->type == 'video')
										<div data-file-type="video" class="file-ctn">
											<div class="mt-2">
												<video class="newsfeed-file-ctn" controls>
													<source src="files/user/videos/{{$post->filename}}"/>
												</video>
											</div>
										</div>
									@elseif($post->type == 'image')
										<div data-file-type="image" class="file-ctn">
											<div class="my-2">
												<img class="newsfeed-file-ctn" src="files/user/images/{{$post->filename}}"/>
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
						<div class="row text-align-center mt-2">
							<div onmouseleave="reactionMouseLeave(this)" class="col-4 position-relative">
								<div onmouseleave="reactionGroupMouseLeave(this)" class="position-absolute reaction-container d-none">
									<span class="cursor-pointer" onclick="chooseReaction(this, 'Like', 'share-container')">
										<span class="like-rct fw-800">Like</span>
										<!-- <img class="rtn-opt" src="/icons/images/like.png"/> -->
									</span>
									<span class="cursor-pointer" onclick="chooseReaction(this, 'Unlike', 'share-container')">
										<span class="unlike-rct fw-800">Unlike</span>
										<!-- <img class="rtn-opt" src="/icons/images/dislike.png"/> -->
									</span>
									<span class="cursor-pointer" onclick="chooseReaction(this, 'Sad', 'share-container')">
										<span class="sad-rct fw-800">Sad</span>
										<!-- <img class="rtn-opt" src="/icons/icos/sad.ico"/> -->
									</span>
									<span class="cursor-pointer" onclick="chooseReaction(this, 'Haha', 'share-container')">
										<span class="haha-rct fw-800">Haha</span>
										<!-- <img class="rtn-opt" src="/icons/icos/haha.ico"/> -->
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
							<span onclick="openCommentForm(event, 'share-container')" class="comment-react fw-800 cursor-pointer react-container col-4">
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
						<hr>
					</div>
				@endif
			@endforeach
		</div>
		<!-- newsfeed.js is used -->
	</div>
@endsection