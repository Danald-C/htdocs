<?php
	
	function form_url($url, $req){
		$new_url = $url;
		$new_url .= "?";
		if(is_array($req)){
			for($i=0; $i<count($req); $i++){
				$new_url .= $req[$i];
				if($i != count($req)-1){
					$new_url .= "&";
				}
			}
		}else{
			$new_url .= $req;
		}
		return $new_url;
	}
	
	function set_up(){
		global $objs;
		
		// DEV
		$all_u = $objs[3]->find_all();
		if($objs[0][0]->get_numOFrows($all_u) == 0){
			// $sql = "INSERT INTO `u_grps` (`id`, `pw`, `date`, `type`, `room`, `a_k`) VALUES (NULL, '".sha1($get_data[6])."', '1988-11-14', '0', '0', '0')";
			// $new_core_4->all_Queries($sql);
			
			// Add a Room
			$d1_d1_file = array(3, "data_2", "data_1.json");
			$d1_d1 = $objs[2]->file_data("", $d1_d1_file, true);
			$d1_d1[0][0] += 1;
			
			$objs[3]->pw = sha1($get_data[0][6]);
			$objs[3]->room = $d1_d1[0][0];
			$objs[3]->type = 0; // 1: Admin; 2: S-U; 3; O-U 
			$objs[3]->date = "1988-11-14"; // Date they became a user
			if($objs[3]->create()){
				$new_data = array($get_data[0][7], $get_data[0][8], $get_data[0][6]);
				// $str =  preg_replace('/-+/+/', '', date("Y-m-d"));
				$this_u = $objs[3]->find_by_id($objs[3]::$new_id, "id");
				
				$new_data[0] = implode('', $objs[2]->enc_dec_str($new_data[0], true, 6, 3));
				$new_data[1] = implode('', $objs[2]->enc_dec_str($new_data[1], true, 6, 3));
				$new_data[2] = implode('', $objs[2]->enc_dec_str($new_data[2], true, 6, 3));
				$d1_d1[] = array(array(array($new_data, $this_u->id)), $d1_d1[0][0]);
				$d1_d1 = $objs[2]->file_data($d1_d1, $d1_d1_file, false);
			}
		}
	}
	
	function install_tb_struct(){
		global $objs;
		
		$entry = "";
		$objs[2]->request_dir(array(3, "data_1", ""));
		$lines = file($objs[2]->get_dir.DS."skeleton.sql");
		foreach($lines as $line){
			if(substr($line, 0, 2) == '--' || $line == ''){
				continue;
			}
			$entry .= $line;
			if(substr(trim($line), -1, 1) == ';'){
				$objs[0][count($objs[0])-1]->all_Queries($entry);
				$entry = '';
			}
		}
	}
	
	function u_prefs($data=array()){
		global $objs;
		
		$db_name = org_data($objs[1]->org_id[0])[1];
		$mem_data_file = array(1, $db_name, "u_prefs.json");
		
		$u_prefs = array();
		if(!$objs[2]->fileExists(1, $db_name, "u_prefs.json")){
			// Logged In U. Usually, admin
			$u_prefs = array($objs[1]->u_id, array());
			$u_prefs[1][] = array(0, array(array(1, 1), array(1, 1, 1, 1), array(1, 1, 1))); // Display State
			$u_prefs[1][] = array(0, array(array(), "", 0)); // Tmp Attendance
			$mem_data = $objs[2]->file_data(array($u_prefs), $mem_data_file, false);
		}else{
			$mem_data = $objs[2]->file_data("", $mem_data_file, true);
			$u_found = array(array(), 0);
			for($i=0; $i<count($mem_data); $i++){
				if($mem_data[$i][0] == $objs[1]->u_id){
					$u_prefs = $mem_data[$i];
					$u_found[0][] = 1;
					$u_found[1] = $i;
				}else{
					$u_found[0][] = 0;
				}
			}
			if(!in_array(1, $u_found[0])){
				$u_prefs = array($objs[1]->u_id, array());
				$u_prefs[1][] = array(0, array(array(1, 1), array(1, 1, 1, 1), array(1, 1, 1)));
				$u_prefs[1][] = array(0, array(array(), "", 0));
				$mem_data[] = $u_prefs;
				$mem_data = $objs[2]->file_data($mem_data, $mem_data_file, false);
			}else{
				// print_r($u_found);
				if(count($data) > 0 && $data[0] > 0 && $data[0] == $mem_data[$u_found[1]][0]){ // $data[0]: U id
					$mem_data[$u_found[1]][1] = $data[1];
					$u_prefs = $mem_data[$u_found[1]];
					$mem_data = $objs[2]->file_data($mem_data, $mem_data_file, false);
				}
			}
		}
		
		return $u_prefs;
	}
	
	function currDB_obj($org_id){
		global $objs;
		
		$this_org = $objs[4]->find_by_id($org_id, "id");
		$data = get_db_data("", $this_org->u_room);
		$currDB_obj = 0;
		for($i=1; $i<count($objs[0]); $i++){
			$org_name_1 = $objs[0][$i]->get_data[0][3];
			$org_cnt_1 = $objs[0][$i]->get_data[1];
		// print_r($this_org->ref_db.", ".$org_name_1.", ".$this_org->u_room.", ".$org_cnt_1." ");
			/* if($this_org->ref_db == $org_name_1){
					$currDB_obj = $i;
			} */
			// print_r("Sub: ".$org_name_1);
			for($j=0; $j<count($data); $j++){
				$org_name_2 = $data[$j][0][3];
				$org_cnt_2 = $data[$j][1];
				// if($org_name_1 == $org_name_2 && $org_cnt_1 == $org_cnt_2){
				if($this_org->ref_db == $org_name_1 && $org_name_1 == $org_name_2 && $org_cnt_1 == $org_cnt_2){
			// echo "<br />";
					$currDB_obj = $i;
				}
			}
		}
		
		return $currDB_obj;
	}
	
	function db_obj_tbls($obj){
		// $all_tables = array("members", "mem_extra_1", "mem_extra_2", "mem_extra_3", "mem_extra_4", "mem_extra_5", "images");
		$each_tb_obj = array();
		
		for($i=1; $i<10; $i++){
			// $each_tb_obj[] = new core_10($obj, $all_tables[$i]);
			$each_tb_obj[] = new core_10($obj, "data_{$i}");
		}
		
		return $each_tb_obj;
	}
	
	function new_mem($tbs_obj, $currDB_obj, $mem_move){
		global $objs;
		
		// Id, Index
		$mem_id = array(0, 0);
		$date = date("Y-m-d");
		
		$first_u = 0;
		if($tbs_obj[0]->create()){
			$mem_id[0] = $currDB_obj->new_id;
			
			// $first_u = who_is_this($tbs_obj, $mem_id[0]);
			// if($first_u[0] == 1){
				$sel_orgs = $objs[5]->findMultiple_by_id($objs[1]->org_id[0], "org_id");
				while($rec = $currDB_obj->get_selectedResults($sel_orgs)){
					if($rec['u_id'] == $objs[1]->u_id && $rec['m_id'] == 0 && $mem_id[0] == 1){
						$org_u = $objs[5]->find_by_id($rec['id'], "id");
						$org_u->m_id = $mem_id[0];
						$org_u->update("id");
						
						$first_u = 1;
					}
				}
			// }
			
			for($i=1; $i<9; $i++){
				$tbs_obj[$i]->m_id = $mem_id[0];
				if($i == 3 || $i == 4){
					$tbs_obj[$i]->active = 1;
				}
				if($i == 6){ // Member account registration
					$tbs_obj[$i]->date = $date;
					$tbs_obj[$i]->taken_by = ($first_u == 0) ? get_mem_id($objs[1]->u_id, $objs[1]->org_id[0], $tbs_obj, 0)[0] : 0;
					$tbs_obj[$i]->type = 1; // 0 exit; 1 entry
					$tbs_obj[$i]->active = 1; // 0 previous record; 1 most recent/current record
				}
				if($i == 7){
					$tbs_obj[$i]->status = 1;
					$tbs_obj[$i]->auto_off = 1;
					$tbs_obj[$i]->data_progress = 1;
				}
				$tbs_obj[$i]->create();
			}
		}
		
		// Member file data
		$mem_data_file = array(1, array(org_data($objs[1]->org_id[0])[1], "data_1"), "data_1.json"); // Org directory
		$mem_data = $objs[2]->file_data("", $mem_data_file, true);
		$mem_index = count($mem_data);
		$mem_data[] = array(comp_mem_file_data($mem_id[0], $tbs_obj), $mem_index);
		// $mem_data[] = array(array("David", "Danquah"), count($mem_data));
		$mem_data = $objs[2]->file_data($mem_data, $mem_data_file, false);
		
		// Fix mem file index into db
		$mem_id[1] = $mem_index;
		$this_tbl = $tbs_obj[0]->find_by_id($mem_id[0], "id");
		$this_tbl->f_id = $mem_id[1];
		$this_tbl->update("id");
		
		// if($first_u == 1){
			// First inquire whether a lvl exists
			/* $lvls_data_file = array(1, array(org_data($objs[1]->org_id[0])[1], "data_1", "data_1"), "data_1.json");
			$lvls_data = $objs[2]->file_data("", $lvls_data_file, true); */
			// if(count($lvls_data) < 2){ // Lvls begin from [1]. [0] is lvls count. 2 because count begins from 1
				// move_mem($mem_id, array(), array(array(1, 0), 1), 0);
				move_mem($mem_id, $mem_move[0], $mem_move[1], 0);
			// }
		// }
		
		return $mem_id;
	}
	
	function mem_data_toFile($mem_id, $tbs_obj){ // Save a mem data to file. Only for existing. Sort of like update
		global $objs;
		
		// Member file data
		$mem_data_file = array(1, array(org_data($objs[1]->org_id[0])[1], "data_1"), "data_1.json"); // Org directory
		$mem_data = $objs[2]->file_data("", $mem_data_file, true);
		
		// Compose from db
		$comp_data = comp_mem_file_data($mem_id, $tbs_obj);
		
		$mem_rec_0 = $tbs_obj[0]->find_by_id($mem_id, "id");
		/* for($i=0; $i<count($mem_data); $i++){
			if($mem_rec_0->f_id == $mem_data[$i][1] && $mem_rec_0->id == $mem_data[$i][0]["id"]){
				$mem_data[$i][0] = $comp_data;
			}
		} */
		if($mem_rec_0->f_id == $mem_data[$mem_rec_0->f_id][1] && $mem_rec_0->id == $mem_data[$mem_rec_0->f_id][0]["id"]){
			$mem_data[$mem_rec_0->f_id][0] = $comp_data;
		}
		
		return $objs[2]->file_data($mem_data, $mem_data_file, false);
	}
	
	function get_mems_onType($get_data, $curr_state){
		$get_mems = array();
		// $mem_type_opt = $get_data[1][1][1][0];
		$mem_type_opt = $curr_state;
		$file_data = $get_data[0];
		$grp_data = $get_data[1];
		for($i=0; $i<count($file_data); $i++){
			for($j=0; $j<count($file_data[$i][0]["register"]); $j++){
				if($mem_type_opt[0] == 1 && $file_data[$i][0]["register"][$j][4] == 1){ // Active/Most Recent record
					if($file_data[$i][0]["register"][$j][3] == 1){ // Type: active only
							// echo "[4] Members";
						// echo "<br />";
						// if($mem_type_opt[1][$mem_type_opt[0]][0] == 1){ // Admin
						if($mem_type_opt[1][0] == 1){
							if($file_data[$i][0]["user"][0] > 0 && $file_data[$i][0]["user"][1] == 1){ // Admin
								$get_mems[] = $file_data[$i];
							}
						}
						// if($mem_type_opt[1][$mem_type_opt[0]][1] == 1){ // Super U
						if($mem_type_opt[1][1] == 1){
							if($file_data[$i][0]["user"][0] > 0 && $file_data[$i][0]["user"][1] == 2){ // Super U
								$get_mems[] = $file_data[$i];
							}
						}
						// if($mem_type_opt[1][$mem_type_opt[0]][2] == 1){ // Ordinary U
						if($mem_type_opt[1][2] == 1){
							if($file_data[$i][0]["user"][0] > 0 && $file_data[$i][0]["user"][1] == 3){ // Ordinary U
								$get_mems[] = $file_data[$i];
							}
						}
						/* // if($mem_type_opt[1][$mem_type_opt[0]][3] == 1){ // Superiors
						if($mem_type_opt[1][3] == 1){
							if(in_array(array($file_data[$i][0]["id"], $file_data[$i][1]), $grp_data[0][1])){ // Superiors
							// echo "[3] Superiors";
						// echo "<br />";
								$get_mems[] = $file_data[$i];
							}
						} */
						// if($mem_type_opt[1][$mem_type_opt[0]][3] == 1){ // Just a member
						if($mem_type_opt[1][3] == 1){ // Just a member
							if($file_data[$i][0]["user"][0] == 0 && $file_data[$i][0]["user"][1] == 0){
						/* print_r($mem_type_opt);
						echo "<br />";
						echo "<br />";
						print_r($file_data[$i]);
						echo "<br />"; */
								$get_mems[] = $file_data[$i];
							}
						}
					}
				}elseif($mem_type_opt[0] == 2 && $file_data[$i][0]["register"][$j][4] == 1){ // Active/Most Recent record
					// if($file_data[$i][0]["register"][$j][3] != 1){ // Type: inactive 0/2/3
						// if($mem_type_opt[1][$mem_type_opt[0]][0] == 1 && $file_data[$i][0]["register"][$j][3] == 2){ // Manually turned off (alive)
						if($mem_type_opt[1][0] == 1 && $file_data[$i][0]["register"][$j][3] == 2){ // Manually turned off (alive)
							$get_mems[] = $file_data[$i];
						}
						// if($mem_type_opt[1][$mem_type_opt[0]][1] == 1 && $file_data[$i][0]["register"][$j][3] == 3){ // Manually turned off (dead)
						if($mem_type_opt[1][1] == 1 && $file_data[$i][0]["register"][$j][3] == 3){ // Manually turned off (dead)
							$get_mems[] = $file_data[$i];
						}
						// if($mem_type_opt[1][$mem_type_opt[0]][2] == 1 && $file_data[$i][0]["register"][$j][3] == 0){ // Auto turned off (alive/dead)
						if($mem_type_opt[1][2] == 1 && $file_data[$i][0]["register"][$j][3] == 0){ // Auto turned off (alive/dead)
							$get_mems[] = $file_data[$i];
						}
					// }
				}elseif($mem_type_opt[0] == 3 && $file_data[$i][0]["register"][$j][4] == 1){
					if(in_array(array($file_data[$i][0]["id"], $file_data[$i][1]), $grp_data[0][1])){
						$get_mems[] = $file_data[$i];
					}
				}else{ // Type: All
					// if($mem_type_opt[1][$mem_type_opt[0]][0] == 1 && $file_data[$i][0]["register"][$j][3] == 1){ // All Actives
					if($mem_type_opt[1][0] == 1 && $file_data[$i][0]["register"][$j][3] == 1){ // All Actives
						$get_mems[] = $file_data[$i];
					}
					// if($mem_type_opt[1][$mem_type_opt[0]][1] == 1 && $file_data[$i][0]["register"][$j][3] != 1){ // All Inactives
					if($mem_type_opt[1][1] == 1 && $file_data[$i][0]["register"][$j][3] != 1){ // All Inactives
						$get_mems[] = $file_data[$i];
					}
				}
			}
		}
		
		return $get_mems;
	}
	
	function mem_gen_status($val, $type=0){
		$data = "";
		if($type == 1){
			switch($val){
				case 1:
					$data = 'Married';
					break;
				case 2:
					$data = 'Separated';
					break;
				case 3:
					$data = 'Divorced';
					break;
				default:
					$data = 'Single';
			}
		}else{
			$data = ($val == 'M') ? "Male" : "Female";
		}
		
		return $data;
	}
	
	# $from_lvl: [0] (lvl, grp); [1] 'presence'
	# $to_lvl: [0] (lvl, grp); [1] 'presence'
	function move_mem($id, $from_lvl, $to_lvl, $reception){
		global $objs;
		// $to_lvl = array(1, 0);
		
		// Member move/transfer history
		$mem_data_file = array(1, array(org_data($objs[1]->org_id[0])[1], "data_1"), "data_2.json");
		$mem_data = $objs[2]->file_data("", $mem_data_file, true);
		$from = (count($from_lvl) > 0) ? array(date("Y-m-d"), $from_lvl[0], $from_lvl[1]) : array(); // Empty: very first move. Has no from.
		$to = array(date("Y-m-d"), $to_lvl[0], $to_lvl[1]); // Index 2: presence 0 'no more'; 1 'present'; 2 'waiting'
		$mem_data[] = array($id, array(array($from, $to)));
		$mem_data = $objs[2]->file_data($mem_data, $mem_data_file, false);
		
		// Group in lvl
		$lvls_data_file = array(1, array(org_data($objs[1]->org_id[0])[1], "data_1", "data_1"), "data_1.json");
		$lvls_data = $objs[2]->file_data("", $lvls_data_file, true);
		$this_lvl = $lvls_data[$to_lvl[0][0]];
		$lvl_id = $this_lvl[2];
		$lvl_date = $this_lvl[1];
		
		$lvl_data_file = get_lvl_path(org_data($objs[1]->org_id[0])[1], $lvl_id, $lvl_date, "");
		$lvl_data = $objs[2]->file_data("", $lvl_data_file, true);
		if($reception == 1){ // Waiting Room
			$lvl_data[$to_lvl[0][1]][0][2][0][] = $id;
		}else{ // Main Room
			$lvl_data[$to_lvl[0][1]][0][0][] = $id;
		}
		$lvl_data = $objs[2]->file_data($lvl_data, $lvl_data_file, false);
	}
	
	# $by: if 0, return u's mem id
	# $org_id: if $by is 1, this can be 0
	function get_mem_id($id, $org_id, $tbs_obj, $by=1){
		global $objs;
		
		$mem_id = array(0, 0);
		if($by == 0){
			if($objs[1]->u_id != $id){ // In case the u was mistaken for another member.
				$id = $objs[1]->u_id;
			}
			$org_u = $objs[5]->findMultiple_by_id($org_id, "org_id");
			while($rec = $objs[0][0]->get_selectedResults($org_u)){
				if($rec['u_id'] == $id){
					$mem_id[0] = $rec['m_id'];
				}
			}
		}
		
		if($by == 1){
			$mem_id[0] = $id;
		}
		
		$this_tbl = $tbs_obj[0]->find_by_id($mem_id[0], "id");
		$mem_id[1] = $this_tbl->f_id;
		
		return $mem_id;
	}
	
	function comp_mem_file_data($id, $tbs_obj){
		global $objs;
		
		$fields = array("id", "name", "gender", "dob", "ranks", "address", "contact", "email", "occupation", "maritalstat", "img", "status", "register");
		$fields_vals = array();
		foreach($fields as $key => $val){
			$fields_vals[$val] = get_mem_data(array($id, $tbs_obj), $key);
		}
		
		// U data
		$fields_vals["user"] = u_status($id);
		
		// $values = array($id, array(name_long, name_short, name_fn, name_on, name_ln), gender, array(dob, yr, mnth, day_num, age), array(ranks), array(nation, state, city, address), occupation, mar_stat, img, status);
		
		return $fields_vals;
	}
	
	function u_status($id){
		global $objs;
		
		// U data
		$org_us = $objs[5]->findMultiple_by_id($objs[1]->org_id[0], "org_id");
		$u_status = array(0, 0);
		while($rec = $objs[0][0]->get_selectedResults($org_us)){
			if($rec['m_id'] == $id){
				$this_u = $objs[3]->find_by_id($rec['u_id'], "id");
				$u_status = array($this_u->id, $this_u->type);
			}
		}
		
		return $u_status;
	}
	
	function get_mem_data($data, $key){
		global $objs;
		
		// $by_field = ($key > 0) ? "m_id" : "id";
		$which = 0;
		if($key == 0){ // Id
			$by_field = "id";
		}
		if($key == 1){ // Name
			$by_field = "m_id";
			$which = 1;
		}
		if($key == 2){ // Gender
			$by_field = "id";
			$key = 0;
			$which = 2;
		}
		if($key == 3){ // dob
			$by_field = "id";
			$key = 0;
			$which = 3;
		}
		if($key == 4){ // Ranks
			$by_field = "id";
			$key = 0;
			$which = 4;
		}
		if($key == 5){ // Residence
			$by_field = "m_id";
			$key = 5;
			$which = 5;
		}
		if($key == 6){ // Contact
			$by_field = "m_id";
			$key = 3;
			$which = 6;
		}
		if($key == 7){ // Email
			$by_field = "m_id";
			$key = 4;
			$which = 7;
		}
		if($key == 8){ // Occupation
			$by_field = "id";
			$key = 0;
			$which = 8;
		}
		if($key == 9){ // Mar_Stat
			$by_field = "id";
			$key = 0;
			$which = 9;
		}
		if($key == 10){ // Img
			$by_field = "m_id";
			$key = 8;
			$which = 10;
		}
		if($key == 11){ // Status
			$by_field = "m_id";
			$key = 7;
			$which = 11;
		}
		if($key == 12){ // Register
			$by_field = "m_id";
			$key = 6;
			$which = 12;
		}
		
		$this_data = array();
		if($key == 3 || $key == 4){
			$currDB_obj = $objs[0][$objs[1]->org_id[1]];
			// $tbs_obj = db_obj_tbls($currDB_obj);
			$field = ($key == 3) ? "contact" : "email";
			
			$mem_conts = $data[1][$key]->findMultiple_by_id($data[0], $by_field);
			while($rec = $currDB_obj->get_selectedResults($mem_conts)){
				$this_data[] = array($rec['id'], $rec[$field], $rec['active']);
			}
		}elseif($key == 6){
			$currDB_obj = $objs[0][$objs[1]->org_id[1]];
			$register = $data[1][$key]->findMultiple_by_id($data[0], $by_field);
			while($rec = $currDB_obj->get_selectedResults($register)){
				$this_data[] = array($rec['id'], $rec['date'], $rec['taken_by'], $rec['type'], $rec['active']);
			}
		}else{
			$this_data = $data[1][$key]->find_by_id($data[0], $by_field);
		}
		
		$get_data = array();
		$img_path = "domains".DS.org_data($objs[1]->org_id[0])[1].DS."data_8";
		switch($which){
			case 0:
				$get_data = $this_data->id;
				break;
			case 1:
				$name_1 = $this_data->f_name." ";
				$name_1 .= (!empty($this_data->o_name)) ? $this_data->o_name." " : "";
				$name_1 .= $this_data->l_name;
				$get_data = array($name_1, $name_1, $this_data->f_name, $this_data->o_name, $this_data->l_name);
				break;
			case 2:
				$get_data = $this_data->gender;
				break;
			case 3:
				// $get_data = array($this_data->dob, $objs[2]->ageFrom_dob($this_data->dob), date("Y", strtotime($this_data->dob)), date("F", strtotime($this_data->dob)), date("d", strtotime($this_data->dob)));
				$get_data = array($this_data->dob, $objs[2]->ageFrom_dob($this_data->dob), date("Y", strtotime($this_data->dob)), date("m", strtotime($this_data->dob)), date("d", strtotime($this_data->dob)));
				break;
			case 4:
				$get_data = array();
				break;
			case 5:
				$get_data = array($this_data->nation, $this_data->state_province, $this_data->city, $this_data->h_address);
				break;
			case 6:
				$get_data = $this_data;
				break;
			case 7:
				$get_data = $this_data;
				break;
			case 8:
				$get_data = $this_data->occupation;
				break;
			case 9:
				$get_data = $this_data->marital_status;
				break;
			case 10:
				$get_data = array($img_path.DS.$this_data->l_name, $img_path.DS.$this_data->m_name, $img_path.DS.$this_data->s_name);
				break;
			case 11:
				$get_data = array($this_data->status, $this_data->auto_off, $this_data->data_progress);
				break;
			case 12:
				$get_data = $this_data;
				break;
			/* default;
				echo "mango"; */
		}
		
		return $get_data;
	}
	
	# $value: 0/1/2 'All/Actives/Inactives' when $type[0] = 0; 0/1 'Each Color Value' when $type[0] = 1
	# $type: [0] Selected Side; 0 'All/Actives/Inactives', 1 Mem Segregation 'Color Groups'
	# $type: [1] Equal to length of Selected State 'All/Actives/Inactives'. Only used when $type[0] = 1'Color Groups'
	# $type: [2] Various colors value in selected $type[1]
	# mems_disp_state(0/1/2, array(0), 1) // Set Selected State
	# mems_disp_state(0/1, array(1, 0<=Selected State, 0<=Color Groups), 1) // Set Color Groups
	# mems_disp_state(0, array(0), 0) // Call both Selected State & Segregation
	function mems_disp_state($value, $type=array(0), $mode=0){
		if($mode == 1){
			if($type[0] == 0){
				$cookie_name = "m-l-s-0";
				$cookie_value = $value;
				
				$sel_state = $value;
			}
			if($type[0] == 1){
				$cookie_name = "m-l-s-1-".$type[1]."-".$type[2];
				$cookie_value = $value;
				
				// $sel_state = $_COOKIE["m-l-s-0"];
				$sel_state = $type[1];
			}
			// echo "We came here..".", ".$cookie_name.", ".$type[0];
			// print_r($sel_state);
						// echo "<br />";
			// setcookie($cookie_name, $cookie_value, time() + (86400 * 30), 'SameSite=None', "/"); // 86400 is the number of seconds a day. 86400 = 1 day
			setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 is the number of seconds a day. 86400 = 1 day
			
			$len = 1; // Superiors
			if($sel_state == 0){ // All
				$len = 2;
			}elseif($sel_state == 1){ // Actives
				$len = 4;
			}elseif($sel_state == 2){ // Inactives
				$len = 3;
			}
			
			$states = array($sel_state, array());
			for($i=0; $i<$len; $i++){
				$states[1][] = ($type[0] == 1 && $i == $type[2]) ? $value : $_COOKIE["m-l-s-1-".$sel_state."-".$i];
			}
			
			return $states;
		}else{
			if(!isset($_COOKIE["m-l-s-0"])){
				mems_disp_state(0, array(0), 1);
			}
			for($i=0; $i<4; $i++){
				$len = 1; // Superiors
				if($i == 0){ // All
					$len = 2;
				}elseif($i == 1){ // Actives
					$len = 4;
				}elseif($i == 2){ // Inactives
					$len = 3;
				}
				
				for($j=0; $j<$len; $j++){
					// echo $j;
					// echo "<br />";
					if(!isset($_COOKIE["m-l-s-1-".$i."-".$j])){
						// echo "m-l-s-1-".$i."-".$j.", not set";
						// echo "<br />";
						mems_disp_state(1, array(1, $i, $j), 1);
					}
				}
			}
			
			$len = 1; // Superiors
			if($_COOKIE["m-l-s-0"] == 0){ // All
				$len = 2;
			}elseif($_COOKIE["m-l-s-0"] == 1){ // Actives
				$len = 4;
			}elseif($_COOKIE["m-l-s-0"] == 2){ // Inactives
				$len = 3;
			}
			
			$states = array($_COOKIE["m-l-s-0"], array());
			for($i=0; $i<$len; $i++){
				$states[1][] = $_COOKIE["m-l-s-1-".$_COOKIE["m-l-s-0"]."-".$i];
			}
			
			return $states;
		}
	}
	
	function org_data($id){
		global $objs;
		
		$this_org = $objs[4]->find_by_id($id, "id");
		$db_name = "org_".$this_org->id.$this_org->u_room;
		$file_name = "dom_".$this_org->id.$this_org->u_room;
		
		return array($db_name, $file_name);
	}
	
	function who_is_this($tbs_obj, $id){ // Is this the Admin or just a member
		global $objs;
		
		$this_org = $objs[4]->find_by_id($objs[1]->org_id[0], "id");
		
		$d1_d1_file = array(3, "data_2", "data_1.json");
		$d1_d1 = $objs[2]->file_data("", $d1_d1_file, true);
		$new_d1_d1 = array();
		for($i=1; $i<count($d1_d1); $i++){
			$new_d1_d1[] = $d1_d1[$i];
		}
		
		$first_u = array(0, "", "");
		
		$mem_id = get_mem_id($objs[1]->u_id, $objs[1]->org_id[0], $tbs_obj, 0);
		$all_mem = $tbs_obj[0]->find_all();
		if($objs[0][0]->get_numOFrows($all_mem) == 1 && $mem_id[0] == $id){
			for($i=0; $i<count($new_d1_d1); $i++){
				if($new_d1_d1[$i][1] == $this_org->u_room){ // On U room
					for($j=0; $j<count($new_d1_d1[$i][0]); $j++){
						if($new_d1_d1[$i][0][$j][1] == $objs[1]->u_id){ // On U id
							$email = implode('', $objs[2]->enc_dec_str($new_d1_d1[$i][0][$j][0][0], false, 6, 3)); // Decode before;
							$contact = implode('', $objs[2]->enc_dec_str($new_d1_d1[$i][0][$j][0][1], false, 6, 3)); // Decode before;
							$first_u = array(1, $email, $contact);
						}
					}
				}
			}
		}
		
		return $first_u;
	}
	
	function get_lvl_grp($lvl_grp, $mem_data){
		global $objs;
		
		# Lvl
		$lvls_data_file = array(1, array(org_data($objs[1]->org_id[0])[1], "data_1", "data_1"), "data_1.json");
		$lvls_data = $objs[2]->file_data("", $lvls_data_file, true);
		$this_lvl = $lvls_data[$lvl_grp[0]];
		$lvl_id = $this_lvl[2];
		$lvl_date = $this_lvl[1];
		
		# Grp
		$lvl_data_file = get_lvl_path(org_data($objs[1]->org_id[0])[1], $lvl_id, $lvl_date, "");
		$lvl_data = $objs[2]->file_data("", $lvl_data_file, true);
		// $main_room = $lvl_data[$lvl_grp[1]][0][0];
		$this_grp = $lvl_data[0][$lvl_grp[1]]; // $lvl_data: [0]: Mother Grp. All elements to follow from [1] going will be custom/children/sub grps under the mother grp.
		$main_room = $this_grp[0][0];
		
		$get_data = array();
		for($i=0; $i<count($main_room); $i++){
			$this_mem_data = $mem_data[$main_room[$i][1]];
			$get_data[] = get_sel_mem($this_mem_data, $main_room[$i]); // Append extra data
		}
		
		// return array($get_data, $lvl_data[$lvl_grp[1]]);
		return array($get_data, $this_grp);
	}
	
	function get_sel_mem($this_mem_data, $mem_id){
		global $objs;
		
		$get_data = array();
		if($this_mem_data[0]['id'] == $mem_id[0]){ // On mem id
			$this_mem_data[0]["dob"][] = $objs[2]->count_down_in_month($this_mem_data[0]["dob"][0]);
			// print_r($this_mem_data[0]["dob"]);
			$get_data = $this_mem_data;
		}
		
		return $get_data;
	}
	
	function display_birthday($data){
		global $objs;
		
		// $html = "";
		$bd_mess = array("", "");
		// print_r($data[0]);
		if($data[0][0] == 1){
			// Background Color
			// $html .= "<section style='display: inline-block; width: 15px; height: 15px; background-color: #";
			if($data[0][1] == 1){
				// $html .= ($data[$data[0][1]][0] == 1) ? "ffffb3" : "fbd3d3";
				if($data[$data[0][1]][0] == 1){
					// $bd_mess[0] .= "Yesterday, ".$dob_str[1];
					$bd_mess[0] .= "Yesterday, ".$data[4][0];
					$bd_mess[0] .= " was your Birthday.";
					
					$bd_mess[1] = "birthday-1-1";
					// $bd_color_txt = "#808000";
					// $bd_color_bgr = "#ffffb3";
				}else{
					$passed_suffix = ($data[$data[0][1]][1] == 1) ? "" : "s";
					$bd_mess[0] .= "Your Birthday was ".$data[$data[0][1]][1];
					$bd_mess[0] .= " day".$passed_suffix." ago.";
					// $bd_mess[0] .= " On ".$dob_str[1].".";
					$bd_mess[0] .= " On ".$data[4][0].".";
					
					$bd_mess[1] = "birthday-1-2";
					// $bd_color_txt = "#dd0000";
					// $bd_color_bgr = "#fbd3d3";
				}
			}
			if($data[0][1] == 2){
				// $html .= "d3fbd7";
				$bd_mess[0] .= "Hurray!!! Today, ";
				// $bd_mess[0] .= $dob_str[1]." is your Birthday...";
				$bd_mess[0] .= $data[4][0]." is your Birthday...";
				$bd_mess[0] .= "Congratulations!! ";
				// $bd_mess[0] .= "<em>You're ".$age." years old.</em>";
				$bd_mess[0] .= "<em>You're ".$data[4][1]." years old.</em>";
				
				$bd_mess[1] = "birthday-2";
				// $bd_color_txt = "#00990f";
				// $bd_color_bgr = "#d3fbd7";
			}
			if($data[0][1] == 3){
				// $html .= ($data[$data[0][1]][0] == 1) ? "ffffb3" : "ffe0b3";
				if($data[$data[0][1]][0] == 1){
					// $bd_mess[0] .= "Tomorrow, ".$dob_str[1];
					$bd_mess[0] .= "Tomorrow, ".$data[4][0];
					$bd_mess[0] .= " is your Birthday..!!";
					
					$bd_mess[1] = "birthday-2-1";
					// $bd_color_txt = "#808000";
					// $bd_color_bgr = "#ffffb3";
				}else{
					$coming_suffix = ($data[$data[0][1]][1] == 1) ? "" : "s";
					$bd_mess[0] .= $data[$data[0][1]][1]." day".$coming_suffix;
					$bd_mess[0] .= " remaining to your Birthday..";
					// $bd_mess[0] .= " On ".$dob_str[1];
					$bd_mess[0] .= " On ".$data[4][0];
					
					$bd_mess[1] = "birthday-2-2";
					// $bd_color_txt = "#995c00";
					// $bd_color_bgr = "#ffe0b3";
				}
			}
			
			// Border Color
			/* $html .= "; border: 1px solid #";
			if($data[0][1] == 1){
				$html .= ($data[$data[0][1]][0] == 1) ? "808000" : "dd0000";
			}
			if($data[0][1] == 2){
				$html .= "00990f";
			}
			if($data[0][1] == 3){
				$html .= ($data[$data[0][1]][0] == 1) ? "808000" : "995c00";
			}
			$html .= "; border-radius: 50%;'></section> <em>Birthday..</em>"; */
		}
		
		return $bd_mess;
	}
	
	
	# LEVELS HERE
	function get_lvl_path($db_name, $id, $date, $name){
		global $objs;
		
		$filename = $objs[2]->compose_name($id, $date, $name);
		// $dnmc_info_sub = array("dynamic_grpn", $filename.".json");
		$lvl_info_sub = array(1, array($db_name, "data_1", "data_1"), $filename.".json");
		
		return $lvl_info_sub;
	}
?>