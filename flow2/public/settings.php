<?php
$page = 'settings';
$title = 'Configurações';
include "../config.php";
include "templates/head.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>

<main>
    <section class="main">
    <div class="container" style="margin-top: 85px;">
      <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
          <h2 class="text-center mb-5">Configurações</h2>
          
          <div class="card shadow-sm">
            <div class="card-body p-4">
              <h5 class="mb-4">Configurações da Conta</h5>
              
              <div class="alert alert-info">
                <strong>Em desenvolvimento!</strong> Esta página está em construção e será disponibilizada em breve.
              </div>
              
              <div class="list-group">
                <div class="list-group-item d-flex justify-content-between align-items-center">
                  <div>
                    <h6 class="mb-1">Notificações por Email</h6>
                    <p class="mb-1 text-muted">Receba lembretes de tarefas por email</p>
                  </div>
                  <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" disabled>
                  </div>
                </div>
                
                <div class="list-group-item d-flex justify-content-between align-items-center">
                  <div>
                    <h6 class="mb-1">Tema Escuro</h6>
                    <p class="mb-1 text-muted">Altere para o modo escuro</p>
                  </div>
                  <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" disabled>
                  </div>
                </div>
                
                <div class="list-group-item d-flex justify-content-between align-items-center">
                  <div>
                    <h6 class="mb-1">Lembretes Diários</h6>
                    <p class="mb-1 text-muted">Receba resumo diário de tarefas</p>
                  </div>
                  <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" disabled>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <div class="text-center mt-4">
            <a href="profile.php" class="btn btn-outline-primary">Voltar ao Perfil</a>
          </div>
        </div>
      </div>
    </div>
    </section>
  </main>

<?php
include "templates/footer.php"
?>