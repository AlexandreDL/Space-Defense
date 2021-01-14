<?php

$commander_set = false;
$defList = array(
	'refuel',
	'assist',
	'cargo'
);
$atkList = array(
	'battleship',
	'cruiser',
	'destroyer'
);
$arrayMap = array();
$coordinate_list = array();
for($count_pair_vessel = 0; $count_pair_vessel < 25; $count_pair_vessel++){
	$coordinate_valid = false;
	while($coordinate_valid == false){
		$x = rand(0,99);
		$y = rand(0,99);
		$z = rand(0,99);
		if(empty($arrayMap[$x][$y][$z])){ //free position
			$free_positions = getAllFreePositionAround($arrayMap, $x, $y, $z);
			if(!empty($free_positions)){ //free position around
				if(!$commander_set){
					$arrayMap[$x][$y][$z] = 'commander destroyer';
					$commander_set = true;
				}else{
					$arrayMap[$x][$y][$z] = $atkList[rand(0,count($atkList)-1)]; //random atk type
				}
				$random_def = rand(0,count($free_positions)-1);
				$arrayMap[$free_positions[$random_def]['x']][$free_positions[$random_def]['y']][$free_positions[$random_def]['z']] = $defList[rand(0,count($defList)-1)]; //random def type
				$coordinate_list[] = array(
					'atk' => array(
						'type' => $arrayMap[$x][$y][$z], 
						'x' => $x, 
						'y' => $y, 
						'z' => $z
					),
					'def' => array(
						'type' => $arrayMap[$free_positions[$random_def]['x']][$free_positions[$random_def]['y']][$free_positions[$random_def]['z']], 
						'x' => $free_positions[$random_def]['x'], 
						'y' => $free_positions[$random_def]['y'], 
						'z' => $free_positions[$random_def]['z']
					)
				);
				$coordinate_valid = true;
			}
		}
	}
}

function getAllFreePositionAround($arrayMap, $x, $y, $z){
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
					&& empty($arrayMap[$x+$x_loop][$y+$y_loop][$z+$z_loop])
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

//echo '<pre>$arrayMap = '.(print_r($arrayMap,true)).'</pre>';
echo '<pre>$coordinate_list = '.(print_r($coordinate_list,true)).'</pre>';