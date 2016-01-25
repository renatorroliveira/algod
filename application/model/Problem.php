<?php
	if (!isset($PROBLEMMODEL_INCLUDED)){
		$PROBLEMMODEL_INCLUDED = true;
		
		@include "./model/Model.php";
		@include "../model/Model.php";
		@include "./model/Discipline.php";
		@include "../model/Discipline.php";
		
		class Problem extends Model {
			function __construct() {
				$this->fields = array(
					'prb_id',
					'prb_title',
					'prb_description',
					'prb_difficultyLevel',
					'prb_dsc_id',
					'prb_deleted'
				);
				$this->data = array();
				$this->dbTable = 'problem';
				$this->primaryFields = array('prb_id');
				$this->deletedField = 'prb_deleted';
				$this->foreignFields = array('prb_dsc_id');
				$this->foreignModels = array();
				$this->foreignModels['prb_dsc_id'] = new Discipline();
				$this->metaValues = array();
				$this->metaFields = array('count(`evc_id`)');
				$this->metaFieldsAliases = array('prb_numberOfTestCases');
			}
		}
	}
?>