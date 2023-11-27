<?php
	function redirect_to($location = NULL){
		if($location != NULL){
			header("Location: {$location}");
			exit;
		}
	}
	
	function get_root_path($ds="", $rp=""){ // $ds=Directory Separator, $rp=Root Path
		// If not defined, param variables must not be empty
		if(defined('LIBRARY_PATH') && defined('DS')){
			$ds = DS;
			$rp = LIBRARY_PATH;
		}
		//$find_path = explode(DS, LIBRARY_PATH);
		$find_path = explode($ds, $rp);
		$index = 0;
		$root_path = array();
		$root_name = "";
		for($i=0; $i<count($find_path); $i++){
			$root_path[] = $find_path[$i];
			if($i == $index && $i > 0){
				break;
			}
			if($find_path[$i] == "htdocs"){
				$index = $i+1;
				$root_name = $find_path[$index];
			}
		}
		//$root_path = implode(DS, $root_path);
		$root_path = implode($ds, $root_path);
		
		return array($root_path, $root_name);
	}
		
	function get_db_data($dbname="", $num=0, $action=0){
		$ds = (defined('DS')) ? DS : DIRECTORY_SEPARATOR;
		$r_arr = (defined('ROOT_PATH') && defined('ROOT_NAME')) ? array(ROOT_PATH, ROOT_NAME) : get_root_path($ds, dirname(__FILE__));
		
		$log_child = $r_arr[0].$ds.'logs'.$ds."data_1";
		$file_name = "data_{$num}.json";
		$file_path = $log_child.$ds.$file_name;
		
		// print_r($action);
		if($action == 1){
			$save = 0;
			if(!file_exists($file_path)){
				$arr_data = array(array("localhost", "root", "", ($num == 0) ? strtolower("nkb_y_main") : $dbname), 1);
				if($num == 0){
					$arr_data[0][] = 1;
					$arr_data[0][] = "danald";
					$arr_data[0][] = "moonlight4orange";
					$arr_data[0][] = "kwadjodanq@gmail.com";
					$arr_data[0][] = "+233558244996";
				}
				// $content = array(array(array("localhost", "root", "", strtolower("nkb_y_main"), 1, "danald", "moonlight4orange", "kwadjodanq@gmail.com", "+233558244996"), 0));
				// $content = json_encode($content);
				$arr_data[1] = 1;
				$arr_data = array($arr_data);
				$save = 1;
			}else{
				$arr_data = open_file($log_child, $file_name);
				if(!$num == 0){
					$arr_data[] = array(array("localhost", "root", "", $dbname), count($arr_data)+1);
					$save = 1;
				}
			}
			if($save == 1){
				save_file($arr_data, $log_child, $file_name);
			}
			$arr_data = open_file($log_child, $file_name);
		}else{
			$arr_data = (file_exists($file_path)) ? open_file($log_child, $file_name) : array();
		}
		
		// $db_data = json_decode($db_data[0], true);
		
		return $arr_data;
	}
	
	function open_file($dir, $filename){
		$content = array();
		
		$full_dir = $dir.DS.$filename;
		if(file_exists($full_dir) && is_readable($full_dir) && $handle = fopen($full_dir, "r")){ // read
			// While we're not equal to the end of the file
			while(!feof($handle)){
				// Get every information line by line
				$entry = fgets($handle);
				
				// Is there a new line empty with nothing?
				// Then clear it
				if(trim($entry) != ""){
					// $content[] = trim(preg_replace('/\s\s+/', '', $entry));
					$content[] = json_decode(preg_replace('/\s\s+/', '', $entry), true); // Take off newline before decode
				}
			}
			fclose($handle);
		}else{
			$content[] = "Cannot read from or find {$logfile}.";
		}
		
		return $content;
	}
	
	function save_file($content, $dir, $filename){
		$full_dir = $dir.DS.$filename;
		if(file_exists($full_dir) || is_dir($full_dir)){
			chmod($full_dir, 0700);
		}
		
		/* if($handle = fopen($full_dir, "w")){ // Mode of action
			$each_line = "";
			if(is_array($content)){
				foreach($content as $key => $cont){
					$each_line .= $cont."\n";
				}
			}else{
				$each_line .= $content."\n";
			}
			fwrite($handle, $each_line);
			fclose($handle);
			if(file_exists($full_dir) || is_dir($full_dir)){
				chmod($full_dir, 0400);
			}
		} */
		
		
		$new_line = "";
		if(is_array($content)){
			foreach($content as $key => $cont){
				$new_line .= json_encode($cont)."\n"; // The newline \n disqualifies this as a complete json data
			}
		}else{
			$new_line .= json_encode($content)."\n"; // The newline \n disqualifies this as a complete json data
		}
		
		file_put_contents($full_dir, $new_line);
	}
	
?>