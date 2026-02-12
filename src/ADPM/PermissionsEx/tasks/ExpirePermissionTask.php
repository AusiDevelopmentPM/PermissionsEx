<?php
/**
 * @org AusiDevelopmentPM
 * @author EyNoah1171
 * @year 2026
 * @license Dont steal my Code.
 */

namespace ADPM\PermissionsEx\tasks;

use ADPM\PermissionsEx\api\PexAPI;
use ADPM\PermissionsEx\manager\PEXManager;
use pocketmine\permission\PermissionManager;
use pocketmine\scheduler\Task;

class ExpirePermissionTask extends Task
{

    public function __construct(private PEXManager $manager)
    {

    }

    public function onRun(): void
    {
        $this->manager->cleanupExpired();
    }

}