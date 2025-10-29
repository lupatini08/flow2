<?php
// arquivo de navbar simplificada somente com o nome do site para a tela de registro

$links = [
  'index' => 'Início',
  'tasks' => 'Tarefas',
  'notes' => 'Notas',
];
?>

<nav class="navbar navbar-expand-lg bg-light navbar-light fixed-top border-bottom shadow-sm">
  <div class="container-fluid d-flex justify-content-between align-items-center mx-5">

    <a class="navbar-brand fw-bold mx-3" href="index.php">Flow</a>

    <div class="d-flex align-items-center mx-3 text-center">
      <?php if($user_logged): ?>
      <?php else: ?>
        <a href="register.php" class="btn btn-outline-primary btn-sm mx-2">Criar Conta</a>
        <a href="login.php" class="btn btn-primary btn-sm">Login</a>
      <?php endif; ?>
    </div>
  </div>
</nav>