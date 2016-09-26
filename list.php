<?php
// Check if we are a user
OCP\User::checkLoggedIn();


$tmpl = new OCP\Template('files_eostrashbin', 'index', '');
OCP\Util::addStyle('files_eostrashbin', 'trash');
OCP\Util::addScript('files_eostrashbin', 'app');
OCP\Util::addScript('files_eostrashbin', 'filelist');
OCP\Util::addScript('files_eostrashbin', 'restorepathview');
$tmpl->printPage();
