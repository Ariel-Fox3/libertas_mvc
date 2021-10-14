<?php 
include("../class/class.Conexao.php");
include("../class/class.CategoriaVO.php");
include("../class/DAO/class.CategoriaDAO.php");
include("../class/class.NoticiaVO.php");
include("../class/DAO/class.NoticiaDAO.php");
include("../class/class.Func.php");

$categoriaDAO = new CategoriaDAO();
$noticiaDAO = new NoticiaDAO();

?>
<script>
$('document').ready(function() {
  $('a.removeNoticia').click(function() {

    var del_id = $(this).attr('id');
    var parent = $(this).parent();
    $.post('noticia/ajax/ajax_del.php', {
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
  $('a.desativaNoticia').click(function() {

    var del_id = $(this).attr('id');
    var parent = $(this).parent();
    $.post('noticia/ajax/ajax_desativa.php', {
      id: del_id
    }, function(data) {
      alert(data);
      location.reload();
    });
  });
});
</script>

<div class="card">
  <div class="card-header">Gerenciar notícias</div>
  <div class="card-body">
    <ul class="list-inline">
      <li class="list-inline-item">
        <a href="?pg=novo&lc=noticia" class="btn btn-labeled btn-success">
          <span class="btn-label"><i class="fas fa-plus"></i></span>
          Adicionar nova notícia
        </a>
      </li>
    </ul>
    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
      <thead>
        <tr>
          <th class="text-center">Imagem</th>
          <th class="text-center">Nome</th>
          <!-- <th class="text-center">Tags</th> -->
          <th class="text-center">Data</th>
          <th class="text-center">Ativo</th>
          <th class="text-center">Opções</th>
        </tr>
      </thead>
      <tbody>
        <?php 
          $listNoticias = $noticiaDAO->getAll();
          if(sizeof($listNoticias) > 0) {
            foreach($listNoticias as $objVo) {
              $cat = $categoriaDAO->getById($objVo->getIdcategoria());
              printf('<tr class="gradeU %s">', $objVo->getAtivo() == '1' ? '' : 'bg-warning');
              printf('<td width="100" class="text-center"><img src="../../uploads/%s" width="100" height="100"></td>', $objVo->getImagem());
              printf('<td class="text-center">%s<br><small>%s</small></td>', $objVo->getNome(), $cat->getNome());
              // printf('<td class="text-center">%s</td>', $objVo->getTags());
              $d = DateTime::createFromFormat('Y-m-d H:i:s', $objVo->getData());
              printf('<td class="text-center">%s</td>', $d->format('d/m/Y'));
              printf('<td class="text-center">%s</td>', $objVo->getAtivo() ? "<i class='fas fa-check'>" : "não"); 
              printf('<td class="text-center">');
                printf('<a href="?pg=alterar&lc=noticia&id=%s" class="btn btn-dark" data-toggle="tooltip" alt="Editar notícia" title="Editar notícia"><i class="fas fa-pencil-alt"></i></a> ', $objVo->getIdnoticia());
                printf('<a href="#" class="btn btn-danger removeNoticia" id="%s" data-toggle="tooltip" alt="Remover notícia" title="Remover notícia"><i class="fas fa-trash"></i></a> ', $objVo->getIdnoticia());
                printf('<a href="#" class="btn btn-%s desativaNoticia" id="%s" data-toggle="tooltip" alt="Desativar notícia" title="Desativar notícia"><i class="fas fa-%s"></i></a> ', $objVo->getAtivo() == '1' ? 'warning' : 'success', $objVo->getIdnoticia(), $objVo->getAtivo() == '1' ? 'times' : 'check');
                printf('<a href="?pg=arquivo&lc=noticia&id=%s" class="btn btn-info" data-toggle="tooltip" alt="Arquivos da notícia" title="Arquivos da notícia"><i class="fas fa-paperclip"></i></a> ', $objVo->getIdnoticia());
              printf('</td>');
              printf('</tr>');
            }
          }
        ?>
      </tbody>
    </table>
  </div>
</div>