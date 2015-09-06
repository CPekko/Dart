<?php
 
class DartThrow{

	private $target;
	private $hit;
	private $distanceFromTarget;
	private $streak;

	function __construct($target, $hit, $streak) {
		$this->target = $target;
		$this->hit = $hit;
		$this->streak = $streak;
		if (! $hit){
			$this->distanceFromTarget = null;
		} elseif ($hit == 25 || $hit == 50) {
			$this->distanceFromTarget = null;
		} else {
			$dartboard = array(20,1,18,4,13,6,10,15,2,17,3,19,7,16,8,11,14,9,12,5);
			$offset = abs(array_search($hit, $dartboard) - array_search($target, $dartboard));
			if ($offset > 10){
				$this->distanceFromTarget = 20 - $offset;
			} else {
				$this->distanceFromTarget = $offset;	
			}
		} 
	}

	function getTarget(){
		return $this->target;
	}

	function getHit(){
		return $this->hit;
	}

	function getStreak(){
		return $this->streak;
	}

	function setStreak($streak){
		$this-> $streak = $streak;
	}

	function getDistanceFromTarget(){
		return $this->distanceFromTarget;
	}
}

class DartGame{

	private $gameId;
	private $player;
	private $throws;
	private $started;
	private $finished;
	private $numThrows;

	function __construct($gameId, $player, $throws, $started, $finished) {
		$this->player = $player;
		$this->throws = (is_null($throws) ? array() : $throws);
		$this->started = (is_null($started) ? date("Y-m-d H:i:s") : $started);
		$this->finished = $finished;
		$this->gameId = $gameId;
	}

	function getThrows(){
		return $this->throws;
	}

	function getTimeStarted(){
		return $this->started;
	}

	function getTimeFinished(){
		return $this->finished;
	}

	function getNextTarget(){
		$hitList = array();
		if (! sizeof($this->throws)) return 1;
		foreach ($this->throws as $dartThrow) {
			$hitList[$dartThrow->getTarget()] ++;
		}
		$highestTarget = max(array_keys($hitList));
		foreach ($this->throws as $dartThrow) {
			if($dartThrow->getTarget() == $highestTarget){
				if (is_null($dartThrow->getDistanceFromTarget())){
					continue;
				} elseif ($dartThrow->getDistanceFromTarget() == 0){
					return ++$highestTarget;
				}
			}
		}
		return $highestTarget;
	}

	function getThrowsOnTarget(){
		$throwsOnTarget = 0;
		$nextTarget = $this->getNextTarget();
		foreach ($this->throws as $dartThrow) {
			if ($nextTarget == $dartThrow->getTarget()) $throwsOnTarget++;
		}
		return $throwsOnTarget;
	}

	function getGameId(){
		return $this->gameId;
	}

	function getPlayer(){
		return $this->player;
	}

	function throwDart($dartThrow){
		$this->throws[] = $dartThrow;
	}

	function setThrows($throws){
		$this->throws = $throws; 
	}

	function setNumThrows($numThrows){
		$this->numThrows = $numThrows;
	}

	function getNumThrows(){
		return $this->numThrows;
	}

	function getStreakCount(){
		if (sizeof($this->throws)) return end(array_values($this->throws))->getStreak();
		else return 0;
	}
}

class Player{

	private $playerID;
	private $name;
	private $active;
	private $image;

	function __construct($playerID, $name, $active) {
		$this->playerID = $playerID;
		$this->name = $name;
		$this->active = $active;
		$this->image = "http://folk.ntnu.no/eiriknf/dart/images/default.png";
	}

	function getPlayerId(){
		return $this->playerID;
	}

	function getName(){
		return $this->name;
	}

	function getImage(){
		return $this->image;
	}

	function setImage($image){
		$this->image = $image;
	}

	function isActive(){
		return $this->active;
	}
}

?>