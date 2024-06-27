<?php

class database extends mysqli
{
   var $dbHost, $dbUser, $dbPassword, $dbSchema;

   public function __construct($XMLFilePath, $connectionID)
   {
       $this->_getDBParameters($XMLFilePath, $connectionID);
       parent::__construct($this->dbHost, $this->dbUser, $this->dbPassword, $this->dbSchema);

       if (mysqli_connect_errno())
       {
           throw new Exception(sprintf("Can't connect to database. Error: %s", mysqli_connect_error()));
       }
	   else
	   {
		   unset($this->dbPassword);
		   unset($this->dbUser);
		   unset($this->dbSchema);
	   }
   }

   public function __destruct()
   {
       if (!mysqli_connect_errno())
       {
           $this->close();
       }
   }

   private function _getDBParameters($XMLFilePath, $connectionID)
   {
       if (!(file_exists($XMLFilePath)))
       {
           return FALSE;
       }

       $connections = simplexml_load_file($XMLFilePath);

       foreach ($connections as $connection)
       {
           if ($connection['ID'] == $connectionID)
           {
               $this->dbHost    = (string) $connection->dbHost;
               $this->dbUser    = (string) $connection->dbUser;
               $this->dbPassword = (string) $connection->dbPassword;
               $this->dbSchema  = (string) $connection->dbSchema;
           }
       }
   }

   public function query($sql, $resultmode = NULL )
   {
	    if (!parent::ping()) parent::connect();
		if (!($query = parent::query($sql)))
		{
			die(sprintf("Não consegui executar o query. Error: %s", $this->error));
		}

		if (!is_object($query)) {
			return $query;
		}
		else
		{
			while ($row = $query->fetch_array()) {
				$return[] = $row;
			}
			$query->close();
		}
		return isset($return) ? $return : false ;
		
   }

	public function get_columns_max_len($dbTable)
	{
		if (!($result = $this->query("DESCRIBE $dbTable")))
		{
			return FALSE;
		}

		while ($row = $result->fetch_array(MYSQLI_ASSOC))
		{
			if (ereg('^.+\((.+)\)', $row['Type'], $columnMaxLen))
			{
			   $columnMaxLens[$row['Field']] = (int)$columnMaxLen[1];
			}
		}
		return $columnMaxLens;
	}

	public function get_columns_comments($dbTable)
	{
		if (!($result = $this->query("SHOW CREATE TABLE $dbTable")))
		{
			return FALSE;
		}

		$row = $result->fetch_row();
		$tableSQL = explode("\n", $row[1]);

		foreach ($tableSQL as $tableSQLLine)
		{
			if (ereg(".+ `(.+)` .+ COMMENT '(.+)'", $tableSQLLine, $columnCommentsBuffer))
			{
			   $columnComments[$columnCommentsBuffer[1]] = $columnCommentsBuffer[2];
			}
		}
		return $columnComments;
	}

	public function get_enum_options($dbTable, $dbColumn)
	{
		if (!($result = $this->query("DESCRIBE $dbTable")))
		{
			return FALSE;
		}

		while ($row = $result->fetch_array(MYSQLI_ASSOC))
		{
			if ($row['Field'] == $dbColumn)
			{
			   if (eregi('(enum|set)\((.+)\)', $row['Type'], $enumValues))
			   {
				   $enumValues = explode(",", str_replace("'", NULL, $enumValues[2]));
			   }
			}
		}
		return isset($enumValues) ? $enumValues : FALSE;
	}

	function getLastInsertId()
	{
		return $this->insert_id;
	}
	
	function getAffectedRows()
	{
		return $this->affected_rows;
	}

	function getFields($table) 
	{
		return $this->query("describe $table");
	}
	
	function begin() {
		$this->autocommit(false);
	}

	function rollback($flags = NULL, $name = NULL) {
		$this->rollback();
		$this->autocommit(true);
	}

	function commit($flags = NULL, $name = NULL) {
		$this->autocommit(true);
	} 
}

//-- vi: set tabstop=4 shiftwidth=4 showmatch nowrap: 
	
?>
