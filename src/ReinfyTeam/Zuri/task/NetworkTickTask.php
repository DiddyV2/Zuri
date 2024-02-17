<?php

/*
 *
 *  ____           _            __           _____
 * |  _ \    ___  (_)  _ __    / _|  _   _  |_   _|   ___    __ _   _ __ ___
 * | |_) |  / _ \ | | | '_ \  | |_  | | | |   | |    / _ \  / _` | | '_ ` _ \
 * |  _ <  |  __/ | | | | | | |  _| | |_| |   | |   |  __/ | (_| | | | | | | |
 * |_| \_\  \___| |_| |_| |_| |_|    \__, |   |_|    \___|  \__,_| |_| |_| |_|
 *                                   |___/
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author ReinfyTeam
 * @link https://github.com/ReinfyTeam/
 *
 *
 */

declare(strict_types=1);

namespace ReinfyTeam\Zuri\task;

use pocketmine\scheduler\Task;
use pocketmine\Server;
use ReinfyTeam\Zuri\APIProvider;
use ReinfyTeam\Zuri\config\ConfigManager;

class NetworkTickTask extends Task {
	private array $network = [];
	private static $instance = null;
	protected APIProvider $plugin;

	public function __construct(APIProvider $plugin) {
		$this->plugin = $plugin;
	}

	public function onRun() : void {
		self::$instance = $this;
		foreach (Server::getInstance()->getOnlinePlayers() as $player) {
			$ipPlayer = $player->getNetworkSession()->getIp();
			if (isset($this->network[$player->getXuid()])) {
				if ($this->network[$player->getXuid()]["ip"] != $ipPlayer) {
					$player->kick(ConfigManager::getData(ConfigManager::NETWORK_MESSAGE), null, ConfigManager::getData(ConfigManager::NETWORK_MESSAGE));
				}
			} else {
				$this->network[$player->getXuid()] = ["ip" => $ipPlayer, "player" => $player];
			}
		}
		foreach ($this->network as $xuid => $data) {
			$player2 = $data["player"];
			if (!$player2->isOnline()) {
				unset($this->network[$xuid]);
			}
		}
	}

	public static function getInstance() : self {
		return self::$instance;
	}
}
