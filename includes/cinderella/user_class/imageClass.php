<?PHP

class imageClass{

	public function imageOutput($devName = ""){
		$imgType = 2;

		$imgePref = "";
		$fileHandle = fopen("mrhns-net/user_data/division.dat", "r", TRUE);
		while($divList = fgetcsv($fileHandle, 0, "\t")){
			if($divList[0] == $devName){
				$imgPref = $divList[1];
				break;
			}
		}
		if($imgPref == ""){
			$imgPref = "b";
		}

		$IMGDATA = file_get_contents("mrhns-net/user_data/image/mrhn_".$imgType."_".$imgPref.".gif", FILE_USE_INCLUDE_PATH|FILE_BINARY);

		header("Content-type: image/gif");
		print $IMGDATA;
	}

}
?>