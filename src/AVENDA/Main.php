<?php

namespace AVENDA;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\Config;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\Player;
use AVENDA\MyStatsTask;

class Main extends PluginBase implements Listener {
	public $config, $db;
	public function onEnable (){
		$data = $this->getDataFolder();
		@mkdir($data);
		$this->config = new Config ( $data . "playerstats.yml", Config::YAML);
		$this->db = $this->config->getAll();
		$this->getServer()->getPluginManager()->registerEvents ($this,$this);
		}
	public function onBlockBreak (BlockBreakEvent $event){
		$player = $event->getPlayer();
		$name = strtolower($player->getName());
		$this->db [$name] ["breakcount"] += 1;
		$this->save();
		}
	public function onBlockPlace (BlockPlaceEvent $event){
		$player = $event->getPlayer();
		$name = strtolower($player->getName());
		$this->db [$name] ["placecount"] += 1;
		$this->save();
		}
	public function save (){ 
		$this->config->setAll($this->db);
		$this->config->save();
		}
	public function onJoin (PlayerJoinEvent $event){
		$player = $event->getPlayer();
		$name = strtolower($player->getName());
		if ( ! isset ( $this->db [$name])){
			$this->db [$name] ["breakcount"] =0;
			$this->db [$name] ["placecount"] =0;
			$this->db [$name] ["joincount"] =0;
			$this->save();
			}
			$this->db [$name] ["joincount"] +=1;
			$this->getScheduler()->scheduleRepeatingTask(new MyStatsTask ( $this, $player)  20);
			$this->save();
		}
	public function sendInfo (Player $player){
		$player->sendPopup("====[MyStats]====\nWelcome, " . $player->getName() . "\nPlacedBlock: " . $this->db [$player->getName()] ["placecount"] . "\nBreakedBlock: " . $this->db [$player->getName()] ["breakcount"] . "\nJoins: " . $this->db [$player->getName()] ["joincount"] . "\n\n\n");
		}
	}
