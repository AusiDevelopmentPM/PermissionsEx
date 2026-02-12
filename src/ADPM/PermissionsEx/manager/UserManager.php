<?php
/**
 * @org AusiDevelopmentPM
 * @author EyNoah1171
 * @year 2026
 * @license Dont steal my Code.
 */

namespace ADPM\PermissionsEx\manager;

use ADPM\PermissionsEx\models\User;
use pocketmine\utils\Config;

class UserManager
{

    private Config $config;
    private array $users = [];

    public function __construct(private PEX $plugin)
    {
        $this->config = new Config($plugin->getDataFolder() . "users.yml", Config::YAML);
        $this->load();
    }

    public function load(): void
    {
        $this->users = [];
        foreach ($this->config->getAll() as $name => $data) {
            $this->users[$name] = new User(
                $name,
                $data["groups"] ?? ["default"],
                $data["timed"] ?? []
            );
        }
    }

    public function get(string $name): User
    {
        $name = strtolower($name);
        if (!isset($this->users[$name])) {
            $this->users[$name] = new User($name);
        }
        return $this->users[$name];
    }

    public function save(): void
    {
        $data = [];
        foreach ($this->users as $name => $user) {
            $data[$name] = [
                "groups" => $user->groups,
                "timed" => $user->timedPermissions
            ];
        }
        $this->config->setAll($data);
        $this->config->save();
    }

}