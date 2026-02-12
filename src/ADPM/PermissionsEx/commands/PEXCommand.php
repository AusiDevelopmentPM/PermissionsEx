<?php
/**
 * @org AusiDevelopmentPM
 * @author EyNoah1171
 * @year 2026
 * @license Dont steal my Code.
 */

namespace ADPM\PermissionsEx\commands;

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

        switch (strtolower($args[0])) {
            case "setgroup":

                if (count($args) < 3) {
                    $sender->sendMessage("§c/pex setgroup <player> <group>");
                    return true;
                }

                if (!PEX::getInstance()->getPexAPI()->setGroup($args[1], $args[2])) {
                    $sender->sendMessage("§cThis group does not exists");
                    return true;
                }

                $api->setGroup($args[1], $args[2]);
                $sender->sendMessage("§aGroup has been rewrited!");

                break;
            case "addgroup":
                if (count($args) < 3) {
                    $sender->sendMessage("§c/pex addgroup <player> <group>");
                    return true;
                }

                $api->addGroup($args[1], $args[2]);
                $sender->sendMessage("§aGroup has been added!");
                break;
            case "delgroup":
                if (count($args) < 3) {
                    $sender->sendMessage("§c/pex delgroup <player> <group>");
                    return true;
                }

                $api->removeGroup($args[1], $args[2]);
                $sender->sendMessage("§aGroup has been removed!");

                break;
            case "reload":
                $this->plugin->getPermManager()->reload();
                $sender->sendMessage("§aPEX has been reloaded.");
                break;

            default:
                $sender->sendMessage("§cInvald Subcommand.");
        }


        return true;
    }

    private function sendHelpMessage(CommandSender $sender): void
    {
        $sender->sendMessage("§e/pex setgroup <player> <group>");
        $sender->sendMessage("§e/pex addgroup <player> <group>");
        $sender->sendMessage("§e/pex delgroup <player> <group>");
        $sender->sendMessage("§e/pex reload");
    }

}