<?php
	
function barcodeWB($days){ 	

	global $wb_api_key;

	$last2Week = date('Y-m-d\T00:00:00', strtotime("-". $days * 2 . " days"));
	$splitDate = date('Y-m-d\T00:00:00', strtotime("-". $days . " days"));
	
	
	//заказы
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, 'https://statistics-api.wildberries.ru/api/v1/supplier/orders?dateFrom=' . $last2Week);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array(
		'Authorization: '.$wb_api_key,
	));
	$out = curl_exec($curl);
	curl_close($curl);
	$orders = json_decode($out, true);
	//удалим отмененные заказы
	foreach ($orders as $subKey => $subArray) { 
		if ($subArray['cancelDate'] != '0001-01-01T00:00:00') {
			unset($orders[$subKey]);
		}
	}
	
	// цены
	$data = [
	'offset' 	=> 0,
	'limit' 	=> 1000,
	//'filterNmID'=> ''
	];
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, 'https://discounts-prices-api.wb.ru/api/v2/list/goods/filter?' . http_build_query($data));
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array(
		'Authorization: '.$wb_api_key,
	));
	$out = curl_exec($curl);
	curl_close($curl);
	$tmp = json_decode($out, true);
	$price = array();
	foreach ($tmp['data']['listGoods'] as $i) {
		$price[$i['nmID']] = $i['sizes'][0];	
	}
	

	//остатки
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, 'https://statistics-api.wildberries.ru/api/v1/supplier/stocks?dateFrom=2001-01-01');
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array(
		'Authorization: '.$wb_api_key,
	));
	$out = curl_exec($curl);
	curl_close($curl);
	$stocks = json_decode($out, true);
	$barcode = array();
	
	
	// считаем остатки
	foreach ($stocks as $items) {		
		if (!array_key_exists($items['barcode'], $barcode)) {
			$barcode[$items['barcode']] = array(
				'stock_WB' => array(), 
				'order_WB' => array(),
				'order_WB_old' => array(),
				'order_WB_ytd' => array(),
				'order_WB_price' => array(),
				'nmId'	=> ''
			);
		}		
		if (!array_key_exists($items['warehouseName'], $barcode[$items['barcode']]['stock_WB'])) {
			$barcode[$items['barcode']]['stock_WB'][$items['warehouseName']] = 0;
		}
		if ($barcode[$items['barcode']]['nmId'] == '') {
			$barcode[$items['barcode']]['nmId'] = $items['nmId'];
			$barcode[$items['barcode']]['order_WB_price'] = $price[$items['nmId']];	
		}
		
		$barcode[$items['barcode']]['stock_WB'][$items['warehouseName']] += $items['quantity'];
		if ($items['warehouseName'] == 'Коледино' || $items['warehouseName'] == 'Электросталь') {
			if (!array_key_exists('Москва', $barcode[$items['barcode']]['stock_WB'])) {
				$barcode[$items['barcode']]['stock_WB']['Москва'] = 0;
			}
			$barcode[$items['barcode']]['stock_WB']['Москва'] += $items['quantity'];	
		}
	};
	
	// считаем продажи
	foreach ($orders as $items) {
		if (!array_key_exists($items['barcode'], $barcode)) {
			$barcode[$items['barcode']] = array(
				'stock_WB' => array(), 
				'order_WB' => array(),
				'order_WB_old' => array(),
				'order_WB_ytd' => array(),
				'order_WB_price' => array(),
				'nmId'	=> ''
			);
		}
		if (strtotime($items['date']) >= strtotime('yesterday midnight') && strtotime($items['date']) <= strtotime('today midnight')) {
			if (!array_key_exists($items['warehouseName'], $barcode[$items['barcode']]['order_WB_ytd'])) {
				$barcode[$items['barcode']]['order_WB_ytd'][$items['warehouseName']] = 0;
			}
			$barcode[$items['barcode']]['order_WB_ytd'][$items['warehouseName']] += 1;
		} elseif (strtotime($items['date']) > strtotime($splitDate)) {
			if (!array_key_exists($items['warehouseName'], $barcode[$items['barcode']]['order_WB'])) {
				$barcode[$items['barcode']]['order_WB'][$items['warehouseName']] = 0;
			}
			$barcode[$items['barcode']]['order_WB'][$items['warehouseName']] += 1;
			if ($items['warehouseName'] == 'Коледино' || $items['warehouseName'] == 'Электросталь') {
				if (!array_key_exists('Москва', $barcode[$items['barcode']]['order_WB'])) {
					$barcode[$items['barcode']]['order_WB']['Москва'] = 0;
				}
				$barcode[$items['barcode']]['order_WB']['Москва'] += 1;	
			}	
		} else {
			if (!array_key_exists($items['warehouseName'], $barcode[$items['barcode']]['order_WB_old'])) {
				$barcode[$items['barcode']]['order_WB_old'][$items['warehouseName']] = 0;
			}
			$barcode[$items['barcode']]['order_WB_old'][$items['warehouseName']] += 1;
			if ($items['warehouseName'] == 'Коледино' || $items['warehouseName'] == 'Электросталь') {
				if (!array_key_exists('Москва', $barcode[$items['barcode']]['order_WB_old'])) {
					$barcode[$items['barcode']]['order_WB_old']['Москва'] = 0;
				}
				$barcode[$items['barcode']]['order_WB_old']['Москва'] += 1;	
			}
		}	
	};
	return $barcode;
};


	
?>	























