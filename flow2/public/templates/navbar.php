<?php
// arquivo para o navbar do site

// links do menu
$links = [
  'index' => 'Início',
  'tasks' => 'Tarefas',
  'notes' => 'Notas',
];
?>

<nav class="navbar navbar-expand-lg bg-light navbar-light fixed-top border-bottom shadow-sm">
  <div class="container-fluid d-flex justify-content-between align-items-center mx-5">

    <a class="navbar-brand fw-bold mx-3" href="index.php">Flow</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarContent">
      <ul class="navbar-nav nav-underline mb-2 mb-lg-0 text-center">
          <?php foreach ($links as $slug => $label): ?>
              <li class="nav-item">
              <?php if ($page==$slug):?>
                  <a href="<?= $slug ?>.php" class="nav-link active"> 
              <?php else:?>
                  <a href="<?= $slug ?>.php" class="nav-link"> 
              <?php endif;?>
              <?= $label ?>
                </a>
              </li>
          <?php endforeach; ?>
      </ul>

        <div class="d-flex align-items-center mx-3 text-center">
          <?php if($user_logged): ?>
            <span class="me-2 text-secondary">Olá, <?= $user_name ?></span>
            <!-- Botão para abrir a sidebar lateral -->
            <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="offcanvas" data-bs-target="#userSidebar">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
              </svg>
            </button>
          <?php else: ?>
            <a href="register.php" class="btn btn-outline-primary btn-sm mx-2">Criar Conta</a>
            <a href="login.php" class="btn btn-primary btn-sm">Login</a>
          <?php endif; ?>
        </div>
    </div>
  </div>
</nav>

<!-- Sidebar Lateral do Usuário -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="userSidebar" aria-labelledby="userSidebarLabel">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="userSidebarLabel">Minha Conta</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <div class="d-flex flex-column align-items-center mb-4">
      <div class="bg-light rounded-circle p-3 mb-3">
        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-person-circle text-secondary" viewBox="0 0 16 16">
          <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
          <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
        </svg>
      </div>
      <h6><?= $user_name ?></h6>
      <small class="text-muted">Usuário</small>
    </div>

    <div class="list-group list-group-flush">
      <a href="profile.php" class="list-group-item list-group-item-action d-flex align-items-center">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person me-3" viewBox="0 0 16 16">
          <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664z"/>
        </svg>
        Meu Perfil
      </a>
      <a href="settings.php" class="list-group-item list-group-item-action d-flex align-items-center">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-gear me-3" viewBox="0 0 16 16">
          <path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492zM5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0"/>
          <path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52zM8 13.5a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11"/>
        </svg>
        Configurações
      </a>
      <div class="list-group-item list-group-item-action d-flex align-items-center text-danger" data-bs-dismiss="offcanvas" onclick="event.preventDefault(); document.getElementById('logoutForm').submit();" style="cursor: pointer;">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-right me-3" viewBox="0 0 16 16">
          <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z"/>
          <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z"/>
        </svg>
        Sair
      </div>
    </div>

    <!-- Formulário invisível para logout -->
    <form id="logoutForm" action="../logout.php" method="POST" style="display: none;">
    </form>
  </div>
</div>