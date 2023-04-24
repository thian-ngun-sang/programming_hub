// script file for general purpose

function outerElementAt(element, num){
	var new_element = element.parentNode;
	for(i = 1; i < num; i++){
	  new_element = new_element.parentNode;
	}
	return new_element;
}

function outerElementWith(element, param){
  let parentElement = element.parentNode;

  if(param.startsWith(".")){  // run this function if it is a class
	let className = param.slice(1);
	try{
	  while(!parentElement.classList.contains(className)){
		parentElement = parentElement.parentNode;
	  }
	  return parentElement;
	}catch(error){
	  return false;
	}
  }else if (param.startsWith("#")) {  // run this function if it is an id
	let idName = param.slice(1);
	let parentElementId = parentElement.id;
	try{
	  while(parentElementId != idName){
		parentElement = parentElement.parentNode;
		parentElementId = parentElement.id;
	  }
	  return parentElement;
	}catch(error){
	  return false;
	}
  }
}

function outerElementAtWith(element, param){
  let result;
  if(typeof param === "string"){
	result = outerElementWith(element, param)
  }else if(typeof param === "number"){
	result = outerElementAt(element, param);
  }
  return result;
}

function innerElementWith(element, param){
	let elementList = element.children;
	if(param.startsWith(".")){  // run this function if it is a class
		let className = param.slice(1);
		for(let i = 0; i < elementList.length; i++){
			if(elementList[i].classList.contains(className)){
				return elementList[i];
			}
		}
	  }else if (param.startsWith("#")) {  // run this function if it is an id
		let idName = param.slice(1);
		console.log(idName);
	  }
}

timeAgo = (date) => {	// copied from stack over flow
            var ms = (new Date()).getTime() - date.getTime();
            var seconds = Math.floor(ms / 1000);
            var minutes = Math.floor(seconds / 60);
            var hours = Math.floor(minutes / 60);
            var days = Math.floor(hours / 24);
            var months = Math.floor(days / 30);
            var years = Math.floor(months / 12);
    
            if (ms === 0) {
                return 'Just now';
            } if (seconds < 60) {
                return seconds + ' seconds ago';
            } if (minutes < 60) {
                return minutes + ' minutes ago';
            } if (hours < 24) {
                return hours + ' hours ago';
            } if (days < 30) {
                return days + ' days ago';
            } if (months < 12) {
                return months + ' months ago';
            } else {
                return years + ' years ago';
            }
    
        }

timeAgoShortcut = (date) => {	// copied from stack over flow
            var ms = (new Date()).getTime() - date.getTime();
            var seconds = Math.floor(ms / 1000);
            var minutes = Math.floor(seconds / 60);
            var hours = Math.floor(minutes / 60);
            var days = Math.floor(hours / 24);
            var months = Math.floor(days / 30);
            var years = Math.floor(months / 12);
    
            if (ms === 0) {
                return 'Just now';
            } if (seconds < 60) {
                return seconds + ' s';
            } if (minutes < 60) {
                return minutes + ' m';
            } if (hours < 24) {
                return hours + ' h';
            } if (days < 30) {
                return days + ' d';
            } if (months < 12) {
                return months + ' mo';
            } else {
                return years + ' y';
            }
    
        }