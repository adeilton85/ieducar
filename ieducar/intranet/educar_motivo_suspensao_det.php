<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	*																	     *
	*	@author Prefeitura Municipal de Itaja�								 *
	*	@updated 29/03/2007													 *
	*   Pacote: i-PLB Software P�blico Livre e Brasileiro					 *
	*																		 *
	*	Copyright (C) 2006	PMI - Prefeitura Municipal de Itaja�			 *
	*						ctima@itajai.sc.gov.br					    	 *
	*																		 *
	*	Este  programa  �  software livre, voc� pode redistribu�-lo e/ou	 *
	*	modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme	 *
	*	publicada pela Free  Software  Foundation,  tanto  a vers�o 2 da	 *
	*	Licen�a   como  (a  seu  crit�rio)  qualquer  vers�o  mais  nova.	 *
	*																		 *
	*	Este programa  � distribu�do na expectativa de ser �til, mas SEM	 *
	*	QUALQUER GARANTIA. Sem mesmo a garantia impl�cita de COMERCIALI-	 *
	*	ZA��O  ou  de ADEQUA��O A QUALQUER PROP�SITO EM PARTICULAR. Con-	 *
	*	sulte  a  Licen�a  P�blica  Geral  GNU para obter mais detalhes.	 *
	*																		 *
	*	Voc�  deve  ter  recebido uma c�pia da Licen�a P�blica Geral GNU	 *
	*	junto  com  este  programa. Se n�o, escreva para a Free Software	 *
	*	Foundation,  Inc.,  59  Temple  Place,  Suite  330,  Boston,  MA	 *
	*	02111-1307, USA.													 *
	*																		 *
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
require_once ("include/clsBase.inc.php");
require_once ("include/clsDetalhe.inc.php");
require_once ("include/clsBanco.inc.php");
require_once( "include/pmieducar/geral.inc.php" );

class clsIndexBase extends clsBase
{
	function Formular()
	{
		$this->SetTitulo( "{$this->_instituicao} i-Educar - Motivo Suspens&atilde;o" );
		$this->processoAp = "607";
	}
}

class indice extends clsDetalhe
{
	/**
	 * Titulo no topo da pagina
	 *
	 * @var int
	 */
	var $titulo;

	var $cod_motivo_suspensao;
	var $ref_usuario_exc;
	var $ref_usuario_cad;
	var $nm_motivo;
	var $descricao;
	var $data_cadastro;
	var $data_exclusao;
	var $ativo;

	function Gerar()
	{
		@session_start();
		$this->pessoa_logada = $_SESSION['id_pessoa'];
		session_write_close();

		$this->titulo = "Motivo Suspens&atilde;o - Detalhe";
		$this->addBanner( "imagens/nvp_top_intranet.jpg", "imagens/nvp_vert_intranet.jpg", "Intranet" );

		$this->cod_motivo_suspensao=$_GET["cod_motivo_suspensao"];

		$tmp_obj = new clsPmieducarMotivoSuspensao( $this->cod_motivo_suspensao );
		$registro = $tmp_obj->detalhe();

		if( ! $registro )
		{
			header( "location: educar_motivo_suspensao_lst.php" );
			die();
		}

		if( $registro["nm_motivo"] )
		{
			$this->addDetalhe( array( "Motivo Suspens&atilde;o", "{$registro["nm_motivo"]}") );
		}
		if( $registro["descricao"] )
		{
			$this->addDetalhe( array( "Descri&ccedil;&atilde;o", "{$registro["descricao"]}") );
		}

		$obj_permissoes = new clsPermissoes();
		if( $obj_permissoes->permissao_cadastra( 607, $this->pessoa_logada, 11 ) )
		{
		$this->url_novo = "educar_motivo_suspensao_cad.php";
		$this->url_editar = "educar_motivo_suspensao_cad.php?cod_motivo_suspensao={$registro["cod_motivo_suspensao"]}";
		}

		$this->url_cancelar = "educar_motivo_suspensao_lst.php";
		$this->largura = "100%";
	}
}

// cria uma extensao da classe base
$pagina = new clsIndexBase();
// cria o conteudo
$miolo = new indice();
// adiciona o conteudo na clsBase
$pagina->addForm( $miolo );
// gera o html
$pagina->MakeAll();
?>