<?php
require('init.php');

class store{
	private $id;
	private $name;
	private $city;
	private $streetAddress;
	private $postalCode;
	private $phoneNumber;
	private $storeHours;
	private $email;
	
	private $acceptGet = array(
		'id',
		'name',
		'city',
		'streetAddress',
		'postalCode',
		'phoneNumber',
		'storeHours',
		'email'
	);
	
	private $acceptSet = array(
		'name',
		'city',
		'streetAddress',
		'postalCode',
		'phoneNumber',
		'storeHours',
		'email'
	);
	
	public function store($id = null){
        if(!is_null($id)){
            $this->load($id);
        }
    }

	public function load($id){
        global $database;
        $attributes = $database->getObject($this, $id);
        foreach ($attributes as $name => $value) {
        	if($name != 'storeHours')
            	$this->$name = $value;
        }
        $this->storeHours = $this->getStoreHours();
 	}
    
    public function get($param){
    	if(in_array($param, $this->acceptGet) && isset($this->id)){
    		return $this->$param;
    	}
    }

    public function set($param, $value){
        global $database;

        if(in_array($param, $this->acceptSet) && isset($this->id)){
            $database->setParam($this, $param, $value, $this->id);
            $this->$param = $value;
        }
    }
    
    public function getStoreHours(){
    	global $database;
    	
    }
    
    public function buildTable(){
		
    	$objectVariables = get_object_vars($this);
    	unset($objectVariables['acceptGet']);
    	unset($objectVariables['acceptSet']);
    	unset($objectVariables['storeHours']);
    	$objectVariables['storeHoursId'] = '';
    	
    	$variableKeys = array_keys($objectVariables);
    	$columns = array();
    	    	    	
    	foreach($variableKeys as $variableKey){
    		if($variableKey == 'id' || $variableKey == 'storeHoursId'){
    			$columns[$variableKey] = array();
    			$columns[$variableKey]['type'] = 'intMedium';
    			$columns[$variableKey]['null'] = false;
    			$columns[$variableKey]['options']['autoIncrement'] = '';
    		}elseif($variableKey == 'streetAddress' || $variableKey == 'email'){
    			$columns[$variableKey] = array();
    			$columns[$variableKey]['type'] = 'textLarge';
    			$columns[$variableKey]['null'] = true;
    			$columns[$variableKey]['options']['default'] = '';
    		}elseif($variableKey == 'postalCode'){
    			$columns[$variableKey] = array();
    			$columns[$variableKey]['type'] = 'textSmall';
    			$columns[$variableKey]['null'] = false;
    			$columns[$variableKey]['options']['default'] = '';
    		}elseif($variableKey == 'phoneNumber'){
    			$columns[$variableKey] = array();
    			$columns[$variableKey]['type'] = 'textSmall';
    			$columns[$variableKey]['null'] = true;
    			$columns[$variableKey]['options']['default'] = '';
    		}else{
    			$columns[$variableKey] = array();
    			$columns[$variableKey]['type'] = 'textMedium';
    			$columns[$variableKey]['null'] = false;
    			$columns[$variableKey]['options']['default'] = '';
    		}
    	}   	
    	    	    	
    	if(in_array('id', $variableKeys))
    		$primaryKey = 'id';
 	    		
    	$tableName = get_class($this);
    	
    	global $database;
    	$database->buildTable($tableName, $columns, $primaryKey, $uniqueKey);
    }
}
?>