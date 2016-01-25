<?php
	if (!isset($QUERYBUILDER_INCLUDED)) {
		$QUERYBUILDER_INCLUDED = true;
		
		class QueryBuilder {
			protected $table;
			
			protected $nExtraFields;
			protected $extraFields;
			protected $extraFieldsAliases;
			
			protected $nFilters;
			protected $operators;
			protected $fields;
			protected $values;
			
			protected $nOrderBy;
			protected $orderFields;
			protected $orderDirections;
			protected $limits;
			
			protected $nJoins;
			protected $joinTables;
			protected $joinAlias;

			protected $nLeftJoins;
			protected $leftJoinTables;
			protected $leftJoinAlias;
			protected $leftJoinFields1;
			protected $leftJoinFields2;
			protected $leftJoinDeletedFields;
			
			protected $nGroupBy;
			protected $groupByTables;
			protected $groupByFields;
			
			public function __construct($table) {
				if (!isset($table))
					die("<h1>QueryBuilder precisa de uma tabela do DB.</h1>");
				$this->table = $table;

				$this->nExtraFields = 0;
				$this->extraFields = array();
				$this->extraFieldsAliases = array();
				
				$this->nFilters = 0;
				$this->operators = array();
				$this->fields = array();
				$this->values = array();
				
				$this->nOrderBy = 0;
				$this->orderFields = array();
				$this->orderDirections = array();
				$this->limits = null;

				$this->nJoins = 0;
				$this->joinAlias = array();
				$this->joinTables = array();

				$this->nLeftJoins = 0;
				$this->leftJoinTables = array();
				$this->leftJoinAlias = array();
				$this->leftJoinFields1 = array();
				$this->leftJoinFields2 = array();
				$this->leftJoinDeletedFields = array();
				
				$this->nGroupBy = 0;
				$this->groupByFields = array();
				$this->groupByTables = array();
			}
			
			public function toSelectString() {
				$sql = "SELECT DISTINCT *";
				if ($this->nExtraFields > 0) {
					for ($i = 0; $i < $this->nExtraFields; $i++) {
						$sql.= ",".$this->extraFields[$i];
						if (isset($this->extraFieldsAliases[$i]) && ($this->extraFieldsAliases[$i] != "")) {
							$sql.= " AS ".$this->extraFieldsAliases[$i];
						}
					}
				}
				$sql .= " FROM `".$this->table."` ";
				if ($this->nJoins > 0) {
					$sql .= "JOIN ";
					foreach ($this->joinTables as $t => $table) {
						if ($t > 0)
							$sql .= ",";
						$sql .= $table;
						$alias = $this->joinAlias[$t];
						if (isset($alias) && ($alias != ""))
							$sql .= " AS ".$alias;
					}
				}
				if ($this->nLeftJoins > 0) {
					$sql .= " LEFT JOIN ";
					foreach ($this->leftJoinTables as $t => $table) {
						if ($t > 0)
							$sql .= ",";
						$sql .= $table;
						@$alias = $this->leftJoinAlias[$t];
						if (isset($alias) && ($alias != ""))
							$sql .= " AS ".$alias;
						else
							$alias = $table;
						$sql .= " ON "."`".$this->table."`.`".
								$this->leftJoinFields1[$t]."`=".
								$alias.".`".$this->leftJoinFields2[$t]."`";
						@$deletedField = $this->leftJoinDeletedFields[$t];
						if (isset($deletedField) && ($deletedField != "")) {
							$sql .= " AND ".$alias.".`".
								$deletedField."`='0'";
						}
					}
				}
				$sql .= " ".$this->toString().";";
				return $sql;
			}
			
			public function toString() {
				$sql = "";
				if ($this->nFilters > 0) {
					$sql .= " WHERE ";
					foreach ($this->fields as $f => $field) {
						if ($f > 0)
							$sql .= " AND ";
						$sql .= $field.$this->operators[$f].$this->values[$f];
					}
				}
				
				if ($this->nGroupBy > 0) {
					$sql .= " GROUP BY ";
					foreach ($this->groupByFields as $f => $field) {
						if ($f > 0)
							$sql .= ",";
						@$tbl = $this->groupByTables[$f];
						if (isset($tbl) && ($tbl != ""))
							$sql .= $tbl.".".$field;
						else
							$sql .= $field;
					}
				}
				
				if ($this->nOrderBy > 0) {
					$sql .= " ORDER BY ";
					foreach ($this->orderFields as $f => $field) {
						if ($f > 0)
							$sql .= ",";
						$sql .= $field." ".$this->orderDirections[$f];
					}
				}
				
				if ($this->limits !== null)
					$sql .= " LIMIT "+$this->limits;
				
				return $sql;
			}

			public function addExtraField($field, $alias) {
				$this->extraFields[$this->nExtraFields] = $field;
				$this->extraFieldsAliases[$this->nExtraFields] = $alias;
				$this->nExtraFields++;
			}

			public function addLike($field, $value, $table = null) {
				if (!isset($table))
					$table = $this->table;
				$this->fields[$this->nFilters] = "`".$table."`.`".$field."`";
				$this->values[$this->nFilters] = "'".$value."'";
				$this->operators[$this->nFilters] = "LIKE";
				$this->nFilters++;
			}
			public function addEqual($field, $value, $table = null) {
				if (!isset($table))
					$table = $this->table;
				$this->fields[$this->nFilters] = "`".$table."`.`".$field."`";
				$this->values[$this->nFilters] = "'".$value."'";
				$this->operators[$this->nFilters] = "=";
				$this->nFilters++;
			}
			public function addNotEqual($field, $value, $table = null) {
				if (!isset($table))
					$table = $this->table;
				$this->fields[$this->nFilters] = "`".$table."`.`".$field."`";
				$this->values[$this->nFilters] = "'".$value."'";
				$this->operators[$this->nFilters] = "<>";
				$this->nFilters++;
			}
			public function addLess($field, $value, $table = null) {
				if (!isset($table))
					$table = $this->table;
				$this->fields[$this->nFilters] = "`".$table."`.`".$field."`";
				$this->values[$this->nFilters] = "'".$value."'";
				$this->operators[$this->nFilters] = "<";
				$this->nFilters++;
			}
			public function addLessEqual($field, $value, $table = null) {
				if (!isset($table))
					$table = $this->table;
				$this->fields[$this->nFilters] = "`".$table."`.`".$field."`";
				$this->values[$this->nFilters] = "'".$value."'";
				$this->operators[$this->nFilters] = "<=";
				$this->nFilters++;
			}
			public function addGreater($field, $value, $table = null) {
				if (!isset($table))
					$table = $this->table;
				$this->fields[$this->nFilters] = "`".$table."`.`".$field."`";
				$this->values[$this->nFilters] = "'".$value."'";
				$this->operators[$this->nFilters] = ">";
				$this->nFilters++;
			}
			public function addGreaterEqual($field, $value, $table = null) {
				if (!isset($table))
					$table = $this->table;
				$this->fields[$this->nFilters] = "`".$table."`.`".$field."`";
				$this->values[$this->nFilters] = "'".$value."'";
				$this->operators[$this->nFilters] = ">=";
				$this->nFilters++;
			}
			
			public function addOrder($field, $direction, $table = null) {
				if (!isset($table))
					$table = $this->table;
				$this->orderFields[$this->nOrderBy] = "`".$table."`.`".$field."`";
				$this->orderDirections[$this->nOrderBy] = $direction;
				$this->nOrderBy++;
			}
			
			public function setLimits($limits) {
				$this->limits = $limits;
			}
			
			public function addJoin($table, $field1, $field2, $alias = "") {
				$this->joinTables[$this->nJoins] = "`".$table."`";
				if (isset($alias) && ($alias != "")) {
					$this->joinAlias[$this->nJoins] = "`".$alias."`";
					$table = $alias;
				} else
					$this->joinAlias[$this->nJoins] = $alias;
				
				$this->fields[$this->nFilters] = "`".$this->table."`.`".$field1."`";
				$this->values[$this->nFilters] = "`".$table."`.`".$field2."`";
				$this->operators[$this->nFilters] = "=";
				$this->nFilters++;
				
				$this->nJoins++;
			}

			public function addLeftJoin($table, $field1, $field2, $deletedField = "", $alias = "") {
				$this->leftJoinTables[$this->nLeftJoins] = "`".$table."`";
				if (isset($alias) && ($alias != "")) {
					$this->leftJoinAlias[$this->nLeftJoins] = "`".$alias."`";
					$table = $alias;
				} else
					$this->leftJoinAlias[$this->nLeftJoins] = $alias;
			
				$this->leftJoinFields1[$this->nLeftJoins] = $field1;
				$this->leftJoinFields2[$this->nLeftJoins] = $field2;
				$this->leftJoinDeletedFields[$this->nLeftJoins] = $deletedField;
				$this->nLeftJoins++;
			}
			
			public function addGroupBy($field, $table = null) {
				if ((!isset($table)) || ($table == ""))
					$table = $this->table;
				$this->groupByFields[$this->nGroupBy] = "`".$field."`";
				$this->groupByTables[$this->nGroupBy] = "`".$table."`";
				$this->nGroupBy++;
			}
			
			
			// Constantes estáricas.
			public static $ASC = 'asc';
			public static $DESC = 'desc';
		}
	}
?>