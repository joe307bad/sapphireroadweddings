jQuery(function( $ ){

	// Enable parallax and fade effects on homepage sections
	$(window).scroll(function(){

		scrolltop = $(window).scrollTop()
		scrollwindow = scrolltop + $(window).height();

		$(".home-section-1").css("backgroundPosition", "50% " + -(scrolltop/6) + "px");

		if ( $(".home-section-4").length ) {
		
			sectionthreeoffset = $(".home-section-4").offset().top;		  

			if( scrollwindow > sectionthreeoffset ) {

				// Enable parallax effect
				backgroundscroll = scrollwindow - sectionthreeoffset;
				$(".home-section-4").css("backgroundPosition", "50% " + -(backgroundscroll/6) + "px");

			}
		
		}

	});

});