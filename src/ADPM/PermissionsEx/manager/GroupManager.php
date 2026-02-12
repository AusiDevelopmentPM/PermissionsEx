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
use pocketmine\utils\Config;

class GroupManager
{

    private Config $config;
    private array $groups = [];

    public function __construct(private PEX $plugin)
    {
        $this->config = new Config($this->plugin->getDataFolder() . "groups.yml", Config::YAML);
        $this->load();
    }

    public function load(): void
    {
        $this->groups = [];
        foreach ($this->config->getAll() as $name => $data) {
            $this->groups[$name] = new Group(
                $name,
                $data["permissions"] ?? [],
                $data["inherit"] ?? [],
                $data["weight"] ?? 0,
                $data["prefix"] ?? "",
                $data["suffix"] ?? ""
            );
        }
    }

    public function get(string $name): ?Group
    {
        return $this->groups[$name] ?? null;
    }

    public function getSorted(array $groupNames): array
    {
        $groups = [];
        foreach ($groupNames as $name) {
            if (isset($this->groups[$name])) {
                $groups[] = $this->groups[$name];
            }
        }

        usort($groups, fn($a, $b) => $b->weight <=> $a->weight);
        return $groups;
    }

}