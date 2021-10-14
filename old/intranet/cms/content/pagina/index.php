<?php
include("../class/class.Conexao.php");
include("../class/class.PaginaVO.php");
include("../class/DAO/class.PaginaDAO.php");

$paginaDAO = new PaginaDAO();
@$idpagina = (int)$_GET['id'];
$con_pag = 0;
if(isset($idpagina) && !empty($idpagina)) {
  $listPaginas = $paginaDAO->getByIdsublink($idpagina);
} else {
  $listPaginas = $paginaDAO->getAll();
  $con_pag = 1;
}

?>
<script>
$('document').ready(function() {
  $('a.removePagina').click(function() {

    var del_id = $(this).attr('id');
    var parent = $(this).parent();
    $.post('pagina/ajax/ajax_del.php', {
      id: del_id
    }, function(data) {

      if (data == '') {
        location.reload();
      } else {
        alert(data);
      }
    });
  });

  $(function() {
    $("#tabela-paginas").sortable({
      items: 'tbody tr',
      axis: 'y',
      cursor: 'n-resize',
      helper: 'clone',
      zIndex: 9999,
      placeholder: {
        element: function(currentItem) {
          return $(`<div class="drop-placeholder">
                      <div class="borda">
                        <span><i class="fas fa-clone"></i></span>
                      </div>
                    </div>`)[0];
        },
        update: function(container, p) {
          return;
        }
      },
      start: function(event, ui) {
        $(ui.helper).toggleClass("highlight");
        $(ui.item[0]).show().css('opacity', '0');
      },
      stop: function(event, ui) {
        $(ui.helper).toggleClass("highlight");
        $(ui.item[0]).css('opacity', '1');
        var itemOrder = $('#tabela-paginas').sortable("toArray");
        var arr = [];
        for (var i = 0; i < itemOrder.length; i++) {
          arr.push(itemOrder[i]);
        }
        var strval = arr.join(',');

        $.ajax({
          url: "pagina/ajax/ajax_pagina_order.php",
          type: 'post',
          data: {
            str: strval
          },
          beforeSend: function() {
            $("#ordem-salva").fadeOut();
          }
        })
        .done(function(msg) {
          $("#ordem-salva").fadeIn();
        })
        .fail(function(jqXHR, textStatus, msg) {
          alert(msg);
        });
        }
    });
    $("#tabela-paginas").disableSelection();

  });

});
</script>
<div class="card">
  <div class="card-header">Gerenciar páginas</div>
  <div class="card-body">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="index.php?pg=index&lc=pagina">Paginas</a></li>
        <?php
          if(isset($idpagina) && !empty($idpagina)) {
            $paginaLoad = $paginaDAO->getById($idpagina);
            if($con_pag == 0) {
              @$paginaAntes = $paginaDAO->getById($paginaLoad->getIdsublink());
              if($paginaAntes != null) {
                printf('<li class="breadcrumb-item"><a href="index.php?pg=index&lc=pagina&id=%s">%s</a></li>', $paginaAntes->getIdpagina(), $paginaAntes->getNome());
              }
            }
            printf('<li class="breadcrumb-item active">%s</li>', $paginaLoad->getNome());
          } 
        ?>
    </ol>
    <ul class="list-inline">
      <li class="list-inline-item">
        <a href="?pg=novo&lc=pagina<?=isset($idpagina) && $idpagina > 0 ? '&id='.$idpagina : ''?>" class="btn btn-labeled btn-success">
          <span class="btn-label"><i class="fas fa-plus"></i></span>
          Adicionar nova página
        </a>
      </li>
    </ul>

    <span id="ordem-salva" class="float-right text-success" style="display: none"><i class="fas fa-check"></i> Salvo</span>
    <table class="table table-striped table-bordered table-hover" id="tabela-paginas">
      <thead>
        <tr>
          <th width="25px"></th>
          <th>Opções</th>
          <th>Nome</th>
          <th>Sublink</th>
          <th class="text-center">Fixa</th>
        </tr>
      </thead>
      <tbody>
        <?php
          if(sizeof($listPaginas) > 0) {
            foreach($listPaginas as $objVo) {
              if($objVo->getFixo() == $con_pag) {
                $subs = $paginaDAO->getByIdsublink($objVo->getIdpagina());
                printf('<tr class="gradeU %s" id="%s">', $objVo->getAtivo() == '1' ? '' : 'bg-warning', $objVo->getIdpagina());
                printf('<td><a class="btn btn-secondary text-white btn-move"><i class="fas fa-arrows-alt-v"></i></a></td>');
                printf('<td class="text-center">');
                printf('<a class="btn btn-dark" href="?pg=alterar&lc=pagina&id=%s"><i class="fas fa-pencil-alt"></i></a> ', $objVo->getIdpagina());
                if ($objVo->getAtivo() == '1') {
                  printf('<a class="btn btn-warning removePagina" href="#" id="%s"><i class="fas fa-trash"></i></a> ', $objVo->getIdpagina());
                } else {
                  printf('<a class="btn btn-success removePagina" href="#" id="%s"><i class="fas fa-check"></i></a> ', $objVo->getIdpagina());
                }
                printf('<a class="btn btn-primary" href="?pg=arquivo&lc=pagina&id=%s"><i class="fas fa-paperclip"></i></i></a>', $objVo->getIdpagina());
                printf('</td>');
                printf('<td>%s</td>', strip_tags($objVo->getNome()));
                printf('<td><a href="index.php?pg=index&lc=pagina&id=%s">%s</a></td>', $objVo->getIdpagina(), @$subs != null ? count($subs)." paginas" : "");
                printf('<td class="text-center">%s</td>', $objVo->getFixo() == 1 ? "<i class='fas fa-check'></i>" : "");
                printf('</tr>');
              }

            }
          }
        ?>
      </tbody>
    </table>
  </div>
</div>
