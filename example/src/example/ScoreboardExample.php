<?php

namespace example;

use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\ClosureTask;
use scoreboard\Scoreboard;

class ScoreboardExample extends PluginBase {

    protected function onEnable(): void {
        $this->getScheduler()->scheduleRepeatingTask(new ClosureTask(function (): void {
            $this->onScore();
        }), 20);
    }

    private function onScore() {
        foreach ($this->getServer()->getOnlinePlayers() as $player) {
            $scoreboard = new Scoreboard("TITLE", "exampleScore", [$player]);
            $scoreboard->removeScoreboard();
            $scoreboard->createScoreboard();
            $scoreboard->addEntry("§8- §7Players: §a" . count($this->getServer()->getOnlinePlayers()));
        }
    }
}