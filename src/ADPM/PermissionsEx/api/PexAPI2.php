<?php
/**
 * @org AusiDevelopmentPM
 * @author EyNoah1171
 * @year 2026
 * @license Dont steal my Code.
 */

namespace ADPM\PermissionsEx\api;

use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;

class PexAPI2 {

    use SingletonTrait;

    private Config $groups;
    private Config $users;

    public function __construct(string $dataFolder)
    {
        self::setInstance($this);

        @mkdir($dataFolder);

        $this->groups = new Config($dataFolder . "groups.yml", Config::YAML);
        $this->users = new Config($dataFolder . "users.yml", Config::YAML);
    }

    public function reload(): void
    {
        $this->groups->reload();
        $this->users->reload();
    }

    public function createGroup(string $groupName): void
    {
        if (!$this->groups->exists($groupName)) {
            $this->groups->set($groupName, [
                "permissions" => [],
                "prefix" => "",
                "suffix" => "",
                "inheritance" => []
            ]);

            $this->groups->save();
        }
    }

    public function deleteGroup(string $groupName): void
    {
        if ($this->groups->exists($groupName)) {
            $this->groups->remove($groupName);
            $this->groups->save();
        }
    }

    public function addPermissionToGroup(string $groupName, string $permission): void
    {
        if (!$this->groups->exists($groupName)) return;

        $data = $this->groups->get($groupName);
        $data["permissions"][] = $permission;

        $this->groups->set($groupName, $data);
        $this->groups->save();
    }

    public function addPermissionToUser(string $playerName, string $permission): void
    {
        $data = $this->users->get($playerName, []);
        $data["permissions"][] = $permission;

        $this->users->set($playerName, $data);
        $this->users->save();
    }

    public function removePermissionFromUser(string $player, string $permission): void {
        $data = $this->users->get($player, []);
        if (!isset($data["permissions"])) return;

        $data["permissions"] = array_filter(
            $data["permissions"],
            fn($perm) => $perm !== $permission
        );

        $this->users->set($player, $data);
        $this->users->save();
    }
}