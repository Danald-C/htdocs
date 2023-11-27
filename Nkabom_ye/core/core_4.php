<?php
	class core_4{
		
		//
		public $u_id;
		public $u_type;
		private $logged_in = false;
		
		public $org_id;
		public $dbObj_i;
		
		public $tmp_data = array();
		private $messages = array(array(), 0);
		
		
		function __construct(){
			if(!session_start()){
				session_start();
			}
			
			$this->check_page_status();
			$this->check_userLogin();
			$this->check_currOrg();
			$this->check_tmpData();
		}
		
		private function pages(){
			return array("page_0", "page_1", "page_2", "page_3");
		}
		
		public function page_access_all($mode=0){
			if($mode == 1){
				for($i=0; $i<count($this->pages()); $i++){
					$this->page_access($i);
				}
			}else{
				for($i=0; $i<count($this->pages()); $i++){
					$this->page_unset($i);
				}
			}
		}
		public function page_access($page){
			$_SESSION[$this->pages()[$page]] = true;
			$this->{$this->pages()[$page]} = true;
		}
		public function page_status($page){
			return $this->{$this->pages()[$page]};
		}
		public function page_unset($page){
			unset($_SESSION[$this->pages()[$page]]);
			$this->{$this->pages()[$page]} = false;
		}
		
		public function curr_org($mode=0, $org_id=array(0, 0)){
			if($mode == 1){
				$_SESSION['org_id'] = $org_id; // [0] Org id; [1] DB obj index
				$this->org_id = $org_id;
			}else{
				unset($_SESSION['org_id']);
				unset($this->org_id);
			}
		}
		
		public function user_login($user_found){
			if($user_found){
				$_SESSION['u_id'] = $user_found->id;
				$_SESSION['u_type'] = $user_found->type;
			}
			$this->logged_in = true;
		}
		public function is_logged_in(){
			return $this->logged_in;
		}
		
		public function user_logout(){
			unset($_SESSION['u_id']);
			unset($_SESSION['u_type']);
			unset($this->u_id);
			unset($this->u_type);
			$this->logged_in = false;
		}
		
		public function tmp_data($mode=0, $tmp_data=array()){
			if($mode == 1){
				$_SESSION['tmp_data'] = $tmp_data;
				$this->tmp_data = $tmp_data;
			}else{
				unset($_SESSION['tmp_data']);
				unset($this->tmp_data);
			}
		}
		
		public function messages($message="", $mode=0){
			if($mode == 0){
				$this->messages[0][] = $message;
				$this->messages[1] = 1;
				$_SESSION['messages'] = $this->messages;
			}
			if($mode == 1){
				return $this->messages;
			}
			if($mode == 2){
				$this->messages[1] = 0;
				$_SESSION['messages'] = $this->messages;
			}
		}
		
		
		
		private function check_page_status(){
			foreach($this->pages() as $page){
				if(isset($_SESSION[$page])){
					$this->$page = true;
					// $this->$page = $page;
				}else{
					unset($_SESSION[$page]);
					$this->$page = false;
					// $this->$page = 0;
				}
			}
		}
		private function check_userLogin(){
			if(isset($_SESSION['u_id'], $_SESSION['u_type'])){
				$this->u_id = $_SESSION['u_id'];
				$this->u_type = $_SESSION['u_type'];
				$this->logged_in = true;
			}
		}
		private function check_currOrg(){
			if(isset($_SESSION['org_id'])){
				$this->org_id = $_SESSION['org_id'];
			}
		}
		private function check_tmpData(){
			if(isset($_SESSION['tmp_data'])){
				$this->tmp_data = $_SESSION['tmp_data'];
			}
		}
		private function check_messages(){
			if(isset($_SESSION['messages'])){
				if($_SESSION['messages'][1] == 1){
					$this->messages = $_SESSION['messages'];
				}
			}
		}
	}
	$new_core_4 = new core_4();
?>