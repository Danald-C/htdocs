<?php
	class core_7 extends parent_dataClass{
		
		// public static $db_obj;
		public $db_obj;
		// public static $table_name = "u_grps";
		public $table_name = "u_grps";
		// public static $table_fields;
		public $table_fields;
		
		function __construct(){
			global $old_objs;
			
			// self::$db_obj = $old_objs[0];
			$this->db_obj = $old_objs[0];
			// $this::$table_fields = self::$db_obj->get_tableFields($this::$table_name);
			$this->table_fields = $this->db_obj->get_tableFields($this->table_name);
			
			// foreach($this::$table_fields as $field){
			foreach($this->table_fields as $field){
				$this->$field = "";
			}
		}
		
		
		// public static function authenticate($password=""){
		public function authenticate($password=""){
			global $old_objs;
			
			// $pass = self::$db_obj->escape_value(sha1(trim($password)));
			$pass = $this->db_obj->escape_value(sha1(trim($password)));
			
			// $sql = "select * from ".self::$table_name;
			$sql = "select * from ".$this->table_name;
			$sql .= " where pw='".$pass."' ";
			$sql .= "limit 1";
			
			
			$result_array = self::find_by_sql($sql);
			$this_data = array(array(), 0);
			if(!empty($result_array)){
				$this_data = array(array_shift($result_array), 1);
			}
			return $this_data;
		}
		
		
		
		
		//**********************************************//
		//**********************************************//
		// Common new_core_4 Methods
		// public static function find_by_sql($sql=""){
		public function find_by_sql($sql=""){
			global $old_objs;
			
			// $result_set = self::$db_obj->all_Queries($sql);
			$result_set = $this->db_obj->all_Queries($sql);
			$object_array = array();
			// while($row = self::$db_obj->get_selectedResults($result_set)){
			while($row = $this->db_obj->get_selectedResults($result_set)){
				$object_array[] = self::instantiate($row);
			}
			return $object_array;
		}
		
		private static function instantiate($row){
			// Make a new instance of this class
			//$called_class = get_called_class();
			//$object = new $called_class;
			$object = new self;
			
			foreach($row as $table_field=>$value){
				if($object->has_attribute($table_field)){
					$object->$table_field = $value;
				}
			}
			return $object;
		}
		
		protected function attributes(){
			$attributes = array();
			// foreach(self::$table_fields as $field){
			foreach($this->table_fields as $field){
				if(property_exists($this, $field)){
					$attributes[$field] = $this->$field;
				}
			}
			return $attributes;
		}
	}
	$new_core_7 = new core_7();
?>