<?php
	function page_header(){
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
			
			<!-- <link rel="stylesheet" href="_modules/CROPPER/cropperjs/src/css/cropper.css"> -->
			
			<!-- <link rel="stylesheet" href="_modules/rcrop/dist/rcrop.min.css">
			<link rel="stylesheet" href="_modules/rcrop/dist/rcrop.css"> -->
		</head>
		<body class="w3-container w3-auto">
        <span class="scrollToTop">Up</span>
<?php
	}
	
	function page_footer(){
		global $objs;
?>
		<script type="text/javascript" src="_js/jquery-1.9.1.min.js"></script>
		<script type="text/javascript" src="_js/js_main.js"></script>
		
		<!-- <script type="text/javascript" src="_modules/CROPPER/cropperjs/src/js/cropper.js"></script>
		<script type="text/javascript" src="_modules/CROPPER/jquery-cropper/dist/jquery-cropper.js"></script> -->
		
		<!-- <script type="text/javascript" src="_modules/rcrop/dist/rcrop.min.js"></script> -->
		
		<!-- <script type="text/javascript" src="_js/bluebird.min.js"></script> -->
		<!-- <script type="text/javascript" src="_js/smartcrop.js"></script> -->
<?php
		if(isset($_GET['m-d']) && $_GET['m-d'] == 4 && $_GET['stage'] > 0){
?>
			<!-- imgAreaSelect placed here for causing interference on jQuery. -->
			<script type="text/javascript" src="_js/imgAreaSelect_plugins/jquery-pack.js"></script>
			<script type="text/javascript" src="_js/imgAreaSelect_plugins/jquery.imgareaselect.min.js"></script>
<?php
		}
?>
		</body>
		</html>
<?php
		for($i=0; $i<count($objs[0]); $i++){
			$objs[0][$i]->close_connection();
		}
		ob_end_flush();
	}
	
	function page_layout($page=1){
		global $objs;
		
		// $this_org = $objs[4]->find_by_id($objs[1]->org_id, "id");
		$this_org = $objs[4]->find_by_id($objs[1]->org_id[0], "id");
		$org_name = implode('', $objs[2]->enc_dec_str($this_org->ref_name, false, 6, 3)); // Decode before;
?>
		<div id="header-main" class="w3-row">
			<div id="page-nav" class="w3-twothird page-divs">
				<div>
					User Profile
				</div>
				<ul>
					<li>
						<a href="<?php echo $_SERVER["PHP_SELF"]."?p_f={$_GET['p_t']}&p_t=1&req=0" ?>">Home</a>
					</li>
					<li>
						<a href="<?php echo $_SERVER["PHP_SELF"]."?p_f={$_GET['p_t']}&p_t=2&req=0" ?>">Members</a>
						<ul>
							<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?p_f=1&p_t=2&m-d=2&d-s=1&on=0&req=0">New</a></li>
						</ul>
					</li>
					<li>
						<a href="<?php echo $_SERVER["PHP_SELF"]."?p_f={$_GET['p_t']}&p_t=3&req=0" ?>">Attendance</a>
					</li>
					<li>
						<a href="#">Group</a>
					</li>
					<li>
						<a href="#">Programs</a>
					</li>
					<li>
						<a href="#">Settings</a>
					</li>
					<li>
						<a href="<?php echo $_SERVER["PHP_SELF"]."?p_f={$_GET['p_t']}&p_t=0&req=0" ?>">Exit</a>
					</li>
				</ul>
			</div>
			<div id="page-main" class="w3-third page-divs">
				<h1>
					<?php
						echo $org_name;
					?>
				</h1>
				<div id="page-body">
					<?php
						if($page == 1){
							echo home();
						}
						if($page == 2){
							echo members();
						}
						if($page == 3){
							echo attendance();
						}
						if($page == 4){
							echo home();
						}
					?>
				</div>
			</div>
		</div>
		<div id="footer-main">
			<strong>Nkabom Ye</strong> software&trade; <?php echo $org_name." ".date("Y", time()); ?> All Rights Reserved.&reg;
		</div>
<?php
	}
	
	function home(){
?>
		<h2>Have a smooth control over your membership with <strong>Nkabom Ye</strong> Membership Software.</h2>
<?php
	}
	
	function members(){
		global $objs;
		global $currDB_obj;
		global $tbs_obj;
		
		if(isset($_GET['m-d']) && $_GET['m-d'] < 3){
			mem_form($_GET);
		}elseif(isset($_GET['m-d']) && $_GET['m-d'] == 3){
			$curr_state = mems_disp_state(0, array(0), 1); // Display State
			
			$mem_data_file = array(1, array(org_data($objs[1]->org_id[0])[1], "data_1"), "data_1.json"); // Org directory
			$mem_data = $objs[2]->file_data("", $mem_data_file, true);
			$get_data = get_lvl_grp(array(1, 0), $mem_data);
			
			$get_mems = get_mems_onType($get_data, $curr_state);
			// print_r($get_data[1][1][1][0][0]);
			
			$mem_rec_0 = $tbs_obj[0]->find_by_id($_GET["on"], "id");
			$mem_rec_1 = $tbs_obj[1]->find_by_id($mem_rec_0->id, "m_id");
			$mem_rec_2 = $tbs_obj[2]->find_by_id($mem_rec_0->id, "m_id");
			$mem_rec_3 = $tbs_obj[2]->find_by_id($mem_rec_0->id, "m_id");
			$mem_rec_8 = $tbs_obj[8]->find_by_id($mem_rec_0->id, "m_id");
			
			// $mem_rec_3 = $tbs_obj[3]->find_by_id($mem_rec_0->id, "m_id");
			$mem_conts = $tbs_obj[3]->findMultiple_by_id($mem_rec_0->id, "m_id");
			$cont_ids = array();
			while($rec = $currDB_obj->get_selectedResults($mem_conts)){
				$cont_ids[] = array($rec['contact'], $rec['active']);
			}
			
			$mem_rec_4 = $tbs_obj[4]->find_by_id($mem_rec_0->id, "m_id");
			$mem_rec_5 = $tbs_obj[5]->find_by_id($mem_rec_0->id, "m_id");
			$mem_rec_6 = $tbs_obj[6]->find_by_id($mem_rec_0->id, "m_id");
			$mem_rec_7 = $tbs_obj[7]->find_by_id($mem_rec_0->id, "m_id");
			
			$objs[2]->request_dir(array(1, array(org_data($objs[1]->org_id[0])[1], "data_8"), ""));
			$this_img = "..".DS."domains".DS.org_data($objs[1]->org_id[0])[1].DS."data_8".DS.$mem_rec_8->m_name;
			
			$u_status = array(u_status($mem_rec_0->id), "");
			if($u_status[0][1] == 1){ // Admin
				$u_status[1] = "red";
			}elseif($u_status[0][1] == 2){ // Super U
				$u_status[1] = "orange";
			}elseif($u_status[0][1] == 3){ // Ordinary U
				$u_status[1] = "green";
			}else{ // Just a mem
				$u_status[1] = "#fff";
			}
			
			$birthday = $objs[2]->count_down_in_month($mem_rec_0->dob);
			
			$display_state = array(0, 0, 0);
			/* for($i=0; $i<count($get_data[0]); $i++){
				if($get_data[0][$i][0]["id"] == $mem_rec_0->id){
					$display_state[0] = ($i > 0) ? $get_data[0][$i-1][0]["id"] : -1;
					$display_state[1] = $get_data[0][$i][0]["id"];
					$display_state[2] = ($i < count($get_data[0])-1) ? $get_data[0][$i+1][0]["id"] : -1;
				}
			} */
			for($i=0; $i<count($get_mems); $i++){
				if($get_mems[$i][0]["id"] == $mem_rec_0->id){
					$display_state[0] = ($i > 0) ? $get_mems[$i-1][0]["id"] : -1;
					$display_state[1] = $get_mems[$i][0]["id"];
					$display_state[2] = ($i < count($get_mems)-1) ? $get_mems[$i+1][0]["id"] : -1;
				}
			}
			$nav_1 = form_url($_SERVER['PHP_SELF'], array("p_f={$_GET['p_f']}", "p_t={$_GET['p_t']}", "m-d=3", "on={$display_state[0]}")); // Prev
			$nav_2 = form_url($_SERVER['PHP_SELF'], array("p_f={$_GET['p_f']}", "p_t={$_GET['p_t']}", "m-d=3", "on={$display_state[2]}")); // Next
			
			$nav_3 = form_url($_SERVER['PHP_SELF'], array("p_f={$_GET['p_f']}", "p_t={$_GET['p_t']}", "m-d=4", "stage=0", "on={$_GET['on']}", "sm=true")); // Profile Pic
?>
			<div id="view-mem-data" uid="<?php echo $_GET["on"]; ?>">
				<div class="mem-data">
					<div class="pagination"><div><a href="<?php echo $nav_1; ?>" style="display: <?php echo ($display_state[0] > -1) ? "display-block" : "none"; ?>;" title="Previous Member">&laquo; Previous Member</a></div><div><a href="<?php echo $nav_2; ?>" style="display: <?php echo ($display_state[2] > -1) ? "display-block" : "none"; ?>;" title="Next Member">Next Member &raquo;</a></div></div>
					<div>
						<a href='<?php echo $nav_3; ?>'>Profile Picture</a>
						<a href='#'>Relationship</a>
						<a href='#'>User</a>
					</div>
				</div>
				<div class="mem-data">
					<div class="mem-data-profile" style="<?php echo (empty($mem_rec_8->m_name) || $mem_rec_8->state == 0) ? "width: 255px; height: 255px;" : ""; ?> border: 3px solid <?php echo $u_status[1]; ?>;"><img src="<?php echo $this_img; ?>" /></div>
					<div class="mem-data-profile">
						<div id="<?php echo $mem_rec_7->data_progress; ?>" class="data-progress-bar profile-details"><span></span><br style='clear: both;' /></div>
						<div id="birthday" class="profile-details">
							<!-- <div class="birthday-layout-1 <?php //echo display_birthday($birthday)[1]; ?>">sdgertrhf</div> -->
							<div class="birthday-layout-2 <?php echo display_birthday($birthday)[1]; ?>"><?php echo display_birthday($birthday)[0]; ?></div>
						</div>
					</div>
					<br style="clear: both;" />
				</div>
				<div class="mem-data">
					<table>
						<tr>
							<td>Name: </td><td><?php echo $mem_rec_1->f_name." ".$mem_rec_1->o_name." ".$mem_rec_1->l_name; ?></td>
						</tr>
						<tr>
							<td>Date of birth: </td><td><?php echo $objs[2]->dateTo_string($mem_rec_0->dob)[0].($mem_rec_0->dob != "0000-00-00" ? " <strong>Age:</strong> ".$objs[2]->ageFrom_dob($mem_rec_0->dob)." years old." : ""); ?></td>
						</tr>
						<tr>
							<td>Nationality: </td><td><?php echo $mem_rec_0->nationality; ?></td>
						</tr>
						<tr>
							<td>Marital Status: </td><td><?php echo mem_gen_status($mem_rec_0->marital_status, 1); ?></td>
						</tr>
						<tr>
							<td>Currently lives in: </td><td><?php echo $mem_rec_5->h_address.", ".$mem_rec_5->city.", ".$mem_rec_5->state_province." - ".$mem_rec_5->nation; ?></td>
						</tr>
						<tr>
							<td>Occupation / Profession: </td><td><?php echo $mem_rec_0->occupation; ?></td>
						</tr>
						<tr id="contact" class="contacts">
							<td>Contact Numbers: </td><td></td>
						</tr>
						<tr id="email" class="contacts">
							<td>Email: </td><td></td>
						</tr>
						<tr>
							<td>Dedication: </td><td><?php echo "On: ".($mem_rec_2->dedi_date != "0000-00-00" ? $objs[2]->dateTo_string($mem_rec_2->dedi_date)[0] : "<em>None</em>")."; By: ".(!empty($mem_rec_2->dedi_by) ? $mem_rec_2->dedi_by : "<em>None</em>"); ?></td>
						</tr>
						<tr>
							<td>Baptismal: </td><td><?php echo "On: ".($mem_rec_3->bapt_date != "0000-00-00" ? $objs[2]->dateTo_string($mem_rec_3->bapt_date)[0] : "<em>None</em>")."; By: ".(!empty($mem_rec_3->bapt_by) ? $mem_rec_3->bapt_by : "<em>None</em>"); ?></td>
						</tr>
						<tr>
							<td>Register: </td>
							<td>
<?php
								$mem_reg = $mem_rec_6->findMultiple_by_id($mem_rec_0->id, "m_id");
								while($rec = $currDB_obj->get_selectedResults($mem_reg)){
									$reg_u = ($rec['taken_by'] > 0) ? $tbs_obj[1]->find_by_id($rec['taken_by'], "m_id") : "";
									
									$action = "<strong style='color: ";
									if($rec['type'] == 0 || $rec['type'] == 2 || $rec['type'] == 3){
										switch($rec['type']){
											case 0:
												$action .= "red";
												break;
											case 2:
												$action .= "orange";
												break;
											default:
												$action .= "#000";
										}
									}else{
										$action .= "green";
									}
									$action .= ";'>";
									$init = "Joined";
									/* if($rec['type'] == 0){ // Was performed by Sys
										$action = "Disjoined";
									}elseif($rec['type'] == 2){ // Was performed by U: Mem is Alive.
										$action = "Disjoined";
									}elseif($rec['type'] == 3){ // Was performed by U: Mem is Dead.
										$action = "Disjoined";
									} */
									if($rec['type'] == 0 || $rec['type'] == 2 || $rec['type'] == 3){ // Was performed by Sys
										$init = "Disjoined";
									}
									$action .= $init;
									$action .= "</strong>";
									
									$action .= ($rec['type'] == 3) ? " <em>by death</em>" : "";
									
									$action .= " on ";
									$action .= $objs[2]->dateTo_string($rec['date'])[1];
									$action .= " by ";
									$action .= ($rec['taken_by'] == 0) ? "System" : $reg_u->f_name." ".(!empty($reg_u->o_name) ? $reg_u->o_name." " : "").$reg_u->l_name;
									$action .= "<br />";
									echo $action;
								}
?>
							</td>
						</tr>
					</table>
				</div>
				<div class="mem-data">
					<a href="<?php echo $_SERVER['PHP_SELF']."?p_f={$_GET['p_f']}&p_t={$_GET['p_t']}&m-d=1&d-s=1&req=1&on={$mem_rec_0->id}"; ?>">Update</a>
					<a href="<?php echo $_SERVER['PHP_SELF']."?p_f={$_GET['p_f']}&p_t={$_GET['p_t']}"; ?>">Close</a>
				</div>
			</div>
<?php
		}elseif(isset($_GET['m-d']) && $_GET['m-d'] == 4){
			$objs[2]->request_dir(array(1, array(org_data($objs[1]->org_id[0])[1], "data_8"), ""));
			$mem_rec_8 = $tbs_obj[8]->find_by_id($_GET["on"], "m_id");
			$request = array("p_f={$_GET['p_f']}", "p_t={$_GET['p_t']}", "on={$_GET['on']}");
			if($_GET['stage'] == 0){
				$request[] = "m-d={$_GET['m-d']}";
				$request[] = "req=2";
				$request[] = "stage=1";
				$nav = form_url($_SERVER['PHP_SELF'], $request);
				echo "<h4>Upload a Profile Photo</h4>";
?>
				<form action="<?php echo $nav; ?>" method="post" id="upload-img" enctype="multipart/form-data">
					<div class="universal-tabs-container">
						<section class="universal-tabs-head">
							<a href="#" class="selected">Computer</a>
							<a href="#">Camera</a>
						</section>
						<section class="universal-tabs-body">
							<div class="universal-tabs-body-frame">
								<input type="file" name="img" />
								<p>Drag your files here or click in this area.</p>
								<button type="submit">Upload</button>
							</div>
						</section>
					</div>
				</form>
<?php
			}
			if($_GET['stage'] == 1){
				$request[] = "m-d={$_GET['m-d']}";
				$request[] = "stage=2";
				$nav = form_url($_SERVER['PHP_SELF'], $request);
				echo "<h4>We're ready to crop..</h4>";
				// $objs[2]->init_cropping($mem_rec_8);
				$get_imgSize = $objs[2]->get_imgSize($objs[2]->get_dir.DS.$mem_rec_8->l_name);
				// $objs[2]->crop_largeImg($mem_rec_8, $get_imgSize);
				
				// echo $objs[2]->get_dir.DS.$mem_rec_8->l_name;
				// $this_img = "../domains/".org_data($objs[1]->org_id[0])[1]."/data_8/".$mem_rec_8->l_name;
				$this_img = "..".DS."domains".DS.org_data($objs[1]->org_id[0])[1].DS."data_8".DS.$mem_rec_8->l_name;
?>
				<div id="img-selection">
					<div id="img-selection-medium"><img src="<?php echo $this_img; ?>" /></div>
					<div id="img-selection-small"><img src="<?php echo $this_img; ?>" /></div>
				</div>
				<div id="large_image" imgsel="<?php echo $_GET['stage']; ?>" class="large_image">
					<!-- <img src="<?php //echo $this_img; ?>" id="select_smallImg" width="<?php //echo $get_imgSize[0]; ?>" height="<?php //echo $get_imgSize[1]; ?>" /> -->
					<img src="<?php echo $this_img; ?>" id="select_smallImg" />
					<!--The drawing guide-->
					<span wt="<?php echo $get_imgSize[0]; ?>" ht="<?php echo $get_imgSize[1]; ?>"></span>
				</div>
				<form id="crop-img" method="post" action="<?php echo $nav; ?>">
					<input type="hidden" name="x1-1" value="" id="x1-1" />
					<input type="hidden" name="y1-1" value="" id="y1-1" />
					<input type="hidden" name="x2-1" value="" id="x2-1" />
					<input type="hidden" name="y2-1" value="" id="y2-1" />
					<input type="hidden" name="w-1" value="" id="w-1" />
					<input type="hidden" name="h-1" value="" id="h-1" />
					
					<input type="hidden" name="x1-2" value="" id="x1-2" />
					<input type="hidden" name="y1-2" value="" id="y1-2" />
					<input type="hidden" name="x2-2" value="" id="x2-2" />
					<input type="hidden" name="y2-2" value="" id="y2-2" />
					<input type="hidden" name="w-2" value="" id="w-2" />
					<input type="hidden" name="h-2" value="" id="h-2" />
					
					<input type="submit" value="Save Crop" />
				</form>
<?php
			}
			if($_GET['stage'] == 2){
				$request[] = "m-d=3";
				$request[] = "sm=true";
				// Get the new coordinates to crop the image.
				$x1_1 = $_POST["x1-1"];
				$y1_1 = $_POST["y1-1"];
				$x2_1 = $_POST["x2-1"];
				$y2_1 = $_POST["y2-1"];
				$w_1 = $_POST["w-1"];
				$h_1 = $_POST["h-1"];
				
				$x1_2 = $_POST["x1-2"];
				$y1_2 = $_POST["y1-2"];
				$x2_2 = $_POST["x2-2"];
				$y2_2 = $_POST["y2-2"];
				$w_2 = $_POST["w-2"];
				$h_2 = $_POST["h-2"];
				
				// $objs[2]->crop_img($w_2, $h_2, $x1_2, $y1_2, $objs[2]->get_dir.DS.$mem_rec_8->s_name, $objs[2]->get_dir.DS.$mem_rec_8->l_name);
				$medium = array($x1_1, $y1_1, $x2_1, $y2_1, $w_1, $h_1);
				$small = array($x1_2, $y1_2, $x2_2, $y2_2, $w_2, $h_2);
				$imgs = array($objs[2]->get_dir.DS.$mem_rec_8->l_name, $objs[2]->get_dir.DS.$mem_rec_8->m_name, $objs[2]->get_dir.DS.$mem_rec_8->s_name);
				$objs[2]->crop_img($imgs, $medium, $small);
				
				$mem_data = mem_data_toFile($mem_rec_8->m_id, $tbs_obj);
				redirect_to(form_url($_SERVER['PHP_SELF'], $request));
			}
		}else{
			// print_r($get_data[1][1]);
			mem_list_structure(array(array(0, 0), array(0, 1)), array(), array(array(2, 0)));
		}
	}
	
	function attendance(){
		$a_links = array(5, array(), array("red", "blue", "green", "yellow", "orange"));
		mem_list_structure(array(array(0, 0, array()), array(0, 2, $a_links)), array(), array(array(2, 0, array())));
	}
	
	function mem_list_structure($top=array(), $middle=array(), $bottom=array()){
?>
		<div id="mems-top" class="mems-list">
			<div>
<?php
				for($i=0; $i<count($top); $i++){
					($top[$i][0] == 0 && $top[$i][1] == 0) ? mem_list_controls() : mem_list_controls($top[$i][0], $top[$i][1], $top[$i][2]);
				}
?>
			</div>
			<div></div>
		</div>
		<div id="mems-middle" class="mems-list">
			<!-- <div class="content"><ul></ul></div>
			<div class="content"><ul></ul></div> -->
		</div>
		<div id="mems-bottom" class="mems-list">
<?php
			for($i=0; $i<count($bottom); $i++){
				($bottom[$i][0] == 0 && $bottom[$i][1] == 0) ? mem_list_controls() : mem_list_controls($bottom[$i][0], $bottom[$i][1], $bottom[$i][2]);
			}
?>
		</div>
<?php
	}
	
	function mem_list_controls($section=0, $type=0, $options=array()){
		global $objs;
		
		$curr_state = mems_disp_state(0, array(0), 0); // Display State
		$mem_data_file = array(1, array(org_data($objs[1]->org_id[0])[1], "data_1"), "data_1.json"); // Org directory
		$mem_data = $objs[2]->file_data("", $mem_data_file, true);
		$get_data = get_lvl_grp(array(1, 0), $mem_data);
		$get_mems = get_mems_onType($get_data, $curr_state);
		
		if($section == 0){ // Top
			if($type == 0){
?>
				<div class='mem-count'>0</div>
				<div class='mem-count'>0</div>
<?php
			}
			if($type == 1){
?>
				<select>
					<option value=0 <?php echo ($curr_state[0] == 0) ? "selected" : ""; ?> >All Members</option>
					<option value=1 <?php echo ($curr_state[0] == 1) ? "selected" : ""; ?> >Active Members</option>
					<option value=2 <?php echo ($curr_state[0] == 2) ? "selected" : ""; ?> >Inactive Members</option>
					<option value=3 <?php echo ($curr_state[0] == 3) ? "selected" : ""; ?> >Superiors (Those in-charge)</option>
				</select>
				<div class='mems-type'>
					<div class='all-mems'>
						<a href='#' title='All Active Members..'></a>
						<a href='#' title='All Inactive Members..'></a>
					</div>
					<div class='all-actives'>
						<a href='#' title='Admin..'></a>
						<a href='#' title='All Super Users..'></a>
						<a href='#' title='All Ordinary Users..'></a>
						<a href='#' title='All Members (Who are not Users)..'></a>
					</div>
					<div class='all-inactives'>
						<a href='#' title='Manually turned off (Member is alive)..'></a>
						<a href='#' title='Manually turned off (Member is dead)..'></a>
						<a href='#' title='Automatically turned off (Member is alive/dead)..'></a>
					</div>
					<div class='all-superiors'>
						<a href='#' title=''></a>
					</div>
				</div>
<?php
			}
			if($type == 2){
?>
				<!-- <a class='switch-view' href="#" clr='#00990f' title="Switch view">1</a> -->
<?php
				for($i=0; $i<$options[0]; $i++){
					echo "<a id='".$i."' class='switch-view' href='#' style='' clr='{$options[2][$i]}' title='Switch view'>1</a>";
				}
?>
				<a id='switch-view-save' href="#" title="Save Progress">Save</a>
				<a id='switch-view-clear' href="#" title="Clear Progress">Clear</a>
<?php
			}
		}
		
		if($section == 1){ // Middle
		}
		
		if($section == 2){ // Bottom
			if($type == 0){
?>
				<div class="pagination"><div><a href="#" title="Previous Set">&laquo;</a></div><div><a href="#" title="Next Set">&raquo;</a></div></div>
<?php
			}
		}
	}
	
	function mem_form($request){
		global $objs;
		global $currDB_obj;
		global $tbs_obj;
		global $new_d1_d1;
		global $this_org;
?>
		<div class="w3-row">
			<div id="<?php echo $request['d-s']; ?>" class="w3-twothird">
<?php
				$mem_state = array($request['m-d'], 1);
				$form_id = array();
				for($i=0; $i<4; $i++){
					$form_id[] = "d_s_{$i}";
				}
				
				$m_d = 0;
				if($mem_state[0] == 1){
					$mem_rec_0 = $tbs_obj[0]->find_by_id($request['on'], "id");
					
					$has_multiple = array(array(0, ""), array(0, ""));
					
					// Names
					$mem_rec_1 = $tbs_obj[1]->find_by_id($mem_rec_0->id, "m_id");
					$mem_rec_2 = $tbs_obj[2]->find_by_id($mem_rec_0->id, "m_id");
					
					// $mem_rec_3 = $tbs_obj[3]->find_by_id($mem_rec_0->id, "m_id");
					$mem_conts = $tbs_obj[3]->findMultiple_by_id($mem_rec_0->id, "m_id");
					$cont_id = array($mem_rec_0->id, "m_id");
					while($rec = $currDB_obj->get_selectedResults($mem_conts)){
						if($rec['active'] == 1){
							$cont_id = array($rec['id'], "id");
							$has_multiple[0][1] = $rec['contact'];
						}
						$has_multiple[0][0] += 1;
					}
					$mem_rec_3 = $tbs_obj[3]->find_by_id($cont_id[0], $cont_id[1]);
					
					// $mem_rec_4 = $tbs_obj[4]->find_by_id($mem_rec_0->id, "m_id");
					$mem_email = $tbs_obj[4]->findMultiple_by_id($mem_rec_0->id, "m_id");
					$email_id = array($mem_rec_0->id, "m_id");
					while($rec = $currDB_obj->get_selectedResults($mem_email)){
						if($rec['active'] == 1){
							$email_id = array($rec['id'], "id");
							$has_multiple[1][1] = $rec['email'];
						}
						$has_multiple[1][0] += 1;
					}
					$mem_rec_4 = $tbs_obj[4]->find_by_id($email_id[0], $email_id[1]);
					
					$mem_rec_5 = $tbs_obj[5]->find_by_id($mem_rec_0->id, "m_id");
					$mem_rec_5 = $tbs_obj[5]->find_by_id($mem_rec_0->id, "m_id");
					
					// State
					$mem_rec_7 = $tbs_obj[7]->find_by_id($mem_rec_0->id, "m_id");
					// $mem_state[1] = $mem_rec_7->data_progress;
					$mem_state[1] = $request['d-s'];
					
					$first_u = who_is_this($tbs_obj, $mem_rec_0->id);
					
					$email = $mem_rec_4->email;
					$contact = $mem_rec_3->contact;
					if($first_u[0] == 1){
						$email = $first_u[1];
						$contact = $first_u[2];
						
						// Get the active one instead
						// Just 1 member but multiple contacts
						if($has_multiple[0][0] > 1){
							$contact = $has_multiple[0][1];
						}
						// Just 1 member but multiple emails
						if($has_multiple[1][0] > 1){
							$email = $has_multiple[1][1];
						}
						echo "Hi, your organization's environment is ready. You're the Admin. Please because you're the admin, make sure to complete your registration below.";
					}else{
						// Get the active one instead
						// Just 1 member but multiple contacts
						if($has_multiple[0][0] > 1){
							$contact = $has_multiple[0][1];
						}
						// Just 1 member but multiple emails
						if($has_multiple[1][0] > 1){
							$email = $has_multiple[1][1];
						}
					}
					
					$m_d = $request['m-d'];
?>
					<h3>Update Member's Data</h3>
					<!-- http://localhost/Nkabom_Ye/home/page_2.php?p_f=1&p_t=2&m-d=1&d-s=1&on=1&sm=true -->
<?php
				}else{
?>
					<h3>New Member Data</h3>
<?php
				}
				
				$prev = $request['d-s']-1; // Prev
				$nxt = $request['d-s']+1; // Next
				$nav_1 = form_url($_SERVER['PHP_SELF'], array("p_f={$request['p_f']}", "p_t={$request['p_t']}", "m-d={$m_d}", "d-s={$prev}", "on={$request['on']}")); // Prev
				
				$nav_2 = form_url($_SERVER['PHP_SELF'], array("p_f={$request['p_f']}", "p_t={$request['p_t']}", "m-d={$m_d}", "d-s={$nxt}", "req=1", "on={$request['on']}")); // Next
				
				$def_page = array("p_f={$request['p_f']}", "p_t={$request['p_t']}", "sm=true");
				if($request['on'] != 0){
					$def_page[] = "m-d=3";
					$def_page[] = "on={$request['on']}";
				}
				
				// FORM PROGRESS
				if($mem_state[0] == 1){
					for($i=1; $i<5; $i++){
						$nav_3 = form_url($_SERVER['PHP_SELF'], array("p_f={$request['p_f']}", "p_t={$request['p_t']}", "m-d={$m_d}", "d-s={$i}", "req=1", "on={$request['on']}")); // Next
						echo ($i < $mem_rec_7->data_progress) ? "<a href='".($i != $mem_state[1] ? $nav_3 : "#")."'" : "<span";
						echo " class='data-progress";
						echo ($i < $mem_rec_7->data_progress) ? " steps-1" : " steps-0";
						echo ($i == $mem_state[1]) ? " active" : "";
						echo ($i < $mem_rec_7->data_progress) ? "'></a>" : "'></span>";
					}
				}
				$nav_4 = form_url($_SERVER['PHP_SELF'], $def_page); // Finish
?>
				<!-- Those to validate
				NATIONALITY -->
				<div id="mem-form-data-lvl-container" mid="<?php echo $request['on']; ?>">
					<form method="post" action="<?php echo $nav_2; ?>" id="<?php echo $form_id[0]; ?>" class="mem-form-data-lvl-sections" style="display: 
<?php
						// $mem_state [0] 1/0 update/new; [1] 1/0 data state/level
						echo ($mem_state[1] == 1) ? "block" : "none";
?>
					;">
						<h3>Basic</h3>
						<span id="data-set-1">
							<input type="text" name="f_name" placeholder="<?php echo ($mem_state[0] == 1 && !empty($mem_rec_1->f_name)) ? $mem_rec_1->f_name : "Firstname"; ?>" />
							<input type="text" name="o_name" placeholder="<?php echo ($mem_state[0] == 1 && !empty($mem_rec_1->o_name)) ? $mem_rec_1->o_name : "Othernames"; ?>" />
							<input type="text" name="l_name" placeholder="<?php echo ($mem_state[0] == 1 && !empty($mem_rec_1->l_name)) ? $mem_rec_1->l_name : "Lastname"; ?>" />
						</span>
						<span id="data-set-2">
							<input type="radio" name="gender" value="M" checked />Male
							<input type="radio" name="gender" value="F" <?php echo ($mem_state[0] == 1 && $mem_rec_0->gender == 'F') ? "checked" : ""; ?> />Female
						</span>
						<span id="data-set-3">
							<label>
								<input type="date" name="dob" value="<?php echo ($mem_state[0] == 1) ? $mem_rec_0->dob : ""; ?>" />Your date of birth
							</label>
						</span>
						<span id="data-set-4">
<?php
							$country_file = array(3, "data_1", "country.json");
							$country = $objs[2]->file_data("", $country_file, true);
								// print_r($country[1]);
?>
							<select name="nationality">
								<option value="">Nationality</option>
<?php
								foreach($country[0] as $key => $value){
									echo "<option value='{$value}' ";
									echo ($mem_state[0] == 1 && $mem_rec_0->nationality == $value) ? "selected" : "";
									echo ">{$value}</option>";
								}
?>
							</select> Country where you identify.
						</span>
						<span id="data-set-5">
							<select name="marital_status">
								<!-- <option value="">Marital Status</option> -->
<?php
								$mar_stat = array("Single", "Married", "Divorced/Separated", "Widow");
								for($i=0; $i<count($mar_stat); $i++){
									echo "<option value={$i} ";
									echo ($mem_state[0] == 1 && $mem_rec_0->marital_status == $i) ? "selected" : "";
									echo ">{$mar_stat[$i]}</option>";
								}
?>
							</select> Marital Status
						</span>
					</form>
					<form method="post" action="<?php echo $nav_2; ?>" id="<?php echo $form_id[1]; ?>" class="mem-form-data-lvl-sections" style="display: <?php echo ($mem_state[1] == 2) ? "block" : "none"; ?>;">
						<h3>Residence</h3>
						<span>
							<select name="nation">
								<option>Nation</option>
<?php
								foreach($country[0] as $key => $value){
									echo "<option value='{$value}' ";
									echo ($mem_state[0] == 1 && $mem_rec_5->nation == $value) ? "selected" : "";
									echo ">{$value}</option>";
								}
								// Atomic-Hills Kwabenya
								// AQ1 GARLIC ST GE-222-2626
								// Energy Advisor
								// +233246545040
?>
							</select> Country where you currently live
						</span>
						<span>
							<input type="text" name="state_province" placeholder="<?php echo ($mem_state[0] == 1 && !empty($mem_rec_5->state_province)) ? $mem_rec_5->state_province : "State/Province"; ?>" />
						</span>
						<span>
							<input type="text" name="city" placeholder="<?php echo ($mem_state[0] == 1 && !empty($mem_rec_5->city)) ? $mem_rec_5->city : "City/Town"; ?>" />
						</span>
						<span>
							<input type="text" name="h_address" placeholder="<?php echo ($mem_state[0] == 1 && !empty($mem_rec_5->h_address)) ? $mem_rec_5->h_address : "Your Address"; ?>" />
						</span>
						<span>
							<input type="text" name="occupation" placeholder="<?php echo ($mem_state[0] == 1 && !empty($mem_rec_0->occupation)) ? $mem_rec_0->occupation : "What work do you do?"; ?>" />
						</span>
						<span>
							<input type="tel" name="contact" value="<?php echo ($first_u[0] == 1 || $mem_state[0] == 1) ? $contact : ""; ?>" placeholder="<?php echo (!empty($contact)) ? $contact : "Your Contact (xxxxxxxxxx)"; ?>" />
						</span>
						<span>
							<input type="email" name="email" value="<?php echo ($first_u[0] == 1 || $mem_state[0] == 1) ? $email : ""; ?>" placeholder="<?php echo (!empty($email)) ? $email : "Your email (name@example.com)"; ?>" />
						</span>
					</form>
					<form method="post" action="<?php echo $nav_2; ?>" id="<?php echo $form_id[2]; ?>" class="mem-form-data-lvl-sections" style="display: 
<?php
						echo ($mem_state[1] == 3) ? "block" : "none";
?>
					;">
						<h3>Other Details</h3>
						<span>
							<label>
								<input type="date" name="dedi_date" value="<?php echo ($mem_state[0] == 1 && $mem_rec_2->dedi_date != "0000-00-00") ? $mem_rec_2->dedi_date : ""; ?>" placeholder="Date when you were dedicated." />Date when you were dedicated.
							</label>
						</span>
						<span>
							<input type="text" name="dedi_by" placeholder="<?php echo ($mem_state[0] == 1 && !empty($mem_rec_2->dedi_by)) ? $mem_rec_2->dedi_by : "Who dedicated you?"; ?>" />
						</span>
						<span>
							<label>
								<input type="date" name="bapt_date" value="<?php echo ($mem_state[0] == 1 && $mem_rec_2->bapt_date != "0000-00-00") ? $mem_rec_2->bapt_date : ""; ?>" />Date when you were Baptized.
							</label>
						</span>
						<span>
							<input type="text" name="bapt_by" placeholder="<?php echo ($mem_state[0] == 1 && !empty($mem_rec_2->bapt_by)) ? $mem_rec_2->bapt_by : "Who baptized you?"; ?>" />
						</span>
					</form>
					<form method="post" action="<?php echo $nav_2; ?>" id="<?php echo $form_id[3]; ?>" class="mem-form-data-lvl-sections" style="display: 
<?php
						echo ($mem_state[1] == 4) ? "block" : "none";
?>
					;">
						<h3>Monitoring Options</h3>
						<span>
							<label>
								<input type="checkbox" name="auto_off" <?php echo ($mem_state[0] == 1 && $mem_rec_7->auto_off == 1 || $mem_state[0] == 0) ? "checked" : ""; ?> /> Auto <strong>Turn-Off</strong> <em>When this is turned on, this member's account will turn off (Not Delete) when found to be inactive after a period of a specified time.</em>
							</label>
						</span>
					</form>
				</div>
				<div id="mem-form-data-lvl-nav">
					<form method="post" action="<?php echo $nav_1; ?>">
						<input type="submit" id="prev" value="Previous" style="display: <?php echo ($prev > 0) ? "inline-block" : "none"; ?>;" />
					</form>
					<input type="button" id="nxt" value="<?php echo ($nxt >= 5) ? "Finish" : "Next"; ?>" form="<?php echo $form_id[$mem_state[1]-1]; ?>" />
					<input type="reset" id="reset" value="Reset" form="<?php echo $form_id[$mem_state[1]-1]; ?>" />
<?php
					if($mem_state[1] < 4){
						echo "<a href='{$nav_4}'>Finish</a>";
					}
?>
				</div>
			</div>
			<div class='w3-third'>
				<h3>View Member's Data</h3>
			</div>
		</div>
		<div id="mem-form-data-verify"></div>
<?php
	}
	
	function mem_list(){
		global $objs;
		global $tbs_obj;
		global $new_d1_d1;
		global $this_org;
		
		
	}
?>