<?php
/**
 * @org AusiDevelopmentPM
 * @author EyNoah1171
 * @year 2026
 * @license Dont steal my Code.
 */

namespace ADPM\PermissionsEx;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\player\chat\ChatFormatter;
use pocketmine\player\chat\StandardChatFormatter;

class EventListener implements Listener
{

    public function onJoin(PlayerJoinEvent $event): void
    {
        $player = $event->getPlayer();
        PEX::getInstance()->getPermManager()->applyPermissions($player);
    }

    /**
     * @throws \JsonException
     */
    public function onQuit(PlayerQuitEvent $ev): void
    {
        PEX::getInstance()->getPermManager()->setUserGroup($ev->getPlayer()->getName(), "default");
    }

    public function onChat(PlayerChatEvent $event): void
    {
        $player = $event->getPlayer();
        $prefix = PEX::getInstance()->getPexAPI()->getPrefix($player);
        $suffix = PEX::getInstance()->getPexAPI()->getSuffix($player);

        $chatFormatter = new StandardChatFormatter();

        $chatFormatter->format("", "");

        $event->setFormatter($chatFormatter->format($player, ""));

    }

}