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

	$array_StoreList = json_decode($storeList);
	$array_allStoreQueueStatus = array();
	foreach ($array_StoreList as $value) 
	{
		$channel = curl_init();
		curl_setopt($channel, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($channel, CURLOPT_URL,$sushiroQueuePath.$value->id);
		// Execute
		$storeQueue = curl_exec($channel);
		$array_StoreQueue = json_decode($storeQueue);
		curl_close($channel);
		$array_Calling = $array_StoreQueue->mixedQueue;
		echo $value->name;
		echo $value->waitingGroup;
		echo count($array_Calling);
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
				foreach ($allStoreQueueStatus as $value) 
				{
					echo "<tr>", PHP_EOL;
					echo "<td>$value[0]</td>", PHP_EOL;
					echo "<td>$value[1]</td>", PHP_EOL;
					echo "<td>0</td>", PHP_EOL;
					echo "<td>1</td>", PHP_EOL;
					echo "<td>2</td>", PHP_EOL;
					echo "</tr>", PHP_EOL;
				}
			?>
		</table>
	</body>
</html>
