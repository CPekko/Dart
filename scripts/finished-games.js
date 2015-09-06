//Highlight each players best game
var bestGames = {};
$( "td[data-player-id]" ).each(function( index ) {
	if (typeof bestGames[$( this ).data('player-id')] === 'undefined'){
		bestGames[$( this ).data('player-id')] = { id: $( this ).attr('id'), score: $( this ).text()};
	}
	else if (bestGames[$( this ).data('player-id')].score >= $( this ).text()){
		bestGames[$( this ).data('player-id')] = { id: $( this ).attr('id'), score: $( this ).text()};
	}
});
for (var game in bestGames) {
  if (bestGames.hasOwnProperty(game)) {
    $('#'+bestGames[game].id).parent().addClass('best-game');
  }
}

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
			if (bool) return ($(row1).find(".name").text() > $(row2).find(".name").text());
			else return ($(row2).find(".name").text() > $(row1).find(".name").text());
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
			if (bool) return ($(row1).find(".text-center").text() - $(row2).find(".text-center").text());
			else return ($(row2).find(".text-center").text()- $(row1).find(".text-center").text());
		});
    }
    
    //Re-insert the rows in correct order.
    $(rows).each(function(){
        $('.table > tbody:last').append($(this));
    });
}
