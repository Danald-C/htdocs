<?php
	
	require_once("_layouts/layout_1.php");
	
	// if ((!$new_core_4->page_status(0) && !$new_core_4->is_logged_in()) || @$_GET["access-type"] > 1) { // Access-Type: 0 Login; 1 Sign Up
	if ((!$objs[1]->page_status(0) && !$objs[1]->is_logged_in()) || @$_GET["access-type"] > 1) { // Access-Type: 0 Login; 1 Sign Up
		redirect_to("index.php?sm=true");
	}
	if(isset($_GET["p_f"]) || isset($_GET["p_t"])){
		$objs[1]->page_access_all();
	}
	
	$d1_d1_file = array(3, "data_2", "data_1.json");
	$d1_d1 = $objs[2]->file_data("", $d1_d1_file, true);
	$new_d1_d1 = array();
	for($i=1; $i<count($d1_d1); $i++){
		$new_d1_d1[] = $d1_d1[$i];
	}
	
	// if($new_core_4->is_logged_in()){
	if($objs[1]->is_logged_in()){
		$loggedIn_u = $objs[3]->find_by_id($objs[1]->u_id, "id");
	}
	
	if(isset($_GET["logout"])){
		for($i=1; $i<count($d1_d1); $i++){
			for($j=0; $j<count($d1_d1[$i][0]); $j++){
				// if($d1_d1[$i][0][$j][1] == $new_core_4->u_id){
				if($d1_d1[$i][0][$j][1] == $objs[1]->u_id){
					// $this_u = $new_core_8->find_by_id($new_core_5->u_id, "id");
					// print_r($d1_d1[$i][0][$j]);
					$u_log_file = "u_".$loggedIn_u->id.$loggedIn_u->room;
					// $u_log = $new_core_5->file_data("", array(3, "data_2", $u_log_file.".json"), true);
					$u_log = $objs[2]->file_data("", array(3, "data_2", $u_log_file.".json"), true);
					$u_log[0][1] = date("Y-m-d h:m"); // [0]: Login; [1]: Logout
					// $u_log = $new_core_5->file_data($u_log, array(3, "data_2", $u_log_file.".json"), false);
					$u_log = $objs[2]->file_data($u_log, array(3, "data_2", $u_log_file.".json"), false);
					
					$objs[0] = $objs[0][0];
					// $d1_d1[$i][0][$j][0][1][0][1] = date("Y-m-d h:m"); // [0]: Login; [1]: Logout
					// $d1_d1 = $new_core_6->file_data($d1_d1, $d1_d1_file, false);
				}
			}
		}
		// $new_core_4->user_logout();
		$objs[1]->user_logout();
		redirect_to("index.php?sm=true");
	}
	if(isset($_GET["action"])){
		if($_GET["action"] == 1){ // HERE TO VALIDATE INCOMING ACCESS LINK FROM EMAIL
			$verified = 0;
			// $this_u = $new_core_7->find_by_id($_GET["on"], "id");
			$this_u = $objs[3]->find_by_id($_GET["on"], "id");
			if($this_u->id == 1){
				$verified = 1;
			}else{
				for($i=0; $i<count($new_d1_d1); $i++){
					for($j=0; $j<count($new_d1_d1[$i][0]); $j++){
						if($new_d1_d1[$i][0][$j][1] == $this_u->id){
							// $access_code = str_replace("-", "", $this_u->date).$this_u->room.$this_u->id;
							// A COOKIE CAN BE SET TO MAKE SURE THE CLIENT DID NOT MISTAKENLY ENTER SOMEONE ELSE'S EMAIL
							// PUT AN EXPIRATION ON THE DURATION OF EVERY ACCESS KEY. MAXIMUM SHOULD BE A DAY
							// CHECK WHETHER INCOMING CLIENT ALREADY EXIST
							/* print_r($this_u->a_k.", ".$access_code.", ".$_GET["access-key"]);
							echo "<br />";
							print_r($new_d1_d1[$i][0][$j]);
							echo "<h1>Hello there {$new_d1_d1[$i][0][$j][0][0]}, I'm happy to see you..</h1>"; */
							
							if($this_u->a_k == $_GET["access-key"]){
								$u_log_file = "u_".$this_u->id.$this_u->room;
								// $u_log = $new_core_5->file_data("", array(3, "data_2", $u_log_file.".json"), true);
								$u_log = $objs[2]->file_data("", array(3, "data_2", $u_log_file.".json"), true);
								$get_first = $u_log[0];
								$new_date = date_create($get_first[0]);
								$new_date->add(new DateInterval('PT24H')); // Add 1 day
								
								/* $get_first = $new_d1_d1[$i][0][$j][0][1][0];
								$new_date = date_create($get_first[0]);
								$new_date->add(new DateInterval('PT24H')); // Add 1 day */
								
								if(date("Y-m-d h:m") <= $new_date->format('Y-m-d h:i')){
									$verified = 1;
								}
							}
						}
					}
				}
			}
			
			if($verified == 1){
				redirect_to($_SERVER["PHP_SELF"]."?action=2&on=".$_GET["on"]."&access-type=".$_GET["access-type"]);
			}else{
				redirect_to("index.php?sm=true");
			}
		}
		
		if($_GET["action"] == 3){
			// $this_u = $new_core_7->find_by_id($_GET["on"], "id");
			$this_u = $objs[3]->find_by_id($_GET["on"], "id");
			for($i=1; $i<count($d1_d1); $i++){
				if($this_u->room == $d1_d1[$i][1]){ // On U-Room
					for($j=0; $j<count($d1_d1[$i][0]); $j++){
						if($this_u->id == $d1_d1[$i][0][$j][1]){ // On U-Id
							$u_log_file = "u_".$this_u->id.$this_u->room;
							// $u_log = $new_core_5->file_data("", array(3, "data_2", $u_log_file.".json"), true);
							$u_log = $objs[2]->file_data("", array(3, "data_2", $u_log_file.".json"), true);
							$get_first = $u_log[0]; // When access key was requested
							$new_date = date_create($get_first[0]);
							$new_date->sub(new DateInterval('PT24H')); // minus 1 day
							$u_log[0][0] = date("Y-m-d h:m"); // Set a new date for login success
							$access_code = preg_replace("/[-:\/ ]/", "", $new_date->format('Y-m-d h:i')).$this_u->room.$this_u->id;
							// print_r($access_code);
							
							$page = $_SERVER["PHP_SELF"]."?sm=true";
							if($_GET["access-type"] == 1){ // Sign Up. We receive password for the first time
								// $get_data = implode('', $new_core_5->enc_dec_str($_POST["confirm-pass"], true, 6, 3));
								$get_data = implode('', $objs[2]->enc_dec_str($_POST["confirm-pass"], true, 6, 3));
								$d1_d1[$i][0][$j][0][2] = $get_data;
								
								// $pass = $new_core_3->escape_value(sha1(trim($_POST["confirm-pass"])));
								$pass = $objs[0][0]->escape_value(sha1(trim($_POST["confirm-pass"])));
								$this_u->pw = $pass;
								$this_u->a_k = $access_code;
							// print_r(date_create());
								
								// $new_core_4->user_login($this_u);
								$objs[1]->user_login($this_u);
								// $d1_d1 = $new_core_5->file_data($d1_d1, $d1_d1_file, false);
								$d1_d1 = $objs[2]->file_data($d1_d1, $d1_d1_file, false);
							}else{ // Otherwise, login
								$auth = $objs[3]->authenticate($_POST["pass"]);
								if($auth[1] == 1 && $auth[0]->id == $this_u->id){
									if($this_u->id > 1){
										$this_u->a_k = $access_code;
									}
									
									// Renumber all orgs in this u room.
									$u_room_db_file = array(3, "data_1", "data_{$this_u->room}.json");
									$u_room_db = $objs[2]->file_data("", $u_room_db_file, true);
									for($k=0; $k<count($u_room_db); $k++){
										$u_room_db[$k][1] = $k+1;
									}
									if(count($u_room_db) > 0){
										$u_room_db = $objs[2]->file_data($u_room_db, $u_room_db_file, false);
									}
									
									$objs[1]->user_login($auth[0]);
								}else{
									$page = "index.php?sm=true";
								}
							}
							
							$this_u->update("id");
							// $u_log = $new_core_5->file_data($u_log, array(3, "data_2", $u_log_file.".json"), false);
							$u_log = $objs[2]->file_data($u_log, array(3, "data_2", $u_log_file.".json"), false);
							
							// $new_core_4->page_unset(0); // Unset & handover to user session
							$objs[1]->page_unset(0); // Unset & handover to user session
							redirect_to($page);
						}
					}
				}
			}
			// echo "<br />";
			// echo "This access type: ".$_GET["access-type"];
		}
	}
	
	if(isset($_GET["req"])){
		if($_GET["req"] == 1){
			$objs[4]->ref_name = implode('', $objs[2]->enc_dec_str($_POST["org-name"], true, 6, 3));
			$objs[4]->date = date("Y-m-d");
			$objs[4]->u_room = $loggedIn_u->room;
			
			if($objs[4]->create()){
				$this_org = $objs[4]->find_by_id($objs[0][0]->new_id, "id");
				$db_name = "org_".$this_org->id.$this_org->u_room;
				// print_r($db_name);
				
				$this_org->ref_db = $db_name;
				$this_org->update("id");
				$sql = "create database ".$this_org->ref_db;
				$objs[0][0]->all_Queries($sql);
				
				$data = get_db_data($this_org->ref_db, $this_org->u_room, 1);
				
				// Org U
				$objs[5]->u_id = $loggedIn_u->id;
				$objs[5]->org_id = $this_org->id;
				$objs[5]->create();
				
				$objs[2]->request_dir(array(1, org_data($this_org->id)[1], ""));
				if(@mkdir($objs[2]->get_dir, 0744, true)){
					// Members, Attendance, Groups, Programs, Notes, Finance, Relationship, Picture
					for($i=1; $i<9; $i++){
						if(is_dir($objs[2]->get_dir)){
							if($i == 1){
								@mkdir($objs[2]->get_dir.DS."data_".$i.DS."data_1", 0744, true);
							}else{
								@mkdir($objs[2]->get_dir.DS."data_".$i, 0744, true);
							}
						}
					}
				}
				
				redirect_to($_SERVER["PHP_SELF"]."?req=2&on={$this_org->id}");
			}
		}
		
		if($_GET["req"] == 2){
			install_tb_struct();
			
			$db_name = org_data($_GET["on"])[1];
			if(!$objs[2]->fileExists(1, array($db_name, "data_1"), "data_1.json")){
				$mem_data_file = array(1, array($db_name, "data_1"), "data_1.json");
				$mem_data = $objs[2]->file_data(array(), $mem_data_file, false);
			}
			if(!$objs[2]->fileExists(1, array($db_name, "data_1"), "data_2.json")){ // Mem lvls & grps extra data eg. History etc
				$mem_data_file = array(1, array($db_name, "data_1"), "data_2.json");
				$mem_data = $objs[2]->file_data(array(), $mem_data_file, false);
			}
			
			# LEVELS & GROUPS
			if(!$objs[2]->fileExists(1, array($db_name, "data_1", "data_1"), "data_1.json")){
				$lvls_data_file = array(1, array($db_name, "data_1", "data_1"), "data_1.json");
				$lvls_data = $objs[2]->file_data(array(array(0)), $lvls_data_file, false);
				
				$lvls_data[0][0] += 1;
				$date = date("Y-m-d");
				
				# Level
				// Create corresponding file
				$lvl_data_file = get_lvl_path($db_name, $lvls_data[0][0], $date, "");
				$lvl_data = $objs[2]->file_data(array(), $lvl_data_file, false);
				
				$lvls_data[] = array("My New Level", $date, $lvls_data[0][0]);
				
				// Add corresponding data
				$lvls_data = $objs[2]->file_data($lvls_data, $lvls_data_file, false);
				
				# Group
				$this_lvl = $lvls_data[1];
				$lvl_id = $this_lvl[2];
				$lvl_date = $this_lvl[1];
				
				// Get corresponding file
				$lvl_data_file = get_lvl_path($db_name, $lvl_id, $lvl_date, "");
				$lvl_data = $objs[2]->file_data("", $lvl_data_file, true);
				# [0]: Mother Grp. All elements to follow from [1] going will be custom/children/sub grps under the mother grp.
				$grp_data = array(array(array(array(), array(), array(array(), array())), array(array("My New Group", $date), array(), array(array(), count($lvl_data), array()))));
				$lvl_data[] = $grp_data;
				$lvl_data = $objs[2]->file_data($lvl_data, $lvl_data_file, false);
			}
			
			if(!$objs[2]->fileExists(1, array($db_name, "data_2"), "data_1.json")){ // Attendance: [0] count
				$mem_data_file = array(1, array($db_name, "data_2"), "data_1.json");
				$mem_data = $objs[2]->file_data(array(array(0)), $mem_data_file, false);
			}
			
			redirect_to($_SERVER["PHP_SELF"]."?sm=true");
		}
		
		if($_GET["req"] == 3){
			// echo "Did we come here?..".$objs[1]->page_status($_GET["p_f"]);
			// echo "Did we come here?..".$objs[1]->page_status($_GET["p_t"]);
			// $objs[1]->page_access_all(1);
			$go = 1;
			if($_GET["p_t"] == 1){
				// $objs[1]->curr_org(1, $_GET["on"]);
				$obj_i = currDB_obj($_GET["on"]);
				$objs[1]->curr_org(1, array($_GET["on"], $obj_i));
				if($obj_i == 0){
					$go = $obj_i;
				}
			}
			
			if($go == 1){
				$objs[1]->page_access($_GET["p_t"]);
				u_prefs(); // U Preferences/Settings
				mems_disp_state(0, array(0), 0); // Set Members Display
				redirect_to("page_{$_GET["p_t"]}.php?p_f={$_GET["p_f"]}&p_t={$_GET["p_t"]}&sm=true");
			}else{
				redirect_to($_SERVER['PHP_SELF']."?sm=true");
			}
		}
		
		if($_GET["req"] == 4){ // Remove Org & db related
			$this_org = $objs[4]->find_by_id($_GET["on"], "id");
	
			$objs[2]->request_dir(array(1, org_data($this_org->id)[1], ""));
			$from_dir = $objs[2]->get_dir;
			if(is_dir($from_dir)){
				$objs[2]->rem_copy_dir_ingine(array($from_dir, ""), 0); // Clear file
			}
			
			$data = get_db_data("", $this_org->u_room);
			$obj_i = array(-1, -1);
			for($i=1; $i<count($objs[0]); $i++){
				$org_name_1 = $objs[0][$i]->get_data[0][3];
				$org_cnt_1 = $objs[0][$i]->get_data[1];
				for($j=0; $j<count($data); $j++){
					$org_name_2 = $data[$j][0][3];
					$org_cnt_2 = $data[$j][1];
					if($this_org->ref_db == $org_name_1 && $org_name_1 == $org_name_2 && $org_cnt_1 == $org_cnt_2){
						$obj_i = array($i, $j);
					}
				}
			}
			if($obj_i[0] > 0){
				array_splice($objs[0], $obj_i[0], 1); // Clear obj
				
				$org_db_file = array(3, "data_1", "data_{$this_org->u_room}.json");
				$org_db = $objs[2]->file_data("", $org_db_file, true);
				array_splice($org_db, $obj_i[1], 1); // Clear obj
				$org_db = $objs[2]->file_data($org_db, $org_db_file, false);
				
				// Clear database
				$sql = "DROP DATABASE ".org_data($this_org->id)[0];
				// $sql = "DROP DATABASE org_1611";
				$link = new mysqli('localhost', 'root', '');
				$link->query($sql);
				$link->close();
				// print_r($refine_str);
				
				// Clear associated db data
				$objs[5]->delete($this_org->id, "org_id", true); // From org u
				$objs[4]->delete($this_org->id, "id"); // From org
				
				redirect_to($_SERVER["PHP_SELF"]."?sm=true");
			}
		}
	}
	
	// echo $objs[1]->u_id.", ".$objs[1]->org_id;
?>
<!doctype html>
<html>
<head>
	<!-- <script src="_css/bootstrap/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script> -->
	
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- <link href="_css/bootstrap/node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"> -->
	<link href="_css/css_main.css" rel="stylesheet">
	<link rel="stylesheet" href="_css/w3.css">
</head>
<body class="w3-container w3-auto">
	<div id="page-0">
		<!-- <div class="container p-5 my-5 text-white"> -->
		<div id="access">
			<?php
				if(isset($_GET["action"]) && $_GET["action"] == 2){
					if(!$objs[1]->is_logged_in()){
						echo "<form id='{$_GET["access-type"]}' method='post' action='".$_SERVER["PHP_SELF"]."?action=3&on=".$_GET["on"]."&access-type=".$_GET["access-type"]."'>";
							echo "<input type='password' id='pass' name='pass' placeholder='Your Password..' />";
							$inscription = "Log In";
							if($_GET["access-type"] == 1){ // Sign Up
								echo "<input type='password' id='confirm-pass' name='confirm-pass' placeholder='Confirm Password..' />";
								$inscription = "Sign In";
							}
							echo "<input id='grant-access' type='button' value='{$inscription}' />";
						echo "</form>";
					}else{
						redirect_to($_SERVER["PHP_SELF"]."?sm=true");
					}
				}
			?>
		</div>
		<div id="u-page">
			<?php
				if($objs[1]->is_logged_in()){
					$loggedin_u = array();
					for($i=0; $i<count($new_d1_d1); $i++){
						for($j=0; $j<count($new_d1_d1[$i][0]); $j++){
							if($new_d1_d1[$i][0][$j][1] == $objs[1]->u_id){
								$new_d1_d1[$i][0][$j][0][0] = implode('', $objs[2]->enc_dec_str($new_d1_d1[$i][0][$j][0][0], false, 6, 3));
								$new_d1_d1[$i][0][$j][0][1] = implode('', $objs[2]->enc_dec_str($new_d1_d1[$i][0][$j][0][1], false, 6, 3));
								$loggedin_u = $new_d1_d1[$i][0][$j];
							}
						}
					}
					// print_r($loggedin_u[0][0]);
					echo "Welcome, ".$loggedin_u[0][0];
					echo "<br />";
					echo "Levels are Managed here.";
					echo "<br />";
					echo "<a href=".$_SERVER["PHP_SELF"]."?logout>Logout</a>";
			?>
					<div class="universal-tabs-container">
						<section class="universal-tabs-head">
							<a href="#" class="selected">Existing</a>
							<a href="#">New</a>
						</section>
						<section class="universal-tabs-body">
							<div class="universal-tabs-body-frame">
								<ul id="all-orgs">
			<?php
									$all_orgs = $objs[4]->findMultiple_by_id($loggedIn_u->room, "u_room");
									// print_r("We got here right?");
									while($rec = $objs[0][0]->get_selectedResults($all_orgs)){
										echo "<li>";
											echo "<span>";
												echo implode('', $objs[2]->enc_dec_str($rec['ref_name'], false, 6, 3));
											echo "</span>";
											echo "<a href='".$_SERVER["PHP_SELF"]."?req=4&on=".$rec['id']."'>Remove</a>";
											echo "<a href='".$_SERVER["PHP_SELF"]."?req=3&p_f=0&p_t=1&on=".$rec['id']."'>Enter</a>";
										echo "</li>";
									}
			?>
								<br style="clear: both;" />
								</ul>
							</div>
							<div class="universal-tabs-body-frame">
								<form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>?req=1">
									<input type="text" name="org-name" placeholder="Organization Name.." />
									<input type="submit" value="Create" />
								</form>
							</div>
						</section>
					</div>
			<?php
				}
			?>
		</div>
	</div>
	
<script type="text/javascript" src="_js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="_js/js_main.js"></script>
</body>
</html>
<?php
	// for($i=0; $i<count(get_db_data()); $i++){
	for($i=0; $i<count($objs[0]); $i++){
		$objs[0][$i]->close_connection();
	}
?>
<?php ob_end_flush(); ?>