var articles = document.getElementsByClassName("overflowable");
for (var i = 0; i < articles.length; i++) {
    if (articles[i].scrollHeight > articles[i].clientHeight || articles[i].scrollWidth > articles[i].clientWidth) {
        articles[i].classList.add("text-overflow-anim");
    }
}