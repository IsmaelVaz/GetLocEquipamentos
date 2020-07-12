<?php
/* * ****************************************************************
 * $Rev: 1 $
 * $LastChangedDate: 2012-07-05 10:15:42 -0300 (qui, 05 jul 2012) $
 * $LastChangedBy: author $
 * $Author: author $
 * * ----------------------------------------------------------------------
 */ 

	 /**
		* atributo class
		* This class manipulates the table atributo
		* @requires    >= PHP 5
		* @author      P2S Tecnologia           <suporte@p2s.com.br>
		* @copyright   (C)2015       <http://www.p2s.com.br>
		* @DataBase = p2s_ecommerce_v1.5
		* @DatabaseType = mysql
		* @Host = localhost
		* @date 03/08/2015
		**/ 
class atributo extends core_atributo {


	/**
	 * @category Insert dinamico
	 * Metodo generico para salvar dados no banco de dados, pode ser sobrescrito.
	 * Por padrao o keyfield sera a chave primaria da tabela
	 * @param campos em array $campos
	 * @param KeyField $key
	 */
    function save($campos,$key=NULL)
   	{

		/** Coloque aqui seu codigo **/
		$return = parent::save($campos,$key);
		/** Coloque aqui seu codigo **/
		return $return;
	}

	/**
	 * @category Exclusao dinamica
	 * Metodo generico para excluir dados no banco de dados, pode ser sobrescrito.
	 * Por padrao o keyfield sera a chave primaria da tabela
	 * @param identificacao do registro $value
	 * @param KeyField $key
	 */
	function delete($value,$key=NULL)
	{
		/** Coloque aqui seu codigo **/
		$return = parent::delete($value,$key);
		/** Coloque aqui seu codigo **/
		return $return;
	}

	/**
	 * @category Listagem de um unico campo
	 * Metodo generico para listar um unico campo no banco de dados, pode ser sobrescrito.
	 * @param campo de consulta $key
	 * @param ordem da pesquisa $order
	 * @param filtro opcional $filter
	 */
	static function listSingle($conn,$key,$order ='',$filter ='', $class='') {

		/** Coloque aqui seu codigo **/
		$return =  parent::listSingle($conn,$key,$order,$filter,__CLASS__);
		/** Coloque aqui seu codigo **/
		return $return;
	}

	/**
	 * @category Listagem de todos os campos da tabela
	 * Metodo generico listar todas os campos no banco de dados, pode ser sobrescrito.
	 * @param filtro opcional $filter
	 * @param ordem da pesquisa $order
	 */
	static function listAll($conn,$filter="",$order ='', $class='') {
		/** Coloque aqui seu codigo **/
		$arrLista = parent::listAll($conn,$filter,$order,__CLASS__);
		/** Coloque aqui seu codigo **/
		return $arrLista;
	}
	
	static function partialResult($conn,$filter="",$order ='',$atual=0,$rpp=10,$path="", $class='') {

		return parent::partialResult($conn,$filter,$order,$atual,$rpp,$path,__CLASS__);
	}

}
?>