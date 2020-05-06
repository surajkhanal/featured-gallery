var swiperH = new Swiper('.swiper-container-h', {
    spaceBetween: 50,
    pagination: {
        el: '.swiper-pagination-h',
        clickable: true,
    },
  
});

var galleryThumbs = new Swiper('.gallery-thumbs', {
    spaceBetween: 10,
    slidesPerView: 5,
    freeMode: true,
    watchSlidesVisibility: true,
    watchSlidesProgress: true,
    preventClicks: false,
    preventClicksPropagation: false,
    slideToClickedSlide: true,
    centeredSlides: true,
    breakpoints: {
        // when window width is >= 320px
        320: {
          slidesPerView: 2,
          spaceBetween: 20
        },
        // when window width is >= 480px
        480: {
          slidesPerView: 3,
          spaceBetween: 30
        },
        // when window width is >= 640px
        640: {
          slidesPerView: 4,
          spaceBetween: 40
        }
      }
});

var swiperV = new Swiper('.swiper-container-v', {
    // direction: 'vertical',
    spaceBetween: 50,
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    },
    nested: true,
});

swiperV.forEach((swiperVEl, index) => {
    swiperVEl.controller.control = galleryThumbs[index];
    swiperVEl.thumbs.swiper = galleryThumbs[index];
    galleryThumbs[index].on('click', function(event) {
        swiperVEl.slideTo(this.activeIndex);
    });
});
