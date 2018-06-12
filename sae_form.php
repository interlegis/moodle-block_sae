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
 * @package    block_sae
 * @copyright  2018 Billy Brian {billybrianm@gmail.com}
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

        $asd = $mform->addElement('select', 'campo1', 'Selecione tópico', $topics, array('onchange' => 'javascript:campo1Change(this);'));     
        $i = 0;

        foreach($topics as $topic) {
        	$topic_id = $DB->get_field('sae_topic', 'id', array('name' => $topic));
        	$subtopic_names = $DB->get_fieldset_sql('SELECT name FROM {sae_topic} WHERE parent_id = ?', array($topic_id));
        	// fim db

        	array_unshift($subtopic_names, "Selecione...");

        	$mform->addElement('select', 'topic'.$i, '', $subtopic_names, array('onchange' => 'javascript:change(this, false);'));

            $mform->hideIf('topic'.$i, 'campo1', 'neq', $i);                 

        	$i++;
    	}
        $mform->hideIf('topic2', 'campo1', 'neq', 0);
        $mform->hideIf('topic3', 'campo1', 'neq', 0);

        $mform->addElement('textarea', 'elogio', 'Insira seu elogio', 'wrap="virtual" rows="10" cols="50"');
        $mform->hideIf('elogio', 'campo1', 'neq', 2);

        $mform->addElement('textarea', 'sugestao', 'Insira sua sugestão', 'wrap="virtual" rows="10" cols="50"');
        $mform->hideIf('sugestao', 'campo1', 'neq', 3);

		echo file_get_contents("sae_form_js.php");

        $this->add_action_buttons(false, 'Enviar e-mail');
        $mform->hideIf('submitbutton', 'campo1', 'eq', 0);
        $mform->hideIf('submitbutton', 'campo1', 'eq', 1);

        $mform->disable_form_change_checker();        
    }

    function get_submit_value($elementname) {
        $mform = $this->_form;
        return $mform->getSubmitValue($elementname);
    }
}
