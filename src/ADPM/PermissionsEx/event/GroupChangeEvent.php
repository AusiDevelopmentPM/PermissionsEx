<?php
/**
 * @org AusiDevelopmentPM
 * @author EyNoah1171
 * @year 2026
 * @license Dont steal my Code.
 */

namespace ADPM\PermissionsEx\event;

use pocketmine\event\Event;
use pocketmine\player\Player;

class GroupChangeEvent extends Event
{

    public function __construct(private Player $player, private array $oldGroups, private array $newGroups)
    {

    }

    public function getPlayer(): Player
    {
        return $this->player;
    }

    public function getOldGroups(): array
    {
        return $this->oldGroups;
    }

    public function getNewGroups(): array
    {
        return $this->newGroups;
    }

}