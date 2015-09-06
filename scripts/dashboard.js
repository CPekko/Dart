function playRound(gameId, playerId){
	var base = 'http://folk.ntnu.no/eiriknf/dart/game/';
	window.location.href = base + '?id=' + playerId;
}

function abandonGame(gameId, playerId){
	if (confirm("Are you sure you want to abandon game?")){
		var base = 'http://folk.ntnu.no/eiriknf/dart/partials/gameOptions.php?action=1';
		window.location.href = base + '&gameID=' + gameId + '&playerID=' + playerId;
	}
}

function newGame(playerId){
	var base = 'http://folk.ntnu.no/eiriknf/dart/partials/gameOptions.php?action=2';
	window.location.href = base + '&playerID=' + playerId;
}