<?php

/**
 * GD Image Driver.
 *
 * $Id: GD.php 3769 2008-12-15 00:48:56Z zombor $
 *
 * @package    Image
 * @author     Kohana Team
 * @copyright  (c) 2007-2008 Kohana Team
 * @license    http://kohanaphp.com/license.html
 */
class Image_GD_Driver extends Image_Driver {

	// A transparent PNG as a string
	protected static $blank_png;
	protected static $blank_png_width;
	protected static $blank_png_height;

	public function __construct()
	{
		// Make sure that GD2 is available
		if ( ! function_exists('gd_info'))
            throw new CException('image gd requires v2');

		// Get the GD information
		$info = gd_info();

		// Make sure that the GD2 is installed
		if (strpos($info['GD Version'], '2.') === FALSE)
			throw new CException('image gd requires v2');
	}

	public function process($image, $actions, $dir, $file, $render = FALSE)
	{
		// Set the "create" function
		switch ($image['type'])
		{
			case IMAGETYPE_JPEG:
				$create = 'imagecreatefromjpeg';
			break;
			case IMAGETYPE_GIF:
				$create = 'imagecreatefromgif';
			break;
			case IMAGETYPE_PNG:
				$create = 'imagecreatefrompng';
			break;
		}

		// Set the "save" function
        $save = 'imagejpeg';
		switch (strtolower(substr(strrchr($file, '.'), 1)))
		{
			case 'jpg':
			case 'jpeg':
				$save = 'imagejpeg';
			break;
			case 'gif':
				$save = 'imagegif';
			break;
			case 'png':
				$save = 'imagepng';
			break;
		}

		// Make sure the image type is supported for import
		if (empty($create) OR ! function_exists($create))
			throw new CException('image type not allowed');

		// Make sure the image type is supported for saving
		if (empty($save) OR ! function_exists($save))
			throw new CException('image type not allowed');

		// Load the image
		$this->image = $image;

		// Create the GD image resource
		$this->tmp_image = $create($image['file']);

		// Get the quality setting from the actions
		$quality = CArray::remove('quality', $actions);

		if ($status = $this->execute($actions))
		{
			// Prevent the alpha from being lost
			imagealphablending($this->tmp_image, TRUE);
			imagesavealpha($this->tmp_image, TRUE);

			switch ($save)
			{
				case 'imagejpeg':
					// Default the quality to 95
					($quality === NULL) and $quality = 95;
				break;
				case 'imagegif':
					// Remove the quality setting, GIF doesn't use it
					unset($quality);
				break;
				case 'imagepng':
					// Always use a compression level of 9 for PNGs. This does not
					// affect quality, it only increases the level of compression!
					$quality = 9;
				break;
			}

			if ($render === FALSE)
			{
				// Set the status to the save return value, saving with the quality requested
				$status = isset($quality) ? $save($this->tmp_image, $dir.$file, $quality) : $save($this->tmp_image, $dir.$file);
			}
			else
			{
                $cache_time = 3600*24;    //缓存时间：一天
                header("Pragma: public"); // required
                header("Cache-Control: max-age=".$cache_time);//24小时
                header('Last-Modified:'.gmdate('D, d M Y H:i:s').' GMT');
                header('Expires:'.gmdate('D, d M Y H:i:s', time() + $cache_time).' GMT');
                header("Cache-Control: private",false); // required for certain browsers
                header("Content-Transfer-Encoding: binary");

				// Output the image directly to the browser
				switch ($save)
				{
					case 'imagejpeg':
						header('Content-Type: image/jpeg');
					break;
					case 'imagegif':
						header('Content-Type: image/gif');
					break;
					case 'imagepng':
						header('Content-Type: image/png');
					break;
				}

				$status = isset($quality) ? $save($this->tmp_image, NULL, $quality) : $save($this->tmp_image);
			}

			// Destroy the temporary image
			imagedestroy($this->tmp_image);
		}

		return $status;
	}

    public function flip($direction)
	{
		// Get the current width and height
		$width = imagesx($this->tmp_image);
		$height = imagesy($this->tmp_image);

		// Create the flipped image
		$flipped = $this->imagecreatetransparent($width, $height);

		if ($direction === Image::HORIZONTAL)
		{
			for ($x = 0; $x < $width; $x++)
			{
				$status = imagecopy($flipped, $this->tmp_image, $x, 0, $width - $x - 1, 0, 1, $height);
			}
		}
		elseif ($direction === Image::VERTICAL)
		{
			for ($y = 0; $y < $height; $y++)
			{
				$status = imagecopy($flipped, $this->tmp_image, 0, $y, 0, $height - $y - 1, $width, 1);
			}
		}
		else
		{
			// Do nothing
			return TRUE;
		}

		if ($status === TRUE)
		{
			// Swap the new image for the old one
			imagedestroy($this->tmp_image);
			$this->tmp_image = $flipped;
		}

		return $status;
	}

	public function crop($properties)
	{
		// Sanitize the cropping settings
		$this->sanitize_geometry($properties);

		// Get the current width and height
		$width = imagesx($this->tmp_image);
		$height = imagesy($this->tmp_image);

		// Create the temporary image to copy to
		$img = $this->imagecreatetransparent($properties['width'], $properties['height']);

		// Execute the crop
		if ($status = imagecopyresampled($img, $this->tmp_image, 0, 0, $properties['left'], $properties['top'], $width, $height, $width, $height))
		{
			// Swap the new image for the old one
			imagedestroy($this->tmp_image);
			$this->tmp_image = $img;
		}

		return $status;
	}

	public function resize($properties)
	{
		// Get the current width and height
		$width = imagesx($this->tmp_image);
		$height = imagesy($this->tmp_image);

		if (substr($properties['width'], -1) === '%')
		{
			// Recalculate the percentage to a pixel size
			$properties['width'] = round($width * (substr($properties['width'], 0, -1) / 100));
		}

		if (substr($properties['height'], -1) === '%')
		{
			// Recalculate the percentage to a pixel size
			$properties['height'] = round($height * (substr($properties['height'], 0, -1) / 100));
		}
		
		// Recalculate the width and height, if they are missing
		empty($properties['width'])  and $properties['width']  = round($width * $properties['height'] / $height);
		empty($properties['height']) and $properties['height'] = round($height * $properties['width'] / $width);
		
		if ($properties['master'] === Image::AUTO)
		{
			// Change an automatic master dim to the correct type
			$properties['master'] = (($width / $properties['width']) > ($height / $properties['height'])) ? Image::WIDTH : Image::HEIGHT;
		}

		if (empty($properties['height']) OR $properties['master'] === Image::WIDTH)
		{
			// Recalculate the height based on the width
			$properties['height'] = round($height * $properties['width'] / $width);
		}

		if (empty($properties['width']) OR $properties['master'] === Image::HEIGHT)
		{
			// Recalculate the width based on the height
			$properties['width'] = round($width * $properties['height'] / $height);
		}

		// Test if we can do a resize without resampling to speed up the final resize
		if ($properties['width'] > $width / 2 AND $properties['height'] > $height / 2)
		{
			// Presize width and height
			$pre_width = $width;
			$pre_height = $height;

			// The maximum reduction is 10% greater than the final size
			$max_reduction_width  = round($properties['width']  * 1.1);
			$max_reduction_height = round($properties['height'] * 1.1);

			// Reduce the size using an O(2n) algorithm, until it reaches the maximum reduction
			while ($pre_width / 2 > $max_reduction_width AND $pre_height / 2 > $max_reduction_height)
			{
				$pre_width /= 2;
				$pre_height /= 2;
			}

			// Create the temporary image to copy to
			$img = $this->imagecreatetransparent($pre_width, $pre_height);

			if ($status = imagecopyresized($img, $this->tmp_image, 0, 0, 0, 0, $pre_width, $pre_height, $width, $height))
			{
				// Swap the new image for the old one
				imagedestroy($this->tmp_image);
				$this->tmp_image = $img;
			}

			// Set the width and height to the presize
			$width  = $pre_width;
			$height = $pre_height;
		}

		// Create the temporary image to copy to
		$img = $this->imagecreatetransparent($properties['width'], $properties['height']);

		// Execute the resize
		if ($status = imagecopyresampled($img, $this->tmp_image, 0, 0, 0, 0, $properties['width'], $properties['height'], $width, $height))
		{
			// Swap the new image for the old one
			imagedestroy($this->tmp_image);
			$this->tmp_image = $img;
		}

		return $status;
	}

    public function rotate($amount)
	{
		// Use current image to rotate
		$img = $this->tmp_image;

		// White, with an alpha of 0
		$transparent = imagecolorallocatealpha($img, 255, 255, 255, 127);

		// Rotate, setting the transparent color
		$img = imagerotate($img, 360 - $amount, $transparent, -1);

		// Fill the background with the transparent "color"
		imagecolortransparent($img, $transparent);

		// Merge the images
		if ($status = imagecopymerge($this->tmp_image, $img, 0, 0, 0, 0, imagesx($this->tmp_image), imagesy($this->tmp_image), 100))
		{
			// Prevent the alpha from being lost
			imagealphablending($img, TRUE);
			imagesavealpha($img, TRUE);

			// Swap the new image for the old one
			imagedestroy($this->tmp_image);
			$this->tmp_image = $img;
		}

		return $status;
	}

	public function watermark($wm)
	{
        $is_url=$wm['is_url'];
        $wm_str=$wm['wm_str'];
        $wm_width=intval($wm['wm_width']);
		$width = imagesx($this->tmp_image);
		$height = imagesy($this->tmp_image);

        $water_db=1;

        if($is_url){
            if(!Tools::is_url($wm_str)){
                return false;
            }
            $watermark = imagecreatefrompng($wm_str);
            $ww = imagesx($watermark);
            $wh = imagesy($watermark);
        }else{
            $ww=($wm_width>0) ? $wm_width : 180;
            $wh=60;
            $font = Yii::getPathOfAlias(
                'application.extensions.image.font'
            ).DIRECTORY_SEPARATOR.'segoepr.ttf';
            // Create the image
            $watermark = imagecreatetruecolor($ww*$water_db, $wh*$water_db);

            // Create some colors
            $grey = imagecolorallocate($watermark, 128, 128, 128);      //透明背景色
            $red = imagecolorallocate($watermark, 255, 0, 0);           //文字背景
            $yellow = imagecolorallocate($watermark, 255, 255, 0);      //文字颜色
            $blue = imagecolorallocate($watermark, 0, 0, 255);
            $green = imagecolorallocate($watermark, 0, 128, 0);
            $white = imagecolorallocate($watermark, 255, 255, 255);
            $black = imagecolorallocate($watermark, 0, 0, 0);
            $color_select=2;
            $color=array($grey, $red, $yellow, $blue, $green,$white);

            imagefilledrectangle($watermark, 0, 0, $ww, $wh, $grey);    //创建画布
            // Add some shadow to the text
//            imagettftext($watermark, 12, 10, 10, 47, $red, $font, $wm_str);
            // Add the text

            imagettftext($watermark, 16*$water_db, 9, 12*$water_db, 45*$water_db, $color[$color_select], $font, $wm_str);
            imagecolortransparent($watermark,$grey);                    //透明化

//            $watermark_new=imagecreatetruecolor($ww, $wh);
//            imagecopyresampled($watermark_new, $watermark, 0, 0, 0, 0, $ww, $wh, $ww*$water_db, $wh*$water_db);
//            imagecolortransparent($watermark_new,$black);
        }

        if(empty($watermark)){
            return false;
        }

		// Merge the images
        imagealphablending($this->tmp_image, TRUE);
        imagesavealpha($this->tmp_image, TRUE);
        imagealphablending($watermark, TRUE);
        imagesavealpha($watermark, TRUE);

        return imagecopymerge($this->tmp_image, $watermark, $width-$ww-10, $height-$wh-5, 0, 0, $ww, $wh, 100);

	}

	public function sharpen($amount)
	{
		// Make sure that the sharpening function is available
		if ( ! function_exists('imageconvolution'))
			throw new CException('image unsupported method');

		// Amount should be in the range of 18-10
		$amount = round(abs(-18 + ($amount * 0.08)), 2);

		// Gaussian blur matrix
		$matrix = array
		(
			array(-1,   -1,    -1),
			array(-1, $amount, -1),
			array(-1,   -1,    -1),
		);

		// Perform the sharpen
		return imageconvolution($this->tmp_image, $matrix, $amount - 8, 0);
	}

	protected function properties()
	{
		return array(imagesx($this->tmp_image), imagesy($this->tmp_image));
	}

	/**
	 * Returns an image with a transparent background. Used for rotating to
	 * prevent unfilled backgrounds.
	 *
	 * @param   integer  image width
	 * @param   integer  image height
	 * @return  resource
	 */
	protected function imagecreatetransparent($width, $height)
	{
		if (self::$blank_png === NULL)
		{
			// Decode the blank PNG if it has not been done already
			self::$blank_png = imagecreatefromstring(base64_decode
			(
				'iVBORw0KGgoAAAANSUhEUgAAACgAAAAoCAYAAACM/rhtAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29'.
				'mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAADqSURBVHjaYvz//z/DYAYAAcTEMMgBQAANegcCBN'.
				'CgdyBAAA16BwIE0KB3IEAADXoHAgTQoHcgQAANegcCBNCgdyBAAA16BwIE0KB3IEAADXoHAgTQoHcgQ'.
				'AANegcCBNCgdyBAAA16BwIE0KB3IEAADXoHAgTQoHcgQAANegcCBNCgdyBAAA16BwIE0KB3IEAADXoH'.
				'AgTQoHcgQAANegcCBNCgdyBAAA16BwIE0KB3IEAADXoHAgTQoHcgQAANegcCBNCgdyBAAA16BwIE0KB'.
				'3IEAADXoHAgTQoHcgQAANegcCBNCgdyBAgAEAMpcDTTQWJVEAAAAASUVORK5CYII='
			));

			// Set the blank PNG width and height
			self::$blank_png_width = imagesx(self::$blank_png);
			self::$blank_png_height = imagesy(self::$blank_png);
		}

		$img = imagecreatetruecolor($width, $height);

		// Resize the blank image
		imagecopyresized($img, self::$blank_png, 0, 0, 0, 0, $width, $height, self::$blank_png_width, self::$blank_png_height);

		// Prevent the alpha from being lost
		imagealphablending($img, FALSE);
		imagesavealpha($img, TRUE);

		return $img;
	}

} // End Image GD Driver