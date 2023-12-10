<?php namespace App\Services;

use App\Models\User;
use App\Models\Artwork;
use Illuminate\Http\Request;

class PrivacyLevelService {
	public static function getMaxPrivacyAllowed($user, $ownerIDs) {
		if(!$user) return 1;
		if($ownerIDs->contains(auth()->user()->id)) return 5;
		return 2;
	}

	public static function filterArtworkCollection($user, $artworks) {
		return $artworks->reject(function($artwork) use($user) {
			$ownerIDs = $artwork->users()->get()->pluck("id");
			$maxPrivacyAllowed = PrivacyLevelService::getMaxPrivacyAllowed($user, $ownerIDs);
			$artworkPrivacy = $artwork->folders()->get()->map(function($i) { return $i->getLineagePrivacyLevel(); })->max();
			return($artworkPrivacy > $maxPrivacyAllowed);
		});
	}
}