<?php

	header('Content-type: text/html; charset=utf8');
	DEFINE("DEBUG", false); 
	$wd = dirname(__FILE__);
	
	if (file_exists($wd . 'barcodeWB.txt'))  unlink($wd . 'barcodeWB.txt');
    if (file_exists($wd . 'barcodeOZ.txt'))  unlink($wd . 'barcodeOZ.txt');
	
	require_once  __DIR__ . '/vendor/autoload.php';
	$googleAccountKeyFilePath = __DIR__ . '/serviceacc.json';
	putenv( 'GOOGLE_APPLICATION_CREDENTIALS=' . $googleAccountKeyFilePath );
	$wb_api_key = 'eyJhbGciOiJFUzI1NiIsImtpZCI6IjIwMjQwNTA2djEiLCJ0eXAiOiJKV1QifQ.eyJlbnQiOjEsImV4cCI6MTczMzg2NzEzMywiaWQiOiJiN2Y2MjdkZi03MDcyLTQwMTctYmI1OC0xNWY3MDRkMTM5NzEiLCJpaWQiOjMwNDc3MDkwLCJvaWQiOjMzMDE0LCJzIjoxMDczNzQ1OTE4LCJzaWQiOiIwNzM2ZDI2NS0xODEyLTVhMWYtYjIwOC1lNTdjMDc1ZWI1ZmYiLCJ0IjpmYWxzZSwidWlkIjozMDQ3NzA5MH0.g2O27GkF70PL34GULJmhd2CdeRY2cwwvr4BJzHKfGPEx6TjiB6qcb615AiVCHB_7TPReXuZiI4lex0G3BRrANQ';
	$client_id = '6142';
	$api_key = '47be62b7-f19a-4718-bbca-0474fd77c55d';
	$count_days = 14;
	$spreadsheetId_1 = '1DzKBvn-KzaAu30AqjMkgj1XFUB1_zmToDHAIMw_igCs';
	$spreadsheetId_2 = '1V3g8MAzLrSwE3uKtPda5Vemc8uanNf26RjwSky3i_eo';
	$spreadsheetId_3 = '1NmR0e7d3VjCtDgJYj6ldZFfJImlA_skxgsDnTTLjauQ';
			
//	require  __DIR__ . '/go_wb.php';
//	require  __DIR__ . '/go_oz.php';
	require  __DIR__ . '/go_statoz.php';
	
//	$barcodeWB 		= barcodeWB($count_days);
//	$barcodeOZ 		= barcodeOZ($count_days);
	$barcodeOZStat 	= barcodeOZStat();
				
//	SaveFile_1($barcodeWB, array('B','E','U'), 'discountedPrice');
//	SaveFile_1($barcodeOZ, array('J','M','V'), 'marketing_price');
//	SaveFile_2($barcodeWB);

/*
$barcodeOZStat=Array("day7_14"=>Array(
"200833487"=>Array("0"=>209,"1"=>211),
"200824536"=>Array("0"=>222,"1"=>171),
"1268409021"=>Array("0"=>155,"1"=>157),
"200494187"=>Array("0"=>140,"1"=>113),
"200506869"=>Array("0"=>100,"1"=>104),
"200832497"=>Array("0"=>70,"1"=>87),
"199043693"=>Array("0"=>62,"1"=>82),
"1350864515"=>Array("0"=>92,"1"=>82),
"1268397202"=>Array("0"=>91,"1"=>80),
"407364015"=>Array("0"=>78,"1"=>73),
"200588719"=>Array("0"=>61,"1"=>68),
"200832473"=>Array("0"=>67,"1"=>67),
"1269313444"=>Array("0"=>68,"1"=>65),
"200832419"=>Array("0"=>78,"1"=>64),
"200490428"=>Array("0"=>68,"1"=>63),
"568823905"=>Array("0"=>84,"1"=>62),
"200490421"=>Array("0"=>36,"1"=>61),
"1566525036"=>Array("0"=>23,"1"=>54),
"296757835"=>Array("0"=>50,"1"=>53),
"200494185"=>Array("0"=>50,"1"=>52),
"200833489"=>Array("0"=>58,"1"=>49),
"259210660"=>Array("0"=>84,"1"=>48),
"1480008539"=>Array("0"=>49,"1"=>46),
"215034185"=>Array("0"=>41,"1"=>41),
"149952536"=>Array("0"=>29,"1"=>38),
"272542981"=>Array("0"=>30,"1"=>37),
"201036466"=>Array("0"=>32,"1"=>35),
"522239943"=>Array("0"=>45,"1"=>34),
"272841668"=>Array("0"=>18,"1"=>33),
"275683221"=>Array("0"=>38,"1"=>33),
"907686625"=>Array("0"=>42,"1"=>33),
"200544691"=>Array("0"=>25,"1"=>32),
"1091522027"=>Array("0"=>16,"1"=>32),
"568832346"=>Array("0"=>32,"1"=>30),
"1180997620"=>Array("0"=>14,"1"=>30),
"275672308"=>Array("0"=>30,"1"=>29),
"200588641"=>Array("0"=>30,"1"=>28),
"896255855"=>Array("0"=>20,"1"=>28),
"151395241"=>Array("0"=>33,"1"=>26),
"299814618"=>Array("0"=>25,"1"=>26),
"787774488"=>Array("0"=>22,"1"=>26),
"201036453"=>Array("0"=>37,"1"=>25),
"1024936618"=>Array("0"=>14,"1"=>25),
"1330773998"=>Array("0"=>1,"1"=>25),
"1480213739"=>Array("0"=>32,"1"=>25),
"272543067"=>Array("0"=>30,"1"=>24),
"275684472"=>Array("0"=>32,"1"=>24),
"646576936"=>Array("0"=>28,"1"=>24),
"646578015"=>Array("0"=>20,"1"=>24),
"200830473"=>Array("0"=>16,"1"=>23),
"200544677"=>Array("0"=>18,"1"=>22),
"299856241"=>Array("0"=>33,"1"=>22),
"200490424"=>Array("0"=>20,"1"=>21),
"275693814"=>Array("0"=>10,"1"=>21),
"200506880"=>Array("0"=>21,"1"=>20),
"200832409"=>Array("0"=>26,"1"=>20),
"275684104"=>Array("0"=>18,"1"=>20),
"200490412"=>Array("0"=>22,"1"=>18),
"522231261"=>Array("0"=>8,"1"=>18),
"522239455"=>Array("0"=>18,"1"=>18),
"1090058358"=>Array("0"=>16,"1"=>18),
"200525643"=>Array("0"=>13,"1"=>17),
"200830483"=>Array("0"=>16,"1"=>17),
"259198213"=>Array("0"=>25,"1"=>17),
"151455096"=>Array("0"=>18,"1"=>16),
"200832405"=>Array("0"=>17,"1"=>16),
"269876764"=>Array("0"=>9,"1"=>16),
"149964920"=>Array("0"=>2,"1"=>15),
"200832407"=>Array("0"=>19,"1"=>15),
"1329517775"=>Array("0"=>8,"1"=>15),
"1350819702"=>Array("0"=>46,"1"=>15),
"151454186"=>Array("0"=>10,"1"=>14),
"275775574"=>Array("0"=>16,"1"=>14),
"998835269"=>Array("0"=>20,"1"=>13),
"1342998416"=>Array("0"=>14,"1"=>13),
"1398225895"=>Array("0"=>13,"1"=>13),
"152894615"=>Array("0"=>28,"1"=>12),
"200588599"=>Array("0"=>10,"1"=>12),
"1398060900"=>Array("0"=>11,"1"=>12),
"1398209895"=>Array("0"=>18,"1"=>12),
"200832425"=>Array("0"=>18,"1"=>11),
"200544685"=>Array("0"=>16,"1"=>10),
"272543060"=>Array("0"=>7,"1"=>10),
"1398221618"=>Array("0"=>7,"1"=>10),
"313930265"=>Array("0"=>7,"1"=>9),
"804948827"=>Array("0"=>10,"1"=>9),
"823238148"=>Array("0"=>8,"1"=>9),
"1317411099"=>Array("0"=>0,"1"=>9),
"299806982"=>Array("0"=>11,"1"=>8),
"1330637594"=>Array("0"=>6,"1"=>8),
"1343004125"=>Array("0"=>7,"1"=>8),
"1398062189"=>Array("0"=>9,"1"=>8),
"200544706"=>Array("0"=>7,"1"=>7),
"1330793641"=>Array("0"=>6,"1"=>7),
"1474527762"=>Array("0"=>7,"1"=>7),
"215275749"=>Array("0"=>5,"1"=>6),
"250118115"=>Array("0"=>9,"1"=>6),
"522240317"=>Array("0"=>1,"1"=>6),
"712812173"=>Array("0"=>4,"1"=>6),
"804949859"=>Array("0"=>8,"1"=>6),
"1398236620"=>Array("0"=>5,"1"=>6),
"275710578"=>Array("0"=>9,"1"=>5),
"522241036"=>Array("0"=>11,"1"=>5),
"561540038"=>Array("0"=>2,"1"=>5),
"712812883"=>Array("0"=>8,"1"=>5),
"1330946631"=>Array("0"=>1,"1"=>5),
"1398059946"=>Array("0"=>11,"1"=>5),
"1549305427"=>Array("0"=>6,"1"=>5),
"149964853"=>Array("0"=>7,"1"=>4),
"217599462"=>Array("0"=>4,"1"=>4),
"1317383964"=>Array("0"=>5,"1"=>4),
"1398231829"=>Array("0"=>5,"1"=>4),
"1549306182"=>Array("0"=>2,"1"=>4),
"215749508"=>Array("0"=>3,"1"=>3),
"700820620"=>Array("0"=>1,"1"=>3),
"804949213"=>Array("0"=>8,"1"=>3),
"804949650"=>Array("0"=>1,"1"=>3),
"1091046966"=>Array("0"=>2,"1"=>3),
"1398202712"=>Array("0"=>5,"1"=>3),
"215276165"=>Array("0"=>3,"1"=>2),
"700821571"=>Array("0"=>0,"1"=>2),
"712811377"=>Array("0"=>2,"1"=>2),
"1311753111"=>Array("0"=>0,"1"=>2),
"700821131"=>Array("0"=>0,"1"=>1),
"712810443"=>Array("0"=>3,"1"=>1),
"1315742270"=>Array("0"=>2,"1"=>1),
"200490402"=>Array("0"=>0,"1"=>0),
"200494177"=>Array("0"=>0,"1"=>0),
"200830504"=>Array("0"=>0,"1"=>0),
"201165507"=>Array("0"=>0,"1"=>0),
"201544384"=>Array("0"=>0,"1"=>0),
"202188558"=>Array("0"=>'',"1"=>0),
"202188720"=>Array("0"=>0,"1"=>0),
"202188772"=>Array("0"=>0,"1"=>0),
"202250510"=>Array("0"=>0,"1"=>0),
"202250534"=>Array("0"=>0,"1"=>0),
"215034047"=>Array("0"=>0,"1"=>0),
"221238276"=>Array("0"=>0,"1"=>0),
"221238952"=>Array("0"=>0,"1"=>0),
"221354368"=>Array("0"=>0,"1"=>0),
"258987136"=>Array("0"=>0,"1"=>0),
"258991776"=>Array("0"=>0,"1"=>0),
"700814861"=>Array("0"=>0,"1"=>0),
"700815427"=>Array("0"=>0,"1"=>0),
"700817272"=>Array("0"=>0,"1"=>0),
"700818150"=>Array("0"=>0,"1"=>0),
"700819273"=>Array("0"=>0,"1"=>0),
"711277355"=>Array("0"=>0,"1"=>0),
"712808547"=>Array("0"=>0,"1"=>0),
"712812093"=>Array("0"=>0,"1"=>0),
"712855500"=>Array("0"=>0,"1"=>0),
"726450042"=>Array("0"=>0,"1"=>0),
"726450555"=>Array("0"=>0,"1"=>0),
"794407767"=>Array("0"=>0,"1"=>0),
"1308272600"=>Array("0"=>0,"1"=>0),
"1308281101"=>Array("0"=>0,"1"=>0),
"1311853997"=>Array("0"=>0,"1"=>0),
"1315274277"=>Array("0"=>0,"1"=>0),
"1315404903"=>Array("0"=>0,"1"=>0),
"1330660074"=>Array("0"=>0,"1"=>0),
"1598489107"=>Array("0"=>'',"1"=>0)
));
*/

	SaveFile_3($barcodeOZStat);
	
	
	
//	echo "<h1>Обновление файла завершено</h1>";









	
	if (DEBUG) {  
		file_put_contents('barcodeWB.txt', "\xEF\xBB\xBF" . print_r($barcodeWB, true)); 
		file_put_contents('barcodeOZ.txt', "\xEF\xBB\xBF" . print_r($barcodeOZ, true));
		echo '<div><a href="/wb/barcodeWB.txt" target="blank_">barcodeWB.txt</div>';
		echo '<div><a href="/wb/barcodeOZ.txt" target="blank_">barcodeOZ.txt</div>';
	}

	function SaveFile_1 ($q,$c,$m){
		global $spreadsheetId_1;
		global $count_days;
		// Подключаемся к API
		$client = new Google_Client();
		$client->useApplicationDefaultCredentials();
		$client->addScope( 'https://www.googleapis.com/auth/spreadsheets' );
		$service = new Google_Service_Sheets( $client );
		$response = $service->spreadsheets->get($spreadsheetId_1);
		$range = 'B4:AC';
		$response = $service->spreadsheets_values->get($spreadsheetId_1, $range);
		$values  = $response->getValues();
		$dataGT = array();
		foreach ($values as $n => $i) {
			if ( array_key_exists($i[27], $q) && !empty($i[27])) { 
				$data = $q[$i[27]];
			} else {
				continue;
			}
			
			array_key_exists("Москва", $data['stock_WB']) ? $stock_WB_msk = $data['stock_WB']['Москва'] : $stock_WB_msk = 0;
			array_key_exists("Москва", $data['order_WB']) ? $order_WB_msk = $data['order_WB']['Москва'] : $order_WB_msk = 0;
			array_key_exists("Москва", $data['order_WB_old']) ? $order_WB_old_msk = $data['order_WB_old']['Москва'] : $order_WB_old_msk = 0;
			
			$sum_stock = array_sum($data['stock_WB']) - $stock_WB_msk;
			$order_speed_cur = (array_sum($data['order_WB']) - $order_WB_msk) / $count_days;
			$order_speed_old = (array_sum($data['order_WB_old']) - $order_WB_old_msk) / $count_days;
			$order_ytd = array_sum($data['order_WB_ytd']);
			$price = $data['order_WB_price'][$m];	
		
			$val = array(
				array(
					number_format($order_speed_old, 2, ',', ''),
					number_format($order_speed_cur, 2, ',', ''),
					$order_ytd,	
					$sum_stock
				)
			);
			$range = $c[0] . $n + 4 . ':'. $c[1] . $n + 4;
			array_push(
				$dataGT,
				new \Google\Service\Sheets\ValueRange(array(
					'range' => $range,
					'values' =>  $val
				))
			);
			$val = array(
				array(
					number_format($price, 2, ',', '')
				)
			);
			$range = $c[2] . $n + 4;
			array_push(
				$dataGT,
				new \Google\Service\Sheets\ValueRange(array(
					'range' => $range,
					'values' =>  $val
				))
			);
		}	
		$body = new \Google\Service\Sheets\BatchUpdateValuesRequest(array(
			'valueInputOption' => 'USER_ENTERED',
			'data' => $dataGT
		));
		$result = $service->spreadsheets_values->batchUpdate($spreadsheetId_1, $body);		
	};	
	
	
	function SaveFile_2 ($q){	
		global $spreadsheetId_2;
		// Подключаемся к API
		$client = new Google_Client();
		$client->useApplicationDefaultCredentials();
		$client->addScope( 'https://www.googleapis.com/auth/spreadsheets' );
		$service = new Google_Service_Sheets( $client );

		// ID таблицы
		$response = $service->spreadsheets->get($spreadsheetId_2);
		$range = 'Лист1!A3:CB';
		$response = $service->spreadsheets_values->get($spreadsheetId_2, $range);
		$values  = $response->getValues();		
		$dataGT = array();
		foreach ($values as $n => $i) {	
			if (array_key_exists($i[0], $q)) {
				$data = $q[$i[0]];
				foreach ($data['stock_WB'] as $wh => $qtt) {
					switch ($wh) {
						case 'Казань' : $range = 'C' . $n + 3 . ':D' . $n + 3;break;
						case 'Москва' : $range = 'F' . $n + 3 . ':G' . $n + 3;break;
						case 'Краснодар' : $range = 'I' . $n + 3 . ':J' . $n + 3;break;
						case 'Санкт-Петербург Шушары' : $range = 'L' . $n + 3 . ':M' . $n + 3;break;
						case 'Екатеринбург - Перспективный 12' : $range = 'O' . $n + 3 . ':P' . $n + 3;break;
						case 'Новосибирск' : $range = 'R' . $n + 3 . ':S' . $n + 3;break;
						case 'Алексин' : $range = 'U' . $n + 3 . ':V' . $n + 3;break;
						case 'Атакент' : $range = 'X' . $n + 3 . ':Y' . $n + 3;break;
						default: $range = 0;;
					}
					if ($range == 0) continue;
					if (array_key_exists($wh, $data['order_WB'])) {
						$order_speed = $data['order_WB'][$wh] / 7;
					} else {
						$order_speed = 0;
					}
					$val = 	array(
								array(
									$qtt, 
									number_format($order_speed, 2, ',', ''),
								)
							);
					array_push(
						$dataGT,
						new \Google\Service\Sheets\ValueRange(array(
							'range' => $range,
							'values' =>  $val
						))
					);
				}	
			}			
		}
		$body = new \Google\Service\Sheets\BatchUpdateValuesRequest(array(
			'valueInputOption' => 'USER_ENTERED',
			'data' => $dataGT
		));
		$result = $service->spreadsheets_values->batchUpdate($spreadsheetId_2, $body);		
	};


	function SaveFile_3 ($q){	
		global $spreadsheetId_3;
		// Подключаемся к API
		$client = new Google_Client();
		$client->useApplicationDefaultCredentials();
		$client->addScope( 'https://www.googleapis.com/auth/spreadsheets' );
		$service = new Google_Service_Sheets( $client );

		// ID таблицы
		$response = $service->spreadsheets->get($spreadsheetId_3);
		$range = 'Лист1!A3:Y';
		$response = $service->spreadsheets_values->get($spreadsheetId_3, $range);
		$values  = $response->getValues();		
		$dataGT = array();
		foreach ($values as $n => $i) {	
			if (count($i) < 25 ) continue;
			if (array_key_exists($i[24], $q['day7_14'])) {
				$qtt = $q['day7_14'][$i[24]];
				$range = 'P' . $n + 3 . ':Q' . $n + 3;
				if (isset($qtt[0])) {
					$ordered_units1_7 = $qtt[0];
				} else {
					$ordered_units1_7 = 0;
				}
				if (isset($qtt[1])) {
					$ordered_units8_14 = $qtt[1];
				} else {
					$ordered_units8_14 = 0;
				}
				$val = 	array(
					array(
						$ordered_units1_7, 
						$ordered_units8_14,
					)
				);	
				array_push(
					$dataGT,
					new \Google\Service\Sheets\ValueRange(array(
						'range' => $range,
						'values' =>  $val
					))
				);
							
			}
		}
		
		
/*
					
		echo '<pre>';
		print_r($q['day7_14'][$i[24]]);
		echo '</pre>';	
				
*/
		
		
		$body = new \Google\Service\Sheets\BatchUpdateValuesRequest(array(
			'valueInputOption' => 'USER_ENTERED',
			'data' => $dataGT
		));
		$result = $service->spreadsheets_values->batchUpdate($spreadsheetId_3, $body);		
	};
	
?>
