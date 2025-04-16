"use strict";

//===RevolutionSliderActiver===
function revolutionSliderActiver () {
	if ($('.rev_slider_wrapper #slider1').length) {
		$("#slider1").revolution({
			sliderType:"standard",
			sliderLayout:"auto",
			delay:5000,
            startwidth:1170,
		    startheight:600,

            navigationType:"bullet",
		    navigationArrows:"0",
		    navigationStyle:"preview3",

            dottedOverlay:'yes',

            hideTimerBar:"off",
            onHoverStop:"off",
			navigation: {
				arrows:{enable:true}
			},
            gridwidth: [1170],
            gridheight: [600]
		});
	};
}

//====Main menu===
function mainmenu() {
	//Submenu Dropdown Toggle
	if($('.main-menu li.dropdown ul').length){
		$('.main-menu li.dropdown').append('<div class="dropdown-btn"></div>');

		//Dropdown Button
		$('.main-menu li.dropdown .dropdown-btn').on('click', function() {
			$(this).prev('ul').slideToggle(500);
		});
	}
}

//===Header Sticky===
function stickyHeader() {
    if ($('.stricky').length) {
        var strickyScrollPos = 100;
        if ($(window).scrollTop() > strickyScrollPos) {
            $('.stricky').addClass('stricky-fixed');
            $('.scroll-to-top').fadeIn(1500);
        } else if ($(this).scrollTop() <= strickyScrollPos) {
            $('.stricky').removeClass('stricky-fixed');
            $('.scroll-to-top').fadeOut(1500);
        }
    };
}

//  scoll to Top
function scrollToTop() {
    if ($('.scroll-to-target').length) {
        $(".scroll-to-target").on('click', function() {
            var target = $(this).attr('data-target');
            // animate
            $('html, body').animate({
                scrollTop: $(target).offset().top
            }, 1000);

        });
    }
}


// ===Prealoder===
function prealoader() {
    if($('.preloader').length){
        $('.preloader').delay(2000).fadeOut(500);
    }
}


// Search Toggle Btn
function search () {
    if($('.toggle-search').length){
        $('.toggle-search').on('click', function() {
            $('.header-search').slideToggle(300);
        });

    }
}

//===Prettyphoto Lightbox===
function prettyPhoto() {
    $("a[data-rel^='prettyPhoto']").prettyPhoto({
        animation_speed:'normal',
        slideshow:3000,
        autoplay_slideshow: false,
        fullscreen: true,
        social_tools: false
    });
}

//===Team Carousel===
function teamCarousel () {
    if ($('.team-carousel').length) {
        $('.team-carousel').owlCarousel({
            dots: false,
            loop:true,
            margin:30,
            nav:true,
            navText: [
                '<i class="fa fa-angle-left"></i>',
                '<i class="fa fa-angle-right"></i>'
            ],
            autoplayHoverPause: false,
            autoplay: 6000,
            smartSpeed: 1000,
            responsive:{
                0:{
                    items:1
                },
                600:{
                    items:2
                },
                800:{
                    items:2
                },
                1024:{
                    items:3
                },
                1100:{
                    items:4
                },
                1200:{
                    items:4
                }
            }
        });
    }
}

//=== Choose Carousel===
function chooseCarousel () {
    if ($('.choose-carousel').length) {
        $('.choose-carousel').owlCarousel({
            dots: true,
            loop:true,
            margin:30,
            nav:false,
            navText: [
                '<i class="fa fa-angle-left"></i>',
                '<i class="fa fa-angle-right"></i>'
            ],
            autoplayHoverPause: false,
            autoplay: 6000,
            smartSpeed: 1000,
            responsive:{
                0:{
                    items:1
                },
                600:{
                    items:2
                },
                800:{
                    items:3
                },
                1024:{
                    items:3
                },
                1100:{
                    items:4
                },
                1200:{
                    items:4
                }
            }
        });
    }
}

//===Brand Carousel===
function brandCarousel () {
    if ($('.brand').length) {
        $('.brand').owlCarousel({
            dots: false,
            loop:true,
            margin:30,
            nav:true,
            navText: [
                '<i class="fa fa-angle-left"></i>',
                '<i class="fa fa-angle-right"></i>'
            ],
            autoplayHoverPause: false,
            autoplay: 4000,
            smartSpeed: 1000,
            responsive:{
                0:{
                    items:1
                },
                600:{
                    items:2
                },
                800:{
                    items:3
                },
                1024:{
                    items:4
                },
                1100:{
                    items:4
                },
                1200:{
                    items:5
                }
            }
        });
    }
}

//===Testimonial Slider===
function testimonialSlider() {
    if ($('.testimonial-carousel').length) {
        $('.testimonial-carousel').owlCarousel({
            loop:true,
            margin:30,
            nav:true,
            dots: false,
            autoplayHoverPause:false,
            autoplay: 4000,
            smartSpeed: 700,
            navText: [ '<span class="fa fa-angle-left"></span>', '<span class="fa fa-angle-right"></span>' ],
            responsive:{
                0:{
                    items:1
                },
                600:{
                    items:1
                },
                800:{
                    items:2
                },
                1024:{
                    items:2
                },
                1100:{
                    items:2
                },
                1200:{
                    items:3
                }
            }
        })
    }
}

//Accordion Box
function accordion() {
    if($('.accordion-box').length){
        $(".accordion-box").on('click', '.accord-btn', function() {

            if($(this).hasClass('active')!==true){
            $('.accordion .accord-btn').removeClass('active');

            }

            if ($(this).next('.accord-content').is(':visible')){
                $(this).removeClass('active');
                $(this).next('.accord-content').slideUp(500);
            }else{
                $(this).addClass('active');
                $('.accordion .accord-content').slideUp(500);
                $(this).next('.accord-content').slideDown(500);
            }
        });
    }
}


//Contact Form Validation
if($("#contact-form").length){
    $("#contact-form").validate({
        submitHandler: function(form) {
          var form_btn = $(form).find('button[type="submit"]');
          var form_result_div = '#form-result';
          $(form_result_div).remove();
          // form_btn.before('<div id="form-result" class="alert alert-success" role="alert" style="display: none;"></div>');
          var form_btn_old_msg = form_btn.html();
          form_btn.html(form_btn.prop('disabled', true).data("loading-text"));
          $(form).ajaxSubmit({
            dataType:  'json',
            success: function(data) {
              if( data.status = 'true' ) {
                $(form).find('.form-control').val('');
              }
              form_btn.prop('disabled', false).html(form_btn_old_msg);
              $(form_result_div).html(data.message).fadeIn('slow');
              setTimeout(function(){ $(form_result_div).fadeOut('slow') }, 6000);
            }
          });
        }
    });
}

//=== Thm scroll anim===
function thmScrollAnim() {
    if ($('.wow').length) {
        var wow = new WOW({
            mobile: false
        });
        wow.init();
    };
}


// Dom Ready Function
jQuery(document).on('ready', function () {
	(function ($) {
        // add your functions
        revolutionSliderActiver ();
        mainmenu ();
        search ();
        teamCarousel ();
        chooseCarousel ();
        brandCarousel ();
        testimonialSlider ();
        scrollToTop ();
        prettyPhoto ();
        accordion ();
        thmScrollAnim();
	})(jQuery);
});


jQuery(window).on('scroll', function(){
	(function ($) {
	stickyHeader();
	})(jQuery);
});


// Instance Of Fuction while Window Load event
jQuery(window).on('load', function() {
    (function($) {
        prealoader ();
    })(jQuery);
});


function validateZipCode(input, blankErrorEl, invalidErrorEl) {
    const zipValue = input.value.trim();
    const zipRegex = /^\d{5}(-\d{4})?$/; // Match 5 digits or 5+4 format

    // Hide all errors initially
    blankErrorEl.style.display = 'none';
    invalidErrorEl.style.display = 'none';

    if (!zipValue) {
        blankErrorEl.style.display = 'block';
    } else if (!zipRegex.test(zipValue)) {
        invalidErrorEl.style.display = 'block';
    }
}

// Apply validation to all forms with the class `zip-code-form`
document.querySelectorAll('.zip-code-form').forEach((form) => {
    const zipInput = form.querySelector('.zip-code-input');
    const blankError = form.querySelector('.blank-error');
    const invalidError = form.querySelector('.invalid-error');

    // Add input event listener for real-time validation
    zipInput.addEventListener('input', () => {
        validateZipCode(zipInput, blankError, invalidError);
    });

    // Add submit event listener for final validation
    form.addEventListener('submit', (e) => {
        validateZipCode(zipInput, blankError, invalidError);

        const isBlankErrorVisible = blankError.style.display !== 'none';
        const isInvalidErrorVisible = invalidError.style.display !== 'none';

        if (isBlankErrorVisible || isInvalidErrorVisible) {
            e.preventDefault(); // Prevent form submission if errors are visible
        } else {
            // alert('ZIP Code is valid! Proceeding...');
            // Add form submission logic here
        }
    });
});


