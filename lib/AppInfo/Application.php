<?php
namespace OCA\Files_EosTrashbin\AppInfo;

use OCP\AppFramework\App;
use OCA\Files_EosTrashbin\Expiration;

class Application extends App {
	public function __construct (array $urlParams = []) {
		parent::__construct('files_eostrashbin', $urlParams);
	}
}
