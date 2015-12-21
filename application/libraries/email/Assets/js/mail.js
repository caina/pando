$(document).ready(function() {
		$('#email-list li > .star > a').on('click', function() {
			$(this).toggleClass('starred');
		});
		
		$(".has-tooltip").each(function (index, el) {
			$(el).tooltip({
				placement: $(this).data("placement") || 'bottom'
			});
		});
		
		// setHeightEmailContent();
		
		// initEmailScroller();
		
		$(".clickable-row > div:not(.chbox,.star)").click(function(e) {
			if ((e.target instanceof HTMLAnchorElement) == true) {
				return;
			}
			
			var href = $(this).parent().data('href');
			
			if (href != '' && typeof href != 'undefined') {
				window.document.location = href;
			}
		});
	});
	
	// $(window).smartresize(function(){
	// 	setHeightEmailContent();
		
	// 	initEmailScroller();
	// });
	
	function setHeightEmailContent() {
		if ($( document ).width() >= 992) {
			var windowHeight = $(window).height();
			var staticContentH = $('#header-navbar').outerHeight() + $('#email-header').outerHeight();
			staticContentH += ($('#email-box').outerHeight() - $('#email-box').height());
	
			$('#email-content').css('height', windowHeight - staticContentH);
		}
		else {
			$('#email-content').css('height', '');
		}
	}
	
	function initEmailScroller() {
		if ($( document ).width() >= 992) {
			$('#email-navigation').nanoScroller({
		    	alwaysVisible: false,
		    	iOSNativeScrolling: false,
		    	preventPageScrolling: true,
		    	contentClass: 'email-nav-nano-content'
		    });
			
			$('#email-content').nanoScroller({
		    	alwaysVisible: false,
		    	iOSNativeScrolling: false,
		    	preventPageScrolling: true,
		    	contentClass: 'email-content-nano-content'
		    });
		}
	}