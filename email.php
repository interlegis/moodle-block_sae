<?php

//echo '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">'; // IMPORTAÇÃO DO BOOTSTRAP (QUEBRANDO O TEMPLATE)
/**
 * SAE.
 * @copyright 2018 Billy Brian <billybrianm@gmail.com>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once('email_form.php');

$url = new moodle_url('/sae/view.php');

global $DB, $USER;

$PAGE->set_url($url);

require_login();

$type = $_GET['type'];

//navigation_node::override_active_url(new moodle_url('/course/view.php', array('id' => $course->id)));

$pagetitle = 'Sistema de Atendimento ao Estudante';

$PAGE->set_pagelayout('standard');
$PAGE->set_title("$pagetitle");
$PAGE->set_heading($COURSE->fullname);

$mform = new email_form(null, array('type' => $type));

echo $OUTPUT->header();
echo $OUTPUT->heading(format_string('Sistema de Atendimento ao Estudante'));
if ($mform->is_cancelled()){
	redirect('view.php');
}
else if ($data = $mform->get_data()) {	

	if($type == 'Dúvida')
		$type = 'Duvida';

    $context = stream_context_create(array(
        'http' => array(
            'method'  => 'POST',
            'content' => 'nome='.fullname($USER).'&email='.$USER->email.'&assunto1='.$data->gv.'&mensagem='.$data->mensagem
        )
    ));
      
    $result = file_get_contents($CFG->wwwroot.'/blocks/sae/ticket.php', null, $context);
    //echo "<script>console.log('".$context."');</script>";

    print('<b>E-mail enviado com sucesso!</b>');

    //print_r($result);

    //redirect('view.php');
} else {
	 echo "<script>console.log('nada');</script>";
}

ECHO $mform->display();

echo $OUTPUT->footer();