
// Scripts used by the theme



// Noconflict
var $str = jQuery.noConflict();


// Check if an iOS device
function bentoCheckDevice() {
	var iDevices = [
		'iPad Simulator',
		'iPhone Simulator',
		'iPod Simulator',
		'iPad',
		'iPhone',
		'iPod',
		'Mac68K',
		'MacPPC',
		'MacIntel'
	];
	if ( !!navigator.platform ) {
		while ( iDevices.length ) {
			if ( navigator.platform === iDevices.pop() ) { 
				return true; 
			}
		}
	}
	return false;
}

function topBar() {
	if ( $str(window).scrollTop() > 800 ) {
		$str('.review-topbar').fadeIn();
	} else {
		$str('.review-topbar').fadeOut();
	}
}

function copyToClipboard(element) {
	var $temp = $str("<input>");
	var txt = element.text();
	txt = txt.replace(/\s+/g,'');
	$str("body").append($temp);
	$temp.val(txt).select();
	console.log(txt);
	document.execCommand("copy");
	$temp.remove();
}


$str(document).ready(function() {
	
	
	// Submenu animations
	$str('.site-wrapper').on( 'mouseenter mouseleave', '.primary-menu .menu-item-has-children', function(ev) {
		var parentMenu = $str(this);
		var submPos = parentMenu.offset().left;
		var windowWidth = $str(window).width();
		if ( parentMenu.parents('.menu-item-has-children').length ) {
			var subsubOff = submPos + 400; // 200 for each submenu width
			if ( subsubOff > windowWidth ) {
				parentMenu.children('.sub-menu').css({'left':'auto','right':'100%'});
			}
		} else {
			var subsubOff = submPos + 200; // 200 is the submenu width
			if ( subsubOff > windowWidth ) {
				parentMenu.children('.sub-menu').css({'right':'0'});
			}
		}
		if ( ev.type === 'mouseenter' ) {
			$str(this).children('.sub-menu').fadeIn(200);
		} else {
			$str(this).children('.sub-menu').fadeOut(200);
		}	
	});
		
	
	// Mobile menu animations
	$str('.mobile-menu-trigger-container').click(function() {	
		var device = bentoCheckDevice();
		if ( device == false ) {
			$str('body').addClass('mobile-menu-open');
		}
		$str('.mobile-menu-shadow').fadeIn(500);
		$str('#nav-mobile').css("left", '0');
	});
	$str('.mobile-menu-close,.mobile-menu-shadow').click(function() {
		if ( $str('body').hasClass('mobile-menu-open') ) {
			$str('body').removeClass('mobile-menu-open');
		}
		$str('.mobile-menu-shadow').fadeOut(500);
		$str('#nav-mobile').css("left", '-100%');
	});
	
	
	// Comment form labels
	$str('.comment-form-field input').focus(function() {
		$str(this).siblings('label').fadeIn(500);
	}).blur(function() {
		if ( ! $str(this).val() ) {
			$str(this).siblings('label').fadeOut(500);
		}
	});
	if ( $str('.comment-form-field input').val() ) {
		$str(this).siblings('label').css('display','block');	
	}
	
	
	// Responsive videos
	$str('.entry-content iframe[src*="youtube.com"],.entry-content iframe[src*="vimeo.com"]').each(function() {
		$str(this).parent().fitVids();
	});
	
	
	// GA events
	$str('.review-visit a').click(function() {
		var br = $str(this).data('broker');
		ga('send', 'event', 'aff', 'click', br );
	});
	
	
	// Front page tabs
	$str('.front-list-tab-clickable').click(function() {
		var type = $str(this).data('type');
		$str('.front-list-tab-active').removeClass('front-list-tab-active');
		$str(this).addClass('front-list-tab-active');
		$str('.front-list-'+type).show();
		$str('.front-list-items:not(.front-list-'+type+')').hide();
		$str('.front-list-tab-slogan').text($str(this).data('slogan')+':');
	});
	
	
	// Display mobile top bar
	topBar();
	
	
	// My IP tool: copy to clipboard
	$str('.myip-ip-group').click(function() {
		$ip = $str(this).find('.myip-ip');
		copyToClipboard($ip);
		$msg = $str('<div class="message-copied">copied!</div>').fadeIn( 500, function() {
			$str(this).fadeOut( 3000, function() {
				$str(this).remove();
			});
		});
		$str(this).append($msg);
	});
	
	
	// Roll Dice Online
	var dicetype = $str('#rdoselect').val();
	$str('#rdoselect').on("change paste keyup", function() {
		dicetype = $str(this).val();
	});
	var roll = function() {
		var sides = dicetype;
		$str('#rdoselect').on("change paste keyup", function() {
			sides = $str(this).val();
		});
		var randomNumber = Math.floor(Math.random() * parseInt(sides)) + 1;
		console.log('sides-'+sides);
		return randomNumber;
	}
	var dicetimes = $str('.rdotool-rolls input').val();
	$str('.rdotool-rolls input').on("change paste keyup", function() {
		dicetimes = $str(this).val();
	});
	$str('body').on('click','.rdo-roll',function() {
		console.log(dicetype+'--'+dicetimes);
		if ( dicetimes > 100 ) {
			dicetimes = 100;
		}
		var result = '';
		for ( i = 0; i < dicetimes; i++ ) {
			result += roll()+' ';
		} 
		$str('.rdoresult').html(result);
	});
		
		
});


$str(window).load(function () {
	
	
	// Scroll to the correct position for hash URLs
	if ( window.location.hash ) {
		var bento_cleanhash = window.location.hash.substr(1);
		if ( $str('#' + bento_cleanhash).length ) {
			var bento_headerHeight = 0;
			if ( bentoThemeVars.fixed_menu == 1 ) {
				bento_headerHeight = $str('.site-header').outerHeight(true);
			}
			scrollPosition = $str('#' + bento_cleanhash).offset().top - bento_headerHeight - 10;
			$str('html, body').animate( { scrollTop: scrollPosition }, 1 );
		}
	}


});


$str(window).resize(function () {
		
	
	// Display mobile top bar
	topBar();
	

});


$str(window).scroll(function () {
	
	
	// Display mobile top bar
	topBar();
	
	
});