<?php
namespace App\Services;
use Illuminate\Support\Str;

class SanitiseService {
  
  public static function sanitiseHTML($string) {
    $blocked = implode(
			"|",
			["script", "style", "title", "head", "body"]
		);
		$re = "/<\/?($blocked).*?>/";
	  $string = preg_replace($re, "", $string);
    return $string;
  }

	public static function makeURL(string $string, int $maxWords, int $hashLength) {
		$cleanString = preg_replace("/[^A-Za-z0-9]+/", " ", $string);
		$string = Str::of($cleanString)->squish()->trim()->lower()->words($maxWords)->kebab();
		if($hashLength > 0) $string = $string . "-" . Str::random($hashLength);
		return $string;
	}

	public static function makeTag(string $string, int $maxLength=255) {
		$string = preg_replace("/[^A-Za-z0-9]+/", " ", $string);
		$string = Str::of($string)->squish()->trim()->substr(0, $maxLength)->lower()->kebab();
		error_log($string);
    	return $string;
	}

}