<html>
	<head>
		<title>Sushiro HK Store Queue Status</title>
		<meta http-equiv="refresh" content="120">
	</head>
	<body>
		<?php
			$sushiroStoreList = "https://sushipass.sushiro.com.hk/api/2.0/info/storelist?latitude=22&longitude=114&numresults=25&region=HK";
			$sushiroQueuePath = "https://sushipass.sushiro.com.hk/api/2.0/remote/groupqueues?region=HK&storeid=";
			echo "breakpoint";
		//  Initiate curl
$ch = curl_init();
// Will return the response, if false it print the response
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// Set the url
curl_setopt($ch, CURLOPT_URL,$sushiroStoreList);
// Execute
$result=curl_exec($ch);
// Closing
curl_close($ch);

// Will dump a beauty json :3
var_dump(json_decode($result, true));
		echo "breakpoint";
			$StoreList = file_get_contents($sushiroStoreListPath);
  		$decodedStoreList = json_decode($StoreList);
			$storeCount = count($decodedStoreList);
			echo "breakpoint";
			for ($x = 0; $x < $storeCount; $x++)
			{
					echo $decodedStoreList[$x]->name;
					echo " Current Queue Size: ".$decodedStoreList[$x]->waitingGroup;
    
					$StoreQueue = file_get_contents($sushiroQueuePath.$decodedStoreList[$x]->id);
					$decodedStoreQueue = json_decode($StoreQueue);
					echo "Next Ticket: ".$decodedStoreQueue->mixedQueue[0], PHP_EOL;;
  		}
	?>
  </body>
</html>
