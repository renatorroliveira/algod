<?php
	if (!isset($USERMODEL_INCLUDED)){
		$USERMODEL_INCLUDED = true;
		
		@include "./model/Model.php";
		@include "../model/Model.php";
		
		class User extends Model {
			function __construct() {
				$this->fields = array('usr_id','usr_name','usr_email','usr_matricula','usr_password',
						'usr_accessLevel','usr_confirmationCode', 'usr_deleted');
				$this->data = array();
				$this->dbTable = 'user';
				$this->primaryFields = array('usr_id');
				$this->deletedField = "usr_deleted";
				$this->foreignFields = array();
				$this->foreignModels = array();
			}
		}
	}
?>