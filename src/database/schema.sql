CREATE DATABASE IF NOT EXISTS db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE db;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) NOT NULL UNIQUE,
  email VARCHAR(191) NULL,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS roles (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL UNIQUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS permissions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL UNIQUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS role_permissions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  role_id INT NOT NULL,
  permission_id INT NOT NULL,
  FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
  FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS user_roles (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  role_id INT NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS refresh_tokens (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  token VARCHAR(255) NOT NULL,
  expires_at DATETIME NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE estado (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE cidade (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    estado_id INT NOT NULL,
    FOREIGN KEY (estado_id) REFERENCES estado(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE pessoa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    sobrenome VARCHAR(100) NOT NULL,
    sexo ENUM('M', 'F', 'OUTRO') NOT NULL,
    situacao ENUM('ativo', 'inativo') NOT NULL DEFAULT 'ativo',
    data_nascimento DATE,
    nacionalidade VARCHAR(100),
    municipio_nascimento INT,
    estado_id INT,
    cpf VARCHAR(14),
    numero_carteira_ieclb VARCHAR(50),
    estado_civil ENUM('solteiro', 'casado', 'divorciado', 'viuvo'),
    regime_casamento ENUM('separação de bens','comunhao parcial','comunhao universal'),
    nome_pai VARCHAR(150),
    nome_mae VARCHAR(150),
    FOREIGN KEY (municipio_nascimento) REFERENCES cidade(id),
    FOREIGN KEY (estado_id) REFERENCES estado(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE endereco (
    id INT AUTO_INCREMENT PRIMARY KEY,
    endereco_principal BOOLEAN DEFAULT FALSE,
    pessoa_id INT NOT NULL,
    cep VARCHAR(10),
    estado_id INT,
    cidade_id INT,
    bairro VARCHAR(100),
    logradouro VARCHAR(150),
    numero VARCHAR(20),
    complemento VARCHAR(100),
    tipo_moradia ENUM('casa','apto','sitio','outro'),
    situacao_moradia ENUM('propria','alugada','cedida'),
    ponto_referencia VARCHAR(255),
    endereco_correspondencia ENUM('sim','nao') DEFAULT 'nao',
    FOREIGN KEY (pessoa_id) REFERENCES pessoa(id),
    FOREIGN KEY (estado_id) REFERENCES estado(id),
    FOREIGN KEY (cidade_id) REFERENCES cidade(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE contato (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pessoa_id INT NOT NULL,
    telefone_residencial VARCHAR(20),
    telefone_celular VARCHAR(20),
    email VARCHAR(150),
    dados_proprios BOOLEAN DEFAULT TRUE,
    responsavel VARCHAR(150),
    FOREIGN KEY (pessoa_id) REFERENCES pessoa(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE parentescos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo_parentesco ENUM('pai','mae','filho(a)','tio','avo','avó'),
    pessoa_id INT NOT NULL,
    parente_id INT NOT NULL,
    FOREIGN KEY (pessoa_id) REFERENCES pessoa(id),
    FOREIGN KEY (parente_id) REFERENCES pessoa(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE integracao_desligamento (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pessoa_id INT NOT NULL,
    forma_ingresso ENUM('batismo','profissao de fé','outra'),
    outro VARCHAR(150),
    data_ingresso_ieclb DATE,
    data_admissao_comunidade DATE,
    nome_comunidade_anterior VARCHAR(150),
    cidade_id INT,
    estado_id INT,
    FOREIGN KEY (pessoa_id) REFERENCES pessoa(id),
    FOREIGN KEY (cidade_id) REFERENCES cidade(id),
    FOREIGN KEY (estado_id) REFERENCES estado(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE dados_integracao (
    id INT AUTO_INCREMENT PRIMARY KEY,
    integracao_desligamento_id INT NOT NULL,
    enum_integracao ENUM('BATISMO','CONFIRMACAO','CASAMENTO_CIVIL','CASAMENTO_RELIGIOSO'),
    data DATE,
    cidade_id INT,
    estado_id INT,
    FOREIGN KEY (integracao_desligamento_id) REFERENCES integracao_desligamento(id),
    FOREIGN KEY (cidade_id) REFERENCES cidade(id),
    FOREIGN KEY (estado_id) REFERENCES estado(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE instrucao (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pessoa_id INT NOT NULL,
    grau_estudo ENUM('analfabeto','ensino_fundamental','ensino_medio','ensino_superior','pos_graduacao','mestrado','doutorado','pos_doutorado'),
    situacao ENUM('cursando','completo','incompleto'),
    entidade_escolar VARCHAR(150),
    FOREIGN KEY (pessoa_id) REFERENCES pessoa(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE dados_profissionais (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pessoa_id INT NOT NULL,
    nome_empresa VARCHAR(150),
    cargo VARCHAR(100),
    profissao VARCHAR(100),
    aposentado BOOLEAN DEFAULT FALSE,
    data_aposentadoria DATE,
    FOREIGN KEY (pessoa_id) REFERENCES pessoa(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE informacoes_complementares (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pessoa_id INT NOT NULL,
    grupo_setor_atividade VARCHAR(255) DEFAULT NULL, -- exemplo: Casais, OASE, Louvor, etc.
    contribuicao_mensal DECIMAL(10,2) DEFAULT NULL,  -- valor que a pessoa deseja contribuir
    observacoes TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (pessoa_id) REFERENCES pessoa(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;