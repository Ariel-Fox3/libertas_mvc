<?php 
date_default_timezone_set('America/Sao_Paulo');
include("../../../class/class.Conexao.php");
include("../../../class/class.Func.php");
include("../../../class/class.Aluno_CursoVO.php");
include("../../../class/DAO/class.Aluno_CursoDAO.php");

$aluno_cursoDAO = new Aluno_CursoDAO();
$aluno_curso = $aluno_cursoDAO->getById($_POST['id']);
$aluno_cursoDAO->delete($aluno_curso);
?>