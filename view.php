<?php

/**
 * Display the calendar page.
 * @copyright 2003 Jon Papaioannou
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @package core_calendar
 */

require_once('../config.php');
require_once($CFG->dirroot.'/course/lib.php');
// require_once($CFG->dirroot.'/calendar/lib.php');

$courseid = optional_param('course', SITEID, PARAM_INT);
$view = optional_param('view', 'upcoming', PARAM_ALPHA);


$url = new moodle_url('/sae/view.php');

// $url->param('time', $time);

$PAGE->set_url($url);

if ($courseid != SITEID && !empty($courseid)) {
    $course = $DB->get_record('course', array('id' => $courseid));
    $courses = array($course->id => $course);
    $issite = false;
    navigation_node::override_active_url(new moodle_url('/course/view.php', array('id' => $course->id)));
} else {
    $course = get_site();
    // $courses = calendar_get_default_courses();
    $issite = true;
}

require_course_login($course);

$pagetitle = '';

// $strcalendar = get_string('sae', 'sae');


// Print title and header
$PAGE->set_pagelayout('standard');
$PAGE->set_title("$course->shortname: $strcalendar: $pagetitle");
$PAGE->set_heading($COURSE->fullname);

$renderer = $PAGE->get_renderer('core_calendar');
// $calendar->add_sidecalendar_blocks($renderer, true, $view);

echo $OUTPUT->header();
echo $renderer->start_layout();
echo html_writer::start_tag('div', array('class'=>'heightcontainer'));
// echo $OUTPUT->heading(get_string('sae', 'sae'));

?>

    <form class="well form-horizontal" action="ticket.php" method="post"  id="ticket_form" ">
      <fieldset>

      <!-- Form Name -->
      <h3 class=""><strong>Sistema de Atendimento ao Aluno</strong></h3>
      <br>

       <?php  
        echo "<input  name='id' placeholder='Id' style='display: none;' class='form-control' value='".$USER->id ."'  type='text'>"
        ?>
       <?php 
        echo "<input  name='nome' placeholder='Nome' style='display: none;' class='form-control' value='" . $USER->username ."'  type='text'>"
        ?>   
          
        <?php   
        echo "<input name='email' placeholder='E-Mail Address' style='display: none;'  class='form-control' value='" . $USER->email . "' type='text'>"
        ?>
            <!-- Text input-->
             
      <div class="form-group">
        <label class=" control-label"><strong>Assunto</strong></label>  
          <div class=" inputGroupContainer">
          <div class="input-group">
              
          <select name="assunto1" class="form-control camada1" onchange="change_select(this);">
            <option selected="selected" value=""></option>
<!--             <option value="Reclamações sobre certificados">Reclamações sobre certificados</option>
            <option value="Reclamações sobre cursos">Reclamações sobre cursos</option>
            <option value="Reclamações sobre tutor">Reclamações sobre tutor</option>
            <option value="Reclamações sobre dados cadastrais">Reclamações sobre dados cadastrais</option>
            <option value="Dúvidas sobre certificados">Dúvidas sobre certificados</option>
            <option value="Dúvidas sobre cursos">Dúvidas sobre cursos</option>
            <option value="Dúvidas sobre dados cadastrais">Dúvidas sobre dados cadastrais</option> -->
            <option value="Dúvidas">Dúvidas</option>
            <option value="Reclamações">Reclamações</option>
            <option  value="Elogios">Elogios</option>
            <option value="Sugestões">Sugestões</option>
          </select>



          </div>
        </div>
      </div>

      <select name="assunto2"  class="form-control duvidas" style="display: none; margin-bottom: 12px;" onchange="change_select(this);">
        <option  value=""></option>
        <option value="Dúvidas sobre certificados">Dúvidas sobre certificados</option>
        <option value="Dúvidas sobre cursos">Dúvidas sobre cursos</option>
        <option value="Dúvidas sobre senhas">Dúvidas sobre senhas</option>
        <option value="Dúvidas sobre dados cadastrais">Dúvidas sobre dados cadastrais</option>
        <option value="Dúvidas sobre declarações">Dúvidas sobre declarações</option>
        <option value="Outras Dúvidas">Outras Dúvidas</option>
      </select>

      <select name="assunto3" class="form-control reclamacoes" style="display: none; margin-bottom: 12px;" onchange="change_select(this);">
        <option selected="selected" value=""></option>
        <option value="Reclamações sobre certificados">Reclamações sobre certificados</option>
        <option value="Reclamações sobre matrícula">Reclamações sobre matrícula</option>
        <option value="Reclamações sobre cursos">Reclamações sobre cursos</option>
        <option value="Reclamações sobre dados cadastrais">Reclamações sobre dados cadastrais</option>
        <option value="Reclamações sobre tutor">Reclamações sobre tutor</option>
      </select>

      <select name="assunto4"  class="form-control duvidas_curso" style="display: none; margin-bottom: 12px;" onchange="change_select_cursos(this);">
        <option selected="selected" value=""></option>
        <option value="Como faço para me matricular em cursos sem tutoria?">Como faço para me matricular em cursos sem tutoria?</option>
        <option value="Como faço para cancelar a matrícula no curso?">Como faço para cancelar a matrícula no curso?</option>
        <option value="Onde posso obter informações sobre carga horária e o conteúdo programático dos cursos?">Onde posso obter informações sobre carga horária e o conteúdo programático dos cursos?</option>
        <option value="Quantos cursos posso fazer ao mesmo tempo?">Quantos cursos posso fazer ao mesmo tempo?</option>
        <option value="Concluí um curso mas não consigo me matricular em outro">Concluí um curso mas não consigo me matricular em outro</option>
        <option value="Como faço para não receber mensagens no mural de avisos">Como faço para não receber mensagens no mural de avisos</option>
        <option value="Quantos dias ainda tenho para concluir o curso?">Quantos dias ainda tenho para concluir o curso?</option>
        <option value="Como faço para imprimir o conteúdo do curso?">Como faço para imprimir o conteúdo do curso?</option>
        <option value="Concluí o curso e quero imprimir o conteúdo, como faço?">Concluí o curso e quero imprimir o conteúdo, como faço?</option>
        <option  value="Não consigo acessar o avalie o curso e a avaliação final">Não consigo acessar o avalie o curso e a avaliação final</option>
        <option  value="Não consigo acessar o conteúdo do curso, o que fazer?">Não consigo acessar o conteúdo do curso, o que fazer?</option>
        <option  value="Não fui aprovado no curso, e agora?">Não fui aprovado no curso, e agora?</option>  
        <option  value="Como faço para me matricular em cursos COM TUTORIA?">Como faço para me matricular em cursos COM TUTORIA?</option>
        <option  value="Como faço para me inscrever em cursos de pós graduação?">Como faço para me inscrever em cursos de pós graduação?</option>
      </select>

      <select name="assunto5"  class="form-control duvidas_senha" style="display: none; margin-bottom: 12px;" onchange="change_select_senha(this);">
        <option selected="selected" value=""></option>
        <option value="Esqueci minha senha, o que fazer?">Esqueci minha senha, o que fazer?</option>
        <option value="Esqueci minha senha e não me lembro qual endereço email cadastrei na plataforma">Esqueci minha senha e não me lembro qual endereço email cadastrei na plataforma</option>
        <option value="Como faço para alterar minha senha?">Como faço para alterar minha senha?</option>
      </select>

      <select name="assunto6"  class="form-control duvidas_cadastro" style="display: none; margin-bottom: 12px;" onchange="change_select_cadastro(this);">
        <option selected="selected" value=""></option>
        <option value="Como posso alterar meus dados cadastrais?">Como posso alterar meus dados cadastrais?</option>
        <!-- <option value="Não consigo alterar meu CPF">Não consigo alterar meu CPF</option> -->
        <option value="Como faço para cancelar meu cadastro?">Como faço para cancelar meu cadastro?</option>
        <option value="Não consigo acessar o saberes, posso fazer um novo cadastro?">Não consigo acessar o saberes, posso fazer um novo cadastro?</option>
      </select>

      <select name="assunto7"  class="form-control duvidas_declaracao" style="display: none; margin-bottom: 12px;" onchange="change_select_declaracao();">
        <option selected="selected" value=""></option>
        <option value="Como posso alterar meus dados cadastrais?">Preciso de uma declaração de que concluí o curso</option>
        <option value="Preciso de uma declaração de que fiz o curso, mas não fui aprovado">Preciso de uma declaração de que fiz o curso, mas não fui aprovado</option>
      </select>

      <select name="assunto8"  class="form-control duvidas_certificado" style="display: none; margin-bottom: 12px;" onchange="change_select_certificados(this);">
        <option selected="selected" value=""></option>
        <option value="Como faço para imprimir meu certificado? Fora do curso">Como faço para imprimir meu certificado? Fora do curso</option>
        <option value="Como faço para imprimir certificados antigos?(Entre 2010 e 2013)">Como faço para imprimir certificados antigos?(Entre 2010 e 2013)</option>
        <option value="Quais informações constam no certificado?">Quais informações constam no certificado?</option>
        <option value="No verso do certificado existe um código com letras e números. Para que serve?">No verso do certificado existe um código com letras e números. Para que serve?</option>
<!--         <option value="Meu certificado não foi gerado">Meu certificado não foi gerado</option>
        <option value="Meu certificado está com dados errados">Meu certificado está com dados errados</option> -->
      </select>

      <select name="assunto9"  class="form-control duvidas_outras" style="display: none; margin-bottom: 12px;" onchange="change_select_outras();" >
        <option selected="selected" value=""></option>
        <option value="O que é ILB?">O que é ILB?</option>
        <option value="Os cursos a distância oferecidos pelo ILB são reconhecidos pelo MEC?">Os cursos a distância oferecidos pelo ILB são reconhecidos pelo MEC?</option>
        <option value="Os certificados do ILB podem ser utilizados como crédito para curso de graduação?">Os certificados do ILB podem ser utilizados como crédito para curso de graduação?</option>
        <option value="Posso usar os cursos do ILB para licença capacitação">Posso usar os cursos do ILB para licença capacitação?</option>
      </select>

      <select name="assunto10"  class="form-control recl_certificado" style="display: none; margin-bottom: 12px;" onchange="change_select_recl_certificado();" >
        <option selected="selected" value=""></option>
        <option value="Meu certificado não foi gerado">Meu certificado não foi gerado</option>
      </select>

      <select name="assunto11"  class="form-control recl_cadastro" style="display: none; margin-bottom: 12px;" onchange="change_select_recl_cadastro();" >
        <option selected="selected" value=""></option>
        <option value="Não consigo alterar meu CPF">Não consigo alterar meu CPF</option>
        <option value="Erro no meu cadastro">Erro no meu cadastro</option>
      </select>

      <select name="assunto12"  class="form-control recl_matricula" style="display: none; margin-bottom: 12px;" onchange="change_select_recl_matricula();" >
        <option selected="selected" value=""></option>
        <option value="Concluí o curso mas não consigo me matricular em outro">Concluí o curso mas não consigo me matricular em outro</option>
      </select>

      <select name="assunto13"  class="form-control recl_curso" style="display: none; margin-bottom: 12px;" onchange="change_select_recl_curso();" >
        <option selected="selected" value=""></option>
        <option value="Não consigo acessar o Avalie o Curso e a Avaliação Final">Não consigo acessar o Avalie o Curso e a Avaliação Final</option>
        <option value="Erro no conteúdo do curso">Erro no conteúdo do curso</option>
        <option value="Erro nos exercícios de fixação">Erro nos exercícios de fixação</option>
        <option value="Erro na avaliação final">Erro na avaliação final</option>
      </select>


      <p id="resposta1" style="margin-top: 12px; margin-bottom: 12px; display: none;">Para se matricular em um curso sem tutoria, siga os seguintes passos:
      Na página inicial da plataforma Saberes, escolha a categoria <strong>Cursos on-line sem tutoria</strong>.
      Clique no curso de seu interesse para ver o conteúdo programático e a carga horário. Caso seja do seu interessa, leia as regras e critérios com atenção e clique em “MATRICULAR-SE”. O curso tem início assim que a matrícula é efetuada. 
      </p>

      <p id="resposta2" style="margin-top: 12px; margin-bottom: 12px; display: none;">Matrículas não podem ser canceladas.
      Caso você tenha se matriculado em um curso e não queira cursar, você terá que aguardar o prazo de finalização do curso, 60 dias, e cumprir a punição de 30 (trinta) dias, a contar da data final do curso, para ter a sua vaga liberada para uma nova matrícula.
      </p>

      <p id="resposta3" style="margin-top: 12px; margin-bottom: 12px; display: none;">Na página inicial da plataforma clique em <strong>Cursos on-line sem tutoria</strong>. Em seguida, clique na mais recente oferta de Cursos sem tutoria. Identifique o curso de seu interesse e clique no <strong>ícone do Sumário</strong>, localizado na lateral direita do nome do curso.
      </p>

      <p id="resposta4" style="margin-top: 12px; margin-bottom: 12px; display: none;">Cada aluno pode realizar simultaneamente até dois cursos sem tutoria e um curso com tutoria, desde que haja vaga.
      </p>

      <p id="resposta5" style="margin-top: 12px; margin-bottom: 12px; display: none;">Cada aluno pode estar matriculado em até dois cursos sem tutoria ao mesmo tempo.
      Ao concluir o curso, a vaga só é liberada quando o certificado for emitido.
      Em caso de abando ou reprovação, a vaga só é liberada 90 dias após a matrícula no curso.
      </p>

      <p id="resposta6" style="margin-top: 12px; margin-bottom: 12px; display: none;">Para bloquear as notificações enviadas dentro do curso, por favor, siga os seguintes passos:
      Acesse a plataforma e clique no campo da foto. 
      Clique no botão de engrenagem, no canto direito da página do perfil, e em seguida Preferências de mensagens. Você poderá ativar e desativar as notificações.
      </p>

      <p id="resposta7" style="margin-top: 12px; margin-bottom: 12px; display: none;">Você terá até 60 (sessenta) dias para concluir o curso.
      Em Duração do Curso, localizado do lado direito da página inicial do curso, o sistema informa quantos dias faltam para expirar o prazo de 60 dias.
      O curso poderá ser concluído antes desse prazo, mas o certificado só será emitido 21 (vinte e um) dias após a data de matrícula.
      </p>

      <p id="resposta8" style="margin-top: 12px; margin-bottom: 12px; display: none;">Para acessar a versão em PDF com o conteúdo do curso, acesse a página inicial do curso, na seção <strong>Módulo de Apoio/ Biblioteca</strong> e clique em <strong>Conteúdo do curso em PDF</strong>. 
      Lembramos que ao concluir o curso você perderá o acesso ao conteúdo geral do curso
      </p>

      <p id="resposta9" style="margin-top: 12px; margin-bottom: 12px; display: none;">Ao concluir o curso você perdeu acesso ao conteúdo, inclusive à apostila disponibilizada em PDF.
      </p>

      <p id="resposta10" style="margin-top: 12px; margin-bottom: 12px; display: none;">Para acessar o Avalie o curso e a Avaliação final, você deve fazer os exercícios de fixação de cada módulo. Observe se os quadradinhos ao lado dos exercícios estão sinalados. Caso não estejam, você pode marcá-los manualmente. Esse procedimento lhe dará acesso ao Avalie o curso. Assim, a plataforma libera o próximo passo para você continuar o curso.
      </p>

      <p id="resposta11" style="margin-top: 12px; margin-bottom: 12px; display: none;">Provavelmente você excedeu o prazo de conclusão do curso, que é de 60 (sessenta) dias a contar da data da matrícula. Seu status no curso ficará como abandono. Por isso, sua vaga ficará bloqueada por 30 (trinta) dias a contar da data limite para finalização do curso. Caso você não saiba a data limite, solicite a informação via e-mail para <u>ilbead@senado.leg.br.</u> 
      </p>

      <p id="resposta12" style="margin-top: 12px; margin-bottom: 12px; display: none;">Em caso de reprovação, a vaga fica bloqueada por 30 dias, a partir da data limite de conclusão do curso, para fazer o mesmo ou outro curso.
      Para refazer o curso, o aluno deve aguardar a abertura de uma nova turma, geralmente no início de cada semestre.
      </p>

      <p id="resposta13" style="margin-top: 12px; margin-bottom: 12px; display: none;">Os cursos com tutoria têm um número de vagas limitadas e, para realizar o curso, você deve primeiro fazer a pré-matrícula, seguindo os seguintes passos: 
      Na página inicial da plataforma Saberes, escolha a categoria <strong>Cursos on-line com tutoria</strong>.
      Clique no curso de seu interesse para ver o conteúdo programático e carga horário. Caso seja do seu interessa, leia as regras e critérios com atenção e clique em “MATRICULAR-SE”. Você receberá uma mensagem informando a pré-matrícula e o período em que a matrícula será confirmada ou declinada.
      As vagas são destinas aos alunos conforme ordem de inscrição, observando a prioridade dos servidores do Legislativo Federal, Estadual e Municipal e órgão conveniados.
      </p>

      <p id="resposta14" style="margin-top: 12px; margin-bottom: 12px; display: none;">Os cursos de pós-graduação oferecidos pelo ILB são presenciais e apenas para servidores do Senado Federal, Câmara dos Deputados, Tribunal de Contas da União e Câmara Legislativa do Distrito Federal.
      </p>

      <p id="resposta1-1" style="margin-top: 12px; margin-bottom: 12px; display: none;">Para acessar seu certificado, vá na Página Inicial do Saberes e, no lado direito da barra superior, clique em Certificados.
      Em seguida, clique em Obter certificados.
      Lembramos que o certificado só fica disponível para impressão 21 dias após a data de matrícula no curso.
      </p>

      <p id="resposta1-2" style="margin-top: 12px; margin-bottom: 12px; display: none;">Para obter certificados emitidos entre 2010 e 2013, siga os seguintes passos:
      Na página inicial da plataforma Saberes, abaixo de Institucional Escola de Governo, clique em Certificados. Em seguida, clique em Certificados antigos.
      Você será redirecionado para a página da antiga plataforma Trilhas. Caso tenha esquecido a senha, clique em “Esqueci a senha”. Ao digitar o seu CPF e endereço e-mail, o sistema lhe fornecerá uma nova senha na própria tela, não sendo necessário acessar o correio eletrônico. Com ela você acessará a página inicial do Trilhas para acessar seus certificados antigos.
      </p>

      <p id="resposta1-3" style="margin-top: 12px; margin-bottom: 12px; display: none;">No certificado constam o nome e CPF do aluno; modalidade do curso, nome do curso; nota de aprovação; período de realização; carga horária; conteúdo programático; código de verificação do certificado e CNPJ do Senado Federal.

      <div id="resposta1-4" style="margin-top: 12px; margin-bottom: 12px; display: none;">Todos os certificados emitidos pela plataforma Saberes possuem um código de validação. Esse código possibilita que qualquer pessoa verifique a autenticidade do documento. Para confirmar sua veracidade, faça o seguinte.
      <ol>
        <li>Acesse <a href="http://saberes.senado.leg.br" target="_blank">http://saberes.senado.leg.br.</a></li>
        <li>Não é necessário inserir a identificação de usuário e senha.</li>
        <li>Nessa página, clique em Certificados, localizado na barra superior.</li>
        <li>Na opção Validar Certificado, digite o código constante no verso do certificado.</li>
        <li>Em seguida, clique em Verificar.</li>
        <li>Na tela seguinte, serão exibidos o código de verificação, o nome do aluno, o CPF, o nome do curso realizado, data de emissão do certificado, o período de realização, a carga horária e o programa do curso.</li>
        <li>Este documento poderá ser impresso. Para isso, clique no ícone da impressora que aparece no início da tela.</li>
      </ol>
      </div>

      <div id="resposta2-1" style="margin-top: 12px; margin-bottom: 12px; display: none;">
        <ol>  
          <li>Acesse o Saberes e no bloco <strong>Bem-vindo ao Saberes</strong>, clique em: <strong>Perdeu a senha?</strong></li>
          <li>Preencha as informações solicitadas (Identificação de Usuário ou E-mail).</li>
          <li>Clique em Buscar.</li>
          <li>Será encaminhado um link para o seu correio eletrônico, cadastrado na plataforma para a criação de uma nova senha.</li>
          <li>Não recebeu o link? Entre em contato com o ILB pelo endereço eletrônico ilbead@senado.leg.br.</li> 
        </ol>
      </div>

      <p id="resposta2-2" style="margin-top: 12px; margin-bottom: 12px; display: none;">Escreva para ilbead@senado.leg.br, informando seu nome completo e número do CPF, e solicite a redefinição da sua senha.
      </p>

      <p id="resposta2-3" style="margin-top: 12px; margin-bottom: 12px; display: none;">Para alterar sua senha, clique no campo da foto. Clique na engrenagem localizada no canto superior direito da página do perfil e, em seguida, em Mudar senha.
      Faça a redefinição da senha e clique em Salvar senha.
      </p>

      <p id="resposta3-1" style="margin-top: 12px; margin-bottom: 12px; display: none;">Para alterar dados cadastrais, acesse o Saberes e <strong>clique no campo da foto</strong>. Em seguida, clique em Modificar perfil, faça as alterações necessárias e no, final da página, clique em Atualizar perfil.
      Todas as informações podem ser alteradas, menos o CPF.
      </p>

      <p id="resposta3-2" style="margin-top: 12px; margin-bottom: 12px; display: none;">Cadastro não pode ser cancelado para que o histórico do aluno não seja perdido. Entretanto, você pode solicitar que ele seja inativado pelo período que desejar escrevendo para ilbead@senado.leg.br. 
      </p>

      <p id="resposta3-3" style="margin-top: 12px; margin-bottom: 12px; display: none;">Não, cada aluno pode ter apenas um cadastro na plataforma. Solicite sua Identificação de usuário e/ou senha ao ilbead@senado.leg.br. 
      </p>

      <p id="resposta4-1" style="margin-top: 12px; margin-bottom: 12px; display: none;">O ILB não emite declarações. Caso seja do seu interesse, você pode salvar a página inicial do curso em PDF que contém as informações das atividades realizadas para entregar ao órgão solicitante.
      Caso tenha perdido acesso ao curso, solicite o relatório de atividades através do ilbead@senado.leg.br.  
      </p>

      <p id="resposta4-2" style="margin-top: 12px; margin-bottom: 12px; display: none;">O ILB não emite declarações, entretanto, você poderá obter um relatório de atividades que fez do curso escrevendo para ilbead@senado.leg.br.
      </p>

      <p id="resposta5-1" style="margin-top: 12px; margin-bottom: 12px; display: none;">O ILB, Instituto Legislativo Brasileiro, é a Escola de Governo do Senado Federal, criado por meio da Resolução nº 09, de 29 de janeiro de 1997.
      </p>

      <p id="resposta5-2" style="margin-top: 12px; margin-bottom: 12px; display: none;">Os cursos a distância ofertados pelo ILB são considerados cursos de educação continuada e, por isso, não estão sujeitos ao reconhecimento do MEC, conforme a lei nº. 9394/96; Decreto nº. 5.154/04; Deliberação CEE 14/97 (Indicação CEE 14/97).
      </p>

      <p id="resposta5-3" style="margin-top: 12px; margin-bottom: 12px; display: none;">Algumas instituições de ensino aceitam e até indicam nossos cursos como atividades complementares. Sugerimos que você entre em contato com a secretaria acadêmica da sua faculdade para obter essa informação.
      </p>

      <p id="resposta5-4" style="margin-top: 12px; margin-bottom: 12px; display: none;">Os cursos do ILB não são voltados para licença capacitação, porém muitos servidores fazem nossos cursos para esse fim.
      Caso queira fazer um de nossos cursos em sua licença capacitação, é importante que observe as regras da plataforma quanto ao período do curso:
      O curso tem início no ato da matrícula e o aluno tem até 60 dias para conclui-lo.
      O período de realização do curso que figura no certificado é a data da inscrição a data que o aluno realiza a avalição final.
      Caso o aluno faça a avalição final antes de 20 dias da matriculado no curso, a data de conclusão será a do 20º dia da data da matrícula.
      </p>

      <p id="resposta6-1" style="margin-top: 12px; margin-bottom: 12px; display: none;">Certifique-se que você concluiu o curso com média superior a 70 pontos e que está há mais de 20 dias matriculado no curso.
      O certificado pode levar até 4 horas para ser gerado, após o envido da avaliação final.
      Caso seu certificado não seja gerado após esse período, envie-nos uma mensagem informado o nome do curso.
      </p>

      <p id="resposta7-1" style="margin-top: 12px; margin-bottom: 12px; display: none;">O CPF é a única informação que o aluno não consegue alterar na plataforma. Caso você esteja com problemas no número do CPF, escreve para ilbead@senado.leg.br, informando qual é o problema, seu nome completo e o número do seu CPF.
      </p>

      <p id="resposta7-2" style="margin-top: 12px; margin-bottom: 12px; display: none;">Para alterar dados cadastrais, acesse o Saberes e clique no campo da foto. Em seguida, clique em Modificar perfil, faça as alterações necessárias e no, final da página, clique em Atualizar perfil.
      Todas as informações podem ser alteradas, menos o CPF.
      </p>

      <p id="resposta8-1" style="margin-top: 12px; margin-bottom: 12px; display: none;">Cada aluno pode estar matriculado em até dois cursos sem tutoria ao mesmo tempo.
      Ao concluir o curso, a vaga só é liberada quando o certificado for emitido.
      Em caso de abando ou reprovação, a vaga só é liberada 90 dias após a matrícula no curso.
      </p>

      <p id="resposta9-1" style="margin-top: 12px; margin-bottom: 12px; display: none;">Para acessar o Avalie o curso e a Avaliação final, você deve fazer os exercícios de fixação de cada módulo. Observe se os quadradinhos ao lado dos exercícios estão sinalados. Caso não estejam, você pode marcá-los manualmente. Esse procedimento lhe dará acesso ao Avalie o curso. Assim, a plataforma libera o próximo passo para você continuar o curso.

      <p id="resposta9-2" style="margin-top: 12px; margin-bottom: 12px; display: none;">Caso você tenha identificado algum problema no conteúdo de algum curso, escreva para nós indicando seus questionamentos com o nome, o módulo, a unidade e página do curso.
      </p>

      <p id="resposta9-3" style="margin-top: 12px; margin-bottom: 12px; display: none;">Caso você queira questionar/contestar algum item dos exercícios de fixação, escreva para nós indicando o nome e modulo do curso descrevendo suas observações/objeções.
      </p>

      <p id="resposta9-4" style="margin-top: 12px; margin-bottom: 12px; display: none;">Caso você queira questionar/contestar algum item da avaliação final, escreva para nós indicando o nome e modulo do curso descrevendo suas observações/objeções.
      </p>
    
      <button type="button" class="btn header"><span>Escrever email</span></button>

          <div class="content" style=" display: none; margin-bottom: 12px;">
              
                <div class="form-group">
                  <label class=" control-label"><strong>Mensagem</strong></label>
                    <div class=" inputGroupContainer">
                    <div class="input-group">
                        
                          <textarea rows="10" class="form-control" name="mensagem" placeholder="Project Description"></textarea>
                  </div>
                  </div>
                </div>

                <div class="alert alert-success">
                  <strong>Sucesso!</strong> Mensagem enviada para o sistema de atendimento.
                </div>

                <div class="alert alert-danger">
                  <strong>Erro!</strong> Não foi possível enviar sua mensagem.
                </div>

                <!-- Button -->
                <div class="form-group">
                  <label class=" control-label"></label>
                  <div class="">
                    <button type="submit" class="btn btn-primary" >Enviar <span class="glyphicon glyphicon-send"></span></button>
                  </div>
                </div>

              
          </div>
      </div>



      </fieldset>
      </form>

      <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
      <script type="text/javascript">
          

          $(".header").click(function () {

              $header = $(this);
              //getting the next element
              $content = $header.next();
              //open up the content needed - toggle the slide- if visible, slide up, if not slidedown.
              $content.slideToggle(300, function () {
                  //execute this after slideToggle is done
                  //change text of header based on visibility of content div
                  $header.text(function () {
                      //change text based on condition
                      return $content.is(":visible") ? "Escrever email" : "Escrever email";
                  });
              });

          });
          
          // mensagem confirmando envio fica escondida ate que seja enviado o ticket
          $(".alert-success").hide();
          $(".alert-danger").hide();


          function duvidas_null(){
            $(".duvidas_curso").css('display', 'none');
            $('.duvidas').css('display', 'none');
            $(".duvidas_certificado").css('display', 'none');
            $(".duvidas_senha").css('display', 'none');
            $(".duvidas_cadastro").css('display', 'none');
            $(".duvidas_outras").css('display', 'none');
            $(".duvidas_declaracao").css('display', 'none');
          }

          function recl_null(){
            $(".recl_certificado").css('display', 'none');
            $(".recl_matricula").css('display', 'none');
            $(".recl_curso").css('display', 'none');
            $(".recl_cadastro").css('display', 'none');
          }

          function change_select(sel) {
          
            <?php for ($i = 1; $i <= 14; $i++) {
               
                echo "$('#resposta" . $i ."').css('display', 'none');";
                echo "$('#resposta1-" . $i ."').css('display', 'none');";
                echo "$('#resposta2-" . $i ."').css('display', 'none');";
                echo "$('#resposta3-" . $i ."').css('display', 'none');";
                echo "$('#resposta4-" . $i ."').css('display', 'none');";
                echo "$('#resposta5-" . $i ."').css('display', 'none');";
                echo "$('#resposta6-" . $i ."').css('display', 'none');";
                echo "$('#resposta7-" . $i ."').css('display', 'none');";
                echo "$('#resposta8-" . $i ."').css('display', 'none');";
                echo "$('#resposta9-" . $i ."').css('display', 'none');";
                               
              } ?>
            
            if ($('.camada1').val() == "Dúvidas") {
                $(".duvidas").css('display', 'block');
                $(".reclamacoes").css('display', 'none');
                recl_null();
                // $('select[name=assunto4]').val(null);
                
                if ($('.duvidas').val() == "Dúvidas sobre cursos") {
                  $(".duvidas_curso").css('display', 'block');
                  $(".duvidas_certificado").css('display', 'none');
                  $(".duvidas_senha").css('display', 'none');
                  $(".duvidas_cadastro").css('display', 'none');
                  $(".duvidas_outras").css('display', 'none');
                  $(".duvidas_declaracao").css('display', 'none');
                  $('.reclamacoes').css('display', 'none');
                  recl_null();
                  // $('#resposta1').slideToggle(300);
                  //open up the content needed - toggle the slide- if visible, slide up, if not slidedown.
                }

                if ($('.duvidas').val() == "Dúvidas sobre certificados") {
                  $(".duvidas_certificado").css('display', 'block');
                  $(".duvidas_curso").css('display', 'none');
                  $(".duvidas_senha").css('display', 'none');
                  $(".duvidas_cadastro").css('display', 'none');
                  $(".duvidas_outras").css('display', 'none');
                  $(".duvidas_declaracao").css('display', 'none');
                  $('.reclamacoes').css('display', 'none');
                  recl_null();
                  // $('#resposta1').slideToggle(300);
                  //open up the content needed - toggle the slide- if visible, slide up, if not slidedown.
                }

                if ($('.duvidas').val() == "Dúvidas sobre senhas") {
                  $(".duvidas_senha").css('display', 'block');
                  $(".duvidas_certificado").css('display', 'none');
                  $(".duvidas_curso").css('display', 'none');
                  $(".duvidas_cadastro").css('display', 'none');
                  $(".duvidas_outras").css('display', 'none');
                  $(".duvidas_declaracao").css('display', 'none');
                  $('.reclamacoes').css('display', 'none');
                  recl_null();
                  // $('#resposta1').slideToggle(300);
                  //open up the content needed - toggle the slide- if visible, slide up, if not slidedown.
                }
                if ($('.duvidas').val() == "Dúvidas sobre dados cadastrais") {
                  $(".duvidas_cadastro").css('display', 'block');
                  $(".duvidas_senha").css('display', 'none');
                  $(".duvidas_certificado").css('display', 'none');
                  $(".duvidas_curso").css('display', 'none');
                  $(".duvidas_outras").css('display', 'none');
                  $(".duvidas_declaracao").css('display', 'none');
                  $('.reclamacoes').css('display', 'none');
                  recl_null();
                  // $('#resposta1').slideToggle(300);
                  //open up the content needed - toggle the slide- if visible, slide up, if not slidedown.
                }
                if ($('.duvidas').val() == "Dúvidas sobre declarações") {
                  $(".duvidas_declaracao").css('display', 'block');
                  $(".duvidas_cadastro").css('display', 'none');
                  $(".duvidas_senha").css('display', 'none');
                  $(".duvidas_certificado").css('display', 'none');
                  $(".duvidas_curso").css('display', 'none');
                  $(".duvidas_outras").css('display', 'none');
                  $('.reclamacoes').css('display', 'none');
                  recl_null();
                  // $('#resposta1').slideToggle(300);
                  //open up the content needed - toggle the slide- if visible, slide up, if not slidedown.
                }
                if ($('.duvidas').val() == "Outras Dúvidas") {
                  $(".duvidas_outras").css('display', 'block');
                  $(".duvidas_declaracao").css('display', 'none');
                  $(".duvidas_cadastro").css('display', 'none');
                  $(".duvidas_senha").css('display', 'none');
                  $(".duvidas_certificado").css('display', 'none');
                  $(".duvidas_curso").css('display', 'none');
                  $('.reclamacoes').css('display', 'none');
                  recl_null();
                }
                // $('select[name=assunto1]').val(null);
                // $('select[name=assunto3]').val(null);
            }else{
              if ($('.camada1').val() == "Reclamações") {
                  $('.reclamacoes').css('display', 'block');
                  $('.duvidas').css('display', 'none');
                  duvidas_null();
                
                if ($('.reclamacoes').val() == "Reclamações sobre certificados") {
                  $(".recl_certificado").css('display', 'block');
                  $(".recl_matricula").css('display', 'none');
                  $(".recl_curso").css('display', 'none');
                  $(".recl_cadastro").css('display', 'none');
                    duvidas_null();
                }

                if ($('.reclamacoes').val() == "Reclamações sobre matrícula") {
                  $(".recl_matricula").css('display', 'block');
                  $(".recl_certificado").css('display', 'none');
                  $(".recl_curso").css('display', 'none');
                  $(".recl_cadastro").css('display', 'none');
                  duvidas_null();
                }

                if ($('.reclamacoes').val() == "Reclamações sobre cursos") {
                  $(".recl_curso").css('display', 'block');
                  $(".recl_certificado").css('display', 'none');
                  $(".recl_matricula").css('display', 'none');
                  $(".recl_cadastro").css('display', 'none');
                  duvidas_null();
                }

                if ($('.reclamacoes').val() == "Reclamações sobre dados cadastrais") {
                  $(".recl_cadastro").css('display', 'block');
                  $(".recl_certificado").css('display', 'none');
                  $(".recl_matricula").css('display', 'none');
                  $(".recl_curso").css('display', 'none');
                  duvidas_null();
                }

                if ($('.reclamacoes').val() == "Reclamações sobre tutor") {
                  recl_null();
                  duvidas_null();
                }
                // $('select[name=assunto1]').val(null);
                // $('select[name=assunto2]').val(null);
              }else{
                  $('.reclamacoes').css('display', 'none');
                  $('.duvidas').css('display', 'none');
                  duvidas_null();
                  recl_null();
                  
                  $('select[name=assunto2]').val(null);
                  $('select[name=assunto3]').val(null);
                }
            } 
          }
          function change_select_cursos(sel) {
            // $('#resposta1').slideToggle(300);
            // if ($('.reclamacoes').val() == "Reclamações sobre certificados") {
            //   $(".reclamacoes-certificados").css('display', 'block');
            //   $('select[name=assunto4]').val(null);
            // }
            // else{
            //   $(".reclamacoes-certificados").css('display', 'none');
            //   $('select[name=assunto3]').val(null);
            // }
            if($(".duvidas_curso").val() == "Como faço para me matricular em cursos sem tutoria?"){
              $('#resposta1').slideToggle(300);
              <?php for ($i = 1; $i <= 14; $i++) {
                if ($i != 1) {
                  echo "$('#resposta" . $i ."').css('display', 'none');";
                }
              
              } ?>
            }
            if($(".duvidas_curso").val() == "Como faço para cancelar a matrícula no curso?"){
              $('#resposta2').slideToggle(300);
              <?php for ($i = 1; $i <= 14; $i++) {
                if ($i != 2) {
                  echo "$('#resposta" . $i ."').css('display', 'none');";
                }
              
              } ?>            }
            if($(".duvidas_curso").val() == "Onde posso obter informações sobre carga horária e o conteúdo programático dos cursos?"){
              $('#resposta3').slideToggle(300);
              <?php for ($i = 1; $i <= 14; $i++) {
                if ($i != 3) {
                  echo "$('#resposta" . $i ."').css('display', 'none');";
                }
              
              } ?>
            }
            if($(".duvidas_curso").val() == "Quantos cursos posso fazer ao mesmo tempo?"){
              $('#resposta4').slideToggle(300);
              <?php for ($i = 1; $i <= 14; $i++) {
                if ($i != 4) {
                  echo "$('#resposta" . $i ."').css('display', 'none');";
                }
              
              } ?>
            }
            if($(".duvidas_curso").val() == "Concluí um curso mas não consigo me matricular em outro"){
              $('#resposta5').slideToggle(300);
              <?php for ($i = 1; $i <= 14; $i++) {
                if ($i != 5) {
                  echo "$('#resposta" . $i ."').css('display', 'none');";
                }
              
              } ?>
            }
            if($(".duvidas_curso").val() == "Como faço para não receber mensagens no mural de avisos"){
              $('#resposta6').slideToggle(300);
              <?php for ($i = 1; $i <= 14; $i++) {
                if ($i != 6) {
                  echo "$('#resposta" . $i ."').css('display', 'none');";
                }
              
              } ?>
            }
            if($(".duvidas_curso").val() == "Quantos dias ainda tenho para concluir o curso?"){
              $('#resposta7').slideToggle(300);
              <?php for ($i = 1; $i <= 14; $i++) {
                if ($i != 7) {
                  echo "$('#resposta" . $i ."').css('display', 'none');";
                }
              
              } ?>
            }
            if($(".duvidas_curso").val() == "Como faço para imprimir o conteúdo do curso?"){
              $('#resposta8').slideToggle(300);
              <?php for ($i = 1; $i <= 14; $i++) {
                if ($i != 8) {
                  echo "$('#resposta" . $i ."').css('display', 'none');";
                }
              
              } ?>
            }
            if($(".duvidas_curso").val() == "Concluí o curso e quero imprimir o conteúdo, como faço?"){
              $('#resposta9').slideToggle(300);
              <?php for ($i = 1; $i <= 14; $i++) {
                if ($i != 9) {
                  echo "$('#resposta" . $i ."').css('display', 'none');";
                }
              
              } ?>
            }
            if($(".duvidas_curso").val() == "Não consigo acessar o avalie o curso e a avaliação final"){
              $('#resposta10').slideToggle(300);
              <?php for ($i = 1; $i <= 14; $i++) {
                if ($i != 10) {
                  echo "$('#resposta" . $i ."').css('display', 'none');";
                }
              
              } ?>
            }
            if($(".duvidas_curso").val() == "Não consigo acessar o conteúdo do curso, o que fazer?"){
              $('#resposta11').slideToggle(300);
              <?php for ($i = 1; $i <= 14; $i++) {
                if ($i != 11) {
                  echo "$('#resposta" . $i ."').css('display', 'none');";
                }
              
              } ?>
            }
            if($(".duvidas_curso").val() == "Não fui aprovado no curso, e agora?"){
              $('#resposta12').slideToggle(300);
              <?php for ($i = 1; $i <= 14; $i++) {
                if ($i != 12) {
                  echo "$('#resposta" . $i ."').css('display', 'none');";
                }
              
              } ?>
            }
            if($(".duvidas_curso").val() == "Como faço para me matricular em cursos COM TUTORIA?"){
              $('#resposta13').slideToggle(300);
              <?php for ($i = 1; $i <= 14; $i++) {
                if ($i != 13) {
                  echo "$('#resposta" . $i ."').css('display', 'none');";
                }
              
              } ?>
            }
            if($(".duvidas_curso").val() == "Como faço para me inscrever em cursos de pós graduação?"){
              $('#resposta14').slideToggle(300);
              <?php for ($i = 1; $i <= 14; $i++) {
                if ($i != 14) {
                  echo "$('#resposta" . $i ."').css('display', 'none');";
                }
              
              } ?>
            }
          }

          function change_select_certificados(sel){
            if($(".duvidas_certificado").val() == "Como faço para imprimir meu certificado? Fora do curso"){
              $('#resposta1-1').slideToggle(300);
              <?php for ($i = 1; $i <= 6; $i++) {
                if ($i != 1) {
                  echo "$('#resposta1-" . $i ."').css('display', 'none');";
                }
              } ?>
            }
            if($(".duvidas_certificado").val() == "Como faço para imprimir certificados antigos?(Entre 2010 e 2013)"){
              $('#resposta1-2').slideToggle(300);
              <?php for ($i = 1; $i <= 6; $i++) {
                if ($i != 2) {
                  echo "$('#resposta1-" . $i ."').css('display', 'none');";
                }
              } ?>
            }
            if($(".duvidas_certificado").val() == "Quais informações constam no certificado?"){
              $('#resposta1-3').slideToggle(300);
              <?php for ($i = 1; $i <= 6; $i++) {
                if ($i != 3) {
                  echo "$('#resposta1-" . $i ."').css('display', 'none');";
                }
              } ?>
            }
            if($(".duvidas_certificado").val() == "No verso do certificado existe um código com letras e números. Para que serve?"){
              $('#resposta1-4').slideToggle(300);
              <?php for ($i = 1; $i <= 6; $i++) {
                if ($i != 4) {
                  echo "$('#resposta1-" . $i ."').css('display', 'none');";
                }
              } ?>
            }
          }

         function change_select_senha(sel){
          if($(".duvidas_senha").val() == "Esqueci minha senha, o que fazer?"){
            $('#resposta2-1').slideToggle(300);
            <?php for ($i = 1; $i <= 3; $i++) {
              if ($i != 1) {
                echo "$('#resposta2-" . $i ."').css('display', 'none');";
              }
            } ?>
          }
          if($(".duvidas_senha").val() == "Esqueci minha senha e não me lembro qual endereço email cadastrei na plataforma"){
            $('#resposta2-2').slideToggle(300);
            <?php for ($i = 1; $i <= 3; $i++) {
              if ($i != 2) {
                echo "$('#resposta2-" . $i ."').css('display', 'none');";
              }
            } ?>
          }
          if($(".duvidas_senha").val() == "Como faço para alterar minha senha?"){
            $('#resposta2-3').slideToggle(300);
            <?php for ($i = 1; $i <= 3; $i++) {
              if ($i != 3) {
                echo "$('#resposta2-" . $i ."').css('display', 'none');";
              }
            } ?>
          }
        }

         function change_select_cadastro(sel){
          if($(".duvidas_cadastro").val() == "Como posso alterar meus dados cadastrais?"){
            $('#resposta3-1').slideToggle(300);
            <?php for ($i = 1; $i <= 4; $i++) {
              if ($i != 1) {
                echo "$('#resposta3-" . $i ."').css('display', 'none');";
              }
            } ?>
          }
          if($(".duvidas_cadastro").val() == "Como faço para cancelar meu cadastro?"){
            $('#resposta3-2').slideToggle(300);
            <?php for ($i = 1; $i <= 4; $i++) {
              if ($i != 2) {
                echo "$('#resposta3-" . $i ."').css('display', 'none');";
              }
            } ?>
          }
          if($(".duvidas_cadastro").val() == "Não consigo acessar o saberes, posso fazer um novo cadastro?"){
            $('#resposta3-3').slideToggle(300);
            <?php for ($i = 1; $i <= 4; $i++) {
              if ($i != 3) {
                echo "$('#resposta3-" . $i ."').css('display', 'none');";
              }
            } ?>
          }
        }

         function change_select_declaracao(){
            if($(".duvidas_declaracao").val() == "Como posso alterar meus dados cadastrais?"){
              $('#resposta4-1').slideToggle(300);
              <?php for ($i = 1; $i <= 2; $i++) {
                if ($i != 1) {
                  echo "$('#resposta4-" . $i ."').css('display', 'none');";
                }
              } ?>
            }
            if($(".duvidas_declaracao").val() == "Preciso de uma declaração de que fiz o curso, mas não fui aprovado"){
              $('#resposta4-2').slideToggle(300);
              <?php for ($i = 1; $i <= 2; $i++) {
                if ($i != 2) {
                  echo "$('#resposta4-" . $i ."').css('display', 'none');";
                }
              } ?>
            }
          }

          function change_select_outras(){
            if($(".duvidas_outras").val() == "O que é ILB?"){
              $('#resposta5-1').slideToggle(300);
              <?php for ($i = 1; $i <= 4; $i++) {
                if ($i != 1) {
                  echo "$('#resposta5-" . $i ."').css('display', 'none');";
                }
              } ?>
            }
            if($(".duvidas_outras").val() == "Os cursos a distância oferecidos pelo ILB são reconhecidos pelo MEC?"){
              $('#resposta5-2').slideToggle(300);
              <?php for ($i = 1; $i <= 4; $i++) {
                if ($i != 2) {
                  echo "$('#resposta5-" . $i ."').css('display', 'none');";
                }
              } ?>
            }
            if($(".duvidas_outras").val() == "Os certificados do ILB podem ser utilizados como crédito para curso de graduação?"){
              $('#resposta5-3').slideToggle(300);
              <?php for ($i = 1; $i <= 4; $i++) {
                if ($i != 3) {
                  echo "$('#resposta5-" . $i ."').css('display', 'none');";
                }
              } ?>
            }
            if($(".duvidas_outras").val() == "Posso usar os cursos do ILB para licença capacitação"){
              $('#resposta5-4').slideToggle(300);
              <?php for ($i = 1; $i <= 4; $i++) {
                if ($i != 4) {
                  echo "$('#resposta5-" . $i ."').css('display', 'none');";
                }
              } ?>
            }
          }

          function change_select_recl_certificado(){
            if($(".recl_certificado").val() == "Meu certificado não foi gerado"){
              $('#resposta6-1').slideToggle(300);
            }
          }

          function change_select_recl_cadastro(){
            if($(".recl_cadastro").val() == "Não consigo alterar meu CPF"){
              $('#resposta7-1').slideToggle(300);
              <?php for ($i = 1; $i <= 2; $i++) {
                if ($i != 1) {
                  echo "$('#resposta7-" . $i ."').css('display', 'none');";
                }
              } ?>
            }
             if($(".recl_cadastro").val() == "Erro no meu cadastro"){
              $('#resposta7-2').slideToggle(300);
              <?php for ($i = 1; $i <= 2; $i++) {
                if ($i != 2) {
                  echo "$('#resposta7-" . $i ."').css('display', 'none');";
                }
              } ?>
            }
          }

          function change_select_recl_matricula(){
            if($(".recl_matricula").val() == "Concluí o curso mas não consigo me matricular em outro"){
              $('#resposta8-1').slideToggle(300);
            }
          }

          function change_select_recl_curso(){
            if($(".recl_curso").val() == "Não consigo acessar o Avalie o Curso e a Avaliação Final"){
              $('#resposta9-1').slideToggle(300);
              <?php for ($i = 1; $i <= 4; $i++) {
                if ($i != 1) {
                  echo "$('#resposta9-" . $i ."').css('display', 'none');";
                }
              } ?>
            }
             if($(".recl_curso").val() == "Erro no conteúdo do curso"){
              $('#resposta9-2').slideToggle(300);
              <?php for ($i = 1; $i <= 4; $i++) {
                if ($i != 2) {
                  echo "$('#resposta9-" . $i ."').css('display', 'none');";
                }
              } ?>
            }
            if($(".recl_curso").val() == "Erro nos exercícios de fixação"){
              $('#resposta9-3').slideToggle(300);
              <?php for ($i = 1; $i <= 4; $i++) {
                if ($i != 3) {
                  echo "$('#resposta9-" . $i ."').css('display', 'none');";
                }
              } ?>
            }
            if($(".recl_curso").val() == "Erro na avaliação final"){
              $('#resposta9-4').slideToggle(300);
              <?php for ($i = 1; $i <= 4; $i++) {
                if ($i != 4) {
                  echo "$('#resposta9-" . $i ."').css('display', 'none');";
                }
              } ?>
            }
          }

          // $('select[name=assunto]').val(assunto);

         // this is the id of the form
          $("#ticket_form").submit(function(e) {

             if( document.getElementById('ticket_form').mensagem.value == "" )
             {
                alert( "Por favor preencha o campo mensagem!" );
                document.getElementById('ticket_form').mensagem.focus() ;
                return false;
             }

            var url = "ticket.php"; // the script where you handle the form input.
             
            $.ajax({
              type: "POST",
              url: url,
              data: $("#ticket_form").serialize(), // serializes the form's elements.
              success: function(data)
              {
              // alert("Ticket enviado com sucesso"); // show response from the php script.
                $(".alert-success").fadeTo(1000, 0.9);
              },
              error: function(data)
              {
              // alert("Ticket enviado com sucesso"); // show response from the php script.
                $(".alert-danger").fadeTo(1000, 0.9);
              }
            });

            e.preventDefault(); // avoid to execute the actual submit of the form.
            setTimeout(function() {
              $('.alert-success').fadeOut('slow');
              $('.alert-danger').fadeOut('slow');

            }, 4000); // <-- time in milliseconds
          });
      </script>

<?php 



//Link to calendar export page.
// echo $OUTPUT->container_start('bottom');


// echo $OUTPUT->container_end();
echo html_writer::end_tag('div');
echo $renderer->complete_layout();
echo $OUTPUT->footer();


