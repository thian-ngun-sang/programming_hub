// script file for "post.upload_page.blade.php"

function uploadPostFile(obj){
    let file = obj.files[0];
    let inputField = obj.parentNode;
    let filePath;

    let reader = new FileReader();
    reader.addEventListener("load", () => {
        filePath = reader.result;
        if(file.type === "image/jpeg" || file.type === "image/png"){
            inputField.insertAdjacentHTML("afterend", `<img class="upload-file" src="${filePath}"/>`);
            inputField.classList.add("d-none");
        }
        if(file.type === "video/mp4" || file.type === "video/webm"){
            inputField.insertAdjacentHTML("afterend", `<video class="upload-file" controls src="${filePath}"></video>`);
            inputField.classList.add("d-none");
        }
    })
    reader.readAsDataURL(file);
}