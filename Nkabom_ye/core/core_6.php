<?php
	class parent_dataClass{
		
		// This retrieves the new id every time there's an insert		
		
		
		public static $errors = array();
		public static $success = array();
		
		
	
		// public static function find_all(){
		public function find_all(){
			global $old_objs;
			
			/* echo get_called_class();
			echo get_parent_class(); */
			
			// $sql = " select * from ".static::$table_name;
			$sql = " select * from ".$this->table_name;
			// $list_returned = static::$db_obj->all_Queries($sql);
			$list_returned = $this->db_obj->all_Queries($sql);
			
			return $list_returned;
		}
		
		// public static function find_by_id($this_id=0, $field_name=""){
		public function find_by_id($this_id=0, $field_name=""){
			global $old_objs;
			
			// $result_array = static::find_by_sql("select * from ".static::$table_name." where {$field_name}=".$old_objs[self::$sel_db]->escape_value($this_id)." limit 1");
			$result_array = $this->find_by_sql("select * from ".$this->table_name." where {$field_name}=".$this->db_obj->escape_value($this_id)." limit 1");
			return !empty($result_array) ? array_shift($result_array) : false;
		}
		
		// public static function findMultiple_by_id($this_id, $field_name){
		public function findMultiple_by_id($this_id, $field_name){
			global $old_objs;
			
			// $sql = "select * from ".static::$table_name." where {$field_name}=".static::$db_obj->escape_value($this_id);
			$sql = "select * from ".$this->table_name." where {$field_name}=".$this->db_obj->escape_value($this_id);
			// $list_returned = static::$db_obj->all_Queries($sql);
			$list_returned = $this->db_obj->all_Queries($sql);
			
			return $list_returned;
		}
		
		
		protected function has_attribute($table_field){			
			$object_vars = $this->attributes();
			return array_key_exists($table_field, $object_vars);
		}
		
		protected function sanitized_attributes(){
			global $old_objs;
			
			$clean_attributes = array();
			foreach($this->attributes() as $key => $value){
				// $clean_attributes[$key] = static::$db_obj->escape_value($value);
				$clean_attributes[$key] = $this->db_obj->escape_value($value);
			}
			return $clean_attributes;
		}
		
		
		public function create(){
			global $old_objs;
			$attributes = $this->sanitized_attributes();
			
			// $sql = "INSERT INTO ".static::$table_name." (";
			$sql = "INSERT INTO ".$this->table_name." (";
			$sql .= join(", ", array_keys($attributes));
			$sql .= ") VALUES ('";
			$sql .= join("', '", array_values($attributes));
			$sql .= "')";
			
			// static::$db_obj->all_Queries($sql);
			$this->db_obj->all_Queries($sql);
			$this->db_obj->mysql_call = false;
			// if(static::$db_obj->confirm_affectedRow()){
			if($this->db_obj->confirm_affectedRow()){
				// static::$new_id = static::$db_obj->get_lastID();
				$this->db_obj->new_id = $this->db_obj->get_lastID();
				
				$this->db_obj->mysql_call = true;
			}
			return $this->db_obj->mysql_call;
		}
		
		public function update($db_field="id"){
			global $old_objs;
			
			$attributes = $this->sanitized_attributes();
			$table_fields_pairs = array();
			foreach($attributes as $key => $value){
				$table_fields_pairs["{$key}='{$value}'"] = "{$key}='{$value}'";
			}
			
			// $sql = "UPDATE ".static::$table_name." SET ";
			$sql = "UPDATE ".$this->table_name." SET ";
			$sql .= join(", ", array_keys($table_fields_pairs));
			//$sql .= " WHERE {$db_field}=".$new_core_4->escape_value($this_id);
			$sql .= " WHERE {$db_field}=".$this->id;
			
			// static::$db_obj->all_Queries($sql);
			$this->db_obj->all_Queries($sql);
			
			// self::$mysql_call = (static::$db_obj->confirm_affectedRow()) ? true : false;
			$this->db_obj->mysql_call = ($this->db_obj->confirm_affectedRow()) ? true : false;
			return $this->db_obj->mysql_call;
		}
		
		// public function update_multiple($table="", $change_field="", $new_value="", $where_this_field="", $old_value, $val_1_type=true, $val_2_type=true){
		public function update_multiple($field_1, $field_2){
			global $old_objs;
			
			// $sql = "UPDATE ".$table." SET ";
			$sql = "UPDATE ".$this->table_name." SET ";
			// $sql .= "{$change_field}=";
			$sql .= "{$field_2[0]}=";
			/* if($val_1_type){ // String values
				$sql .= "'{$new_value}'";
			}else{
				$sql .= "{$new_value}";
			} */
			$sql .= "'{$field_2[1]}'";
			// $sql .= " WHERE {$where_this_field}=";
			$sql .= " WHERE {$field_1[0]}=";
			/* if($val_2_type){ // String values
				$sql .= "'{$old_value}'";
			}else{
				$sql .= "{$old_value}";
			} */
			$sql .= "'{$field_1[1]}'";
			// static::$db_obj->all_Queries($sql);
			$this->db_obj->all_Queries($sql);
			
			// self::$mysql_call = (static::$db_obj->confirm_affectedRow()) ? true : false;
			$this->db_obj->mysql_call = ($this->db_obj->confirm_affectedRow()) ? true : false;
		}
		
		public function delete($this_id=0, $db_field="id", $multiple=false){
			global $old_objs;
			
			$sql = "DELETE";
			// $sql .= " FROM ".static::$table_name;
			$sql .= " FROM ".$this->table_name;
			// $sql .= " WHERE {$db_field}=".static::$db_obj->escape_value($this_id);
			$sql .= " WHERE {$db_field}=".$this->db_obj->escape_value($this_id);
			if(!$multiple){
				$sql .= " LIMIT 1";
			}
			
			// static::$db_obj->all_Queries($sql);
			$this->db_obj->all_Queries($sql);
			
			// self::$mysql_call = (static::$db_obj->confirm_affectedRow()) ? true : false;
			$this->db_obj->mysql_call = ($this->db_obj->confirm_affectedRow()) ? true : false;
		}
	}
?>