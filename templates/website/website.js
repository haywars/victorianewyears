$(function() { 

    $('#seo').live('click',function() { // enables the seo button
		page = $(this).attr('page_path')
		uri = $(this).attr('uri');
		website_ide = $(this).attr('website_ide');
		url = window.location.href;
		$.skybox('/admin/seo/webpage/seo-webpage-skybox',{ page_path: page, url: url, uri: uri, website_ide: website_ide });
	});

    $('.current-city').live('click',function(){
        if ( $('.choose-city').is(':visible') ) {
            $('.choose-city').slideUp();
        } else {
            $('.choose-city').slideDown();
        }
        return false;
    });

    $('.choose-city').live('mouseleave',function(){
        $(this).slideUp();
    });

    $('.current-city-sb').on('click',function(){
    	$.skybox('/skybox/markets');
    });

});



	$('#seo').live('click',function() { // enables the seo button
		page = $(this).attr('page_path')
		uri = $(this).attr('uri');
		website_ide = $(this).attr('website_ide');
		url = window.location.href;
		$.skybox('/admin/seo/webpage/seo-webpage-skybox',{ page_path: page, url: url, uri: uri, website_ide: website_ide });
	}) 

    function countdown(year, month, day, hour, prefix) {
    var dateNow = new Date();
    var dateFuture = new Date(year, month - 1, day, hour, 0, 0);
    var amount = dateFuture.getTime() - dateNow.getTime();
    
    if (amount > 0 ) {
        var days = 0; var hours = 0; var mins = 0; var secs = 0;
        amount = Math.floor(amount/1000);
        days = Math.floor(amount/86400).toString();
        amount = amount%86400;
        hours = Math.floor(amount/3600).toString();
        amount = amount%3600;
        mins = Math.floor(amount/60).toString();
        amount = amount%60;
        secs = Math.floor(amount).toString();
        
        for (var i = 0; i < days.length; i++) {
            var char = days.charAt(days.length - i - 1);
            var id = '#' + prefix + 'D' + (3-i);
            $(id).html(char);
        }
        for (var i = 0; i < hours.length; i++) {
            var char = hours.charAt(hours.length - i - 1);
            var id = '#' + prefix + 'H' + (2-i);
            $(id).html(char);
        }
        for (var i = 0; i < mins.length; i++) {
            var char = mins.charAt(mins.length - i - 1);
            var id = '#' + prefix + 'M' + (2-i);
            $(id).html(char);
        }
        for (var i = 0; i < secs.length; i++) {
            var char = secs.charAt(secs.length - i - 1);
            var id = '#' + prefix + 'S' + (2-i);
            $(id).html(char);
        }
    }
}
