<?php
	if (!isset($EVALUATIONCASEMODEL_INCLUDED)){
		$EVALUATIONCASEMODEL_INCLUDED = true;
		
		@include "./model/Model.php";
		@include "../model/Model.php";
		@include "./model/Problem.php";
		@include "../model/Problem.php";
		
		class EvaluationCase extends Model {
			function __construct() {
				$this->fields = array(
					'evc_id',
					'evc_inputs',
					'evc_inputsDataTypes',
					'evc_outputs',
					'evc_outputsDataTypes',
					'evc_prb_id',
					'evc_deleted'
				);
				$this->data = array();
				$this->dbTable = 'evaluationcase';
				$this->primaryFields = array('evc_id');
				$this->deletedField = 'evc_deleted';
				$this->foreignFields = array('evc_prb_id');
				$this->foreignModels = array();
				$this->foreignModels['evc_prb_id'] = new Problem();
			}
			
			public function getParsedInputs() {
				$inputs = array();
				$inputs["values"] = array();
				$inputs["datatypes"] = array();
				$ins = $this->get('evc_inputs');
				if (isset($ins) && ($ins != "")) {
					$inputs["values"] = explode("&",$this->get('evc_inputs'));
					$inputs["datatypes"] = explode("&",$this->get('evc_inputsDataTypes'));
				}
				
				return $inputs;
			}
			
			public function getParsedOutputs() {
				$outputs = array();
				$outputs["values"] = array();
				$outputs["datatypes"] = array();
				$outs = $this->get('evc_outputs');
				if (isset($outs) && ($outs != "")) {
					$outputs["values"] = explode("&", $this->get('evc_outputs'));
					$outputs["datatypes"] = explode("&", $this->get('evc_outputsDataTypes'));
				}
			
				return $outputs;
			}
		}
	}
?>