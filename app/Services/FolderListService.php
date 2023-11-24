<?php
namespace App\Services;
use App\Models\Folder;

use function Laravel\Prompts\error;

class FolderListService {

	public function __construct(public Folder $folder){
	}
	
	public static function class(Folder $folder) {
		return new folderListService($folder);
	}

	public function tree($includeRoot, $maxPrivacyAllowed=5) {
		$accumulator = collect([]);
		$sorted = $this->recursivePush($this->folder, 0+intval($includeRoot), $accumulator, $maxPrivacyAllowed);
		return $sorted;
	}
	
	public function recursivePush($folder, $depth, $accumulator, $maxPrivacyAllowed) {
		if($folder->privacy_level_id > $maxPrivacyAllowed) return $accumulator;
		if($depth > 0) {
			$attributes = collect([
				"id" => $folder->id,
				"title" => $folder->getDisplayName(),
				"depth" => $depth,
				"parent_folder_id" => $folder->parent_folder_id
			]);
			$accumulator->push($attributes);
		}
		
		$children = $folder->allChildren;
		if($children->count() > 0) {
			foreach($children as $child) {
				$this->recursivePush($child, $depth+1, $accumulator, $maxPrivacyAllowed);
			}
		}
		return $accumulator;
	}

	public function lineage() {
		$ancestor = $this->folder;
		$accumulator = collect([]);
		while($ancestor !== null) {
			$attributes = collect([
				"id" => $ancestor->id,
				"title" => $ancestor->title,
				"privacy_level_id" => $ancestor->privacy_level_id
			]);
			$accumulator->push($attributes);
			$ancestor = $ancestor->ancestors;
		}
		return $accumulator;
	}
	
}