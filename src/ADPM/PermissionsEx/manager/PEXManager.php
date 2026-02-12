<?php
/**
 * @org AusiDevelopmentPM
 * @author EyNoah1171
 * @year 2026
 * @license Dont steal my Code.
 */

namespace ADPM\PermissionsEx\manager;

use ADPM\PermissionsEx\event\GroupChangeEvent;
use ADPM\PermissionsEx\models\Group;
use ADPM\PermissionsEx\PEX;
use pocketmine\player\Player;
use pocketmine\utils\Config;

class PEXManager
{

    private array $attachments = [];

    public function __construct(
        private PEX $plugin,
        private GroupManager $groupManager,
        private UserManager $userManager,
    )
    {

    }

    public function apply(Player $player): void
    {
        $name = strtolower($player->getName());

        if (isset($this->attachments[$name])) {
            $player->removeAttachment($this->attachments[$name]);
        }

        $attachment = $player->addAttachment($this->plugin);
        $this->attachments[$name] = $attachment;

        $user = $this->userManager->get($name);
        $groups = $this->groupManager->getSorted($user->groups);

        foreach ($groups as $group) {
            foreach ($group->permissions as $perm => $value) {
                $attachment->setPermission($perm, $value);
            }
        }

        foreach ($user->timedPermissions as $perm => $expire) {
            if (time() < $expire) {
                $attachment->setPermission($perm, true);
            }
        }

        $player->setNameTag(
            PEX::getInstance()->getPexAPI()->getPrefix($player) .
            $player->getName() . PEX::getInstance()->getPexAPI()->getSuffix($player)
        );

        $player->recalculatePermissions();
    }

    public function removeAttachment(Player $player): void
    {
        $name = strtolower($player->getName());
        if (isset($this->attachments[$name])) {
            $player->removeAttachment($this->attachments[$name]);
            unset($this->attachments[$name]);
        }
    }

    public function cleanupExpired(): void
    {
        foreach ($this->userManager->load() as $user) {}
    }

    public function reload(): void
    {
        $this->groupManager->load();
        $this->userManager->load();

        foreach ($this->plugin->getServer()->getOnlinePlayers() as $player) {
            $this->apply($player);
        }
    }

    public function callGroupChangeEvent(Player $player, array $old, array $new): void
    {
        $event = new GroupChangeEvent($player, $old, $new);
        $event->call();
    }

    public function getPlugin(): PEX
    {
        return $this->plugin;
    }

}