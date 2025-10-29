<?php
// arquivo para fazer o logout do usuário

include "config.php"; // inclui o arquivo de configuração que inicia a sessão e a conexão com o banco

$_SESSION = array(); // apaga todas as variáveis de sessão

//apaga também o cookie de sessão
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();

// redireciona para a pagina de login
header("Location: public/login.php");
exit;
?>