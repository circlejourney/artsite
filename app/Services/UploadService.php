<?php namespace App\Services;
use Illuminate\Support\Facades\Storage;

class UploadService {
    public function upload($file, $target_folder) {
		// $target_folder is relative to the public/storage.
		$filename = $file->hashName();
        Storage::put($target_folder, $file);
		return "$target_folder/$filename";
    }

	public function delete($relative_path) {
		// $relative_path is relative to the public/storage.
		Storage::delete($relative_path);
	}

	public function resizeToFit($relative_path, $maxsize) {
		$imagepath = Storage::path($relative_path);
		if(!$imagesize = getimagesize($imagepath) ) {
            return false;
        };
		$hwratio = $imagesize[1] / $imagesize[0];
        $scaleH = $imagesize[1] > $imagesize[0] ? $maxsize : $maxsize * $hwratio;
        $scaleW = $imagesize[0] > $imagesize[1] ? $maxsize : $maxsize / $hwratio;

        if($imagesize[1] <= $maxsize && $imagesize[0] <= $maxsize) {
            return true;
        }

        $mime = explode("/", $imagesize["mime"])[1];
        $imagecreatefunc = "imagecreatefrom".$mime;
        
        $source_image_blob = $imagecreatefunc($imagepath);
        $destination_image_blob = imagecreatetruecolor($scaleW, $scaleH);
        imagealphablending($destination_image_blob, false);
        imagesavealpha($destination_image_blob, true);
        $clear = imagecolorallocatealpha($destination_image_blob, 255, 255, 255, 127);
        imagefilledrectangle($destination_image_blob, 0, 0, $scaleW, $scaleH, $clear);
        imagecopyresampled( $destination_image_blob, $source_image_blob,
            0, 0,
            0, 0,
            $scaleW, $scaleH,
            $imagesize[0], $imagesize[1]
        );
        
        unlink(realpath($imagepath));
		$imagefunc = "image".$mime;
        $imagefunc($destination_image_blob, $imagepath);
        
        return $imagepath;
	}
    
    public function generate_thumbnail($imagepath, $target_path, $maxsize) {
		$filenameparts = explode(".", basename($imagepath));
		$basename = $filenameparts[0];
        $mime = $filenameparts[1];
		$thumbpath = "$target_path/$basename"."_thumb.$mime";
		Storage::copy($imagepath, $thumbpath);
		$this->resizeToFit($thumbpath, $maxsize);
		return $thumbpath;
    }
}
