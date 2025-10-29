<?php
// arquivo para editar anotações

ob_start();

$page = 'notes';
$title = 'Editar Anotação';

include "../config.php";
include "templates/head.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// pega o id da anotação
$anotacao_id = $_GET['id'] ?? null;

// se nao tiver id, manda para a pagina de anotações
if (!$anotacao_id) {
    header("Location: notes.php");
    exit;
}

// busca a anotaçao no bd garantindo que ela pertence ao usuario logado
$stmt = $pdo->prepare("SELECT * FROM notas WHERE codigo = ? AND cod_user = ?");
$stmt->execute([$anotacao_id, $_SESSION['user_id']]);
$anotacao = $stmt->fetch();

// se nao tiver anotação, manda para a pagina de anotações
if (!$anotacao) {
    header("Location: notes.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // pega os dados da anotação
    $titulo = $_POST['titulo'];
    $anotacao_texto = $_POST['anotacao'];
    $data_modificacao = date('Y-m-d');
    
    // atualiza a anotação no bd
    $stmt = $pdo->prepare("UPDATE notas SET titulo = ?, anotacao = ?, data_modificacao = ? WHERE codigo = ? AND cod_user = ?");
    $stmt->execute([$titulo, $anotacao_texto, $data_modificacao, $anotacao_id, $_SESSION['user_id']]);
    
    ob_end_clean();
    header("Location: notes.php");
    exit;
}
?>

<main>
    <section class="main">
        <div class="container mb-5" style="margin-top: 85px;">
            <h2 class="text-center mb-5">Editar Anotação</h2>
            
            <div class="row justify-content-center">
                <div class="col-12 col-md-8 col-lg-6">
                    <form method="POST">
                        <div class="mb-3">
                            <label for="titulo" class="form-label">Título</label>
                            <input type="text" class="form-control" id="titulo" name="titulo" value="<?= htmlspecialchars($anotacao['titulo']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="anotacao" class="form-label">Anotação</label>
                            <textarea class="form-control" id="anotacao" name="anotacao" rows="10" required><?= htmlspecialchars($anotacao['anotacao']) ?></textarea>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                            <a href="notes.php" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include "templates/footer.php" ?>