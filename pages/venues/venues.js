$(document).ready(function(){
//check if we are on profile page
	if($('#grid_content').length>0){
		$('#venue_title').corner('tr');
		$("#grid_content").masonry({
			singleMode: true,
			resizeable: true
		});
	}
});

function notify_me(ide,ct_holiday_ide) {
	var title = $('h1').text();
	$.skybox_form('ct_notify_me',{
							'venue_ide':ide,
							'ct_holiday_ide':ct_holiday_ide,
							'width':540,
							'title':'<h2 class="notifyme_header">'+title+' Tickets <br>Not Yet Available</h2>',
							'timeout':2500,
							'onSuccessFn':'onSuccessFnA'});
}
function onSuccessFnA(data){
	if ( trimString(data).indexOf('<!--saved-->') != 0 ) {
		$('#ct_notify_me_message').html(data);
		$.scrollTo(0,1000);
	} else { 
		var needle = '<!--ide=';
		var start = data.indexOf(needle) + needle.length;
		var end = data.indexOf('-->',start);
		var ide = data.substring(start,end);
		$('#ct_notify_me_message').html('<div id = "notify_thanks">Thank you. Your information has been submitted.</div>');
	}
}
function notify_me_submit(theform,ide) {

}
function search_venue(){
	var venue_type_ide = $('#venue_type_ide').val();
	var q = $('#venue_search').val();
	if(q=='Enter venue name'){
		q='';
	}
	$('#venue_search_result').
		html('<center><img src="/images/loading3.gif"/></center>').
		load('/'+market_slug+'/_venue_type.slug_/includes/venue-listing',{q:q,venue_type_ide:venue_type_ide});
	
}