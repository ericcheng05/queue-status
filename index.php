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

<html>
	<head>
		<title>Sushiro HK Store Queue Status</title>
		<meta http-equiv="refresh" content="120">
	</head>
	<body>
		<table>
			<tr>
				<th>Store</th>
				<th>Current Queue Size</th>
				<th>Next 1st Ticket</th>
				<th>Next 2nd Ticket</th>
				<th>Next 3rd Ticket</th>
  			</tr>
			<?php
				foreach ($decodedStoreList as $value) 
				{
					echo "<tr>", PHP_EOL;
					echo "<td>$value->name</td>", PHP_EOL;
					echo "<td>$value->waitingGroup</td>", PHP_EOL;
					echo "<td>0</td>", PHP_EOL;
					echo "<td>1</td>", PHP_EOL;
					echo "<td>2</td>", PHP_EOL;
					echo "</tr>", PHP_EOL;
				}
			?>
		</table>
	</body>
</html>
