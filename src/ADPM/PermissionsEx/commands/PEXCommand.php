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

        if (count($args) < 3) {
            $sender->sendMessage("/pex setgroup <player> <group>");
            return true;
        }

        if ($args[0] === "setgroup") {
            $this->plugin->getPermManager()->setUserGroup($args[1], $args[2]);
            $sender->sendMessage("§aGruppe gesetzt.");
        }

        return true;
    }
}