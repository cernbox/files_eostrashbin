<?php
$l = \OC::$server->getL10N('files_eostrashbin');

\OCA\Files\App::getNavigationManager()->add(
array(
	"id" => 'eostrashbin',
	"appname" => 'files_eostrashbin',
	"script" => 'list.php',
	"order" => 50,
	"name" => $l->t('Deleted files')
)
);
