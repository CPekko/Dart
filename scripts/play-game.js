var dartThrows = [];
var nextTargetList = [];
var throwsOnCurrent = [];
var totalThrows;
var estimatedTotal = [];
var streakList = [];

function registerNewThrow(hit, multiplier){
	dartThrows.push(hit);
	var nextTarget = getNextTarget();
	var throwsOnTargetCount = getThrowsOnTargetCount();
	var currentStreak = getCurrentStreak();
	totalThrows++;
	if(hit == 20 && nextTarget == 20){
		disableTheShit();
		addThrow(hit,nextTarget);
		streakList.push(currentStreak + 1);
	}
	else if (hit == nextTarget){
		nextTargetList.push(nextTarget + 1);
		throwsOnCurrent.push(0);
		addThrow(hit,nextTarget);
		streakList.push(currentStreak + 1);
		estimatedTotal.push(Math.round(totalThrows/(getNextTarget()-1)*20));
	}
	else {
		nextTargetList.push(nextTarget);
		throwsOnCurrent.push(throwsOnTargetCount + 1);
		addThrow(hit,nextTarget);
		streakList.push(0);
		estimatedTotal.push(Math.round(totalThrows/(getNextTarget()-1)*20));
	}
	updateHtml();
}

function getNextTarget(){
	return nextTargetList[nextTargetList.length-1];
}

function getThrowsOnTargetCount(){
	return throwsOnCurrent[throwsOnCurrent.length-1];
}

function getCurrentEstimate(){
	return estimatedTotal[estimatedTotal.length-1];
}

function getCurrentStreak(){
	return streakList[streakList.length-1];
}

function getPreviousHit(){
	return dartThrows[dartThrows.length-1];
}

function undo(){
	if (dartThrows.length){
		if(getPreviousHit() == 20 && getNextTarget() == 20){
			disableTheShit();
			dartThrows.pop();
			streakList.pop();
			totalThrows--;
			removeThrow();
			updateHtml();
		}else{
			dartThrows.pop();
			nextTargetList.pop();
			throwsOnCurrent.pop();
			estimatedTotal.pop();
			streakList.pop();
			totalThrows--;
			removeThrow();
			updateHtml();
		}
	}
}

function saveRound(id, element){
	data = [];
	for(var i = 0; i<dartThrows.length; i++){
		data.push({'target': nextTargetList[i], 'hit': dartThrows[i], 'streak': streakList[i+1]});
	}
	element.disabled = true;
	$.post(
		"register.php",
		{data : JSON.stringify(data),
		gameId : id},
		function(response) {
			window.location.href= "http://folk.ntnu.no/eiriknf/dart/";
		}
	);
}

function init(){
	nextTargetList.push(parseInt($('#nextTarget').html(), 10));
	throwsOnCurrent.push(parseInt($('#throwsOnTarget').html(),10));
	totalThrows = parseInt($('#totalThrows').html(), 10);
	estimatedTotal.push(parseInt($('#estimatedTotal').html(),10));
	streakList.push(parseInt($('#streak').html(),10));
	
	if (! getThrowsOnTargetCount()){
		$('#target-' + getNextTarget()).html('0');
	}
}

function updateHtml(){
	var nextTarget = getNextTarget();
	$('#nextTarget').html(nextTarget);
	$('#nextTargetText').html(nextTarget);
	$('#throwsOnTarget').html(getThrowsOnTargetCount());
	$('#totalThrows').html(totalThrows);
	$('#estimatedTotal').html(getCurrentEstimate());
	$('#streak').html(getCurrentStreak());
}

function addThrow(hit,target){
	$('#target-' + target).html(parseInt($('#target-' + target).html(), 10)+1);
	if (hit === target){
		$('#target-' + (target+1)).html('0');
		$('#listOfThrows').append('<li class="list-group-item hit-success">'+ hit +'</li>');
	} else if (hit === 0) {
		$('#listOfThrows').append('<li class="list-group-item hit-miss">miss</li>');
	} else {
		$('#listOfThrows').append('<li class="list-group-item hit-miss">'+ hit +'</li>');
	}
}

function removeThrow(){
	$('#listOfThrows > li:last-of-type').remove();
	for (var i=20; i>0; i--){
		if (! $('#target-' + i).is(':empty')){
			if(parseInt($('#target-' + i).html(),10) === 0){
				$('#target-' + i).html("");
			}else{
				$('#target-' + i).html(parseInt($('#target-' + i).html(),10)-1);
				break;
			}
		}
	}
}

function disableTheShit(){
	$('.board').toggleClass('disabled');
	$('#currentTarget').toggleClass('disabled');
	$('#estimatedTotalWrapper').toggleClass('disabled');
}

init();