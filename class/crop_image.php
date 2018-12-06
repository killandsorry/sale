<?
/* Crop Image Class */

class Crop_Image {

	var $source_image;
	var $new_image_name;
	var $save_to_folder;
	var $filename;
   var $error;

	function crop($x_pos = 0, $y_pos = 0, $width_crop = 230, $height_crop = 230){
		$info = GetImageSize($this->source_image);		
		$width = $info[0];
		$height = $info[1];
		$mime = $info['mime'];
		
		
		if($width < 230){
			$this->error = 'Ảnh của bạn quá nhỏ để làm ảnh bìa Album';
		} else {
		// What sort of image?
		
			$type = substr(strrchr($mime, '/'), 1);
		
		switch ($type){
			case 'jpeg':
			    $image_create_func = 'ImageCreateFromJPEG';
			    $image_save_func = 'ImageJPEG';
				$new_image_ext = 'jpg';
			    break;
			
			case 'png':
			    $image_create_func = 'ImageCreateFromPNG';
			    $image_save_func = 'ImagePNG';
				$new_image_ext = 'png';
			    break;
			
			case 'bmp':
			    $image_create_func = 'ImageCreateFromBMP';
			    $image_save_func = 'ImageBMP';
				$new_image_ext = 'bmp';
			    break;
			
			case 'gif':
			    $image_create_func = 'ImageCreateFromGIF';
			    $image_save_func = 'ImageGIF';
				$new_image_ext = 'gif';
			    break;
			default:
				$image_create_func = 'ImageCreateFromJPEG';
			    $image_save_func = 'ImageJPEG';
				$new_image_ext = 'jpg';
		}
		
  		//width và height ảnh 
		$new_width = $width_crop;
  		$new_height = $height_crop;
  		
  		
		$image = $image_create_func($this->source_image);
		
		$new_image = ImageCreateTrueColor($new_width, $new_height);
		
		// Crop to Square using the given dimensions
		ImageCopy($new_image, $image, 0, 0, $x_pos, $y_pos, $width, $height);
		
		if($this->save_to_folder)
				{
			       if($this->new_image_name)
			       {
			       	$new_name = $this->new_image_name;
			       }
			       else
			       {
			       	$new_name = $this->new_image_name( basename($this->source_image) ).'_boa.'.$new_image_ext;
			       }
				$this->filename =	 $new_name;
				$save_path = $this->save_to_folder.$new_name;
				}
				else
				{
				/* Show the image (on the fly) without saving it to a folder */
				   header("Content-Type: ".$mime);
		
			       $image_save_func($new_image);
		
				   $save_path = '';
				}
		
		// Save image 
		
		$process = $image_save_func($new_image, $save_path) or die("There was a problem in saving the new file.");
		
		return array('result' => $process, 'new_file_path' => $save_path, 'filename' => $this->new_image_name($this->source_image));
		}
	}
	
	function new_image_name($filename){
		$string = trim($filename);
		$arr_name		=	array();
		$arr_name		=	explode("/", $string);
		$name_image		=	end($arr_name);
		$name				=	explode(".", $name_image);
		$name_file		=	$name[0];
		
		return $name_file;
	}
}//end class
?>