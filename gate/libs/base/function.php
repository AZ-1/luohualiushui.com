<?php
function dump($p,$die=1){
    header("Content-type: text/html; charset=utf-8");
	echo '<pre>';
    var_dump($p);
	echo '</pre>';
	if($die) die();
}

function debug($p, $die=1){
	if(isset($_GET['debug'])){
		pr($p,$die);
	}	
}

function vd($p, $die=1){
	dump($p,$die);
}

function pr($p,$die=1){
    //header("Content-type: text/html; charset=utf-8");
	echo '<pre>';
	empty($p) ? var_dump($p)  :  print_r($p);
	echo '</pre>';
	if($die) die();
}
