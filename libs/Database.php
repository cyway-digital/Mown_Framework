<?php

class Database extends PDO
{
    private $debug;
	
	public function __construct($DB_TYPE, $DB_HOST, $DB_NAME, $DB_USER, $DB_PASS, $options = []) {
	    // $this->debug = (SYS_STAGE=='DEV')?true:false;
	    $this->debug = false;

	    if ($this->debug) {
            $trace = debug_backtrace();
            if (isset($trace[1])) {
                $msg = __CLASS__." instantiated by {$trace[1]['class']} :: {$trace[1]['function']} | from {$trace[1]['file']}:{$trace[1]['line']}\r\n";
//                Pushover::send($msg);
            } else {
                $msg =  "NO TRACE";
            }
            $this->logWrite($msg);
        }

		try {
			parent::__construct($DB_TYPE.':host='.$DB_HOST.';dbname='.$DB_NAME, $DB_USER, $DB_PASS, $options);
		} catch (PDOException $e) {
            $msg = 'PDO | '.$e->getMessage();
            throw new mownException($msg);
		}
	}

    public function debug($method,$arguments,$trace) {
	    $msg = "";
        if (isset($trace[1])) {
            $class = $trace[1]['class'] ?? '(empty class)';
            $msg .= strtoupper($method)." called by $class :: {$trace[1]['function']} | from {$trace[1]['file']}:{$trace[1]['line']}\r\n";
        }
	    $msg .="Args: ".print_r($arguments,true)."\r\n";
        $this->logWrite($msg);
    }

    private function logWrite($msg) {
	    return; // TEMP
        $var = fopen(LOG_FILE_DB,"a+");

        if ($var) {
            if (flock($var, LOCK_EX)) {
                fwrite($var, $msg);
                flock($var, LOCK_UN);
            }
        } else {
            Pushover::send($msg." [WARN: Cannot write to log file!]");
        }

        fclose($var);
    }
	
	/**
	 * select
	 * @param string $sql An SQL string
	 * @param array $array Parameters to bind (:)
	 * @param string $fetchType type of fetch (default fetchAll)
	 * @param mixed $fetchMode A PDO Fetch mode (default FETCH_ASSOC)
	 * @return boolean
	 */
	public function select($sql, $array = array(), $fetchType = 'fetchAll', $fetchMode = PDO::FETCH_ASSOC) {
	    if ($this->debug) $this->debug(__METHOD__,func_get_args(),debug_backtrace());
		$sth = $this->prepare("SELECT $sql");
		foreach ($array as $key => $value) {
			$sth->bindValue("$key", $value);
		}
		
		$sth->execute();
		return $sth->$fetchType($fetchMode);
	}
	
	/**
	 * insert
	 * @param string $table A name of table to insert into
	 * @param string $data An associative array
     * @return integer last insert id
	 */
	public function insert($table, $data) {
        if ($this->debug) $this->debug(__METHOD__,func_get_args(),debug_backtrace());
		ksort($data);
		
		$fieldNames = str_replace(':', '', implode(', ', array_keys($data)));
		$fieldValues = implode(', ', array_keys($data));
		$sth = $this->prepare("INSERT INTO $table ($fieldNames) VALUES ($fieldValues)");
		
		foreach ($data as $key => $value) {
			$sth->bindValue("$key", $value);
		}
		
		$sth->execute();
        return $this->lastInsertId();
		
	}
	
	/**
	 * update
	 * @param string $table A name of table to insert into
	 * @param string $data An associative array
	 * @param string $where the WHERE query part
     * @return integer true/false
	 */
	public function update($table, $data, $where) {
        if ($this->debug) $this->debug(__METHOD__,func_get_args(),debug_backtrace());
		ksort($data);
		
		$fieldDetails = NULL;
		foreach($data as $key=> $value) {
			$fieldDetails .= "`$key`=:$key,";
		}
		$fieldDetails = rtrim($fieldDetails, ',');
		
		$sth = $this->prepare("UPDATE $table SET $fieldDetails WHERE $where");

		foreach ($data as $key => $value) {
			$sth->bindValue(":$key", $value);
		}

		return $sth->execute();
	}
	
	/**
	 * delete
	 * 
	 * @param string $table
	 * @param string $where
	 * @param integer $limit (0 means no limit)
	 * @return integer Affected Rows
	 */
	public function delete($table, $where, $limit = 1) {
        if ($this->debug) $this->debug(__METHOD__,func_get_args(),debug_backtrace());
            $statement = "DELETE FROM $table WHERE $where";
            
            if ($limit !== 0) {
                 $statement .= " LIMIT $limit";
            }
		
            return $this->exec($statement);
	}


	public function freeQuery($sql,$data,$returnSelect = false) {
        $sth = $this->prepare($sql);

        foreach ($data as $key => $value) {
            $sth->bindValue("$key", $value);
        }

        $ret =  $sth->execute();

        if ($returnSelect) {
            return $sth->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return $ret;
        }

    }

} //END CLASS