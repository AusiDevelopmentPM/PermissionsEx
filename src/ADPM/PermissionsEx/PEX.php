<?php
/**
 * @org AusiDevelopmentPM
 * @author EyNoah1171
 * @year 2026
 * @license Dont steal my Code.
 */

namespace ADPM\PermissionsEx;

use ADPM\PermissionsEx\api\PexAPI;
use ADPM\PermissionsEx\commands\PEXCommand;
use ADPM\PermissionsEx\manager\GroupManager;
use ADPM\PermissionsEx\manager\PEXManager;
use ADPM\PermissionsEx\manager\UserManager;
use pocketmine\permission\Permission;
use pocketmine\permission\PermissionManager;
use pocketmine\plugin\PluginBase;

class PEX extends PluginBase {

    private static self $instance;

    private static PexAPI $api;

    private PEXManager $PEXManager;
    private GroupManager $groupManager;
    private UserManager $userManager;

    public function onEnable(): void
    {
        self::$instance = $this;
        $this->initPerms();
        @mkdir($this->getDataFolder());
        $this->saveResource("groups.yml");
        $this->saveResource("users.yml");
        $this->PEXManager = new PEXManager($this);
        $this->groupManager = new GroupManager();
        $this->userManager = new UserManager();

        self::$api = new PexAPI();

        $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
        $this->getServer()->getCommandMap()->register("pex", new PEXCommand($this));

        $this->getLogger()->info("         
  ____                     _         _                 _____      
 |  _ \ ___ _ __ _ __ ___ (_)___ ___(_) ___  _ __  ___| ____|_  __
 | |_) / _ \ '__| '_ ` _ \| / __/ __| |/ _ \| '_ \/ __|  _| \ \/ /
 |  __/  __/ |  | | | | | | \__ \__ \ | (_) | | | \__ \ |___ >  < 
 |_|   \___|_|  |_| |_| |_|_|___/___/_|\___/|_| |_|___/_____/_/\_\
 made by EyNoah1171
");
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

    public function getPexAPI(): PexAPI
    {
        return self::$api;
    }

    public function getPermManager(): PEXManager
    {
        return $this->PEXManager;
    }



}