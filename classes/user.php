<?php
require("init.php");

class user{
	
    private $id;
	private $username;
	private $password;
   	private $firstName;
    private $lastName;
    private $email;

	private $acceptGet = array(
    'id',
	'username',
	'password',
	'firstName',
	'lastName',
	'email');

    private $acceptSet = array(
    'username',
    'password',
    'firstName',
    'lastName',
    'email');
    
    public function user($username = null){
        if(!is_null($username)){
            $this->load($username);
        }
    }

	public function load($username){
        global $database;
        $attributes = $database->getObject($this, $username);
        foreach ($attributes as $name => $value) {
            $this->$name = $value;
        }
 	}
    
    public function get($param){
    	if(in_array($param, $this->acceptGet) && isset($this->id)){
    		return $this->$param;
    	}
    }

    public function set($param, $value){
        global $database;

        if(in_array($param, $this->acceptSet) && isset($this->id)){
            $database->setParam($this, $param, $value, $this->username);
            $this->$param = $value;
        }
    }
    
    public function login($username, $password){
        global $database;
        
        $userVars = $database->getObject($this,$username);

        if($userVars['password'] == $password){
            $this->load($username);
            setcookie('username', $username);
        }else{
            return false;
        }
    }

    public function create($username, $password, $firstName, $lastName, $email){
        global $database;
        $params = array('username'=>$username, 'password'=>$password, 'firstName'=>$firstName, 'lastName'=>$lastName, 'email'=>$email);
        // $database->nonQueryCommand("p_create_user",$params);
        $database->createObject($this,$params);
        $this->login($username, $password);
    }
    
    public function buildTable(){
    	$objectVariables = get_object_vars($this);
    	unset($objectVariables['acceptGet']);
    	unset($objectVariables['acceptSet']);
    	
    	$variableKeys = array_keys($objectVariables);
    	$columns = array();
    	    	    	
    	foreach($variableKeys as $variableKey){
    		if($variableKey == 'id'){
    			$columns[$variableKey] = array();
    			$columns[$variableKey]['type'] = 'intMedium';
    			$columns[$variableKey]['null'] = false;
    			$columns[$variableKey]['options']['autoIncrement'] = '';
    		}elseif($variableKey == 'username'){
    			$columns[$variableKey] = array();
    			$columns[$variableKey]['type'] = 'textMedium';
    			$columns[$variableKey]['null'] = false;
    			$columns[$variableKey]['options']['default'] = '';
    		}else{
    			$columns[$variableKey] = array();
    			$columns[$variableKey]['type'] = 'textMedium';
    			$columns[$variableKey]['null'] = true;
    			$columns[$variableKey]['options']['default'] = '';
    		}
    	}   	
    	    	    	
    	if(in_array('id', $variableKeys))
    		$primaryKey = 'id';
    	
    	
    	if(in_array('username', $variableKeys))
    		$uniqueKey = 'username';
    	    		
    	$tableName = get_class($this);
    	
    	global $database;
    	$database->buildTable($tableName, $columns, $primaryKey, $uniqueKey);
    }
}
