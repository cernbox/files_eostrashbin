<?php
namespace OCA\Files_EosTrashbin;

use OC\CernBox\Storage\Eos\IDeletedEntry;

class Helper {

	/**
	 * @param []IDeletedEntry $fileInfos
	 * @return array
	 */
	public static function formatFileInfos($files) {
		$data = array();
		$id = 0;
		foreach ($files as $file) {
			$data[] = self::formatFileInfo($file, $id);
			$id++;
		}
		return $files;
	}

	private static function formatFileInfo(IDeletedEntry $i, $id) {
		\OC::$server->getLogger()->info("restore path -> " . $i->getOriginalPath());
		$entry = $i;
		$entry['id'] = $id;
		$entry['permissions'] = \OCP\Constants::PERMISSION_READ;
		$entry['mtime'] = $entry->getDeletionMTime();
		if ($entry->getType() === 'dir') {
			$entry['mimetype'] = 'httpd/unix-directory';

		} else {
			$entry['mimetype'] = \OC::$server->getMimeTypeDetector()->detectPath(basename($entry->getOriginalPath()));
		}
		return $entry;
	}

	private static function compareFileNames(IDeletedEntry $a, IDeletedEntry $b) {
		$aType = $a->getType();
		$bType = $b->getType();
		if ($aType === 'dir' and $bType !== 'dir') {
			return -1;
		} elseif ($aType !== 'dir' and $bType === 'dir') {
			return 1;
		} else {
			return \OCP\Util::naturalSortCompare($a['name'], $b['name']);
		}
	}

	private static function compareTimestamp(IDeletedEntry $a, IDeletedEntry $b) {
		$aTime = $a->getDeletionMTime();
		$bTime = $b->getDeletionMTime();
		return ($aTime < $bTime) ? -1 : 1;
	}

	private static function compareSize(IDeletedEntry $a, IDeletedEntry $b) {
		$aSize = $a->getSize();
		$bSize = $b->getSize();
		return ($aSize < $bSize) ? -1 : 1;
	}

	public static function sortFiles($files, $sortAttribute = 'name', $sortDescending = false)
	{
		$sortFunc = 'compareFileNames';
		if ($sortAttribute === 'mtime')
		{
			$sortFunc = 'compareTimestamp';
		}
		else if ($sortAttribute === 'size')
		{
			$sortFunc = 'compareSize';
		}

		usort($files, array('\OCA\Files_EosTrashbin\Helper', $sortFunc));
		if ($sortDescending) {
			$files = array_reverse($files);
		}
		return $files;
	}
}
