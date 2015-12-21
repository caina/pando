/* Sidebar navigation */
/* ------------------ */

/* Show navigation when the width is greather than or equal to 991px */

$(document).ready(function(){

  $(window).resize(function()
  {
    if($(window).width() >= 767){
      $(".sidey").slideDown(150);
    }                
  });

});

$(document).ready(function(){

  $(".has_submenu > a").click(function(e){
    e.preventDefault();
    var menu_li = $(this).parent("li");
    var menu_ul = $(this).next("ul");

    if(menu_li.hasClass("open")){
      menu_ul.slideUp(150);
      menu_li.removeClass("open")
    }
    else{
      $(".nav > li > ul").slideUp(150);
      $(".nav > li").removeClass("open");
      menu_ul.slideDown(150);
      menu_li.addClass("open");
    }
  });
  
});

/* Sidebar dropdown */

$(document).ready(function(){
  $(".sidebar-dropdown a").on('click',function(e){
      e.preventDefault();

      if(!$(this).hasClass("open")) {
        // hide any open menus and remove all other classes
        $(".sidebar .sidey").slideUp(150);
        $(".sidebar-dropdown a").removeClass("open");
        
        // open our new menu and add the open class
        $(".sidebar .sidey").slideDown(150);
        $(this).addClass("open");
      }
      
      else if($(this).hasClass("open")) {
        $(this).removeClass("open");
        $(".sidebar .sidey").slideUp(150);
      }
  });

});



/* ***************************** */

/* Revolution Slider JS */
/* ----------------------------- */
var revapi;
	jQuery(document).ready(function() {

		   revapi = jQuery('.banner').revolution(
			{
				delay:8000,
				
				startheight:550,
				hideThumbs:10,
				
				navigationType:"none",		// navigation bullet display
				onHoverStop:"on",
				hideThumbsOnMobile:"off",	// thumb is not responsive on mobile view
				
				touchenabled:"on",			//touch enable on the slide
				hideArrowsOnMobile: "on",
				
				stopAtSlide:-1,				// slide loop for infinite time
				stopAfterLoops:-1,
				
				hideCaptionAtLimit:0,
				fullWidth:"off",
				forceFullWidth:"on",
				
				shadow:0					// bottom shadow of the slider you have possible value choise(0, 1, 2, ..)

			});

	});	


/* ***************************** */

/* Way point for feature block */
/* ----------------------- */
$(document).ready(function(){

	$('.f-one').waypoint(function(down){
		$(this).addClass('animation');
		$(this).addClass('flipInY');
	}, { offset: '75%' });

	$('.f-two').waypoint(function(down){
		$(this).addClass('animation');
		$(this).addClass('flipInY');
	}, { offset: '75%' });

	$('.f-three').waypoint(function(down){
		$(this).addClass('animation');
		$(this).addClass('flipInY');
	}, { offset: '75%' });

	$('.f-four').waypoint(function(down){
		$(this).addClass('animation');
		$(this).addClass('flipInY');
	}, { offset: '75%' });

});
/* ************************************** */

/* Way point for Service-one block */
/* ----------------------- */


	$('.sitem-one').waypoint(function(down){
		$(this).addClass('br-red');
		$(this).addClass('color-change');
	}, { offset: '65%' });

	$('.sitem-two').waypoint(function(down){
		$(this).addClass('br-lblue');
		$(this).addClass('color-change');
	}, { offset: '65%' });

	$('.sitem-three').waypoint(function(down){
		$(this).addClass('br-green');
		$(this).addClass('color-change');
	}, { offset: '65%' });

/* ************************************** */


/* PrettyPhoto for Recent Post */
/* ----------------------- */

$(".p-item-link").prettyPhoto({
   overlay_gallery: false, social_tools: false
});

/* *************************************** */  




/* *************************************** */  
/* Way point for Information block */
/* ----------------------- */

$(document).ready(function(){

	$('.s-one').waypoint(function(down){
		$(this).addClass('animation');
		$(this).addClass('bounceInDown');
	}, { offset: '55%' });

	$('.s-two').waypoint(function(down){
		$(this).addClass('animation');
		$(this).addClass('bounceInDown');
	}, { offset: '55%' });

	$('.s-three').waypoint(function(down){
		$(this).addClass('animation');
		$(this).addClass('bounceInDown');
	}, { offset: '55%' });

	$('.s-four').waypoint(function(down){
		$(this).addClass('animation');
		$(this).addClass('bounceInDown');
	}, { offset: '55%' });

});

/* ************************************** */


/* Tooltip */
/* -------- */

$('.bs-tooltip').tooltip();


/* ************************************** */

/* Scroll to Top */
/* ------------- */

$(document).ready(function(){
  $(".totop").hide();

  $(function(){
    $(window).scroll(function(){
      if ($(this).scrollTop()>300)
      {
        $('.totop').slideDown();
      } 
      else
      {
        $('.totop').slideUp();
      }
    });

    $('.totop a').click(function (e) {
      e.preventDefault();
      $('body,html').animate({scrollTop: 0}, 500);
    });

  });
});

/* ******************************************* */

/* ************** Way point js ****************** */

/* Map */

$(document).ready(function(){

	$('.c-map i').waypoint(function(down) {
			$(this).addClass('animation');
			$(this).addClass('fadeInDown');
		}, { offset: '65%' });
		
	$('.c-map span.label').waypoint(function(down) {
			$(this).addClass('animation');
			$(this).addClass('fadeInDown');
		}, { offset: '64%' });
		
});
/* ******************************************* */

/* *************************************** */  

/* Progressbar animation */
/* --------------------- */

$(document).ready(function(){
    setTimeout(function(){

        $('.bar .progress .progress-bar').each(function() {
            var $bar = $(this);
            var $perc = $bar.attr("aria-valuemax");

            var current_perc = 0;

            var progress = setInterval(function() {
                if (current_perc>=$perc) {
                    clearInterval(progress);
                } else {
                    current_perc +=8;
                    $bar.css('width', (current_perc)+'%');
                }


            }, 500);

        });

    },500);
});

/* *************************************** */  

/* PrettyPhoto for Gallery */
/* ----------------------- */

$(".prettyphoto").prettyPhoto({
   overlay_gallery: false, social_tools: false
});
        

/* Isotype */

// cache container
var $container = $('#portfolio');
// initialize isotope
$container.isotope({
  // options...
});

// filter items when filter link is clicked
$('#filters a').click(function(){
  var selector = $(this).attr('data-filter');
  $container.isotope({ filter: selector });
  return false;
});           
        
/* *************************************** */  