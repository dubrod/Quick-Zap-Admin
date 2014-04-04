$("document").ready( function(){
	$.slidebars();
	
	//NOISY BG'S
		$('body').noisy({
		    intensity: 0.9,
		    size: 200,
		    opacity: 0.08,
		    fallback: 'fallback.png',
		    randomColors: false, // true by default
		    color: '#000000'
		});
		
		$('#sb-site').noisy({
		    intensity: 0.9,
		    size: 200,
		    opacity: 0.04,
		    fallback: 'fallback.png',
		    randomColors: false, // true by default
		    color: '#000000'
		});
		
		$('.sb-slidebar').noisy({
		    intensity: 0.9,
		    size: 200,
		    opacity: 0.04,
		    fallback: 'fallback.png',
		    randomColors: false, // true by default
		    color: '#000000'
		});
		
		$('.data-title').noisy({
		    intensity: 0.9,
		    size: 200,
		    opacity: 0.04,
		    fallback: 'fallback.png',
		    randomColors: false, // true by default
		    color: '#000000'
		});
		
		$('#loginForm h1').noisy({
		    intensity: 0.9,
		    size: 200,
		    opacity: 0.08,
		    fallback: 'fallback.png',
		    randomColors: false, // true by default
		    color: '#000000'
		});
	//EOF NOISY BG'S	
});