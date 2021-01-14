<?php

class fleet {

	protected CONST MAX_PAIR_VESSEL = 25;

	protected CONST MIN_X = 0;
	protected CONST MIN_Y = 0;
	protected CONST MIN_Z = 0;

	protected CONST MAX_X = 99;
	protected CONST MAX_Y = 99;
	protected CONST MAX_Z = 99;

	protected $defList = array(
		'refuel',
		'assist',
		'cargo'
	);

	protected $atkList = array(
		'battleship',
		'cruiser',
		'destroyer'
	);

	protected $arrayMap = array();

	protected $coordinateList = array();

	public function __construct(){
		$this->arrayMap = array();
		$this->coordinateList = array();
	}

	/**
	 * @return array 
	 */
	public function generateMapCoordinates(){
		$commander_set = false;
		for($count_pair_vessel = 0; $count_pair_vessel < self::MAX_PAIR_VESSEL; $count_pair_vessel++){
			$coordinate_valid = false;
			while($coordinate_valid == false){
				$x = rand(self::MIN_X,self::MAX_X);
				$y = rand(self::MIN_Y,self::MAX_Y);
				$z = rand(self::MIN_Z,self::MAX_Z);
				if(empty($this->arrayMap[$x][$y][$z])){ //free position
					$free_positions = $this->getAllFreePositionAround($x, $y, $z);
					if(!empty($free_positions)){ //free position around
						if(!$commander_set){
							$this->arrayMap[$x][$y][$z] = 'commander destroyer';
							$commander_set = true;
						}else{
							$this->arrayMap[$x][$y][$z] = $this->getRandomAtkVessel();
						}
						$random_coordinates = $free_positions[rand(0,count($free_positions)-1)];
						$this->arrayMap[$random_coordinates['x']][$random_coordinates['y']][$random_coordinates['z']] = $this->getRandomDefVessel();
						$this->coordinateList[] = array(
							'atk' => array(
								'type' => $this->arrayMap[$x][$y][$z], 
								'x' => $x, 
								'y' => $y, 
								'z' => $z
							),
							'def' => array(
								'type' => $this->arrayMap[$random_coordinates['x']][$random_coordinates['y']][$random_coordinates['z']], 
								'x' => $random_coordinates['x'], 
								'y' => $random_coordinates['y'], 
								'z' => $random_coordinates['z']
							)
						);
						$coordinate_valid = true;
					}
				}
			}
		}
		return $this->coordinateList;
	}

	protected function getRandomAtkVessel(){
		return $this->atkList[rand(0,count($this->atkList)-1)];
	}

	protected function getRandomDefVessel(){
		return $this->defList[rand(0,count($this->defList)-1)];
	}

	protected function getAllFreePositionAround($x, $y, $z){
		$free_positions = array();
		for($x_loop = -1; $x_loop <= 1; $x_loop++){
			for($y_loop = -1; $y_loop <= 1; $y_loop++){
				for($z_loop = -1; $z_loop <= 1; $z_loop++){
					if(
						(
							$x_loop !== 0 
							|| $y_loop !== 0 
							|| $z_loop !== 0
						)
						&& empty($this->arrayMap[$x+$x_loop][$y+$y_loop][$z+$z_loop])
					){
						$free_positions[] = array(
							'x' => $x+$x_loop,
							'y' => $y+$y_loop,
							'z' => $z+$z_loop
						);
					}
				}
			}
		}
		return $free_positions;
	}
}

$myFleet = new fleet();
echo '<pre>'.print_r($myFleet->generateMapCoordinates(),true).'</pre>';

exit();