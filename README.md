##############################################
## Sistema HouseHUB                         ##
##    Sistema de controle de ambientes      ##
##############################################

[![Build Status](https://travis-ci.org/souzabrizolara/HouseHub.png?branch=master)](https://travis-ci.org/souzabrizolara/HouseHub)
[![Coverage Status](https://coveralls.io/repos/souzabrizolara/HouseHub/badge.png?branch=master)](https://coveralls.io/r/souzabrizolara/HouseHub?branch=master)


1. Instalação

Enquanto nós trabalhamos em uma página para instalação automática, você
já pode instalar o sistema da forma tradicional: Modificando valores de
configuração.

1.1 - Requisitos do sistema
	Para executar as funcionalidades desta aplicação, sua máquina vai
	precisar de:
	1. Sistema operacional Linux (recomendado);
	2. Servidor HTTP com suporte a PHP (recomenda-se Apache Server);
	3. PHP5;
	4. Banco de Dados (utilizamos por padrão MySQL server);
	
	Os padrões marcados como "recomendados" foram os utilizados durante
	o desenvolvimento da aplicação. Não garantimos que o sistema irá
	funcionar com outras tecnologias. 

1.2 - Passo a passo
	Siga os passos abaixo para instalar a aplicação
	
-----------------------------------------------------------
1) Tenha certeza de suprir todos os requisitos da seção 1.1;
2) Crie um banco de dados com um nome qualquer. Defina o collation deste
	como 'utf8_general_ci'.
3) Crie um usuário com permissões de INSERT, UPDATE, DELETE e SELECT.
