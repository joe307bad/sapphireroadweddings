(function ($) {
    var Rentals = {
        init: function () {

            this.BuildRentalsMasonryGrid();

            this.BuildRentalSlider();

        },
        BuildRentalsMasonryGrid: function () {
            var $container = $('#rental-container');

            $container.on( 'layoutComplete', function( event, laidOutItems ) {
                $container.css("opacity", "100");
            } );

            // layout Masonry after each image loads
            // $container.imagesLoaded().progress(function () {
            //     $container.masonry({
            //         // options
            //         itemSelector: '.rental, .single-rental',
            //         columnWidth: 200,
            //         fitWidth: true,
            //         gutter: 20
            //     }).on( 'layoutComplete', function( event, laidOutItems ) {
            //         //$container.css("opacity", "100");
            //     } );
            // });


        },
        BuildRentalSlider: function () {

            var swiper = new Swiper('.swiper-container', {
                loop: true,
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