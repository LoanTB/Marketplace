function triggerAccountMenu() {
    var menu = document.getElementById("accountcontrol");
    if (menu.style.display == "block") {
        menu.style.top = "5px";
        menu.style.opacity = 0;
        setTimeout(function() {
            menu.style.display = "none";
        }, 250);
    } else {
        menu.style.display = "block";
        setTimeout(function() {
            menu.style.top = "65px";
            menu.style.opacity = 1;
        }, 10);   
    }
}