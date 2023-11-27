<?php
	defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);
	
	// Find the full path into this directory 'core'
	defined('LIBRARY_PATH') ? null : define('LIBRARY_PATH', dirname(__FILE__));
	
	require_once(LIBRARY_PATH.DS."core_2.php");
	
	$this_path = get_root_path();
			
	defined('ROOT_PATH') ? null : define('ROOT_PATH', $this_path[0]);
	defined('ROOT_NAME') ? null : define('ROOT_NAME', $this_path[1]);
	
	for($i=3; $i<11; $i++){
		require_once(LIBRARY_PATH.DS."core_{$i}.php");
	}
	
	if($new_core_4->is_logged_in()){
	// if(isset($new_core_4->org_id)){
		$this_u = $new_core_7->find_by_id($new_core_4->u_id, "id");
		$get_orgs = $new_core_8->findMultiple_by_id($this_u->room, "u_room"); // Does this room yet have orgs?
		if($old_objs[0]->get_numOFrows($get_orgs) > 0){
			$data = get_db_data("", $this_u->room);
			for($i=0; $i<count($data); $i++){
				$this_obj = new core_3($data[$i]);
				$old_objs[] = $this_obj;
			}
		}
	}
	
	// [1]: Session, [2]: Misc Class, [3]: U grps, [4]: Orgs, [5]: Org_u
	$objs = array($old_objs, $new_core_4, $new_core_5, $new_core_7, $new_core_8, $new_core_9);
	require_once("core/core_1.php");
	
	set_up();
	
	ob_start();
	date_default_timezone_set('UTC');
?>