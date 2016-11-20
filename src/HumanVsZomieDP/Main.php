<?php

namespace HumanVsZombieDP;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\scheduler\PluginTask;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\block\Block;
use pocketmine\item\Item;
use pocketmine\inventory\InventoryBase;
use pocketmine\utils\TextFormat as Color;
use pocketmine\utils\Config;
use pocketmine\Player;
use pocketmine\Server;

class Main extends PluginBase implements Listener{
	
	public $players = 0;
	public $minute = 0;
	public $second = 60;
	public $counttype = "down";
	
	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->getServer()->getScheduler()->scheduleRepeatingTask(new Task($this), 20);
		@mkdir($this->getDataFolder());
		$xyz = [
				
				'xp1' => 0,
				'yp1' => 0,
				'zp1' => 0,
				'xp2' => 0,
				'yp2' => 0,
				'zp2' => 0,
				'xp3' => 0,
				'yp3' => 0,
				'zp3' => 0,
				'xz1' => 0,
				'yz1' => 0,
				'zz1' => 0,
				'xz2' => 0,
				'yz2' => 0,
				'zz2' => 0,
				'xl' => 0,
				'yl' => 0,
				'zl' => 0,
		];
		$cfg = new Config($this->getDataFolder() . "config.yml", Config::YAML, $xyz);
		$cfg->save();
		
		$worlds = [
				
				'game' => 'world',
				'leave' => 'world',
		];
		$world = new Config($this->getDataFolder() . "worlds.yml", Config::YAML, $worlds);
		$world->save();
		
		$juzexmod = [
				
				'plugin_by' => 'juzexmod',
		];
		
		$juze = new Config($this->getDataFolder() . "juzexmod.yml", Config::YAML, $juzexmod);
		$juze->save();
		
		$block = [
		
				'id' => 47,
		];
		
		$juze = new Config($this->getDataFolder() . "block.yml", Config::YAML, $block);
		$juze->save();
	}
	
	public function onDisable(){
		$this->getConfig()->save();
	}
	
	public function onCommand(CommandSender $sender, Command $cmd, $lable, array $args){
		if($sender->isOp()){
			switch($cmd->getName()){
				case 'hvz':
					
					if(isset($args[0])){
						switch($args[0]){
							
							case 'setspawn':
								
								if(isset($args[1])){
									switch($args[1]){
										
										case 'player':
											
											if(isset($args[2])){
												switch($args[2]){
													
													case '1':
														$xp1 = $sender->getFloorX();
														$yp1 = $sender->getFloorY();
														$zp1 = $sender->getFloorZ();
														
														$this->getConfig()->set("xp1", $xp1);
														$this->getConfig()->set("yp1", $yp1);
														$this->getConfig()->set("zp1", $zp1);
														
														$sender->sendMessage(Color::GREEN."[HumanVsZombie] Player 1 Spawn Now In [$xp1, $yp1, $zp1]");
														return true;
														
													case '2':
														$xp2 = $sender->getFloorX();
														$yp2 = $sender->getFloorY();
														$zp2 = $sender->getFloorZ();
														
														$this->getConfig()->set("xp2", $xp2);
														$this->getConfig()->set("yp2", $yp2);
														$this->getConfig()->set("zp2", $zp2);
														
														$sender->sendMessage(Color::GREEN."[HumanVsZombie] Player 2 Spawn Now In [$xp2, $yp2, $zp2]");
														return true;
														
													case '3':
														$xp3 = $sender->getFloorX();
														$yp3 = $sender->getFloorY();
														$zp3 = $sender->getFloorZ();
														
														$this->getConfig()->set("xp3", $xp3);
														$this->getConfig()->set("yp3", $yp3);
														$this->getConfig()->set("zp3", $zp3);
														
														$sender->sendMessage(Color::GREEN."[HumanVsZombie] Player 3 Spawn Now In [$xp3, $yp3, $zp3]");
												}
											}
											
										case 'zombie':
											
											if(isset($args[2])){
												switch($args[0]){
													
													case '1':
														$xz1 = $sender->getFloorX();
														$yz1 = $sender->getFloorY();
														$zz1 = $sender->getFloorZ();
															
														$this->getConfig()->set("xz1", $xz1);
														$this->getConfig()->set("yz1", $yz1);
														$this->getConfig()->set("zz1", $zz1);
															
														$sender->sendMessage(Color::GREEN."[HumanVsZombie] Zombie 1 Spawn Now In [$xz1, $yz1, $zz1]");
														return true;
														
													case '2':
														$xz2 = $sender->getFloorX();
														$yz2 = $sender->getFloorY();
														$zz2 = $sender->getFloorZ();
															
														$this->getConfig()->set("xz2", $xz2);
														$this->getConfig()->set("yz2", $yz2);
														$this->getConfig()->set("zz2", $zz2);
															
														$sender->sendMessage(Color::GREEN."[HumanVsZombie] Zombie 2 Spawn Now In [$xz2, $yz2, $zz2]");
														return true;
												}
											}
									}
								}
								
							case 'setworld':
								
								if(isset($args[1])){
									switch($args[1]){
										
										case 'leave':
											$w = new Config($this->getDataFolder() . "worlds.yml");
											$leave = $sender->getLevel()->getName();
											$w->set("leave", $leave);
											$xl = $sender->getFloorX();
											$yl = $sender->getFloorY();
											$zl = $sender->getFloorZ();
												
											$this->getConfig()->set("xl", $xl);
											$this->getConfig()->set("yl", $yl);
											$this->getConfig()->set("zl", $zl);
											$sender->sendMessage(Color::GREEN."[HumanVsZombie] Leave World Name [$leave]");
											return true;
											
										case 'game':
											$w = new Config($this->getDataFolder() . "worlds.yml");
											$game = $sender->getLevel()->getName();
											$w->set("game", $game);
											$sender->sendMessage(Color::GREEN."[HumanVsZombie] Game World Name [$game]");
									}
								}
						}
					}
			}
		}
	}
	
	public function tick(){
		$w = new Config($this->getDataFolder() . "worlds.yml");
		$game = $w->get("game");
		$map = $this->getServer()->getLevelByName("$game")->getPlayers();
		
		foreach($map as $p){
	
		if($this->counttype == "down"){
			$this->second--;
			
			if($this->second < 60 && $this->second > 9){
				$p->sendTip($this->minute.":".$this->second);
			}
			
			if($this->second < 10){
				$p->sendTip($this->minute.":0".$this->second);
				
			if($this->second == 0){
				$this->second = 60;
				$this->minute--;
			}
			}
		}
	}
	}
	
	public function onMove(PlayerMoveEvent $event){
		$player = $event->getPlayer();
		$w = new Config($this->getDataFolder() . "worlds.yml");
		
		$game = $w->get("game");
		$map = $this->getServer()->getLevelByName("$game")->getPlayers();
		
		foreach($map as $p){
			$event->setCancelled(true);
			
			if($this->second < 0 && $this->second > 0){
				$event->setCancelled(false);
			}
		}
	}
	
	public function onBlockBreak(BlockBreakEvent $event){
		$player = $event->getPlayer();
		$w = new Config($this->getDataFolder() . "worlds.yml");
		$game = $w->get("game");
		$map = $this->getServer()->getLevelByName("$game")->getPlayers();
		
		foreach($map as $p){
			$event->setCancelled(true);
		}
	}
	
	public function onPlace(BlockPlaceEvent $event){
		$player = $event->getPlayer();
		$w = new Config($this->getDataFolder() . "worlds.yml");
		$game = $w->get("game");
		$map = $this->getServer()->getLevelByName("$game")->getPlayers();
		
		foreach($map as $p){
			$event->setCancelled(true);
		}
	}
	
	public function onTouch(PlayerInteractEvent $event){
		$player = $event->getPlayer();
		$block = $event->getBlock()->getId();
		$b = new Config($this->getDataFolder() . "block.yml");
		$w = new Config($this->getDataFolder() . "worlds.yml");
		$id = $b->get("id");
		$name = $player->getName();
		$humantag = " ";
		$zombietag = "[Zombie]";
		$game = $w->get("game");
		$xp1 = $this->getConfig()->get("xp1");
		$yp1 = $this->getConfig()->get("yp1");
		$zp1 = $this->getConfig()->get("zp1");
		$xz1 = $this->getConfig()->get("xz1");
		$yz1 = $this->getConfig()->get("yz1");
		$zz1 = $this->getConfig()->get("zz1");
		$xp2 = $this->getConfig()->get("xp2");
		$yp2 = $this->getConfig()->get("yp2");
		$zp2 = $this->getConfig()->get("zp2");
		$xz2 = $this->getConfig()->get("xz2");
		$yz2 = $this->getConfig()->get("yz2");
		$zz2 = $this->getConfig()->get("zz2");
		$xp3 = $this->getConfig()->get("xp3");
		$yp3 = $this->getConfig()->get("yp3");
		$zp3 = $this->getConfig()->get("zp3");
		
		if($block == $id){
			if($this->players == 0){
				$player->teleport($this->getServer()->getLevelbyName("$game")->getSafeSpawn());
				$player->teleport(new position($xp1, $yp1, $zp1));
				$player->getInventory()->clearAll();
				$player->sendMessage(Color::AQUA."[HumanVsZombie] Welcome $name To Play HumanVsZombie You Human ;)");
				$player->setNameTag("$humantag");
				$player->getInventory()->addItem(364, 0, 64);
				$player->getInventory()->addItem(272, 0, 1);
				$player->getInventory()->setHelmet(302, 0, 1);
				$player->getInventory()->setChestplate(303, 0, 1);
				$player->getInventory()->setLeggings(304, 0, 1);
				$player->getInventory()->setBoots(305, 0, 1);
				$this->players = 1;
			}
			
			if($this->players == 1){
				$player->teleport($this->getServer()->getLevelbyName("$game")->getSafeSpawn());
				$player->teleport(new position($xz1, $yz1, $zz1));
				$player->getInventory()->clearAll();
				$player->sendMessage(Color::AQUA."[HumanVsZombie] Welcome $name To Play HumanVsZombie You Zombie ;)");
				$player->setNameTag("$zombietag");
				$player->getInventory()->addItem(364, 0, 64);
				$player->getInventory()->addItem(276, 0, 1);
				$player->getInventory()->setHelmet(86, 0, 1);
				$player->getInventory()->setChestplate(311, 0, 1);
				$player->getInventory()->setLeggings(312, 0, 1);
				$player->getInventory()->setBoots(305, 0, 1);
				$this->players = 2;
			}
			
			if($this->players == 2){
				$player->teleport($this->getServer()->getLevelbyName("$game")->getSafeSpawn());
				$player->teleport(new position($xp2, $yp2, $zp2));
				$player->getInventory()->clearAll();
				$player->sendMessage(Color::AQUA."[HumanVsZombie] Welcome $name To Play HumanVsZombie You Human ;)");
				$player->setNameTag("$humantag");
				$player->getInventory()->addItem(364, 0, 64);
				$player->getInventory()->addItem(272, 0, 1);
				$player->getInventory()->setHelmet(302, 0, 1);
				$player->getInventory()->setChestplate(303, 0, 1);
				$player->getInventory()->setLeggings(304, 0, 1);
				$player->getInventory()->setBoots(305, 0, 1);
				$this->players = 3;
			}
			
			if($this->players == 3){
				$player->teleport($this->getServer()->getLevelbyName("$game")->getSafeSpawn());
				$player->teleport(new position($xz2, $yz2, $zz2));
				$player->getInventory()->clearAll();
				$player->sendMessage(Color::AQUA."[HumanVsZombie] Welcome $name To Play HumanVsZombie You Zombie ;)");
				$player->setNameTag("$zombietag");
				$player->getInventory()->addItem(364, 0, 64);
				$player->getInventory()->addItem(276, 0, 1);
				$player->getInventory()->setHelmet(86, 0, 1);
				$player->getInventory()->setChestplate(311, 0, 1);
				$player->getInventory()->setLeggings(312, 0, 1);
				$player->getInventory()->setBoots(305, 0, 1);
				$this->players = 4;
			}
			
			if($this->players == 4){
				$player->teleport($this->getServer()->getLevelbyName("$game")->getSafeSpawn());
				$player->teleport(new position($xp3, $yp3, $zp3));
				$player->getInventory()->clearAll();
				$player->sendMessage(Color::AQUA."[HumanVsZombie] Welcome $name To Play HumanVsZombie You Human ;)");
				$player->setNameTag("$humantag");
				$player->getInventory()->addItem(364, 0, 64);
				$player->getInventory()->addItem(272, 0, 1);
				$player->getInventory()->setHelmet(302, 0, 1);
				$player->getInventory()->setChestplate(303, 0, 1);
				$player->getInventory()->setLeggings(304, 0, 1);
				$player->getInventory()->setBoots(305, 0, 1);
				$this->players = 5;
			}
			
			if($this->players == 5){
				$player->sendMessage(Color::RED."[HumanVsZombie] Game was Started");
			}
		}
	}
	
	public function onDeath(PlayerDeathEvent $event){
		$player = $event->getPlayer();
		$w = new Config($this->getDataFolder() . "worlds.yml");
		$game = $w->get("game");
		$leave = $w->get("leave");
		$map = $this->getServer()->getLevelByName("$game")->getPlayers();
		$xl = $this->getConfig()->get("xl");
		$yl = $this->getConfig()->get("yl");
		$zl = $this->getConfig()->get("zl");
		foreach($map as $p){
			$player->teleport($this->getServer()->getLevelbyName("$leave")->getSafeSpawn());
			$player->teleport(new position($xl, $yl, $zl));
		}
	}
	
	public function onEntityDamage(EntityDamageEvent $event){
		if ($event instanceof EntityDamageByEntityEvent) {
			if ($event->getEntity() instanceof Player && $event->getDamager() instanceof Player) {
				$golpeado = $event->getEntity()->getNameTag();
				$golpeador = $event->getDamager()->getNameTag();
				if ((strpos($golpeado, "[Zombie]") !== false) && (strpos($golpeador, "[Zombie]") !== false)) {
					$event->setCancelled();
				}
	
				else if ((strpos($golpeado, " ") !== false) && (strpos($golpeador, " ") !== false)) {
	
					$event->setCancelled();
				}
			}
	
		}
	}
}

class Task extends PluginTask{

	public function __construct(Main $plugin){
		parent::__construct($plugin);
		$this->plugin = $plugin;

	}



	

    public function onRun($currentTick){
    $this->plugin->tick();
}

}
