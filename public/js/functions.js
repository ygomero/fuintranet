function page_loading(){
    var element = document.getElementById("loading_view");
    element.classList.add("display");
}

function page_stop_loading(){
    var element = document.getElementById("loading_view");
    element.classList.remove("display");
}