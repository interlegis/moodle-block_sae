<?php

//echo '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">'; // IMPORTAÇÃO DO BOOTSTRAP (QUEBRANDO O TEMPLATE)
/**
 * SAE.
 * @copyright 2018 Billy Brian <billybrianm@gmail.com>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once('sae_form.php');

$url = new moodle_url('/blocks/sae/view.php');

global $DB, $USER;

$PAGE->set_url($url);

require_login();

//navigation_node::override_active_url(new moodle_url('/course/view.php', array('id' => $course->id)));

$pagetitle = 'Sistema de Atendimento ao Estudante';

$PAGE->set_pagelayout('standard');
$PAGE->set_title("$pagetitle");
$PAGE->set_heading($COURSE->fullname);

$mform = new sae_form(null, array('chapter'=>$chapter, 'options'=>$options, 'content'=>$content));


if ($data = $mform->get_data()) {

  if($data->elogio || $data->sugestao) {

    if($data->elogio) {
      $msg = $data->elogio;
      $sub = 'Elogio';
    }
    else if ($data->sugestao) {
      $msg = $data->sugestao;
      $sub = 'Sugestão';

    }

    $name = fullname($USER);   
      
    $context = stream_context_create(array(
        'http' => array(
            'method'  => 'POST',
            'content' => 'nome='.$name.'&email='.$USER->email.'&assunto1='.$sub.'&mensagem='.$msg.'&muserid='.$USER->id.'&assunto2='.$sub,
            //'content' => $content,
        )
    ));  
      
    $result = file_get_contents($CFG->wwwroot.'/blocks/sae/ticket.php', null, $context);

    dbg('Result: ' . trim($result));

    if(trim($result) == '201') {
      $keys = array_keys((array)$data);
      foreach($keys as $key) {
        if($key != 'campo1' && $key != 'submitbutton') {
          $obr = $key;
        }
      }
    }

    notice('Mensagem enviada com sucesso!', new moodle_url($PAGE->url));

  } else {
    notice('Por favor digite uma mensagem.', new moodle_url($PAGE->url));
  }
}


echo $OUTPUT->header();
echo $OUTPUT->heading(format_string('Sistema de Atendimento ao Estudante'));



echo $mform->display();

$j = 0;
$all_help_title = $DB->get_recordset_sql('SELECT * FROM {sae_topic_help}');
foreach ($all_help_title as $record) {
    echo '
    <div class="panel-group">
      <div class="panel panel-default" id="hhelp'.$j.'" style="display: none;">
        <div class="panel-heading">
          <h4 class="panel-title">
            <a data-toggle="collapse" href="#item'.$j.'"><div class="title">'.$record->title.'</div></a>
          </h4>
        </div>
        <div id="item'.$j.'" class="panel-collapse collapse coll">
          <div class="panel-body" id="thelp'.$j.'" style="display: none;">
            <div class="tagged">'.$record->description.'</div>
          </div>
        </div>
      </div>
    </div>';

    $j++;
  }
$all_help_title->close();

function js($what) {
  echo "<script>".$what."</script>";
}

function dbg($what) {
  echo '<script>console.log("'.$what.'");</script>';
}

echo file_get_contents("view_aux.php");
echo "
  <script>
    function send() {
    if(document.getElementById('mensagem').style.display == 'none') {
      document.getElementById('mensagem').style.display = 'block';
      document.getElementById('hidemail').style.display = 'block';      
    } else {
      var hid = document.getElementById('hid').innerHTML;
      var mess = document.getElementById('mensagem').value;
      var topic = document.getElementById('id_campo1').options[document.getElementById('id_campo1').selectedIndex].text;
      var xhr = new XMLHttpRequest();
        xhr.open('POST', '$CFG->wwwroot/blocks/sae/ticket.php', true);

        //Envia o cabeçalho informativo adequado junto com a requisição.
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

        xhr.onreadystatechange = function() {//Call a function when the state changes.
            if(xhr.readyState == XMLHttpRequest.DONE) {
                console.log('Mensagem enviada! Output: ' + xhr.status);
                if(xhr.status == 200) {                  
                  alert('Mensagem enviada com sucesso!');
                  window.location.replace('$CFG->wwwroot/blocks/sae/view.php');
                }
            }
        }
        xhr.send('nome=".$name."&email=".$USER->email."&assunto1='+hid+'&assunto2='+topic+'&mensagem='+mess+'&muserid=".$USER->id."'); 
    }    
  }
  </script>";

echo $OUTPUT->footer();


