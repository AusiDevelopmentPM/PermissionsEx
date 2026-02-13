<?php
/**
 * @org AusiDevelopmentPM
 * @author EyNoah1171
 * @year 2026
 * @license Dont steal my Code.
 */

namespace ADPM\PermissionsEx\api;

use ADPM\PermissionsEx\manager\GroupManager;
use ADPM\PermissionsEx\manager\PEXManager;
use ADPM\PermissionsEx\manager\UserManager;
use ADPM\PermissionsEx\models\Group;
use ADPM\PermissionsEx\PEX;
use pocketmine\permission\PermissionManager;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;

class PexAPI
{

    use SingletonTrait;

    private Config $groups;
    private Config $users;

    public function __construct(
        private PEXManager $manager,
        private GroupManager $groupManager,
        private UserManager $userManager
    )
    {
        self::setInstance($this);
        @mkdir("");
    }

    public function setGroup(Player|string $player, string $group): bool
    {
        if (!$this->groupManager->exists($group)) {
            return false;
        }

        $name = $player instanceof Player ? $player->getName() : $player;
        $user = $this->userManager->get($name);

        $old = $user->groups;
        $user->groups = [$group];

        $this->userManager->save();

        $online = $player instanceof Player ? $player : $this->manager->getPlugin()->getServer()->getPlayerExact($name);

        if ($online !== null) {
            $this->manager->apply($online);
            $this->manager->callGroupChangeEvent($online, $old, [$group]);
        }

        return true;
    }


    public function getGroup(Player|string $player): Group
    {
        return $this->groupManager->get($player);
    }

    public function addGroup(Player|string $player, string $group): bool
    {

        if (!$this->groupManager->exists($group)) {
            return false;
        }

        $name = $player instanceof Player ? $player->getName(): $player;
        $user = $this->userManager->get($name);

        if (!in_array($group, $user->groups)) return true;

        $old = $user->groups;
        $user->groups[] = $group;

        $this->userManager->save();

        $online = $player instanceof Player ? $player : $this->manager->getPlugin()->getServer()->getPlayerExact($name);

        if ($online !== null) {
            $this->manager->apply($online);
            $this->manager->callGroupChangeEvent($online, $old, $user->groups);
        }

        return true;
    }

    public function removeGroup(Player|string $player, string $group): bool
    {

        if (!$this->groupManager->exists($group)) {
            return false;
        }

        $name = $player instanceof Player ? $player->getName() : $player;
        $user = $this->userManager->get($name);

        if (!in_array($group, $user->groups)) return true;

        $old = $user->groups;
        $user->groups = array_values(array_diff($user->groups, [$group]));

        if (empty($user->groups)) {
            $user->groups = ["default"];
        }

        $this->userManager->save();

        $online = $player instanceof Player ? $player : $this->manager->getPlugin()->getServer()->getPlayerExact($name);

        if ($online !== null) {
            $this->manager->apply($online);
            $this->manager->callGroupChangeEvent($online, $old, $user->groups);
        }

        return true;
    }

    public function addTimedPermission(Player $player, string $perm, int $seconds): void
    {
        $user = $this->userManager->get($player->getName());
        $user->timedPermissions[$perm] = time() + $seconds;
        $this->userManager->save();
        $this->manager->apply($player);
    }

    public function getPrefix(Player $player): string
    {
        $user = $this->userManager->get($player->getName());
        $groups = $this->groupManager->getSorted($user->groups);
        return $groups[0]->prefix ?? "";
    }

    public function getSuffix(Player $player): string
    {
        $user = $this->userManager->get($player->getName());
        $groups = $this->groupManager->getSorted($user->groups);
        return $groups[0]->suffix ?? "";
    }
}