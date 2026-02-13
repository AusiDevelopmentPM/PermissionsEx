<?php
/**
 * @org AusiDevelopmentPM
 * @author EyNoah1171
 * @year 2026
 * @license Dont steal my Code.
 */

namespace ADPM\PermissionsEx\commands;

use ADPM\PermissionsEx\api\PexAPI2;
use ADPM\PermissionsEx\Data;
use ADPM\PermissionsEx\PEX;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class PEXCommand extends Command
{

    private PEX $plugin;

    public function __construct(PEX $plugin)
    {
        parent::__construct("pex");
        $this->setPermission("pex.admin");
        $this->plugin = $plugin;
    }

    /**
     * @throws \JsonException
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        if (!$sender->hasPermission("pex.admin")) return false;

        if (count($args) < 1) {
            $this->sendHelpMessage($sender);
            return true;
        }

        $api = PEX::getInstance()->getPexAPI();
        $api2 = PexAPI2::getInstance();

        switch (strtolower($args[0])) {

            case "reload":
                if ($sender->hasPermission("pex.command.reload")) {
                    $this->plugin->getPermManager()->reload();
                    $sender->sendMessage(PEX::prefix() . "§aPEX has been reloaded.");
                } else {
                    $sender->sendMessage(Data::NoPermissionMessage());
                }
                break;

            case "user":
                if (!$sender->hasPermission("pex.command.user")) {
                    $sender->sendMessage(Data::NoPermissionMessage());
                    return false;
                }

                if (count($args) < 4) {
                    $sender->sendMessage(PEX::prefix() . "§cUsage§8: §f/pex user <player> <add|remove> <permission>");
                    return false;
                }

                $name = $args[1];
                $action = strtolower($args[2]);
                $permission = $args[3];

                switch ($action) {
                    case "add":
                        $api2->addPermissionToUser($name, $permission);
                        $sender->sendMessage("§aPermission added.");
                        break;
                    case "remove":
                        $api2->removePermissionFromUser($name, $permission);
                        $sender->sendMessage("§aPermission removed.");
                        break;

                    default:
                        $sender->sendMessage("§cInvalid action.");

                }

                return true;

            case "group":
                if (!$sender->hasPermission("pex.command.group")) {
                    $sender->sendMessage(Data::NoPermissionMessage());
                    return false;
                }

                if (count($args) < 3) {
                    $sender->sendMessage(PEX::prefix() . "§cUsage§8: §8/§fpex group <name> <create|delete|addperm|removeperm>");
                    return false;
                }

                $groupName = strtolower($args[1]);
                $action = strtolower($args[2]);

                switch ($action) {
                    case "create":
                        $api2->createGroup($groupName);
                        $sender->sendMessage("§aGroup created.");
                        break;

                    case "delete":
                        $api2->deleteGroup($groupName);
                        $sender->sendMessage("§aGroup deleted.");
                        break;

                        case "addperm":
                            if (!isset($args[3])) {
                                $sender->sendMessage(PEX::prefix() . "§cUsage§8: /§fpex group <name> add <permission>");
                                return false;
                            }

                            $api2->addPermissionToGroup($groupName, $args[3]);
                            $sender->sendMessage(PEX::prefix() . "§aPermission added to group.");
                            break;

                            case "removeperm":
                                if (!isset($args[3])) {
                                    $sender->sendMessage(PEX::prefix() . "§cUsage§8: /§fpex group <name> removeperm <permission>");
                                }

                                $api2->removePermissionFromUser($groupName, $args[3]);
                                $sender->sendMessage(PEX::prefix() . "§aPermission removed from group.");
                                break;

                    default:
                        $sender->sendMessage("§cInvalid action.");
                }

                return true;

            default:
                $sender->sendMessage(PEX::prefix() . "§cInvald Subcommand.");
        }


        return true;
    }

    private function sendHelpMessage(CommandSender $sender): void
    {
        $sender->sendMessage(PEX::prefix() . "§e/pex setgroup <player> <group>");
        $sender->sendMessage(PEX::prefix() . "§e/pex addgroup <player> <group>");
        $sender->sendMessage(PEX::prefix() . "§e/pex delgroup <player> <group>");
        $sender->sendMessage(PEX::prefix() . "§e/pex reload");
    }

}