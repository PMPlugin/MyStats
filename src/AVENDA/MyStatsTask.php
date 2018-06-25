<?php

namespace AVENDA;

use pocketmine\scheduler\Task;
use AVENDA\Main;

class MyStatsTask extends Task {
	protected $owner;
	protected $player;
	public function __construct(Main $owner, $player){
		$this->owner = $owner;
		$this->player = $player
		}
	public function onRun ($currentTick){
		$this->owner->sendInfo($this->player);
		}
	}