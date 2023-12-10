<?php
namespace App\Services;

use App\Models\Artwork;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Str;

class SanitiseService {

	public function __construct(public $string) {
		return $this;
	}

	public static function of(string $string) {
		return new SanitiseService($string);
	}
  
  public function sanitiseHTML() {
    $blocked = implode(
			"|",
			["script", "style", "title", "head", "body"]
		);
		$re = "/<\/?($blocked).*?>/";
	  $this->string = preg_replace($re, "", $this->string);
    	return $this;
  }
  
	public function stripHTML() {
			$re = "/<\/?.*?>/";
		$this->string = preg_replace($re, "", $this->string);
			return $this;
	}

	public function makeURL(int $maxWords, int $hashLength) {
		$cleanString = preg_replace("/[^A-Za-z0-9]+/", " ", $this->string);
		$this->string = Str::of($cleanString)->squish()->trim()->lower()->words($maxWords)->kebab();
		if($hashLength > 0) $this->string = $this->string . "-" . Str::random($hashLength);
    	return $this;
	}

	public function makeTag(int $maxLength=255) {
		$this->string = preg_replace("/[^A-Za-z0-9]+/", " ", $this->string);
		$this->string = Str::of($this->string)->squish()->trim()->substr(0, $maxLength)->lower()->kebab();
    	return $this;
	}

	public function makePing(Artwork $artwork=null) {
		preg_match_all("/\@([\w\-]+)/", $this->string, $pings);
		foreach($pings[1] as $i => $username) {
			$user = User::where("name", $username)->first();
			if($user) {
				$notif = Notification::create([
					"sender_id" => auth()->user()->id,
					"artwork_id" => $artwork->id ?? null,
					"type" => "ping"
				]);
				$user->notifications()->attach($notif);
			}
		}
    	return $this;
	}

	public function formatPings() {
		preg_match_all("/\@([\w\-]+)/", $this->string, $pings);
		foreach($pings[0] as $i => $ping) {
			$username = $pings[1][$i];
			$user = User::where("name", $username)->first();
			if($user) $this->string = str_replace($ping, $user->getNametag(), $this->string);
		}
    	return $this;
	}

	public function get() {
		return $this->string;
	}

}