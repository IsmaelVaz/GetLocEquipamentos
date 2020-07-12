<?php
/* * ****************************************************************
 * $Rev: 216 $
 * $LastChangedDate: 2017-02-02 16:21:49 -0200 (qui, 02 fev 2017) $
 * $LastChangedBy: chermont $
 * $Author: chermont $
 * * ----------------------------------------------------------------------
 */

// *********************************************************************************
// * Script                  : Conexao Generica Banco de dados
// * Desenvolvimento         : P2S Tecnologia
// * Linguagem               : PHP
// * Objetivo                : Conexao a banco de dados sem se preocupar com qual banco de dados esta usando
// *********************************************************************************


abstract class ComandosClasses {

    protected $debug = true;
    protected $indiceDebug = 0;
    protected $conexao = '';
    protected $dataBase = '';
    protected $indiceAmostraDebug = 0;
    protected $DebugStr = Array();
    protected $DebugAmostra = Array();

    // Metodos \\
    function __set($campo, $valor) {
        $this->$campo = $valor;
        return true;
    }

    /*     * ******************************************************************************************************** */

    function __get($campo) {
        return $this->$campo;
    }

    /*     * ******************************************************************************************************** */

    public function set_debug($dado) {
        if ($this->debug) {
            $this->DebugStr[$this->indiceDebug] = $dado;
            $this->indiceDebug++;
            
            //Limita o debug para os ultimos 5 mensagens
            if($this->indiceDebug>=5){
                unset($this->DebugStr[$this->indiceDebug-5]);
            }
        }
    }

    /*     * ******************************************************************************************************** */

    public function set_amostra($dado) {
        if ($this->debug) {
            $this->DebugAmostra[$this->indiceDebug - 1] = $dado;
        }
    }

    /*     * ******************************************************************************************************** */

    public function debug($mostra=1) {
        $this->mostradebug($mostra);
    }
    
    public function mostradebug($mostra=1) {
        $debug = NULL;

        if (count($this->DebugStr) > 0) {
            $debug = '<table width="100%" cellpadding="1" cellspacing="1" border="0" style="text-align:center ; border: 1px solid #000000;">
				<tr>
				<td style="background-color:#DDEFD1;text-align:center; border: 1px solid #000000"><strong>Sistema de Debug Banco de dados </strong></td>
				</tr>' . chr(13);
            foreach ($this->DebugStr as $indice => $dados) {
                $debug.='<tr>';
                $debug.="<td style=\" border: 1px solid #000000; \">$dados</td>";
                $debug.='</tr>';
                if (isset($this->DebugAmostra[$indice])) {
                    $debug.='<tr>';
                    $debug.="<td style=\"text-align:center ;  border: 1px solid #000000;background-color:#E1DDC1\"><strong>Amostra de dados do Select</strong></td>";
                    $debug.='</tr>';
                    $debug.='<tr>';
                    $debug.="<td>" . $this->DebugAmostra[$indice] . "</td>";
                    $debug.='</tr>';
                }
            }
        }
        if ($mostra) {
            echo $debug;
            exit;
        }else{
            echo $debug;
        }
    }

    /*     * ******************************************************************************************************** */

    public function totalregistros() {
        return $this->totalregistros;
    }

    /*     * ******************************************************************************************************** */

    public function proxima() {
        $this->linha++;
    }

    /*     * ******************************************************************************************************** */

    public function anterior() {
        $this->linha--;
        if ($this->linha < 0) {
            $this->linha = 0;
        }
    }

    /*     * ******************************************************************************************************** */

    public function primeira() {
        $this->linha = 0;
    }

    /*     * ******************************************************************************************************** */

    public function ultima() {
        $this->linha = $this->totalregistros - 1;
    }

    /*     * ******************************************************************************************************** */

    public function pegalinha($campo) {
        if (isset($this->resultado[$this->linha][$campo])) {
            return $this->resultado[$this->linha][$campo];
        }
    }

    /*     * ******************************************************************************************************** */

    public function totalcampos() {
        return $this->totalcampos - 1;
    }

    /*     * ******************************************************************************************************** */

    public function resultado() {
        return $this->resultado;
    }
    
    /*     * ******************************************************************************************************** */

    public function limpaDados() {
        if (!$this->debug) {
            $this->DebugAmostra = array();
            $this->DebugStr = array();
            $this->indiceDebug = 0;
            $this->indiceAmostraDebug = 0;
            if(gc_enabled()) gc_collect_cycles();
        }
    }
}

/* ################################################ */

Interface IBanco {

    public function connect();

    public function select($query);

    public function insert($query);

    public function update($query);

    public function delete($query);
}

/* ################################################ */
/* Classe Singleton + Factory */

class Conexao {

    public static $MysqlInstance;
    public static $InterbaseInstance;
    public static $SqlServerInstance;
    public static $PostGrInstance;
    public static $MysqliInstance;

    public function __construct() {
        echo __CLASS__ . '  - Nao deve ser Instanciada diretamente';
    }

    public static function UsarBanco($tipo, $host, $usuario, $senha, $banco, $porta=3306, $singConnect=true) {
        switch ($tipo) {
            case 'mysql':
                if ($singConnect == false) {
                    self::$MysqlInstance = new mysqlconect($host, $usuario, $senha, $banco, $porta);
                } else {
                    if (!isset(self::$MysqlInstance)) {
                        self::$MysqlInstance = new mysqlconect($host, $usuario, $senha, $banco, $porta);
                    }
                }
                return self::$MysqlInstance;
                break;
            case 'mysqli':
                if ($singConnect == false) {
                    self::$MysqliInstance = new mysqliconnect($host, $usuario, $senha, $banco, $porta);
                } else {
                    if (!isset(self::$MysqlInstance)) {
                        self::$MysqliInstance = new mysqliconnect($host, $usuario, $senha, $banco, $porta);
                    }
                }
                return self::$MysqliInstance;
                break;
            case 'ibase':
                if ($singConnect == false) {
                    self::$InterbaseInstance = new ibaseconect($host, $usuario, $senha, $banco, $porta);
                } elseif (!isset(self::$InterbaseInstance)) {
                    self::$InterbaseInstance = new ibaseconect($host, $usuario, $senha, $banco, $porta);
                }
                return self::$InterbaseInstance;
                break;

            case 'mssql':
                if ($singConnect == false) {
                    self::$SqlServerInstance = new mssqlconnect($host, $usuario, $senha, $banco, $porta);
                } elseif (!isset(self::$SqlServerInstance)) {

                    self::$SqlServerInstance = new mssqlconnect($host, $usuario, $senha, $banco, $porta);
                }
                return self::$SqlServerInstance;
                break;

            case 'pgsql':
                if ($singConnect == false) {
                    self::$PostGrInstance = new pgconnect($host, $usuario, $senha, $banco, $porta);
                } elseif (!isset(self::$PostGrInstance)) {

                    self::$PostGrInstance = new pgconnect($host, $usuario, $senha, $banco, $porta);
                }
                return self::$PostGrInstance;
                break;
        }
    }

}

/* ################################################ */



/* CONEXAO MYSQL */


/* ################################################ */

class mysqlconect extends ComandosClasses implements IBanco {

    protected $resultado = Array();
    protected $linha = 0;
    protected $totalcampos;
    protected $totalregistros;

    public function __construct($host, $usuario, $senha, $banco, $porta) {


        $this->__set('host', $host);
        $this->__set('usuario', $usuario);
        $this->__set('senha', $senha);
        $this->__set('banco', $banco);
        $this->__set('porta', $porta);

        $this->connect();
    }

    /*     * ******************************************************************************************************** */

    public function connect() {
        $conexao = @mysql_connect($this->__get('host') . ":" . $this->__get('porta'), $this->__get('usuario'), $this->__get('senha'));
        $bd = @mysql_select_db($this->__get('banco'), $conexao);
        $this->conexao = $conexao;
        $this->database = $this->__get('banco');
        if ((!$conexao ) or (!$bd)) {
            try {
                throw new Exception('Query Invalida');
            } catch (Exception $e) {
                $this->set_debug("Erro ao conectar  em :  " . __CLASS__ . "  -  <strong>" . mysql_error() . "</strong>");
            }
        } else {
            $this->set_debug('Conectado ao mysql em <strong>' . $this->host . '</strong> com o usuario <strong>' . $this->usuario . '</strong> e senha <strong>xxxxxxxx</strong>');
            $this->set_debug('Conectando ao banco de dados <strong>' . $this->banco . '</strong>');
        }

        return $conexao;
    }

    /*     * ******************************************************************************************************** */

    public function select($query,$count=false) {
        
        @mysql_select_db($this->__get('database'), $this->__get('conexao'));
        $this->resultado = NULL;
        $i = 0;
        $j = 0;
        $exec = @mysql_query($query, $this->conexao);
        if (!$exec) {
            try {
                throw new Exception('Query Invalida');
            } catch (Exception $e) {
                $this->set_debug("Query Invalida em " . __CLASS__ . "  -  <strong>" . mysql_error() . ' - :' . $query . "</strong>");
            }
            $this->totalregistros = 0;
            return false;
        }
        $campos = mysql_num_fields($exec);
        $this->set_debug("A tabela tem <strong>$campos</strong> campos");
        $this->set_debug("Query '<strong>$query</strong>' execudada com sucesso ");
        $this->totalcampos = $campos;


        $dados = NULL;
        $amostra = '<table width=100% border="0"> <tr>';
        for ($j = 0; $j != $campos; $j++) {
            $campo_str = mysql_fieldname($exec, $j);
            $amostra .= '<td style="background-color:#DDEFD1;text-align:center; border: 1px solid #000000;">' . ucfirst($campo_str) . '</td>' . chr(13);
        }
        $amostra.='</tr><tr>' . chr(13);
        if(!$count){
            while ($dados = mysql_fetch_array($exec)) {


                for ($j = 0; $j != $campos; $j++) {

                    $campo_str = mysql_fieldname($exec, $j);
                    $this->resultado[$i][$campo_str] = isset($this->resultado[$i][$campo_str])?$this->resultado[$i][$campo_str]:$dados[$j];;
                    $this->resultado[$i][$j] = $dados[$j];
                    if ($i <= 5) {
                        if ($j % 2 == 0) {
                            $fundo = '#F4F4F4';
                        } else {
                            $fundo = '#FFEEE6';
                        }
                        $amostra.='<td style="text-align:center; border: 1px solid #000000;">' . $dados[$j] . '</td>' . chr(13);
                    }
                }
                $i++;
                if ($i <= 5) {
                    $amostra.= '</tr><tr>' . chr(13);
                }
            }
        }
        $this->totalregistros = mysql_affected_rows();
        
        $this->primeira();
        $amostra.='</table>' . chr(13);
        $this->set_amostra($amostra);
        return true;
    }

    /*     * ******************************************************************************************************** */

    public function update($query) {
        @mysql_select_db($this->__get('database'), $this->__get('conexao'));
        if (is_array($query)) {
            $i = 1;
            foreach ($query as $instr) {
                $total = count($query);

                $exec = @mysql_query(($instr), $this->conexao);
                if (!$exec) {
                    try {
                        throw new Exception('Query Invalida');
                    } catch (Exception $e) {
                        $this->set_debug("Query Inválida em " . __CLASS__ . "  -  <strong>" . mysql_error() . "</strong>");
                    }
                    return false;
                }
                $this->set_debug("Atualizacao em array: <strong>$i/$total</strong> - <strong>" . $instr . "</strong>");
                $i++;
            }
        } else {
            $exec = @mysql_query($query, $this->conexao);
            
            if (!$exec) {
                try {
                    throw new Exception('Query Invalida');
                } catch (Exception $e) {
                    $this->set_debug("Query Invalida em " . __CLASS__ . "  -  <strong>" . mysql_error() . "</strong> - <strong>" . $query . "</strong>");
                }
                return false;
            } else {
                $this->set_debug("Atualizacao da tabela executada com sucesso : <strong>" . $query . "</strong>");
            }
        }
        return true;
    }

    /*     * ******************************************************************************************************** */

    public function delete($query) {
        @mysql_select_db($this->__get('database'), $this->__get('conexao'));
        if (is_array($query)) {
            $i = 1;
            $total = count($query);
            foreach ($query as $instr) {
                $exec = @mysql_query($instr, $this->conexao);
                if (!$exec) {
                    try {
                        throw new Exception('Query Invalida');
                    } catch (Exception $e) {
                        $this->set_debug("Query Invalida em " . __CLASS__ . "  -  <strong>" . mysql_error() . "</strong>");
                    }
                    return false;
                }
                $this->set_debug("Exclusao em array: <strong>$i/$total</strong> - <strong>" . $instr . "</strong>");
                $i++;
            }
        } else {
            $exec = @mysql_query($query, $this->conexao);
            if (!$exec) {
                try {
                    throw new Exception('Query Invalida em ".__CLASS__." ');
                } catch (Exception $e) {
                    $this->set_debug("Query Invalida em " . __CLASS__ . "  -  <strong>" . mysql_error() . "</strong>");
                }
                return false;
            } else {
                $this->set_debug("Exclusao efetuada com sucesso :<strong> $query</strong>");
            }
        }
        return true;
    }

    public function insert($query) {
        @mysql_select_db($this->__get('database'), $this->__get('conexao'));
        if (is_array($query)) {
            $i = 0;
            $total = count($query);
            foreach ($query as $instr) {
                $exec = @mysql_query(($instr), $this->conexao);
                if (!$exec) {
                    try {
                        throw new Exception('Query Invalida em ".__CLASS__." ');
                    } catch (Exception $e) {
                        $this->set_debug("Query Invalida em " . __CLASS__ . "  -  <strong>" . mysql_error() . "</strong>");
                    }
                    return false;
                }
                $id[] = mysql_insert_id();
                $this->set_debug("Insercao de dados em array , retornando id <strong>" . mysql_insert_id() . "</strong> - <strong>$instr</strong>");
            }
        } else {
            $exec = @mysql_query($query, $this->conexao);
            if (!$exec) {
                try {
                    throw new Exception('Query Invalida em ".__CLASS__." ');
                } catch (Exception $e) {
                    $this->set_debug("Query Inv&aacute;lida em " . __CLASS__ . "  -  <strong>" . mysql_error() . "</strong>" . $query);
                }
                return false;
            }
            $id = mysql_insert_id();
            $this->set_debug("Inser&ccedil;&atilde;o de dados executado com sucesso, retornando id <strong>$id</strong> - <strong>$query</strong>");
        }
        return $id;
    }

}

/* ################################################ */



/* CONEXAO INTERBASE */


/* ################################################ */

class ibaseconect extends ComandosClasses implements IBanco {

    protected $resultado = Array();
    protected $linha = 0;
    protected $totalcampos;

    public function __construct($host, $usuario, $senha, $banco, $porta) {
        $this->__set('host', $host);
        $this->__set('usuario', $usuario);
        $this->__set('senha', $senha);
        $this->__set('banco', $banco);
        $this->__set('porta', $porta);
        $this->connect();
    }

    /*     * ******************************************************************************************************** */

    public function connect() {
        $conexao = @ibase_connect($this->__get('host') . $this->__get('banco'), $this->__get('usuario'), $this->__get('senha'));
        $this->conexao = $conexao;
        $this->database = $this->__get('banco');
        if ((!$conexao)) {
            try {
                throw new Exception('Query Invalida');
            } catch (Exception $e) {
                $this->set_debug("Erro ao conectar  em :  " . __CLASS__ . "  -  <strong>" . ibase_errmsg() . "</strong>");
            }
        } else {
            $this->set_debug('Conectado ao Ibase em <strong>' . $this->host . '</strong> com o usuario <strong>' . $this->usuario . '</strong> e senha <strong>xxxxxxxx</strong>');
            $this->set_debug('Conectando ao banco de dados <strong>' . $this->banco . '</strong>');
        }

        return $conexao;
    }

    /*     * ******************************************************************************************************** */

    public function select($query) {
        $i = 0;
        $j = 0;
        $exec = @ibase_query($query);
        if (!$exec) {
            try {
                throw new Exception("Query Invalida em " . __CLASS__);
            } catch (Exception $e) {
                $this->set_debug("Query Invalida em " . __CLASS__ . "  -  <strong>" . ibase_errmsg() . "</strong>");
            }
            return false;
        }
        $campos = ibase_num_fields($exec);
        $this->totalcampos = $campos;
        $dados = NULL;
        $amostra = '<table width=100% border="0"> <tr>';
        for ($j = 0; $j != $campos; $j++) {
            $campo_str = ibase_field_info($exec, $j);
            echo "<pre>";

            $amostra .= '<td style="background-color:#DDEFD1;text-align:center; border: 1px solid #000000;">' . ucfirst(strtolower($campo_str['name'])) . '</td>' . chr(13);
        }
        $amostra.='</tr><tr>' . chr(13);
        while ($dados = ibase_fetch_row($exec)) {
            for ($j = 0; $j != $campos; $j++) {
                $camp = ibase_field_info($exec, $j);
                $this->resultado[$i][strtolower($camp["name"])] = $dados[$j];
                $this->resultado[$i][$j] = $dados[$j];
                if ($i <= 5) {
                    if ($j % 2 == 0) {
                        $fundo = '#F4F4F4';
                    } else {
                        $fundo = '#FFEEE6';
                    }
                    $amostra.='<td style="text-align:center; border: 1px solid #000000;">' . $dados[$j] . '</td>' . chr(13);
                }
            }
            $i++;
            if ($i <= 5) {
                $amostra.= '</tr><tr>' . chr(13);
            }
        }
        $this->primeira();
        $amostra.='</table>' . chr(13);
        $this->set_amostra($amostra);

        return true;
    }

    /*     * ******************************************************************************************************** */

    public function update($query) {
        if (is_array($query)) {
            $i = 1;
            foreach ($query as $instr) {
                $total = count($query);

                $exec = @ibase_query(($instr));
                if (!$exec) {
                    try {
                        throw new Exception('Query Invalida');
                    } catch (Exception $e) {
                        $this->set_debug("Query Invalida em " . __CLASS__ . "  -  <strong>" . ibase_error() . "</strong>");
                    }
                    return false;
                }
                $this->set_debug("Atualizacao em array: <strong>$i/$total</strong> - <strong>" . $instr . "</strong>");
                $i++;
            }
        } else {
            $exec = @ibase_query($query);
            if (!$exec) {
                try {
                    throw new Exception('Query Invalida');
                } catch (Exception $e) {
                    $this->set_debug("Query Invalida em " . __CLASS__ . "  -  <strong>" . ibase_error() . "</strong>");
                }
                return false;
            } else {
                $this->set_debug("Atualizacao da tabela executada com sucesso : <strong>" . $query . "</strong>");
            }
        }
        return true;
    }

    /*     * ******************************************************************************************************** */

    public function delete($query) {

        if (is_array($query)) {
            $i = 1;
            $total = count($query);
            foreach ($query as $instr) {
                $exec = @ibase_query(($instr));
                if (!$exec) {
                    try {
                        throw new Exception('Query Invalida');
                    } catch (Exception $e) {
                        $this->set_debug("Query Invalida em " . __CLASS__ . "  -  <strong>" . ibase_error() . "</strong>");
                    }
                    return false;
                }
                $this->set_debug("Exclusao em array: <strong>$i/$total</strong> - <strong>" . $instr . "</strong>");
                $i++;
            }
        } else {
            $exec = @ibase_query($query);
            if (!$exec) {
                try {
                    throw new Exception('Query Invalida em ".__CLASS__." ');
                } catch (Exception $e) {
                    $this->set_debug("Query Invalida em " . __CLASS__ . "  -  <strong>" . ibase_errmsg() . "</strong>");
                }
                return false;
            } else {
                $this->set_debug("Exclusao efetuada com sucesso :<strong> $query</strong>");
            }
        }
        return true;
    }

    /*     * ******************************************************************************************************** */

    public function insert($query) {
        $id = '';
        if (is_array($query)) {
            $i = 0;
            $total = count($query);
            foreach ($query as $instr) {
                $exec = @ibase_query(($instr));
                if (!$exec) {
                    try {
                        throw new Exception('Query Invalida em ' . __CLASS__);
                    } catch (Exception $e) {
                        $this->set_debug("Query Invalida em " . __CLASS__ . "  -  <strong>" . ibase_errmsg() . " : $instr</strong>");
                    }
                    return false;
                }

                $this->set_debug("Insercao de dados em array - <strong>$instr</strong>");
            }
        } else {
            $exec = @ibase_query($query);
            if (!$exec) {
                try {
                    throw new Exception('Query Invalida em ".__CLASS__." ');
                } catch (Exception $e) {
                    $this->set_debug("Query Invalida em " . __CLASS__ . "  -  <strong>" . ibase_errmsg() . " : $sql</strong>");
                }
                return false;
            }

            $this->set_debug("Insercao de dados executado com sucesso  - <strong>$query</strong>");
        }
        return $id;
    }

}

/* ################################################ */



/* CONEXAO SQL SERVER */


/* ################################################ */

class mssqlconnect extends ComandosClasses implements IBanco {

    protected $resultado = Array();
    protected $linha = 0;
    protected $totalcampos;

    public function __construct($host, $usuario, $senha, $banco, $porta) {

        $this->__set('host', $host);
        $this->__set('usuario', $usuario);
        $this->__set('senha', $senha);
        $this->__set('banco', $banco);
        $this->__set('porta', $porta);
        $this->connect();
    }

    /*     * ******************************************************************************************************** */

    public function connect() {

        $conexao = mssql_connect($this->__get('host'), $this->__get('usuario'), $this->__get('senha'));

        $bd = mssql_select_db($this->__get('banco'));
        $this->conexao = $conexao;
        $this->database = $this->__get('banco');
        if ((!$conexao ) or (!$bd)) {
            try {
                throw new Exception('Query Invalida');
            } catch (Exception $e) {
                $this->set_debug("Erro ao conectar  em :  " . __CLASS__ . "  -  <strong>Erro ao Conectar ao Banco de dados</strong>");
            }
        } else {
            $this->set_debug('Conectado ao mysql em <strong>' . $this->host . '</strong> com o usuario <strong>' . $this->usuario . '</strong> e senha <strong>xxxxxxxx</strong>');
            $this->set_debug('Conectando ao banco de dados <strong>' . $this->banco . '</strong>');
        }

        return $conexao;
    }

    /*     * ******************************************************************************************************** */

    public function select($query) {
        $this->resultado = NULL;
        $i = 0;
        $j = 0;
        $exec = @mssql_query($query);

        if (!$exec) {
            try {
                throw new Exception('Query Invalida');
            } catch (Exception $e) {
                $this->set_debug("Query Invalida em " . __CLASS__ . "  -  <strong>: " . $query . "</strong>");
            }

            return false;
        }
        $campos = mssql_num_fields($exec);
        $this->set_debug("A tabela tem <strong>$campos</strong> campos");
        $this->set_debug("Query '<strong>$query</strong>' execudada com sucesso ");
        $this->totalcampos = $campos;


        $dados = NULL;
        $amostra = '<table width=100% border="0"> <tr>';
        for ($j = 0; $j != $campos; $j++) {
            $campo_str = mssql_field_name($exec, $j);
            $amostra .= '<td style="background-color:#DDEFD1;text-align:center; border: 1px solid #000000;">' . ucfirst($campo_str) . '</td>' . chr(13);
        }
        $amostra.='</tr><tr>' . chr(13);
        while ($dados = mssql_fetch_array($exec)) {


            for ($j = 0; $j != $campos; $j++) {

                $campo_str = mssql_field_name($exec, $j);
                $this->resultado[$i][$campo_str] = $dados[$j];
                $this->resultado[$i][$j] = $dados[$j];
                if ($i <= 5) {
                    if ($j % 2 == 0) {
                        $fundo = '#F4F4F4';
                    } else {
                        $fundo = '#FFEEE6';
                    }
                    $amostra.='<td style="text-align:center; border: 1px solid #000000;">' . $dados[$j] . '</td>' . chr(13);
                }
            }
            $i++;
            if ($i <= 5) {
                $amostra.= '</tr><tr>' . chr(13);
            }
        }
        $this->primeira();
        $amostra.='</table>' . chr(13);
        $this->set_amostra($amostra);
        return true;
    }

    /*     * ******************************************************************************************************** */

    public function update($query) {
        if (is_array($query)) {
            $i = 1;
            foreach ($query as $instr) {
                $total = count($query);

                $exec = @mssql_query(($instr));
                if (!$exec) {
                    try {
                        throw new Exception('Query Invalida');
                    } catch (Exception $e) {
                        $this->set_debug("Query Invalida em " . __CLASS__ . "  -  <strong>: " . $query . "</strong>");
                    }
                    return false;
                }
                $this->set_debug("Atualizacao em array: <strong>$i/$total</strong> - <strong>" . $instr . "</strong>");
                $i++;
            }
        } else {
            $exec = @mssql_query($query);
            if (!$exec) {
                try {
                    throw new Exception('Query Invalida');
                } catch (Exception $e) {
                    $this->set_debug("Query Invalida em " . __CLASS__ . "  -  <strong>: " . $query . "</strong>");
                }
                return false;
            } else {
                $this->set_debug("Atualizacao da tabela executada com sucesso : <strong>" . $query . "</strong>");
            }
        }
        return true;
    }

    /*     * ******************************************************************************************************** */

    public function delete($query) {

        if (is_array($query)) {
            $i = 1;
            $total = count($query);
            foreach ($query as $instr) {
                $exec = @mssql_query(($instr));
                if (!$exec) {
                    try {
                        throw new Exception('Query Invalida');
                    } catch (Exception $e) {
                        $this->set_debug("Query Invalida em " . __CLASS__ . "  -  <strong>: " . $query . "</strong>");
                    }
                    return false;
                }
                $this->set_debug("Exclusao em array: <strong>$i/$total</strong> - <strong>" . $instr . "</strong>");
                $i++;
            }
        } else {
            $exec = @mssql_query($query);
            if (!$exec) {
                try {
                    throw new Exception('Query Invalida em ".__CLASS__." ');
                } catch (Exception $e) {
                    $this->set_debug("Query Invalida em " . __CLASS__ . "  -  <strong>: " . $query . "</strong>");
                }
                return false;
            } else {
                $this->set_debug("Exclusao efetuada com sucesso :<strong> $query</strong>");
            }
        }
        return true;
    }

    public function insert($query) {
        $id = '';
        if (is_array($query)) {
            $i = 0;
            $total = count($query);
            foreach ($query as $instr) {
                $exec = @mssql_query(($instr));
                if (!$exec) {
                    try {
                        throw new Exception('Query Invalida em ".__CLASS__." ');
                    } catch (Exception $e) {
                        $this->set_debug("Query Invalida em " . __CLASS__ . "  -  <strong>: " . $query . "</strong>");
                    }
                    return false;
                }
                $this->set_debug("Insercao de dados em array </strong> - <strong>$instr</strong>");
            }
        } else {
            $exec = @mssql_query($query);
            if (!$exec) {
                try {
                    throw new Exception('Query Invalida em ".__CLASS__." ');
                } catch (Exception $e) {
                    $this->set_debug("Query Invalida em " . __CLASS__ . "  -  <strong>: " . $query . "</strong>");
                }
                return false;
            }
            $this->set_debug("Insercao de dados executado com sucesso,  - <strong>$query</strong>");
        }
        return true;
    }

}

/* ################################################ */



/* CONEXAO POSTGREE */


/* ################################################ */

class pgconnect extends ComandosClasses implements IBanco {

    protected $resultado = Array();
    protected $linha = 0;
    protected $totalcampos;

    public function __construct($host, $usuario, $senha, $banco, $porta) {
        $this->__set('host', $host);
        $this->__set('usuario', $usuario);
        $this->__set('senha', $senha);
        $this->__set('banco', $banco);
        $this->__set('porta', $porta);
        $this->connect();
    }

    /*     * ******************************************************************************************************** */

    public function connect() {
        $conexao = @pg_connect("host=" . $this->__get('host') . " port=" . $this->__get('porta') . " dbname=" . $this->__get('banco') . " user=" . $this->__get('usuario') . " password=" . $this->__get('senha'));

        if ((!$conexao)) {
            try {
                throw new Exception('Query Invalida');
            } catch (Exception $e) {
                $this->set_debug("Erro ao conectar  em :  " . __CLASS__ . "  -  <strong>" . pg_result_error() . "</strong>");
            }
        } else {
            $this->set_debug('Conectado ao PostGree em <strong>' . $this->host . '</strong> com o usuario <strong>' . $this->usuario . '</strong> e senha <strong>xxxxxxxx</strong>');
            $this->set_debug('Conectando ao banco de dados <strong>' . $this->banco . '</strong>');
        }

        return $conexao;
    }

    /*     * ******************************************************************************************************** */

    public function select($query) {
        $i = 0;
        $j = 0;
        $exec = @pg_query($query);
        if (!$exec) {
            try {
                throw new Exception("Query Invalida em " . __CLASS__);
            } catch (Exception $e) {
                $this->set_debug("Query Invalida em " . __CLASS__ . "  -  <strong>" . pg_result_error() . "</strong>");
            }
            return false;
        }
        $campos = pg_num_fields($exec);
        $this->totalcampos = $campos;
        $dados = NULL;
        $amostra = '<table width=100% border="0"> <tr>';
        for ($j = 0; $j != $campos; $j++) {
            $campo_str = pg_field_name($exec, $j);

            echo "<pre>";
            $amostra .= '<td style="background-color:#DDEFD1;text-align:center; border: 1px solid #000000;">' . ucfirst(strtolower($campo_str)) . '</td>' . chr(13);
        }
        $amostra.='</tr><tr>' . chr(13);
        while ($dados = pg_fetch_row($exec)) {
            for ($j = 0; $j != $campos; $j++) {
                $camp = pg_field_name($exec, $j);
                $this->resultado[$i][strtolower($camp)] = $dados[$j];
                $this->resultado[$i][$j] = $dados[$j];
                if ($i <= 5) {
                    if ($j % 2 == 0) {
                        $fundo = '#F4F4F4';
                    } else {
                        $fundo = '#FFEEE6';
                    }
                    $amostra.='<td style="text-align:center; border: 1px solid #000000;">' . $dados[$j] . '</td>' . chr(13);
                }
            }
            $i++;
            if ($i <= 5) {
                $amostra.= '</tr><tr>' . chr(13);
            }
        }
        $this->primeira();
        $amostra.='</table>' . chr(13);
        $this->set_amostra($amostra);

        return true;
    }

    /*     * ******************************************************************************************************** */

    public function update($query) {
        if (is_array($query)) {
            $i = 1;
            foreach ($query as $instr) {
                $total = count($query);

                $exec = @pg_query(($instr));
                if (!$exec) {
                    try {
                        throw new Exception('Query Invalida');
                    } catch (Exception $e) {
                        $this->set_debug("Query Invalida em " . __CLASS__ . "  -  <strong>" . pg_result_error() . "</strong>");
                    }
                    return false;
                }
                $this->set_debug("Atualizacao em array: <strong>$i/$total</strong> - <strong>" . $instr . "</strong>");
                $i++;
            }
        } else {
            $exec = @pg_query($query);
            if (!$exec) {
                try {
                    throw new Exception('Query Invalida');
                } catch (Exception $e) {
                    $this->set_debug("Query Invalida em " . __CLASS__ . "  -  <strong>" . pg_result_error() . "</strong>");
                }
                return false;
            } else {
                $this->set_debug("Atualizacao da tabela executada com sucesso : <strong>" . $query . "</strong>");
            }
        }
        return true;
    }

    /*     * ******************************************************************************************************** */

    public function delete($query) {

        if (is_array($query)) {
            $i = 1;
            $total = count($query);
            foreach ($query as $instr) {
                $exec = @pg_query(($instr));
                if (!$exec) {
                    try {
                        throw new Exception('Query Invalida');
                    } catch (Exception $e) {
                        $this->set_debug("Query Invalida em " . __CLASS__ . "  -  <strong>" . pg_result_error() . "</strong>");
                    }
                    return false;
                }
                $this->set_debug("Exclusao em array: <strong>$i/$total</strong> - <strong>" . $instr . "</strong>");
                $i++;
            }
        } else {
            $exec = @pg_query($query);
            if (!$exec) {
                try {
                    throw new Exception('Query Invalida em ".__CLASS__." ');
                } catch (Exception $e) {
                    $this->set_debug("Query Invalida em " . __CLASS__ . "  -  <strong>" . pg_result_error() . "</strong>");
                }
                return false;
            } else {
                $this->set_debug("Exclusao efetuada com sucesso :<strong> $query</strong>");
            }
        }
        return true;
    }

    /*     * ******************************************************************************************************** */

    public function insert($query) {
        $id = '';
        if (is_array($query)) {
            $i = 0;
            $total = count($query);
            foreach ($query as $instr) {
                $exec = @pg_query(($instr));
                if (!$exec) {
                    try {
                        throw new Exception('Query Invalida em ' . __CLASS__);
                    } catch (Exception $e) {
                        $this->set_debug("Query Invalida em " . __CLASS__ . "  -  <strong>" . pg_result_error() . "</strong>");
                    }
                    return false;
                }

                $this->set_debug("Insercao de dados em array - <strong>$instr</strong>");
            }
        } else {
            $exec = @pg_query($query);
            if (!$exec) {
                try {
                    throw new Exception('Query Invalida em ".__CLASS__." ');
                } catch (Exception $e) {
                    $this->set_debug("Query Invalida em " . __CLASS__ . "  -  <strong>" . pg_result_error() . "</strong>");
                }
                return false;
            }

            $this->set_debug("Insercao de dados executado com sucesso  - <strong>$query</strong>");
        }
        return $id;
    }

}


/* CONEXAO MYSQLi */


/* ################################################ */

class mysqliconnect extends ComandosClasses implements IBanco {

    protected $resultado = Array();
    protected $linha = 0;
    protected $totalcampos;
    protected $totalregistros;

    public function __construct($host, $usuario, $senha, $banco, $porta) {


        $this->__set('host', $host);
        $this->__set('usuario', $usuario);
        $this->__set('senha', $senha);
        $this->__set('banco', $banco);
        $this->__set('porta', $porta);

        $this->connect();
    }

    /*     * ******************************************************************************************************** */

    public function connect() {
        $conexao = new mysqli($this->__get('host') , $this->__get('usuario'), $this->__get('senha'),$this->__get('banco'), $this->__get('porta'));
        $this->conexao = $conexao;
        $this->database = $this->__get('banco');
        if ((!$conexao )) {
            try {
                throw new Exception('Query Invalida');
            } catch (Exception $e) {
                $this->set_debug("Erro ao conectar  em :  " . __CLASS__ . "  -  <strong>" . $conexao->error . "</strong>");
            }
        } else {
            $this->set_debug('Conectado ao mysql em <strong>' . $this->host . '</strong> com o usuario <strong>' . $this->usuario . '</strong> e senha <strong>xxxxxxxx</strong>');
            $this->set_debug('Conectando ao banco de dados <strong>' . $this->banco . '</strong>');
        }

        return $conexao;
    }

    /*     * ******************************************************************************************************** */

    public function select($query,$count=false) {
        
        $this->resultado = NULL;
        $i = 0;
        $j = 0;
        $result = $this->conexao->query($query);
        if (!$result) {
            try {
                throw new Exception('Query Invalida');
            } catch (Exception $e) {
                $this->set_debug("Query Invalida em " . __CLASS__ . "  -  <strong>" . $this->conexao->error . ' - :' . $query . "</strong>");
            }
            $this->totalregistros = 0;
            return false;
        }
        $campos = mysqli_field_count($this->conexao);
        $this->set_debug("A tabela tem <strong>$campos</strong> campos");
        $this->set_debug("Query '<strong>$query</strong>' execudada com sucesso ");
        $this->totalcampos = $campos;


        $dados = NULL;
        $properties = array();
        $amostra = '<table width=100% border="0"> <tr>';
        while ($property = mysqli_fetch_field($result)) {
            $properties[]=$property->name;
            $campo_str = $property->name;
            $amostra .= '<td style="background-color:#DDEFD1;text-align:center; border: 1px solid #000000;">' . ucfirst($campo_str) . '</td>' . chr(13);
        }
        $amostra.='</tr><tr>' . chr(13);
        if(!$count){
            while ($dados = $result->fetch_array(MYSQLI_NUM)) {


                for ($j = 0; $j != $campos; $j++) {

                    $campo_str = $properties[$j];
                    $this->resultado[$i][$campo_str] = isset($this->resultado[$i][$campo_str])?$this->resultado[$i][$campo_str]:$dados[$j];
                    $this->resultado[$i][$j] = $dados[$j];
                    if ($i <= 5) {
                        if ($j % 2 == 0) {
                            $fundo = '#F4F4F4';
                        } else {
                            $fundo = '#FFEEE6';
                        }
                        $amostra.='<td style="text-align:center; border: 1px solid #000000;">' . $dados[$j] . '</td>' . chr(13);
                    }
                }
                $i++;
                if ($i <= 5) {
                    $amostra.= '</tr><tr>' . chr(13);
                }
            }
        }
        $this->totalregistros = $result->num_rows;
        
        $this->primeira();
        $amostra.='</table>' . chr(13);
        $this->set_amostra($amostra);
        return true;
    }

    /*     * ******************************************************************************************************** */

    public function update($query) {
        if (is_array($query)) {
            $i = 1;
            foreach ($query as $instr) {
                $total = count($query);

                $exec = $this->conexao->query($instr);
                if (!$exec) {
                    try {
                        throw new Exception('Query Invalida');
                    } catch (Exception $e) {
                        $this->set_debug("Query Inválida em " . __CLASS__ . "  -  <strong>" . $this->conexao->error . "</strong>");
                    }
                    return false;
                }
                $this->set_debug("Atualizacao em array: <strong>$i/$total</strong> - <strong>" . $instr . "</strong>");
                $i++;
            }
        } else {
            $exec = $this->conexao->query($query);
            
            if (!$exec) {
                try {
                    throw new Exception('Query Invalida');
                } catch (Exception $e) {
                    $this->set_debug("Query Invalida em " . __CLASS__ . "  -  <strong>" . $this->conexao->error . "</strong> - <strong>" . $query . "</strong>");
                }
                return false;
            } else {
                $this->set_debug("Atualizacao da tabela executada com sucesso : <strong>" . $query . "</strong>");
            }
        }
        return true;
    }

    /*     * ******************************************************************************************************** */

    public function delete($query) {
        if (is_array($query)) {
            $i = 1;
            $total = count($query);
            foreach ($query as $instr) {
                $exec = $this->conexao->query($instr);
                if (!$exec) {
                    try {
                        throw new Exception('Query Invalida');
                    } catch (Exception $e) {
                        $this->set_debug("Query Invalida em " . __CLASS__ . "  -  <strong>" . $this->conexao->error . "</strong>");
                    }
                    return false;
                }
                $this->set_debug("Exclusao em array: <strong>$i/$total</strong> - <strong>" . $instr . "</strong>");
                $i++;
            }
        } else {
            $exec = $this->conexao->query($query);
            if (!$exec) {
                try {
                    throw new Exception("Query Invalida em ".__CLASS__." ");
                } catch (Exception $e) {
                    $this->set_debug("Query Invalida em " . __CLASS__ . "  -  <strong>" . $this->conexao->error . "</strong>");
                }
                return false;
            } else {
                $this->set_debug("Exclusao efetuada com sucesso :<strong> $query</strong>");
            }
        }
        return true;
    }

    public function insert($query) {
        if (is_array($query)) {
            $i = 0;
            $total = count($query);
            foreach ($query as $instr) {
                $exec = $this->conexao->query($instr);
                if (!$exec) {
                    try {
                        throw new Exception("Query Invalida em ".__CLASS__." ");
                    } catch (Exception $e) {
                        $this->set_debug("Query Invalida em " . __CLASS__ . "  -  <strong>" . $this->conexao->error . "</strong>");
                    }
                    return false;
                }
                $id[] = $this->conexao->insert_id;
                $this->set_debug("Insercao de dados em array , retornando id <strong>" . mysqli_insert_id() . "</strong> - <strong>$instr</strong>");
            }
        } else {
            $exec = $this->conexao->query($query);
            if (!$exec) {
                try {
                    throw new Exception("Query Invalida em ".__CLASS__." ");
                } catch (Exception $e) {
                    $this->set_debug("Query Inv&aacute;lida em " . __CLASS__ . "  -  <strong>" . $this->conexao->error . "</strong>" . $query);
                }
                return false;
            }
            $id = $this->conexao->insert_id;
            $this->set_debug("Inser&ccedil;&atilde;o de dados executado com sucesso, retornando id <strong>$id</strong> - <strong>$query</strong>");
        }
        return $id;
    }

}

/* ################################################ */
?>
