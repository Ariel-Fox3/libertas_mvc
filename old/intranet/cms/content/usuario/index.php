<?php 
include("../class/class.Conexao.php");
include("../class/class.UsuarioVO.php");
include("../class/DAO/class.UsuarioDAO.php");
include("../class/class.Funcao.php");

$usuarioDAO = new UsuarioDAO();

?>
<script>
$('document').ready(function() {
  $('a.removeUsuario').click(function() {

    var del_id = $(this).attr('id');
    var parent = $(this).parent();
    $.post('usuario/ajax/ajax_del.php', {
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
  $('a.desativaUsuario').click(function() {

    var del_id = $(this).attr('id');
    var parent = $(this).parent();
    $.post('usuario/ajax/ajax_desativa.php', {
      id: del_id
    }, function(data) {
      alert(data);
      location.reload();
    });
  });
});
</script>

<div class="card">
  <div class="card-header">Gerenciar usuários</div>
  <div class="card-body">
    <ul class="list-inline">
      <li class="list-inline-item">
        <a href="?pg=novo&lc=usuario" class="btn btn-labeled btn-success">
          <span class="btn-label"><i class="fas fa-plus"></i></span>
          Adicionar novo usuário
        </a>
      </li>
    </ul>
    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
      <thead>
        <tr>
          <th class="text-center">Opções</th>
          <th class="text-center">Usuário</th>
          <th class="text-center">Ultimo Acesso</th>
          <th class="text-center">Ativo</th>
        </tr>
      </thead>
      <tbody>
        <?php 
          $listUsuario = $usuarioDAO->getAll();
          if(sizeof($listUsuario) > 0) {
            foreach($listUsuario as $objVo) {
              printf('<tr class="gradeU %s">', $objVo->getAtivo() == '1' ? '' : 'bg-warning');
              printf('<td class="text-center">');
              printf('<a href="?pg=alterar&lc=usuario&idusuario=%s" class="btn btn-dark" alt="Editar usuário" title="Editar usuário"><i class="fas fa-pencil-alt"></i></a> ', $objVo->getIdusuario());
              printf('<a href="#" class="btn btn-danger removeUsuario" id="%s" alt="Remover usuário" title="Remover usuário"><i class="fas fa-trash"></i></a> ', $objVo->getIdusuario());
              printf('<a href="#" class="btn btn-%s desativaUsuario" id="%s" alt="Desativar usuário" title="Desativar usuário"><i class="fas fa-%s"></i></a> ', $objVo->getAtivo() == '1' ? 'warning' : 'success', $objVo->getIdusuario(), $objVo->getAtivo() == '1' ? 'times' : 'check');
              printf('</td>');
              printf('<td class="text-center">%s</td>', $objVo->getEmail());
              printf('<td class="text-center">%s</td>', $objVo->getUltimo_acesso() && $objVo->getUltimo_acesso() != '0000-00-00 00:00:00' ? @timeAgo($objVo->getUltimo_acesso()) : "-");
              printf('<td class="text-center">%s</td>', $objVo->getAtivo() ? "<i class='fas fa-check'>" : "não"); 
              printf('</tr>');
            }
          }
        ?>
      </tbody>
    </table>
  </div>
</div>