<?php
session_start();
require_once 'config/conexaoBD.php';

// INSERT
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: form.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $comentario = trim($_POST['comment']);
    $usuario_id = $_SESSION['usuario_id'];
    
    if (!empty($comentario)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO comentarios (usuario_id, comentario) VALUES (?, ?)");
            $stmt->execute([$usuario_id, $comentario]);
            
            header("Location: formAction.php");
            exit();
        } catch(PDOException $e) {
            die("Erro ao salvar comentário: " . $e->getMessage());
        }
    }
}

header("Location: form.php");
exit();
?>

