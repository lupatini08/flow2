CREATE TABLE usuario(
 codigo INT AUTO_INCREMENT PRIMARY KEY,
 primeiro_nome varchar(30) NOT NULL, 
nome_meio varchar(30), 
ultimo_nome varchar(30)  NOT NULL, 
email varchar(320) NOT NULL,
senha varchar(100) NOT NULL,
data_nascimento date not null 
);

CREATE TABLE tarefas( 
codigo INT AUTO_INCREMENT PRIMARY KEY, 
cod_user INT NOT NULL, 
urgencia INT, 
data_entrega date, 
hora_entrega time, 
descricao text, 
titulo varchar(255), 
FOREIGN KEY(cod_user) REFERENCES usuario(codigo) ); 

CREATE TABLE notas(
codigo INT AUTO_INCREMENT PRIMARY KEY, 
cod_user INT NOT NULL, 
data_criacao DATE NOT NULL, 
data_modificacao DATE NOT NULL, 
anotacao TEXT, 
titulo VARCHAR(255), 
FOREIGN KEY (cod_user)REFERENCES usuario (codigo) )


ALTER TABLE tarefas ADD COLUMN concluida TINYINT DEFAULT 0;
ALTER TABLE tarefas ADD COLUMN data_conclusao DATETIME;
