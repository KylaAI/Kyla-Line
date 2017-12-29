<?php
function dd($data){
	echo "<pre>";
	print_r($data);
	echo "</pre>";
}
function logs($files,$data){
	$files = BASEPATH."Logs/".ucfirst($files).".json";
	$data = (is_array($data))? json_encode($data): $data;
	file_put_contents($files, $data);
}