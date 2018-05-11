
<?php
#
# Configuration: Enter the url and key. That is it.
#  url => URL to api/task/cron e.g #  http://yourdomain.com/support/api/tickets.json
#  key => API's Key (see admin panel on how to generate a key)
#  $data add custom required fields to the array.
#
#  Originally authored by jared@osTicket.com
#  Modified by ntozier@osTicket / tmib.net

// If 1, display things to debug.
$debug="0";

// You must configure the url and key in the array below.

$config = array(
        //'url'=>'10.1.2.242/osticket/upload/api/http.php/tickets.json',  // URL to site.tld/api/tickets.json
    'url'=>'http://10.1.2.242/osticket/upload/api/http.php/tickets.json',
    'key'=>'B154D35D63E7A4ADAE8BA8282C6490CD'  // API Key goes here
);

# NOTE: some people have reported having to use "http://your.domain.tld/api/http.php/tickets.json" instead.

# Fill in the data for the new ticket, this will likely come from $_POST.
# NOTE: your variable names in osT are case sensiTive. 
# So when adding custom lists or fields make sure you use the same case
# For examples on how to do that see Agency and Site below.

$name = $_POST['nome'];
$email = $_POST['email'];
$phone = $_POST['telefone'];
$assunto1 = $_POST['assunto1'];
$assunto2 = $_POST['assunto2'];
$assunto3 = $_POST['assunto3'];
$mensagem = $_POST['mensagem'];

if (!empty($assunto3) || !empty($assunto2)) {
  if (!empty($assunto3)) {
    $assunto = $assunto3;
  }
  if(!empty($assunto2)){
   $assunto = $assunto2; 
  }
}else{
  $assunto = $assunto1;
}

switch ($assunto) {
  case 'Dúvidas sobre certificados':
    $topicId = 17;
    break;

  case 'Dúvidas sobre cursos':
    $topicId = 18;
    break;

  case 'Reclamações sobre certificados':
    $topicId = 21;
    break;

  case 'Reclamações sobre cursos':
    $topicId = 22;
    break;

  case 'Dúvidas':
    $topicId = 13;
    break;

  case 'Dúvidas sobre dados cadastrais':
    $topicId = 19;
    break;

  case 'Outras dúvidas':
    $topicId = 20;
    break;

  case 'Elogios':
    $topicId = 16;
    break;

  case 'Reclamações':
    $topicId = 14;
    break;

  case 'Outras reclamações':
    $topicId = 25;
    break;

  case 'Reclamações sobre dados cadastrais':
    $topicId = 24;
    break;

  case 'Reclamações sobre tutor':
    $topicId = 23;
    break;

  case 'Sugestões':
    $topicId = 15;
    break;
  
  default:
    $topicId = 13;
    break;
}

$data = array(
    'name'      =>      $name,  // from name aka User/Client Name
    'email'     =>      $email,  // from email aka User/Client Email
    'phone'     =>      $phone,  // phone number aka User/Client Phone Number
    'subject'   =>      $assunto,  // test subject, aka Issue Summary
    'message'   =>      $mensagem,  // test ticket body, aka Issue Details.
    'ip'        =>      $_SERVER['REMOTE_ADDR'], // Should be IP address of the machine thats trying to open the ticket.
  'topicId'   =>      $topicId, // the help Topic that you want to use for the ticket 
  //'Agency'  =>    '58', //this is an example of a custom list entry. This should be the number of the entry.
  //'Site'  =>    'Bermuda'; // this is an example of a custom text field.  You can push anything into here you want. 
    'attachments' => array()
);

# more fields are available and are documented at:
# https://github.com/osTicket/osTicket-1.8/blob/develop/setup/doc/api/tickets.md


  print_r($data);
  // die();


# Add in attachments here if necessary
# Note: there is something with this wrong with the file attachment here it does not work.
//$data['attachments'][] =
//array('file.txt' =>
  //      'data:text/plain;base64;'
    //        .base64_encode(file_get_contents('/file.txt')));  // replace ./file.txt with /path/to/your/test/filename.txt
 

#pre-checks
function_exists('curl_version') or die('CURL support required');
function_exists('json_encode') or die('JSON support required');

#set timeout
set_time_limit(30);

#curl post
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $config['url']);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_USERAGENT, 'osTicket API Client v1.8');
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_HTTPHEADER, array( 'Expect:', 'X-API-Key: '.$config['key']));
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
$result=curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo $code;

if ($code != 201){
    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
    echo 'Unable to create ticket: '.$result;
}

$ticket_id = (int) $result;

# Continue onward here if necessary. $ticket_id has the ID number of the
# newly-created ticket

function IsNullOrEmptyString($question){
    return (!isset($question) || trim($question)==='');
}
?>