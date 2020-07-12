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
		* @copyright   (C)2019       <http://www.p2s.com.br>
		* @DataBase = p2s_ecommerce_v3
		* @DatabaseType = mysql
		* @Host = localhost
		* @date 05/04/2019
		**/ 

require_once("commons.class.php");

abstract class core_atributo extends Commons {

	/* Objects from tables */
	/* end Objects from tables */
	/* Properties */
 	protected $keyField = "id_atributo"; /* Key Field */
 	public $id_atributo;
 	public $atributo;

	/* GET FIELDS FROM TABLE */	

		/**
			 * @category Recuperar dados da propriedade id_atributo
			 * Metodo para recuperar o valor de id_atributo
			 * $dado = $MyClass->GET_id_atributo()
			 * 

			 */
		function GET_id_atributo (){
			return $this->id_atributo;
		}/* End of get id_atributo */

		/**
			 * @category Recuperar dados da propriedade atributo
			 * Metodo para recuperar o valor de atributo
			 * $dado = $MyClass->GET_atributo()
			 * 

			 */
		function GET_atributo (){
			return $this->atributo;
		}/* End of get atributo */

	/* GET OBJECTS */

	/* END GET OBJECTS */
	

	/* SET FIELDS FROM TABLE */	

		/**
			 * @category Inserir dados na propriedade id_atributo
			 * Metodo inserir o valor de id_atributo
			 * $MyClass->SET_id_atributo($valor)
			 * 

			 */
		function SET_id_atributo ($value){
		$this->id_atributo = $value;
		}/* End of SET id_atributo */

		/**
			 * @category Inserir dados na propriedade atributo
			 * Metodo inserir o valor de atributo
			 * $MyClass->SET_atributo($valor)
			 * 

			 */
		function SET_atributo ($value){
		$this->atributo = $value;
		}/* End of SET atributo */


	/**
	 * @category Constructor
	 * Metodo que sera chamado logo que a classe for instanciada.
	 * Por padrao o KeyField sera a chave primaria da tabela
	 * @param DB Source $conn
	 * @param Value $id
	 * @param KeyField $key
	 */
    function __construct($conn,$id=NULL,$key=NULL)
    {
	
		
    	$this->conn = $conn;
    	if($id != NULL)
	 	{
			if($key != NULL){
				$this->populateObject(get_class($this),$id,$key);
			}else{
			$this->populateObject(get_class($this),$id,$this->keyField);
			}
		}
		$this->keyField = 'id_atributo';
    }

	/**
	 * @category Insert dinamico
	 * Metodo generico para salvar dados no banco de dados, pode ser sobrescrito.
	 * Por padrao o keyfield sera a chave primaria da tabela
	 * @param campos em array $campos
	 * @param KeyField $key
	 */
    function save($campos,$key=NULL)
   	{

		if($key == NULL){
		 $key = $this->keyField;
		}
		$return = parent::__save($this,$campos,$key);
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
		if($key == NULL){
		 $key = $this->keyField;
		}
		$return = parent::__delete($this,$value,$key);
		return $return;
	}

	/**
	 * @category Listagem de um unico campo
	 * Metodo generico para listar um unico campo no banco de dados, pode ser sobrescrito.
	 * @param campo de consulta $key
	 * @param ordem da pesquisa $order
	 * @param filtro opcional $filter
	 */
	static function listSingle($conn,$key,$order ='',$filter ='',$class='') {

		if($key == NULL){
		 $key = $this->keyField;
		}
		$return =  parent::__listSingle($conn,$key,$order,$filter,$class);
		return $return;
	}

	/**
	 * @category Listagem de todos os campos da tabela
	 * Metodo generico listar todas os campos no banco de dados, pode ser sobrescrito.
	 * @param filtro opcional $filter
	 * @param ordem da pesquisa $order
	 */
	static function listAll($conn,$filter="",$order ='',$class='') {
            return parent::__listAll($conn, $filter,$order,$class);
	}

	static function partialResult($conn,$filter="",$order ='',$pgAt=0,$rpp=10,$path='',$class=''){
            return parent::partialResult($conn,$filter,$order,$pgAt,$rpp,$path,$class);
	}	

}
?>