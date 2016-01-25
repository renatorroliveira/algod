<?php 
	if (!isset($TESTLOGBS_INCLUDED)) {
		$TESTLOGBS_INCLUDED = true;
		
		@include "./model/DAO.php";
		@include "../model/DAO.php";
		@include "./model/TestLog.php";
		@include "../model/TestLog.php";
		
		class TestLogBS {
			protected $params;
			
			function __construct($params) {
				$this->params = $params;
			}
			
			public function findAll($qbuilder) {
				if (UserSession::getInstance()->getAccessLevel() < 3)
					die("<h1>Forbidden resource for you.</h1>");
				@$dao = new DAO(TestLog);
				if (!isset($qbuilder)) {
					$qbuilder = new QueryBuilder('testlog');
					$qbuilder->addOrder("tsl_tst_id", QueryBuilder::$DESC);
					$qbuilder->addOrder("usr_name", QueryBuilder::$ASC, 'user');
					$qbuilder->addOrder("tsl_time", QueryBuilder::$DESC);
					$qbuilder->addOrder("tsl_remoteAddr", QueryBuilder::$ASC);
				}
				$qbuilder->addJoin("user", "tsl_usr_id", "usr_id");
				$qbuilder->addJoin("test", "tsl_tst_id", "tst_id");
				$tlogs = $dao->findByQuery($qbuilder);
				return $tlogs;
			}
		}
	}
?>