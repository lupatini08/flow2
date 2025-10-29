<?php
// arquivo para concluir uma tarefa

include "../config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // pega o id da tarefa e se foi concluida
    $tarefa_id = $_POST['tarefa_id'];
    $concluida = $_POST['concluida'];
    
    if ($concluida) {
        // se foi concluida, salva a data
        $data_conclusao = date('Y-m-d H:i:s');
        $stmt = $pdo->prepare("UPDATE tarefas SET concluida = 1, data_conclusao = ? WHERE codigo = ? AND cod_user = ?");
        $stmt->execute([$data_conclusao, $tarefa_id, $_SESSION['user_id']]);
    } else {
        // se foi desmarcada, remove a data
        $stmt = $pdo->prepare("UPDATE tarefas SET concluida = 0, data_conclusao = NULL WHERE codigo = ? AND cod_user = ?");
        $stmt->execute([$tarefa_id, $_SESSION['user_id']]);
    }
    
    // retorna sucess para o js saber que deu certo
    echo "success";
    exit;
}
?>