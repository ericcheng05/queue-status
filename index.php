<html>
	<head>
		<title>Sushiro HK Store Queue Status</title>
		<meta http-equiv="refresh" content="120">
		<link rel="stylesheet" type="text/css" href="css/main.css">
	</head>
	<body>
		<table class="container">
			<tr>
				<th>Store</th>
				<th>Current Queue Size</th>
				<th>Next 1st Ticket</th>
				<th>Next 2nd Ticket</th>
				<th>Next 3rd Ticket</th>
  			</tr>
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
					
					echo "<tr>", PHP_EOL;
					echo "<td>$value->name</td>", PHP_EOL;
					echo "<td>$value->waitingGroup</td>", PHP_EOL;
					echo count($array_StoreQueue->mixedQueue);
					if (count($array_StoreQueue->mixedQueue) == 3)
					{
						echo "<td>$array_StoreQueue->mixedQueue[0]</td>", PHP_EOL;
						echo "<td>$array_StoreQueue->mixedQueue[1]</td>", PHP_EOL;
						echo "<td>$array_StoreQueue->mixedQueue[2]</td>", PHP_EOL;
					}
					elseif (count(array_StoreQueue->mixedQueue) == 2)
					{
						echo "<td>$array_StoreQueue->mixedQueue[0]</td>", PHP_EOL;
						echo "<td>$array_StoreQueue->mixedQueue[1]</td>", PHP_EOL;
						echo "<td>NA</td>", PHP_EOL;
					}
					elseif (count(array_StoreQueue->mixedQueue) == 2)
					{
						echo "<td>$array_StoreQueue->mixedQueue[0]</td>", PHP_EOL;
						echo "<td>NA</td>", PHP_EOL;
						echo "<td>NA</td>", PHP_EOL;
					}
					elseif (count(array_StoreQueue->mixedQueue) == 1)
					{
						echo "<td>$array_StoreQueue->mixedQueue[0]</td>", PHP_EOL;
						echo "<td>NA</td>", PHP_EOL;
						echo "<td>NA</td>", PHP_EOL;
					}
					else
					{
						echo "<td>NA</td>", PHP_EOL;
						echo "<td>NA</td>", PHP_EOL;
						echo "<td>NA</td>", PHP_EOL;
					}
					echo "</tr>", PHP_EOL;					
				}
			?>

			
			
			<?php
				foreach ($allStoreQueueStatus as $value) 
				{
					
				}
			?>
		</table>
	</body>
</html>
