<?php
namespace App\Services;
use App\Models\Artwork;
use App\Models\Tag;
use App\Models\User;
use App\Services\SanitiseService;

class TaggerService {
	
	public static function tagArtwork(Artwork $artwork, array $tags) {
		$incomingtagIDs = collect($tags)->map(function($tagraw) {
			if(!trim($tagraw)) return null;
			$tagstring = SanitiseService::makeTag($tagraw, 32);
			$incomingtag = Tag::firstOrNew(["id" => $tagstring]);
			$incomingtag->save();
			return $tagstring;
		});

		$removeTagsIDs = $artwork->tags()->get()->pluck("id")->diff($incomingtagIDs);
		$artwork->tags()->sync(
			$incomingtagIDs->map(function($tag){ return collect(["tag_id"=>$tag]); })
		);
		foreach($removeTagsIDs as $removeTagID) self::cleanupTags($removeTagID);
	}

	public static function cleanupTags($tagID) {
		$tag = Tag::where("id", $tagID)->firstOrFail();
		$artlist = $tag->artworks()->get();
		if($artlist->count() === 0) {
			Tag::find($tagID)->delete();
		}
	}

}