<?php
	require_once("../core_1.php");
?>
<?php
	
	$action = @$_REQUEST['action'];
	$mode = @$_REQUEST['mode'];

	// $action = array(array(array(1, 0), array(array(array("+233207501054", 1), 1), 1)), 0); //
	// $action = array(array(array(1, 0), array(2, 0)), 2); //
	
	// $action = [[["1","0"],["3",["0","0"]]],"2"];
	// $action = [["David","","Danq","M","",""],"0"];
	// $mode = "mems"; //
	// $mode = "mems-1"; //
	
	$data = array();
	
	if(isset($mode)){
		if($mode == "mems-1"){
			processor_1($action);
			$mode = "mems-1";
		}
		if($mode == "mems"){
			processor_2($action);
			$mode = "mems";
		}
	}
	
	function processor_1($action){
		global $objs;
		
		global $data;
		
		
		$currDB_obj = $objs[0][$objs[1]->org_id[1]];
		$tbs_obj = db_obj_tbls($currDB_obj);
		
		// $objs[2]->request_dir(array(3, "data_2", ""));
		// file_put_contents($objs[2]->get_dir.DS."debug.json", json_encode($action)); // Debug
		
		$objs[2]->filter_array($action[0], 0);
		$new_data = $action[0];
		
		$mem_data_file = array(1, array(org_data($objs[1]->org_id[0])[1], "data_1"), "data_1.json"); // Org directory
		$mem_data = $objs[2]->file_data("", $mem_data_file, true);
		
		// $validate = array(0, 0, 0, 0, 0, 0);
		$get_mems_data = array();
		$all_mems = $tbs_obj[1]->find_all();
		while($rec = $currDB_obj->get_selectedResults($all_mems)){
			$mem_rec = $tbs_obj[0]->find_by_id($rec['m_id'], "id");
			
			if(!empty($new_data[0]) && $rec['f_name'] == $new_data[0]){
				$get_mems_data[] = array($mem_rec->id, $mem_rec->f_id);
			}
			if(!empty($new_data[1]) && $rec['o_name'] == $new_data[1]){
				$get_mems_data[] = array($mem_rec->id, $mem_rec->f_id);
			}
			
			if($mem_rec->gender == $new_data[3] && $mem_rec->dob == $new_data[4] && $mem_rec->nationality == $new_data[5]){
				$get_mems_data[] = array($mem_rec->id, $mem_rec->f_id);
			}
		}
		
		if(!empty($new_data[2])){
			// $sql = "SELECT * FROM data_2 WHERE l_name like '%".$new_data[2]."%'";
			$sql = "SELECT * FROM data_2 WHERE l_name like '%".$new_data[2]."%'";
			$query = $currDB_obj->all_Queries($sql);
			while($rec = $currDB_obj->get_selectedResults($query)){
				$mem_rec = $tbs_obj[0]->find_by_id($rec['m_id'], "id");
				if(!in_array(array($mem_rec->id, $mem_rec->f_id), $get_mems_data)){
					$get_mems_data[] = array($mem_rec->id, $mem_rec->f_id);
				}
			}
		}
		// print_r($get_mems_data);
		// $get_mems_data = $objs[2]->compare_contents($get_mems_data, true, false);
		
		$get_mem_data = array();
		for($i=0; $i<count($get_mems_data); $i++){
			if($get_mems_data[$i][1] == $mem_data[$get_mems_data[$i][1]][1] && $get_mems_data[$i][0] == $mem_data[$get_mems_data[$i][1]][0]["id"]){
				// $get_mem_data[] = $mem_data[$get_mems_data[$i][1]];
				$get_mem_data[] = get_sel_mem($mem_data[$get_mems_data[$i][1]], $get_mems_data[$i]);
			}
		}
			// print_r($get_mem_data);
		
		$data = $get_mem_data;
	}
	
	function processor_2($action){
		global $objs;
		
		global $data;
		
		$currDB_obj = $objs[0][$objs[1]->org_id[1]];
		$tbs_obj = db_obj_tbls($currDB_obj);
		
		// $objs[2]->request_dir(array(3, "data_2", ""));
		// file_put_contents($objs[2]->get_dir.DS."debug.json", json_encode($action)); // Debug
			// print_r($objs[2]->get_dir);
		
		$objs[2]->filter_array($action[0], 0);
		$new_data = $action[0];
		
		$mem_data_file = array(1, array(org_data($objs[1]->org_id[0])[1], "data_1"), "data_1.json"); // Org directory
		$mem_data = $objs[2]->file_data("", $mem_data_file, true);
		
		if($action[1] == 1){
			$act_type = $new_data[1][1]; // Action Type 1: Add, 0: Clear/Remove, 2: Make Active
			$mem_id = $new_data[1][0][1]; // [0]: Mem id; [1]: Record id
			$data_type = $new_data[1][0][0][1]; // 1: Contact, 0: Email
			$obj_key = ($data_type == 1) ? array(3, "contact") : array(4, "email");
			if($act_type == 1){ // Add
				$tbs_obj[$obj_key[0]]->m_id = $mem_id[0];
				$tbs_obj[$obj_key[0]]->{$obj_key[1]} = $new_data[1][0][0][0];
				$tbs_obj[$obj_key[0]]->active = 0;
				$tbs_obj[$obj_key[0]]->create();
					// print_r($tbs_obj[$obj_key[0]]->{$obj_key[1]});
			}elseif($act_type == 2){ // Make Active
				$tbs_obj[$obj_key[0]]->update_multiple(array("m_id", $mem_id[0]), array("active", 0));
				$obj_rec = $tbs_obj[$obj_key[0]]->find_by_id($mem_id[1], "id");
				$obj_rec->active = 1;
				$obj_rec->update("id");
			}else{ // Clear/Remove
				$tbs_obj[$obj_key[0]]->delete($mem_id[1], "id"); // From org
			}
			
			$mem_data = mem_data_toFile($mem_id[0], $tbs_obj);
		}
		
		$curr_state = mems_disp_state(0, array(0), 0);
		if($action[1] == 2){
			if($new_data[1][1][1] == 0){
				// $lvl_data[$new_data[0][1]][1][1][$new_data[1][1][0]][$new_data[1][1][1]] = $new_data[1][0];
				$curr_state = mems_disp_state($new_data[1][0], array($new_data[1][1][1]), 1);
			}
			if($new_data[1][1][1] == 1){
				// $lvl_data[$new_data[0][1]][1][1][$new_data[1][1][0]][$new_data[1][1][1]][$new_data[1][1][2][0]][$new_data[1][1][2][1]] = $new_data[1][0];
				$curr_state = mems_disp_state($new_data[1][0], array($new_data[1][1][1], $new_data[1][1][2][0], $new_data[1][1][2][1]), 1);
			}
			// print_r($curr_state);
		}
		
		$get_data = get_lvl_grp($new_data[0], $mem_data);
		$get_data = array(get_mems_onType($get_data, $curr_state), $get_data[1]);
		
		
		$data = array($get_data, $curr_state);
		// $new_session->message($new_core_6->combine_all_messages);
		// $history = $new_core_6->save_userHistory($content, $U->id);
	}
	
	$json = json_encode(array("data"=>$data, "mode"=>$mode));
	echo $json;
?>