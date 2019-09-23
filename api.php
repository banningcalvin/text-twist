<?php



    // Get all subracks and submit
	function generate_rack($length){
  		$tileBag = "AAAAAAAAABBCCDDDDEEEEEEEEEEEEFFGGGHHIIIIIIIIIJKLLLLMMNNNNNNOOOOOOOOPPQRRRRRRSSSSTTTTTTUUUUVVWWXYYZ";
  		$rack_letters = substr(str_shuffle($tileBag), 0, $length);
  
  		$temp = str_split($rack_letters);
  		sort($temp);
  		return implode($temp);
    };
    
	$rack = generate_rack(7);
	$racks = [];
	for($i = 0; $i < pow(2, strlen($rack)); $i++){
		$answer = "";
		for($j = 0; $j < strlen($rack); $j++){
			if (($i >> $j) % 2) {
			  $answer .= $rack[$j];
			}
		}
		if (strlen($answer) > 1){
			$racks[] = $answer;	
		}
	}
	$racks = array_unique($racks);

	$response = array('letters' => $rack, 'words' => array());






    $dbhandle = new PDO("sqlite:scrabble.sqlite") or die("Failed to open DB");
    if (!$dbhandle) die ($error);
 
    foreach ($racks as $subrack) {
        $query = "SELECT words FROM racks WHERE rack = ?";
        

        $statement = $dbhandle->prepare($query);
        $statement->bindParam(1, $subrack, PDO::PARAM_STR);
        $statement->execute();
        
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($results as $row) {
            $response['words'] = array_merge(
                $response['words'],
                explode('@@', $row['words'])
            );
        }
    }
    
    header('HTTP/1.1 200 OK');
    header('Content-Type: application/json');
    echo json_encode($response);
?>