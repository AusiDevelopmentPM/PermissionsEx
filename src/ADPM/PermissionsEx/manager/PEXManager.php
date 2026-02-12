<?php
/**
 * @org AusiDevelopmentPM
 * @author EyNoah1171
 * @year 2026
 * @license Dont steal my Code.
 */

namespace ADPM\PermissionsEx\manager;

use ADPM\PermissionsEx\models\Group;
use ADPM\PermissionsEx\PEX;
use pocketmine\player\Player;
use pocketmine\utils\Config;

class PEXManager
{

    private Config $groups;
    private Config $users;
    private PEX $plugin;

    /** @var Group[] */
    private array $groupCache = [];

    public function __construct(PEX $plugin)
    {
        $this->plugin = $plugin;
        $this->groups = new Config($plugin->getDataFolder() . "groups.yml", Config::YAML);
        $this->users = new Config($plugin->getDataFolder() . "users.yml", Config::YAML);

        $this->loadGroups();
    }

    private function loadGroups(): void
    {
        foreach ($this->groups->getAll() as $name => $data) {
            $this->groupCache[$name] = new Group(
                $name,
                $data["permissions"] ?? [],
                $data["inherit"] ?? []
            );
        }
    }

    public function getUserGroup(string $player): string
    {
        return $this->users->get(strtolower($player), "default");
    }

    /**
     * @throws \JsonException
     */
    public function setUserGroup(string $player, string $group): void
    {
        $this->users->set(strtolower($player), $group);
        $this->users->save();
    }

    public function applyPermissions(Player $player): void
    {
        $groupName = $this->getUserGroup($player->getName());
        $attachment = $player->addAttachment($this->plugin);

        $this->applyGroup($groupName, $attachment);
    }

    private function applyGroup(string $groupName, $attachment): void
    {
        if (!isset($this->groupCache[$groupName])) return;

        $group = $this->groupCache[$groupName];

        foreach ($group->permissions as $perm => $value) {
            $attachment->setPermission($perm, $value);
        }

        foreach ($group->inherit as $parent) {
            $this->applyGroup($parent, $attachment);
        }
    }

}