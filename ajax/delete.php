<?php
/**
 * @author Bart Visscher <bartv@thisnet.nl>
 * @author Björn Schießle <bjoern@schiessle.org>
 * @author Lukas Reschke <lukas@statuscode.ch>
 * @author Robin Appelman <icewind@owncloud.com>
 * @author Roeland Jago Douma <rullzer@owncloud.com>
 * @author Vincent Petry <pvince81@owncloud.com>
 *
 * @copyright Copyright (c) 2016, ownCloud, Inc.
 * @license AGPL-3.0
 *
 * This code is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License, version 3,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License, version 3,
 * along with this program.  If not, see <http://www.gnu.org/licenses/>
 *
 */
OCP\JSON::checkLoggedIn();
OCP\JSON::callCheck();
\OC::$server->getSession()->close();
$username = \OC::$server->getUserSession()->getUser()->getUID();
$instanceManager = \OC::$server->getCernBoxEosInstanceManager();

$folder = isset($_POST['dir']) ? $_POST['dir'] : '/';

// "empty trash" command
if (isset($_POST['allfiles']) && (string)$_POST['allfiles'] === 'true'){
	$purged = $instanceManager->purgeAllDeletedFiles($username);
	if($purged) {
		OCP\JSON::success(array("data" => array("success" => array())));
	} else {
		OCP\JSON::error(array("data" => array("message" => "not all files have been purged", "success" => array(), "error" => array())));
	}
}
else {
		OCP\JSON::error(array("data" => array("message" => "Single file purging is not allowed", "success" => array(), "error" => array())));
}
