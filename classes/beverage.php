<?php
require('init.php');

class beverage{
	private $id;
	private $name;
	private $milk;
	private $syrup;
	private $sugar;
	private $hot;
	private $modifier;
	private $notes;
	private $size;
	
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
    	
    	$database->queryCommand();
    	
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
    		if($variableKey == 'id'){
    			$columns[$variableKey] = array();
    			$columns[$variableKey]['type'] = 'intMedium';
    			$columns[$variableKey]['null'] = false;
    			$columns[$variableKey]['options']['autoIncrement'] = '';
    		}elseif($variableKey == 'hot' || $variableKey == 'size'){
    			$columns[$variableKey] = array();
    			$columns[$variableKey]['type'] = 'intMedium';
    			$columns[$variableKey]['null'] = false;
    		}elseif($variableKey == 'notes' || $variableKey == 'modifier'){
    			$columns[$variableKey] = array();
    			$columns[$variableKey]['type'] = 'textLarge';
    			$columns[$variableKey]['null'] = true;
    			$columns[$variableKey]['options']['default'] = 'NULL';
    		}elseif($variableKey == 'sugar'){
    			$columns[$variableKey] = array();
    			$columns[$variableKey]['type'] = 'intSmall';
    			$columns[$variableKey]['null'] = true;
    			$columns[$variableKey]['options']['default'] = 'NULL';
    		}elseif($variableKey == 'name'){
    			$columns[$variableKey] = array();
    			$columns[$variableKey]['type'] = 'textMedium';
    			$columns[$variableKey]['null'] = false;
    		}elseif($variableKey == 'syrup'){
    			$columns[$variableKey] = array();
    			$columns[$variableKey]['type'] = 'textSmall';
    			$columns[$variableKey]['null'] = true;
    			$columns[$variableKey]['default'] = 'NULL';
    		}elseif($variableKey == 'milk'){
    			$columns[$variableKey] = array();
    			$columns[$variableKey]['type'] = 'textSmall';
    			$columns[$variableKey]['null'] = false;
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