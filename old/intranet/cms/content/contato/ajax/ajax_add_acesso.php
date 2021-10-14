<?php 
date_default_timezone_set('America/Sao_Paulo');
include("../../../class/class.Conexao.php");
include("../../../class/class.Func.php");
include("../../../class/class.Aluno_CursoVO.php");
include("../../../class/DAO/class.Aluno_CursoDAO.php");
include("../../../class/class.AlunoVO.php");
include("../../../class/DAO/class.AlunoDAO.php");
include("../../../class/class.EmpresaVO.php");
include("../../../class/DAO/class.EmpresaDAO.php");

$aluno_cursoDAO = new Aluno_CursoDAO();
$alunoDAO = new AlunoDAO();
$empresaDAO = new EmpresaDAO();
$aluno = $alunoDAO->getById($_POST['idaluno']);

$aluno_curso = new Aluno_CursoVO();
$aluno_curso->setIdaluno($_POST['idaluno']);
$dti = DateTime::createFromFormat('d/m/Y', $_POST['inputDti']);
$dtf = DateTime::createFromFormat('d/m/Y', $_POST['inputDtf']);
$aluno_curso->setDti($dti->format('Y-m-d'));
$aluno_curso->setDtf($dtf->format('Y-m-d'));
$aluno_curso->setComentario('Liberação manual');
$inputs = array(
  isset($_POST['inputFull_outros']) && $_POST['inputFull_outros'] == 1 ? true : false,
  isset($_POST['inputOutro']) && $_POST['inputOutro'] != '' ? true : false,
  isset($_POST['inputFull_access']) && $_POST['inputFull_access'] == 1 ? true : false,
  isset($_POST['inputCurso']) && $_POST['inputCurso'] != '' ? true : false
);

$cnt = 0;
foreach ($inputs as $input) {
  if ($input == false) {
    $cnt++;
  }
}

if ($cnt == sizeof($inputs)) {
  echo 'Você precisa escolher ao menos um conteúdo para liberar acesso.';
  exit;
}

$full_aulas = false;
$full_outros = false;
$idcurso = null;
$idoutro = null;

if (isset($_POST['inputFull_outros']) && $_POST['inputFull_outros'] == 1) {
  $aluno_curso->setFull_outros(1);
  $full_outros = true;
} else {
  if (isset($_POST['inputOutro']) && $_POST['inputOutro'] != '') {
    $aluno_curso->setIdoutro($_POST['inputOutro']);
    $idoutro = $_POST['inputOutro'];
  }
}

if (isset($_POST['inputFull_access']) && $_POST['inputFull_access'] == 1) {
  $aluno_curso->setFull_access(1);
  $full_aulas = true;
} else {
  if (isset($_POST['inputCurso']) && $_POST['inputCurso'] != '') {
    $aluno_curso->setIdcurso($_POST['inputCurso']);
    $idcurso = $_POST['inputCurso'];
  }
}

if ($aluno->getEmpresa() != 0) {
  $acesso_empresa = $aluno_cursoDAO->getByIdempresa($aluno->getEmpresa(), null, $full_aulas, $full_outros, $idcurso, $idoutro);
  if (sizeof($acesso_empresa) > 0) {
    $empresa = $empresaDAO->getById($aluno->getEmpresa());
    echo 'O aluno já possuí acesso ao conteúdo selecionado pois pertence a empresa ' . $empresa->getNome();
    exit;
  }
}
$aluno_curso->setJoined(date('Y-m-d H:i:s'));

$aluno_curso = $aluno_cursoDAO->save($aluno_curso);
?>