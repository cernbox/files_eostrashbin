<?php
OCP\JSON::checkLoggedIn();
OCP\JSON::callCheck();
\OC::$server->getSession()->close();
$instanceManager = \OC::$server->getCernBoxEosInstanceManager();
$username = OC::$server->getUserSession()->getUser()->getUID();

$deletedFiles = $instanceManager->getDeletedFiles($username);
$empty = count($deletedFiles) > 0 ? true : false;
OCP\JSON::success(array("data" => array("isEmpty" => $empty)));


