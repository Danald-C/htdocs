<?php
	/*	*** THESE ONLY CONTAINS MANY DIRRERENT KINDS OF OBJECTS ***	*/
	// ***************************************************** //
	class core_5{
		
		// 
		public $get_dir;
		
		
		
		
		##	&&&&&&&&&&&&&&&	IMAGES CONTROLS	&&&&&&&&&&&&&&&&&&&&&&&&	##
		##	&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&	##
		private function img_mimeTypes(){
			return array('image/pjpeg' => "jpg", 'image/jpeg' => "jpg", 'image/jpg' => "jpg", 'image/png' => "png", 'image/x-png' => "png", 'image/gif' => "gif");
		}
		
		private function get_extension($file){
			$full_name = basename($file['name']);
			$get_ext = strrpos($full_name, '.') + 1; // Position/Number of what come after.
			$get_ext = substr($full_name, $get_ext); // Find & return that small part
			$extension = strtolower($get_ext);
			
			return $extension;
		}
		
		public function validate_image($file){
			global $objs;
			
			$valid = false;
			$err_type = array(1, 0);
			$img_validate = array(1, 1);
			foreach ($this->img_mimeTypes() as $mime_type => $ext) {
				if($file['type'] == $mime_type && $this->get_extension($file) == $ext){
					// $valid = true;
					// $err_type[0] = 0;
					$img_validate[0] = 0;
				}
			}
			
			if(count(str_split($file['size'])) >= 5){
				$img_validate[1] = 0;
			}
			
			if(!in_array(1, $img_validate)){
				$objs[1]->messages("Your selected image is valid.");
			}else{
				if($img_validate[0] == 1){
					$objs[1]->messages("Sorry, files with ".implode(' ', $this->valid_exts())." extensions are accepted for upload.");
				}
				if($img_validate[1] == 1){
					$objs[1]->messages($this->all_messages(array(1, 2)));
				}
				$objs[1]->messages($this->all_messages(array(1, 7)));
			}
			
			return (!in_array(1, $img_validate)) ? true : false;
		}
		
		private function valid_exts(){
			$image_ext = array_unique($this->img_mimeTypes()); // Cutout all duplicates
			$exts = array();
			foreach ($image_ext as $mime_type => $ext) {
				$exts[] = strtoupper($ext);
			}
			
			return $exts;
		}
		
		private function get_imgName($img_ids){
			$name = "";
			for($i=0; $i<count($img_ids); $i++){
				$name .= $img_ids[$i]."_";
			}
			
			return array($name."l_name", $name."m_name", $name."s_name");
		}
		
		# $call_type: 0 System; 1 Member
		public function remove_img($data_rec, $call_type=1){
			global $objs;
			
			if($call_type == 1){
				$this->request_dir(array(1, array(org_data($objs[1]->org_id[0])[1], "data_8"), ""));
				
				$imgs = array(false, false, false);
				if(!empty($data_rec->l_name)){
				// echo $this->get_dir;
					if(!empty($data_rec->l_name) && file_exists($this->get_dir.DS.$data_rec->l_name)){
						$imgs[0] = true;
						chmod($this->get_dir.DS.$data_rec->l_name, 0777);
						unlink($this->get_dir.DS.$data_rec->l_name);
					}
					if(!empty($data_rec->m_name) && file_exists($this->get_dir.DS.$data_rec->m_name)){
						$imgs[1] = true;
						chmod($this->get_dir.DS.$data_rec->m_name, 0777);
						unlink($this->get_dir.DS.$data_rec->m_name);
					}
					if(!empty($data_rec->s_name) && file_exists($this->get_dir.DS.$data_rec->s_name)){
						$imgs[2] = true;
						chmod($this->get_dir.DS.$data_rec->s_name, 0777);
						unlink($this->get_dir.DS.$data_rec->s_name);
					}
					
					$data_rec->l_name = "";
					$data_rec->m_name = "";
					$data_rec->s_name = "";
					$data_rec->state = 0;
					$data_rec->update("id");
				}
				
			}
		}
		
		public function save_img($data_rec, $file, $call_type=1){
			global $objs;
			
			$tmp_data = $this->attach_file($file, $call_type);
			
			$mem_img_name = $this->get_imgName(array(1, 0, $data_rec->m_id));
			
			for($i=0; $i<count($mem_img_name); $i++){
				$mem_img_name[$i] .= ".".$this->get_extension($file);
			}
			print_r($tmp_data);
			
			$l_name = $this->get_dir.DS.$mem_img_name[0];
			$m_name = $this->get_dir.DS.$mem_img_name[1];
			$s_name = $this->get_dir.DS.$mem_img_name[2];
			
			if(empty($tmp_data[0])){
				$objs[1]->messages($this->all_messages(array(1, 4)));
				return false;
			}
			
			if(move_uploaded_file($tmp_data[0], $l_name)){
				$this->do_copy_f($l_name, $m_name);
				$this->do_copy_f($l_name, $s_name);
				$this->correctImgOrientation($l_name);
				$data_rec->state = 1;
				
				if($call_type == 1){
					$data_rec->l_name = $mem_img_name[0];
					$data_rec->m_name = $mem_img_name[1];
					$data_rec->s_name = $mem_img_name[2];
					$data_rec->update("id");
					
			// print_r($data_rec->l_name.", ".$data_rec->m_name.", ".$data_rec->s_name);
					$objs[1]->messages($this->all_messages(array(1, 0)));
				}
				$objs[1]->messages("Saving image has completed successfully.");
			}else{
				$objs[1]->messages($this->all_messages(array(1, 6)));
			}
		}
		
		private function attach_file($file, $call_type){
			global $objs;
			
			$tmp_path = array();
			if(!$file || empty($file) || !is_array($file)){
				$objs[1]->messages($this->all_messages(array(1, 4)));
			}elseif($file['error'] != 0){
				$err_globs = array(UPLOAD_ERR_OK, UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE, UPLOAD_ERR_PARTIAL, UPLOAD_ERR_NO_FILE, UPLOAD_ERR_NO_TMP_DIR, UPLOAD_ERR_CANT_WRITE, UPLOAD_ERR_EXTENSION);
				foreach($err_globs as $key => $val){
					if($val === $file['error']){
						$objs[1]->messages($this->all_messages(array(1, $key)));
					}
				}
			}else{
				$tmp_path[0] = $file["tmp_name"];
				if($call_type == 1){
					// $img_rec->$field = basename($file["name"]);
					$tmp_path[1] = $file["name"];
				}
			}
			
			return $tmp_path;
		}
		
		// Check & restore the orientation.
		protected function correctImgOrientation($filename){
			if(function_exists('exif_read_data')){
				$exif = exif_read_data($filename);
				if($exif && isset($exif['Orientation'])){
					$orientation = $exif['Orientation'];
					if($orientation != 1){
						$img = imagecreatefromjpeg($filename);
						chmod($img, 0777);
						$degrees = 0;
						switch($orientation){
							case 3:
								$degrees = 180;
								break;
							case 6:
								$degrees = 270;
								break;
							case 8:
								$degrees = 90;
								break;
						}
						if($degrees){
							$img = imagerotate($img, $degrees, 0);
						}
						imagejpeg($img, $filename, 95);
					}
				}
			}
		}
		
		# $size: [0] Width; [1] Height
		public function get_imgSize($img){
			//print_r($this->large_image_location);
			$size = getimagesize($img);
			
			return $size;
		}
		
		/* private function scaleLarge_img($img){
			// Divide to get the small size.
			$scale;
			if($this->get_imgSize($img)[0] > 400){
				$scale = 400 / $this->get_imgSize($img)[0];
			}else{
				$scale = 1;
			}
			return $scale;
		} */
		
		private function resize_image($imgs, $medium, $small, $scale){
			
			// Get the different props of the image
			list($imagewidth, $imageheight, $imageType) = getimagesize($imgs[2]);
			$imageType = image_type_to_mime_type($imageType);
			
			$newImageWidth_1 = ceil($medium[4] * $scale[0]);
			$newImageHeight_1 = ceil($medium[5] * $scale[0]);
			$newImage_1 = imagecreatetruecolor($newImageWidth_1, $newImageHeight_1);
			
			$newImageWidth_2 = ceil($small[4] * $scale[1]);
			$newImageHeight_2 = ceil($small[5] * $scale[1]);
			$newImage_2 = imagecreatetruecolor($newImageWidth_2, $newImageHeight_2);
					
			switch($imageType) {
				case "image/gif":
					$source=imagecreatefromgif($imgs[0]); 
					break;
				case "image/pjpeg":
				case "image/jpeg":
				case "image/jpg":
					$source=imagecreatefromjpeg($imgs[0]); 
					break;
				case "image/png":
				case "image/x-png":
					$source=imagecreatefrompng($imgs[0]); 
					break;
			}
			
			# Arg 1: newSize
			# Arg 2: newImage
			# Arg 3, 4, 5, 6: X1, Y1, X2, Y2
			# Arg 7: newWidth
			# Arg 8: newHeight
			# Arg 9: originalWidth
			# Arg 10: originalHeight
			// imagecopyresampled($newImage,$source,0,0,$start_width,$start_height,$newImageWidth,$newImageHeight,$width,$height);
			imagecopyresampled($newImage_1, $source, 0, 0, $medium[0], $medium[1], $newImageWidth_1, $newImageHeight_1, $medium[4], $medium[5]);
			imagecopyresampled($newImage_2, $source, 0, 0, $small[0], $small[1], $newImageWidth_2, $newImageHeight_2, $small[4], $small[5]);
			
			// print_r($newImage, true);
			chmod($imgs[1], 0777);
			chmod($imgs[2], 0777);
			switch($imageType) {
				case "image/gif":
					imagegif($newImage_1, $imgs[1]); 
					imagegif($newImage_2, $imgs[2]); 
					break;
				case "image/pjpeg":
				case "image/jpeg":
				case "image/jpg":
					imagejpeg($newImage_1, $imgs[1],90); 
					imagejpeg($newImage_2, $imgs[2],90); 
					break;
				case "image/png":
				case "image/x-png":
					imagepng($newImage_1, $imgs[1]);  
					imagepng($newImage_2, $imgs[2]);  
					break;
			}
		}
		
		public function crop_img($imgs, $medium, $small){
			$this->resize_image($imgs, $medium, $small, array($this->scale_img(array($medium[4], $medium[5]), 1, $imgs[1]), $this->scale_img(array($small[4], $small[4]))));
		}
		
		public function scale_img($selectionWidth_val, $type=0, $img=""){
			// $size = ($type == 1) ? 300 : 200;
			$size = ($type == 1) ? 350 : 200;
			$scale = $size / $selectionWidth_val[0];
			
			return $scale;
		}
		##	&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&	##
		##	&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&	##
		
		
		
		public function all_messages($type){
			$messages = array();
			$messages[0] = array();
			$messages[1] = array("image successfully uploaded.", "Your file is larger than the maximum size.", "The size of your file is not allowed.", "could not save your picture. Please try again or report this.", "No file was specified or the file location was not available.", "Temporary directory cannot be found.", "Could not save your new picture. Possibly due to permissions on it. Please try again or report this.", "You selected an invalid image file. A valid image file is required.");
			
			return (count($type) > 1) ? $messages[$type[0]][$type[1]] : $messages[$type[0]];
		}
		
		// Contents read from files comes with \n & others, remove all
		public function filterFile_contents($content){
			return trim(preg_replace('/\s\s+/', '', $content));
		}
		
		public function val_exist_1($val, $arr, $n=0){
			if($n < count($arr)){
				if($val == $arr[$n]){
					return array(1, $n);
				}else{
					return $this->val_exist_1($val, $arr, $n+=1);
				}
			}else{
				return array(0, 0);
			}
		}
		
		# $new_arr must be like this: array("name" => "value")
		public function splice_assoc_array_insert($old_arr, $new_arr, $offset){
			return array_slice($old_arr, 0, $offset, true) + $new_arr + array_slice($old_arr, $offset, NULL, true);
		}
		
		# $new_arr must be like this: array("name" => "value")
		public function splice_assoc_array($old_arr, $offset, $new_arr, $state=true, $length=0){
			$keys = array_keys($old_arr);
			$values = array_values($old_arr);
			
			if($state){
				array_splice($keys, $offset, $length, array_keys($new_arr));
				array_splice($values, $offset, $length, array_values($new_arr));
			}else{
				array_splice($keys, $offset, $length);
				array_splice($values, $offset, $length);
			}
			
			return array_combine($keys, $values);
		}
		
		# Return the index of an element that falls in-between an array()
		public function inbetween_index($n, $val, $arr=array()){
			if(!is_array($arr) || count($arr) == 0){
				return 0;
			}else{
				if($arr[$n] > $val){
					return $n;
				}else{
					$n += 1;
					//return $this->inbetween_index($n+=1, $val, $reg_ids);
					return ($n == count($arr)) ? $n : $this->inbetween_index($n, $val, $arr);
				}
			}
		}
		
		// private function enc_dec($old_data, $mode=true, $interval=3){
		public function enc_dec($old_data, $mode=true, $interval=3){
			if(!is_array($old_data)){
				$old_data = str_split($old_data);
			}
			// $any_char = preg_match('/[!@#$%^&*(),.+=_\[\]\';\/{}|":<>?`\\\\]/', $data[$n]);
			$capped_alph = range('A', 'Z');
			$uncapped_alph = range('a', 'z');
			$numbers = range(0, 9);
			# ONLY APPEND TO THIS. DO NOT CHANGE.
			$spec_chars = array("~", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "_", "-", "+", "=", "{", "[", "}", "]", "|", "'", "\"", ",", ":", ";", "?", "/", ">", ".", "<", "\\", " ");
			$replacement = array($capped_alph, $uncapped_alph, $numbers, $spec_chars);
			
			$new_data = array();
			foreach($old_data as $key => $data){
				$get_type_1 = preg_match_all('/[A-Z]/', $data, $matches, PREG_OFFSET_CAPTURE);
				$get_type_2 = preg_match('/[!@#$%^&*()+=_\[\]\';\/{}|":<>?`~\\ ,|.-]/', $data);
				if($get_type_1 > 0){
				// if(count($matches[0]) > 0){
					// echo "Cap: ".$data;
					$arr_set = $replacement[0];
				}elseif(is_numeric($data)){
					// echo "Num: ".$data;
					$arr_set = $replacement[2];
				}elseif($get_type_2 > 0){
					// echo "Sp: ".$data;
					$arr_set = $replacement[3];
				}else{
					// echo "Uncap: ".$data;
					$arr_set = $replacement[1];
				}
				// echo ", ";
				$get_index = $this->val_exist_1($data, $arr_set);
				
				$new_index = $get_index[1]+$interval;
				if(!$mode){
					$new_index = $get_index[1]-$interval;
					if($get_index[1] < $interval){ // If index is under Interval
						// Subtract Index from Interval to get remainder & offset it minus 1 because of 0 based indexing.
						// Subtract the remainder from the whole array length to get final index.
						$new_index = (count($arr_set)-1) - (($interval-$get_index[1])-1);
					}
				}
				if($get_index[0] == 1){
					if($new_index > count($arr_set)-1){
						$excess = $new_index - (count($arr_set)-1);
						$new_data[] = $arr_set[$excess-1];
						$place = $excess-1;
						$curr_item = $arr_set[$excess-1];
						// echo "=> 1";
					}else{
						$new_data[] = $arr_set[$new_index];
						$place = $new_index;
						$curr_item = $arr_set[$new_index];
						// echo "=> 2";
					}
				}
				// print_r("Interval: ".$interval.", Place 1: ".$get_index[1]." Place 2: ".$place.", From: ".$data.", To: ".$curr_item);
				// echo "<br />";
			}
			
			return $new_data;
		}
		
		public function enc_dec_str($str, $mode=true, $interval=3, $level=1){
			for($i=0; $i<$level; $i++){
				// Returns an array always
				$str = $this->enc_dec($str, $mode, $interval);
				// print_r($str);
				// echo "<br />";
			}
			
			return $str;
		}
		
		# Arg 1: Array data
		# Arg 2: true 'all elements that match' / false 'all elements that do not match'
		# Arg 3: true 'do not collect duplicate elements' / false 'collect duplicate elements'
		# Arg 3 (optional): Container for collection
		# Arg 3 (optional): Where to start from
		// public function compare_contents($merged, $matched=true, $matched_elem=array(), $n=array(1, 0)){
		public function compare_contents($merged, $matched=true, $duplicate=true, $matched_elem=array(), $n=array(0, 1)){
			/* if($n[0] < count($merged)){
				if($matched){
					if($merged[$n[1]] == $merged[$n[0]]){
						$matched_elem[] = $merged[$n[0]];
					}
				}else{
					if($merged[$n[1]] != $merged[$n[0]]){
						$matched_elem[] = $merged[$n[0]];
					}
				}
				$n[1] += 1;
				if($n[1] == $n[0]){
					$n[1] = 0;
					$n[0] += 1;
				}
				return $this->compare_contents($merged, $matched, $matched_elem, $n);
			}else{
				return $matched_elem;
			} */
			
			if(count($merged) == 2){
				if($matched){
					if($merged[$n[0]] == $merged[$n[1]]){
						$merged = (!$duplicate) ? $merged[$n[0]] : $merged;
					}else{
						$merged = array();
					}
				}else{
					$merged = ($merged[$n[0]] != $merged[$n[1]]) ? $merged : array();
				}
				return $merged;
			}elseif(count($merged) == 1){
				return $merged;
			}
			
			if($n[0]+1 < count($merged)){
				if($matched){
					if($merged[$n[0]] == $merged[$n[1]]){
						if(!$duplicate){
							if(!in_array($merged[$n[0]], $matched_elem)){
								$matched_elem[] = $merged[$n[0]];
							}
							if(!in_array($merged[$n[1]], $matched_elem)){
								$matched_elem[] = $merged[$n[1]];
							}
						}else{
							$matched_elem[] = $merged[$n[0]];
							$matched_elem[] = $merged[$n[1]];
						}
					}
				}else{
					if($merged[$n[0]] != $merged[$n[1]]){
						if(!$duplicate){
							if(!in_array($merged[$n[0]], $matched_elem)){
								$matched_elem[] = $merged[$n[0]];
							}
							if(!in_array($merged[$n[1]], $matched_elem)){
								$matched_elem[] = $merged[$n[1]];
							}
						}else{
							$matched_elem[] = $merged[$n[0]];
							$matched_elem[] = $merged[$n[1]];
						}
					}
				}
				
				$n[1] += 1;
				if($n[1] == count($merged)){
					$n[0] += 1;
					$n[1] = $n[0]+1;
				}
				return $this->compare_contents($merged, $matched, $duplicate, $matched_elem, $n);
			}else{
				// print_r($matched_elem);
				return $matched_elem;
			}
		}
		
		public function ageFrom_dob($dob){
			// 31556926 is the number of seconds in a year
			$age = floor((date(time())-strtotime($dob)) / 31556926);
			return $age;
		}
		
		// Params take Unix-TimeStamp
		# Returns the precise difference in array(). Can be used to check age.
		public function date_difference($first, $second){
			$diff = abs(date($first) - date($second));
			
			$years = floor($diff / (365*60*60*24));
			$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
			$days = floor(($diff - $years * 365*60*60*24 - $months * 30*60*60*24) / (60*60*24));
			$hours = floor(($diff - $years * 365*60*60*24 - $months * 30*60*60*24 - $days * 60*60*24) / (60*60));
			$minutes = floor(($diff - $years * 365*60*60*24 - $months * 30*60*60*24 - $days * 60*60*24 - $hours * 60*60) / 60);
			$seconds = floor(($diff - $years * 365*60*60*24 - $months * 30*60*60*24 - $days * 60*60*24 - $hours * 60*60 - $minutes*60));
			
			$date_diff = array($years, $months, $days, $hours, $minutes, $seconds);
			
			return $date_diff;
		}
		
		// Takes actual date eg. 2011-04-22
		public function count_down_in_month($date){
			$dob_str = $this->dateTo_string($date);
			
            $d_m_1 = date("m", strtotime($date));
            $d_m_2 = date("m", time());
            $d_d_1 = date("d", strtotime($date))+1;
            $d_d_2 = date("d", time())+1;
		
			$prev = array();
			//$curr = array();
			$next = array();
			$bd_month = 0;
			$pointer = 0;
			$age = $this->ageFrom_dob($date);
			
			$blanc_date = false;
			if(date("Y", strtotime($date)) == "0000" || date("Y", strtotime($date)) < 0){
				$blanc_date = true;
			}
			if(!$blanc_date){
				if($d_m_1 == $d_m_2){
					$bd_month = 1;
					
					$yesterday = $d_d_1+1;
					$tomorrow = $d_d_2+1;
					$d_coming = $d_d_1-$d_d_2;
					$d_passed = $d_d_2-$d_d_1;
					$bd_state = $this->negative_positive($d_coming);
					
					if($bd_state == -1){
						$pointer = 1;
						if($yesterday == $d_d_2){
							$prev[0] = 1; // Just a day before was birthday
							$prev[1] = 0;
						}else{
							$d_passed = $d_passed-1;
							$prev[0] = 0;
							$prev[1] = $d_passed; // Days ago was birthday
						}
					}
					if($bd_state == 0){
						$pointer = 2; // Today is the birthday
					}
					if($bd_state == 1){
						$pointer = 3;
						if($tomorrow == $d_d_1){
							$next[0] = 1; // Just a day to birthday
							$next[1] = 0; // Count-Down is over
						}else{
							$d_coming = $d_coming-1;
							$next[0] = 0;
							$next[1] = $d_coming; // Count-Down to birthday
						}
					}
				}
			}
			
			return array(array($bd_month, $pointer), $prev, 0, $next, array($dob_str[1], $age));
		}
		
		public function dateTo_string($date_string){
			$new_yr_1 = date("Y", date(time()));
			$new_yr_2 = date("Y", strtotime($date_string));
			$month_1 = date("m", strtotime($date_string));
			$day_1 = date("d", strtotime($date_string));
			
			$day_2 = $this->date_supScript($day_1);
			
			$m_and_d_1 = $new_yr_1."-".$month_1."-".$day_1;
			$m_and_d_2 = $new_yr_2."-".$month_1."-".$day_1;
			$str_d_1 = date("l F d, Y", strtotime($m_and_d_2));
			$str_d_2 = date("l F d, Y", strtotime($m_and_d_1));
			
			$new_str_date_1 = substr($str_d_1, 0, (strlen($str_d_1)-8));
			$new_str_date_1 .= $day_2;
			$new_str_date_1 .= substr($str_d_1, (strlen($str_d_1)-6));
			$new_str_date_2 = substr($str_d_2, 0, (strlen($str_d_2)-8));
			$new_str_date_2 .= $day_2;
			$new_str_date_2 .= substr($str_d_2, (strlen($str_d_2)-6));
			
			// l 'day name', F 'month name', d 'date' & Y 'year'
			$this_string = array();
			//$this_string[] = date("l F d, Y", strtotime($date_string));
			$this_string[] = $new_str_date_1;
			$this_string[] = $new_str_date_2;
			
			return $this_string;
		}
		
		public function date_supScript($day){
			$rw_num = $day*1;
			$supScript = $rw_num."<sup>th</sup>";
			if($rw_num == 1 || $rw_num == 21 || $rw_num == 31){
				$supScript = $rw_num."<sup>st</sup>";
			}
			if($rw_num == 2 || $rw_num == 22){
				$supScript = $rw_num."<sup>nd</sup>";
			}
			if($rw_num == 3 || $rw_num == 23){
				$supScript = $rw_num."<sup>rd</sup>";
			}
			
			return $supScript;
		}
		
		// Check whether a number is Nagative or Positive
		public function negative_positive($number){
			// Every number in the negative is represented by -1
			return ($number > 0) ? 1 : (($number < 0) ? -1 : 0);
		}
		
		public function filter_array(&$data, $action=0){
			if(is_array($data) && count($data) == 1 && empty($data[0]) && $action == 0){
				$data = array();
			}
			if(count($data) == 0 && $action == 1){
				$data = array("");
			}
			if(!is_array($data) && $action == 2){
				$data = "";
			}
			
			for($i=0; $i<count($data); $i++){
				if(is_array($data[$i])){
					// echo "Look here..";
					if(count($data[$i]) == 1 && empty($data[$i][0]) && $action == 0){
						$data[$i] = array();
					}elseif(count($data[$i]) == 0 && $action == 1){
						$data[$i] = array("");
					}else{
						// $data[$i][] = "David";
						$this->filter_array($data[$i]);
					}
				}else{
					if($action == 2){
						$data[$i] = "David";
					}
				}
			}
		}
		
		//	$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$	//
		//	$$$$$$$$$$$$ ITERATE AN ARRAY AND CHANGE DATA ON LEVELS	$$$$$$$$$$$$	//
		# Param 1: $sel_data is data 'must be an array'
		# Param 2: $keys is keys 'array of indexes to iterate data'
		# Param 3: $action is action to perform 'must be 0/1/2'. 0 return empty arrays as array(). 1 return empty arrays as array(""). 2 return empty strings "".
		public function changeVals_inTree($arr_data, $i_tree, $action=-1, $n=0){
			if($n < count($i_tree)){
				// print_r($i_tree[$n]);
				if((count($arr_data) == 1 && empty($arr_data[0])) || count($arr_data) == 0 || empty($arr_data[0])){
					if($action == 2){
						$arr_data[] = "";
					}
					
					// Turn array("") to array(). $data_type[1] == 1 means array("")
					if($action == 0){
						$arr_data[] = array();
					}
					
					// Turn array() to array(""). $data_type[1] == 0 means array()
					if($action == 1){
						$arr_data[] = array("");
					}
					
					// return $arr_data;
				}else{
					$sel_level = $this->get_lvl_data($arr_data, $i_tree[$n]);
					$data_type = $this->type_of_arrVal($sel_level[1]);
					// Data is an array
					if($data_type[0] == 0){ // No child; empty data or a String
						$do_action = 0;
						if($action == 2 && $data_type[1] == 2){
							$new_val = "David";
							$do_action = 1;
						}
						
						// echo "Did we come here?..".$action.", ".$data_type[1].", ".$n;
						// Turn array("") to array(). $data_type[1] == 1 means array("")
						if($action == 0 && $data_type[1] == 1){
							$new_val = array();
							$do_action = 1;
						}
						
						// Turn array() to array(""). $data_type[1] == 0 means array()
						if($action == 1 && $data_type[1] == 0){
							$new_val = array("");
							$do_action = 1;
						}
						
						if($do_action == 1){
							$arr_data = $this->assign_newVal_toTree($arr_data, $i_tree[$n], $new_val);
						}
					}
				}
					
					$n += 1;
					return $this->changeVals_inTree($arr_data, $i_tree, $action, $n);
			}else{
				return $arr_data;
			}
		}
		
		public function assign_newVal_toTree($arr_data, $i_tree, $new_val){
			if(count($i_tree) > 0){
				$last_i = count($i_tree)-1;
				$last_val = $i_tree[$last_i];
				
				array_splice($i_tree, $last_i, 1);
				$sel_level = $this->get_lvl_data($arr_data, $i_tree);
				
				$sel_level[1][$last_val] = $new_val;
				$new_val = $sel_level[1];
				
				return $this->assign_newVal_toTree($arr_data, $i_tree, $new_val);
			}else{
				return $new_val;
			}
		}
		//	$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$	//
		//	$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$	//
		
		//	&&&&&&&&&&&&&&&&&&&&&	TOUCH LAST VALS IN ARRAY	&&&&&&&&&&&&&&&&&&&&&	//
		// Returns index tree set of array
		public function iterate_array($arr_data, $i_tree=array(array(), array(0))){
			$data_type = $this->type_of_arrVal($arr_data);
			if(!is_array($arr_data) || $data_type[0] == 0){
				// print_r("This function expects an array.");
				// exit;
				
				return array(array(0));
			}
			
			if(count($i_tree[1]) > 0){
				$sel_level = $this->get_lvl_data($arr_data, $i_tree[1]);
				$data_type = $this->type_of_arrVal($sel_level[1]);
				
				if($data_type[0] == 1){ // Is an array with many children
					$i_tree[1][] = 0;
				}else{ // Is a String ""/"Some string", array(), array("")
					$i_tree[0][] = $i_tree[1];
					$i_tree[1] = $this->stepback($arr_data, $i_tree[1]);
				}
				
				return $this->iterate_array($arr_data, $i_tree);
			}else{
				return $i_tree[0];
			}
		}
		
		public function get_lvl_data($arr_data, $i_tree, $n=0){
			if($n < count($i_tree)){
				$curr_i = $i_tree[$n];
				
				if(count($arr_data) > $curr_i){
				// print_r($arr_data);
				// echo "Now we're here..".$curr_i;
					return $this->get_lvl_data($arr_data[$curr_i], $i_tree, $n+=1);
				}else{
					return array(0, $arr_data);
				}
			}else{
				return array(1, $arr_data);
			}
		}
		
		public function stepback($arr_data, $i_tree){
			$last_val = count($i_tree)-1;
			$i_tree[$last_val] += 1;
			$sel_level = $this->get_lvl_data($arr_data, $i_tree);
			if($sel_level[0] == 0){
				array_splice($i_tree, $last_val, 1);
				
				if(count($i_tree) > 0){
					return $this->stepback($arr_data, $i_tree);
				}else{
					return $i_tree;
				}
			}else{
				return $i_tree;
			}
		}
		// Type of value array()/array("")/"string"
		// Returns array(0/1, 0/1/2)
		public function type_of_arrVal($data){
			# $return_data[0]: 0/1 last descendant / more descendants
			# $return_data[1]:
			# 0 = $data is an array & completely empty
			# 1 = $data is an array & contains an empty string ""
			# 2 = $data is not an array but a STRING
			$return_data = array(0, 0);
			if(is_array($data)){ // Data is an array
				$condition_1 = (count($data) == 0) ? true : false;
				$condition_2 = (count($data) == 1 && $data[0] == "") ? true : false;
				
				if($condition_1 || $condition_2){ // No child; Empty Arrays
					$type = 0; // Completely empty array()
					if($condition_2){
						$type = 1; // Contains an empty string array("")
					}
					$return_data[1] = $type;
				}else{ // Children; $data contains more descendants
					$return_data[0] = 1;
				}
			}else{ // Data is a string
				$return_data[1] = 2;
			}
			
			return $return_data;
		}
		//	&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&	//
		//	&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&	//
		
		// %%%%%%%%%%%	TO REMOVE OR COPY AN ENTIRE DIRECTORY	%%%%%%%%%%% //
		# $dirs: [0] 'from folder'; [1] 'to folder';
		public function rem_copy_dir_ingine($dirs, $mode=1){
			if($mode == 0){
				if(empty($dirs[0])){ // Safe guard. Will start from Root if empty
					return false;
				}
				// METHOD 1
				/* if(!is_dir($dirs[0])){
					throw new InvalidArgumentException($dirs[0]." must be a directory");
				}
				if(substr($dirs[0], strlen($dirs[0])-1, 1) != DS){
					$dirs[0] .= DS;
				}
				$files = glob($dirs[0].'*', GLOB_MARK);
				foreach($files as $file){
					if(is_dir($file)){
						$this->rem_copy_dir_ingine(array($file, $file), $mode);
					}else{
						unlink($file);
					}
				}
				rmdir($dirs[0]);
				
				
				// METHOD 2
				return is_file($dirs[0]) ? @unlink($dirs[0]) : array_map(__FUNCTINO__, glob($dirs[0].DS.'*'));

				
				// METHOD 4
				$files = array_diff(scandir($dirs[0]), array('.', '..'));
				foreach($files as $file){
					(is_dir($dirs[0].DS.$file)) ? $this->rem_copy_dir_ingine(array($$dirs[0].DS.$file, ""), $mode) : unlink($dirs[0].DS.$file);
				}
				return rmdir($dirs[0]);
				*/
				
				
				// METHOD 4
				$iterate = new RecursiveDirectoryIterator($dirs[0], RecursiveDirectoryIterator::SKIP_DOTS);
				$files = new RecursiveIteratorIterator($iterate, RecursiveIteratorIterator::CHILD_FIRST);
				foreach($files as $file){
					if($file->isDir()){
						rmdir($file->getRealPath());
					}else{
						unlink($file->getRealPath());
					}
				}
				rmdir($dirs[0]);
			}
			
			if($mode == 1){
				foreach(scandir($dirs[0]) as $key => $file){
					if('.' === $file || '..' === $file)
						continue;
					
					if(is_dir($dirs[0].DS.$file)){
						if(!is_dir($dirs[1].DS.$file)){
							mkdir($dirs[1].DS.$file, 0700, true);
						}
						$this->rem_copy_dir_ingine(array($dirs[0].DS.$file, $dirs[1].DS.$file), $mode);
					}else{
						if(!file_exists($dirs[1].DS.$file)){
							$this->do_copy_f($dirs[0].DS.$file, $dirs[1].DS.$file);
						}
					}
				}
			}
		}
		//	%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%	//
		
		public function compose_name($id, $time, $type){
			$this_name = $id;
			$this_name .= str_replace("-", "", $time);
			$this_name .= $type;
			
			return $this_name;
		}
		
		
		public function file_data($get_content, $directory, $mode){
			if($mode){
				$file_content = $this->open_file($directory);
			}else{
				$this->save_file($get_content, $directory);
				$file_content = $this->file_data("", $directory, true);
			}
			
			return $file_content;
		}
		
		private function check_path($directory, $dir_type=true){
			// [0]: Direction; [1]: Path; [2]: Filename
			// True: Path is an array; False: Path is a string
			$logfile = ($dir_type) ? $this->get_dir($directory[0], $directory[1]) : $directory[1];
			$logfile .= DS.$directory[2];
			if(file_exists($logfile) || is_dir($logfile)){
				chmod($logfile, 0700);
			}
			
			return $logfile;
		}
		
		public function save_file($content, $directory, $dir_type=true){
			/* if($dir_type){
				$logfile = $this->get_dir($directory[0], $directory[1]);
				$logfile .= DS.$directory[2];
			}else{
				$logfile = $directory[1].DS.$directory[2];
			} */
			$logfile = $this->check_path($directory, $dir_type);
			
			$new_line = "";
			if(is_array($content)){
				foreach($content as $key => $cont){
					$new_line .= json_encode($cont)."\n"; // The newline \n disqualifies this as a complete json data
					// $new_line .= $cont."\n"; // The newline \n disqualifies this as a complete json data
				}
			}else{
				$new_line .= json_encode($content)."\n"; // The newline \n disqualifies this as a complete json data
			}
			
			file_put_contents($logfile, $new_line);
		}
		
		public function open_file($directory, $dir_type=true){
			/* $logfile = ($dir_type) ? $this->get_dir($directory[0], $directory[1]) : $directory[1];
			$logfile .= DS.$directory[2]; */
			$logfile = $this->check_path($directory, $dir_type);
			
			$content = array();
			if(file_exists($logfile) && is_readable($logfile) && $handle = fopen($logfile, 'r')){ // read
				while(!feof($handle)){
					// Get every information line by line
					$entry = fgets($handle);
					
					// Is there a new line empty with nothing?
					// Then clear it
					if(trim($entry) != ""){
						$content[] = json_decode($this->filterFile_contents($entry), true); // Take off newline before decode
					}
				}
				fclose($handle);
			}else{
				$content[] = "Cannot read from or find {$logfile}.";
			}
			return $content;
		}
		
		public function request_dir($directory){
			// [0]: Direction; [1]: Path
			$this->get_dir($directory[0], $directory[1]);
		}
		
		private function get_dir($direction_i, $directory){
			// If array, must not be empty. Else if string, must not be empty.
			$sub_d = (is_array($directory) && count($directory) >= 1 || !empty($directory)) ? $this->get_subDirs($directory) : "";
			
			$direction = array("core", "domains", "home", "logs");
			// $dir = $this->root_path();
			$dir = $this->root_path($direction[$direction_i]);
			if(!empty($sub_d)){
				$dir .= $sub_d;
			}
			
			$this->get_dir = $dir;
			
			return $dir;
		}
		
		public function fileExists($direction_i, $directory, $file_name){
			$logfile = (is_array($directory) && count($directory) >= 1 || !empty($directory)) ? $this->get_dir($direction_i, $directory) : $directory;
			$logfile .= DS.$file_name;
			
			return file_exists($logfile) ? true : false;
		}
		
		public function do_copy_f($from, $to){
			if(file_exists($from)){
				chmod($from, 0700);
				if(file_exists($to)){
					chmod($to, 0700);
				}
				if(copy($from, $to)){
					chmod($from, 0400);
					chmod($to, 0400);
				}
			}
		}
		
		// public function root_path(){
		private function root_path($direction){
			// return ROOT_PATH.DS.'logs'.DS;
			return ROOT_PATH.DS.$direction.DS;
		}
		
		private function get_subDirs($directory){
			// $directory can be an array from parent to grand-children
			
			$comp_path = "";
			if(is_array($directory)){
				$folder_num = count($directory);
				for($i=0; $i<$folder_num; $i++){
					if($i != $folder_num-1){
						$comp_path .= $directory[$i].DS;
					}else{
						$comp_path .= $directory[$i];
					}
				}
			}else{
				$comp_path = $directory;
			}
			
			return $comp_path;
		}
	}
	$new_core_5	= new core_5();
	// ***************************************************** //
?>