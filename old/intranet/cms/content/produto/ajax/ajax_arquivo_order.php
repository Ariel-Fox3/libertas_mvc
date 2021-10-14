<?php 
include("../../../class/class.Conexao.php");
include("../../../class/class.ArquivoVO.php");
include("../../../class/DAO/class.ArquivoDAO.php");

$arquivoDAO = new ArquivoDAO();

$files = explode(',', $_POST['str']);
$cnt = 0;
foreach ($files as $idf) {
  $arquivo = $arquivoDAO->getById($idf);
  if($arquivo->getIdarquivo() != null) {  
    $arquivo->setOrder($cnt);
    $arquivoDAO->save($arquivo);
    $cnt++;
  }
}

?>