<?php

/**
 * Display the calendar page.
 * @copyright 2003 Jon Papaioannou
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @package core_calendar
 */

require_once('../../config.php');
require_once('sae_form.php');

$courseid = optional_param('course', SITEID, PARAM_INT);
$view = optional_param('view', 'upcoming', PARAM_ALPHA);


$url = new moodle_url('/sae/view.php');


$PAGE->set_url($url);

require_course_login($course);

$pagetitle = 'Sistema de Atendimento ao Estudante';

// Print title and header
$PAGE->set_pagelayout('standard');
$PAGE->set_title("$pagetitle");
$PAGE->set_heading($COURSE->fullname);

$mform = new sae_form(null, array('chapter'=>$chapter, 'options'=>$options, 'content'=>$content));

echo $OUTPUT->header();
echo html_writer::start_tag('div', array('class'=>'heightcontainer'));
// echo $OUTPUT->heading(get_string('sae', 'sae'));

//Link to calendar export page.
// echo $OUTPUT->container_start('bottom');


// echo $OUTPUT->container_end();
echo html_writer::end_tag('div');

echo $mform->display();

echo $OUTPUT->footer();


