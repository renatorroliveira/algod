<?php
	if (!isset($DISCIPLINEMODEL_INCLUDED)){
		$DISCIPLINEMODEL_INCLUDED = true;
		
		@include "./model/Model.php";
		@include "../model/Model.php";
		@include "./model/User.php";
		@include "../model/User.php";
		
		class Discipline extends Model {
			function __construct() {
				$this->fields = array('dsc_id','dsc_name','dsc_code','dsc_usr_id', 'dsc_deleted');
				$this->data = array();
				$this->dbTable = 'discipline';
				$this->primaryFields = array('dsc_id');
				$this->deletedField = 'dsc_deleted';
				$this->foreignFields = array('dsc_usr_id');
				$this->foreignModels = array();
				$this->foreignModels['dsc_usr_id'] = new User();
			}
		}
	}
?>