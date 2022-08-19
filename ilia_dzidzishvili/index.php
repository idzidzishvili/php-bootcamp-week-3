<?php
$host     = 'localhost';
$database = 'bitoid';
$username = 'root';
$password = '';
$table    = 'challenge3_cryptocurrencies';
$isPost 	 = false; // post back
$cryptos  = null; // search result or complete data
$keyword	 = null;

if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
	$isPost = true;
	$conn = new mysqli($host, $username, $password, $database);
	if (!$conn->connect_error) {
		if (isset($_POST['get-data'])) {
			$null = null;
			$url = 'https://api.nomics.com/v1/currencies/ticker?key=827e44e59005c5d2d404dfc432b2980503f301f7';
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			$response = curl_exec($curl);
			$responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
			curl_close($curl);
			$cryptos = json_decode($response, true);
			// Empty table
			$query = "DELETE FROM $table";
			$stmt = $conn->prepare($query);
			$stmt->execute();
			// Prepare query
			$query = "INSERT INTO $table (name, symbol, price, max_supply, high, logo_url) VALUES (?, ?, ?, ?, ?, ?)";
			$stmt = $conn->prepare($query);
			// loop insert
			foreach ($cryptos as $i => $crypto) {
				$maxSupply = isset($crypto['max_supply'])?$crypto['max_supply']:null;
				$stmt->bind_param('ssdids', $crypto['name'], $crypto['symbol'], $crypto['price'], $maxSupply, $crypto['high'], $crypto['logo_url']);
				$stmt->execute();
			}	
			$stmt->close();
		}
		if (isset($_POST['search-crypto'])) {
			$keyword = trim(preg_replace('/[^a-zA-Z1-9\s]+$/i', '',$_POST['keyword']));
			$name 	= '%'.$keyword.'%';
			$stmt 	= $conn->prepare("select * from $table where name like ?");
			$stmt->bind_param("s", $name); 
			$stmt->execute();
			$result 	= $stmt->get_result(); 
			$cryptos = $result->fetch_all(MYSQLI_ASSOC);
		}
	}
	$conn->close();	
}
?>

<!doctype html>
<html>
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link href="dist/style.css" rel="stylesheet">
	</head>
	<body>
		<div class="max-w-6xl mx-auto">
			<div class="text-black p-3">
				<div class="border-b border-b-black p-3 mb-6">
					<h1 class="text-3xl font-bold">Bitoid week 3</h1>
				</div>
				<?php if($isPost): ?>
					<div class="mb-4">
						<form method="post" action="index.php" class="flex">
							<input name="keyword" class="border-solid border-l border-y border-sky-700 focus:outline-none px-3 py-1.5 rounded-l-lg grow" value="<?= $keyword??''?>">
							<button name="search-crypto" class="border-solid border border-sky-700 bg-sky-600 text-white px-3 py-1.5 rounded-r-lg flex-none" type="submit">Search crypto</button>
						</form>
					</div>
					<div>
						<?php if($cryptos): ?>
							<table class="border-collapse border border-slate-500 w-full">
								<thead>
									<tr>
										<th class="td">#</th>
										<th class="td">Logo</th>
										<th class="td">Name</th>
										<th class="td">Symbol</th>
										<th class="td">Price</th>
										<th class="td">Max supply</th>
										<th class="td">All time high</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach($cryptos as $i=>$crypto): ?>
										<tr class="hover:bg-yellow-100 ">
											<td class="td text-center"><?= $i + 1 ?></td>
											<td class="td"><img src="<?= $crypto['logo_url'] ?>" class="h-6 mx-auto"></td>
											<td class="td"><?= $crypto['name'] ?></td>
											<td class="td"><?= $crypto['symbol'] ?></td>
											<td class="td"><?= $crypto['price'] ?></td>
											<td class="td"><?= $crypto['max_supply']??null ?></td>
											<td class="td relative">
												<div class="z-10 relative"><?= $crypto['high'] ?></div>
												<div class="z-0 absolute bottom-0 left-0 top-0" style="width:<?=(int)($crypto['price']/$crypto['high']*100);?>%; background-color:rgba(<?=255-(int)($crypto['price']/$crypto['high']*255);?>,<?=(int)($crypto['price']/$crypto['high']*255);?>,0,.85)">
												</div>
											</td>
										</tr>	
									<?php endforeach; ?>					
								</tbody>
							</table> 
						<?php else: ?>
							Nothing found
						<?php endif; ?>
					</div>
				<?php else: ?>
					<div class="">
						<form method="post" action="index.php">
							<button class="border-solid border-2 border-sky-700 bg-sky-600 text-white px-3 py-2 rounded" name="get-data" type="submit">Get crypto data</button>
						</form>
					</div>
				<?php endif; ?>
			</div>
			<div>
			</div>
		</div>
	</body>
</html>