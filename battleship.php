<?php

/**
 * Class map
 */
class map{
	/**
	 * @var fleet
	 */
	protected $fleet = null;

	public function construct(){
		$this->fleet = new fleet();
	}

	public function setup(){
		$this->fleet->setup();
	}
}

/**
 * Class fleet
 */
class fleet{

	public CONST MAX_VESSEL = 50;
	public CONST MAX_VESSEL_ATK = 25;
	public CONST MAX_VESSEL_DEF = 25;

	/**
	 * @var int
	 */
	public $id = null;

	/**
	 * @var array
	 */
	public $typeList = null;

	/**
	 * @var array
	 */
	public $mainTypeList = null;

	/**
	 * @var array
	 */
	public $vesselList = null;

	/**
	 * fleet constructor.
	 */
	public function __construct(){
		$this->vesselList = array();
	}

	/**
	 *
	 */
	public function setup(){
		$this->addMainType('def', 'Support Craft');
		$this->addMainType('atk', 'Offensive Craft');

		$this->addType('def', 'refuel', 'Refueling');
		$this->addType('def', 'assist', 'Mechanical Assistance');
		$this->addType('def', 'cargo', 'Cargo');

		$this->addType('atk', 'battleship', 'Battleship');
		$this->addType('atk', 'cruiser', 'Cruiser');
		$this->addType('atk', 'destroyer', 'Destroyer');

		$this->addVessel('battleship');

		$count_def = 0;
		$count_atk = 0;

		$this->addVessel($this->getType('battleship'),true, $this->generateValidCoordinates());

		for($create_loop = 0; $create_loop < (int)(self::MAX_VESSEL/2)-1; $create_loop++){
			if($count_def < self::MAX_VESSEL_ATK){
				$defVessel = new vessel($this->getType($this->getRandomTypeCodeFromMain('atk')));
				$this->addVessel($defVessel);
			}
		}
	}

	/**
	 * @return array
	 */
	public function generateRandomCoordinates(){
		//todo generate array with random X & Y
	}

	/**
	 * @param int $position_x
	 * @param int $position_y
	 * @return bool
	 */
	public function validateCoordinates($position_x, $position_y){
		//todo check that current coordinates are valid or not
	}

	/**
	 * @return array
	 */
	public function generateValidCoordinates(){
		//todo call generateRandomCoordinates until valid coordinates are found via validateCoordinates
	}

	public function generateDefenderCoordinates($atk_position_x, $atk_position_y){
		//todo generate coordinate close to the provide x+y coordinates
	}

	/**
	 * @param string $code
	 * @param string $label
	 */
	protected function addMainType($code, $label){
		$this->mainTypeList[$code] = vesselMainType::getInstance($code, $label);
	}

	protected function addType($main_code, $code, $label){
		$this->typeList[$code] = vesselType::getInstance($code, $label, $this->getMainType($main_code));
	}

	/**
	 * @param string $code
	 * @return vesselMainType
	 */
	public function getMainType($code){
		return $this->mainTypeList[$code];
	}

	/**
	 * @param string $code
	 * @return vesselType
	 */
	public function getType($code){
		return $this->typeList[$code];
	}

	/**
	 * @param string $code
	 */
	public function addVessel($code, $is_commander = false){
		$vessel = new vessel($this->getType($code));
		if($is_commander === true){
			$vessel->setCommander(true);
		}
		$this->vesselList[] = $vessel;
	}

	protected function getRandomTypeCodeFromMain($code){
		$index = Rand(0, count($this->typeList));
		return $this->typeList[$index]->getCode();
	}
}

/**
 * Class vessel
 */
class vessel{
	/**
	 * @var int
	 */
	public $id = null;

	/**
	 * @var bool
	 */
	public $is_commander = false;

	/**
	 * @var int
	 */
	public $position_x = null;

	/**
	 * @var int
	 */
	public $position_y = null;

	/**
	 * vessel constructor.
	 */
	public function __construct($coordinates = array()){
		$this->position_x = 0;
		$this->position_y = 0;
		if(is_array($coordinates) && !empty($coordinates) && isset($coordinates['x']) && isset($coordinates['y'])){
			$this->setCoordinate($coordinates['x'], $coordinates['y']);
		}
	}

	/**
	 * @param int $x
	 * @param int $y
	 */
	public function setCoordinate($x, $y){
		$this->position_x = $x;
		$this->position_y = $y;
	}

	/**
	 * @param false $is_commander
	 */
	public function setCommander($is_commander = false){
		$this->is_commander = (bool)$is_commander;
	}
}

class vesselType{

	/**
	 * @var array
	 */
	public static $instanceList = array();

	/**
	 * @var int
	 */
	public $id;

	/**
	 * @var string
	 */
	public $code;

	/**
	 * @var string
	 */
	public $label;

	/**
	 * vesselType constructor.
	 * @param string $code
	 * @param string $label
	 */
	protected function __construct($code, $label){
		$this->code = (string)$code;
		$this->label = (string)$label;
	}

	/**
	 * @param string $code
	 * @param string $label
	 * @return vesselType
	 */
	public static function getInstance($code, $label = null){
		if(is_null(self::$instanceList[$code])){
			self::$instanceList[$code] = new static($code, $label);
		}
		return self::$instanceList[$code];
	}
}

class vesselMainType{

	/**
	 * @var array
	 */
	public static $instanceList = array();

	/**
	 * @var int
	 */
	public $id;

	/**
	 * @var string
	 */
	public $code;

	/**
	 * @var string
	 */
	public $label;

	/**
	 * @var vesselMainType
	 */
	public $mainType = null;

	/**
	 * vesselMainType constructor.
	 * @param string $code
	 * @param string $label
	 * @param vesselMainType $mainType
	 */
	protected function __construct($code, $label, $mainType){
		$this->code = (string)$code;
		$this->label = (string)$label;
		$this->mainType = (string)$mainType;
	}

	/**
	 * @param string $code
	 * @param string $label
	 * @param vesselMainType $mainType
	 * @return vesselType
	 */
	public static function getInstance($code, $label = null, $mainType = null){
		if(is_null(self::$instanceList[$code])){
			self::$instanceList[$code] = new static($code, $label, $mainType);
		}
		return self::$instanceList[$code];
	}
}

$map = new map();
$map->setup();