-- ============================================
-- REMOVER PROCEDURE SE EXISTIR (COM MYSQLI)
-- ============================================

USE mysql;

DROP PROCEDURE IF EXISTS criar_database_catxbit;

-- ============================================
-- CRIAÇÃO DA PROCEDURE (USANDO ; DIRETO)
-- ============================================
CREATE PROCEDURE criar_database_catxbit()
BEGIN
    DECLARE db_count INT DEFAULT 0;
    
    -- Verifica se o banco já existe
    SELECT COUNT(*) INTO db_count 
    FROM information_schema.SCHEMATA 
    WHERE SCHEMA_NAME = 'catxbit';
    
    IF db_count = 0 THEN
        -- Cria o banco de dados
        CREATE DATABASE catxbit;
        SELECT '1: Database catxbit criada com sucesso!' AS Mensagem;
        
        -- NOTA: O 'USE' dentro da procedure não afeta a conexão PHP
        -- então vamos criar tabelas com nome completo
        CREATE TABLE catxbit.usuarios (
          id INT(11) NOT NULL AUTO_INCREMENT,
          usuario VARCHAR(50) NOT NULL,
          senha VARCHAR(255) NOT NULL,
          data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
          PRIMARY KEY (id),
          UNIQUE KEY usuario (usuario)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
        
        SELECT '2: Tabela usuarios criada com sucesso!' AS Mensagem;
        
        CREATE TABLE catxbit.comentarios (
          id INT(11) NOT NULL AUTO_INCREMENT,
          usuario_id INT(11) NOT NULL,
          comentario TEXT NOT NULL,
          data_comentario TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
          PRIMARY KEY (id),
          KEY usuario_id (usuario_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
        
        SELECT '3: Tabela comentarios criada com sucesso!' AS Mensagem;
        
        -- Adicionar constraint separadamente
        ALTER TABLE catxbit.comentarios 
        ADD CONSTRAINT comentarios_ibfk_1 
        FOREIGN KEY (usuario_id) 
        REFERENCES catxbit.usuarios (id) 
        ON DELETE CASCADE;
        
        SELECT '4: Constraints aplicadas com sucesso!' AS Mensagem;
        SELECT '✅ Instalação completa! Database catxbit criada com 2 tabelas.' AS Final;
        
    ELSE
        -- Se já existe, apenas mostra mensagem
        SELECT 'ℹ️ Database catxbit já existe. Nenhuma alteração foi feita.' AS Mensagem;
    END IF;
END;

-- ============================================
-- EXECUÇÃO DA PROCEDURE
-- ============================================
CALL criar_database_catxbit();

-- ============================================
-- LIMPEZA (Remove a procedure após uso)
-- ============================================
DROP PROCEDURE criar_database_catxbit;