<?php
	if (!isset($TESTLOGMODEL_INCLUDED)){
		$TESTLOGMODEL_INCLUDED = true;
		
		@include "./model/Model.php";
		@include "../model/Model.php";
		@include "./model/Test.php";
		@include "../model/Test.php";
		@include "./model/User.php";
		@include "../model/User.php";
		
		class TestLog extends Model {
			function __construct() {
				$this->fields = array(
					'tsl_id',
					'tsl_usr_id',
					'tsl_tst_id',
					'tsl_hostname',
					'tsl_remoteAddr',
					'tsl_message',
					'tsl_time',
                                        'tsl_score',
                                        'tsl_questNumber'
				);
				$this->data = array();
				$this->dbTable = 'testlog';
				$this->primaryFields = array('tsl_id');
				$this->deletedField = '';
				$this->foreignFields = array('tsl_usr_id','tsl_tst_id');
				$this->foreignModels = array();
				$this->foreignModels['tsl_usr_id'] = new User();
				$this->foreignModels['tsl_tst_id'] = new Test();
			}
		}
	}
?>