<html>
	<head>
		<title>Sushiro HK Store Queue Status</title>
		<meta http-equiv="refresh" content="120">
		<link rel="stylesheet" type="text/css" href="css/main.css">
	</head>
	<body>
		<h1>Sushiro籌號</h1>
		<?php
			$dt = new DateTime("now", new DateTimeZone('Asia/Hong Kong'));
			$dt->format("Y-m-d H:i");	
			echo "<h2>Update Time:	$dt</h2>", PHP_EOL;
		?>
					
		<table class="container">
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
					
					
					if ($value->counterReservationsAllowed)
					{
						echo "<td>暫停派籌</td>", PHP_EOL;
					}
					else
					{
						echo "<td>派籌中</td>", PHP_EOL;
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
			?>
		</table>
	</body>
</html>
