<?php
namespace App\Services;
use App\Models\Folder;


class FolderListService {

	public function __construct(public object $folderlist){}
	
	public static function class($folderlist) {
		return new FolderListService($folderlist);
	}

	public function makeTree(): array {
		$unsorted = $this->folderlist->orderBy("depth", "asc")->get();
		$sorted = collect();
		foreach($unsorted as $folder) {
			$parentid = $folder->parent_folder_id;
			$foundparent = $sorted->search(function($item)use($parentid) {
				return $item->id === $parentid;
			});
			if($foundparent === false) {
				$sorted->push($folder);
				continue;
			}
			$insertpoint = $foundparent + 1;
			$sorted->splice($insertpoint, 0, [$folder]);
		}
		return $sorted->all();
	}
}