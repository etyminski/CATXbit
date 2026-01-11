<?php
session_start();
require_once 'config/conexaoBD.php';

// ve se o usuário está logado e redireciona se não estiver (proteção da página)
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: form.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['excluir'])) {
    $comentario_id = $_POST['id'] ?? '';
    $usuario_id = $_SESSION['usuario_id'];
    
    if (empty($comentario_id)) {
        header("Location: formAction.php");
        exit();
    }
    
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
        
        // exclui o comentário
        $stmt = $pdo->prepare("DELETE FROM comentarios WHERE id = ?");
        $stmt->execute([$comentario_id]);
        
        $_SESSION['sucesso'] = "Comentário excluído";
        header("Location: formAction.php");
        exit();
        
    } catch(PDOException $e) {
        $_SESSION['erro'] = "Erro ao excluir comentário: " . $e->getMessage();
        header("Location: formAction.php");
        exit();
    }
} else {
    header("Location: formAction.php");
    exit();
}
?>