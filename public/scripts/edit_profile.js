// script file for user.edit_profile.blade.php

function changeCoverImage(event){
    const currentElement = event.target;
    const targetElement = outerElementAtWith(currentElement, 2);
    const file = currentElement.files[0];
    let reader = new FileReader();
    let coverImagePath;
    reader.addEventListener("load", () => {
        coverImagePath = reader.result;
        targetElement.style.backgroundImage = `url(${coverImagePath})`;
    })
    reader.readAsDataURL(file);
}

function changeProfileImage(event){
    const file = event.target.files[0];
    const currentElement = event.target;
    const targetElement = outerElementAtWith(currentElement, 2);

    let reader = new FileReader();
    let profileImagePath;
    reader.addEventListener("load", () => {
        profileImagePath = reader.result;
        targetElement.style.backgroundImage = `url(${profileImagePath})`;
    })
    reader.readAsDataURL(file);
}