// script file for main.blade.php

const mainContent = document.querySelector(".main-content");
const moreNavBtn = document.querySelector(".open-more-navs");

function moreNav(obj){
	const navs = document.createElement("div");
	const CloseMoreNavs = document.querySelector(".close-more-navs");
	navs.classList.add("more-nav-ctn", "bg-dark", "text-align-right", "pe-4");
	const innerNavs = `
		<a href="/upload-post-page">Upload</a><br/>
		<a href="#">Programming Languages</a><br/>
		<a href="/user/account">Account</a>
	`;
	navs.innerHTML = innerNavs;
	const navCtn = outerElementAt(obj, 2);
	navCtn.append(navs);

	moreNavBtn.classList.add("d-none");
	mainContent.classList.add("d-none");
	CloseMoreNavs.classList.remove("d-none");
}

function closeMoreNav(obj){
	const moreNavCtn = document.querySelector(".more-nav-ctn");

	moreNavCtn.remove();
	moreNavBtn.classList.remove("d-none");
	obj.classList.add("d-none");
	mainContent.classList.remove("d-none")
}