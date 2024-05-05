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

namespace ReinfyTeam\Zuri\checks\moving;

use pocketmine\event\Event;
use pocketmine\event\player\PlayerMoveEvent;
use ReinfyTeam\Zuri\checks\Check;
use ReinfyTeam\Zuri\player\PlayerAPI;

class ClickTP extends Check {
	public function getName() : string {
		return "ClickTP";
	}

	public function getSubType() : string {
		return "A";
	}

	public function maxViolations() : int {
		return 1;
	}

	public function checkEvent(Event $event, PlayerAPI $playerAPI) : void {
		if ($event instanceof PlayerMoveEvent) {
			$distance = $event->getFrom()->distanceSquared($event->getTo());
			$oldYaw = $event->getFrom()->getYaw();
			$newYaw = $event->getTo()->getYaw();
			$oldPitch = $event->getFrom()->getPitch();
			$newPitch = $event->getTo()->getPitch();
			if ($distance > 40.0 && $oldYaw === $newYaw && $oldPitch === $newPitch) {
				$event->cancel();
				$this->failed($playerAPI);
				$this->debug($playerAPI, "distance=$distance, oldYaw=$oldYaw, newYaw=$newYaw, oldPitch=$oldPitch, newPitch=$newPitch");
			}
		}
	}
}