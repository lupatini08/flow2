<?php
// arquivo para pegar as tarefas do bd e mostrar no calendario 

include "../config.php";

// define o cabeçalho para json
header('Content-Type: application/json');

// verifica se o usuario ta logado, se nao retorna vazio
if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit;
}

// busca as tarefas nao concluidas que possuem data de entrega no bd
$stmt = $pdo->prepare("SELECT codigo, titulo, data_entrega as start, descricao FROM tarefas WHERE cod_user = ? AND data_entrega IS NOT NULL AND concluida = 0");
$stmt->execute([$_SESSION['user_id']]);
$tasks = $stmt->fetchAll();

// formata as tarefas para o formato do calendario
$events = [];
foreach ($tasks as $task) {
    $events[] = [
        'id' => $task['codigo'],
        'title' => $task['titulo'],
        'start' => $task['start'],
        'description' => $task['descricao']
    ];
}

// retorna as tarefas no formato json
echo json_encode($events);
?>