<?php
/**
 * @org AusiDevelopmentPM
 * @author EyNoah1171
 * @year 2026
 * @license Dont steal my Code.
 */

namespace ADPM\PermissionsEx;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;

class EventListener implements Listener
{

    public function onJoin(PlayerJoinEvent $event): void
    {
        $player = $event->getPlayer();
        PEX::getInstance()->getPermManager()->applyPermissions($player);
    }

}