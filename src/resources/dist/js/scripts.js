$(document).ready(function() {
	$(document).on('click', 'a.expand-code', function(event) {
		event.preventDefault();
		if($(this).hasClass('add'))
		{
			$(this).parent('.sdf-error-message').next().slideDown();
			$(this).removeClass('add').addClass('minus');
		}
		else
		{
			$(this).parent('.sdf-error-message').next().slideUp();
			$(this).addClass('add').removeClass('minus');
		}
	});
});