<?php
// arquivo para o perfil do usuário

$page = 'profile';
$title = 'Meu Perfil';

include "../config.php";
include "templates/head.php";

// verifica se o usuario ta logado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// buscar dados do usuário no bd
$stmt = $pdo->prepare("SELECT * FROM usuario WHERE codigo = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

// conta tarefas concluidas e anotações atuais do usuário
$stmt_tasks = $pdo->prepare("SELECT COUNT(*) as total FROM tarefas WHERE cod_user = ? AND concluida = 1");
$stmt_tasks->execute([$_SESSION['user_id']]);
$total_tasks = $stmt_tasks->fetch()['total'];

$stmt_notes = $pdo->prepare("SELECT COUNT(*) as total FROM notas WHERE cod_user = ?");
$stmt_notes->execute([$_SESSION['user_id']]);
$total_notes = $stmt_notes->fetch()['total'];
?>
  
<main>
    <section class="main">
    <div class="container" style="margin-top: 85px;">
      <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
          <h2 class="text-center mb-5">Meu Perfil</h2>
          
          <div class="card shadow-sm">
            <div class="card-body p-4">
              <div class="row">
                <div class="col-md-4 text-center mb-4 mb-md-0">
                  <div class="bg-light rounded-circle p-4 d-inline-block">
                    <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="currentColor" class="bi bi-person-circle text-secondary" viewBox="0 0 16 16">
                      <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                      <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
                    </svg>
                  </div>
                  <h4 class="mt-3"><?= htmlspecialchars($user['primeiro_nome'] . ' ' . $user['ultimo_nome']) ?></h4>
                  <p class="text-muted">Usuário</p>
                </div>
                
                <div class="col-md-8">
                  <h5 class="mb-4">Informações Pessoais</h5>
                  
                  <div class="row mb-3">
                    <div class="col-sm-4">
                      <strong>Nome Completo:</strong>
                    </div>
                    <div class="col-sm-8">
                      <?= htmlspecialchars($user['primeiro_nome'] . ' ' . ($user['nome_meio'] ? $user['nome_meio'] . ' ' : '') . $user['ultimo_nome']) ?>
                    </div>
                  </div>
                  
                  <div class="row mb-3">
                    <div class="col-sm-4">
                      <strong>Email:</strong>
                    </div>
                    <div class="col-sm-8">
                      <?= htmlspecialchars($user['email']) ?>
                    </div>
                  </div>
                  
                  <div class="row mb-3">
                    <div class="col-sm-4">
                      <strong>Data de Nascimento:</strong>
                    </div>
                    <div class="col-sm-8">
                      <?= $user['data_nascimento'] ? date('d/m/Y', strtotime($user['data_nascimento'])) : 'Não informada' ?>
                    </div>
                  </div>
                  
                  <div class="row mb-3">
                    <div class="col-sm-4">
                      <strong>ID do Usuário:</strong>
                    </div>
                    <div class="col-sm-8">
                      #<?= $user['codigo'] ?>
                    </div>
                  </div>
                  
                  <hr class="my-4">
                  
                  <h5 class="mb-4">Estatísticas</h5>
                  
                  <div class="row text-center">
                    <div class="col-6">
                      <div class="border rounded p-3">
                        <h3 class="text-primary"><?= $total_tasks ?></h3>
                        <p class="mb-0 text-muted">Tarefas concluídas</p>
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="border rounded p-3">
                        <h3 class="text-success"><?= $total_notes ?></h3>
                        <p class="mb-0 text-muted">Anotações</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <div class="text-center mt-4">
            <a href="tasks.php" class="btn btn-primary me-2">Minhas Tarefas</a>
            <a href="notes.php" class="btn btn-outline-primary">Minhas Anotações</a>
          </div>
        </div>
      </div>
    </div>
    </section>
  </main>

<?php
include "templates/footer.php"
?>