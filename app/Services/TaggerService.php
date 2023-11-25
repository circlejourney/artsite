<?php
namespace App\Services;
use App\Models\Artwork;
use App\Models\Tag;
use App\Services\SanitiseService;

class TaggerService {
	
	public static function tagArtwork(Artwork $artwork, array $tags) {
		$incomingtagIDs = collect($tags)->map(function($tagraw) {
			if(!$tagraw) return "";
			$tagstring = SanitiseService::makeTag($tagraw, 32);
			$incomingtag = Tag::firstOrNew(["id" => $tagstring]);
			$incomingtag->save();
			return $incomingtag->artwork_id;
		});

		$removeTags = $artwork->tags()->get()->reject(function($tag) use($incomingtagIDs) {
			$incomingtagIDs->doesntContain($tag->id);
		});

		$artwork->tags()->sync($tags);
		foreach($removeTags as $removeTag) self::cleanupTags($removeTag);
	}

	public static function cleanupTags($tag) {
		$tagID = $tag->pivot->tag_id;
		$artlist = Artwork::whereHas("tags", function($query) use($tagID) {
			return $query->where("id", $tagID);
		})->get();
		if($artlist->count() === 0) {
			Tag::find($tagID)->delete();
		}
	}

}