<?php

//require_once('../../lib/formslib.php');
require_once('../../config.php');
	
	function aa() {
		global $DB;
		$value = $_GET['topic'];

		$topics = $DB->get_recordset_sql("SELECT title, description FROM {sae_topic_help} h
											JOIN {sae_topic} t
												ON h.topic_id = t.id and
												t.name = '".$value."'", array($value));
		
		$r = array();
		foreach ($topics as $record) {
			array_push($r, $record);
		}		
		$topics->close();

		echo json_encode($r);
	}
	aa();
	//echo aa();
?>