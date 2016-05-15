<?php
namespace BoxOfDevs\StatsMe;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\Config;
use pocketmine\Server;
use pocketmine\Player;
use onebone\economyapi\EconomyAPI;
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
               $this->getLogger()->notice("EconomyAPI support in StatsMe disabled!");
          }
     }
     
     public function onCommand(CommandSender $sender, Command $command, $label, array $args){
          switch($command->getName()){
               case "stats":
                    if($sender instanceof Player){
                         $stats = $this->config->get("stats");
                         $sname = $sender->getName();
                         $stats = str_replace("{name}", $sname, $stats);
                         $x = round($sender->x, 0);
                         $y = round($sender->y, 0);
                         $z = round($sender->z, 0);
                         $stats = str_replace("{xyz}", $x.", ".$y.", ".$z, $stats);
                         $stats = str_replace("{coins}", $this->economyapi->mymoney($sender->getName()), $stats);
                         $stats = str_replace("{line}", "\n", $stats);
                         $stats = str_replace("&", "§", $stats);
                         $sender->sendMessage($stats);
                         break;
                    }else{
                         $sender->sendMessage("Please use this command ingame!");
                    }
          }
          return true;
     }
}
