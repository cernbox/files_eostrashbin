<?php
namespace OCA\Files_EosTrashbin\AppInfo;

$application = new Application();

$this->create('files_eostrashbin_ajax_delete', 'ajax/delete.php')
	->actionInclude('files_eostrashbin/ajax/delete.php');
$this->create('files_eostrashbin_ajax_isEmpty', 'ajax/isEmpty.php')
	->actionInclude('files_eostrashbin/ajax/isEmpty.php');
$this->create('files_eostrashbin_ajax_list', 'ajax/list.php')
	->actionInclude('files_eostrashbin/ajax/list.php');
$this->create('files_eostrashbin_ajax_undelete', 'ajax/undelete.php')
	->actionInclude('files_eostrashbin/ajax/undelete.php');

