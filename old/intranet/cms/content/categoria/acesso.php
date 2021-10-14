<?php 
include("../class/class.Conexao.php");
include("../class/class.CursoVO.php");
include("../class/DAO/class.CursoDAO.php");
include("../class/class.AlunoVO.php");
include("../class/DAO/class.AlunoDAO.php");
include("../class/class.Aluno_CursoVO.php");
include("../class/DAO/class.Aluno_CursoDAO.php");
include("../class/class.OutroVO.php");
include("../class/DAO/class.OutroDAO.php");
include("../class/class.EmpresaVO.php");
include("../class/DAO/class.EmpresaDAO.php");
include("../class/class.PlanoVO.php");
include("../class/DAO/class.PlanoDAO.php");
include("../class/class.Acesso_PlanoVO.php");
include("../class/DAO/class.Acesso_PlanoDAO.php");
include("../class/class.Aluno_PlanoVO.php");
include("../class/DAO/class.Aluno_PlanoDAO.php");
include("../class/class.Funcao.php");

$alunoDAO = new AlunoDAO();
$cursoDAO = new CursoDAO();
$aluno_cursoDAO = new Aluno_CursoDAO();
$outroDAO = new OutroDAO();
$empresaDAO = new EmpresaDAO();
$planoDAO = new PlanoDAO();
$acesso_planoDAO = new Acesso_PlanoDAO();
$aluno_planoDAO = new Aluno_PlanoDAO();
$idaluno = $_GET['idaluno'];
$aluno = $alunoDAO->getById($idaluno);

?>
<script>

$('document').ready(function() {

  var f = $('#formAddAcesso');
	var b = $('#btn-liberar-acesso');
	
	b.click(function() {
		f.ajaxForm({
			beforeSend : function() {
				b.attr('disabled', 'disabled');
			},
			success : function(e) {
				b.removeAttr('disabled');
        if (e == '') {
          location.reload();
        } else {
          alert(e);
        }
			},
			error : function(e) {
				b.removeAttr('disabled');
        location.reload();
			}
		});
	});


  var f2 = $('#formAddPlano');
	var b2 = $('#btn_liberar_plano');
	
	b2.click(function() {
		f2.ajaxForm({
			beforeSend : function() {
				b2.attr('disabled', 'disabled');
			},
			success : function(e) {
				b2.removeAttr('disabled');
        if (e == '') {
          location.reload();
        } else {
          alert(e);
        }
			},
			error : function(e) {
				b2.removeAttr('disabled');
        location.reload();
			}
		});
	});

  $('input[name="inputDti"], input[name="inputDtf"]').mask('00/00/0000');
  $('a.removerAcesso').click(function() {

    var del_id = $(this).attr('id');
    var parent = $(this).parent();
    var conf = confirm('Você tem certeza que deseja remover o acesso deste aluno?');
    if (conf) {
      $.post('aluno/ajax/ajax_remover_acesso.php', {
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
    }
  });

  $('a.removerPlano').click(function() {

    var del_id = $(this).attr('id');
    var conf = confirm('Você tem certeza que deseja remover o plano deste aluno?');
    if (conf) {
      $.post('aluno/ajax/ajax_remover_plano.php', {
        id: del_id
      }, function(data) {

        if (data == '') {
          location.reload();
        } else {
          alert(data);
        }
      });
    }
  });

  $('#inputFull_access').change(function () {
    var curso = $('#curso_liberar');
    var outro = $('#outro_liberar');
    var input = $('#inputFull_outros').parent();
    if ($(this).prop('checked')) {
      curso.addClass('d-none');
      outro.addClass('d-none');
      input.addClass('d-none');
    } else {
      curso.removeClass('d-none');
      outro.removeClass('d-none');
      input.removeClass('d-none');
    }
  });

  $('#inputFull_outros').change(function () {
    var curso = $('#curso_liberar');
    var outro = $('#outro_liberar');
    var input = $('#inputFull_access').parent();
    if ($(this).prop('checked')) {
      curso.addClass('d-none');
      outro.addClass('d-none');
      input.addClass('d-none');
    } else {
      curso.removeClass('d-none');
      outro.removeClass('d-none');
      input.removeClass('d-none');
    }
  });

  $('select[name="inputCurso"]').change(function () {
    var full_curso = $('#inputFull_access').parent();
    var full_outro = $('#inputFull_outros').parent();
    var outro = $('#outro_liberar');
    if ($(this).val()) {
      full_curso.addClass('d-none');
      full_outro.addClass('d-none');
      outro.addClass('d-none');
    } else {
      full_curso.removeClass('d-none');
      full_outro.removeClass('d-none');
      outro.removeClass('d-none');
    }
  });

  $('select[name="inputOutro"]').change(function () {
    var full_curso = $('#inputFull_access').parent();
    var full_outro = $('#inputFull_outros').parent();
    var curso = $('#curso_liberar');
    if ($(this).val()) {
      full_curso.addClass('d-none');
      full_outro.addClass('d-none');
      curso.addClass('d-none');
    } else {
      full_curso.removeClass('d-none');
      full_outro.removeClass('d-none');
      curso.removeClass('d-none');
    }
  });
});
</script>

<style>

  .list-acesso-aluno i {
    width: 30px;
    height: 30px;
    display: inline-flex;
    justify-content: center;
    align-items: center;
    background-color: #79de90;
    color: #fff;
  }

  .list-acesso-aluno li {
    margin-bottom: 10px;
  }

</style>

<div class="card">
  <div class="card-header">Gerenciar acessos</div>
  <div class="card-body">
    <h5 class="m-0"><b><?=$aluno->getNome()?></b></h5>
    <?php 
    if ($aluno->getEmpresa() != 0) { 
      $empresa = $empresaDAO->getById($aluno->getEmpresa()); 
      $acesso_empresa = $aluno_cursoDAO->getByIdempresa($empresa->getIdempresa());
      printf('<span class="text-black-50 d-block">Empresa: <b>%s</b></span>', $empresa->getNome());
    }

    @$aluno_plano = $aluno_planoDAO->getByIdaluno($aluno->getIdaluno());
    if ($aluno_plano) {
      $plano = $planoDAO->getById($aluno_plano->getIdplano());
      $acesso_plano = $acesso_planoDAO->getByIdplano($plano->getIdplano());
      printf('<span class="text-black-50 d-block">Plano: <b>%s</b></span>', $plano->getNome());
    }
    
    if (isset($acesso_empresa) || isset($aluno_plano)) {
      printf('<div class="container-fluid mt-2">');
        // printf('<span>O aluno já tem acesso:</span>');
        printf('<div class="row">');
          if (isset($acesso_empresa)) {
            printf('<div class="col px-4 py-3 border mr-3">');
              printf('<span class="font-weight-bold">%s</span>', $empresa->getNome());
              printf('<ul class="list-acesso-aluno list-inline list-unstyled">');
                foreach ($acesso_empresa as $objVoA) {
                  $str = '';
                  if ($objVoA->getFull_access() == 1) {
                    $str = 'Todos as aulas gravadas';
                  } else if ($objVoA->getFull_outros() == 1) {
                    $str = 'Todos os materiais extras';
                  } else if ($objVoA->getIdcurso() == 1) {
                    $curso = $cursoDAO->getById($objVoA->getIdcurso());
                    $str = $curso->getNome();
                  } else if ($objVoA->getIdoutro() == 1) {
                    $outro = $outroDAO->getById($objVoA->getIdoutro());
                    $str = $outro->getNome();
                  }
                  $dti = DateTime::createFromFormat('Y-m-d', $objVoA->getDti());
                  $dtf = DateTime::createFromFormat('Y-m-d', $objVoA->getDtf());
                  $str_dt = "de " . $dti->format('d/m/Y') . " até " . $dtf->format('d/m/Y');
                  printf('<li class="list-inline-item d-inline-flex align-items-center mr-5"><i class="fas fa-check mr-2"></i> <div class="d-inline-block">%s <br><small>%s</small></div></li>', $str, $str_dt);
                }
              printf('</ul>');
            printf('</div>');
          }
          if (isset($aluno_plano)) {
            printf('<div class="col px-4 py-3 border ml-3">');
              printf('<span class="font-weight-bold">%s</span>', $plano->getNome());
              printf('<ul class="list-acesso-aluno list-inline list-unstyled">');
                foreach ($acesso_plano as $objVoA) {
                  $str = '';
                  if ($objVoA->getFull_cursos() == 1) {
                    $str = 'Todos as aulas gravadas';
                  } else if ($objVoA->getFull_outros() == 1) {
                    $str = 'Todos os materiais extras';
                  } else if ($objVoA->getIdcurso() == 1) {
                    $curso = $cursoDAO->getById($objVoA->getIdcurso());
                    $str = $curso->getNome();
                  } else if ($objVoA->getIdoutro() == 1) {
                    $outro = $outroDAO->getById($objVoA->getIdoutro());
                    $str = $outro->getNome();
                  }
                  $dti = DateTime::createFromFormat('Y-m-d', $aluno_plano->getDti());
                  $dtf = DateTime::createFromFormat('Y-m-d', $aluno_plano->getDtf());
                  $str_dt = "de " . $dti->format('d/m/Y') . " até " . $dtf->format('d/m/Y');
                  printf('<li class="list-inline-item d-inline-flex align-items-center mr-5"><i class="fas fa-check mr-2"></i> <div class="d-inline-block">%s <br><small>%s</small></div></li>', $str, $str_dt);
                }
              printf('</ul>');
            printf('</div>');
          }
        printf('</div>');
      printf('</div>');
      printf('<hr>');
    }
    
    ?>

    <ul class="nav nav-pills nav-justified mb-0 mt-2 bg-white" id="pills-tab" role="tablist">
      <li class="nav-item">
        <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Planos</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Acessos individuais</a>
      </li>
    </ul>
    <div class="tab-content bg-white mb-3" id="pills-tabContent">
      <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
        <?php if ($aluno_plano) { ?>
          <div class="alert alert-warning mt-3">O aluno já possui um plano vinculado e portanto não pode ter outro.</div>
        <?php } else { ?>
          <form id="formAddPlano" class=" p-3" action="aluno/ajax/ajax_add_plano.php" name="formCadastro" method="post" role="form" enctype="multipart/form-data">
            <input type="hidden" name="idaluno" value="<?=$idaluno?>">
            <div class="form-group">
              <label for="inputPlano">Plano do aluno</label>
              <select name="inputPlano" data-live-search="true" title="Nenhum plano selecionado">
                <?php
                  $listPlanos = $planoDAO->getAll(true);
                  if (sizeof($listPlanos) > 0) {
                    foreach ($listPlanos as $objVoP) {
                      printf('<option value="%s">%s</option>', $objVoP->getIdplano(), $objVoP->getNome());
                    }
                  }
                ?>
              </select>
            </div>
            <div class="form-group">
              <div class="row">
                <div class="col">
                  <label for="inputDti">Data de início</label>
                  <input type="text" class="form-control datepicker" autocomplete="off" name="inputDti" required>
                </div>
                <div class="col">
                  <label for="inputDtf">Data de finalização</label>
                  <input type="text" class="form-control datepicker" autocomplete="off" name="inputDtf" required>
                </div>
              </div>
            </div>
            <button type="submit" class="btn btn-success" id="btn_liberar_plano">Atribuir plano</button>
          </form>
        <?php } ?>
      </div>
      <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
        <form id="formAddAcesso" class="p-3" action="aluno/ajax/ajax_add_acesso.php" name="formCadastro" method="post" role="form" enctype="multipart/form-data">
          <input type="hidden" name="idaluno" value="<?=$idaluno?>">
          <div class="form-group">
            <div class="checkbox mb-2">
              <input type="checkbox" name="inputFull_access" id="inputFull_access" value="1" class="switch_1 input-change">
              <label for="inputFull_access">Acesso a todas as aulas gravadas</label>
            </div>
            <div class="checkbox">
              <input type="checkbox" name="inputFull_outros" id="inputFull_outros" value="1" class="switch_1 input-change">
              <label for="inputFull_outros">Acesso a todos os materiais extras</label>
            </div>
          </div>
          <div class="form-group input-change" id="curso_liberar">
            <label for="inputCurso">Curso</label>
            <select name="inputCurso" data-live-search="true" class="input-change">
              <option value="">Nenhum curso</option>
              <?php
                $listCursos = $cursoDAO->getAll(true);
                if (sizeof($listCursos) > 0) {
                  foreach ($listCursos as $objVoC) {
                    printf('<option value="%s">%s</option>', $objVoC->getIdcurso(), $objVoC->getNome());
                  }
                }
              ?>
            </select>
          </div>

          <div class="form-group input-change" id="outro_liberar">
            <label for="inputOutro">Material extra <a href="javascript:;" data-toggle="popover" data-placement="right" data-trigger="hover" data-content="Este campo lista todos os materiais extras que não possuem um curso vinculado e que não possuem a opção 'Material gratuito' ativa." class="ml-2 text-black-50"><i class="fas fa-question-circle"></i></a></label>
              <?php
                $listOutros = $outroDAO->getPagos();
                if (sizeof($listOutros) > 0) {
                  printf('<select name="inputOutro" data-live-search="true" class="input-change">');
                    printf('<option value="">Nenhum material extra</option>');
                    foreach ($listOutros as $objVoC) {
                      printf('<option value="%s">%s</option>', $objVoC->getIdoutro(), $objVoC->getNome());
                    }
                  printf('</select>');
                } else {
                  printf('<div class="alert alert-warning">Nenhum material extra disponível para acesso.</div>');
                }
              ?>
          </div>

          <div class="form-group">
            <div class="row">
              <div class="col">
                <label for="inputDti">Data de início</label>
                <input type="text" class="form-control datepicker" autocomplete="off" name="inputDti" required>
              </div>
              <div class="col">
                <label for="inputDtf">Data de finalização</label>
                <input type="text" class="form-control datepicker" autocomplete="off" name="inputDtf" required>
              </div>
            </div>
          </div>

          <button type="submit" class="btn btn-success" id="btn-liberar-acesso">Liberar acesso</button>
        </form>
      </div>
    </div>

    <label>Plano</label>
    <table class="table table-striped table-bordered table-hover mb-5" id="dataTables-example">
      <thead>
        <tr>
          <th class="text-center">Opções</th>
          <th class="text-center">Aluno</th>
          <th class="text-center">Plano</th>
          <th class="text-center">Comentário</th>
          <th class="text-center">Início</th>
          <th class="text-center">Fim</th>
        </tr>
      </thead>
      <tbody>
        <?php 
          if($aluno_plano) {
            printf('<tr class="gradeU">');
            printf('<td class="text-center">');
            printf('<a href="javascript:;" class="btn btn-danger removerPlano" id="%s" data-toggle="tooltip" alt="Remover acesso" title="Remover acesso"><i class="fas fa-trash"></i></a> ', $aluno_plano->getIdaluno_plano());
            printf('</td>');
            printf('<td class="text-center">%s</td>', $aluno->getNome());
            printf('<td class="text-center">%s</td>', $plano->getNome());
            printf('<td class="text-center">%s</td>', $aluno_plano->getComentario());
            $dti = DateTime::createFromFormat('Y-m-d', $aluno_plano->getDti());
            $dtf = DateTime::createFromFormat('Y-m-d', $aluno_plano->getDtf());
            printf('<td class="text-center">%s</td>', $dti->format('d/m/Y'));
            printf('<td class="text-center">%s</td>', $dtf->format('d/m/Y'));
            printf('</tr>');
          }
        ?>
      </tbody>
    </table>

    
    <label>Acessos</label>
    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
      <thead>
        <tr>
          <th class="text-center">Opções</th>
          <th class="text-center">Aluno</th>
          <th class="text-center">Curso</th>
          <th class="text-center">Material extra</th>
          <th class="text-center">Início</th>
          <th class="text-center">Fim</th>
        </tr>
      </thead>
      <tbody>
        <?php 
          $listAluno_Curso = $aluno_cursoDAO->getByIdaluno($idaluno);
          if(sizeof($listAluno_Curso) > 0) {
            foreach($listAluno_Curso as $objVo) {
              if ($objVo->getFull_access() == 0) {
                @$curso = $cursoDAO->getById($objVo->getIdcurso());
              }
              if ($objVo->getFull_outros() == 0) {
                @$outro = $outroDAO->getById($objVo->getIdoutro());
              }
              printf('<tr class="gradeU">');
              printf('<td class="text-center">');
              printf('<a href="javascript:;" class="btn btn-danger removerAcesso" id="%s" data-toggle="tooltip" alt="Remover acesso" title="Remover acesso"><i class="fas fa-trash"></i></a> ', $objVo->getIdaluno_curso());
              printf('</td>');
              printf('<td class="text-center">%s</td>', $aluno->getNome());
              $str_curso = ($objVo->getFull_access() == 1 ? 'Todos os cursos' : ($objVo->getIdcurso() != 0 ? $curso->getNome() : '<i class="fas fa-times"></i>'));
              printf('<td class="text-center">%s</td>', $str_curso);
              $str_outros = ($objVo->getFull_outros() == 1 ? 'Todos os materiais' : ($objVo->getIdoutro() != 0 ? $outro->getNome() : '<i class="fas fa-times"></i>'));
              printf('<td class="text-center">%s</td>', $str_outros);
              $dti = DateTime::createFromFormat('Y-m-d', $objVo->getDti());
              $dtf = DateTime::createFromFormat('Y-m-d', $objVo->getDtf());
              printf('<td class="text-center">%s</td>', $dti->format('d/m/Y'));
              printf('<td class="text-center">%s</td>', $dtf->format('d/m/Y'));
              printf('</tr>');
            }
          }
        ?>
      </tbody>
    </table>
    
  </div>
</div>