<?php
OCP\JSON::checkLoggedIn();
\OC::$server->getSession()->close();
$instanceManager = \OC::$server->getCernBoxEosInstanceManager();
$username = OC::$server->getUserSession()->getUser()->getUID();

// Load the files
$dir = isset($_GET['dir']) ? (string)$_GET['dir'] : '';
$sortAttribute = isset($_GET['sort']) ? (string)$_GET['sort'] : 'name';
$sortDirection = isset($_GET['sortdirection']) ? ($_GET['sortdirection'] === 'desc') : false;
$data = array();

// make filelist
try {
	$deletedFiles = $instanceManager->getDeletedFiles($username);
	$deletedFiles = \OCA\Files_EosTrashbin\Helper::sortFiles($deletedFiles, $sortAttribute, $sortDirection);
} catch (Exception $e) {
	header("HTTP/1.0 404 Not Found");
	exit();
}

$encodedDir = \OCP\Util::encodePath($dir);

\OC::$server->getLogger()->info("number of deleted files: " . count($deletedFiles));
$data['permissions'] = 0;
$data['directory'] = $dir;
$data['files'] = \OCA\Files_EosTrashbin\Helper::formatFileInfos($deletedFiles);

OCP\JSON::success(array('data' => $data));

