<?php 
include("../class/class.Conexao.php");
include("../class/class.CategoriaVO.php");
include("../class/DAO/class.CategoriaDAO.php");
include("../class/class.Func.php");

$categoriaDAO = new CategoriaDAO();

?>
<script>
$('document').ready(function() {
  $('a.removeCategoria').click(function() {

    var del_id = $(this).attr('id');
    var parent = $(this).parent();
    $.post('categoria/ajax/ajax_del.php', {
      id: del_id
    }, function(data) {

      if (data == '') {
        parent.slideUp('slow', function() {
          //$(this).remove();
          //$(this).remove();
          alert("Removido com sucesso!");
          $(this).closest('tr').remove();

        });
      } else {
        alert(data);
      }
    });
  });
  $('a.desativaCategoria').click(function() {

    var del_id = $(this).attr('id');
    var parent = $(this).parent();
    $.post('categoria/ajax/ajax_desativa.php', {
      id: del_id
    }, function(data) {
      alert(data);
      location.reload();
    });
  });
});
</script>

<div class="card">
  <div class="card-header">Gerenciar categorias</div>
  <div class="card-body">
    <ul class="list-inline">
      <li class="list-inline-item">
        <a href="?pg=novo&lc=categoria" class="btn btn-labeled btn-success">
          <span class="btn-label"><i class="fas fa-plus"></i></span>
          Adicionar nova categoria
        </a>
      </li>
    </ul>
    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
      <thead>
        <tr>
          <th class="text-center">Imagem</th>
          <th class="text-center">Nome</th>
          <th class="text-center">Ativo</th>
          <th class="text-center">Opções</th>
        </tr>
      </thead>
      <tbody>
        <?php 
          $listCategorias = $categoriaDAO->getAll();
          if(sizeof($listCategorias) > 0) {
            foreach($listCategorias as $objVo) {
              printf('<tr class="gradeU %s">', $objVo->getAtivo() == '1' ? '' : 'bg-warning');
              if ($objVo->getImagem() != '') {
                printf('<td width="100" class="text-center"><img src="../../uploads/%s" width="100"></td>', $objVo->getImagem());
              } else {
                printf('<td></td>');
              }
              printf('<td class="text-center">%s</td>', $objVo->getNome());
              printf('<td class="text-center">%s</td>', $objVo->getAtivo() ? "<i class='fas fa-check'>" : "não"); 
              printf('<td class="text-center">');
                printf('<a href="?pg=alterar&lc=categoria&id=%s" class="btn btn-dark" data-toggle="tooltip" alt="Editar categoria" title="Editar categoria"><i class="fas fa-pencil-alt"></i></a> ', $objVo->getIdcategoria());
                if ($admin == '1') {
                  printf('<a href="#" class="btn btn-danger removeCategoria" id="%s" data-toggle="tooltip" alt="Remover categoria" title="Remover categoria"><i class="fas fa-trash"></i></a> ', $objVo->getIdcategoria());
                }
                printf('<a href="#" class="btn btn-%s desativaCategoria" id="%s" data-toggle="tooltip" alt="Desativar categoria" title="Desativar categoria"><i class="fas fa-%s"></i></a> ', $objVo->getAtivo() == '1' ? 'warning' : 'success', $objVo->getIdcategoria(), $objVo->getAtivo() == '1' ? 'times' : 'check');
              printf('</td>');
              printf('</tr>');
            }
          }
        ?>
      </tbody>
    </table>
  </div>
</div>