document.querySelectorAll('a[href^="#"]').forEach(anchor => {

    anchor.addEventListener("click", function(e){

        e.preventDefault();

        const target = document.querySelector(
            this.getAttribute("href")
        );

        if(target){

            target.scrollIntoView({
                behavior:"smooth",
                block:"start"
            });

        }

    });

});


window.addEventListener("scroll", () => {

    const cards = document.querySelectorAll(
        ".service-card, .pricing-card, .hero-card"
    );

    cards.forEach(card => {

        const rect = card.getBoundingClientRect();

        if(rect.top < window.innerHeight - 80){

            card.style.opacity = "1";
            card.style.transform = "translateY(0px)";

        }

    });

});


document.querySelectorAll(
    ".service-card, .pricing-card, .hero-card"
).forEach(card => {

    card.style.opacity = "0";
    card.style.transform = "translateY(30px)";
    card.style.transition = ".6s ease";

});
