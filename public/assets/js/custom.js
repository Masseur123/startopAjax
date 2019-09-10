/* ------------------------------------------------------------------------------
 *
 *  # Custom JS code
 *
 *  Place here all your custom js. Make sure it's loaded after app.js
 *
 * ---------------------------------------------------------------------------- */

$(function () {

	// Le code jQuery sera inséré ici

	$(".fade-error").fadeIn("200", function () {
		$(".fade-error").delay(10000).fadeOut(400);
	});

	$(".fade-error").click(function () {
		$(".fade-error").clearQueue().fadeOut(400);
	});

}); 