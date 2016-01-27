//Highlight each players best game
var bestGames = {};
$( "td[data-player-id]" ).each(function( index ) {
	if (typeof bestGames[$( this ).data('player-id')] === 'undefined'){
		bestGames[$( this ).data('player-id')] = { id: $( this ).attr('id'), score: parseInt($( this ).text(),10)};
	}
	else if (bestGames[$( this ).data('player-id')].score >= parseInt($( this ).text(),10)) {
		bestGames[$( this ).data('player-id')] = { id: $( this ).attr('id'), score: parseInt($( this ).text(),10)};
	}
});
for (var game in bestGames) {
  if (bestGames.hasOwnProperty(game)) {
    $('#'+bestGames[game].id).parent().addClass('best-game');
  }
}

$(".record-box").change(function() {
    if(this.checked)
        $('.table.finished').addClass('records-only');
    else
		$('.table.finished').removeClass('records-only');
});

//Just flip this sometimes to change sort order
var bool = false;

function sortTable(col){
	//Flipping sort order
    bool = !bool;

    //Detach all rows from table
    var rows = $('table.table > tbody').children('tr').detach();
    
    //Sort by name
    if(col===1){
		rows.sort(function(row1, row2){
			if (bool) return (parseInt($(row1).find(".text-center").data('player-id'),10) - parseInt($(row2).find(".text-center").data('player-id'),10));
			else return (parseInt($(row2).find(".text-center").data('player-id'),10) - parseInt($(row1).find(".text-center").data('player-id'),10));
		});
	}

	//Sort by time started
    else if (col === 2){
		rows.sort(function(row1, row2){
			if (bool) return ($(row1).find(".started").data('timestamp') - $(row2).find(".started").data('timestamp'));
			else return ($(row2).find(".started").data('timestamp')- $(row1).find(".started").data('timestamp'));
		});
    }

    //Sort by time finished
    else if (col===3){
		rows.sort(function(row1, row2){
			if (bool) return ($(row1).find(".finished").data('timestamp') - $(row2).find(".finished").data('timestamp'));
			else return ($(row2).find(".finished").data('timestamp')- $(row1).find(".finished").data('timestamp'));
		});
    }

    //Sort by number of throws
    else{
		rows.sort(function(row1, row2){
			if (bool) return (parseInt($(row1).find(".text-center").text(),10) - parseInt($(row2).find(".text-center").text(),10));
			else return (parseInt($(row2).find(".text-center").text(),10) - parseInt($(row1).find(".text-center").text(),10));
		});
    }
    
    //Re-insert the rows in correct order.
    $(rows).each(function(){
        $('.table > tbody:last').append($(this));
    });
}
