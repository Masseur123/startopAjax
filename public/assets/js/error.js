
/* ------------------------------------------------------------------------------
 *
 *  # Custom JS code
 *
 *  Place here all your error js. 
 *  Use to fadein and fadeout error div 
 *
 * ---------------------------------------------------------------------------- */

$('.nav-link').click(function () {
	$('ul li a[class="nav-link active"]').toggleClass('nav-link active', false).toggleClass('nav-link', true);
	$(this).toggleClass('nav-link', false).toggleClass('nav-link active', true);
});

/*$('.nav-link btn-link').click(function() {
	$('ul li a[class="nav-link active btn-link"]').toggleClass('nav-link active btn-link', false).toggleClass('nav-link btn-link', true);
	$(this).toggleClass('nav-link btn-link', false).toggleClass('nav-link active btn-link', true);
});*/