<?php
/**
 * @copyright Copyright (c) 2017 Robin Appelman <robin@icewind.nl>
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace OCA\VirtualFolder\AppInfo;

use OCA\Files\Event\LoadAdditionalScriptsEvent;
use OCA\VirtualFolder\Listeners\LoadAdditionalScriptsListener;
use OCA\VirtualFolder\Mount\VirtualFolderMountProvider;
use \OCP\AppFramework\App;
use OCP\AppFramework\Bootstrap\IBootContext;
use OCP\AppFramework\Bootstrap\IBootstrap;
use OCP\AppFramework\Bootstrap\IRegistrationContext;
use OCP\Files\Config\IMountProviderCollection;

class Application extends App implements IBootstrap {
	public function __construct(array $urlParams = []) {
		parent::__construct('virtualfolder', $urlParams);
	}

	public function register(IRegistrationContext $context): void {
		$context->registerEventListener(LoadAdditionalScriptsEvent::class, LoadAdditionalScriptsListener::class);
	}

	public function boot(IBootContext $context): void {
		$context->injectFn(function (IMountProviderCollection $mountProviderCollection, VirtualFolderMountProvider $mountProvider) {
			$mountProviderCollection->registerProvider($mountProvider);
		});
	}
}
