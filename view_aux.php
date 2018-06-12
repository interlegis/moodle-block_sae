<?php
//
// ARQUIVO AUXILIAR DO VIEW - FUNÇÕES JAVASCRIPT E HTML
//
?>

<br><br>
<textarea class='form-control' rows='10' cols='50' placeholder='Digite sua mensagem...' id='mensagem' style='display:none'></textarea>

<div class='btn-group' style='text-align: center'>
  <button type='button' class='btn btn-primary' id='sendmail' onclick='send();' style='display:none'>Enviar e-mail</button>
  <button type='button' class='btn btn-secondary' id='hidemail' onclick='hideMail();' style='display:none'>Ocultar</button>
</div>

<div id='hid' hidden></div>

<script>
  function hideMail() {
    if(document.getElementById('mensagem').style.display == 'block') {
      document.getElementById('mensagem').style.display = 'none';
      document.getElementById('hidemail').style.display = 'none';      
    }
  }
</script>