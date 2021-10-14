<?php

  include('../../../class/class.Conexao.php');
  include('../../../class/class.Func.php');
  include("../../../class/class.ProdutoVO.php");
  include("../../../class/DAO/class.ProdutoDAO.php");
  include('../../../class/class.Categoria_ProdutoVO.php');
  include('../../../class/DAO/class.Categoria_ProdutoDAO.php');

  $produtoDAO = new ProdutoDAO();
  $categoria_produtoDAO = new Categoria_ProdutoDAO();

  $limit = $_POST['limit'];
  $offset = $_POST['index'];
  @$idc = isset($_POST['idc']) && $_POST['idc'] != '' ? $_POST['idc'] : null;

  $listProdutos = $produtoDAO->getByFiltro($idc, $limit, $offset, false);

  if (sizeof($listProdutos) > 0) {
    foreach ($listProdutos as $objVo) {
      $categorias = json_decode($objVo->getIdcategoria(), true);
      $categoria = array();
      if (sizeof($categorias) > 0) {
        foreach ($categorias as $idc) {
          $categoria[] = $categoria_produtoDAO->getById($idc)->getNome();
        }
      }
      $categoria = implode(', ', $categoria);
    
      printf('<tr class="gradeU %s" id="%s">', $objVo->getAtivo() == '1' ? '' : 'bg-warning', $objVo->getIdproduto());
      printf('<td><a class="btn btn-secondary text-white btn-move"><i class="fas fa-arrows-alt-v"></i></a></td>');
      printf('<td class="text-center">');
      printf('<a href="?pg=alterar&lc=produto&id=%s" class="btn btn-dark" alt="Editar produto" data-toggle="tooltip" title="Editar produto"><i class="fas fa-pencil-alt"></i></a> ', $objVo->getIdproduto());
      printf('<a href="#" class="btn btn-danger removeUsuario" id="%s" alt="Remover produto" data-toggle="tooltip" title="Remover produto"><i class="fas fa-trash"></i></a> ', $objVo->getIdproduto());
      printf('<a href="#" class="btn btn-%s desativaUsuario" id="%s" alt="Desativar produto" data-toggle="tooltip" title="Desativar produto"><i class="fas fa-%s"></i></a> ', $objVo->getAtivo() == '1' ? 'warning' : 'success', $objVo->getIdproduto(), $objVo->getAtivo() == '1' ? 'times' : 'check');
      printf('<a class="btn btn-primary" href="?pg=arquivo&lc=produto&id=%s"><i class="fas fa-paperclip"></i></i></a>', $objVo->getIdproduto());
      printf('</td>');
      printf('<td class="text-center"><img style="width: 100px;" src="%sintranet/uploads/%s" /></td>', getUrl(), $objVo->getImagem());
      printf('<td class="text-center">%s</td>', $objVo->getNome());
      printf('<td class="text-center">%s</td>', $categoria);
      printf('<td class="text-center">%s</td>', $objVo->getAtivo() ? "<i class='fas fa-check'>" : "<i class='fas fa-times'></i>"); 
      printf('</tr>');
    }
  }
  
?>