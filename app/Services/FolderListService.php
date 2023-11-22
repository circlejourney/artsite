<?php
namespace App\Services;
use App\Models\Folder;
use App\Models\User;

use function Laravel\Prompts\error;

class FolderListService {

	public function __construct(public Folder $folder){
	}
	
	public static function class(Folder $folder) {
		return new folderListService($folder);
	}

	public function tree() {
		$sorted = $this->recursivePush($this->folder, 0, collect([]));
		return $sorted;
	}	
	
	public function recursivePush($folder, $depth, $accumulator) {
		if($depth > 0) {
			$attributes = array(
				"id" => $folder->id,
				"title" => $folder->title,
				"depth" => $depth,
				"parent_folder_id" => $folder->parent_folder_id
			);
			$accumulator->push($attributes);
		}
		
		$children = $folder->allChildren;
		if($children->count() > 0) {
			foreach($children as $child) {
				$this->recursivePush($child, $depth+1, $accumulator);
			}
		}
		return $accumulator;
	}

	public function listChildren() {
		
	}
	
}