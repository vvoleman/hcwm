<?php
declare(strict_types=1);

namespace App\Service\Geojson;

class DataComposerUtil
{

	public function clearCache(): bool
	{
		$dir = AbstractDataComposer::FOLDER_PATH;

		// Clear everything in $dir
		$files = glob($dir.'/*');
		$results = [];
		foreach ($files as $file) {
			if (is_file($file)) {
				$result = unlink($file);
			} else {
				$result = rmdir($file);
			}

			$results[] = $result;
		}

		return !in_array(false, $results);
	}

}