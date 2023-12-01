<?php
namespace App\Services;
use App\Models\Artwork;
use App\Models\Tag;
use App\Models\User;
use App\Services\SanitiseService;

class TaggerService {
	
	public static function tagArtwork(Artwork $artwork, array $tags) {
		$user = auth()->user();
		$incomingtagIDs = collect($tags)->map(function($tagraw) use($user) {
			if(!trim($tagraw)) return null;
			$tagstring = SanitiseService::makeTag($tagraw, 32);
			$incomingtag = Tag::firstOrNew([
				"name" => $tagstring,
				"user_id" => $user->id
			]);
			$incomingtag->save();
			return $incomingtag->id;
		})->filter();
		$currentTagIDs = $artwork->tags->where("user_id", $user->id)->pluck("id");
		$removeTagIDs = $currentTagIDs->diff($incomingtagIDs);
		$addTagIDs = $incomingtagIDs->diff($currentTagIDs);
		$artwork->tags()->detach($removeTagIDs);
		$artwork->tags()->attach($addTagIDs);
		foreach($removeTagIDs as $removeTagID) self::cleanupTags($removeTagID);
	}

	public static function cleanupTags($tagID) {
		$tag = Tag::where("id", $tagID)->firstOrFail();
		$artlist = $tag->artworks()->get();
		if($artlist->count() === 0) {
			$tag->delete();
		}
	}
}