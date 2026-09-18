document.addEventListener("DOMContentLoaded", function () {
    const defaultBreakpoints = {
        0: { slidesPerView: 1, spaceBetween: 10 },
        576: { slidesPerView: 2, spaceBetween: 20 },
        768: { slidesPerView: 2, spaceBetween: 25 },
        992: { slidesPerView: 3, spaceBetween: 30 },
        1200: { slidesPerView: 3, spaceBetween: 30 }
    };

    const createSwiper = (selector, nextBtn, prevBtn, customBreakpoints = {}) => {
        const mergedBreakpoints = { ...defaultBreakpoints, ...customBreakpoints };
        new Swiper(selector, {
            slidesPerView: 3,
            spaceBetween: 30,
            pagination: {
                el: selector + " .swiper-pagination",
                clickable: true,
            },
            navigation: {
                nextEl: nextBtn,
                prevEl: prevBtn,
            },
            breakpoints: mergedBreakpoints,
        });
    };

    createSwiper(".carousal1", ".carousal1-next", ".carousal1-prev");

    createSwiper(".carousal2", ".carousal2-next", ".carousal2-prev", {
        0: { slidesPerView: 1, spaceBetween: 10 },
        576: { slidesPerView: 2, spaceBetween: 20 },
        768: { slidesPerView: 2, spaceBetween: 20 },
        992: { slidesPerView: 4, spaceBetween: 20 },
        1200: { slidesPerView: 4, spaceBetween: 20 }
    });

    createSwiper(".carousal3", ".carousal3-next", ".carousal3-prev", {
        0: { slidesPerView: 1, spaceBetween: 10 },
        576: { slidesPerView: 2, spaceBetween: 20 },
        768: { slidesPerView: 2, spaceBetween: 20 },
        992: { slidesPerView: 2, spaceBetween: 20 },
        1200: { slidesPerView: 2, spaceBetween: 20 }
    });

});

