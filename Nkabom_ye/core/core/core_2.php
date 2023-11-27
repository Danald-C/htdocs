<?php
	require_once("../core_1.php");
	// require_once("mixed_obj_funcs.php");
?>
<?php
	
	$action = @$_REQUEST['action'];
	$mode = @$_REQUEST['mode'];

	// $action = [["akdonyinah@gmail.com","0"],"2"]; // 0 by email; 1 by number
	// $action = [["+233558244996","0"],"2"]; //
	// $mode = "auth"; //
	
	$data = array();
	
	if(isset($mode)){
		if($mode == "auth"){
			processor_1($action);
			// $mode = "auth";
			
			/* try{
				echo divide(5, 0);
			}catch(Exception $ex){
				$code = $ex->getCode();
				$message = $ex->getMessage();
				$file = $ex->getFile();
				$line = $ex->getLine();
				
				echo "Exception thrown in $file on line $line: [Code $code] $message";
			}finally{
				echo "Process complete.";
			} */
		}
	}
	
	/* function divide($dividend, $divisor){
		if($divisor == 0){
			throw new Exception("You can't divide by zero.");
		}
		return $dividend / $divisor;
	} */
	function processor_1($action){
		global $objs;
		
		global $data;
		
		$d1_d1_file = array(3, "data_2", "data_1.json");
		$d1_d1 = $objs[2]->file_data("", $d1_d1_file, true);
		$new_d1_d1 = array();
		for($i=1; $i<count($d1_d1); $i++){
			$new_d1_d1[] = $d1_d1[$i];
		}
		
		// file_put_contents($objs[2]->get_dir.DS."debug.json", json_encode($action)); // Debug
			// print_r($objs[2]->get_dir);
		
		// $index_tree_set = $objs[2]->iterate_array($action[0]);
		// $new_data = $objs[2]->changeVals_inTree($action[0], $index_tree_set, 0);
		$objs[2]->filter_array($action[0], 0);
		$new_data = $action[0];
		
		$ret_opts = array();
		if($action[1] == 1){ // Sign Up
			$d1_d1[0][0] += 1;
			
			if($objs[1]->is_logged_in()){ // Terminate every active session
				$objs[1]->user_logout();
			}
			
			$objs[3]->room = $d1_d1[0][0];
			$objs[3]->type = 1; // 1: Admin; 2: S-U; 3; O-U 
			$objs[3]->date = date("Y-m-d"); // Date they became a user
			$ret_opts[0] = 0;
			if($objs[3]->create()){
				// $str =  preg_replace('/-+/+/', '', date("Y-m-d"));
				// $this_u = $objs[3]->find_by_id($objs[3]::$new_id, "id");
				$this_u = $objs[3]->find_by_id($objs[0][0]->new_id, "id");
				$login_ts = date("Y-m-d h:m");
				$access_code = preg_replace("/[-: ]/", "", $login_ts).$d1_d1[0][0].$this_u->id;
				$this_u->a_k = $access_code;
				if($this_u->update("id")){
					$u_log_file = "u_".$this_u->id.$d1_d1[0][0];
					$objs[2]->file_data(array(array($login_ts, "")), array(3, "data_2", $u_log_file.".json"), false);
					
					$new_data[0] = implode('', $objs[2]->enc_dec_str($new_data[0], true, 6, 3));
					$new_data[1] = implode('', $objs[2]->enc_dec_str($new_data[1], true, 6, 3));
					$new_data[] = ""; // Here for password
					$d1_d1[] = array(array(array($new_data, $this_u->id)), $d1_d1[0][0]);
					$d1_d1 = $objs[2]->file_data($d1_d1, $d1_d1_file, false);
					$objs[1]->page_access(0);
					
					$ret_opts[0] = $this_u->id;
					$ret_opts[1] = $this_u->a_k;
					// print_r($this_u->a_k);
				}
			}
		}
		
		if($action[1] == 2){ // Login
			if($objs[1]->is_logged_in()){ // Terminate every active session
				$objs[1]->user_logout();
			}
			for($i=1; $i<count($d1_d1); $i++){
				for($j=0; $j<count($d1_d1[$i][0]); $j++){
					$d1_d1[$i][0][$j][0][$new_data[1]] = implode('', $objs[2]->enc_dec_str($d1_d1[$i][0][$j][0][$new_data[1]], false, 6, 3)); // Decode before
					$item = $d1_d1[$i][0][$j][0][$new_data[1]];
					$d1_d1[$i][0][$j][0][$new_data[1]] = implode('', $objs[2]->enc_dec_str($d1_d1[$i][0][$j][0][$new_data[1]], true, 6, 3)); // Encode before
					if($item == $new_data[0]){
						$this_u = $objs[3]->find_by_id($d1_d1[$i][0][$j][1], "id");
						$login_ts = date("Y-m-d h:m");
						$access_code = preg_replace("/[-: ]/", "", $login_ts).$this_u->room.$this_u->id;
						
						$this_u->a_k = $access_code;
						$this_u->update("id"); // This takes effect when data aren't same
						
						$u_log_file = "u_".$this_u->id.$this_u->room;
						$u_log = $objs[2]->file_data("", array(3, "data_2", $u_log_file.".json"), true);
						array_unshift($u_log, array($login_ts, ""));
						$objs[2]->file_data($u_log, array(3, "data_2", $u_log_file.".json"), false);
						
						$d1_d1 = $objs[2]->file_data($d1_d1, $d1_d1_file, false);
						$objs[1]->page_access(0);
						
			// print_r($this_u->id.", ".$this_u->a_k);
						$ret_opts[0] = $this_u->id;
						$ret_opts[1] = $this_u->a_k;
					}
				}
			}
		}
		
		for($i=0; $i<count($new_d1_d1); $i++){
			for($j=0; $j<count($new_d1_d1[$i][0]); $j++){
				$new_d1_d1[$i][0][$j][0][0] = implode('', $objs[2]->enc_dec_str($new_d1_d1[$i][0][$j][0][0], false, 6, 3)); // Decode before
				$new_d1_d1[$i][0][$j][0][1] = implode('', $objs[2]->enc_dec_str($new_d1_d1[$i][0][$j][0][1], false, 6, 3)); // Decode before
			}
		}
		$data = [$new_d1_d1, [$action[1], $ret_opts]];
		// $new_session->message($new_core_6->combine_all_messages);
		// $history = $new_core_6->save_userHistory($content, $U->id);
	}
	
	$json = json_encode(array("data"=>$data, "mode"=>$mode));
	echo $json;
?>