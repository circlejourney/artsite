<?php
namespace App\Services;
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

	public function makePing() {
		preg_match_all("/@[\w\-]+/", $this->string, $pings, PREG_OFFSET_CAPTURE);
		foreach($pings[1] as $ping) {
			error_log($ping);
		}
    	return $this;
	}

	public function get() {
		return $this->string;
	}

}