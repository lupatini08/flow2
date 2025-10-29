<?php
// arquivo para gerenciar as anotações

ob_start(); // evita problemas com header

$page = 'notes';
$title = 'Notas';
include "../config.php";
include "templates/head.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// criar nova anotação
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nova_nota'])) {
    $titulo = $_POST['titulo'];
    $anotacao = $_POST['anotacao'];
    $data_atual = date('Y-m-d');
    
    try {
        // insere a nova anotação no bd
        $stmt = $pdo->prepare("INSERT INTO notas (cod_user, titulo, anotacao, data_criacao, data_modificacao) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], $titulo, $anotacao, $data_atual, $data_atual]);
        
        // redireciona para a página de anotações
        ob_end_clean();
        header("Location: notes.php");
        exit;
    } catch (PDOException $e) {
        $error_message = "Erro ao criar anotação: " . $e->getMessage();
    }
}

// busca todas as anotações do usuário
try {
    $stmt = $pdo->prepare("SELECT * FROM notas WHERE cod_user = ? ORDER BY data_modificacao DESC");
    $stmt->execute([$_SESSION['user_id']]);
    $notes = $stmt->fetchAll();
} catch (PDOException $e) {
    $error_message = "Erro ao carregar anotações: " . $e->getMessage();
    $notes = [];
}
?>

<main>
    <section class="main">
        <div class="container mb-5" style="margin-top: 85px;">
            <h2 class="text-center mb-5">Suas notas</h2>
            
            <!-- Mostrar mensagem de erro se existir -->
            <?php if (isset($error_message)): ?>
                <div class="alert alert-danger"><?= $error_message ?></div>
            <?php endif; ?>
            
            <!-- Formulário para nova nota -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Nova Anotação</h5>
                            <form method="POST">
                                <div class="mb-3">
                                    <label for="titulo" class="form-label">Título</label>
                                    <input type="text" class="form-control" id="titulo" name="titulo" placeholder="Título da anotação" required>
                                </div>
                                <div class="mb-3">
                                    <label for="anotacao" class="form-label">Anotação</label>
                                    <textarea class="form-control" id="anotacao" name="anotacao" rows="3" placeholder="Sua anotação..." required></textarea>
                                </div>
                                <button type="submit" name="nova_nota" class="btn btn-primary">Salvar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row my-3">
                <?php if (empty($notes)): ?>
                    <div class="col-12">
                        <div class="alert alert-info text-center">
                            Nenhuma anotação encontrada. Crie sua primeira anotação!
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach ($notes as $note): ?>
                    <div class="col-12 col-md-6 col-lg-4 mb-3">
                        <div class="card h-100">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title"><?= htmlspecialchars($note['titulo']) ?></h5>
                                <p class="card-text flex-grow-1"><?= nl2br(htmlspecialchars(substr($note['anotacao'], 0, 100))) ?>...</p>
                                <div class="d-flex gap-2 mt-auto">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNote<?= $note['codigo'] ?>">
                                        Ver Nota
                                    </button>
                                    <a href="editar_anotacao.php?id=<?= $note['codigo'] ?>" class="btn btn-outline-primary">Editar</a>
                                    <button class="btn btn-outline-danger excluir-anotacao" data-anotacao-id="<?= $note['codigo'] ?>">Excluir</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal -->
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
                                    <button class="btn btn-outline-danger excluir-anotacao-modal" data-anotacao-id="<?= $note['codigo'] ?>">Excluir</button>
                                    <small class="text-muted">Criado em: <?= $note['data_criacao'] ?><br>Modificado em: <?= $note['data_modificacao'] ?></small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>

<script>
// função para excluir anotação
document.querySelectorAll('.excluir-anotacao, .excluir-anotacao-modal').forEach(btn => {
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
                    
                    location.reload(); // recarrega a página para atualizar a lista
                } else {
                    alert('Erro ao excluir anotação.');
                }
            })
            .catch(error => {
                alert('Erro ao excluir anotação: ' + error);
            });
        }
    });
});
</script>

<?php include "templates/footer.php" ?>