<?php 
date_default_timezone_set('America/Sao_Paulo');
include("../../../class/class.Conexao.php");
include("../../../class/class.Func.php");
include("../../../class/class.Aluno_PlanoVO.php");
include("../../../class/DAO/class.Aluno_PlanoDAO.php");

$aluno_planoDAO = new Aluno_PlanoDAO();
$aluno_plano = $aluno_planoDAO->getById($_POST['id']);
$aluno_planoDAO->delete($aluno_plano);
?>