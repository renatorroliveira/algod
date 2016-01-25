<?php
	if (!isset($DAO_INCLUDED)) {
		$DAO_INCLUDED = true;
		
		@include "../model/QueryBuilder.php";
		@include "./model/QueryBuilder.php";
		@include "./QueryBuilder.php";
		@include "../model/Database.php";
		@include "./model/Database.php";
		@include "./Database.php";
		
		class DAO {
			protected $modelClass;
			protected $db;
			
			function __construct($modelClass) {
				$this->modelClass = $modelClass;
				$this->db = new Database();
			}
			
			public function save($model) {
				if (!isset($model))
					return "Unfined model provided for saving.";
				$sql = "INSERT INTO ".$model->getDBTable()."(";
				foreach ($model->getFields() as $f => $field) {
					if ($f > 0)
						$sql .= ",";
					$sql .= $field;
				}
				$sql .= ") VALUES (";
				foreach ($model->getFields() as $f => $field) {
					if ($f > 0)
						$sql .= ",";
					$value = $model->get($field);
					if (isset($value))
						$sql .= "'".$value."'";
					else
						$sql .= "NULL";
				}
				$sql .= ");";
				$status = $this->db->executar($sql);
				if (!$status) {
					echo $status." - SQL:<br /><pre>".$sql."</pre>";
					return "An error ocurred while saving model: ".$model->getDBTable();
				}
				return true;
			}
			
			public function update($model) {
				if (!isset($model))
					return "Unfined model provided for updating.";
				$sql = "UPDATE ".$model->getDBTable()." SET ";
				$inserted = 0;
				foreach ($model->getFields() as $f => $field) {
					$value = $model->get($field);
					if (isset($value)) {
						if ($inserted > 0)
							$sql .= ",";
						$sql .= $field."='".$value."'";
						$inserted++;
					}
				}
				$sql .= " WHERE ";
				foreach ($model->getPrimaryFields() as $i => $field) {
					if ($i > 0)
						$sql .= " AND ";
					$sql .= $field."='".$model->get($field)."'";
				}
				$sql .= ";";
				$status = $this->db->executar($sql);
				if (!$status) {
					die($status." - SQL:<br /><pre>".$sql."</pre>");
					return "An error ocurred while saving model: ".$model->getDBTable();
				}
				return true;
			}
			
			public function retrieveByExample($model) {
				if (!isset($model))
					return "Unfined model provided for retrive by example.";
			}
			
			public function findByQuery($qbuilder) {
				if (!isset($qbuilder)) {
					return array();
				}
				$model = new $this->modelClass();
				$sql = $qbuilder->toSelectString();
				//echo "<code>".$sql."</code>";
				$raw = $this->db->consulta($sql);
				if (count($raw) <= 0)
					return array();
				$typed = array();
				foreach ($raw as $i => $row) {
					$model = new $this->modelClass();
					$model->setFields($row);
					$typed[$i] = $model;
				}
				return $typed;
			}
			
			public function findByQueryWithMetaFields($qbuilder) {
				if (!isset($qbuilder)) {
					return array();
				}
				$model = new $this->modelClass();
				$model->applyMetaFields($qbuilder);
				$sql = $qbuilder->toSelectString();
				//echo "<code>".$sql."</code>";
				$raw = $this->db->consulta($sql);
				if (count($raw) <= 0)
					return array();
				$typed = array();
				foreach ($raw as $i => $row) {
					$model = new $this->modelClass();
					$model->setFields($row);
					$typed[$i] = $model;
				}
				return $typed;
			}
		}
	}
?>
