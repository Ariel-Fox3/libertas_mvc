<style>

#dashboard h2 {
  border-bottom: 2px solid var(--azul-1);
  color: var(--azul-1);
  display: inline-flex;
  width: 250px;
}

  #dashboard .botoes a {
    width: 100%;
    height: 150px;
    background: #eee;
    display: flex;
    justify-content: center;
    align-items: center;
    color: var(--azul-1);
    transition: .4s ease;
    text-transform: uppercase;
  }

  #dashboard .botoes a:hover {
    text-decoration: none;
    background-color: var(--azul-1);
    color: #eee;
  }

</style>


<div class="container" id="dashboard">
  <h2 class="text-uppercase mb-3">Dashboard</h2>
  <div class="row botoes">
    <!-- <div class="col-6 col-md-4">
      <a href="?pg=index&lc=categoria_produto">
        <div class="text-center">
          <i class="fas fa-cogs fa-4x p3-2"></i><br>
          Gerenciar categorias
        </div>
      </a>
    </div>
    <div class="col-6 col-md-4">
      <a href="?pg=index&lc=marca">
        <div class="text-center">
          <i class="fas fa-cogs fa-4x pb-3"></i><br>
          Gerenciar marcas
        </div>
      </a>
    </div>
    <div class="col-6 col-md-4 mt-4 mt-md-0">
      <a href="?pg=index&lc=produto">
        <div class="text-center">
          <i class="fas fa-cogs fa-4x pb-3"></i><br>
          Gerenciar produtos
        </div>
      </a>
    </div> -->
    <div class="col-6 col-md-12 mt-4">
      <a href="?pg=index&lc=solicitacao">
        <div class="text-center">
          <i class="far fa-address-book fa-4x pb-3"></i><br>
          Solicitações de contato
        </div>
      </a>
    </div>
  </div>
  <hr>
</div>