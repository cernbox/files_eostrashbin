<?php
OCP\JSON::checkLoggedIn();
OCP\JSON::callCheck();
\OC::$server->getSession()->close();
$instanceManager = \OC::$server->getCernBoxEosInstanceManager();
$username = OC::$server->getUserSession()->getUser()->getUID();

$filesToBeRestored = array();

if (isset($_POST['files'])) {
	// files to be restored are restore-keys.
	$filesToBeRestored = json_decode($_POST['files']);
} else if (isset($_POST['allfiles'])) {
	// files to be restored are trashbin cache entries
	$filesToBeRestored = $instanceManager->getDeletedFiles($username);
}

$error = array();
$success = array();


$i = 0;
foreach ($filesToBeRestored as $file) {
	if(is_string($file)) {
		// the format of the key is <name>.<eosrestorekey>
		// like 07Labrador.pdf.00000000017683cc
		$parts = explode(".", $file);
		$restoreKey = $parts[count($parts) - 1]; // last part is the restore key
		array_pop($parts);
		$fileName = implode(".",  $parts);
		$errorCode = $instanceManager->restoreDeletedFile($username, $restoreKey);
		if($errorCode === 0) {
			$success[$i]['filename']  = $file;
			$success[$i]['timestamp'] = time();
	 	} else {
	 		if($errorCode === 17) {
				// file/folder already exists
				$error[] = $fileName . " (folder/file already exists)";
			} else if (is_array($errorCode)) {
				list(, $restorePath) = $errorCode;
				$restorePath = substr($restorePath, strlen('files/'));
				$error[] = $fileName . " (you need to create the parent path: $restorePath)";
			} else {
				// whatever other error
				// TODO(labkode) add parent folder like in the case below ?
				// that means a way of retrieving the file name or pass it via
				// JS in the restore call
				$error[] = $fileName . " (you need to create the parent folder)";
			}
		}
		$i++;
	} else {
		// it comes from asking the instanceManager, it is
		// an array of cache entries.
		$errorCode = $instanceManager->restoreDeletedFile($username, $file->getRestoreKey());
		if($errorCode === 0) {
			$success[$i]['filename']  = basename($file->getOriginalPath());
			$success[$i]['timestamp'] = $file->getDeletionMTime();
		} else {
			$fileName = basename($file->getOriginalPath());
			if($errorCode === 17) {
				// file/folder already exists
				$error[] = $fileName . " (folder/file already exists)";
			} else {
				// whatever other error
				$dirName = dirname($file->getOriginalPath());
				// remove files/ prefix
				$dirName = substr($dirName, strlen('files/'));
				$error[] = $fileName . " (you need to create the parent folder: $dirName)";
			}
		}
		$i++;
	}
}
if ( $error ) {
	$fileList = '';
	foreach ( $error as $e ) {
		$fileList .= $e.', ';
	}
	$l = OC::$server->getL10N('files_trashbin');
	$message = $l->t("Couldn't restore %s", array(rtrim($fileList, ', ')));
	OCP\JSON::error(array("data" => array("message" => $message,
										  "success" => $success, "error" => $error)));
} else {
	OCP\JSON::success(array("data" => array("success" => $success)));
}
