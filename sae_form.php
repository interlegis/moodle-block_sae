<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Chapter edit form
 *
 * @package    mod_book
 * @copyright  2004-2010 Petr Skoda {@link http://skodak.org}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

require_once($CFG->libdir.'/formslib.php');


class sae_form extends moodleform {

    function definition() {
        global $CFG, $DB;


        //$chapter = $this->_customdata['chapter'];

        $mform = $this->_form;

        $topics = $DB->get_fieldset_sql('SELECT name FROM {sae_topic} WHERE parent_id is null');

        $mform->addElement('header', 'sae', get_string('pluginname', 'block_sae'));  

        $asd = $mform->addElement('select', 'campo1', 'Selecione tópico', $topics, $attributes);     
        $i = 0;

        $ativo = 'nenhum';


        foreach($topics as $topic) {
        	$topic_id = $DB->get_field('sae_topic', 'id', array('name' => $topic));
        	$subtopic_names = $DB->get_fieldset_sql('SELECT name FROM {sae_topic} WHERE parent_id = ?', array($topic_id));
        	// fim db

        	$mform->addElement('select', 'type'.$i, '', $subtopic_names, array('onchange' => 'javascript:change(name);'));

            $mform->hideIf('type'.$i, 'campo1', 'neq', $i);

            echo "<script>
            function change(name) {

            	console.log(name);
            	document.getElementById('id_type0').id = 'test'
            	return name;
        	}
        	</script>";

        	$i++;
    	}



    	$all_help_title = $DB->get_recordset_sql('SELECT * FROM {sae_topic_help}');

    	$j = 0;
    	foreach ($all_help_title as $record) {

    		if($mform->getElement('test') != null)
				$mform->addElement('static', 'valores', $record->title, '<span style="color:red">'.$record->description.'</span>');

		    $j++;
		}
		$all_help_title->close();
    	

        $mform->addElement('hidden', 'pagenum');
        $mform->setType('pagenum', PARAM_INT);

        $this->add_action_buttons(false, 'Enviar');

        // set the defaults
        $this->set_data($chapter);
    }

    function get_submit_value($elementname) {
        $mform = $this->_form;
        return $mform->getSubmitValue($elementname);
    }
    
    function definition_after_data(){
        $mform = $this->_form;
        $pagenum = $mform->getElement('pagenum');
        if ($pagenum->getValue() == 1) {
            $mform->hardFreeze('subchapter');
        }       
    }
}

