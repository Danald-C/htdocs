<?php
	# COMMUNITY ENVIRONMENT
	
	if (!$objs[1]->page_status($_GET['p_f']) && !$objs[1]->page_status($_GET['p_t'])) {
	// if ($objs[1]->page_status() == $_GET['page']) {
		$objs[1]->page_access_all();
		// $objs[1]->page_access($_GET["p_f"]);
		redirect_to("page_0.php?sm=true");
	}
	
	$this_org = $objs[4]->find_by_id($objs[1]->org_id[0], "id");
	
	$currDB_obj = $objs[0][$objs[1]->org_id[1]];
	$tbs_obj = db_obj_tbls($currDB_obj);
	
	$d1_d1_file = array(3, "data_2", "data_1.json");
	$d1_d1 = $objs[2]->file_data("", $d1_d1_file, true);
	$new_d1_d1 = array();
	for($i=1; $i<count($d1_d1); $i++){
		$new_d1_d1[] = $d1_d1[$i];
	}
	
	if(isset($_GET["req"])){
		if($_GET["req"] == 0){
			// $objs[1]->page_unset($_GET['p_f']);
			// if($_GET['p_t'] > 0){
				$objs[1]->page_access_all();
				$objs[1]->page_access($_GET["p_t"]);
			// }
			
			$get_reqs = array("sm=true");
			foreach($_GET as $key => $val){
				if($key != 'req'){
					$get_reqs[] = $key."=".$val;
				}
				if($key == 'm-d'){ // Mem data request
					$get_reqs[] = "req=1";
				}
			}
			// print_r($get_reqs);
			
			if($_GET['p_t'] == 0){
				$objs[1]->curr_org();
			}
			
			$nav = form_url("page_{$_GET['p_t']}.php", $get_reqs);
			redirect_to($nav);
		}
		
		if($_GET["req"] == 1){
			if($_GET['m-d'] < 2){
				if($_GET['m-d'] == 1){
					$d_s = $_GET["d-s"];
					// $mem_id = $_GET["on"];
					$mem_rec_0 = $tbs_obj[0]->find_by_id($_GET["on"], "id");
					$mem_rec_1 = $tbs_obj[1]->find_by_id($mem_rec_0->id, "m_id");
					$mem_rec_2 = $tbs_obj[2]->find_by_id($mem_rec_0->id, "m_id");
					
					$mem_rec_5 = $tbs_obj[5]->find_by_id($mem_rec_0->id, "m_id");
					$mem_rec_7 = $tbs_obj[7]->find_by_id($mem_rec_0->id, "m_id");
					
					$new_mem_data = $objs[1]->tmp_data;
					$objs[1]->tmp_data();
					if(count($_POST) > 0){
						$new_mem_data = $_POST;
					}
					
					if($d_s == 2){
						$fields = array("f_name", "o_name", "l_name", "gender", "dob", "nationality", "marital_status");
						$key_sets = array(array(0, 1, 2), array(3, 4, 5, 6));
						foreach($fields as $key => $field){
							// echo $new_mem_data[$field]."<br />";
							if(!empty($new_mem_data[$field])){
								if(in_array($key, $key_sets[0])){
									$mem_rec_1->{$field} = $new_mem_data[$field];
									$mem_rec_1->update("id");
								}else{
									$mem_rec_0->{$field} = $new_mem_data[$field];
									$mem_rec_0->update("id");
								}
							}
						}
						
						/* $f_name = $new_mem_data['f_name'];
						$o_name = $new_mem_data['o_name'];
						$l_name = $new_mem_data['l_name'];
						echo "Your name is ".$f_name." ".$o_name." ".$l_name; */
					}
					if($d_s == 3){
						$fields = array("nation", "state_province", "city", "h_address", "occupation", "contact", "email");
						$key_sets = array(array(0, 1, 2, 3), array(4), array(5), array(6));
						print_r($new_mem_data);
						
						foreach($fields as $key => $field){
							// echo $new_mem_data[$field]."<br />";
							if(!empty($new_mem_data[$field])){
								if(in_array($key, $key_sets[0])){
									$mem_rec_5->{$field} = $new_mem_data[$field];
									$mem_rec_5->update("id");
								}elseif(in_array($key, $key_sets[1])){
									$mem_rec_0->{$field} = $new_mem_data[$field];
									$mem_rec_0->update("id");
								}elseif(in_array($key, $key_sets[2])){
									$mem_conts = $tbs_obj[3]->findMultiple_by_id($mem_rec_0->id, "m_id");
									$cont_id = array($mem_rec_0->id, "m_id");
									while($rec = $currDB_obj->get_selectedResults($mem_conts)){
										if($rec['active'] == 1){
											$cont_id = array($rec['id'], "id");
										}
									}
									$tbs_obj[3]->update_multiple(array("m_id", $mem_rec_0->id), array("active", 0));
									$mem_rec_3 = $tbs_obj[3]->find_by_id($cont_id[0], $cont_id[1]);
									
									$mem_rec_3->{$field} = $new_mem_data[$field];
									$mem_rec_3->active = 1;
									$mem_rec_3->update("id");
								}else{
									$mem_email = $tbs_obj[4]->findMultiple_by_id($mem_rec_0->id, "m_id");
									$email_id = array($mem_rec_0->id, "m_id");
									while($rec = $currDB_obj->get_selectedResults($mem_email)){
										if($rec['active'] == 1){
											$email_id = array($rec['id'], "id");
										}
									}
									$tbs_obj[4]->update_multiple(array("m_id", $mem_rec_0->id), array("active", 0));
									$mem_rec_4 = $tbs_obj[4]->find_by_id($email_id[0], $email_id[1]);
									
									$mem_rec_4->{$field} = $new_mem_data[$field];
									$mem_rec_4->active = 1;
									$mem_rec_4->update("id");
								}
							}
						}
									// echo "Contact did we come here?".$mem_rec_3->active;
									// echo "Email did we come here?".$mem_rec_4->active;
					}
					if($d_s == 4){
						$fields = array("dedi_date", "dedi_by", "bapt_date", "bapt_by");
						foreach($fields as $key => $field){
							if(!empty($new_mem_data[$field])){
								// echo $new_mem_data[$field]."<br />";
								$mem_rec_2->{$field} = $new_mem_data[$field];
							}
						}
						$mem_rec_2->update("id");
					}
					
					$save_rec_7 = 0;
					if($d_s == 5){
					// echo "Data Status: ".$new_mem_data['auto_off'];
						$mem_rec_7->auto_off = ($new_mem_data['auto_off'] == "on") ? 1 : 0;
						$save_rec_7 = 1;
					}
					if($d_s > $mem_rec_7->data_progress){
						$mem_rec_7->data_progress = $d_s;
						$save_rec_7 = 1;
					}
					if($save_rec_7 == 1){
						$mem_rec_7->update("id");
					}
					
					$mem_id = get_mem_id($mem_rec_0->id, 0, $tbs_obj, 1);
					$mem_data = mem_data_toFile($mem_id[0], $tbs_obj);
				}/* else{ */ // New
				if($_GET['m-d'] == 0){
					$mem_move = array(array(), array(array(1, 0), 1));
					$mem_id = new_mem($tbs_obj, $currDB_obj, $mem_move);
					
					$objs[1]->tmp_data(1, $_POST);
				}
				
				$get_reqs = array();
				if($_GET['d-s'] < 5){
					foreach($_GET as $key => $val){
						if($key == 'm-d' && $val == 0){ // When it's member data request
								$get_reqs[] = $key."=1";
							if($val == 0){ // New
								// $get_reqs[] = "req=1";
							}/* else{ // Update
								$get_reqs[] = $key."=".$val;
							} */
						}elseif($key == 'req'){
							if($_GET['m-d'] == 0){
								$get_reqs[] = $key."=".$val;
							}
						}elseif($key == 'on'){
							if($_GET['m-d'] == 0){ // In the new
								$get_reqs[] = $key."=".$mem_id[0];
							}else{ // Update
								$get_reqs[] = $key."=".$val;
							}
						}else{
							// if($_GET['m-d'] == 1 && $key != 'req'){
								$get_reqs[] = $key."=".$val;
							// }
						}
					}
				}else{
					$get_reqs = array("p_f={$_GET['p_f']}", "p_t={$_GET['p_t']}", "m-d=3", "on={$mem_rec_0->id}", "sm=true");
				}
				$nav = form_url($_SERVER['PHP_SELF'], $get_reqs);
				// print_r($nav);
				
				// echo "<a href='{$nav}'>Proceed</a>";
				redirect_to($nav);
			}
			// echo "<h3>{$mem_rec_0->f_id}</h3>";
		}
		
		if($_GET["req"] == 2){
			echo "<h2>Okay, let's go..</h2>";
			$file = $_FILES['img'];
			// print_r($file);
			
			$request = array("p_f={$_GET['p_f']}", "p_t={$_GET['p_t']}", "m-d=4", "on={$_GET['on']}");
			if($objs[2]->validate_image($file)){
				$mem_rec_8 = $tbs_obj[8]->find_by_id($_GET["on"], "m_id");
				$objs[2]->remove_img($mem_rec_8);
				$objs[2]->save_img($mem_rec_8, $file);
				
				$request[] = "stage=1";
				$nav_3 = form_url($_SERVER['PHP_SELF'], $request);
				redirect_to($nav_3);
			}else{
				$request[] = "stage=0";
				$request[] = "sm=true";
				$nav_3 = form_url($_SERVER['PHP_SELF'], $request);
				redirect_to($nav_3);
			}
		}
	}
	
	
	/* # MEMBERS FILE DEBUGGER
	// Member file data
	$mem_data_file = array(1, array(org_data($objs[1]->org_id[0])[1], "data_1"), "data_1.json"); // Org directory
	$mem_data = $objs[2]->file_data("", $mem_data_file, true);
	
	$query = $tbs_obj[0]->find_all();
	print_r($mem_data);
			echo "<br />";
			echo "<br />";
	while($rec = $currDB_obj->get_selectedResults($query)){
		$comp_data = comp_mem_file_data($rec['id'], $tbs_obj);
		for($i=0; $i<count($mem_data); $i++){
			if($rec['f_id'] == $mem_data[$i][1] && $rec['id'] == $mem_data[$i][0]["id"]){
				$mem_data[$i][0] = $comp_data;
			}
		}
	}
	print_r($mem_data);
	// $mem_data = $objs[2]->file_data($mem_data, $mem_data_file, false); */
	
	/* # LEVEL-GROUPS FILE DEBUGGER
	$lvls_data_file = array(1, array(org_data($objs[1]->org_id[0])[1], "data_1", "data_1"), "data_1.json");
	$lvls_data = $objs[2]->file_data("", $lvls_data_file, true);
	for($i=1; $i<count($lvls_data); $i++){
		$this_lvl = $lvls_data[$i];
		$lvl_id = $this_lvl[2];
		$lvl_date = $this_lvl[1];
		
		# Grp
		$lvl_data_file = get_lvl_path(org_data($objs[1]->org_id[0])[1], $lvl_id, $lvl_date, "");
		$lvl_data = $objs[2]->file_data("", $lvl_data_file, true);
		for($j=0; $j<count($lvl_data); $j++){
			print_r($lvl_data[$j][1]);
				echo "<br />";
			// array_splice($lvl_data[$j][1], 1, 0, array(array(0)));
			$lvl_data[$j][1][1] = array($lvl_data[$j][1][1]);
			print_r($lvl_data[$j][1]);
				echo "<br />";
				echo "<br />";
		}
		// $lvl_data = $objs[2]->file_data($lvl_data, $lvl_data_file, false);
	} */
	$all_mems = $tbs_obj[0]->find_all();
	while($rec = $currDB_obj->get_selectedResults($all_mems)){
		// $tbs_obj[8]->m_id = $rec['id'];
		// $tbs_obj[8]->state = 0;
		// $tbs_obj[8]->create();
		// print_r($tbs_obj[8]);
		// echo "<br />";
		// echo "<br />";
		// $mem_data = mem_data_toFile($rec['id'], $tbs_obj);
	}
	
	
	
	
	
	
	
	
	/* $cookie_name = "m-l-s-1-0"; // Member List State
	$cookie_value = 1;
	// setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 is the number of seconds a day. 86400 = 1 day
	if(!isset($_COOKIE[$cookie_name])) {
		echo "Cookie named '" . $cookie_name . "' is not set!";
		
		echo "<br />";
	} else {
		echo "Cookie '" . $cookie_name . "' is set!<br>";
		echo "Value is: " . $_COOKIE[$cookie_name];
		
		// Remove
		setcookie($cookie_name, "", time() - 3600, "/");
		unset($_COOKIE[$cookie_name]);
	}
	foreach($_COOKIE as $key => $value){ // Remove mass
		if($key !== "PHPSESSID"){
		echo $key.", ".$value;
		echo "<br />";
		// unset($value);
		// setcookie($key, '', time() - 3600, "/");
		}
	}
	// mems_disp_state(0, array(0), 1);
	// mems_disp_state(0, array(1, 1, 2), 1);
	// print_r(mems_disp_state(0, array(0), 0)); */
	
	
	$get_data = $tbs_obj[0]->find_all();
	if($currDB_obj->get_numOFrows($get_data) > 0){
		while($rec = $currDB_obj->get_selectedResults($get_data)){
			// print_r($rec);
			// echo "<br />";
		}
		// echo "<br />";
			// print_r("Are we here?..");
		$mem_id = get_mem_id($objs[1]->u_id, $objs[1]->org_id[0], $tbs_obj, 0);
		// print_r(get_mem_data(array($mem_id[0], $tbs_obj), 9));
		
		$mem_rec_2 = $tbs_obj[2]->find_by_id($mem_id[0], "m_id");
	}else{
		// When members table has no members at all.
		// print_r($tbs_obj[0]);
		$mem_move = array(array(), array(array(1, 0), 1));
		$mem_id = new_mem($tbs_obj, $currDB_obj, $mem_move);
		if($mem_id[0] > 0){ // If new member did not fail..
			for($i=0; $i<count($new_d1_d1); $i++){
				if($new_d1_d1[$i][1] == $this_org->u_room){ // On U room
					for($j=0; $j<count($new_d1_d1[$i][0]); $j++){
						if($new_d1_d1[$i][0][$j][1] == $objs[1]->u_id){ // On U id
							$email = implode('', $objs[2]->enc_dec_str($new_d1_d1[$i][0][$j][0][0], false, 6, 3)); // Decode before;
							$contact = implode('', $objs[2]->enc_dec_str($new_d1_d1[$i][0][$j][0][1], false, 6, 3)); // Decode before;
							
							// Contact
							$mem_rec_3 = $tbs_obj[3]->find_by_id($mem_id[0], "id");
							// $tbs_obj[3]->m_id = $mem_id;
							$mem_rec_3->contact = $contact;
							$mem_rec_3->active = 1;
							$mem_rec_3->update("id");
							
							// Email
							$mem_rec_4 = $tbs_obj[4]->find_by_id($mem_id[0], "id");
							// $tbs_obj[4]->m_id = $mem_id;
							$mem_rec_4->email = $email;
							$mem_rec_4->active = 1;
							$mem_rec_4->update("id");
							
							$objs[1]->page_access_all();
							$objs[1]->page_access(2);
							$mem_rec_7 = $tbs_obj[7]->find_by_id($mem_id[0], "m_id");
							// m-d: member data 1'update', 0'new'; d-s: data state
							redirect_to("page_2.php?p_f=1&p_t=2&m-d=1&d-s={$mem_rec_7->data_progress}&on={$mem_id[0]}&sm=true");
						}
					}
				}
			}
		}
	}
?>