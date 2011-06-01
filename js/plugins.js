$(document).ready(function () {  
	
  	//Makes the navigation sticky to top of page when page scrolls down
	var msie6 = $.browser == 'msie' && $.browser.version < 7;
	  
	  if (!msie6) {
		var top = $('.navigation').offset().top - parseFloat($('.navigation').css('margin-top').replace(/auto/, 0));
		$(window).scroll(function (event) {
		  var y = $(this).scrollTop();
		  if (y >= top) {
			$('.navigation').addClass('fixed');
			$('.slidey').addClass('nudge');
		  } else {  
			$('.navigation').removeClass('fixed');
			$('.slidey').removeClass('nudge');
		  }
		});
	  }
	
	// Makes buttons look nice
	$("#divButton, #linkButton").button();
	$("#divButton").click(function() {
	  		$("#contactForm").submit();
	  		});
	
	$(".radio").buttonset();
	
	// Smooth Scrolling Buttons
	$("#radio1").click(function() {
		$.scrollTo("#home",1000);
		});		
 	$("#radio2").click(function() {
		$.scrollTo("#employment",800,{
			offset: -111
			});
		});
	$("#radio3").click(function() {
		$.scrollTo("#education",1000,{
			offset: -111
			});
		});
	$("#radio4").click(function() {
		$.scrollTo("#skills",1000,{
			offset: -111
			});
		});
	$("#radio5").click(function() {
		$.scrollTo("#contact",1000,{
			offset: -111
			});
		});	
	
	// Send Message Form
	var options = { 
		target:        '#alert',
		beforeSubmit:  showRequest,
		success:       showResponse
		}; 
	$('#contactForm').ajaxForm(options); 

	function showRequest(formData, jqForm, options) { 
		var queryString = $.param(formData); 
		return true; 
		} 
	function showResponse(responseText, statusText)  {  
		} 
		$.fn.clearForm = function() {
  			return this.each(function() {
				var type = this.type, tag = this.tagName.toLowerCase();
				if (tag == 'form')
					  return $(':input',this).clearForm();
				if (type == 'text' || type == 'password' || tag == 'textarea')
					  this.value = '';
				else if (type == 'checkbox' || type == 'radio')
				  this.checked = false;
				else if (tag == 'select')
				  this.selectedIndex = -1;
	  			})
	  		};
	  	
	 //This highlights the navigation, depending where you are on the page
	 $('.radio').onePageNav({
	 	currentClass: 'ui-state-active',
	 	changeHash: false,
	 	scrollSpeed: 1000
	 	});
	 	
	//Social Media Icons Hover Effect
	$('.icons').mouseenter(function() {
	 	$(this).removeClass('red');
	 	$(this).addClass('blue');
	 	$('.icons').mouseleave(function() {
	 		$(this).addClass('red');
	 		$(this).removeClass('blue');
	 		});
	 });
	 
	//Nice tooltips
	$('.icons a').qtip({
   		style: {
   			top: -100, 
			padding: 3,
			background: '#333333',
			color: '#f6f6f6',
			'text-shadow': 'none',
			'font-size': '11px',
			textAlign: 'center',
			border: {
				radius: 3,
				color: '#333333'
			},
			tip: {
				corner: 'bottomMiddle',
				color: '#333333',
				size: {
					x: 10,
					y : 8
				}
			},
			name: 'dark' // Inherit the rest of the attributes from the preset dark style
		},
   		position: {
      		corner: {
         		target: 'topMiddle',
         		tooltip: 'bottomMiddle'
			}
   		}
	});	  	
  
});