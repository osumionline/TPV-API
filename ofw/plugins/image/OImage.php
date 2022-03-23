<?php declare(strict_types=1);

namespace OsumiFramework\OFW\Plugins;

use \GdImage;

/**
 * Utility class with tools to manipulate images (create new, resize, get information...)
 */
class OImage {
	private ?GdImage $image      = null;
	private ?int     $image_type = null;

	/**
	 * Get Base64 encoded image file's extension
	 *
	 * @param string $data Base64 encoded image file
	 *
	 * @return string Image extension
	 */
	public static function getImageExtension(string $data): string {
		$arr_data = explode(';', $data);
		$arr_data = explode(':', $arr_data[0]);
		$arr_data = explode('/', $arr_data[1]);

		return $arr_data[1];
	}

	/**
	 * Save a Base64 encoded image file on given location
	 *
	 * @param string $path Path where the file should be saved
	 *
	 * @param string $base64_string Base64 encoded image file
	 *
	 * @param string $name Name of the resulting image file
	 *
	 * @param string $ext Extension of the resulting image file
	 *
	 * @param bool $overwrite Overwrite if image found on given location (default true)
	 *
	 * @return string Full path of the resulting image file
	 */
	public static function saveImage(string $path, string $base64_string, string $name, string $ext, bool $overwrite=true): string {
		$full_path = $path.$name.'.'.$ext;

		if (file_exists($full_path)) {
			unlink($full_path);
		}

		$ifp = fopen($full_path, "wb");
		$data = explode(',', $base64_string);
		fwrite($ifp, base64_decode($data[1]));
		fclose($ifp);

		return $full_path;
	}

	/**
	 * Get loaded files image type
	 *
	 * @return int Image type constant of the loaded file (or null if file hasn't been loaded yet)
	 */
	public function getImageType(): ?int {
		return $this->image_type;
	}

	/**
	 * Load into memory the specified file
	 *
	 * @param string $filename Path of the file to be loaded
	 *
	 * @return void
	 */
	public function load(string $filename): void {
		$image_info       = getimagesize($filename);
		$this->image_type = $image_info[2];

		switch ($this->image_type) {
			case IMAGETYPE_JPEG: { $this->image = imagecreatefromjpeg($filename); }
			break;
			case IMAGETYPE_GIF: { $this->image = imagecreatefromgif($filename);  }
			break;
			case IMAGETYPE_PNG: { $this->image = imagecreatefrompng($filename);  }
			break;
			case IMAGETYPE_WEBP: { $this->image = imagecreatefromwebp($filename); }
			break;
		}

		if ($this->image_type === IMAGETYPE_PNG || $this->image_type === IMAGETYPE_WEBP) {
			imagepalettetotruecolor($this->image);
			imagealphablending($this->image, true);
			imagesavealpha($this->image, true);
		}
	}

	/**
	 * Save previously loaded file into the specified format, with a given compression rato and new file permissions
	 *
	 * @param string $filename Path of the new file to be created
	 *
	 * @param int $image_type New images file format
	 *
	 * @param int $compression Compression rate of the new file
	 *
	 * @param int $permissions Permissions of the new file
	 *
	 * @return void
	 */
	public function save(string $filename, int $image_type=IMAGETYPE_JPEG, int $compression=75, int $permissions=null): void {
		switch ($image_type) {
			case IMAGETYPE_JPEG: { imagejpeg($this->image, $filename, $compression); }
			break;
			case IMAGETYPE_GIF: { imagegif($this->image,  $filename); }
			break;
			case IMAGETYPE_PNG: { imagepng($this->image,  $filename); }
			break;
			case IMAGETYPE_WEBP: { imagewebp($this->image, $filename); }
			break;
		}
		if (!is_null($permissions)) {
			chmod($filename, $permissions);
		}
	}

	/**
	 * Change format of the loaded file
	 *
	 * @param int $image_type Format to be converted to
	 *
	 * @return void
	 */
	public function output(int $image_type=IMAGETYPE_JPEG): void {
		switch ($image_type) {
			case IMAGETYPE_JPEG: { imagejpeg($this->image); }
			break;
			case IMAGETYPE_GIF: {  imagegif($this->image);  }
			break;
			case IMAGETYPE_PNG: {  imagepng($this->image);  }
			break;
			case IMAGETYPE_WEBP: { imagewebp($this->image); }
			break;
		}
	}

	/**
	 * Get width of the loaded file
	 *
	 * @return int Width of the loaded file
	 */
	public function getWidth(): int {
		return imagesx($this->image);
	}

	/**
	 * Get height of the loaded file
	 *
	 * @return int Height of the loaded file
	 */
	public function getHeight(): int {
		return imagesy($this->image);
	}

	/**
	 * Resize loaded file to a fixed height mantaining the ratio
	 *
	 * @param int $height Height of the new file
	 *
	 * @return void
	 */
	public function resizeToHeight(int $height): void {
		$ratio = $height / $this->getHeight();
		$width = $this->getWidth() * $ratio;
		$this->resize($width, $height);
	}

	/**
	 * Resize loaded file to a fixed width mantaining the ratio
	 *
	 * @param int $width Width of the new file
	 *
	 * @return void
	 */
	public function resizeToWidth(int $width): void {
		$ratio  = $width / $this->getWidth();
		$height = intval($this->getheight() * $ratio);
		$this->resize($width, $height);
	}

	/**
	 * Scale loaded file to a given percentage ratio
	 *
	 * @param int $scale Scale ratio to be resized to
	 *
	 * @return void
	 */
	public function scale(int $scale): void {
		$width  = $this->getWidth() * $scale/100;
		$height = $this->getheight() * $scale/100;
		$this->resize($width, $height);
	}

	/**
	 * Resize image to a fixed width/height
	 *
	 * @param int $width New width of the loaded file
	 *
	 * @param int $height New height of the loaded file
	 *
	 * @return void
	 */
	public function resize(int $width, int $height): void {
		$new_image = imagecreatetruecolor($width, $height);
		if ($this->image_type === IMAGETYPE_PNG || $this->image_type === IMAGETYPE_WEBP) {
			imagealphablending($new_image, false);
			imagesavealpha($new_image, true);
			$transparent = imagecolorallocatealpha($new_image, 255, 255, 255, 127);
			imagefilledrectangle($new_image, 0, 0, $width, $height, $transparent);
		}
		imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
		$this->image = $new_image;
	}
}