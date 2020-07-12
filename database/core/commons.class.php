<?php
/* * ****************************************************************
 * $Rev: 162 $
 * $LastChangedDate: 2014-06-16 19:19:51 -0300 (seg, 16 jun 2014) $
 * $LastChangedBy: chermont $
 * $Author: chermont $
 * * ----------------------------------------------------------------------
 */

/* preg_match("/<head>((?:(?!<\/head>).)*)<\/head>/is", $file, $return);
 */

class Commons {

    private $dateFormatDatabase = 'Y-m-d';
    private $dateTimeFormatDatabase = 'Y-m-d h:i:s';
    private $dateFormatView = 'd/m/Y';
    private $dateTimeFormatView = 'd/m/Y h:i:s';
    protected $conn; // Conection instance

    function __get($campo) {
        return $this->$campo;
    }

    function __set($campo, $valor) {
        $this->$campo = $valor;
        return true;
    }

    function populateObject($class, $id, $key) {
        $this->conn->select("SELECT * FROM $class WHERE $key = '$id'");
        foreach (get_class_vars($class) as $prop => $value) {
            if ($this->conn->pegalinha($prop) != "") {
                $this->$prop = $this->convert2View($prop, $this->conn->pegalinha($prop));
            }
        }
    }

    function __save($class, $campos, $key) {
        $className = get_class($class);
        foreach (get_class_vars($className) as $prop => $value) {
            if (isset($campos[$prop]) && $prop != $key) {
                $camps[] = $prop;
                $val[] = $this->convert2Save($prop, $campos[$prop]);
            }
        }

        if ($this->$key == NULL) {
            /* AUTO INCREMENT */
            $sql = "SELECT  MAX($key) as LastId FROM $className";
            $this->conn->select($sql);
            $LastId = $this->conn->pegalinha('LastId') == NULL ? '0' : $this->conn->pegalinha('LastId');
            $LastId++;
            $camps[] = $key;
            $val[] = $LastId;
        }
        /* AUTO INCREMENT */
        /* Return false in case you send only fields that dont't  have in database */
        if (count($val) <= 0) {
            return false;
        }
        /* End Return false */
        $str_campos = '';
        $str_valores = '';
        for ($i = 0; $i != count($camps); $i++) {

            if (preg_match('/date_/i', $camps[$i]) > 0 && empty($val[$i])) {
                $str_campos .= "" . $camps[$i] . ",";
                $str_valores.= "NULL,";
            } elseif (preg_match('/dtime_/i', $camps[$i]) > 0 && empty($val[$i])) {
                $str_campos .= "" . $camps[$i] . ",";
                $str_valores.= "NULL,";
            } elseif (preg_match('/pass_/i', $camps[$i]) > 0 && empty($val[$i])) {
                $str_campos .= "";
                $str_valores.= "";
            } elseif (preg_match('/money_/i', $camps[$i]) > 0 && empty($val[$i])) {
                $str_campos .= "" . $camps[$i] . ",";
                $str_valores.= "NULL,";
            } elseif (preg_match('/double_/i', $camps[$i]) > 0 && empty($val[$i])) {
                $str_campos .= "" . $camps[$i] . ",";
                $str_valores.= "NULL,";
            } else {
                $str_campos .= "" . $camps[$i] . ",";
                $str_valores.= "'" . (addslashes($val[$i])) . "',";
            }
        }
        $str_campos = substr($str_campos, 0, strlen($str_campos) - 1);
        $str_valores = substr($str_valores, 0, strlen($str_valores) - 1);
        if ($this->$key == NULL) {
            $sql = "INSERT INTO $className($str_campos) VALUES ($str_valores)";
            if($className!='per_sessao')
                registerLog($sql, "sql_");
            return $this->conn->insert($sql);
        } else {
            $sql = "UPDATE `$className` SET ";
            for ($i = 0; $i != count($camps); $i++) {
                if (preg_match('/date_/i', $camps[$i]) > 0 && empty($val[$i])) {
                    $sql .= $camps[$i] . " = NULL,";
                } elseif (preg_match('/dtime_/i', $camps[$i]) > 0 && empty($val[$i])) {
                    $sql .= $camps[$i] . " = NULL,";
                } elseif (preg_match('/pass_/i', $camps[$i]) > 0 && empty($val[$i])) {
                    $sql .= "";
                } elseif (preg_match('/money_/i', $camps[$i]) > 0 && empty($val[$i])) {
                    $sql .= $camps[$i] . " = NULL,";
                } elseif (preg_match('/double_/i', $camps[$i]) > 0 && empty($val[$i])) {
                    $sql .= $camps[$i] . " = NULL,";
                } else {
                    if ($val[$i]==='') {
                        $sql .= $camps[$i] . " = NULL,";
                    } else {
                        $sql .= $camps[$i] . " = '" . (addslashes($val[$i])) . "',";
                    }
                }
            }
            $sql = substr($sql, 0, strlen($sql) - 1);
            $sql.= " WHERE $key = " . $this->$key;
            if($className!='per_sessao')
                registerLog($sql, "sql_");
            return $this->conn->update($sql);
        }
    }

    function __delete($class, $value, $key) {
        $className = get_class($class);
        $sql = "DELETE FROM $className WHERE $key = '$value'";
        registerLog($sql, "sql_");
        return $this->conn->delete($sql);
    }

    /* Date Conversion do Show on Screen */

    function convertDateView($date, $format, $time=null) {

        $replaces = preg_split("/[.\/ : -]/", $format);
        $values = preg_split("/[.\/ : -]/", $date);
        if ($time == null) {
            return str_replace($replaces, $values, $this->dateFormatView);
        } else {
            return str_replace($replaces, $values, $this->dateTimeFormatView);
        }
    }

    /* Date Conversion do Insert or Update on DataBase */

    function convertDateDataBase($date, $format, $time=null) {
        $replaces = preg_split("/[\/ :]/", $format);
        $values = preg_split("/[\/ :]/", $date);
        if ($time == null) {
            return str_replace($replaces, $values, $this->dateFormatDatabase);
        } else {
            return str_replace($replaces, $values, $this->dateTimeFormatDatabase);
        }
    }

    /* Money Format Conversion do Show on Screen */

    function convertMoneyView($float) {
        $Money = number_format($float, 2, ',', '.');
        return $Money;
    }

    /* Double Format Conversion do Show on Screen */

    function convertDoubleView($float) {
        $Double = str_replace('.', ',', $float);
        return $Double;
    }

    /* Money Format do Insert or Update on DataBase */

    function convertMoneyDataBase($float) {
        $Money = str_replace('.', '', $float);
        $Money = str_replace(',', '.', $Money);
        return $Money;
    }

    /* Double Format do Insert or Update on DataBase */

    function convertDoubleDataBase($float) {
        $Double = str_replace('.', '', $float);
        $Double = str_replace(',', '.', $Double);
        return $Double;
    }

    static function __listAll($conn, $filter="", $order="", $className=NULL) {
        if (isset($filter) && $filter != "") {
            $filter = " WHERE " . $filter;
        }
        if (isset($order) && $order != "") {
            if (substr_count(strtoupper($order), ' ASC') > 0 || substr_count(strtoupper($order), ' DESC') > 0) {
                $order = " ORDER BY " . $order;
            } else {
                $order = " ORDER BY " . $order . " ASC ";
            }
        }

        $conn->select("Show fields from $className");
        for ($i = 0; $i != $conn->totalregistros(); $i++) {
            $fields[] = $conn->pegalinha(0);
            $conn->proxima();
        }

        $conn->select("Select * from $className " . $filter . " " . $order);
        $ob = new $className($conn);
        for ($i = 0, $arrLista = NULL; $i != $conn->totalregistros(); $i++) {
            $arrLista[$i] = new stdClass();
            foreach ($fields as $campos) {
                if(!isset($arrLista[$i]->$campos)){
                    $arrLista[$i]->$campos = $ob->convert2View($campos, $conn->pegalinha($campos));
//                    parr("$i - ".$campos.": ".date("d/m/Y H:i:s")." - ".convert(memory_get_peak_usage()));
                }
            }
            $conn->proxima();
        }
        $conn->limpaDados();
        if (version_compare(phpversion(), '5.3.0', '>')) {
            //Garbage Collector
            if(gc_enabled()) gc_collect_cycles();
        }
        return $arrLista;
    }

    function checkRequireds($campos, $requireds) {
        foreach ($campos as $ind => $valores) {
            if (in_array($ind, $requireds)) {
                if (empty($valores)) {
                    return $ind;
                    break;
                }
            }
        }
    }

    static function __listSingle($conn, $key, $order ='', $filter="", $className=NULL) {
        if (isset($filter) && $filter != "") {
            $filter = " WHERE " . $filter;
        }
        if (isset($order) && $order != "") {
            if (substr_count(strtoupper($order), ' ASC') > 0 || substr_count(strtoupper($order), ' DESC') > 0) {
                $order = " ORDER BY " . $order;
            } else {
                $order = " ORDER BY " . $order . " ASC ";
            }
        }
        $conn->select("SELECT $key FROM $className $filter $order ");
        for ($i = 0, $ArrLista = NULL; $i != $conn->totalregistros(); $i++) {
            $ArrLista[] = $conn->pegalinha($key);
            $conn->proxima();
        }
        return $ArrLista;
    }

    function dateDiff($dateIni, $dateEnd) {
        $di1 = strtotime($dateIni);
        $di = mktime(0, 0, 0, date('m', $di1), date('d', $di1), date('Y', $di1));
        $df1 = strtotime($dateEnd);
        $df = mktime(0, 0, 0, date('m', $df1), date('d', $df1), date('Y', $df1));
        $dias_totais = floor(($df - $di) / 86400);
        return $dias_totais + 1;
    }

    function convert2View($prop, $value) {
        if (preg_match('/date_/i', $prop) > 0) {
            $string = ($this->convertDateView($value, $this->dateFormatDatabase) == '//') ? '' : $this->convertDateView($value, $this->dateFormatDatabase);
        } elseif (preg_match('/dtime_/i', $prop) > 0) {
            $string = ($this->convertDateView($value, $this->dateTimeFormatDatabase, 'time') == '// ::') ? '' : $this->convertDateView($value, $this->dateTimeFormatDatabase, 'time');
        } elseif (preg_match('/money_/i', $prop) > 0) {
            $string = $this->convertMoneyView($value);
        } elseif (preg_match('/perc_/i', $prop) > 0) {
            $string = $this->convertMoneyView($value);
        } elseif (preg_match('/double_/', $prop) > 0) {
            $string = $this->convertDoubleView($value);
        } elseif (preg_match('/qtd_/i', $prop) > 0) {
            $string = $this->convertDoubleView($value);
        } else {
            $string = $value;
        }
        return utf8_encode($string);
    }

    function convert2Save($prop, $value) {
        if (preg_match('/date_/i', $prop) > 0) {
            $string = ($this->convertDateDataBase($value, $this->dateFormatView) == '--') ? NULL : $this->convertDateDataBase($value, $this->dateFormatView);
        } elseif (preg_match('/dtime_/i', $prop) > 0) {
            $string = $this->convertDateDataBase($value, $this->dateTimeFormatView, 'time');
        } elseif (preg_match('/money_/i', $prop) > 0) {
            $string = $this->convertMoneyDataBase($value);
        } elseif (preg_match('/perc_/i', $prop) > 0) {
            $string = $this->convertMoneyDataBase($value);
        } elseif (preg_match('/double_/i', $prop) > 0) {
            $string = $this->convertDoubleDataBase($value);
        } elseif (preg_match('/qtd_/i', $prop) > 0) {
            $string = $this->convertDoubleDataBase($value);
        } elseif (preg_match('/pass_/i', $prop) > 0) {
            if (isset($value) && $value != "")
                $string = md5($value);
            else
                $string = $value;
        } else {
            $string = $value;
        }
        return $this->__utf8_decode($string);
//        return utf8_decode($string);
    }

    static function __utf8_decode($string) {
        //$string = $string."á";
        $tmp = $string;
        $count = 0;
        while (mb_detect_encoding($tmp) == "UTF-8" && $count<10) {
            $tmp = utf8_decode($tmp);
            $count++;
        }

        for ($i = 0; $i < $count - 1; $i++) {
            $string = utf8_decode($string);
        }
        if (substr_count($string, '°')>0 || substr_count($string, 'º')>0 || substr_count($string, 'ª')>0 || substr_count($string, '³')>0 || substr_count($string, 'ú')>0) {
            $string = utf8_decode($string);
        }
        return $string;
    }

    static function mergeObject($objs, &$recebedor) {
        if (count($objs) > 0) {
            foreach ($objs as $ob) {

                $props = get_class_vars(get_class($ob));
                foreach ($props as $ind => $val) {
                    $recebedor->$ind = $ob->$ind;
                }
            }
        }
    }

    static function partialResult($conn, $filter="", $order ='', $pgAt=1, $rpp=10, $path, $className) {

        if (isset($filter) && $filter != "") {
            $filter = " WHERE " . $filter;
        }
        if (isset($order) && $order != "") {
            $order = " ORDER BY " . $order;
        }

        $conn->select("Show fields from $className");
        for ($i = 0; $i != $conn->totalregistros(); $i++) {
            $fields[] = $conn->pegalinha(0);
            $conn->proxima();
        }
        $sql = "Select * from $className $filter $order ";
        $conn->select($sql);
        // $conn->select("Select * from $className ".$filter." ".$order);
        $totalregistros = $conn->totalregistros();
        $caminho = $path;
        $rpp = $rpp;
        $atual = ($pgAt == 0) ? 1 : $pgAt;
        $proxima = $atual + 1;
        // Calcula o total de paginas
        $totalpaginas = ceil($totalregistros / $rpp);
        // Inicio do Limit
        $inicio = ($atual <= 1) ? 0 : ((($atual - 1) * $rpp));
        // Quantos registros deve pegar
        $fim = ($rpp);
        // Trata a query colocando escapes seguros
        $preparado = ($sql . " LIMIT $inicio,$fim ");
        //Associa o sql na propriedade
        $sqltratada = $preparado;
        // Executa a query
        $conn->select($sqltratada);


        $arrLista = NULL;
        // Se for vazio retorna false
        if ($conn->totalregistros() <= 0) {
            $dataSource = false;
        } else {
            $ob = new $className($conn);
            for ($i = 0; $i != $conn->totalregistros(); $i++) {
                $arrLista[$i] = new stdClass();
                foreach ($fields as $campos) {
                    if(!isset($arrLista[$i]->$campos)){
                        $arrLista[$i]->$campos = $ob->convert2View($campos, $conn->pegalinha($campos));
                    }
                }
                $conn->proxima();
            }
        }
        //Verifico que o caminho ja tem querystring
        // Se tiver eu coloco o concatenador &, senao coloco o ?
        $conc = (preg_match('/[?]/i', $caminho)) ? '&' : '?';

        //Gero a div de navecacao entre os dados
        $pre = ($atual <= 1) ? ' ' : '<a href="' . $caminho . $conc . 'pagina=' . ($atual - 1) . '"><<</a>';
        $pos = ($atual >= $totalpaginas) ? '  ' : '<a href="' . $caminho . $conc . 'pagina=' . $proxima . '">>></a>';
        $informacao = "P&aacute;gina " . ($atual) . " de " . $totalpaginas;
        $navegacao = "<div id=\"navegacao\">$pre $informacao $pos</div>"; // return @void
        $results = array();
        $results[0] = $navegacao;
        $results[1] = $arrLista;
        $results["totalregistros"] = $totalregistros;
        return $results;
    }

}

?>