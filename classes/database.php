
<?php
require("init.php");

class database{

	private $server = "localhost";
	private $user = "root";
	private $password = "gangster4*";
	private $name = "dev";
	private $hashPassword = "";
	private $link = null;

	private $gettableTables = array(
		'user' => 'user'
	);

	private $modifiableTables = array(
		'user' => 'user'
	);

	public function database($dbName = null, $dbServer = null, $dbUser=null, $dbPasswd=null){
		$this->connect($dbName, $dbServer, $dbUser, $dbPasswd);
	}

	public function nonQueryCommand($procName, $params = null){
        if(!isset($params)){
            $storedProc = "CALL " . $procName . "();";
        }else{
            $storedProc = "CALL " . $procName . "('" . $params[0];
            for($i=1;$i<count($params);$i++){
                $storedProc .= "', '" . $params[$i];
            }
            
            $storedProc .= "')";
        }

        $rsDetails = mysqli_query($this->link, $storedProc)
			or die(mysql_error());
	}
	
	public function buildTable($tableName, $columns, $primaryKey = null, $uniqueKey = null, $action = "show"){
		$dataTypes = array(
								'intSmall' => 'TINYINT',
								'intMedium' => 'INT(8)',
								'intLarge' => 'INT(12)',
								'decimalSmall' => 'DECIMAL(4,2)',
								'decimalMedium' => 'DECIMAL(8,2)',
								'decimalLarge' => 'DECIMAL(12,2)',
								'textSmall' => 'VARCHAR(15)',
								'textMedium' => 'VARCHAR(50)',
								'textLarge' => 'VARCHAR(100)',
								'datetime' => 'DATETIME',
								'date' => 'DATE'								
							);
							
		$options = array(
							'autoIncrement' => 'AUTO_INCREMENT',
							'default' => 'DEFAULT'
						);
						
		$autoIncrement = 0;					
		$columnNames = array_keys($columns);				
		$createTableCommand = 'CREATE TABLE IF NOT EXISTS ' . $tableName . ' (';
		
		for($i=0;$i<count($columnNames);$i++){
			$createTableCommand .= $columnNames[$i] . ' ' . $dataTypes[$columns[$columnNames[$i]]['type']];
			
			if($columns[$columnNames[$i]]['null'])
				$createTableCommand .= ' NULL ';
			else
				$createTableCommand .= ' NOT NULL ';
				
			if(count($columns[$columnNames[$i]]['options']) > 0){
				$columnOptions = $columns[$columnNames[$i]]['options'];
							
				$keys = array_keys($columnOptions);
				for($j=0;$j < count($keys);$j++){
					$createTableCommand .=  ' ' . $options[$keys[$j]];
					
					if($keys[$j] == 'autoIncrement')
						$autoIncrement++;
		
					if($keys[$j] == 'default'){
						if($columnOptions[$keys[$j]] == 'NULL'){
							$createTableCommand .=  " " . $columnOptions[$keys[$j]] . " ";
						}else{
							$createTableCommand .=  " '" . $columnOptions[$keys[$j]] . "' ";
						}
					}
				}
			}
			
			if($i < count($columnNames) - 1){
				$createTableCommand .= ', ';
			}
		}
		
		if($autoIncrement > 1){
			print 'Only one column can be auto incremented per table. Please change you table definition to only have one auto increment';
			exit('<br/>Exiting function: Mulitple auto increments');	
		}
		
		if(!is_null($primaryKey)){
			$createTableCommand .= ', PRIMARY KEY(`' . $primaryKey . '`)';
		}
			
		if(!is_null($uniqueKey)){
			$createTableCommand .= ', UNIQUE KEY(`' . $uniqueKey . '`)';
		}
		
		$createTableCommand .= ') ENGINE=InnoDB DEFAULT CHARSET=latin1;';		
		
		if($action == "show"){
			print '<br/>' . $createTableCommand;
		}else if($action == "execute"){
			$rsDetails = mysqli_query($this->link, $createTableCommand);
			if(strlen(mysqli_error($this->link)) > 0)
				exit('<br/>Exiting function: ' . mysqli_error($this->link));
				// or die(mysql_error());
		}
		
	}
 	
 	/**
 	*
 	*/
	public function queryCommand($procName, $params = null){
		
		$this->connect();
				
		if(is_null($params)){
			$strSelect = "CALL " . $procName . "();";
			// No params
		}else{

			for ($i=0; $i < count($params); $i++) { 
				$params[$i] = mysqli_real_escape_string($this->link, $params[$i]);
			}

			$strSelect = "CALL " . $procName . "('" . $params[0];

			for($i=1;$i<count($params);$i++) {
				$strSelect .=  "', '" . $params[$i];
			}

			$strSelect .= "');";
		}
									
		$rsDetails = mysqli_query($this->link, $strSelect, MYSQLI_STORE_RESULT)
			or die(print mysqli_error($this->link) . ' ' . $procName . '<br/>');
	
		$results = array();
		
		while($rowDetails = mysqli_fetch_object($rsDetails))
			array_push($results, $rowDetails);

		return $results;
	}
	
	public function disconnect(){
		if(!is_null($this->link))
			mysqli_close($this->link);
	}

	/**
	* Connects to the database
	* @param string $dbName The name of the database to connect to
	* @param string $dbServer The server the database is on
	* @param string $dbUser The user logging into the database
	* @param string $dbPasswd The password
	*/
	public function connect($dbName = null, $dbServer = null, $dbUser=null, $dbPasswd=null){
		// Database Connectivity

		if(is_null($dbServer))
        	$dbServer = $this->server;  
        else
        	$this->server = $dbServer;

        if(is_null($dbUser))
			$dbUser = $this->user;	
		else
			$this->user = $dbUser;
        
        if(is_null($dbPasswd))
			$dbPasswd = $this->password;
		else
			$this->password = $dbPasswd;
        
        if(is_null($dbName))
			$dbName = $this->name;
		else
			$this->name = $dbName;

		$db_connected = mysqli_connect($dbServer, $dbUser, $dbPasswd, $dbName);
		$this->link = $db_connected;
		
		// Create a connection to the local database				
		if(!isset($db_connected))
			print "Not connected : " . mysqli_error($db_connected);

		$db_selected = mysqli_select_db($db_connected, $dbName);

		// Select the database for use							
		if(!isset($db_selected))
			print "Not selected : " . mysqli_error($db_connected);
	}


	/**
	* Gets all the given attributes for a given object
	* @param $object The object that the attributes are being returned for
	* @param $id The objects id 
	* @return array $returnArray the array of attributes for the object
	*/
	public function getObject($object, $id){
		
		$this->connect();

		$id = mysqli_real_escape_string($this->link, $id);

		$objectName = get_class($object);

		$tableIdFields = array(
			'list_item' => 'id',
			'list' => 'id',
			'user' => 'username',
			'achievement' => 'id',
			'activity' => 'id'
		);

		$table = $this->gettableTables[$objectName];
		$idField = $tableIdFields[$table];

		$strSelect = "SELECT * FROM " . $table . " WHERE " . $idField . " = '" . $id . "';";

		$rsDetails = mysqli_query($this->link, $strSelect, MYSQLI_STORE_RESULT)
			or die("get Object: " . $this->name . " : " .  mysqli_error($this->link));

		$results = array();
		
		$rowDetails = mysqli_fetch_object($rsDetails);

		$results = get_object_vars($rowDetails);
		foreach ($results as $colName => $value) {
			$attributeName = explode('_', $colName);
			$attributeExploded = array();
			$attributeExploded[0] = $attributeName[0];
			for($i=1;$i<count($attributeName);$i++) {
				$attributeExploded[] = ucfirst($attributeName[$i]);
			}

			$attribute = implode('', $attributeExploded);
			$returnArray[$attribute] = $value;
		}

		return $returnArray;
	}

	public function setParam($object, $param, $value, $id){
		$this->connect();
		
		$objectName = get_class($object);
		
		$param = mysqli_real_escape_string($this->link, $param);
		$value = mysqli_real_escape_string($this->link, $value);

		$tableIdFields = array(
			'list_item' => 'id',
			'list' => 'id',
			'user' => 'username',
			'achievement' => 'id'
		);
				
		preg_match_all('/[A-Z]/', $param, $matches, PREG_OFFSET_CAPTURE);
		
		$matches = $matches[0];
				
		if(count($matches) > 0){
			$attributeName = array();
			
			$attributeName[0] = substr($param, 0, $matches[0][1]);
			for($i=0, $j=1;$i<count($matches);$i++, $j++){
				if(!isset($matches[$i + 1])){
						$attributeName[$j] = substr($param, $matches[$i][1]);
				}else{
					$attributeName[$j] = substr($param, $matches[$i][1], $matches[$i + 1][1] - $matches[$i][1]);
				}
			}
			
			for($i=0;$i<count($attributeName);$i++) {
				$attributeName[$i] = lcfirst($attributeName[$i]);
			}
							
			$attribute = implode('_', $attributeName);
		}else{
			$attribute = $param;
		}
		
		$table = $this->modifiableTables[$objectName];
		$idField = $tableIdFields[$table];

		$strSelect = "UPDATE " . $table . " SET " . $attribute . " = '" . $value . "'  WHERE " . $idField . " = '" . $id . "';";
		
		$rsDetails = mysqli_query($this->link, $strSelect)
			or die("set Param: " . $this->name . " : " .  mysqli_error($this->link));
	}

	/**
	* Creates a new instance of the given object
	* @param $object The object that the attributes are being returned for
	* @param $params Gives the array key as the column and the corresponding value
	*/
	public function createObject($object, $params){
		$objectName = get_class($object);

		$table = $this->modifiableTables[$objectName];
		$columns = array_keys($params);
		$count = 0;

		foreach($columns as $column){
			preg_match_all('/[A-Z]/', $column, $matches, PREG_OFFSET_CAPTURE);
			
			$matches = $matches[0];
					
			if(count($matches) > 0){
				$attributeName = array();
				
				$attributeName[0] = substr($column, 0, $matches[0][1]);
								
				for($i=0, $j=1;$i<count($matches[0]) - 1;$i++, $j++){
					if(!isset($matches[$i + 1])){
							$attributeName[$j] = substr($column, $matches[$i][1]);
					}else{
						$attributeName[$j] = substr($column, $matches[$i][1], $matches[$i + 1][1] - $matches[$i][1]);
					}
				
				}
				
				for($i=0;$i<count($attributeName);$i++) {
					$attributeName[$i] = lcfirst($attributeName[$i]);
				}
								
				$columns[$count] = implode('_', $attributeName);
				$count++;
			}else{
				$count++;
			}
		}

		$sqlCommand = 'INSERT INTO ' . $table . '(' . $columns[0];

		for($i=1;$i<count($columns);$i++) {
			$sqlCommand .= ',' . $columns[$i];
		}

		$sqlCommand .= ") VALUES('" . $params[$columns[0]];
		for($i=1;$i<count($columns);$i++) {
			$sqlCommand .= "', '" . $params[$columns[$i]];
		}
		$sqlCommand .= "');";

		$rsDetails = mysqli_query($this->link, $sqlCommand)
			or die("create Object" . $this->name . " : " . mysqli_error($this->link));
	}
	
	public function convertBoolean($boolVal){
		if($boolVal == null){
			//Do nothing
		}elseif(strtoupper($boolVal) == 'Y' || strtoupper($boolVal) == 'YES' || $boolVal == true){
			$boolVal = 1;	
		}elseif(strtoupper($boolVal) == 'N' || strtoupper($boolVal) == 'NO' || $boolVal == false){
			$boolVal = 0;
		}elseif((is_int($boolVal) && $boolVal > 1) || !is_int($boolVal)){
			$boolVal = 'invalid';	
		}
		
		return $boolVal;
	}
	
	public function convertNumber($numVal){
		if($numVal == null){
			// Do nothing	
		}if(!is_numeric($numVal)){
			$numVal = 'invalid';
		}
		
		return $numVal;
	}
} ?>