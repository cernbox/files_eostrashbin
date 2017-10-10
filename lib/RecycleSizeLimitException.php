<?php

namespace OCA\Files_EosTrashbin;


class RecycleSizeLimitException extends \Exception  {

	/**
	 * RecycleSizeLimitException constructor.
	 */
	public function __construct($message) {
		$this->message = $message;
		parent::__construct($message);
	}
}