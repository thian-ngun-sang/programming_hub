// script file for "post.list.blade.php"

// for specific post view
window.onload = function(){
    let app = document.querySelector("#view-post");
    if(app != null){
        let upshots = innerElementWith(app, ".upshots");
        let commentBtn = innerElementWith(upshots, ".comment-btn");
        commentBtn.click();
    }
}

window.onscroll = function(){
    removePopups();
    removeDropdowns();
}

function defaultReactionHover(obj){
    parentNode = obj.parentNode;
    const likeReactContainer = parentNode.children[0];
    likeReactContainer.classList.replace("d-none", "d-flex");
}

function reactionMouseLeave(obj){
    const likeReactContainer = obj.children[0];
    if(likeReactContainer.classList.contains("d-flex")){
        hideElement(likeReactContainer);
    }
}

function reactionGroupMouseLeave(obj){
    hideElement(obj);
}

const giveReactionRequest = (action, form_data) => {
    const {post_id, share_id, user_id, reaction, related_to} = form_data;
    let data;
    if(reaction === undefined){
        data = share_id === undefined ? `foreign_id=${post_id}&user_id=${user_id}&related_to=${related_to}` : 
            `foreign_id=${share_id}&user_id=${user_id}&related_to=${related_to}`;
    }else{
        // data = `foreign_id=${foreign_id}&user_id=${user_id}&
        // reaction=${reaction}&related_to=${related_to}`;
        data = share_id === undefined ? `foreign_id=${post_id}&user_id=${user_id}&reaction=${reaction}&related_to=${related_to}` : 
            `foreign_id=${share_id}&user_id=${user_id}&reaction=${reaction}&related_to=${related_to}`;
    }

    const http = new XMLHttpRequest();
    const url = action === "make" ? "/reaction/give-reaction?" + data : "/reaction/drop-reaction?" + data;
    http.open("GET", url, true);
    http.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    http.setRequestHeader("Accept", "application/json");
    http.onload = function(){
        console.log(http.responseText);
    }
    http.send();
}

const giveReaction = (event, className) => {
        const currentElement = event.target;
        const parentElement = currentElement.parentNode;
        const reactionCtn = parentElement.parentNode;

        const postCtn = outerElementAtWith(currentElement, `.${className}`)
        let postData = postCtn.getAttribute("data-post");
        let form_data = JSON.parse(postData);	// post_id, share_id, user_id, related_to
        
        const reactionGroup = innerElementWith(reactionCtn, ".reaction-container");
        hideElement(reactionGroup);

        if(className === "share-container"){
            form_data["related_to"] = "share_posts"
        }

        if(currentElement.classList.contains("gave-rct")){	// check if the "reaction" is already given
            const newElement = parentElement.innerHTML = `<span class="fw-800">Like</span>`
            giveReactionRequest("drop", form_data);
        }else{
            parentElement.innerHTML = `<span class="fw-800 like-rct gave-rct">Like</span>`
            const new_form = {...form_data, reaction: "like"};
            giveReactionRequest("make", new_form);
        }
}

function chooseReaction(obj, rct, className){
    const parentNode = obj.parentNode.parentNode;
    const likeReactContainer = parentNode.children[0];
    const rctBtn = parentNode.children[1];
        
    const postCtn = outerElementAtWith(obj, `.${className}`);	// call function
    let postData = postCtn.getAttribute("data-post");
    let form_data = JSON.parse(postData);	// post_id, share_id, user_id, related_to
    form_data = {...form_data, reaction: rct.toLowerCase()};
    if(className === "share-container"){
        form_data["related_to"] = "share_posts";
    }

    hideElement(likeReactContainer);
    giveReactionRequest("make", form_data);

    const reactionStyle = rct.toLowerCase() + "-rct"
    rctBtn.innerHTML = `<span class="${reactionStyle + ' fw-800 gave-rct'}">
            ${rct}
        </span>`
}

const openCommentForm = async (event, className) => {
        const currentElement = event.target;
        const targetElement = outerElementAtWith(currentElement, `.${className}`);
        const commentListCtn = innerElementWith(targetElement, ".comments");
        const commentForm = innerElementWith(targetElement, ".comment-form");
        
        let formData = targetElement.getAttribute("data-post");
        formData = JSON.parse(formData);
        
        const {post_id, share_id} = formData;
        let {related_to} = formData;
        if(share_id != undefined){
            related_to = "share_posts";
        }

        if(commentForm.classList.contains("d-flex")){
            currentElement.style.color = "white";
            hideElement(commentForm);
            commentListCtn.innerHTML = "";
        }else{
            currentElement.style.color = "rgb(26, 120, 235)";
            commentForm.classList.replace("d-none", "d-flex");

            if(related_to === "posts"){
                const comments = await getComments(post_id, related_to);
                buildCommentSection(commentListCtn, comments);	// comment list container, comments
            }else if(related_to === "share_posts"){
                const comments = await getComments(share_id, related_to);
                buildCommentSection(commentListCtn, comments);
            }
        }
}

const getComments = async (id, related_to) =>{
    let data = await fetch(`/post/get-comment?foreignId=${id}&relatedTo=${related_to}`, {
        method: 'GET',
        headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json'
        }
    })
    .then(res => {
        return res.json()
    });
    return data;
}

const hideElement = (el) => {
    if(el.classList.contains("d-flex")){
        el.classList.replace("d-flex", "d-none")
    }else{
        el.classList.add("d-none");
    }
}

const hidePopups = () => {
    popupList = document.querySelectorAll(".popup");
    console.log(popupList.length);
    for(let i =0; i < popupList.length; i++){
        popupList[i].classList.add("d-none");
    }
}

const removePopups = () => {
    popupList = document.querySelectorAll(".popup");
    if(popupList.length >= 1){
        for(let i =0; i < popupList.length; i++){
            popupList[i].remove();
        }
    }
}

const removeDropdowns = () => {
    let dropdowns = document.querySelectorAll(".cdropdown");
    if(dropdowns.length > 0){
        for(let i = 0; i < dropdowns.length; i++){
            dropdowns[i].remove();
        }
    }
}

function buildCommentSection(elementCtn, comments){
    elementCtn.innerHTML = "";
    for(let i = 0; i < comments.length; i++){
        const currentProfileImage = comments[i].profile_image;
        let profile_image;

        const createdAt = timeAgoShortcut(new Date(comments[i].created_at));	// function from "script.js"
        if(currentProfileImage === null){
            profile_image = "/files/images/future.jpg";
        }else{
            profile_image = `/files/user/profile_images/${comments[i].user_id}/${currentProfileImage}`;
            // profile_image = "" +  + "/" + ;
        }

        const commentElement = `<div class="d-flex my-2">
            <div class="me-2">
                <img class="user-image-small" src="${profile_image}"/>
            </div>
            <div>
                <div class="bg-secondary ps-2 pe-3 border-radius-10">
                    <div class="fw-600 cursor-pointer">${comments[i].name}</div>
                    <div>${comments[i].body}</div>
                </div>
                <div>
                    ${createdAt}
                </div>
            </div>
        </div>`
        elementCtn.innerHTML += commentElement;
    }
}

async function leaveComment(event, param){
    const currentElement = event.target;
    const targetElement = outerElementAtWith(currentElement, `.${param}`);
    const raw_data = targetElement.getAttribute("data-post");
    const commentListCtn = innerElementWith(targetElement, ".comments");
    const form_data = JSON.parse(raw_data);

    let body_data = outerElementAt(currentElement, 1).children[0];
    const body = body_data.value;
    body_data.value = "";

    if(body === ""){
        return false;
    }
    
    const { share_id, post_id } = form_data;
    let data, public_related_to;
    if(share_id === undefined){	// I assume if "share_id" is empty, it belongs to original post
        const {user_id, related_to} = form_data;
        public_related_to = related_to;
        data = `user_id=${user_id}&foreign_id=${post_id}&body=${body}&related_to=${related_to}`;
    }else{
        const {user_id} = form_data;
        const related_to = "share_posts";
        public_related_to = related_to;
        data = `user_id=${user_id}&foreign_id=${share_id}&body=${body}&related_to=${related_to}`;
    }

    const xhttp = new XMLHttpRequest();
    const csrfToken = document.getElementsByName("csrf-token")[0].getAttribute("content")
    xhttp.onload = function(){	// What to do when the response is ready
        // console.log(xhttp.responseText);
    }
    xhttp.open("POST", "http://127.0.0.1:8000/post/make-comment", true);	// open(method, url, async, user, psw)
    xhttp.setRequestHeader("Accept", "application/json");
    xhttp.setRequestHeader("X-CSRF-TOKEN", csrfToken);
    xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhttp.send(data);

    if(share_id === undefined){
        const comments = await getComments(post_id, public_related_to);
        buildCommentSection(commentListCtn, comments);
    }else{
        const comments = await getComments(share_id, public_related_to);
        buildCommentSection(commentListCtn, comments);
    }
}

const sharePopup = (event, param) => {
    const targetPost = outerElementAtWith(event.target, `.${param}`);
    let postData = targetPost.getAttribute("data-post");
    let form_data = JSON.parse(postData);

    const popupCtn = document.createElement("div");
    popupCtn.classList.add("share-popup", "popup", "position-absolute");
    const popup = `
            <div class="share-popup-container px-3 mb-2 py-2 bg-white">
                <div class="d-flex justify-content-between">
                    <div>
                        <button class="bd-rd-5" onclick="removePopups()">Back</button>
                    </div>
                    <div class="d-flex">
                        <select class="me-3 bd-rd-5">
                            <option>Public</option>
                            <option>Friends</option>
                            <option>Only me</option>
                        </select>
                        <div>
                            <button id="share-button" class="bd-rd-5">Post</button>
                        </div>
                    </div>
                </div>
                <div class="text-ctn">
                    <textarea class="w-100 mt-3 share-textarea px-2" placeholder="What's on your mind"></textarea>
                </div>
            </div>
    `
    popupCtn.innerHTML = popup;
    targetPost.append(popupCtn);

    let shareBtn = document.querySelector("#share-button");
    shareBtn.onclick = (event) => {
        share(event, form_data);
    }
}

function share(event, data){
    const {post_id, share_id, user_id, related_to} = data;
    let shareCtn;
    if(share_id != null){
        shareCtn = outerElementAtWith(event.target, ".share-container");
    }else{
        shareCtn = outerElementAtWith(event.target, ".post-container");
    }
    
    let shareBody = innerElementWith(shareCtn, ".share-popup");
    shareBody = innerElementWith(shareBody, ".share-popup-container");
    let textCtn = innerElementWith(shareBody, ".text-ctn");
    let textArea = innerElementWith(textCtn, ".share-textarea");
    let textValue = textArea.value;
    textArea.value = "";
    
    let new_data;
    if(share_id != undefined){	// if share_id is not "undefined" we share a "share_post"
        const shareLog_relatedTo = "share_posts";	// the foreign_id is still "post_id" but shareLog_foreignId is "share_id"
        new_data = `foreign_id=${post_id}&user_id=${user_id}&shareLog_foreignId=${share_id}&body=${textValue}&
            related_to=${related_to}&shareLog_relatedTo=${shareLog_relatedTo}`;
    }else{
        new_data = `foreign_id=${post_id}&user_id=${user_id}&shareLog_foreignId=${post_id}&body=${textValue}&
            related_to=${related_to}&shareLog_relatedTo=${related_to}`;
    }

    fetch(`/post/make-share-post?${new_data}`)
    .then((res) => res.json())
    .then(data => console.log(data.data))
    removePopups();
}

const postDropdown = (event, className) => {
    const currentElement = event.target;
    const dropDownCtn = outerElementWith(currentElement, `.${className}`);
    let postUrl = currentElement.getAttribute("data-url");
    let urlValue = document.createElement("input");
    urlValue.value = postUrl;
    urlValue.setSelectionRange(0, 99999);
    
    let oldDropdown = innerElementWith(dropDownCtn, ".cdropdown");
    // if current post does not have dropdown, remove every dropdowns(onscroll) and make a new one
    if(oldDropdown === undefined){
        const dropDown = document.createElement("div");
        dropDown.classList.add("cdropdown", "post-modifier-dropdown");

        const copyBtn = document.createElement("span");
        copyBtn.classList.add("cursor-pointer");
        copyBtn.innerText = "Copy Link";

        copyBtn.onclick = () => {
            // copy post-link to user clipboard
            navigator.clipboard.writeText(urlValue.value);
            removeDropdowns();
        }

        dropDown.append(copyBtn);
        dropDownCtn.append(dropDown);
    }else{
        // if current post has dropdown already, remove every dropdowns
        removeDropdowns();
    }
}

const hidePost = (obj, className) => {
    let targetElement = outerElementWith(obj, `.${className}`)
    hideElement(targetElement);
}