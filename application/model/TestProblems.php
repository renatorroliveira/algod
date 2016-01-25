<?php
	if (!isset($TESTPROBLEMSMODEL_INCLUDED)){
		$TESTPROBLEMSMODEL_INCLUDED = true;
		
		@include "./model/Model.php";
		@include "../model/Model.php";
		@include "./model/Problem.php";
		@include "../model/Problem.php";
		@include "./model/Test.php";
		@include "../model/Test.php";
		
		class TestProblems extends Model {
			function __construct() {
				$this->fields = array(
					'tpb_prb_id',
					'tpb_tst_id',
					'tpb_questionNumber',
					'tpb_weight',
					'tpb_deleted'
				);
				$this->data = array();
				$this->dbTable = 'testproblems';
				$this->primaryFields = array('tpb_tst_id','tpb_prb_id');
				$this->deletedField = 'tpb_deleted';
				$this->foreignFields = array('tpb_tst_id','tpb_prb_id');
				$this->foreignModels = array();
				$this->foreignModels['tpb_tst_id'] = new Test();
				$this->foreignModels['tpb_prb_id'] = new Problem();
			}
		}
	}
?>