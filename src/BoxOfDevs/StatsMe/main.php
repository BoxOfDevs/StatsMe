<?php
namespace boxofdevs\statsme;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\Config;
use pocketmine\Server;
use onebone\economyapi\EconomyAPI;
class Main extends PluginBase implements Listener{
     
     public function onEnable(){
          $this->getServer()->getPluginManager()->registerEvents($this,$this);
          $this->getLogger()->info("StatsMe by BoxOfDevs enabled!");
          if($this->getServer()->getPluginManager()->getPlugin("EconomyAPI") !=== null){
               $this->economyapi = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
               $this->getLogger->info("EconomyAPI support in StatsMe enabled!")
          }else{
               $this->getLogger->info("EconomyAPI support in StatsMe disabled!")
          }
     }
     
     public function onCommand(CommandSender $sender, Command $command, $label, array $args){
          switch($command->getName()){
               case "stats":
                    $stats = $this->config->get("stats");
                    $sname = $sender->getName();
                    $stats = str_replace("{line}", "\n", $args);
                    $stats = str_replace("'{name}", $sname, $args);
                    $stats = str_replace("'{xyz}", $sender->x.", ".$sender->y.", ".$sender->z, $stats);
                    $stats = str_replace("&", "", $args);
                    $sender->sendMessage($stats);
                    break;
          }
          return true;
     }
}
