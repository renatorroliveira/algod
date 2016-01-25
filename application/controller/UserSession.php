<?php
	if (!isset($USERSESSION_INCLUDED)) {
		$USERSESSION_INCLUDED = true;
		
		@include "./model/User.php";
		@include "../model/User.php";
		
		class UserSession {
			protected static $instance;
			public static function getInstance() {
				if (!isset(UserSession::$instance)) {
					UserSession::$instance = new UserSession();
				}
				return UserSession::$instance;
			}

			protected $logged;
			protected $user;
			
			protected function __construct() {
				session_start();
				if (isset($_SESSION['logged'])) {
					$this->logged = $_SESSION['logged'];
					$this->user = $_SESSION['user'];
				} else {
					$this->logged = false;
					$this->user = new User();
					$_SESSION['logged'] = $this->logged;
					$_SESSION['user'] = $this->user;
				}
			}
			
			public function isLogged() {
				return $this->logged;
			}
			
			public function getAccessLevel() {
				if ($this->isLogged() === false)
					return -1;
				$level = $this->user->get("usr_accessLevel");
				return $level;
			}
			
			public function getUser() {
				return $this->user;
			}
			
			public function login($user) {
				if (isset($user)) {
					$_SESSION['logged'] = true;
					$_SESSION['user'] = $user;
					$this->logged = true;
					$this->user = $user;
				}
			}
		}
		
	}
?>