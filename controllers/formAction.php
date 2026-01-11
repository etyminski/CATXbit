<?php
session_start();
require_once 'config/conexaoBD.php';

// ve se o usuário está logado e redireciona se não estiver (proteção da página)
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: form.php");
    exit();
}

$usuario_atual = $_SESSION['usuario'];
$usuario_id_atual = $_SESSION['usuario_id'];

// query pra pegar os últimos 10 comentários com os nomes dos usuários
try {
    $stmt = $pdo->prepare("
        SELECT c.id, c.comentario, u.usuario, c.data_comentario, u.id as usuario_id
        FROM comentarios c 
        JOIN usuarios u ON c.usuario_id = u.id 
        ORDER BY c.data_comentario DESC 
        LIMIT 10
    ");
    $stmt->execute();
    $comentarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $comentarios = [];
    $erro = "Erro ao carregar comentários: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comentários - CATXbit</title>
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
                    <a href="form.php" class="button" id="comments">Minha Conta</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- sessao dos comentarios -->
    <div class="comment-received" id="comentarios-main">
        <div class="received-container">
            <h1>Comentários <span class="paw">🐾</span></h1>

            <div class="comment-display">
                <h2>Últimos Comentários</h2>

                <?php if (!empty($comentarios)): ?>
                    <?php foreach ($comentarios as $comentario): ?>
                        <div class="comment-card">
                            <div class="comment-header">
                                <h3><?php echo ($comentario['usuario']); ?></h3>
                                <?php if ($comentario['usuario_id'] == $usuario_id_atual): ?>
                                    <div class="comment-actions">
                                        <!-- form pra editar o comentario -->
                                        <form method="POST" action="editar_comentario.php" style="display: inline;">
                                            <input type="hidden" name="id" value="<?php echo $comentario['id']; ?>">
                                            <input type="hidden" name="comentario_atual" value="<?php echo ($comentario['comentario']); ?>">
                                            <button type="submit" class="btn-edit" name="editar">
                                                <img src="https://res.cloudinary.com/dlb75krri/image/upload/v1768013627/edit-icon_fs8nqv.svg" alt="Editar" class="icon">
                                            </button>
                                        </form>
                                        <!-- form pra excluir o comentario -->
                                        <form method="POST" action="excluir_comentario.php" style="display: inline;" onsubmit="return confirm('Tem certeza que deseja excluir este comentário?');">
                                            <input type="hidden" name="id" value="<?php echo $comentario['id']; ?>">
                                            <button type="submit" class="btn-delete" name="excluir">
                                                <img src="https://res.cloudinary.com/dlb75krri/image/upload/v1768013626/delete-icon_argdi0.svg" alt="Excluir" class="icon">
                                            </button>
                                        </form>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <p><?php echo ($comentario['comentario']); ?></p>
                            <div class="data-comentario">
                                <?php echo date('d/m/Y H:i', strtotime($comentario['data_comentario'])); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="comment-card">
                        <p><em>Nenhum comentário ainda...</em></p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="botao-voltar">
                <a href="form.php" class="botao">Voltar</a>
                <a href="logout.php" class="logout-btn">Sair</a>
            </div>
        </div>
    </div>
</body>
</html>