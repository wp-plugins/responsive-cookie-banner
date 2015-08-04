jQuery(function()
{

jQuery('#cookie-banner').hide();

/**
 * Sets a cookie called rcb_cookie that lasts 100 days 
 */

function setCookie()
{
	var d = new Date();
	d.setTime(d.getTime()+(100*24*60*60*1000));
	var expires = d.toGMTString();
	document.cookie = 'rcb_cookie = 1; expires=' + expires + ';' + "domain=." + document.domain + "; path=/;";
}

/**
 * Check if a cookie called rcb_cookie exists 
 * @return true on success false on failure
 */

function checkCookie()
{
	var cookies = document.cookie.split(';');
	var i;

	for(i=0;i<cookies.length;i++)
	{
		var cookie = jQuery.trim(cookies[i]).split('=');

		if(jQuery.inArray('rcb_cookie', cookie) > -1)
		{
			return true;
		}
	}

	return false;
}

// Show the cookie banner if checkCookie() returns false

if(!checkCookie()) 
{
	jQuery('#cookie-banner').prependTo('body').delay(1000).slideDown('slow');

	jQuery('#cookie-banner .accept').click(function()
	{	
		jQuery('#cookie-banner').slideUp('slow');

		setCookie();

		return false; // so # doesn't appear in the url
	})
}

})
