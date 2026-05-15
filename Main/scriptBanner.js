document.addEventListener("DOMContentLoaded", () => {
    const banner = document.getElementById("banner");
    const btnNext = document.querySelector(".btn-next");
    const btnPrev = document.querySelector(".btn-prev");
    const form = document.getElementById("form-anotacoes");

    let position = 0;

    banner.classList.add("sem-animacao");

    const savedPosition = localStorage.getItem("bannerPosition");
    if (savedPosition !== null) {
        position = parseInt(savedPosition);
    }

    function update() {
        banner.classList.remove("direita", "esquerda");

        if (position === 1) banner.classList.add("direita");
        if (position === -1) banner.classList.add("esquerda");

        btnNext.style.display = (position === 1) ? "none" : "block";
        btnPrev.style.display = (position === -1) ? "none" : "block";

        localStorage.setItem("bannerPosition", position);
    }

    btnNext.addEventListener("click", () => {
        if (position < 1) {
            position++;
            update();
        }
    });

    btnPrev.addEventListener("click", () => {
        if (position > -1) {
            position--;
            update();
        }
    });

    form.addEventListener("submit", () => {
        localStorage.setItem("bannerPosition", position);
    });

    update();

    setTimeout(() => {
        banner.classList.remove("sem-animacao");
    }, 50);
});
