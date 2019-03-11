<?php
set_time_limit(60);


if ($_SERVER["REQUEST_METHOD"] == 'POST') {
	$data = json_decode(file_get_contents('php://input'), true);
	
	#najprej shranm file
	$content = $data["fileContent"];
	$contentDecoded = base64_decode($content);
	$file_name = $data["filename"];
	file_put_contents($file_name, $contentDecoded);

	#exec rab met ('ukaz') znotrej pa rabs poti v ""
	$python_path = "\"C:\Program Files (x86)\Python27\python\"";
	$script_name = "\"C:\wamp64\www\ada_login_api\Source_Files\dip_detect_recorded_files.py\"";
	$redirect_err = "2>&1";
	$script_output = null;
	$script_result = -4;

	$rez = exec($python_path. ' '.$script_name.' '.$file_name.' 2>&1',$script_output,$script_result);

	$response = array();
	$error = "";
	if($rez) {
		#exec je uspela. Še preverimo naš ukaz
		if($script_result == 0) {
			#uspeh!
			$response["success"] = 1;
			$response["message"] = "Song detection successful.";
			$response["song_name"] = $script_output[0];
		} else {
			$response["success"] = 0;
			$response["message"] = "Song detection unsuccessful.";
			$response["song_name"] = "unknown";
			#ce ne rata potem pogledam napako
			foreach($script_output as $item) {
	    		$error = $error ."</br>". $item;
			}
		}
	} else {
		$response["success"] = 0;
		$response["message"] = "Song detection unsuccessful.";
		$response["song_name"] = "unknown";
		#pogledam napako
		foreach($script_output as $item) {
    		$error = $error ."</br>". $item;
		}
	}
	echo json_encode($response);# $song_name;
	//if(!empty($error)) {
	//	echo $error;
	//}
}

?>