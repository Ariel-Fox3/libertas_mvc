<?php
  
?>


<div class="card">
  <div class="card-header">Facilitando a venda - RESUMO</div>
  <div class="card-body">
    <h5 class="text-center">Estatísticas da plataforma</h5>
    <div class="row">
      <div class="col-8">
        <div class="row">
          <div class="col">
            <div class="w-100 py-2 bg-dark text-white text-center text-uppercase font-weight-bold m-0">Alunos</div>
            <table class="table table-striped table-hover table-sm">
              <tbody>
                <tr>
                  <td>Alunos cadastrados</td>
                  <td>PHP_INFO</td>
                </tr>
                <tr>
                  <td>Alunos ativos</td>
                  <td>PHP_INFO</td>
                </tr>
              </tbody>
            </table>
    
            <div class="w-100 py-2 bg-dark text-white text-center text-uppercase font-weight-bold m-0">Empresas</div>
            <table class="table table-striped table-hover table-sm">
              <tbody>
                <tr>
                  <td>Empresas cadastradas</td>
                  <td>PHP_INFO</td>
                </tr>
                <tr>
                  <td>Alunos vinculados</td>
                  <td>PHP_INFO</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="col">
            <div class="w-100 py-2 bg-dark text-white text-center text-uppercase font-weight-bold m-0">Materiais</div>
            <table class="table table-striped table-hover table-sm">
              <tbody>
                <tr>
                  <td>Cursos</td>
                  <td>PHP_INFO</td>
                </tr>
                <tr>
                  <td>Aulas</td>
                  <td>PHP_INFO</td>
                </tr>
                <tr>
                  <td>Materiais complementares</td>
                  <td>PHP_INFO</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="col">
        <canvas id="myChart" style="width: 100%; height: 300px;"></canvas>
      </div>
    </div>
  </div>
</div>

<script>

$(document).ready(function () {

  var ctx = document.getElementById('myChart');
  var myChart = new Chart(ctx, {
      type: 'bar',
      data: {
          labels: ['Intenções de compra', 'Compras finalizadas'],
          datasets: [{
              label: 'Número de compras',
              data: [parseInt('PHP_INFO'), parseInt('PHP_INFO')],
              backgroundColor: [
                  'rgba(255, 99, 132, 0.2)',
                  'rgba(54, 162, 235, 0.2)'
              ],
              borderColor: [
                  'rgba(255, 99, 132, 1)',
                  'rgba(54, 162, 235, 1)'
              ],
              borderWidth: 1
          }]
      },
      options: {
          scales: {
              yAxes: [{
                  ticks: {
                      beginAtZero: true
                  }
              }]
          }
      }
  });
});
</script>