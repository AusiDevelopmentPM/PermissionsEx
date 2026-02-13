<?php
/**
 * @org AusiDevelopmentPM
 * @author EyNoah1171
 * @year 2026
 * @license Dont steal my Code.
 */

namespace ADPM\PermissionsEx\api;

use ADPM\PermissionsEx\PEX;
use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;

class PexAPI2 {

    use SingletonTrait;

    private Config $groups;
    private Config $users;


    public function init(string $dataFolder): void
    {
        @mkdir($dataFolder);

        $this->groups = new Config($dataFolder . "groups.yml", Config::YAML);
        $this->users = new Config($dataFolder . "users.yml", Config::YAML);
    }

    public function reload(): void
    {
        $this->groups->reload();
        $this->users->reload();
        PEX::getInstance()->getLogger()->warning(PEX::prefix() . "PEX has been reloaded!");
    }

    public function createGroup(string $groupName): void
    {
        if (!$this->groups->exists($groupName)) {
            $this->groups->set($groupName, [
                "weight" => rand(1, 100),
                "permissions" => [],
                "prefix" => "§8[§f" . $groupName . "§8] §r",
                "suffix" => "",
                "inherit" => []
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

    public function setGroup(string $name, string $group): void
    {
        if (!$this->groups->exists($group)) return;

        $data = $this->users->get($name, []);
        $data["groups"] = [$group];

        $this->users->set($name, $data);
        $this->users->save();
    }

    public function addGroup(string $player, string $group): void
    {
        if (!$this->groups->exists($group)) return;

        $data = $this->users->get($player, []);

        if (!isset($data["groups"])) {
            $data["groups"] = [];
        }

        if (!in_array($group, $data["groups"], true)) {
            $data["groups"][] = $group;
        }

        $this->users->set($player, $data);
        $this->users->save();

    }

}