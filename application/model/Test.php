<?php
	if (!isset($TESTMODEL_INCLUDED)){
		$TESTMODEL_INCLUDED = true;
		
		@include "./model/Model.php";
		@include "../model/Model.php";
		@include "./model/Discipline.php";
		@include "../model/Discipline.php";
		
		class Test extends Model {
			function __construct() {
				$this->fields = array(
					'tst_id',
					'tst_title',
					'tst_description',
					'tst_password',
					'tst_createdAt',
					'tst_visibleSince',
					'tst_visibleUntil',
					'tst_openSince',
					'tst_openUntil',
					'tst_maxTrials',
					'tst_enableLogging',
					'tst_scoreAttenuationPerTrial',
					'tst_dsc_id',
					'tst_deleted'
				);
				$this->data = array();
				$this->dbTable = 'test';
				$this->primaryFields = array('tst_id');
				$this->deletedField = 'tst_deleted';
				$this->foreignFields = array('tst_dsc_id');
				$this->foreignModels = array();
				$this->foreignModels['tst_dsc_id'] = new Discipline();
				$this->metaValues = array();
				$this->metaFields = array("count(`tpb_prb_id`)");
				$this->metaFieldsAliases = array("tst_numberOfQuestions");
			}
		}
	}
?>