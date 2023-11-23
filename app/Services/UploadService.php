<?php namespace App\Services;
use Illuminate\Support\Facades\Storage;

class UploadService {
    
    public function __construct(public $relative_path, public $thumbnail=null){
    }

    public static function upload($file, $target_folder) {
        $filename = $file->hashName();
        Storage::put($target_folder, $file);
        $relative_path = "$target_folder/$filename";
        return new UploadService($relative_path);
    }

    public static function find($relative_path) {
        $file = Storage::get($relative_path);
        return new UploadService($relative_path);
    }

	public static function create($filename, $content, $target_folder) {
		// TODO: write this
		$relative_path = "$target_folder/$filename";
		Storage::put($relative_path, $content);
		return new UploadService($relative_path);
	}

	public function update($content) {
		Storage::put($this->relative_path, $content);
		return $this;
	}
    
    public static function duplicate($relative_path, $new_file_suffix) {
        $imagepath = $relative_path;
        $old_path = dirname($relative_path);
		$mime = explode("/", Storage::mimeType($imagepath))[1];
		$basename = explode(".", basename($imagepath))[0];
        $new_path = "$old_path/$basename"."$new_file_suffix..$mime";
		Storage::copy($relative_path, $new_path);
        return new UploadService($new_path);
    }

    public function delete() {
        Storage::delete($this->relative_path);
        return $this;
    }

    public function resizeToFit($maxsize) {
		$imagepath = Storage::path($this->relative_path);
		if(!$imagesize = getimagesize($imagepath) ) {
            return $this;
        };
		$hwratio = $imagesize[1] / $imagesize[0];
        $scaleH = $imagesize[1] > $imagesize[0] ? $maxsize : $maxsize * $hwratio;
        $scaleW = $imagesize[0] > $imagesize[1] ? $maxsize : $maxsize / $hwratio;

        if($imagesize[1] <= $maxsize && $imagesize[0] <= $maxsize) {
            return $this;
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
        
        return $this;
	}

    public function makeThumbnail($maxsize) : UploadService {
        $thumbnail = UploadService::duplicate($this->relative_path, "_thumb")->resizeToFit($maxsize);
        $thumbpath = $thumbnail->getRelativePath();
		return new UploadService($thumbpath);
    }

    function getStoragePath() {
        return Storage::path($this->relative_path);
    }

    function getRelativePath() {
        return $this->relative_path;
    }

    function getURL() {
        return Storage::url($this->relative_path);
    }

	public function getContent() {
		return Storage::get($this->relative_path);
	}
}