<?php
$page = 'index';
$title = "Página Inicial";

include "../config.php";
include "templates/head.php";

// Verificar se usuário está logado
$user_logged = isset($_SESSION['user_id']);

if ($user_logged) {
    // Buscar anotações do usuário
    $stmt_notes = $pdo->prepare("SELECT * FROM notas WHERE cod_user = ? ORDER BY data_modificacao DESC LIMIT 4");
    $stmt_notes->execute([$_SESSION['user_id']]);
    $notes = $stmt_notes->fetchAll();

    // Buscar tarefas do usuário (apenas não concluídas)
    $stmt_tasks = $pdo->prepare("SELECT * FROM tarefas WHERE cod_user = ? AND concluida = 0 ORDER BY data_entrega ASC LIMIT 4");
    $stmt_tasks->execute([$_SESSION['user_id']]);
    $tasks = $stmt_tasks->fetchAll();
} else {
    // Dados de exemplo para usuários não logados
    $notes = [
        ['codigo' => 1, 'titulo' => 'Revisar o conteúdo de algoritmos antes da prova de quinta.'],
        ['codigo' => 2, 'titulo' => 'Criar um app de lista de compras com modo offline e sincronização automática.']
    ];
    
    $tasks = [
        ['codigo' => 1, 'titulo' => 'Adicionar modo escuro'],
        ['codigo' => 2, 'titulo' => 'Corrigir bug do campo de tarefas duplicadas']
    ];
}
?>
  
<main>
    <section class="main">
    <div class="container" style="margin-top: 85px;">
        <?php if (!$user_logged): ?>
        <div class="alert alert-info text-center mb-4">
            <strong>Você não está logado!</strong> Faça login ou crie uma conta para acessar todas as funcionalidades.
        </div>
        <?php endif; ?>
        
      <div class="row">
        <div class="col-12 col-md-6 col-lg-4">
            <div class="row">
              <div class="container p-2">
                <h2 class="text-center text-lg-start">Suas anotações</h2>
                
                <div class="container p-3 mt-5 mb-4 bg-body-tertiary" style="border-radius: 10px;">
                  <div class="list-group" id="anotacoes-container">
                    <?php if (empty($notes)): ?>
                      <div class="text-center py-3" id="placeholder-anotacoes">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-journal-text text-muted mb-2" viewBox="0 0 16 16">
                          <path d="M5 10.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5m0-2a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m0-2a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m0-2a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5"/>
                          <path d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2"/>
                          <path d="M1 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0V8h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1z"/>
                        </svg>
                        <p class="text-muted mb-0">Nenhuma anotação encontrada</p>
                        <small class="text-muted">Crie sua primeira anotação!</small>
                      </div>
                    <?php else: ?>
                      <div id="lista-anotacoes">
                        <?php foreach ($notes as $note): ?>
                        <div class="d-flex align-items-center mb-2 anotacao-item" data-anotacao-id="<?= $note['codigo'] ?>">
                            <button type="button" class="btn btn-light flex-grow-1 text-start <?= !$user_logged ? 'not-logged' : '' ?>" 
                                    <?php if (!$user_logged): ?>onclick="redirectToLogin()"<?php else: ?>data-bs-toggle="modal" data-bs-target="#modalNote<?= $note['codigo'] ?>"<?php endif; ?>>
                                <?= htmlspecialchars($note['titulo']) ?>
                            </button>
                        </div>
                        <?php endforeach; ?>
                      </div>
                    <?php endif; ?>
                    <div class="d-flex gap-2 mt-3">
                      <a href="notes.php" class="btn btn-secondary <?= !$user_logged ? 'not-logged' : '' ?>" <?php if (!$user_logged): ?>onclick="redirectToLogin(); return false;"<?php endif; ?>>Ver notas</a>
                      <button type="button" class="btn btn-primary <?= !$user_logged ? 'not-logged' : '' ?>" 
                              <?php if (!$user_logged): ?>onclick="redirectToLogin()"<?php else: ?>data-bs-toggle="modal" data-bs-target="#modalNovaAnotacao"<?php endif; ?>>
                        Nova Anotação
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="container p-2">
                <h2 class="text-center text-lg-start">Suas tarefas</h2>
                <div class="container p-3 mt-5 bg-body-tertiary" style="border-radius: 10px;">
                  <div id="tarefas-container">
                    <?php if (empty($tasks)): ?>
                      <div class="text-center py-3" id="placeholder-tarefas">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-check-circle text-muted mb-2" viewBox="0 0 16 16">
                          <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                          <path d="m10.97 4.97-.02.022-3.473 4.425-2.093-2.094a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05"/>
                        </svg>
                        <p class="text-muted mb-0">Nenhuma tarefa encontrada</p>
                        <small class="text-muted">Crie sua primeira tarefa!</small>
                      </div>
                    <?php else: ?>
                      <div id="lista-tarefas">
                        <?php foreach ($tasks as $task): ?>
                        <div class="d-flex align-items-center mb-2 tarefa-item" data-tarefa-id="<?= $task['codigo'] ?>">
                          <?php if ($user_logged): ?>
                            <input class="form-check-input concluir-tarefa" type="checkbox" value="<?= $task['codigo'] ?>" id="task<?= $task['codigo'] ?>">
                          <?php else: ?>
                            <input class="form-check-input" type="checkbox" disabled>
                          <?php endif; ?>
                          <span class="form-check-label ms-2 flex-grow-1 <?= !$user_logged ? 'not-logged' : '' ?>" 
                                style="cursor: pointer;" 
                                <?php if (!$user_logged): ?>onclick="redirectToLogin()"<?php else: ?>data-bs-toggle="modal" data-bs-target="#modalTask<?= $task['codigo'] ?>"<?php endif; ?>>
                            <?= htmlspecialchars($task['titulo']) ?>
                          </span>
                        </div>
                        <?php endforeach; ?>
                      </div>
                    <?php endif; ?>
                  </div>
                  <div class="d-flex gap-2 mt-3">
                    <a href="tasks.php" class="btn btn-secondary <?= !$user_logged ? 'not-logged' : '' ?>" <?php if (!$user_logged): ?>onclick="redirectToLogin(); return false;"<?php endif; ?>>Ver todas as tarefas</a>
                    <button type="button" class="btn btn-primary <?= !$user_logged ? 'not-logged' : '' ?>" 
                            <?php if (!$user_logged): ?>onclick="redirectToLogin()"<?php else: ?>data-bs-toggle="modal" data-bs-target="#modalNovaTarefa"<?php endif; ?>>
                      Nova Tarefa
                    </button>
                  </div>
                </div>
              </div>
            </div>
        </div>

        <div class="d-none d-lg-block col-lg-1"></div>
        <div class="col-12 col-md-6 col-lg-7 p-2">
          <h2 class="text-center text-lg-start">Calendário</h2>
          <div id="calendar"></div>
          <?php if (!$user_logged): ?>
          <div class="alert alert-warning text-center mt-3">
            <small>Faça login para visualizar seu calendário pessoal</small>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
    </section>
  </main>

<?php if ($user_logged): ?>
<!-- Modal para nova anotação rápida -->
<div class="modal fade" id="modalNovaAnotacao" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Nova Anotação Rápida</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formNovaAnotacaoRapida">
                    <div class="mb-3">
                        <label for="titulo_anotacao" class="form-label">Título</label>
                        <input type="text" class="form-control" id="titulo_anotacao" name="titulo" required>
                    </div>
                    <div class="mb-3">
                        <label for="anotacao_rapida" class="form-label">Anotação</label>
                        <textarea class="form-control" id="anotacao_rapida" name="anotacao" rows="5" placeholder="Digite sua anotação..." required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnSalvarAnotacaoRapida">Salvar Anotação</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para nova tarefa rápida -->
<div class="modal fade" id="modalNovaTarefa" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Nova Tarefa Rápida</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formNovaTarefaRapida">
                    <div class="mb-3">
                        <label for="titulo_rapido" class="form-label">Título</label>
                        <input type="text" class="form-control" id="titulo_rapido" name="titulo" required>
                    </div>
                    <div class="mb-3">
                        <label for="descricao_rapida" class="form-label">Descrição</label>
                        <textarea class="form-control" id="descricao_rapida" name="descricao" rows="3"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="urgencia_rapida" class="form-label">Urgência</label>
                            <select class="form-select" id="urgencia_rapida" name="urgencia" required>
                                <option value="1">Baixa</option>
                                <option value="2">Média</option>
                                <option value="3">Alta</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="data_entrega_rapida" class="form-label">Data de Entrega</label>
                            <input type="date" class="form-control" id="data_entrega_rapida" name="data_entrega">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="hora_entrega_rapida" class="form-label">Hora de Entrega</label>
                        <input type="time" class="form-control" id="hora_entrega_rapida" name="hora_entrega">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnSalvarTarefaRapida">Salvar Tarefa</button>
            </div>
        </div>
    </div>
</div>

<!-- Modais para anotações -->
<?php foreach ($notes as $note): ?>
<div class="modal fade" id="modalNote<?= $note['codigo'] ?>" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5"><?= htmlspecialchars($note['titulo']) ?></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <?= nl2br(htmlspecialchars($note['anotacao'])) ?>
            </div>
            <div class="modal-footer">
                <a href="editar_anotacao.php?id=<?= $note['codigo'] ?>" class="btn btn-primary">Editar</a>
                <button type="button" class="btn btn-outline-danger excluir-anotacao-modal" data-anotacao-id="<?= $note['codigo'] ?>">Excluir</button>
                <small class="text-muted">Modificado em: <?= $note['data_modificacao'] ?></small>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>

<!-- Modais para tarefas -->
<?php foreach ($tasks as $task): ?>
<div class="modal fade" id="modalTask<?= $task['codigo'] ?>" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5"><?= htmlspecialchars($task['titulo']) ?></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p><strong>Descrição:</strong></p>
                <p><?= nl2br(htmlspecialchars($task['descricao'])) ?></p>
                <p><strong>Data de entrega:</strong> <?= $task['data_entrega'] ? date('d/m/Y', strtotime($task['data_entrega'])) : 'Não definida' ?></p>
                <p><strong>Hora de entrega:</strong> <?= $task['hora_entrega'] ? $task['hora_entrega'] : 'Não definida' ?></p>
                <p><strong>Urgência:</strong> <?= $task['urgencia'] == 3 ? 'Alta' : ($task['urgencia'] == 2 ? 'Média' : 'Baixa') ?></p>
            </div>
            <div class="modal-footer">
                <a href="editar_tarefa.php?id=<?= $task['codigo'] ?>" class="btn btn-primary">Editar</a>
                <button type="button" class="btn btn-outline-danger excluir-tarefa-modal" data-tarefa-id="<?= $task['codigo'] ?>">Excluir</button>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>
<?php endif; ?>

<script>
// Função para redirecionar para login
function redirectToLogin() {
    window.location.href = 'login.php';
}

<?php if ($user_logged): ?>
// Funções para gerar os placeholders
function getPlaceholderAnotacoes() {
    return `
        <div class="text-center py-3" id="placeholder-anotacoes">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-journal-text text-muted mb-2" viewBox="0 0 16 16">
                <path d="M5 10.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5m0-2a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m0-2a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m0-2a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5"/>
                <path d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2"/>
                <path d="M1 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0V8h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1z"/>
            </svg>
            <p class="text-muted mb-0">Nenhuma anotação encontrada</p>
            <small class="text-muted">Crie sua primeira anotação!</small>
        </div>
    `;
}

function getPlaceholderTarefas() {
    return `
        <div class="text-center py-3" id="placeholder-tarefas">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-check-circle text-muted mb-2" viewBox="0 0 16 16">
                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                <path d="m10.97 4.97-.02.022-3.473 4.425-2.093-2.094a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05"/>
            </svg>
            <p class="text-muted mb-0">Nenhuma tarefa encontrada</p>
            <small class="text-muted">Crie sua primeira tarefa!</small>
        </div>
    `;
}

// Função para verificar e mostrar placeholder de anotações
function verificarAnotacoes() {
    const listaAnotacoes = document.getElementById('lista-anotacoes');
    const containerAnotacoes = document.getElementById('anotacoes-container');
    
    if (listaAnotacoes && listaAnotacoes.querySelectorAll('.anotacao-item').length === 0) {
        listaAnotacoes.remove();
        containerAnotacoes.insertAdjacentHTML('afterbegin', getPlaceholderAnotacoes());
    }
}

// Função para verificar e mostrar placeholder de tarefas
function verificarTarefas() {
    const listaTarefas = document.getElementById('lista-tarefas');
    const containerTarefas = document.getElementById('tarefas-container');
    
    if (listaTarefas && listaTarefas.querySelectorAll('.tarefa-item').length === 0) {
        listaTarefas.remove();
        containerTarefas.insertAdjacentHTML('afterbegin', getPlaceholderTarefas());
    }
}

// Função para salvar anotação rápida
document.getElementById('btnSalvarAnotacaoRapida').addEventListener('click', function() {
    const formData = new FormData(document.getElementById('formNovaAnotacaoRapida'));
    formData.append('nova_nota', 'true');
    
    fetch('notes.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (response.ok) {
            // Fecha o modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('modalNovaAnotacao'));
            modal.hide();
            
            // Limpa o formulário
            document.getElementById('formNovaAnotacaoRapida').reset();
            
            // Recarrega a página para mostrar a nova anotação
            location.reload();
        }
    })
    .catch(error => {
        alert('Erro ao salvar anotação: ' + error);
    });
});

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
                // Remove a tarefa da lista
                const tarefaItem = this.closest('.tarefa-item');
                tarefaItem.style.opacity = '0.5';
                setTimeout(() => {
                    tarefaItem.remove();
                    
                    // Verifica se precisa mostrar o placeholder
                    verificarTarefas();
                    
                    // Atualiza o calendário
                    if (typeof calendar !== 'undefined') {
                        calendar.refetchEvents();
                    }
                }, 500);
            }
        });
    });
});

// Função para excluir anotação
document.querySelectorAll('.excluir-anotacao-modal').forEach(btn => {
    btn.addEventListener('click', function() {
        const anotacaoId = this.getAttribute('data-anotacao-id');
        
        if (confirm('Tem certeza que deseja excluir esta anotação?')) {
            fetch('excluir_anotacao.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `anotacao_id=${anotacaoId}`
            })
            .then(response => response.text())
            .then(data => {
                if (data === 'success') {
                    // Remove a anotação da lista
                    const anotacaoItem = document.querySelector(`[data-anotacao-id="${anotacaoId}"]`);
                    if (anotacaoItem) {
                        anotacaoItem.style.opacity = '0.5';
                        setTimeout(() => {
                            anotacaoItem.remove();
                            
                            // Verifica se precisa mostrar o placeholder
                            verificarAnotacoes();
                            
                            // Fecha o modal se estiver aberto
                            const modal = bootstrap.Modal.getInstance(document.getElementById('modalNote' + anotacaoId));
                            if (modal) {
                                modal.hide();
                            }
                        }, 500);
                    }
                }
            });
        }
    });
});

// Função para excluir tarefa
document.querySelectorAll('.excluir-tarefa-modal').forEach(btn => {
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
                    // Remove a tarefa da lista
                    const tarefaItem = document.querySelector(`[data-tarefa-id="${tarefaId}"]`);
                    if (tarefaItem) {
                        tarefaItem.style.opacity = '0.5';
                        setTimeout(() => {
                            tarefaItem.remove();
                            
                            // Verifica se precisa mostrar o placeholder
                            verificarTarefas();
                            
                            // Fecha o modal se estiver aberto
                            const modal = bootstrap.Modal.getInstance(document.getElementById('modalTask' + tarefaId));
                            if (modal) {
                                modal.hide();
                            }
                            
                            // Atualiza o calendário
                            if (typeof calendar !== 'undefined') {
                                calendar.refetchEvents();
                            }
                        }, 500);
                    }
                }
            });
        }
    });
});

// Função para salvar tarefa rápida
document.getElementById('btnSalvarTarefaRapida').addEventListener('click', function() {
    const formData = new FormData(document.getElementById('formNovaTarefaRapida'));
    formData.append('nova_tarefa', 'true');
    
    fetch('tasks.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (response.ok) {
            // Fecha o modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('modalNovaTarefa'));
            modal.hide();
            
            // Limpa o formulário
            document.getElementById('formNovaTarefaRapida').reset();
            
            // Recarrega a página para mostrar a nova tarefa
            location.reload();
        }
    });
});

// Inicialização do calendário
document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');
    if (calendarEl) {
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            themeSystem: 'bootstrap5',
            locale: 'pt-br',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            selectable: true,
            editable: true,
            events: 'get_events.php',
            dateClick: function(info) {
                alert('Você clicou em: ' + info.dateStr);
            },
            eventClick: function(info) {
                alert('Evento: ' + info.event.title + '\nDescrição: ' + (info.event.extendedProps.description || 'Sem descrição'));
            }
        });
        calendar.render();
        
        // Torna o calendário acessível globalmente para as outras funções
        window.calendar = calendar;
    }
});
<?php endif; ?>
</script>

<style>
.not-logged {
    cursor: pointer;
}
.not-logged:hover {
    opacity: 0.8;
}
</style>

<?php include "templates/footer.php" ?>