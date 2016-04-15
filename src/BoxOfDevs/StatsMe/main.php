<?php
namespace boxofdevs\statsme;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\Config;
use pocketmine\Server;
class Main extends PluginBase implements Listener{
     
     public function onEnable(){
          $this->getServer()->getPluginManager()->registerEvents($this,$this);
          $this->getLogger()->info("StatsMe by BoxOfDevs enabled!");
     }
     
     public function onCommand(CommandSender $sender, Command $command, $label, array $args){
          switch($command->getName()){
               case "stats":
                    $stats = $this->config->get("stats");
                    $sname = $sender->getName();
                    $stats = str_replace("{line}", "\n", $args);
                    $stats = str_replace("'{name}", $sname, $args);
					$stats = str_replace("'{xyz}", $sender->x.", ".$sender->y.", ".$sender->z, $args);
                    $stats = str_replace("&", "", $args);
                    $sender->sendMessage($stats);
                    break;
          }
          return true;
     }
}
