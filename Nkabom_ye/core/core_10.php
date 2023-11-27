<?php
	class core_10 extends parent_dataClass{
		
		public $db_obj;
		public $table_name;
		public $table_fields;
		
		function __construct($db_obj, $table_name){
			$this->db_obj = $db_obj;
			$this->table_name = $table_name;
			
			$this->table_fields = $this->db_obj->get_tableFields($this->table_name);
			
			foreach($this->table_fields as $field){
				$this->$field = "";
			}
		}
		
		
		
		
		//**********************************************//
		//**********************************************//
		// Common new_core_4 Methods
		// public static function find_by_sql($sql=""){
		public function find_by_sql($sql=""){
			$result_set = $this->db_obj->all_Queries($sql);
			$object_array = array();
			while($row = $this->db_obj->get_selectedResults($result_set)){
				// $object_array[] = self::instantiate($row);
				$object_array[] = $this->instantiate($row);
			}
			return $object_array;
		}
		
		// private static function instantiate($row){
		private function instantiate($row){
			// Make a new instance of this class
			//$called_class = get_called_class();
			//$object = new $called_class;
			// $object = new self;
			$object = new core_10($this->db_obj, $this->table_name);
			
			foreach($row as $table_field=>$value){
				if($object->has_attribute($table_field)){
					$object->$table_field = $value;
				}
			}
			return $object;
		}
		
		protected function attributes(){
			$attributes = array();
			foreach($this->table_fields as $field){
				if(property_exists($this, $field)){
					$attributes[$field] = $this->$field;
				}
			}
			return $attributes;
		}
	}
?>