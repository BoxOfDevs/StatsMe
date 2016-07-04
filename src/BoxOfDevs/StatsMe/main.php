<?php
namespace BoxOfDevs\StatsMe;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\Config;
use pocketmine\Server;
use pocketmine\Player;
use pocketmine\permission\Permission;

use onebone\economyapi\EconomyAPI;
use BoxOfDevs\WinAPI\main;

class Main extends PluginBase implements Listener{
     
     public function onEnable(){
          $this->getServer()->getPluginManager()->registerEvents($this,$this);
          $this->getLogger()->info("StatsMe by BoxOfDevs enabled!");
          $this->saveResource("config.yml");
          $this->config = new Config($this->getDataFolder(). "config.yml", Config::YAML);
          if($this->getServer()->getPluginManager()->getPlugin("EconomyAPI") != null){
               $this->economyapi = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
               $this->getLogger()->notice("EconomyAPI support in StatsMe enabled!");
          }else{
               $this->economyapi = null;
               $this->getLogger()->notice("EconomyAPI support in StatsMe disabled!");
          }
          if($this->getServer()->getPluginManager()->getPlugin("WinAPI") != null){
               $this->winapi = $this->getServer()->getPluginManager()->getPlugin("WinAPI");
               $this->getLogger()->notice("WinAPI support in StatsMe enabled!");
          }else{
               $this->winapi = null;
               $this->getLogger()->notice("WinAPI support in StatsMe disabled!");
          }
     }
     
     public function getStats($player, $ptype = "me"){
          $stats = $this->config->get("stats");
          if($ptype === "me"){
               $stats = str_replace("{firstline}", $this->config->get("firstline_mystats"), $stats);
          }else{
               $stats = str_replace("{firstline}", $this->config->get("firstline_otherstats"), $stats);
          }
          $pname = $player->getName();
          $stats = str_replace("{name}", $pname, $stats);
          $x = round($player->x, 0);
          $y = round($player->y, 0);
          $z = round($player->z, 0);
          $stats = str_replace("{xyz}", $x.", ".$y.", ".$z, $stats);
          $health = $player->getHealth();
          $stats = str_replace("{health}", $health, $stats);
          $maxhealth = $player->getMaxHealth();
          $stats = str_replace("{maxhealth}", $maxhealth, $stats);
          $heartsymbol = $this->config->get("heartsymbol");
          $hearts = str_repeat($heartsymbol, $health/2);
          $nohearts = str_repeat($heartsymbol, $maxhealth/2 - $health/2);
          $stats = str_replace("{hearts}", "§c".$hearts."§f".$nohearts, $stats);
          if($this->economyapi != null){
               $stats = str_replace("{coins}", $this->economyapi->mymoney($player->getName()), $stats);
          }
          if($this->winapi != null){
               $stats = str_replace("{wins}", $this->winapi->getwins($player->getName()), $stats);
          }
          $stats = str_replace("{line}", "\n", $stats);
          $stats = str_replace("&", "§", $stats);
          return $stats;
     }

     public function onCommand(CommandSender $sender, Command $command, $label, array $args){
          switch($command->getName()){
               case "stats":
                    if($sender instanceof Player && !isset($args[0]) && $sender->hasPermission("statsme.my")){
                         $stats = $this->getStats($sender);
                         $sender->sendMessage($stats);
                         break;
                    }elseif(isset($args[0]) && $sender->hasPermission("statsme.other")){
                         $player = $this->getServer()->getPlayer($args[0]);
                         if($player instanceof Player){
                              $stats = $this->getStats($player, "other");
                              $sender->sendMessage($stats);
                              break;
                         }else{
                              $sender->sendMessage($this->config->get("player_notfound_msg"));
                         }
                         break;
                    }elseif(!$sender instanceof Player){
                         $sender->sendMessage("Please use this command ingame!");
                         break;
                    }else{
                         $sender->sendMessage($this->config->get("noperm_msg"));
                         break;
                    }
          }
          return true;
     }
}
