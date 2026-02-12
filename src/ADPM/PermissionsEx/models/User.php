<?php
/**
 * @org AusiDevelopmentPM
 * @author EyNoah1171
 * @year 2026
 * @license Dont steal my Code.
 */

namespace ADPM\PermissionsEx\models;

class User {

    public function __construct(public string $name, public array $groups = ["default"], public array $timedPermissions = [])
    {

    }

}