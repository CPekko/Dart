//Set "x time ago".
$('span[data-time]').each(function(i, elem){
	$(elem).html(moment($(elem).data('time')).fromNow());
});