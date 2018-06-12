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

        //$mform->addElement('header', 'sae', get_string('pluginname', 'block_sae'));  

        $asd = $mform->addElement('select', 'campo1', 'Selecione tópico', $topics, array('onchange' => 'javascript:campo1Change(this);'));     
        $i = 0;

        $ativo = 'nenhum';


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

    	$all_help_title = $DB->get_recordset_sql('SELECT * FROM {sae_topic_help}');

    	$j = 0;
    	foreach ($all_help_title as $record) {
				//$mform->addElement('static', 'valores'.$j, $record->title, $record->description);
				//$mform->hideIf('valores', 'campo1', 'neq', $j);
		    $j++;
		}
		$all_help_title->close();

		echo "
		<script>
		function campo1Change(who) {
			if(document.getElementById('obrigado'))
				document.getElementById('obrigado').innerHTML = '';

			change(who, true);

			//if(who.options[who.selectedIndex].text == 'Dúvida' || who.options[who.selectedIndex].text == 'Reclamação') {
			    //document.getElementById('sendmail').style.display = 'block';
			//} 
			//else 
			if (who.options[who.selectedIndex].text == 'Elogio' || who.options[who.selectedIndex].text == 'Sugestão'){
			    document.getElementById('sendmail').style.display = 'none';
			    document.getElementById('hidemail').style.display = 'none';
			    document.getElementById('mensagem').style.display = 'none';
			}
		}

		function change(name, isCampo) {

			if(!isCampo) {
				if(name.value == 0)
					document.getElementById('sendmail').style.display = 'none';
				else
					document.getElementById('sendmail').style.display = 'block';

				console.log(name.value);
			}

			
			document.getElementById('hid').innerHTML = name.options[name.selectedIndex].text;
			var i;
			for (i = 0; i < document.getElementsByClassName('coll').length; i++) {
				document.getElementsByClassName('coll')[i].setAttribute('aria-expanded', 'false');
				document.getElementsByClassName('coll')[i].classList.remove('in');
			}

			var divs = document.getElementsByClassName('tagged');
			var titles = document.getElementsByClassName('title');

			for (i = 0; i < document.getElementsByClassName('tagged').length; i++) {
				divs[i].style.display = 'none';
				titles[i].style.display = 'none';

				document.getElementById('hhelp'+i).style.display = 'none';
				document.getElementById('thelp'+i).style.display = 'none';

			} 
			getOutput(name.options[name.selectedIndex].text);
			//document.getElementById('thelp'+name.value).innerHTML = name.options[name.selectedIndex].text;
			return name;
		}

        function getOutput(id) {
			getRequest(
			'sae_ajax.php',	// URL do PHP
			drawOutput,		// funcao de sucesso 
			drawError,		// funcao de erro (catch)
			id				// parametro
			);
			return false;
		}

		function drawError() {
			console.log('Error.');
		}

		function drawOutput(response) {

			var js = JSON.parse(response);

			var i;
			var title = document.getElementsByClassName('title');
			var body = document.getElementsByClassName('tagged');

			for (i = 0; i < js.length; i++) {
				body[i].style.display = '';
				body[i].innerHTML = js[i].description;
				
				title[i].style.display = '';
				title[i].innerHTML = js[i].title;

				document.getElementById('hhelp'+i).style.display = 'block';
				document.getElementById('thelp'+i).style.display = 'block';

			} 
		}

		function getRequest(url, success, error, id) {
			var req = false;
			try{
				// browsers normais
				req = new XMLHttpRequest();
			} catch (e){
				// IE
				try {
					req = new ActiveXObject('Msxml2.XMLHTTP');
				} catch(e) {
				// IE mais antigo
					try{
						req = new ActiveXObject('Microsoft.XMLHTTP');
					} catch(e) {
						return false;
					}
				}
			}
			if (!req) return false;
			if (typeof success != 'function') success = function () {};
			if (typeof error!= 'function') error = function () {};
			req.onreadystatechange = function(){
				if(req.readyState == 4) {
					return req.status === 200 ? 
					success(req.responseText) : error(req.status);
				}
			}
			req.open('GET', url+'?topic='+id, true);
			req.send(null);
			return req;
		}
        </script>";


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
