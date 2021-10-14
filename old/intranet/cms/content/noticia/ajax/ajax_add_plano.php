<?php 
date_default_timezone_set('America/Sao_Paulo');
include("../../../class/class.Conexao.php");
include("../../../class/class.Func.php");
include("../../../class/class.AlunoVO.php");
include("../../../class/DAO/class.AlunoDAO.php");
include("../../../class/class.Aluno_PlanoVO.php");
include("../../../class/DAO/class.Aluno_PlanoDAO.php");

$alunoDAO = new AlunoDAO();
$aluno_planoDAO = new Aluno_PlanoDAO();
$aluno = $alunoDAO->getById($_POST['idaluno']);

$aluno_plano = new Aluno_PlanoVO();
$aluno_plano->setIdaluno($aluno->getIdaluno());
$aluno_plano->setIdplano($_POST['inputPlano']);
$dti = DateTime::createFromFormat('d/m/Y', $_POST['inputDti']);
$dtf = DateTime::createFromFormat('d/m/Y', $_POST['inputDtf']);
$aluno_plano->setDti($dti->format('Y-m-d'));
$aluno_plano->setDtf($dtf->format('Y-m-d'));
$aluno_plano->setComentario('Liberaçao manual');
$aluno_plano->setJoined(date('Y-m-d H:i:s'));
$aluno_planoDAO->save($aluno_plano);
?>