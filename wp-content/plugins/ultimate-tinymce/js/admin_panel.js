//<![CDATA[ 

// Used to set the checkboxes for buttons group 1
jQuery(document).ready( 
	function($) { 
		$("#allsts").click(function() { 
			$(".one").attr("checked", true); 
		}); 
		$("#nosts").click(function() { 
			$(".one").attr("checked", false); 
		}); 
		$(".one" ).each( function() { 
			var isitchecked = this.checked; 
		}); 
	}
);

// Used to set the checkboxes for buttons group 2
jQuery(document).ready(	
	function($) { 
		$("#allsts2").click(function() { 
			$(".two").attr("checked", true); 
		}); 
		$("#nosts2").click(function() { 
			$(".two").attr("checked", false); 
		}); 
		$(".two" ).each( function() { 
			var isitchecked = this.checked; 
		});
	}
);

// Used to set the checkboxes for buttons for other plugins
jQuery(document).ready( 
	function($) { 
		$("#allsts3").click(function() { 
			$(".three").attr("checked", true); 
		}); 
		$("#nosts3").click(function() { 
			$(".three").attr("checked", false); 
		}); 
		$(".three" ).each( function() { 
			var isitchecked = this.checked; 
		}); 
	}
);

// Used for animating the slide for premium addons in the top tabbed area
jQuery(document).ready( 
	function($) { 
		$("#clickme").click(function() { 
			$("#me").animate({ height: "toggle" }, 300 ); 
		}); 
	}
);
// Used for animating the slide for premium addons in the top tabbed area
jQuery(document).ready( 
	function($) { 
		$("#clickme2").click(function() { 
			$("#me2").animate({ height: "toggle" }, 300 ); 
		}); 
	}
);
// Used for animating the slide for premium addons in the top tabbed area
jQuery(document).ready( 
	function($) { 
		$("#clickme3").click(function() { 
			$("#me3").animate({ height: "toggle" }, 300 ); 
		}); 
	}
);
// Used for animating the slide for premium addons in the top tabbed area
jQuery(document).ready( 
	function($) { 
		$("#clickme4").click(function() { 
			$("#me4").animate({ height: "toggle" }, 300 );
		}); 
	}
);
// Used for animating the slide for premium addons in the top tabbed area
jQuery(document).ready( 
	function($) { 
		$("#clickme5").click(function() { 
			$("#me5").animate({ height: "toggle" }, 300 ); 
		}); 
	}
);
// Used for animating the slide for premium addons in the top tabbed area
jQuery(document).ready( 
	function($) { 
		$("#clickme6").click(function() { 
			$("#me6").animate({ height: "toggle" }, 300 ); 
		}); 
	}
);
// Used for animating the slide for premium addons in the top tabbed area
jQuery(document).ready( 
	function($) { 
		$("#clickme7").click(function() { 
			$("#me7").animate({ height: "toggle" }, 300 ); 
		}); 
	}
);


// Used to animate through the main tabs section     NOTE:  Need to switch this to the jquery tabs.
jQuery(document).ready( 
	function($){  
		$(".menu > li").click(function(e){ 
			switch(e.target.id){  
				case "news": $("#news").addClass("active"); $("#tutorials").removeClass("active"); $("#spread").removeClass("active"); $("#gettingstarted").removeClass("active"); $("#tips").removeClass("active"); $("#defaults").removeClass("active"); $("#links").removeClass("active"); $("div.news").fadeIn(); $("div.tutorials").css("display", "none"); $("div.spread").css("display", "none"); $("div.gettingstarted").css("display", "none"); $("div.tips").css("display", "none"); $("div.defaults").css("display", "none"); $("div.links").css("display", "none");  
				break;  
				case "tutorials": $("#news").removeClass("active"); $("#tutorials").addClass("active"); $("#spread").removeClass("active"); $("#gettingstarted").removeClass("active"); $("#tips").removeClass("active"); $("#defaults").removeClass("active"); $("#links").removeClass("active"); $("div.tutorials").fadeIn(); $("div.news").css("display", "none"); $("div.spread").css("display", "none"); $("div.gettingstarted").css("display", "none"); $("div.tips").css("display", "none"); $("div.defaults").css("display", "none"); $("div.links").css("display", "none");  
				break; 
				case "spread": $("#news").removeClass("active"); $("#tutorials").removeClass("active"); $("#spread").addClass("active"); $("#gettingstarted").removeClass("active"); $("#tips").removeClass("active"); $("#defaults").removeClass("active"); $("#links").removeClass("active"); $("div.spread").fadeIn(); $("div.tips").css("display", "none"); $("div.news").css("display", "none"); $("div.gettingstarted").css("display", "none"); $("div.tutorials").css("display", "none"); $("div.links").css("display", "none"); $("div.defaults").css("display", "none");
				break; 
				case "gettingstarted": $("#news").removeClass("active"); $("#tutorials").removeClass("active"); $("#spread").removeClass("active"); $("#gettingstarted").addClass("active"); $("#tips").removeClass("active"); $("#defaults").removeClass("active"); $("#links").removeClass("active"); $("div.gettingstarted").fadeIn(); $("div.spread").css("display", "none"); $("div.tips").css("display", "none"); $("div.news").css("display", "none"); $("div.tutorials").css("display", "none"); $("div.links").css("display", "none"); $("div.defaults").css("display", "none");
				break; 
				case "tips": $("#news").removeClass("active"); $("#tutorials").removeClass("active"); $("#spread").removeClass("active"); $("#gettingstarted").removeClass("active"); $("#tips").addClass("active"); $("#defaults").removeClass("active"); $("#links").removeClass("active"); $("div.tips").fadeIn(); $("div.spread").css("display", "none"); $("div.gettingstarted").css("display", "none"); $("div.news").css("display", "none"); $("div.tutorials").css("display", "none"); $("div.links").css("display", "none"); $("div.defaults").css("display", "none");
				break; 
				case "defaults": $("#news").removeClass("active"); $("#tutorials").removeClass("active"); $("#spread").removeClass("active"); $("#gettingstarted").removeClass("active"); $("#defaults").addClass("active"); $("#tips").removeClass("active"); $("#links").removeClass("active"); $("div.defaults").fadeIn(); $("div.tips").css("display", "none"); $("div.spread").css("display", "none"); $("div.gettingstarted").css("display", "none"); $("div.news").css("display", "none"); $("div.tutorials").css("display", "none"); $("div.links").css("display", "none");
				break; 
				case "links": $("#news").removeClass("active"); $("#tutorials").removeClass("active"); $("#spread").removeClass("active"); $("#gettingstarted").removeClass("active"); $("#tips").removeClass("active"); $("#defaults").removeClass("active"); $("#links").addClass("active"); $("div.links").fadeIn(); $("div.news").css("display", "none"); $("div.tutorials").css("display", "none"); $("div.spread").css("display", "none"); $("div.gettingstarted").css("display", "none"); $("div.tips").css("display", "none"); $("div.defaults").css("display", "none");
				break;  
			} return false; 
		});  
	}
);


// Used for setting the checkboxes via a master control
jQuery(document).ready( 
	function($) {
		$('select[name="masterBox"]').change(function(){
			$('.actionList option[value="'+$(this).val()+'"]').attr('selected','selected'); 
		});
		$('select[name="masterBox2"]').change(function(){
			$('.actionList2 option[value="'+$(this).val()+'"]').attr('selected','selected'); 
		});
		$('select[name="masterBox3"]').change(function(){
			$('.actionList3 option[value="'+$(this).val()+'"]').attr('selected','selected'); 
		});
	}
);

// Used to show/hide the additional qr code options
jQuery(document).ready( 
	function($) {
    	//Hide div w/id jwl_hide
		if ($("#jwl_qr_code").is(":checked") || $("#jwl_qr_code_pages").is(":checked")) { 
			$('.jwl_hide').fadeIn('slow', function() { 
				$(".jwl_hide").css("display","block"); 
			}); 
		} else { 
			$('.jwl_hide').fadeOut('fast', function() { 
				$(".jwl_hide").css("display","none"); 
			}); 
		} 
		
		$("#jwl_qr_code").click(function(){ 
			$('.jwl_hide').fadeIn('slow', function() { 
				if ($("#jwl_qr_code").is(":checked") || $("#jwl_qr_code_pages").is(":checked")) { 
					$(".jwl_hide").css("display","block"); 
				} else { 
					$('.jwl_hide').fadeOut('slow', function() { 
						$(".jwl_hide").css("display","none"); 
					}); 
				} 
			}); 
		}); 
		
		$("#jwl_qr_code_pages").click(function(){ 
			$('.jwl_hide').fadeIn('slow', function() { 
				if ($("#jwl_qr_code_pages").is(":checked") || $("#jwl_qr_code").is(":checked")) { 
					$(".jwl_hide").css("display","block"); 
				} else { 
					$('.jwl_hide').fadeOut('slow', function() {  
						$(".jwl_hide").css("display","none"); 
					}); 
				} 
			}); 
		});
	}
);

// Used for scrolling to top in admin panel
jQuery(document).ready( 
	function($) {
 		
		//Check to see if the window is top if not then display button
		$(window).scroll(function(){
			if ($(this).scrollTop() > 100) {
				$('.scrollToTop').fadeIn();
			} else {
				$('.scrollToTop').fadeOut();
			}
		});
		
		//Click event to scroll to top
		$('.scrollToTop').click(function(){
			$('html, body').animate({scrollTop : 0},800);
			return false;
		});
		
	}
);
  
//]]>