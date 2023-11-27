<?php
	
	class core_3{
		
		private $connection;
		// private $connection = array();
		public $last_query;
		public $get_data;
		
		public $new_id;
		public $mysql_call = false;
		
		// Escape special characters
		private $magic_quotes_active;
		private $mysql_real_escape_string_exist;
		
		// Run the following first automatically
		// function __construct(){
		function __construct($init_db){
			// print_r($init_db);
			// Run the connection
			$this->get_data = $init_db;
			$this->open_connection();
			// $this->open_connection($init_db);
			
			// Turn on Magic Quotes
			$this->magic_quotes_active = (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) ? get_magic_quotes_gpc() : false;
			
			// Only if this function exists in this version of PHP
			$this->mysql_real_escape_string_exist = function_exists( "mysql_real_escape_string" );
		}
		
		// public function open_connection(){
		public function open_connection(){
			$this->connection = new mysqli($this->get_data[0][0], $this->get_data[0][1], $this->get_data[0][2], $this->get_data[0][3]);
			/* echo "<br /><br />";
			print_r($this->connection[1]); */
			
			// If this is used, the else statement can be ignored.
			//$this->connection = mysqli_connect(SERVER, USER, PASS, DATABASE);
			// $this->connection = new mysqli(SERVER, USER, PASS, DATABASE);
			// if(mysqli_connect_errno()){
			if($this->connection->connect_errno){
				die("Failed to connect to the database.".mysqli_connect_error());
			}
		}
		
		public function request_conn(){
		// public function request_conn($db_i){
			return $this->connection;
			// return $this->connection[$db_i];
		}
		
		public function close_connection(){
			if(isset($this->connection)){
				mysqli_close($this->connection);
				unset($this->connection);
			}
		}
		
		
		
		//**********************************************//
		//**********************************************//
		// Common Database Methods
		public function all_Queries($query){
			$this->last_query = $query;
			$result = mysqli_query($this->connection, $query);
			// $result = mysqli_query($this->connection[$this->sel_db], $query);
			
			// Was this query successful?
			$this->query_status($result);
			
			return $result;
		}
		
		private function query_status($result){
			if(!$result){
				$output = "<strong>Sorry, this request has failed:</strong> ".mysqli_error($this->connection)."<br /><br />";
				// $output = "<strong>Sorry, this request has failed:</strong> ".mysqli_error($this->connection[self::$sel_db])."<br /><br />";
				$output .= "<strong>You requested for:</strong> ".$this->last_query;
				die($output);
			}
		}
		
		public function escape_value( $value ){
			if( $this->mysql_real_escape_string_exist ){
				// PHP v4.3.0 or higher
				// Undo any magic quote effects so mysql_real_escape_string can do the work
				if( $this->magic_quotes_active ){
					$value = stripslashes( $value );
				}
				// Get the stripped slashes value into mysql_real_escape_string()
				$value = mysql_real_escape_string( $value );
			}else{
				// before PHP v4.3.0
				// If magic quotes aren't already on then add slashes manually
				if( !$this->magic_quotes_active ){
					$value = addslashes( $value );
				}
				// If magic quotes are active, then the slashes already exist
			}
			return $value;
		}
			
		public function all_tables(){
			$tables = array();
			$records = $this->all_Queries('SHOW TABLES');
			if ( $this->get_numOFrows($records) == 0 )
				return false;
			
			while ( $record = @mysqli_fetch_row($records) ) {
				$tables[] = $record[0];
			}
			
			return $tables;
		}
		
		public function get_tableFields($table){
			$fields = array();
			
			$sql = "SHOW FIELDS FROM ".$table;
			$records = $this->all_Queries($sql);
				
			while($result = $this->get_associativeResults($records)){
				$fields[] = $result["Field"];
			}
			return $fields;
		}
		
		public function backup_db_1($backup){
			$dir = ROOT_PATH.DS.'logs'.DS.'backups'.DS;
			
			$backupdir_1 = $dir . DATABASE . "_".date("Y-m-d") . '.sql.zip';
			
			//Creates a new instance of MySQLDump: it exports a compressed and base-16 file
			$dumper_1 = new MySQLDump(DATABASE, $backupdir_1, true, $hex=false);
			
			//Use this for plain text and not compressed file
			//$dumper = new MySQLDump(DATABASE, $backupdir, false, false);
			
			//Dumps all the database
			//return ($dumper->doDump()) ? true : false;
			  
			//Dumps all the database structure only (no data)
			//$dumper->getDatabaseStructure();
			
			//Dumps all the database data only (no structure)
			//$dumper->getDatabaseData();
			
			//Dumps "mytable" table structure only (no data)
			//$dumper->getTableStructure('adminlogin');
			
			//Dumps "mytable" table data only (no structure)
			//$dumper->getTableData('adminlogin');
			
			if($backup){
				//$dumper_1->getTableStructure('members');
				//$dumper_1->getTableData('members');
				$dumper_1->getDatabaseStructure();
				$dumper_1->getDatabaseData();
				
				$dumper_2->getDatabaseStructure();
				$dumper_2->getDatabaseData();
				return true;
			}else{
				return false;
			}
		}
		
		# Param 1: Boolean true/false
		# Param 2: array() 0/1 zip/sql
		public function backup_db_2($backup, $file_names){
			$dir_1 = ROOT_PATH.DS.'logs'.DS.'backups'.DS;
			
			$backupdir_1_1 = $dir_1 . $file_names[0]; // Zip for root
			$backupdir_1_2 = $dir_1 . $file_names[1]; // Sql for root
			
			// Creates a new instance of dumpFactory:
			// it exports a compressed and base-16 file
			// Param 2 Compress
			// Param 3 With/Without data
			$dumper_1_1 = new dumpFactory($backupdir_1_1, true, true);
			$dumper_1_2 = new dumpFactory($backupdir_1_2, false, true);
			$dumper_2 = new dumpFactory($backupdir_2, true, true);
			
			if($backup){
				$dumper_1_1->dumpAll();
				$dumper_1_2->dumpAll();
				$dumper_2->dumpAll();
				//$dumper->table_fields($tables="members");
				return true;
			}else{
				return false;
			}
		}
		
		public function get_lastID(){
			return mysqli_insert_id($this->connection);
			// return mysqli_insert_id($this->connection[$this->sel_db]);
		}
		
		public function get_numOFrows($result_set){
			return mysqli_num_rows($result_set);
		}
		
		public function get_selectedResults($result_set){
			return mysqli_fetch_array($result_set);
		}
		
		public function get_associativeResults($result_set){
			return mysqli_fetch_assoc($result_set);
		}
		
		public function get_objectResults($result_set){
			return mysqli_fetch_object($result_set);
		}
		
		private function get_affectedRow(){
			return mysqli_affected_rows($this->connection);
			// return mysqli_affected_rows($this->connection[$this->sel_db]);
		}
		
		public function confirm_affectedRow(){
			return ($this->get_affectedRow() >= 1) ? true : false;
		}
	}
	$data = get_db_data("", 0, 1);
	// print_r($data[0]);
	// $new_core_3 = new core_3();
	$old_objs = array(new core_3($data[0]));
?>