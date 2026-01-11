<?php
session_start();
require_once 'config/conexaoBD.php';

$erro = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $usuario = trim($_POST['usuario']);
    $senha = $_POST['password'];
    
    if (strlen($usuario) < 2) {
        $erro = "O usuário deve ter pelo menos 2 caracteres";
    } else {
        try {
            // verifica se usuário existe
            $stmt = $pdo->prepare("SELECT id, usuario, senha FROM usuarios WHERE usuario = ?");
            $stmt->execute([$usuario]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($senha, $user['senha'])) {
                // logado!
                $_SESSION['usuario_id'] = $user['id'];
                $_SESSION['usuario'] = $user['usuario'];
                $_SESSION['logado'] = true;
                
                header("Location: form.php");
                exit();
            } else {
                $erro = "Usuário ou senha incorretos";
            }
        } catch(PDOException $e) {
            $erro = "Erro no sistema: " . $e->getMessage();
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['registro'])) {
    $usuario = trim($_POST['novo_usuario']);
    $senha = $_POST['nova_senha'];
    
    if (strlen($usuario) < 2) {
        $erro = "O usuário deve ter pelo menos 2 caracteres";
    } else {
        try {
            // verifica se usuário já existe
            $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE usuario = ?");
            $stmt->execute([$usuario]);
            
            if ($stmt->fetch()) {
                $erro = "Usuário já existe";
            } else {
                // cria novo usuário
                $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO usuarios (usuario, senha) VALUES (?, ?)");
                $stmt->execute([$usuario, $senha_hash]);
                
                $usuario_id = $pdo->lastInsertId();
                $_SESSION['usuario_id'] = $usuario_id;
                $_SESSION['usuario'] = $usuario;
                $_SESSION['logado'] = true;
                
                header("Location: form.php");
                exit();
            }
        } catch(PDOException $e) {
            $erro = "Erro no sistema: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CATXbit</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar">
        <div class="navbar_container">
            <a href="http://catxbit.wuaze.com/" id="navbar_logo">CATXbit</a>
            <ul class="navbar_menu">
                <li class="navbar_item">
                    <a href="http://catxbit.wuaze.com/" class="navbar_links" id="home-page">Home</a>
                </li>
                <li class="navbar_item">
                    <a href="facts.html" class="navbar_links" id="facts-page">Facts</a>
                </li>
                <li class="navbar_item">
                    <a href="about.html" class="navbar_links" id="home-page">Sobre</a>
                </li>
                <li class="navbar_btn">
                    <a href="form.php" class="button" id="comments">Login</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="comment-section">
        <h1><?php echo isset($_SESSION['logado']) ? 'Bem-vindo, ' . ($_SESSION['usuario']) . '! <span class="paw">🐾</span>' : 'Login / Registro <span class="paw">🐾</span>'; ?></h1> 

        
        <?php if (isset($erro) && !empty($erro)): ?>
            <div class="error-message"><?php echo $erro; ?></div>
                <?php endif; ?>
        
        <?php if (!isset($_SESSION['logado'])): ?>
            <!-- Formulário de Login -->
            <div class="login-form">
                <h2>Login</h2>
                <form method="POST" action="form.php">
                    <input type="hidden" name="login" value="1" id="login-field">
                    <input type="text" name="usuario" placeholder="Usuário" required value="<?php echo ($_POST['usuario'] ?? ''); ?>">
                    <input type="password" name="password" placeholder="Senha" required>
                    <button type="submit">Entrar</button>
                </form>
            </div>

            <!-- Formulário de Registro -->
            <div class="register-form">
                <h2>Registrar</h2>
                <form method="POST" action="form.php">
                    <input type="hidden" name="registro" value="1" id="register-field">
                    <input type="text" name="novo_usuario" placeholder="Novo usuário" required value="<?php echo ($_POST['novo_usuario'] ?? ''); ?>">
                    <input type="password" name="nova_senha" placeholder="Nova senha" required>
                    <button type="submit">Registrar</button>
                </form>
            </div>
        <?php else: ?>
            <!-- Usuário logado - Formulário de comentário -->
            <div class="comment-form">
                <h2>Deixe seu comentário</h2>
                <form method="POST" action="processa_comentario.php">
                    <textarea name="comment" placeholder="Escreva seu comentário..." required></textarea>
                    <button type="submit">Enviar Comentário</button>
                </form>
                
                <div class="logout-section">
                    <a href="logout.php" class="logout-btn">Sair da conta</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>