<?php

	header('Content-type: text/html; charset=utf8');
	DEFINE("DEBUG", false); 
	$wd = dirname(__FILE__);
	
	if (file_exists($wd . 'barcodeWB.txt'))  unlink($wd . 'barcodeWB.txt');
    	if (file_exists($wd . 'barcodeOZ.txt'))  unlink($wd . 'barcodeOZ.txt');
	/* 
 	*	библиотека для работы с гугл таблицами и файл авторизации
	*	require_once  __DIR__ . '/vendor/autoload.php';
	*	$googleAccountKeyFilePath = __DIR__ . '/serviceacc.json';
	*/
	putenv( 'GOOGLE_APPLICATION_CREDENTIALS=' . $googleAccountKeyFilePath );
	$wb_api_key = '==== your API_KEY ====';
	$client_id = '==== your OZON client_id ====';
	$api_key = '==== your OZON api_key ====';
	$count_days = 14;
	$spreadsheetId_1 = '1DzKBvn-KzaAu30AqjMkgj1XFUB1_zmToDHAIMw_igCs';
	$spreadsheetId_2 = '1V3g8MAzLrSwE3uKtPda5Vemc8uanNf26RjwSky3i_eo';
	$spreadsheetId_3 = '1NmR0e7d3VjCtDgJYj6ldZFfJImlA_skxgsDnTTLjauQ';
			
	require  __DIR__ . '/go_wb.php';
	require  __DIR__ . '/go_oz.php';
	require  __DIR__ . '/go_statoz.php';
	
	$barcodeWB 		= barcodeWB($count_days);
	$barcodeOZ 		= barcodeOZ($count_days);
	$barcodeOZStat 	= barcodeOZStat();
				
	SaveFile_1($barcodeWB, array('B','E','U'), 'discountedPrice');
	SaveFile_1($barcodeOZ, array('J','M','V'), 'marketing_price');
	SaveFile_2($barcodeWB);
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
		
		$body = new \Google\Service\Sheets\BatchUpdateValuesRequest(array(
			'valueInputOption' => 'USER_ENTERED',
			'data' => $dataGT
		));
		$result = $service->spreadsheets_values->batchUpdate($spreadsheetId_3, $body);		
	};
	
?>
