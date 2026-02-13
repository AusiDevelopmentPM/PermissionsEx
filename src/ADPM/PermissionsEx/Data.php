<?php
/**
 * @org AusiDevelopmentPM
 * @author EyNoah1171
 * @year 2026
 * @license Dont steal my Code.
 */

namespace ADPM\PermissionsEx;

class Data {



    public static function NoPermissionMessage(): string
    {
        return PEX::prefix() . "§cYou dont have permission to execute this command.";
    }

}