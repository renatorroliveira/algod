<?php
	if (!isset($TESTTRIALMODEL_INCLUDED)){
		$TESTTRIALMODEL_INCLUDED = true;
		
		@include "./model/Model.php";
		@include "../model/Model.php";
		@include "./model/Problem.php";
		@include "../model/Problem.php";
		@include "./model/Test.php";
		@include "../model/Test.php";
		@include "./model/User.php";
		@include "../model/User.php";
		
		class TestTrial extends Model {
			function __construct() {
				$this->fields = array(
					'ttl_usr_id',
					'ttl_prb_id',
					'ttl_tst_id',
					'ttl_code',
					'ttl_hostname',
					'ttl_remoteAddr',
					'ttl_reason',
					'ttl_score',
					'ttl_trials',
					'ttl_lastTrial',
					'ttl_sourcefile'
				);
				$this->data = array();
				$this->dbTable = 'testtrial';
				$this->primaryFields = array('ttl_usr_id','ttl_tst_id','ttl_prb_id');
				$this->deletedField = '';
				$this->foreignFields = array('ttl_usr_id','ttl_tst_id','ttl_prb_id');
				$this->foreignModels = array();
				$this->foreignModels['ttl_usr_id'] = new User();
				$this->foreignModels['ttl_tst_id'] = new Test();
				$this->foreignModels['ttl_prb_id'] = new Problem();
			}
		}
	}
?>