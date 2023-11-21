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
		$stringparts = explode(" ", $string);
		$string = trim( implode("-", array_slice($stringparts, 0, $maxWords)), "-");
    $string = strtolower( preg_replace("/[^A-Za-z0-9]+/", "-", $string) );

    if($hashLength > 0) $string = $string . "-" . Str::random($hashLength);
		
    return $string;
	}

}