<?php 
// arquivo para fazer o login do usuário

$title = "Login";
$page = 'login';

include "../config.php";
include "templates/head.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') { //verifica se o formulario foi enviado
    $email = $_POST['email']; // pega o email
    $senha = $_POST['senha']; // pega a senha
    
    // faz a busca no bd pelo email
    $stmt = $pdo->prepare("SELECT * FROM usuario WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    // verifica se o usuario existe e se a senha ta correta
    if ($user && password_verify($senha, $user['senha'])) {
        $_SESSION['user_id'] = $user['codigo'];
        $_SESSION['user_name'] = $user['primeiro_nome'];
        header("Location: index.php"); // se tudo certo, manda para a pagina principal
        exit;
    } else {
        $error = "Email ou senha incorretos!";
    }
}
?>

<main>
    <div class="container" style="height: 100vh;">
        <div class="row align-items-center justify-content-center" style="height: 100%;">
            <div class="col-12 col-md-6 col-lg-4">
                <h2>Faça seu login</h2>
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                <div class="my-5">
                <form method="POST">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="senha" class="form-label">Senha</label>
                        <input type="password" class="form-control" id="senha" name="senha" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Entrar</button>
                </form>
                </div>
                <div class="my-5">
                    <p>Não possui uma conta?</p>
                    <a href="register.php" class="btn btn-outline-primary">Criar conta</a>
                </div>
            </div>
        </div>
    </div>
</main>
<?php include "templates/footer.php" ?>