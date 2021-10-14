<?php 
include("../../../class/class.Conexao.php");
include("../../../class/class.ProdutoVO.php");
include("../../../class/DAO/class.ProdutoDAO.php");

$produtoDAO = new ProdutoDAO();

if(isset($_POST['str'])) {
  $produtos = explode(',', $_POST['str']);
  $cnt = 0;
  foreach ($produtos as $idp) {
    $produto = $produtoDAO->getById($idp);
    if($produto->getIdproduto() != null) {  
      $produto->setOrdem($cnt);
      $produtoDAO->save($produto);
      $cnt++;
    }
  }
}

?>