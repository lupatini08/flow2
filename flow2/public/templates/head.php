<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <?php if(isset($page) && ($page == 'calendar' || $page == "index")): ?>
        <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
    <?php endif; ?>
    <link rel="stylesheet" href="assets/css/style.css">

    <title><?php echo $title ?? " - Flow"; ?></title>
</head>
<body class="d-flex flex-column min-vh-100">

<?php
// inclui o gerenciador de sessão
include "../session.php";

// verifica se o usuario ta logado
$user_logged = isset($_SESSION['user_id']);
$user_name = $_SESSION['user_name'] ?? '';

// inclui o navbar exceto na pagina de login e de registro
if ($page != 'login' && $page != 'register') {
    include 'navbar.php'; 
}
?>
