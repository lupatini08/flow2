<?php
// arquivo para gerenciar o início da sessão
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>