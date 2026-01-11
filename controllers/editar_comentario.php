<?php
session_start();
require_once 'config/conexaoBD.php';

// ve se o usuário está logado e redireciona se não estiver (proteção da página)
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: form.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['editar'])) {
    $comentario_id = $_POST['id'] ?? '';
    $comentario_atual = $_POST['comentario_atual'] ?? '';
    $usuario_id = $_SESSION['usuario_id'];
    
    if (empty($comentario_id)) {
        header("Location: formAction.php");
        exit();
    }
    
    // se veio do form de edição (com novo comentário)
    if (isset($_POST['novo_comentario'])) {
        $novo_comentario = trim($_POST['novo_comentario']);
        
        try {
            // verifica se o comentário é do usuário logado
            $stmt = $pdo->prepare("SELECT usuario_id FROM comentarios WHERE id = ?");
            $stmt->execute([$comentario_id]);
            $comentario = $stmt->fetch();
            
            if (!$comentario || $comentario['usuario_id'] != $usuario_id) {
                $_SESSION['erro'] = "Comentário não pertence ao usuário";
                header("Location: formAction.php");
                exit();
            }
            
            // aatualiza comentário
            $stmt = $pdo->prepare("UPDATE comentarios SET comentario = ? WHERE id = ?");
            $stmt->execute([$novo_comentario, $comentario_id]);
            
            $_SESSION['sucesso'] = "Comentário atualizado";
            header("Location: formAction.php");
            exit();
            
        } catch(PDOException $e) {
            $_SESSION['erro'] = "Erro ao atualizar comentário: " . $e->getMessage();
            header("Location: formAction.php");
            exit();
        }
    } else {
        // form pra edição do comentário
        // busca o comentário atual pra mostrar no textarea
        ?>
        <!DOCTYPE html>
        <html lang="pt-BR">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Editar Comentário - CATXbit</title>
            <link rel="stylesheet" href="style.css">
        </head>
        <body>
            <nav class="navbar">
                <div class="navbar_container">
                    <a href="index.html" id="navbar_logo">CATXbit</a>
                    <ul class="navbar_menu">
                        <li class="navbar_item">
                            <a href="index.html" class="navbar_links" id="home-page">Home</a>
                        </li>
                        <li class="navbar_item">
                            <a href="facts.html" class="navbar_links" id="facts-page">Facts</a>
                        </li>
                        <li class="navbar_item">
                            <a href="about.html" class="navbar_links" id="home-page">Sobre</a>
                        </li>
                        <li class="navbar_btn">
                            <a href="form.php" class="button" id="comments">Minha Conta</a>
                        </li>
                    </ul>
                </div>
            </nav>

            <div class="comment-received">
                <div class="received-container">
                    <h1>Editar Comentário <span class="paw">🐾</span></h1>
                    
                    <form method="POST" action="editar_comentario.php" class="comment-form">
                        <input type="hidden" name="id" value="<?php echo $comentario_id; ?>">
                        <textarea name="novo_comentario" placeholder="Edite seu comentário..." required><?php echo ($comentario_atual); ?></textarea>
                        <div class="form-buttons">
                            <button type="submit" name="editar">Salvar</button>
                            <a href="formAction.php" class="botao">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </body>
        </html>
        <?php
        exit();
    }
} else {
    header("Location: formAction.php");
    exit();
}
?>