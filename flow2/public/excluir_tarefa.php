<?php
// arquivo para excluir uma tarefa

include "../config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // pega o id da tarefa
    $tarefa_id = $_POST['tarefa_id'];
    
    // deleta a tarefa do bd, so se for de um usuario logado
    $stmt = $pdo->prepare("DELETE FROM tarefas WHERE codigo = ? AND cod_user = ?");
    $stmt->execute([$tarefa_id, $_SESSION['user_id']]);
    
    echo "success";
    exit;
}
?>