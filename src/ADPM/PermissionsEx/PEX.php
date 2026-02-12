<?php
/**
 * @org AusiDevelopmentPM
 * @author EyNoah1171
 * @year 2026
 * @license Dont steal my Code.
 */

namespace ADPM\PermissionsEx;

use ADPM\PermissionsEx\commands\PEXCommand;
use ADPM\PermissionsEx\manager\PEXManager;
use pocketmine\permission\Permission;
use pocketmine\permission\PermissionManager;
use pocketmine\plugin\PluginBase;

class PEX extends PluginBase {

    private static self $instance;

    private PEXManager $PEXManager;

    public function onEnable(): void
    {
        self::$instance = $this;
        $this->initPerms();
        @mkdir($this->getDataFolder());
        $this->saveResource("groups.yml");
        $this->saveResource("users.yml");
        $this->PEXManager = new PEXManager($this);
        $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
        $this->getServer()->getCommandMap()->register("pex", new PEXCommand($this));
    }

    private function initPerms(): void
    {
        $perm = new Permission("pex.admin");
        PermissionManager::getInstance()->addPermission($perm);
        $this->getLogger()->info("Permissions for Plugin registerd");
    }

    public static function getInstance(): PEX
    {
        return self::$instance;
    }

    public function getPermManager(): PEXManager
    {
        return $this->PEXManager;
    }



}