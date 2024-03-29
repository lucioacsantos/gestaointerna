<?php
/**
*** 010******79 | Lúcio ALEXANDRE Correia dos Santos
**/

/* Classe de interação com o PostgreSQL */
require_once "class/pgsql.class.php";
require_once "class/constantes.inc.php";
require_once "class/authenticator.inc.php";
$pg = new PgSql();
$url = $pg->getCol("SELECT valor FROM db_clti.tb_config WHERE parametro='URL'");

/* Verifica Sessão de Login Ativa */
/*if (!isLoggedIn()){
    header("Location: login_clti.php");
}

if (isset($_SESSION['user_name'])){
	$perfil = $_SESSION['perfil']; 
	if ($perfil == 'TEC_CLTI'){*/

echo"

<!doctype html>
<html lang=\"pt_BR\">
  <head>
    <meta charset=\"utf-8\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1, shrink-to-fit=no\">
    <meta name=\"description\" content=\"Sistema Integrado para Centros Locais de Tecnologia da Informação\">
    <meta name=\"author\" content=\"99242991 Lúcio ALEXANDRE Correia dos Santos\">

    <title>...::: SisCLTI :::...</title>

    <link href=\"$url/css/bootstrap.min.css\" rel=\"stylesheet\">

    <!-- Dashboard CSS  -->
    <link href=\"$url/css/dashboard.css\" rel=\"stylesheet\">

    <!-- ForValidation CSS  -->
    <link href=\"$url/css/form-validation.css\" rel=\"stylesheet\">

    <!-- Stylesheet CSS -->
    <link href=\"$url/css/stylesheet.css\" rel=\"stylesheet\">

  </head>

  <body>
  <div class=\"alert alert-primary\" role=\"alert\">Verificando atualizações...</div>";

$versao = $pg->getCol("SELECT valor FROM db_clti.tb_config WHERE parametro='VERSAO' ");

if ($versao == '1.5.1'){

	echo "<div class=\"alert alert-primary\" role=\"alert\">Atualizando Banco de Dados. Aguarde...</div>";
	$pg->exec("ALTER TABLE db_clti.tb_conectividade ADD status varchar NULL;");
	$pg->exec("DROP VIEW db_clti.vw_conectividade;");
	$pg->exec("CREATE OR REPLACE VIEW db_clti.vw_conectividade
	AS SELECT conec.idtb_conectividade,
		conec.idtb_om_apoiadas,
		conec.fabricante,
		conec.modelo,
		conec.nome,
		conec.qtde_portas,
		conec.idtb_om_setores,
		conec.end_ip,
		conec.data_aquisicao,
		conec.data_garantia,
		conec.status,
		om.sigla,
		setores.sigla_setor,
		setores.compartimento
	   FROM db_clti.tb_conectividade conec,
		db_clti.tb_om_setores setores,
		db_clti.tb_om_apoiadas om
	  WHERE conec.idtb_om_apoiadas = om.idtb_om_apoiadas AND conec.idtb_om_setores = setores.idtb_om_setores;");

	echo "<div class=\"alert alert-primary\" role=\"alert\">Atualizando Controle de Internet. Aguarde...</div>";
	$pg->exec("ALTER TABLE db_clti.tb_controle_internet ADD status varchar NULL;");

	echo "<div class=\"alert alert-primary\" role=\"alert\">Atualizando Controle de USB Habilitado. Aguarde...</div>";		
	$pg->exec("ALTER TABLE db_clti.tb_controle_usb ADD status varchar NULL;");

	echo "<div class=\"alert alert-primary\" role=\"alert\">Atualizando Controle de Funções do SiGDEM. Aguarde...</div>";
	$pg->exec("ALTER TABLE db_clti.tb_funcoes_sigdem ADD status varchar NULL;");

	echo "<div class=\"alert alert-primary\" role=\"alert\">Registrando nova versão. Aguarde...</div>";
	$pg->exec("UPDATE db_clti.tb_config SET valor = '1.5.2' WHERE parametro='VERSAO' ");
	
	echo "<div class=\"alert alert-success\" role=\"alert\">Seu sistema foi atualizado, Versão 1.5.2. Aguarde...</div>
	<meta http-equiv=\"refresh\" content=\"5\">";
}

elseif ($versao == '1.5.2'){

	echo "<div class=\"alert alert-primary\" role=\"alert\">Atualizando Usuários da OM. Aguarde...</div>";
	$pg->exec("ALTER TABLE db_clti.tb_pessoal_om ADD foradaareati varchar NULL;");

	echo "<div class=\"alert alert-primary\" role=\"alert\">Atualizando Permissões de Administrador. Aguarde...</div>";
	$pg->exec("CREATE TABLE db_clti.tb_permissoes_admin (
		idtb_permissoes_admin serial NOT NULL,
		idtb_om_apoiadas int4 NOT NULL,
		idtb_estacoes int4 NOT NULL,
		autorizacao varchar(255) NOT NULL,
		CONSTRAINT tb_permissoes_admin_pkey PRIMARY KEY (idtb_permissoes_admin),
		CONSTRAINT tb_permissoes_admin_fk FOREIGN KEY (idtb_estacoes) REFERENCES db_clti.tb_estacoes(idtb_estacoes),
		CONSTRAINT tb_permissoes_admin_fk1 FOREIGN KEY (idtb_om_apoiadas) REFERENCES db_clti.tb_om_apoiadas(idtb_om_apoiadas)
	);
	COMMENT ON TABLE db_clti.tb_permissoes_admin IS 'Tabela contendo ET com Permissões de Administrador';");

	echo "<div class=\"alert alert-primary\" role=\"alert\">Atualizando Aplicativos não Padronizados. Aguarde...</div>";
	$pg->exec("CREATE TABLE db_clti.tb_nao_padronizados (
		idtb_nao_padronizados serial NOT NULL,
		idtb_om_apoiadas int4 NOT NULL,
		idtb_estacoes int4 NOT NULL,
		autorizacao varchar(255) NOT NULL,
		CONSTRAINT tb_nao_padronizados_pkey PRIMARY KEY (idtb_nao_padronizados),
		CONSTRAINT tb_nao_padronizados_fk FOREIGN KEY (idtb_estacoes) REFERENCES db_clti.tb_estacoes(idtb_estacoes),
		CONSTRAINT tb_nao_padronizados_fk1 FOREIGN KEY (idtb_om_apoiadas) REFERENCES db_clti.tb_om_apoiadas(idtb_om_apoiadas)
	);
	COMMENT ON TABLE db_clti.tb_nao_padronizados IS 'Tabela contendo ET com Aplicativos não Padronizados';");

	echo "<div class=\"alert alert-primary\" role=\"alert\">Registrando nova versão. Aguarde...</div>";
	$pg->exec("UPDATE db_clti.tb_config SET valor = '1.5.3' WHERE parametro='VERSAO' ");
	
	echo "<div class=\"alert alert-success\" role=\"alert\">Seu sistema foi atualizado, Versão 1.5.3.</div>
	<meta http-equiv=\"refresh\" content=\"5\">";
}

elseif ($versao == '1.5.3'){

	echo "<div class=\"alert alert-primary\" role=\"alert\">Atualizando PAD SIC/TIC. Aguarde...</div>";
	$pg->exec("CREATE TABLE db_clti.tb_pad_sic_tic (
		idtb_pad_sic_tic serial NOT NULL,
		idtb_om_apoiadas int4 NOT NULL,
		ano_base int4 NOT NULL,
		data_assinatura date NOT NULL,
		data_revisao date NULL,
		status VARCHAR NOT NULL,
		CONSTRAINT tb_pad_sic_tic_pkey PRIMARY KEY (idtb_pad_sic_tic),
		CONSTRAINT tb_pad_sic_tic_fk1 FOREIGN KEY (idtb_om_apoiadas) REFERENCES db_clti.tb_om_apoiadas(idtb_om_apoiadas)
	);
	COMMENT ON TABLE db_clti.tb_pad_sic_tic IS 'Tabela contendo PAD SIC/TIC';");

	$pg->exec("CREATE TABLE db_clti.tb_temas_pad_sic_tic (
		idtb_temas_pad_sic_tic serial NOT NULL,
		idtb_pad_sic_tic int4 NOT NULL,
		tema VARCHAR NOT NULL,
		status VARCHAR NOT NULL,
		justificativa VARCHAR NULL,
		CONSTRAINT tb_temas_pad_sic_tic_pkey PRIMARY KEY (idtb_temas_pad_sic_tic),
		CONSTRAINT tb_temas_pad_sic_tic_fk1 FOREIGN KEY (idtb_pad_sic_tic) REFERENCES db_clti.tb_pad_sic_tic(idtb_pad_sic_tic)
	);
	COMMENT ON TABLE db_clti.tb_pad_sic_tic IS 'Tabela contendo Temas do PAD SIC/TIC';");

	$pg->exec("CREATE TABLE db_clti.tb_ade_pad_sic_tic (
		idtb_ade_pad_sic_tic serial NOT NULL,
		idtb_pad_sic_tic int4 NOT NULL,
		idtb_pessoal_om int4 NOT NULL,
		CONSTRAINT tb_ade_pad_sic_tic_pkey PRIMARY KEY (idtb_ade_pad_sic_tic),
		CONSTRAINT tb_ade_pad_sic_tic_fk1 FOREIGN KEY (idtb_pad_sic_tic) REFERENCES db_clti.tb_pad_sic_tic(idtb_pad_sic_tic),
		CONSTRAINT tb_ade_pad_sic_tic_fk2 FOREIGN KEY (idtb_pessoal_om) REFERENCES db_clti.tb_pessoal_om(idtb_pessoal_om)
	);
	COMMENT ON TABLE db_clti.tb_pad_sic_tic IS 'Tabela contendo Participantes dos Adestramentos do PAD SIC/TIC';");

	echo "<div class=\"alert alert-primary\" role=\"alert\">Registrando nova versão. Aguarde...</div>";
	$pg->exec("UPDATE db_clti.tb_config SET valor = '1.5.4' WHERE parametro='VERSAO' ");

	echo "<div class=\"alert alert-success\" role=\"alert\">Seu sistema foi atualizado, Versão 1.5.4.</div>
	<meta http-equiv=\"refresh\" content=\"5\">";
}

elseif ($versao == '1.5.4'){

	echo "<div class=\"alert alert-primary\" role=\"alert\">Atualizando Pessoal da OM. Aguarde...</div>";

	$pg->exec("DROP VIEW db_clti.vw_controle_internet;");

	$pg->exec("DROP VIEW db_clti.vw_pessoal_om;");

	$pg->exec("CREATE OR REPLACE VIEW db_clti.vw_pessoal_om
	AS SELECT pesom.idtb_pessoal_om,
		pesom.idtb_posto_grad,
		posto.sigla AS posto_grad,
		pesom.idtb_corpo_quadro,
		corpo.sigla AS corpo_quadro,
		corpo.exibir AS exibir_corpo_quadro,
		pesom.idtb_especialidade,
		espec.sigla AS espec,
		espec.exibir AS exibir_espec,
		pesom.idtb_om_apoiadas,
		om.sigla AS sigla_om,
		pesom.nip,
		pesom.cpf,
		pesom.nome,
		pesom.nome_guerra,
		pesom.correio_eletronico,
		pesom.foradaareati,
		pesom.status
	   FROM db_clti.tb_pessoal_om pesom,
		db_clti.tb_posto_grad posto,
		db_clti.tb_corpo_quadro corpo,
		db_clti.tb_especialidade espec,
		db_clti.tb_om_apoiadas om
	  WHERE pesom.idtb_posto_grad = posto.idtb_posto_grad AND pesom.idtb_corpo_quadro = corpo.idtb_corpo_quadro 
		  AND pesom.idtb_especialidade = espec.idtb_especialidade AND pesom.idtb_om_apoiadas = om.idtb_om_apoiadas;");

	$pg->exec("CREATE OR REPLACE VIEW db_clti.vw_controle_internet
	AS SELECT internet.idtb_controle_internet,
		internet.idtb_om_apoiadas,
		om.sigla,
		internet.idtb_pessoal_om,
		pesom.posto_grad,
		pesom.corpo_quadro,
		pesom.exibir_corpo_quadro,
		pesom.espec,
		pesom.exibir_espec,
		pesom.nip,
		pesom.nome,
		pesom.nome_guerra,
		internet.perfis
	   FROM db_clti.tb_controle_internet internet,
		db_clti.vw_pessoal_om pesom,
		db_clti.tb_om_apoiadas om
	  WHERE internet.idtb_pessoal_om = pesom.idtb_pessoal_om AND internet.idtb_om_apoiadas = om.idtb_om_apoiadas;");
		  	
	echo "<div class=\"alert alert-primary\" role=\"alert\">Registrando nova versão. Aguarde...</div>";
	$pg->exec("UPDATE db_clti.tb_config SET valor = '1.5.5' WHERE parametro='VERSAO' ");

	echo "<div class=\"alert alert-success\" role=\"alert\">Seu sistema foi atualizado, Versão 1.5.5.</div>
	<meta http-equiv=\"refresh\" content=\"5\">";
}

elseif ($versao == '1.5.5'){

	echo "<div class=\"alert alert-primary\" role=\"alert\">Atualizando Controle de Adestramentos. Aguarde...</div>";
	$pg->exec("ALTER TABLE db_clti.tb_temas_pad_sic_tic ADD data_ade date NULL;");
	$pg->exec("ALTER TABLE db_clti.tb_ade_pad_sic_tic RENAME COLUMN idtb_pad_sic_tic TO idtb_temas_pad_sic_tic;");
	$pg->exec("ALTER TABLE db_clti.tb_ade_pad_sic_tic ADD CONSTRAINT tb_ade_pad_sic_tic_fk FOREIGN KEY (idtb_temas_pad_sic_tic) REFERENCES db_clti.tb_temas_pad_sic_tic(idtb_temas_pad_sic_tic);");
	$pg->exec("ALTER TABLE db_clti.tb_ade_pad_sic_tic DROP CONSTRAINT tb_ade_pad_sic_tic_fk1;");


	echo "<div class=\"alert alert-primary\" role=\"alert\">Registrando nova versão. Aguarde...</div>";
	$pg->exec("UPDATE db_clti.tb_config SET valor = '1.5.6' WHERE parametro='VERSAO' ");

	echo "<div class=\"alert alert-success\" role=\"alert\">Seu sistema foi atualizado, Versão 1.5.6.</div>
	<meta http-equiv=\"refresh\" content=\"5\">";
}

elseif ($versao == '1.5.6'){

	echo "<div class=\"alert alert-primary\" role=\"alert\">Atualizando controle de aplicativos não padronizados. 
		Aguarde...</div>";
	$pg->exec("ALTER TABLE db_clti.tb_nao_padronizados ADD soft_autorizados varchar NULL; ");

	echo "<div class=\"alert alert-primary\" role=\"alert\">Registrando nova versão. Aguarde...</div>";
	$pg->exec("UPDATE db_clti.tb_config SET valor = '1.5.7' WHERE parametro='VERSAO' ");

	echo "<div class=\"alert alert-success\" role=\"alert\">Seu sistema foi atualizado, Versão 1.5.7.</div>
	<meta http-equiv=\"refresh\" content=\"5\">";

}

elseif ($versao == '1.5.7'){

	echo "<div class=\"alert alert-primary\" role=\"alert\">Atualizando banco de dados. Aguarde...</div>";
	$pg->exec("CREATE OR REPLACE VIEW db_clti.vw_permissoes_admin
		AS SELECT adm.idtb_permissoes_admin,
			adm.idtb_om_apoiadas,
			om.sigla,
			adm.idtb_estacoes,
			et.nome,
			adm.autorizacao
		FROM db_clti.tb_permissoes_admin adm,
			db_clti.tb_estacoes et,
			db_clti.tb_om_apoiadas om
		WHERE adm.idtb_estacoes = et.idtb_estacoes AND adm.idtb_om_apoiadas = om.idtb_om_apoiadas;");
	
	$pg->exec("CREATE OR REPLACE VIEW db_clti.vw_nao_padronizados
		AS SELECT naopad.idtb_nao_padronizados,
			naopad.idtb_om_apoiadas,
			om.sigla,
			naopad.idtb_estacoes,
			et.nome,
			naopad.autorizacao,
			naopad.soft_autorizados
		FROM db_clti.tb_nao_padronizados naopad,
			db_clti.tb_estacoes et,
			db_clti.tb_om_apoiadas om
		WHERE naopad.idtb_estacoes = et.idtb_estacoes AND naopad.idtb_om_apoiadas = om.idtb_om_apoiadas;");

	echo "<div class=\"alert alert-primary\" role=\"alert\">Registrando nova versão. Aguarde...</div>";
	$pg->exec("UPDATE db_clti.tb_config SET valor = '1.5.8' WHERE parametro='VERSAO' ");

	echo "<div class=\"alert alert-success\" role=\"alert\">Seu sistema foi atualizado, Versão 1.5.8.</div>
	<meta http-equiv=\"refresh\" content=\"5\">";

}

elseif ($versao == '1.5.8'){

	$pg->exec("CREATE TABLE db_clti.tb_soft_padronizados (
		idtb_soft_padronizados serial NOT NULL,
		categoria varchar(255) NOT NULL,
		software varchar(255) NOT NULL,
		status varchar(255) NOT NULL,
		CONSTRAINT tb_soft_padronizados_pkey PRIMARY KEY (idtb_soft_padronizados)
	);
	COMMENT ON TABLE db_clti.tb_soft_padronizados IS 'Tabela contendo Softwares Padronizados';");

	echo "<div class=\"alert alert-primary\" role=\"alert\">Registrando nova versão. Aguarde...</div>";
	$pg->exec("UPDATE db_clti.tb_config SET valor = '1.5.9' WHERE parametro='VERSAO' ");

	echo "<div class=\"alert alert-success\" role=\"alert\">Seu sistema foi atualizado, Versão 1.5.9.</div>
	<meta http-equiv=\"refresh\" content=\"5\">";

}

elseif ($versao == '1.5.9'){

	echo "<div class=\"alert alert-primary\" role=\"alert\">Atualizando banco de dados. Aguarde...</div>";
	$pg->exec("DROP VIEW db_clti.vw_funcoes_sigdem");

	$pg->exec("CREATE OR REPLACE VIEW db_clti.vw_funcoes_sigdem
		AS SELECT funcsigdem.idtb_funcoes_sigdem,
			funcsigdem.idtb_om_apoiadas,
			om.sigla AS sigla_om,
			funcsigdem.descricao,
			funcsigdem.sigla,
			funcsigdem.idtb_pessoal_om,
			posto_grad.sigla AS posto_grad,
			corpo_quadro.sigla AS corpo_quadro,
			corpo_quadro.exibir AS exibir_corpo_quadro,
			espec.sigla AS espec,
			espec.exibir AS exibir_espec,
			pesom.nome_guerra
		FROM db_clti.tb_funcoes_sigdem funcsigdem,
			db_clti.tb_pessoal_om pesom,
			db_clti.tb_posto_grad posto_grad,
			db_clti.tb_corpo_quadro corpo_quadro,
			db_clti.tb_especialidade espec,
			db_clti.tb_om_apoiadas om
		WHERE funcsigdem.idtb_om_apoiadas = om.idtb_om_apoiadas AND funcsigdem.idtb_pessoal_om = pesom.idtb_pessoal_om 
		AND pesom.idtb_posto_grad = posto_grad.idtb_posto_grad AND pesom.idtb_corpo_quadro = corpo_quadro.idtb_corpo_quadro 
		AND pesom.idtb_especialidade = espec.idtb_especialidade; ");
	
	echo "<div class=\"alert alert-primary\" role=\"alert\">Registrando nova versão. Aguarde...</div>";
	$pg->exec("UPDATE db_clti.tb_config SET valor = '1.5.10' WHERE parametro='VERSAO' ");

	echo "<div class=\"alert alert-success\" role=\"alert\">Seu sistema foi atualizado, Versão 1.5.10.</div>
	<meta http-equiv=\"refresh\" content=\"5\">";

}

elseif ($versao == '1.5.10'){

	echo "<div class=\"alert alert-primary\" role=\"alert\">Atualizando banco de dados. Aguarde...</div>";
	$pg->exec("CREATE TABLE db_clti.tb_dias_troca (
		idtb_dias_troca serial NOT NULL,
		id_usuario int4 NOT NULL,
		dias_troca int4 NOT NULL,
		CONSTRAINT tb_dias_troca_pkey PRIMARY KEY (idtb_dias_troca)
	);
	COMMENT ON TABLE db_clti.tb_dias_troca IS 'Tabela contendo Dias para Troca de Senha';");

	$row = $pg->getColValues("SELECT idtb_pessoal_ti FROM db_clti.tb_pessoal_ti");
	foreach ($row as $value){
		$row = $pg->exec("INSERT INTO db_clti.tb_dias_troca (id_usuario,dias_troca) VALUES ($value,60) ");
	}

	echo "<div class=\"alert alert-primary\" role=\"alert\">Registrando nova versão. Aguarde...</div>";
	$pg->exec("UPDATE db_clti.tb_config SET valor = '1.5.11' WHERE parametro='VERSAO' ");

	echo "<div class=\"alert alert-success\" role=\"alert\">Seu sistema foi atualizado, Versão 1.5.11.</div>
	<meta http-equiv=\"refresh\" content=\"5\">";

}

elseif ($versao == '1.5.11'){

	echo "<div class=\"alert alert-primary\" role=\"alert\">Atualizando banco de dados. Aguarde...</div>";
	$pg->exec("CREATE TABLE db_clti.tb_dias_troca_clti (
		idtb_dias_troca_clti serial NOT NULL,
		id_usuario int4 NOT NULL,
		dias_troca int4 NOT NULL,
		CONSTRAINT tb_dias_troca_clti_pkey PRIMARY KEY (idtb_dias_troca_clti)
	);
	COMMENT ON TABLE db_clti.tb_dias_troca IS 'Tabela contendo Dias para Troca de Senha do CLTI';");	

	$row = $pg->getColValues("SELECT idtb_lotacao_clti FROM db_clti.tb_lotacao_clti");
	foreach ($row as $value){
		$row = $pg->exec("INSERT INTO db_clti.tb_dias_troca_clti (id_usuario,dias_troca) VALUES ($value,60) ");
	}

	echo "<div class=\"alert alert-primary\" role=\"alert\">Registrando nova versão. Aguarde...</div>";
	$pg->exec("UPDATE db_clti.tb_config SET valor = '1.5.12' WHERE parametro='VERSAO' ");

	echo "<div class=\"alert alert-success\" role=\"alert\">Seu sistema foi atualizado, Versão 1.5.12.</div>
	<meta http-equiv=\"refresh\" content=\"5\">";

}

elseif ($versao == '1.5.12'){

	echo "<div class=\"alert alert-primary\" role=\"alert\">Atualizando banco de dados. Aguarde...</div>";
	$pg->exec("CREATE TABLE db_clti.tb_rel_servico (
		idtb_rel_servico serial NOT NULL,
		sup_sai_servico varchar(255) NOT NULL,
		sup_entra_servico varchar(255) NOT NULL,
		num_rel int4 NOT NULL,
		data_entra_servico date NOT NULL,
		data_sai_servico date NOT NULL,
		cel_funcional varchar(255),
		sit_servidores varchar(255),
		sit_backup varchar(255),
		status varchar(255),
		CONSTRAINT tb_rel_servico_pkey PRIMARY KEY (idtb_rel_servico),
		CONSTRAINT tb_rel_servico_unique UNIQUE (num_rel)
	);
	COMMENT ON TABLE db_clti.tb_rel_servico IS 'Tabela contendo Relatórios de Serviço do CLTI';");

	$pg->exec("CREATE TABLE db_clti.tb_rel_servico_ocorrencias (
		idtb_rel_servico_ocorrencias serial NOT NULL,
		num_rel int4 NOT NULL,
		ocorrencia text NOT NULL,
		CONSTRAINT tb_rel_servico_ocorrencias_pkey PRIMARY KEY (idtb_rel_servico_ocorrencias),
		CONSTRAINT tb_rel_servico_ocorrencias_fk1 FOREIGN KEY (num_rel) REFERENCES db_clti.tb_rel_servico(num_rel)
	);
	COMMENT ON TABLE db_clti.tb_rel_servico_ocorrencias IS 'Tabela contendo Ocorrências do Serviço do CLTI';");

	echo "<div class=\"alert alert-primary\" role=\"alert\">Registrando nova versão. Aguarde...</div>";
	$pg->exec("UPDATE db_clti.tb_config SET valor = '1.5.13' WHERE parametro='VERSAO' ");

	echo "<div class=\"alert alert-success\" role=\"alert\">Seu sistema foi atualizado, Versão 1.5.13.</div>
	<meta http-equiv=\"refresh\" content=\"5\">";

}

elseif ($versao == '1.5.13'){

	echo "<div class=\"alert alert-primary\" role=\"alert\">Atualizando banco de dados. Aguarde...</div>";

	$pg->exec("CREATE TABLE db_clti.tb_numerador (
		idtb_numerador serial NOT NULL,
		parametro varchar(255) NOT NULL,
		prox_num int4 NOT NULL,
		CONSTRAINT tb_numerador_pkey PRIMARY KEY (idtb_numerador)
	);
	COMMENT ON TABLE db_clti.tb_rel_servico_ocorrencias IS 'Tabela contendo Números de Documentos';");

	$pg->exec("INSERT INTO db_clti.tb_numerador (parametro,prox_num) VALUES ('RelServico',1);");

	echo "<div class=\"alert alert-primary\" role=\"alert\">Registrando nova versão. Aguarde...</div>";
	$pg->exec("UPDATE db_clti.tb_config SET valor = '1.5.14' WHERE parametro='VERSAO' ");

	echo "<div class=\"alert alert-success\" role=\"alert\">Seu sistema foi atualizado, Versão 1.5.14.</div>
	<meta http-equiv=\"refresh\" content=\"5\">";
}

elseif ($versao == '1.5.14'){

	echo "<div class=\"alert alert-primary\" role=\"alert\">Atualizando banco de dados. Aguarde...</div>";

	$pg->exec("DROP TABLE db_clti.tb_rel_servico CASCADE");
	$pg->exec("DROP TABLE db_clti.tb_rel_servico_ocorrencias CASCADE");

	$pg->exec("CREATE TABLE db_clti.tb_rel_servico (
		idtb_rel_servico serial NOT NULL,
		sup_sai_servico int4 NOT NULL,
		sup_entra_servico int4 NOT NULL,
		num_rel int4 NOT NULL,
		data_entra_servico date NOT NULL,
		data_sai_servico date NOT NULL,
		cel_funcional varchar(255),
		sit_servidores varchar(255),
		sit_backup varchar(255),
		status varchar(255),
		CONSTRAINT tb_rel_servico_pkey PRIMARY KEY (idtb_rel_servico),
		CONSTRAINT tb_rel_servico_unique UNIQUE (num_rel),
		CONSTRAINT tb_rel_servico_fkey1 FOREIGN KEY (sup_sai_servico) REFERENCES db_clti.tb_lotacao_clti(idtb_lotacao_clti),
		CONSTRAINT tb_rel_servico_fkey2 FOREIGN KEY (sup_entra_servico) REFERENCES db_clti.tb_lotacao_clti(idtb_lotacao_clti)
	);
	COMMENT ON TABLE db_clti.tb_rel_servico IS 'Tabela contendo Relatórios de Serviço do CLTI';");

	$pg->exec("CREATE TABLE db_clti.tb_rel_servico_ocorrencias (
		idtb_rel_servico_ocorrencias serial NOT NULL,
		num_rel int4 NOT NULL,
		ocorrencia text NOT NULL,
		CONSTRAINT tb_rel_servico_ocorrencias_pkey PRIMARY KEY (idtb_rel_servico_ocorrencias),
		CONSTRAINT tb_rel_servico_ocorrencias_fk1 FOREIGN KEY (num_rel) REFERENCES db_clti.tb_rel_servico(num_rel)
	);
	COMMENT ON TABLE db_clti.tb_rel_servico_ocorrencias IS 'Tabela contendo Ocorrências do Serviço do CLTI';");

	echo "<div class=\"alert alert-primary\" role=\"alert\">Registrando nova versão. Aguarde...</div>";
	$pg->exec("UPDATE db_clti.tb_config SET valor = '1.5.15' WHERE parametro='VERSAO' ");

	echo "<div class=\"alert alert-success\" role=\"alert\">Seu sistema foi atualizado, Versão 1.5.15.</div>
	<meta http-equiv=\"refresh\" content=\"5\">";

}

elseif ($versao == '1.5.15'){

	echo "<div class=\"alert alert-primary\" role=\"alert\">Atualizando banco de dados. Aguarde...</div>";

	$pg->exec("ALTER TABLE db_clti.tb_lotacao_clti ADD idtb_funcoes_clti int4 NULL;");
	$pg->exec("ALTER TABLE db_clti.tb_rel_servico_ocorrencias ADD status varchar(255) NULL;");
	
	$pg->exec("CREATE TABLE db_clti.tb_funcoes_clti (
		idtb_funcoes_clti serial NOT NULL,
		sigla varchar(255) NOT NULL,
		descricao varchar(255) NOT NULL,
		CONSTRAINT tb_funcoes_clti_pkey PRIMARY KEY (idtb_funcoes_clti)
	);
	COMMENT ON TABLE db_clti.tb_funcoes_clti IS 'Tabela contendo Funções do CLTI';");

	echo "<div class=\"alert alert-primary\" role=\"alert\">Registrando nova versão. Aguarde...</div>";
	$pg->exec("UPDATE db_clti.tb_config SET valor = '1.5.16' WHERE parametro='VERSAO' ");

	echo "<div class=\"alert alert-success\" role=\"alert\">Seu sistema foi atualizado, Versão 1.5.16.</div>
	<meta http-equiv=\"refresh\" content=\"5\">";
}

elseif ($versao == '1.5.16'){

	echo "<div class=\"alert alert-primary\" role=\"alert\">Atualizando banco de dados. Aguarde...</div>";

	$pg->exec("ALTER TABLE db_clti.tb_funcoes_clti ADD requerida varchar(3) NULL;");

	$pg->exec("INSERT INTO db_clti.tb_funcoes_clti (sigla,descricao,requerida) VALUES 
		('Enc.CLTI','Encarregado do CLTI','Sim'),
		('Aprov.Rel.Sv','Aprovação de Relatórios de Serviço','Sim'),
		('Sup.Sv.','Supervisor de Serviço','Sim') ");

	echo "<div class=\"alert alert-primary\" role=\"alert\">Registrando nova versão. Aguarde...</div>";
	$pg->exec("UPDATE db_clti.tb_config SET valor = '1.5.17' WHERE parametro='VERSAO' ");

	echo "<div class=\"alert alert-success\" role=\"alert\">Seu sistema foi atualizado, Versão 1.5.17.</div>
	<meta http-equiv=\"refresh\" content=\"5\">";

}

elseif ($versao == '1.5.17'){

	echo "<div class=\"alert alert-primary\" role=\"alert\">Atualizando banco de dados. Aguarde...</div>";

	$pg->exec("ALTER TABLE db_clti.tb_lotacao_clti ADD tarefa varchar(25) NULL;");

	echo "<div class=\"alert alert-primary\" role=\"alert\">Registrando nova versão. Aguarde...</div>";
	$pg->exec("UPDATE db_clti.tb_config SET valor = '1.5.18' WHERE parametro='VERSAO' ");

	echo "<div class=\"alert alert-success\" role=\"alert\">Seu sistema foi atualizado, Versão 1.5.18.</div>
	<meta http-equiv=\"refresh\" content=\"5\">";

}

elseif ($versao == '1.5.18'){

	echo "<div class=\"alert alert-primary\" role=\"alert\">Atualizando banco de dados. Aguarde...</div>";

	$pg->exec("CREATE TABLE db_clti.tb_rel_servico_log (
		idtb_rel_servico_log serial NOT NULL,
		idtb_lotacao_clti int4 NOT NULL,
		num_rel int4 NOT NULL,
		cod_aut varchar(256) NOT NULL,
		data_hora timestamp NOT NULL,
		CONSTRAINT tb_rel_servico_log_pkey PRIMARY KEY (idtb_rel_servico_log),
		CONSTRAINT tb_rel_servico_log_fk1 FOREIGN KEY (num_rel) REFERENCES db_clti.tb_rel_servico(num_rel)
	);
	COMMENT ON TABLE db_clti.tb_rel_servico_log IS 'Tabela contendo Log de Aprovação do Relatório de Serviço';");

	echo "<div class=\"alert alert-primary\" role=\"alert\">Registrando nova versão. Aguarde...</div>";
	$pg->exec("UPDATE db_clti.tb_config SET valor = '1.5.19' WHERE parametro='VERSAO' ");

	echo "<div class=\"alert alert-success\" role=\"alert\">Seu sistema foi atualizado, Versão 1.5.19.</div>
	<meta http-equiv=\"refresh\" content=\"5\">";

}

elseif ($versao == '1.5.19'){

	echo "<div class=\"alert alert-primary\" role=\"alert\">Atualizando banco de dados. Aguarde...</div>";

	$pg->exec("CREATE TABLE db_clti.tb_rel_sv_v2 (
		idtb_rel_servico serial NOT NULL,
		sup_sai_servico int4 NOT NULL,
		sup_entra_servico int4 NOT NULL,
		num_rel int4 NOT NULL,
		data_entra_servico date NOT NULL,
		data_sai_servico date NOT NULL,
		cel_funcional varchar(255),
		sit_servidores varchar(255),
		sit_backup varchar(255),
		status varchar(255),
		CONSTRAINT tb_rel_sv_v2_pkey PRIMARY KEY (idtb_rel_servico),
		CONSTRAINT tb_rel_sv_v2_unique UNIQUE (num_rel),
		CONSTRAINT tb_rel_sv_v2_fkey1 FOREIGN KEY (sup_sai_servico) REFERENCES db_clti.tb_lotacao_clti(idtb_lotacao_clti),
		CONSTRAINT tb_rel_sv_v2_fkey2 FOREIGN KEY (sup_entra_servico) REFERENCES db_clti.tb_lotacao_clti(idtb_lotacao_clti)
	);
	COMMENT ON TABLE db_clti.tb_rel_sv_v2 IS 'Tabela contendo Relatórios de Serviço do CLTI Versão 2';");

	$pg->exec("CREATE TABLE db_clti.tb_rel_sv_v2_ocorrencias (
		idtb_rel_servico_ocorrencias serial NOT NULL,
		num_rel int4 NOT NULL,
		ocorrencia text NOT NULL,
		CONSTRAINT tb_rel_sv_v2_ocorrencias_pkey PRIMARY KEY (idtb_rel_servico_ocorrencias),
		CONSTRAINT tb_rel_sv_v2_ocorrencias_fk1 FOREIGN KEY (num_rel) REFERENCES db_clti.tb_rel_sv_v2(num_rel)
	);
	COMMENT ON TABLE db_clti.tb_rel_sv_v2_ocorrencias IS 'Tabela contendo Ocorrências do Serviço do CLTI';");

	$pg->exec("CREATE TABLE db_clti.tb_gw_om (
		idtb_gw_om serial NOT NULL,
		idtb_om_apoiadas int4 NOT NULL,
		ip_gw varchar(15) NOT NULL,
		status varchar(255),
		qtde_vrf int4,
		CONSTRAINT tb_gw_om_pkey PRIMARY KEY (idtb_gw_om),
		CONSTRAINT tb_gw_om_fk1 FOREIGN KEY (idtb_om_apoiadas) REFERENCES db_clti.tb_om_apoiadas(idtb_om_apoiadas)
	);
	COMMENT ON TABLE db_clti.tb_gw_om IS 'Tabela contendo status do Gateway das OM Apoiadas';");

	$pg->exec("DROP VIEW db_clti.vw_pessoal_clti");

	$pg->exec("CREATE OR REPLACE VIEW db_clti.vw_pessoal_clti
		AS SELECT clti.idtb_lotacao_clti,
			clti.idtb_posto_grad,
			posto.sigla AS sigla_posto_grad,
			clti.idtb_corpo_quadro,
			corpo.sigla AS sigla_corpo_quadro,
			corpo.exibir AS exibir_corpo_quadro,
			clti.idtb_especialidade,
			espec.sigla AS sigla_espec,
			espec.exibir AS exibir_espec,
			clti.nip,
			clti.cpf,
			clti.nome,
			clti.nome_guerra,
			clti.correio_eletronico,
			clti.perfil,
			clti.tarefa,
			clti.status
		FROM db_clti.tb_lotacao_clti clti,
			db_clti.tb_posto_grad posto,
			db_clti.tb_corpo_quadro corpo,
			db_clti.tb_especialidade espec
		WHERE clti.idtb_posto_grad = posto.idtb_posto_grad AND clti.idtb_corpo_quadro = corpo.idtb_corpo_quadro 
			AND clti.idtb_especialidade = espec.idtb_especialidade;");
	
	$pg->exec("DROP VIEW db_clti.vw_qualificacao_clti");

	$pg->exec("CREATE OR REPLACE VIEW db_clti.vw_qualificacao_clti
		AS SELECT quali.idtb_qualificacao_clti,
			quali.idtb_lotacao_clti,
			pesti.idtb_posto_grad,
			posto.sigla AS sigla_posto_grad,
			pesti.idtb_corpo_quadro,
			corpo.sigla AS sigla_corpo_quadro,
			corpo.exibir AS exibir_corpo_quadro,
			pesti.idtb_especialidade,
			espec.sigla AS sigla_espec,
			espec.exibir AS exibir_espec,
			pesti.nome_guerra,
			pesti.nip,
			pesti.cpf,
			quali.instituicao,
			quali.tipo,
			quali.nome_curso,
			quali.meio,
			quali.situacao,
			quali.data_conclusao,
			quali.carga_horaria,
			quali.custo
		FROM db_clti.tb_qualificacao_clti quali,
			db_clti.tb_lotacao_clti pesti,
			db_clti.tb_posto_grad posto,
			db_clti.tb_corpo_quadro corpo,
			db_clti.tb_especialidade espec
		WHERE quali.idtb_lotacao_clti = pesti.idtb_lotacao_clti AND pesti.idtb_posto_grad = posto.idtb_posto_grad 
			AND pesti.idtb_corpo_quadro = corpo.idtb_corpo_quadro AND pesti.idtb_especialidade = espec.idtb_especialidade 
			AND pesti.status = 'ATIVO';");

	$pg->exec("DROP VIEW db_clti.vw_qualificacao_pesti");

	$pg->exec("CREATE OR REPLACE VIEW db_clti.vw_qualificacao_pesti
		AS SELECT quali.idtb_qualificacao_ti,
			quali.idtb_pessoal_ti,
			pesti.idtb_posto_grad,
			posto.sigla AS sigla_posto_grad,
			pesti.idtb_corpo_quadro,
			corpo.sigla AS sigla_corpo_quadro,
			corpo.exibir AS exibir_corpo_quadro,
			pesti.idtb_especialidade,
			espec.sigla AS sigla_espec,
			espec.exibir AS exibir_espec,
			pesti.idtb_om_apoiadas,
			om.sigla AS sigla_om,
			pesti.nome_guerra,
			pesti.nip,
			pesti.cpf,
			quali.instituicao,
			quali.tipo,
			quali.nome_curso,
			quali.meio,
			quali.situacao,
			quali.data_conclusao,
			quali.carga_horaria,
			quali.custo
		FROM db_clti.tb_qualificacao_ti quali,
			db_clti.tb_pessoal_ti pesti,
			db_clti.tb_posto_grad posto,
			db_clti.tb_corpo_quadro corpo,
			db_clti.tb_especialidade espec,
			db_clti.tb_om_apoiadas om
		WHERE quali.idtb_pessoal_ti = pesti.idtb_pessoal_ti AND pesti.idtb_posto_grad = posto.idtb_posto_grad 
			AND pesti.idtb_corpo_quadro = corpo.idtb_corpo_quadro AND pesti.idtb_especialidade = espec.idtb_especialidade 
			AND pesti.idtb_om_apoiadas = om.idtb_om_apoiadas and pesti.status = 'ATIVO';");

	$pg->exec("INSERT INTO db_clti.tb_config (parametro,valor) VALUES 
		('author','99242991 Lúcio ALEXANDRE Correia dos Santos lucio.alexandre@marinha.mil.br'),
		('description','Sistema de Gestão de TI'),
		('TITULO','SiGTI'),
		('generator','LucioACSantos') ");
	
	echo "<div class=\"alert alert-primary\" role=\"alert\">Registrando nova versão. Aguarde...</div>";
	$pg->exec("UPDATE db_clti.tb_config SET valor = '1.5.20' WHERE parametro='VERSAO' ");

	echo "<div class=\"alert alert-success\" role=\"alert\">Seu sistema foi atualizado, Versão 1.5.20.</div>
	<meta http-equiv=\"refresh\" content=\"5\">";
}

elseif ($versao == '1.5.20'){

	echo "<div class=\"alert alert-primary\" role=\"alert\">Atualizando Banco de Dados. Aguarde...</div>";

	$pg->exec("CREATE TABLE db_clti.tb_det_serv (
		idtb_det_serv serial NOT NULL,
		idtb_lotacao_clti int4 NOT NULL,
		data_entra_servico date NOT NULL,
		data_sai_servico date NOT NULL,
		status varchar(255),
		CONSTRAINT tb_det_serv_pkey PRIMARY KEY (idtb_det_serv),
		CONSTRAINT tb_det_serv_fkey1 FOREIGN KEY (idtb_lotacao_clti) REFERENCES db_clti.tb_lotacao_clti(idtb_lotacao_clti)
	);
	COMMENT ON TABLE db_clti.tb_det_serv IS 'Tabela contendo Detalhe de Serviço do CLTI Versão 2';");

	echo "<div class=\"alert alert-primary\" role=\"alert\">Registrando nova versão. Aguarde...</div>";
	$pg->exec("UPDATE db_clti.tb_config SET valor = '1.5.21' WHERE parametro='VERSAO' ");

	echo "<div class=\"alert alert-success\" role=\"alert\">Seu sistema foi atualizado, Versão 1.5.21.</div>
	<meta http-equiv=\"refresh\" content=\"5\">";
}

elseif ($versao == '1.5.21'){

	echo "<div class=\"alert alert-primary\" role=\"alert\">Atualizando Banco de Dados. Aguarde...</div>";

	$pg->exec("ALTER TABLE db_clti.tb_pessoal_ti ADD COLUMN secret varchar (16) DEFAULT 'Não ativado' NOT NULL");

	$pg->exec("ALTER TABLE db_clti.tb_lotacao_clti ADD COLUMN secret varchar (16) DEFAULT 'Não ativado' NOT NULL");

	echo "<div class=\"alert alert-primary\" role=\"alert\">Registrando nova versão. Aguarde...</div>";
	$pg->exec("UPDATE db_clti.tb_config SET valor = '1.5.22' WHERE parametro='VERSAO' ");

	echo "<div class=\"alert alert-success\" role=\"alert\">Seu sistema foi atualizado, Versão 1.5.22.</div>
	<meta http-equiv=\"refresh\" content=\"5\">";
}

elseif ($versao == '1.5.22'){

	echo "<div class=\"alert alert-primary\" role=\"alert\">Atualizando Banco de Dados. Aguarde...</div>";

	$pg->exec("CREATE TABLE db_clti.tb_acesso_suspeito (
		idtb_acesso_suspeito serial NOT NULL,
		end_ip varchar(15) NOT NULL,
		data_acesso date NOT NULL,
		hora_acesso time NOT NULL,
		contador int4 NOT NULL,
		status varchar(255),
		CONSTRAINT tb_acesso_suspeito_pkey PRIMARY KEY (idtb_acesso_suspeito)
	);
	COMMENT ON TABLE db_clti.tb_acesso_suspeito IS 'Tabela contendo Acessos Suspeitos ao Sistema';");

	echo "<div class=\"alert alert-primary\" role=\"alert\">Registrando nova versão. Aguarde...</div>";
	$pg->exec("UPDATE db_clti.tb_config SET valor = '1.5.23' WHERE parametro='VERSAO' ");

	echo "<div class=\"alert alert-success\" role=\"alert\">Seu sistema foi atualizado, Versão 1.5.23.</div>
	<meta http-equiv=\"refresh\" content=\"5\">";
}

elseif ($versao == '1.5.23'){

	echo "<div class=\"alert alert-primary\" role=\"alert\">Atualizando Banco de Dados. Aguarde...</div>";

	$pg->exec("CREATE TABLE db_clti.tb_range_ip (
		idtb_range_ip serial NOT NULL,
		idtb_om_apoiadas int4 NOT NULL,
		sub_rede varchar(15) NOT NULL,
		mascara int4 NOT NULL,
		CONSTRAINT tb_range_ip_pkey PRIMARY KEY (idtb_range_ip)
	);
	COMMENT ON TABLE db_clti.tb_range_ip IS 'Faixas de IP das OM Apoiadas';");

	$pg->exec("ALTER TABLE db_clti.tb_pessoal_ti ADD COLUMN ip_acesso varchar (15) DEFAULT '0.0.0.0' NOT NULL");

	$pg->exec("ALTER TABLE db_clti.tb_pessoal_ti ADD COLUMN cont_erro int4 DEFAULT '0' NOT NULL");

	$pg->exec("ALTER TABLE db_clti.tb_lotacao_clti ADD COLUMN ip_acesso varchar (15) DEFAULT '0.0.0.0' NOT NULL");

	$pg->exec("ALTER TABLE db_clti.tb_lotacao_clti ADD COLUMN cont_erro int4 DEFAULT '0' NOT NULL");

	echo "<div class=\"alert alert-primary\" role=\"alert\">Registrando nova versão. Aguarde...</div>";
	$pg->exec("UPDATE db_clti.tb_config SET valor = '1.5.24' WHERE parametro='VERSAO' ");

	echo "<div class=\"alert alert-success\" role=\"alert\">Seu sistema foi atualizado, Versão 1.5.24.</div>
	<meta http-equiv=\"refresh\" content=\"5\">";
}

elseif ($versao == '1.5.24'){

	echo "<div class=\"alert alert-primary\" role=\"alert\">Atualizando Banco de Dados. Aguarde...</div>";

	$pg->exec("CREATE TABLE db_clti.tb_estacoes_excluidas (
		idtb_estacoes_excluidas serial NOT NULL,
		idtb_om_apoiadas int4 NOT NULL,
		fabricante varchar(255) NOT NULL,
		modelo varchar(255) NOT NULL,
		nome varchar(255) NOT NULL,
		end_ip varchar(255) NOT NULL,
		end_mac varchar(255) NOT NULL,
		data_del date NOT NULL,
		hora_del time NOT NULL,
		CONSTRAINT tb_estacoes_excluidas_pkey PRIMARY KEY (idtb_estacoes_excluidas)
	);
	COMMENT ON TABLE db_clti.tb_estacoes_excluidas IS 'Estações de trabalho excluídas';");

	$pg->exec("CREATE TABLE db_clti.tb_conectividade_excluidos (
		idtb_conectividade_excluidos serial4 NOT NULL,
		idtb_om_apoiadas int4 NOT NULL,
		fabricante varchar(255) NOT NULL,
		modelo varchar(255) NOT NULL,
		end_ip varchar(255) NULL,
		data_del date NOT NULL,
		hora_del time NOT NULL,
		CONSTRAINT tb_conectividade_excluidos_pkey PRIMARY KEY (idtb_conectividade_excluidos)
	);
	COMMENT ON TABLE db_clti.tb_conectividade_excluidos IS 'Equipamentos de conectividade excluídos';");
	
	$pg->exec("CREATE TABLE db_clti.tb_servidores_excluidos (
		idtb_servidores_excluidos serial4 NOT NULL,
		idtb_om_apoiadas int4 NOT NULL,
		fabricante varchar(255) NOT NULL,
		modelo varchar(255) NOT NULL,
		end_ip varchar(255) NULL,
		end_mac varchar(255) NULL,
		data_del date NULL,
		hora_del time NULL,
		CONSTRAINT tb_servidores_excluidos_pkey PRIMARY KEY (idtb_servidores_excluidos)
	);
	COMMENT ON TABLE db_clti.tb_servidores_excluidos IS 'Servidores excluídos'; ");

	$pg->exec("CREATE TABLE db_clti.tb_pessoal_excluido (
		idtb_pessoal_excluido serial4 NOT NULL,
		idtb_om_apoiadas int4 NOT NULL,
		nip varchar(8) NOT NULL,
		cpf varchar(11) NOT NULL,
		nome varchar(255) NOT NULL,
		nome_guerra varchar(255) NOT NULL,
		funcao varchar(255) NOT NULL,
		data_del date NOT NULL,
		hora_del time NOT NULL,
		CONSTRAINT tb_pessoal_excluido_pkey PRIMARY KEY (idtb_pessoal_excluido)
	);
	COMMENT ON TABLE db_clti.tb_pessoal_excluido IS 'Pessoal de TI excluído'; ");
	
	echo "<div class=\"alert alert-primary\" role=\"alert\">Registrando nova versão. Aguarde...</div>";
	$pg->exec("UPDATE db_clti.tb_config SET valor = '1.5.25' WHERE parametro='VERSAO' ");

	echo "<div class=\"alert alert-success\" role=\"alert\">Seu sistema foi atualizado, Versão 1.5.25.</div>
	<meta http-equiv=\"refresh\" content=\"5\">";

}

elseif ($versao == '1.5.25'){

	echo "<div class=\"alert alert-primary\" role=\"alert\">Atualizando o sistema. Aguarde...</div>";

	$pg->exec("ALTER TABLE db_clti.tb_om_apoiadas ADD COLUMN chave_acesso varchar (16) DEFAULT '000000' NOT NULL");

	$pg->exec("ALTER TABLE db_clti.tb_pessoal_ti ADD COLUMN tel_contato varchar (16) DEFAULT '000000' ");

	$pg->exec("ALTER TABLE db_clti.tb_pessoal_ti ADD COLUMN retelma varchar (16) DEFAULT '000000' ");

	$pg->exec("ALTER TABLE db_clti.tb_rel_servico ADD COLUMN num_midia_bakcup int4 DEFAULT 1 NOT NULL");

	$pg->exec("INSERT INTO db_clti.tb_numerador (parametro,prox_num) VALUES ('NumMidiaBk',1)");

	$pg->exec("CREATE TABLE db_clti.tb_agenda_administrativa (
		idtb_agenda_administrativa serial4 NOT NULL,
		assunto varchar(255) NOT NULL,
		setor_resp varchar(255) NOT NULL,
		om_apoiadas varchar(255) NOT NULL,
		destino varchar(255) NOT NULL,
		prazo date NOT NULL,
		situacao varchar(255) NOT NULL,
		observacoes varchar(255) NOT NULL,
		CONSTRAINT tb_agenda_administrativa_pkey PRIMARY KEY (idtb_agenda_administrativa)
	);
	COMMENT ON TABLE db_clti.tb_agenda_administrativa IS 'Agenda Administrativa do CLTI'; ");

	$pg->exec("CREATE TABLE db_clti.tb_inspecoes_visitas (
		idtb_inspecoes_visitas serial4 NOT NULL,
		tipo varchar(255) NOT NULL,
		om_apoiadas varchar(255) NOT NULL,
		data_agendada date NOT NULL,
		situacao varchar(255) NOT NULL,
		observacoes varchar(255) NOT NULL,
		CONSTRAINT tb_inspecoes_visitas_pkey PRIMARY KEY (idtb_inspecoes_visitas)
	);
	COMMENT ON TABLE db_clti.tb_inspecoes_visitas IS 'Agenda de Inspeções e Visitas do CLTI'; ");

	$pg->exec("CREATE TABLE db_clti.tb_acomp_inspecoes_visitas (
		idtb_acomp_inspecoes_visitas serial4 NOT NULL,
		idtb_inspecoes_visitas int4 NOT NULL,
		data_acompanhamento date NOT NULL,
		situacao varchar(255) NOT NULL,
		observacoes varchar(255) NOT NULL,
		CONSTRAINT tb_acomp_inspecoes_visitas_pkey PRIMARY KEY (idtb_acomp_inspecoes_visitas)
	);
	COMMENT ON TABLE db_clti.tb_inspecoes_visitas IS 'Acompanhamento de Inspeções e Visitas do CLTI'; ");

	$pg->exec("CREATE TABLE db_clti.tb_midias_backup (
		idtb_midias_backup serial4 NOT NULL,
		tipo varchar(255) NOT NULL,
		numero int4 NOT NULL,
		capacidade int4 NOT NULL,
		situacao varchar(255) NOT NULL,
		CONSTRAINT tb_midias_backup_pkey PRIMARY KEY (idtb_midias_backup),
		CONSTRAINT tb_midias_backup_key1 UNIQUE (numero)
	);
	COMMENT ON TABLE db_clti.tb_midias_backup IS 'Mídias de armazenamento de backup'; ");

	$pg->exec("CREATE TABLE db_clti.tb_tipos_midias_backup (
		idtb_tipos_midias_backup serial4 NOT NULL,
		descricao varchar(255) NOT NULL,
		sigla varchar(255) NOT NULL,
		CONSTRAINT tb_tipos_midias_backup_pkey PRIMARY KEY (idtb_tipos_midias_backup)
	);
	COMMENT ON TABLE db_clti.tb_tipos_midias_backup IS 'Tipos de mídias de armazenamento de backup'; ");

	$pg->exec("CREATE TABLE db_clti.tb_origem_backup (
		idtb_origem_backup serial4 NOT NULL,
		idtb_servidores int4 NOT NULL,
		dados_backup varchar(255) NOT NULL,
		freq_backup varchar (50) NOT NULL,
		tipo_backup varchar (50) NOT NULL,
		dest_backup varchar (50) NOT NULL,
		CONSTRAINT tb_origem_backup_pkey PRIMARY KEY (idtb_origem_backup)
	);
	COMMENT ON TABLE db_clti.tb_origem_backup IS 'Tabela contendo informações do backup'; ");

	$pg->exec("DROP VIEW db_clti.vw_pessoal_ti");

	$pg->exec("CREATE OR REPLACE VIEW db_clti.vw_pessoal_ti
	AS SELECT pesti.idtb_pessoal_ti,
		pesti.idtb_posto_grad,
		posto.sigla AS sigla_posto_grad,
		pesti.idtb_corpo_quadro,
		corpo.sigla AS sigla_corpo_quadro,
		corpo.exibir AS exibir_corpo_quadro,
		pesti.idtb_especialidade,
		espec.sigla AS sigla_espec,
		espec.exibir AS exibir_espec,
		pesti.idtb_om_apoiadas,
		om.sigla AS sigla_om,
		pesti.nip,
		pesti.cpf,
		pesti.nome,
		pesti.nome_guerra,
		pesti.correio_eletronico,
		pesti.tel_contato,
		pesti.retelma,
		pesti.idtb_funcoes_ti,
		funcao.descricao AS desc_funcao,
		funcao.sigla AS sigla_funcao,
		pesti.status
	FROM db_clti.tb_pessoal_ti pesti,
		db_clti.tb_posto_grad posto,
		db_clti.tb_corpo_quadro corpo,
		db_clti.tb_especialidade espec,
		db_clti.tb_om_apoiadas om,
		db_clti.tb_funcoes_ti funcao
	WHERE pesti.idtb_posto_grad = posto.idtb_posto_grad AND pesti.idtb_corpo_quadro = corpo.idtb_corpo_quadro 
	AND pesti.idtb_especialidade = espec.idtb_especialidade AND pesti.idtb_om_apoiadas = om.idtb_om_apoiadas 
	AND pesti.idtb_funcoes_ti = funcao.idtb_funcoes_ti; ");

	echo "<div class=\"alert alert-primary\" role=\"alert\">Registrando nova versão. Aguarde...</div>";

	$pg->exec("UPDATE db_clti.tb_config SET valor = '1.6' WHERE parametro='VERSAO' ");

	echo "<div class=\"alert alert-success\" role=\"alert\">Seu sistema foi atualizado, Versão 1.6.</div>
	<meta http-equiv=\"refresh\" content=\"5\">";

}

elseif ($versao == '1.6'){

	$pg->exec("DROP TABLE db_clti.tb_origem_backup ");
	$pg->exec("DROP TABLE db_clti.tb_rel_sv_v2_ocorrencias ");
	$pg->exec("DROP TABLE db_clti.tb_rel_sv_v2 ");

	$pg->exec("CREATE TABLE db_clti.tb_origem_backup (
		idtb_origem_backup serial4 NOT NULL,
		idtb_servidores int4 NOT NULL,
		diretorio_backup varchar(255) NOT NULL,
		freq_backup varchar (255) NOT NULL,
		CONSTRAINT tb_origem_backup_pkey PRIMARY KEY (idtb_origem_backup),
		CONSTRAINT tb_origem_backup_fkey1 FOREIGN KEY (idtb_servidores) REFERENCES db_clti.tb_servidores(idtb_servidores)
	);
	COMMENT ON TABLE db_clti.tb_origem_backup IS 'Tabela contendo informações dos servidores para realizar backup'; ");

	$pg->exec("CREATE TABLE db_clti.tb_srv_backup (
		idtb_srv_backup serial4 NOT NULL,
		idtb_servidores int4 NOT NULL,
		diretorio_backup varchar(255) NOT NULL,
		CONSTRAINT tb_srv_backup_pkey PRIMARY KEY (idtb_srv_backup),
		CONSTRAINT tb_srv_backup_fkey1 FOREIGN KEY (idtb_servidores) REFERENCES db_clti.tb_servidores(idtb_servidores)
	);
	COMMENT ON TABLE db_clti.tb_srv_backup IS 'Tabela contendo informações do servidor de backup'; ");

	$pg->exec("CREATE OR REPLACE VIEW db_clti.vw_origem_backup
	AS SELECT srv.idtb_servidores,
		srv.end_ip,
		srv.nome,
		srv_bk.diretorio_backup,
		srv_bk.freq_backup
	FROM db_clti.tb_servidores srv,
		db_clti.tb_origem_backup srv_bk
	WHERE srv.idtb_servidores = srv_bk.idtb_servidores;	");

	$pg->exec("CREATE OR REPLACE VIEW db_clti.vw_srv_backup
	AS SELECT srv_bk.idtb_srv_backup,
		srv.idtb_servidores,
		srv.end_ip,
		srv.nome,
		srv_bk.diretorio_backup
	FROM db_clti.tb_servidores srv,
		db_clti.tb_srv_backup srv_bk
	WHERE srv.idtb_servidores = srv_bk.idtb_servidores; ");

	$pg->exec("CREATE TABLE db_clti.tb_log_backup (
		idtb_log_backup serial4 NOT NULL,
		srv_origem varchar(255) NOT NULL,
		dir_origem varchar(255) NOT NULL,
		data_inicio date NOT NULL,
		hora_inicio time NOT NULL,
		data_fim date NOT NULL,
		hora_fim time NOT NULL,
		tamanho int4 NOT NULL
	); 
	COMMENT ON TABLE db_clti.tb_log_backup IS 'Tabela contendo log dos backup realizados'; ");

	/** Novo Modelo de Relatório de Serviço Personalizado */

	$pg->exec("CREATE TABLE db_clti.tb_titulos_rel_sv_v2 (
		idtb_titulos_rel_sv_v2 serial NOT NULL,
		titulo varchar(255),
		descricao varchar(255),
		CONSTRAINT tb_titulos_rel_sv_v2_pkey PRIMARY KEY (idtb_titulos_rel_sv_v2)
	);
	COMMENT ON TABLE db_clti.tb_titulos_rel_sv_v2 IS 'Tabela contendo Títulos do Relatórios de Serviço do CLTI Versão 2'; ");

	$pg->exec("CREATE TABLE db_clti.tb_subtitulos_rel_sv_v2 (
		idtb_subtitulos_rel_sv_v2 serial NOT NULL,
		idtb_titulos_rel_sv_v2 int4 NOT NULL,
		subtitulo varchar(255),
		descricao varchar(255),
		CONSTRAINT tb_subtitulos_rel_sv_v2_pkey PRIMARY KEY (idtb_subtitulos_rel_sv_v2),
		CONSTRAINT tb_subtitulos_rel_sv_v2_fkey FOREIGN KEY (idtb_titulos_rel_sv_v2) REFERENCES db_clti.tb_titulos_rel_sv_v2(idtb_titulos_rel_sv_v2)
	);
	COMMENT ON TABLE db_clti.tb_subtitulos_rel_sv_v2 IS 'Tabela contento Subtítulos do Relatórios de Serviço do CLTI Versão 2'; ");
	
	$pg->exec("CREATE TABLE db_clti.tb_itens_rel_sv_v2 (
		idtb_itens_rel_sv_v2 serial NOT NULL,
		idtb_subtitulos_rel_sv_v2 int4 NOT NULL,
		item varchar(255),
		descricao varchar(255),
		valores varchar(255),
		CONSTRAINT tb_itens_rel_sv_v2_pkey PRIMARY KEY (idtb_itens_rel_sv_v2),
		CONSTRAINT tb_itens_rel_sv_v2_fkey FOREIGN KEY (idtb_subtitulos_rel_sv_v2) REFERENCES db_clti.tb_subtitulos_rel_sv_v2(idtb_subtitulos_rel_sv_v2)
	);
	COMMENT ON TABLE db_clti.tb_itens_rel_sv_v2 IS 'Tabela contendo Itens do Relatórios de Serviço do CLTI Versão 2';");

	$pg->exec("CREATE TABLE db_clti.tb_rel_servico_v2 (
		idtb_rel_servico_v2 serial NOT NULL,
		idtb_itens_rel_sv_v2 int4 NOT NULL,
		conteudo varchar(255),
		observacoes varchar(255),
		CONSTRAINT tb_rel_servico_v2_pkey PRIMARY KEY (idtb_rel_servico_v2),
		CONSTRAINT tb_rel_servico_v2_fkey FOREIGN KEY (idtb_itens_rel_sv_v2) REFERENCES db_clti.tb_itens_rel_sv_v2(idtb_itens_rel_sv_v2)
	);
	COMMENT ON TABLE db_clti.tb_itens_rel_sv_v2 IS 'Tabela contendo Dados do Relatórios de Serviço do CLTI Versão 2';");

	$pg->exec("CREATE OR REPLACE VIEW db_clti.vw_config_relv2
	AS SELECT titulos.idtb_titulos_rel_sv_v2,
		titulos.titulo,
		titulos.descricao AS desctitulo,
		subtitulos.idtb_subtitulos_rel_sv_v2,
		subtitulos.subtitulo,
		subtitulos.descricao AS descsubtitulo,
		itens.idtb_itens_rel_sv_v2,
		itens.item,
		itens.descricao AS descitem,
		itens.valores
	FROM db_clti.tb_titulos_rel_sv_v2 titulos,
		db_clti.tb_subtitulos_rel_sv_v2 subtitulos,
		db_clti.tb_itens_rel_sv_v2 itens
	WHERE titulos.idtb_titulos_rel_sv_v2 = subtitulos.idtb_titulos_rel_sv_v2 
	    AND subtitulos.idtb_subtitulos_rel_sv_v2 = itens.idtb_subtitulos_rel_sv_v2; ");

	$pg->exec("INSERT INTO db_clti.tb_titulos_rel_sv_v2 (titulo,descricao) VALUES 
		('1) Supervisor de Serviço que Sai:', 'Supervisor de serviço que está passando o serviço.'),
		('2) Supervisor de Serviço que Entra:', 'Supervisor de serviço que está entrando de serviço.'),
		('3) Monitoramento:', 'Monitoramento dos ativos de rede e OM Apoiadas.'),
		('4) Situação dos Servidores:', 'Ocorrências relacionadas aos servidores.'),
		('5) Rotina de Backup:', 'Ocorrências relacionadas à Rotina de Backup.'),
		('6) Chamados:', 'Chamados Abertos/Solucionados/Encaminhados.'),
		('7) Infraestrutura:', 'Compartimentos e infraestrutura física.') ");

	$pg->exec("INSERT INTO db_clti.tb_subtitulos_rel_sv_v2 (idtb_titulos_rel_sv_v2,subtitulo,descricao) VALUES
		(3, '3.1) Rádio Enlace', 'Ocorrências relacionadas aos equipamentos de rádio enlace.'),
		(3, '3.2) Backbone', 'Ocorrências relacionadas aos equipamentos do backbone.'),
		(3, '3.3) MPLS', 'Ocorrências relacionadas aos equipamentos do MPLS.'),
		(3, '3.4) Internet Distrital', 'Ocorrências relacionadas aos equipamentos da Internet Distrital.'),
		(3, '3.5) Roteadores', 'Ocorrências relacionadas aos equipamentos roteadores.'),
		(3, '3.6) Câmeras', 'Ocorrências relacionadas aos equipamentos de vigilância.'),
		(3, '3.7) Refrigeração', 'Ocorrências relacionadas aos equipamentos de refrigeração.'),
		(3, '3.8) Celular Funcional', 'Ocorrências relacionadas ao celular funcional.'),
		(4,'4.1) SiGDEM','Ocorrências relacionadas aos servidores de SiGDEM.'),
		(4,'4.2) Correio Eletrônico','Ocorrências relacionadas ao servidor do Correio Notes.'),
		(4,'4.3) Páginas','Ocorrências relacionadas ao servidor Web.'),
		(4,'4.4) SAMBA','Ocorrências relacionadas ao servidor de arquivos do CLTI.'),
		(4,'4.5) WSUS','Ocorrências relacionadas ao servidor de atualizações do Windows.'),
		(5,'5.1) SiGDEM','Situação do backup dos servidores de SiGDEM.'),
		(5,'5.2) Arquivos','Situação do backup do servidor de arquivos do CLTI.'),
		(5,'5.3) Páginas','Situação do backup do servidor de Web.'),
		(6,'6.1) CLTI','Chamados abertos/solucionados pelo CLTI.'),
		(6,'6.2) CTIM','Chamados abertos/Encaminhados para o CTIM.'),
		(6,'6.3) DAdM','Chamados abertos/Encaminhados para a DAdM.'),
		(6,'6.4) Claro','Chamados abertos/Encaminhados para a Claro (MPLS).'),
		(6,'6.5) RNP','Chamados abertos/Encaminhados para a RNP (Internet Distrital).'),
		(6,'6.6) ROD','Chamados abertos/Encaminhados para a ROD.'),
		(7,'7.1) Sala do CLTI','Ocorrências físicas na sala do CLTI.'),
		(7,'7.2) Sala dos Transmissores','Ocorrências na Sala dos Transmissores da Rádio MB.'),
		(7,'7.3) Sala dos Servidores','Ocorrências físicas na Sala dos Servidores na BNN.'),
		(7,'7.4) Paiol de Fibra','Ocorrências físicas no Paiol de Fibras na BNN.'),
		(7,'7.5) Casa da Torre','Ocorrências físicas na Casa da Torre no Com3ºDN.') ");

	$pg->exec("INSERT INTO db_clti.tb_itens_rel_sv_v2 (idtb_subtitulos_rel_sv_v2,item,descricao,valores) VALUES 
		(1,'Situação','Situação dos equipamentos do Rádio Enlace.','P,R,INOP'),
		(1,'Observações','Observações sobre os equipamentos de Rádio Enlace.','N/C'),
		(2,'Situação','Situação dos equipamentos do Backbone.','P,R,INOP'),
		(2,'Observações','Observações sobre os equipamentos do Backbone.','N/C'),
		(3,'Situação','Situação dos equipamentos do MPLS.','P,R,INOP'),
		(3,'Observações','Observações sobre os equipamentos do MPLS.','N/C'),
		(4,'Situação','Situação dos equipamentos da Internet Distrital.','P,R,INOP'),
		(4,'Observações','Observações sobre os equipamentos da Internet Distrital.','N/C'),
		(5,'Situação','Situação dos equipamentos Roteadores.','P,R,INOP'),
		(5,'Observações','Observações sobre os equipamentos Roteadores.','N/C'),
		(6,'Situação','Situação dos equipamentos do CFTV.','P,R,INOP'),
		(6,'Observações','Observações sobre os equipamentos do CFTV.','N/C'),
		(7,'Situação','Situação dos equipamentos de Refrigeração.','P,R,INOP'),
		(7,'Observações','Observações sobre os equipamentos de Refrigeração.','N/C'),
		(8,'Situação','Situação do Celular Funcional.','P,R,INOP'),
		(8,'Observações','Observações sobre o Celular Funcional.','N/C'),
		(9,'Situação','Situação dos Servidores do SiGDEM.','P,R,INOP'),
		(9,'Observações','Observações sobre os Servidores do SiGDEM.','N/C'),
		(10,'Situação','Situação dos Servidores do Correio Eletrônico.','P,R,INOP'),
		(10,'Observações','Observações sobre os Servidores do Correio Eletrônico.','N/C'),
		(11,'Situação','Situação dos Servidores das Páginas Web.','P,R,INOP'),
		(11,'Observações','Observações sobre os Servidores das Páginas Web.','N/C'),
		(12,'Situação','Situação do Servidor de Arquivos do CLTI.','P,R,INOP'),
		(12,'Observações','Observações sobre o Servidor de Arquivos do CLTI.','N/C'),
		(13,'Situação','Situação do Servidor WSUS.','P,R,INOP'),
		(13,'Observações','Observações sobre o Servidor WSUS.','N/C'),
		(14,'Situação','Situação da Rotina de Backup do SiGDEM.','R,NR'),
		(14,'Observações','Observações sobre a Rotina de Backup do SiGDEM.','N/C'),
		(15,'Situação','Situação da Rotina de Backup do Servidor de Arquivos do CLTI.','R,NR'),
		(15,'Observações','Observações sobre a Rotina de Backup do Servidor de Arquivos do CLTI.','N/C'),
		(16,'Situação','Situação da Rotina de Backup do Servidor Web.','R,NR'),
		(16,'Observações','Observações sobre a Rotina de Backup do Servidor Web.','N/C'),
		(17,'Aberto','Chamados Abertos para o CLTI.','N/C'),
		(17,'Pendente','Chamados Pendentes para o CLTI.','N/C'),
		(17,'Solucionado','Chamados Solucionados pelo CLTI.','N/C'),
		(18,'Aberto','Chamados Encaminhados para o CTIM.','N/C'),
		(18,'Pendente','Chamados Pendentes Encaminhados para o CTIM.','N/C'),
		(18,'Solucionado','Chamados Solucionados pelo CTIM.','N/C'),
		(19,'Aberto','Chamados Abertos Encaminhados para a DAdM.','N/C'),
		(19,'Pendente','Chamados Pendentes Encaminhados para a DAdM.','N/C'),
		(19,'Solucionado','Chamados Solucionados pela DAdM.','N/C'),
		(20,'Aberto','Chamados Abertos Encaminhados para a Claro.','N/C'),
		(20,'Pendente','Chamados Pendentes Encaminhados para a Claro.','N/C'),
		(20,'Solucionado','Chamados Solucionados pela Claro.','N/C'),
		(21,'Aberto','Chamados Abertos Encaminhados para a RNP.','N/C'),
		(21,'Pendente','Chamados Pendentes Encaminhados para a RNP.','N/C'),
		(21,'Solucionado','Chamados Solucionados pela RNP.','N/C'),
		(22,'Aberto','Chamados Abertos Encaminhados para a ROD.','N/C'),
		(22,'Pendente','Chamados Pendentes Encaminhados para a ROD.','N/C'),
		(22,'Solucionado','Chamados Solucionados pela ROD.','N/C'),
		(23,'Situação','Situação da Infrastrututra Física do CLTI.','P,R,INOP'),
		(23,'Observações','Observações sobre a Infrastrututra Física do CLTI.','N/C'),
		(24,'Situação','Situação sobre a Infrastrututra Física da Rádio Marinha.','P,R,INOP'),
		(24,'Observações','Observações sobre a a Infrastrututra Física da Rádio Marinha.','N/C'),
		(25,'Situação','Situação a Infrastrututra Física da Sala dos Servidores.','P,R,INOP'),
		(25,'Observações','Observações sobre a a Infrastrututra Física da Sala dos Servidores.','N/C'),
		(26,'Situação','Situação a Infrastrututra Física do Paiol de Fibra.','P,R,INOP'),
		(26,'Observações','Observações sobre a a Infrastrututra Física do Paiol de Fibra.','N/C'),
		(27,'Situação','Situação da Infrastrututra Física da Casa da Torre.','P,R,INOP'),
		(27,'Observações','Observações sobre a Infrastrututra Física da Casa da Torre.','N/C') ");

	/** Final Modelo de Relatório de Serviço Personalizado */

	$pg->exec("UPDATE db_clti.tb_config SET valor = '1.7' WHERE parametro='VERSAO' ");

	echo "<div class=\"alert alert-success\" role=\"alert\">Seu sistema foi atualizado, Versão 1.7.</div>
	<meta http-equiv=\"refresh\" content=\"5\">";

}

elseif ($versao == '1.7'){

	echo "<div class=\"alert alert-success\" role=\"alert\">Seu sistema está atualizado, Versão 1.7.</div>
	<meta http-equiv=\"refresh\" content=\"5;url=$url\">";

}

else{

	echo "<div class=\"alert alert-primary\" role=\"alert\">Verifique sua instalação!</div>";

}
