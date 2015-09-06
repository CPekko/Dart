function cancel(event){
	event.preventDefault();
	window.location.href='http://folk.ntnu.no/eiriknf/dart/players';
}

function save(event){
	event.preventDefault();
	var base = 'http://folk.ntnu.no/eiriknf/dart/players/edit/';
	var name = $('#name').val();
	var url = $('#url').val();
	var active = $('#active').is(':checked') ? 1 : 0;
	if ($('#name[data-player-id]').length){
		window.location.href = base + '?id=' + $('#name[data-player-id]').data('player-id') + '&name=' + name + '&active=' + active + '&url=' + url;
	} else{
		window.location.href = base + '?name=' + name + '&active=' + active + '&url=' + url;
	}
}