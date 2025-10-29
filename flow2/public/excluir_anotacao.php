<?php
// arquivo para excluir uma anotação

include "../config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // pega o id da anotação
    $anotacao_id = $_POST['anotacao_id'];
    
    // deleta a anotação do bd, so se for de um usuario logado
    $stmt = $pdo->prepare("DELETE FROM notas WHERE codigo = ? AND cod_user = ?");
    $stmt->execute([$anotacao_id, $_SESSION['user_id']]);
    
    echo "success";
    exit;
}
?>