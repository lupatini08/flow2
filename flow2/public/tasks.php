<?php
ob_start();
$page = 'tasks';
$title = 'Tarefas';
include "../config.php";
include "templates/head.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Buscar tarefas do usuário (não concluídas)
$stmt = $pdo->prepare("SELECT * FROM tarefas WHERE cod_user = ? AND concluida = 0 ORDER BY data_entrega ASC");
$stmt->execute([$_SESSION['user_id']]);
$tasks = $stmt->fetchAll();

// Adicionar nova tarefa
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nova_tarefa'])) {
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $urgencia = $_POST['urgencia'];
    $data_entrega = $_POST['data_entrega'];
    $hora_entrega = $_POST['hora_entrega'];
    
    $stmt = $pdo->prepare("INSERT INTO tarefas (cod_user, titulo, descricao, urgencia, data_entrega, hora_entrega) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $titulo, $descricao, $urgencia, $data_entrega, $hora_entrega]);
    ob_end_clean();
    header("Location: tasks.php");
    exit;
}
?>

<main>
    <section class="main">
    <div class="container mb-5" style="margin-top: 85px;">
      <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
          <h2 class="text-center mb-4">Suas tarefas</h2>
          
          <!-- Formulário para nova tarefa -->
          <div class="card shadow-sm mb-4">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
              <h5 class="card-title mb-0">Nova Tarefa</h5>
              <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#formNovaTarefa">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-down" viewBox="0 0 16 16">
                  <path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708"/>
                </svg>
              </button>
            </div>
            <div class="collapse show" id="formNovaTarefa">
              <div class="card-body">
                <form method="POST">
                  <div class="row g-3">
                    <div class="col-md-6">
                      <label for="titulo" class="form-label">Título</label>
                      <input type="text" class="form-control" name="titulo" placeholder="Título da tarefa" required>
                    </div>
                    <div class="col-md-3">
                      <label for="urgencia" class="form-label">Urgência</label>
                      <select class="form-select" name="urgencia" required>
                        <option value="">Selecione...</option>
                        <option value="1">Baixa</option>
                        <option value="2">Média</option>
                        <option value="3">Alta</option>
                      </select>
                    </div>
                    <div class="col-md-3">
                      <label for="data_entrega" class="form-label">Data de Entrega</label>
                      <input type="date" class="form-control" name="data_entrega">
                    </div>
                    <div class="col-md-6">
                      <label for="hora_entrega" class="form-label">Hora de Entrega</label>
                      <input type="time" class="form-control" name="hora_entrega">
                    </div>
                    <div class="col-12">
                      <label for="descricao" class="form-label">Descrição</label>
                      <textarea class="form-control" name="descricao" rows="3" placeholder="Descrição da tarefa..." required></textarea>
                    </div>
                    <div class="col-12">
                      <button type="submit" name="nova_tarefa" class="btn btn-primary">Adicionar Tarefa</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>

          <!-- Lista de Tarefas com Accordion -->
          <div class="card shadow-sm">
            <div class="card-header bg-light">
              <h5 class="card-title mb-0">Lista de Tarefas</h5>
            </div>
            <div class="card-body p-0">
              <div class="accordion" id="accordionTarefas">
                <?php if (empty($tasks)): ?>
                  <div class="text-center py-4">
                    <p class="text-muted mb-0">Nenhuma tarefa encontrada. Crie sua primeira tarefa!</p>
                  </div>
                <?php else: ?>
                  <div style="max-height: 600px; overflow-y: auto;">
                    <?php foreach ($tasks as $index => $task): ?>
                    <div class="accordion-item">
                      <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $task['codigo'] ?>" aria-expanded="false" aria-controls="collapse<?= $task['codigo'] ?>">
                          <div class="d-flex justify-content-between align-items-center w-100 me-3">
                            <div class="d-flex align-items-center">
                              <input class="form-check-input me-3 concluir-tarefa" type="checkbox" value="<?= $task['codigo'] ?>" id="task<?= $task['codigo'] ?>">
                              <span class="fw-bold"><?= htmlspecialchars($task['titulo']) ?></span>
                            </div>
                            <span class="badge text-bg-<?= $task['urgencia'] == 3 ? 'danger' : ($task['urgencia'] == 2 ? 'warning' : 'primary') ?> rounded-pill">
                              <?= $task['urgencia'] == 3 ? 'Alta' : ($task['urgencia'] == 2 ? 'Média' : 'Baixa') ?>
                            </span>
                          </div>
                        </button>
                      </h2>
                      <div id="collapse<?= $task['codigo'] ?>" class="accordion-collapse collapse" data-bs-parent="#accordionTarefas">
                        <div class="accordion-body">
                          <div class="row">
                            <div class="col-md-8">
                              <h6 class="text-muted mb-3">Descrição</h6>
                              <p class="bg-light p-3 rounded"><?= nl2br(htmlspecialchars($task['descricao'])) ?></p>
                            </div>
                            <div class="col-md-4">
                              <h6 class="text-muted mb-3">Detalhes</h6>
                              <div class="mb-3">
                                <small class="text-muted">Data de entrega:</small>
                                <div class="fw-medium"><?= $task['data_entrega'] ? date('d/m/Y', strtotime($task['data_entrega'])) : 'Não definida' ?></div>
                              </div>
                              <div class="mb-3">
                                <small class="text-muted">Hora de entrega:</small>
                                <div class="fw-medium"><?= $task['hora_entrega'] ? $task['hora_entrega'] : 'Não definida' ?></div>
                              </div>
                              <div class="mb-3">
                                <small class="text-muted">Urgência:</small>
                                <div>
                                  <span class="badge text-bg-<?= $task['urgencia'] == 3 ? 'danger' : ($task['urgencia'] == 2 ? 'warning' : 'primary') ?>">
                                    <?= $task['urgencia'] == 3 ? 'Alta' : ($task['urgencia'] == 2 ? 'Média' : 'Baixa') ?>
                                  </span>
                                </div>
                              </div>
                            </div>
                          </div>
                          
                          <div class="d-flex justify-content-end align-items-center mt-4 pt-3 border-top">
                            <div class="d-flex gap-2">
                              <a href="editar_tarefa.php?id=<?= $task['codigo'] ?>" class="btn btn-primary">
                                Editar
                              </a>
                              <button class="btn btn-outline-danger excluir-tarefa" data-tarefa-id="<?= $task['codigo'] ?>">
                                Excluir
                              </button>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <?php endforeach; ?>
                  </div>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    </section>
  </main>

<script>
// Função para concluir tarefa
document.querySelectorAll('.concluir-tarefa').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        const tarefaId = this.value;
        const concluida = this.checked;
        
        fetch('concluir_tarefa.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `tarefa_id=${tarefaId}&concluida=${concluida ? 1 : 0}`
        })
        .then(response => response.text())
        .then(data => {
            if (data === 'success') {
                // Recarrega a página para atualizar a lista
                location.reload();
            }
        });
    });
});

// Função para excluir tarefa
document.querySelectorAll('.excluir-tarefa').forEach(btn => {
    btn.addEventListener('click', function() {
        const tarefaId = this.getAttribute('data-tarefa-id');
        
        if (confirm('Tem certeza que deseja excluir esta tarefa?')) {
            fetch('excluir_tarefa.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `tarefa_id=${tarefaId}`
            })
            .then(response => response.text())
            .then(data => {
                if (data === 'success') {
                    // Recarrega a página para atualizar a lista
                    location.reload();
                }
            });
        }
    });
});
</script>

<?php include "templates/footer.php" ?>