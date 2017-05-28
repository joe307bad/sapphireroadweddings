(function ($) {
    var Rentals = {
        init: function () {

            this.BuildRentalsMasonryGrid();

            this.BuildRentalSlider();

        },
        BuildRentalsMasonryGrid: function () {
            $('#rental-container').masonry({
                // options
                itemSelector: '.rental',
                columnWidth: 200,
                fitWidth: true,
                gutter: 20
            });
        },
        BuildRentalSlider: function(){

            var swiper = new Swiper('.swiper-container', {
                pagination: '.swiper-pagination',
                paginationClickable: true,
                autoHeight: true,
                nextButton: '.swiper-button-next',
                prevButton: '.swiper-button-prev'
            });
        }
    };

    Rentals.init();

})(jQuery);