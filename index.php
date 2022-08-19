<html>
	<head>
		<title>Sushiro HK Store Queue Status</title>
		<meta http-equiv="refresh" content="120">
	</head>
	<body>
		<?php
			$sushiroStoreList = "https://sushipass.sushiro.com.hk/api/2.0/info/storelist?latitude=22&longitude=114&numresults=25&region=HK";
			$sushiroQueuePath = "https://sushipass.sushiro.com.hk/api/2.0/remote/groupqueues?region=HK&storeid=";
			//  Initiate curl
			$channel = curl_init();
			curl_setopt($channel, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($channel, CURLOPT_URL,$sushiroStoreList);
			// Execute
			$storeList = curl_exec($channel);
			curl_close($channel);

			$decodedStoreList = json_decode($storeList);
			$storeCount = count($decodedStoreList);
			echo "breakpoint";
			for ($x = 0; $x < $storeCount; $x++)
			{
				echo $decodedStoreList[$x]->name;
				echo " Current Queue Size: ".$decodedStoreList[$x]->waitingGroup;
    				
				$channel = curl_init();
				curl_setopt($channel, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($channel, CURLOPT_URL,$sushiroQueuePath.$decodedStoreList[$x]->id);
				// Execute
				$storeQueue = curl_exec($channel);
				$decodedStoreQueue = json_decode($storeQueue);
				echo "Next Ticket: ".$decodedStoreQueue->mixedQueue[0], PHP_EOL;
				curl_close($channel);
			}
		
	?>
  </body>
</html>
