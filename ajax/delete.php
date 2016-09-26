<?php
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
