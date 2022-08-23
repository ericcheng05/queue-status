<html>
	<head>
		<title>Sushiro HK Store Queue Status</title>
		<meta http-equiv="refresh" content="120">
		<link rel="stylesheet" type="text/css" href="../css/style.css">
		<link rel="stylesheet" type="text/css" href="../css/table.css">
	</head>
	<body>
		<h1>Sushiro籌號</h1>
		<?php
			date_default_timezone_set('Asia/Hong_Kong');
			$date = date('Y-d-m H:i');
			echo "<h2>Update Time:	$date</h2>", PHP_EOL;
		?>
					
		<table class="center rtable">
			<tr>
				<th>店鋪</th>
				<th>輪候組數</th>
				<th>派籌狀態</th>
				<th>下一張籌號</th>
				<th>下兩張籌號</th>
				<th>下三張籌號</th>
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
			
				// $urls = array();
				$array_allStoreQueueStatus = array();
				$mh = curl_multi_init();
				
				foreach ($array_StoreList as $key => $value) 
				{
					// array_push($urls, $sushiroQueuePath.$value->id);
					
					$array_allStoreQueueStatus[$key]['name'] = $value->name;
					$array_allStoreQueueStatus[$key]['id'] = $value->id;
					$array_allStoreQueueStatus[$key]['waitingGroup'] = $value->waitingGroup;
					$array_allStoreQueueStatus[$key]['storeStatus'] = $value->storeStatus;
					$array_allStoreQueueStatus[$key]['ticketStatus'] = $value->localTicketingStatus;
					$array_allStoreQueueStatus[$key]['url'] = $sushiroQueuePath.$value->id;
					$array_allStoreQueueStatus[$key]['curl_handle'] = curl_init($array_allStoreQueueStatus[$key]['url']);
					curl_setopt($array_allStoreQueueStatus[$key]['curl_handle'], CURLOPT_RETURNTRANSFER, true);
					curl_multi_add_handle($mh, $array_allStoreQueueStatus[$key]['curl_handle']);
				}

				//Execute our requests using curl_multi_exec.
				$stillRunning = false;
				do
				{
					curl_multi_exec($mh, $stillRunning);
				}
				while ($stillRunning);
				
				foreach($array_allStoreQueueStatus as $value)
				{
    				//Remove the handle from the multi handle.
    				curl_multi_remove_handle($mh, $value['curl_handle']);
					//Get the response content and the HTTP status code.
					$value['content'] = curl_multi_getcontent($value['curl_handle']);
					// $value['http_code'] = curl_getinfo($value['curl_handle'], CURLINFO_HTTP_CODE);
					//Close the handle.
					curl_close($value['curl_handle']);
					
					$array_StoreQueue = json_decode($value['content']);
					echo "<tr>", PHP_EOL;
					$name = $value['name'];
					$waitingTicket = $value['waitingGroup'];
					echo "<td>$name</td>", PHP_EOL;
					echo "<td>$waitingTicket</td>", PHP_EOL;
					
					switch ($value['storeStatus'])
					{
						case "OPEN":
							if ($value['ticketStatus'] == "ON")
							{
								echo "<td>派籌中</td>", PHP_EOL;
							}
							else
							{
								echo "<td>暫停派籌</td>", PHP_EOL;
							}
							break;
						case "CLOSED":
							echo "<td>閉店中</td>", PHP_EOL;
							break;
						default:
							echo "<td>閉店中</td>", PHP_EOL;							
					}
					
					$counter = count($array_StoreQueue->mixedQueue);
					if ($counter > 0)
					{
						$firstTicket = ($array_StoreQueue->mixedQueue)[0];
						echo "<td>$firstTicket</td>", PHP_EOL;						
					}
					else
					{
						echo "<td>NA</td>", PHP_EOL;
					}
					if ($counter > 1)
					{
						$secondTicket = ($array_StoreQueue->mixedQueue)[1];
						echo "<td>$secondTicket</td>", PHP_EOL;						
					}
					else
					{
						echo "<td>NA</td>", PHP_EOL;
					}
					if ($counter > 2)
					{
						$thirdTicket = ($array_StoreQueue->mixedQueue)[2];
						echo "<td>$thirdTicket</td>", PHP_EOL;						
					}
					else
					{
						echo "<td>NA</td>", PHP_EOL;
					}
					
					echo "</tr>", PHP_EOL;
				}				
				
				//Close the multi handle.
				curl_multi_close($mh);
			?>
		</table>
	</body>
</html>
