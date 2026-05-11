document.addEventListener("click", function(e) {
    if (e.target.classList.contains("btn-prefinish")) {
        e.target.classList.remove("btn-prefinish");
        e.target.classList.add("btn-finish");

        const li = e.target.parentElement;
        li.classList.add("li-line");
    } 
    else if (e.target.classList.contains("btn-finish")) {
        e.target.classList.remove("btn-finish");
        e.target.classList.add("btn-prefinish");

        const li = e.target.parentElement;
        li.classList.remove("li-line");
    }
});