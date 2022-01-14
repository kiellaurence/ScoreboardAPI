<?php

namespace scoreboard;

use pocketmine\network\mcpe\protocol\ClientboundPacket;
use pocketmine\network\mcpe\protocol\DataPacket;
use pocketmine\network\mcpe\protocol\RemoveObjectivePacket;
use pocketmine\network\mcpe\protocol\SetDisplayObjectivePacket;
use pocketmine\network\mcpe\protocol\SetScorePacket;
use pocketmine\network\mcpe\protocol\types\ScorePacketEntry;
use pocketmine\player\Player;
use pocketmine\Server;

class Scoreboard {

    private string $title;
    private string $objectName;
    private int $score = 0;
    private array $players = [];

    public function __construct(string $title, string $objectName, array $players = []) {
        $this->title = $title;
        $this->objectName = $objectName;
        $this->players = $players;
    }

    public function createScoreboard() {
        $pk = new SetDisplayObjectivePacket();
        $pk->displaySlot = "sidebar";
        $pk->objectiveName = $this->objectName;
        $pk->displayName = $this->title;
        $pk->criteriaName = "dummy";
        $pk->sortOrder = 0;
        $this->sendDataPacket($pk);
    }

    public function addEntry(string $msg) {
        $entry = new ScorePacketEntry();
        $entry->objectiveName = $this->objectName;
        $entry->type = 3;
        $entry->customName = " $msg   ";
        $entry->score = $this->score;
        $entry->scoreboardId = $this->score;
        $pk = new SetScorePacket();
        $pk->type = 0;
        $pk->entries[$this->score] = $entry;
        $this->sendDataPacket($pk);
        $this->score++;
    }

    public function removeEntry(int $score) {
        $pk = new SetScorePacket();
        if (isset($pk->entries[$score])) {
            unset($pk->entries[$score]);
            $this->sendDataPacket($pk);
        }
    }

    public function removeScoreboard()
    {
        $pk = new RemoveObjectivePacket();
        $pk->objectiveName = $this->objectName;
        $this->sendDataPacket($pk);
    }

    private function sendDataPacket(ClientboundPacket $packet) {
        foreach ($this->players as $player) {
            $player->getNetworkSession()->sendDataPacket($packet);
        }
    }
}