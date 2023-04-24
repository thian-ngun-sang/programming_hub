<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}"/>
	<title>Programming Hub</title>
	<!-- general purpose -->
	<link rel="stylesheet" href="{{ asset('styles/style.css') }}">

	<!-- post.list.blade.php | post.get_post.blade.php | post.get_share_post.blade.php -->
	<link rel="stylesheet" href="{{ asset('styles/newsfeed.css') }}">

	<!-- post.upload_page.blade.php -->
	<link rel="stylesheet" href="{{ asset('styles/upload-post.css') }}"/>

	<!-- user.profile.blade.php -->
	<link rel="stylesheet" href="{{ asset('styles/user-profile.css') }}">

	<!-- general purpose -->
	<link rel="stylesheet" href="{{ asset('styles/bootstrap.min.css') }}"/>
</head>
<body class="bg-dark text-white" width="100%">

	<div>
		<div>
			<div class="mb-2">
				<div class="main-nav content-center">
					<div class="bg-dark app-logo">
						<span class="bg-dark text-white">Programming</span>
						<span class="bg-yellow"> Hub</span>
					</div>
					<div class="search-container">
						<form>
							<div>
								<img id="mc" class="icon-xsmall-noraius mb-1" src="{{ asset('/icons/icos/search.ico') }}"/>
								<input id="search-bar" placeholder="Search" type="text" name="q">
							</div>
						</form>
					</div>
					
					<div class="nav-item">
						<a class="del-urderline bg-dark text-white" href="{{ url('upload-post-page') }}">Upload</a>
					</div>
					
					<div class="nav-item">
						<a  class="del-urderline text-white" href="{{ route('user-account-page') }}">Account</a>
					</div>

					<div class="main-nav-option open-more-navs">
						<nav class="cursor-pointer" onclick="moreNav(this)">Nav</nav>
					</div>

					<div class="main-nav-option close-more-navs d-none" onclick="closeMoreNav(this)">
						<nav class="cursor-pointer">CloseNav</nav>
					</div>
				</div>
				<div class="d-flex content-center bottom-navigator">
					<a class="ctlink" href="/">Home</a>
					<a class="ctlink" href="{{ route('post-list') }}">Tutorials</a>
					<a class="ctlink nav-item" href="#">Programming Language</a>
					<a class="ctlink" href="#">Channles</a>
				</div>
			</div>

			<div class="d-flex newsfeed">
				<div class="left-sidebar">

				</div>
				<div class="main-content">
					@yield('content')
				</div>
				<div class="right-sidebar">

				</div>
			</div>
		</div>
	</div>
	<!-- main.blade.php -->
	<script src="{{ asset('scripts/main.js') }}"></script>

	<!-- post.list.blade.php | post.get_post.blade.php | post.get_share_post.blade.php -->
	<script src="{{ asset('scripts/newsfeed.js') }}"></script>

	<!-- post.upload_page.blade.php -->
	<script src="{{ asset('scripts/upload_post.js') }}"></script>

	<!-- user.edit_profile.php -->
	<script src="{{ asset('scripts/edit_profile.js') }}"></script>

	<!-- general purpose -->
	<script src="{{ asset('scripts/script.js') }}"></script>
</body>
</html>