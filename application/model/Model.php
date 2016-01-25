<?php
	if (!isset($MODEL_INCLUDED)) {
		$MODEL_INCLUDED = true;
		
		class Model {
			protected $fields;
			protected $data = array();
			protected $dbTable;
			protected $primaryFields;
			protected $deletedField;
			protected $foreignFields;
			protected $foreignModels;
			protected $metaFields;
			protected $metaValues;
			protected $metaFieldsAliases;
			
			public static $SQL_DATE_FORMAT = "Y-m-d H:i:s";
			public static $INPUT_DATE_FORMAT = "d/m/Y H:i:s";
			public static function parseInputToSQLDate($value) {
				if ((!isset($value)) || ($value == ""))
					return $value;
				$dateobj = DateTime::createFromFormat(Model::$INPUT_DATE_FORMAT, $value);
				return $dateobj->format(Model::$SQL_DATE_FORMAT);
			}
			public static function parseSQLToInputDate($value) {
				if ((!isset($value)) || ($value == ""))
					return $value;
				$dateobj = DateTime::createFromFormat(Model::$SQL_DATE_FORMAT, $value);
				return $dateobj->format(Model::$INPUT_DATE_FORMAT);
			}
			
			public static function escapeForJSString($text) {
				if ((!isset($text)) || ($text == ""))
					return "";
				$find = array("'","\n","\t","\r");
				$replace = array("\\'","\\n","\\t");
				str_replace($find, $replace, $text);
				return $text;
			}
			
			function __construct() {
				die("Model abstract class should not be instantiated directly.");
			}
			
			// Pre-implemented methods.
			public function get($fieldName) {
				if (!isset($this->data))
					$this->data = array();
				return @$this->data[$fieldName];
			}
			
			public function set($fieldName, $value) {
				if (!isset($this->data))
					$this->data = array();
				$this->data[$fieldName] = $value;
			}
			
			public function getData() {
				return $this->data;
			}
			
			public function getPrimaryFields() {
				if (!isset($this->primaryFields))
					die ("Primary fields for model '".$this->dbTable.
							"' not defined.");
				return $this->primaryFields;
			}
			
			public function getFields() {
				if (!isset($this->fields))
					die("There are no fields defined for this model.");
				return $this->fields;
			}
			
			public function getDBTable() {
				if (!isset($this->dbTable))
					die("The database table name was not defined for this model.");
				return $this->dbTable;
			}
			
			public function setFields($data) {
				if (!isset($this->fields))
					die("There are no fields defined for this model.");
				foreach ($this->fields as $field) {
					@$value = $data[$field];
					if (isset($value))
						$this->data[$field] = $value;
				}
				foreach ($this->foreignFields as $ffield) {
					$this->foreignModels[$ffield]->setFields($data);
				}
				if (isset($this->metaFieldsAliases)) {
					foreach ($this->metaFieldsAliases as $mfield) {
						@$value = $data[$mfield];
						if (isset($value))
							$this->metaValues[$mfield] = $value;
					}
				}
			}
			
			public function getDeletedField() {
				return $this->deletedField;
			}
			
			public function getForeignFields() {
				return $this->foreignFields;
			}
			
			public function getForeignModels() {
				return $this->foreignModels;
			}
			
			public function getForeignModel($key) {
				return $this->foreignModels[$key];
			}
			
			public function getMetaField($key) {
				return @$this->metaValues[$key];
			}
			
			public function applyMetaFields($qbuilder) {
				if (isset($qbuilder) && isset($this->metaFields)) {
					foreach ($this->metaFields as $f => $mfield) {
						$qbuilder->addExtraField($mfield, $this->metaFieldsAliases[$f]);
					}
				}
			}
		}
	}
?>