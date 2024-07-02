<?php 

function barcodeOZ($days){ 
	$barcode = array();
	//заказы
	$offset = 0;
	$q_ytd = api_order(
		date('Y-m-d\T00:00:00', strtotime("-1 days")), 
		date('Y-m-d\T00:00:00'), 
		$offset
	);
	$q_cur = api_order(
		date('Y-m-d\T00:00:00', strtotime("-" . $days . " days")), 
		date('Y-m-d\T00:00:00'), 
		$offset
	);
	$q_old = api_order(
		date('Y-m-d\T00:00:00', strtotime("-" . $days * 2 . " days")), 
		date('Y-m-d\T00:00:00', strtotime("-" . $days + 1 . " days")), 
		$offset
	);
	$orders = array();
	foreach ($q_ytd as $i) {
		$orders['ytd'][$i['dimensions'][0]['id']] = $i['metrics'][0];	
	}
	foreach ($q_cur as $i) {
		$orders['cur'][$i['dimensions'][0]['id']] = $i['metrics'][0];	
	}	
	foreach ($q_old as $i) {
		$orders['old'][$i['dimensions'][0]['id']] = $i['metrics'][0];	
	}	

	// цены
	$t_price = api_price();
	$price = array();
	foreach ($t_price as $i) {
		$price[$i['offer_id']] = $i['price'];	
	}
	
	//остатки
	$stocks = api_stock();

	foreach ($stocks as $items) {	
		if (!array_key_exists($items['item_code'], $barcode)) {
			$barcode[$items['item_code']] = array(
				'stock_WB' => array(),
				'order_WB' => array(),
				'order_WB_old' => array(),
				'order_WB_ytd' => array(),
				'order_WB_price' => array(),
			);
		}
	if (!array_key_exists($items['warehouse_name'], $barcode[$items['item_code']]['stock_WB'])) {
			$barcode[$items['item_code']]['stock_WB'][$items['warehouse_name']] = 0;
		}
		$barcode[$items['item_code']]['stock_WB'][$items['warehouse_name']] += $items['free_to_sell_amount'];
		
		if (array_key_exists($items['sku'], $orders['cur'])) {
			$barcode[$items['item_code']]['order_WB'][0] = $orders['cur'][$items['sku']];
		}	
		if (array_key_exists($items['sku'], $orders['old'])) {
			$barcode[$items['item_code']]['order_WB_old'][0] = $orders['old'][$items['sku']];
		}
		
		if (array_key_exists($items['sku'], $orders['ytd'])) {
			$barcode[$items['item_code']]['order_WB_ytd'][0] = $orders['ytd'][$items['sku']];
		}

		if (array_key_exists($items['item_code'], $price)) {
			$barcode[$items['item_code']]['order_WB_price'] = $price[$items['item_code']];
			
		}
	};
	return $barcode;
}

function api_stock(){
	global $client_id;
	global $api_key;
	$headers = array(
        'Client-Id: '.$client_id,
        'Api-Key: '.$api_key,
        'Content-Type: application/json'
	);
	$data = array(
		'limit' 	=>1000,
		'offset'	=> 0,
		'warehouse_type' => 'ALL',
	);
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, 'https://api-seller.ozon.ru/v2/analytics/stock_on_warehouses');
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array(
		'Client-Id: '.$client_id,
        'Api-Key: '.$api_key,
        'Content-Type: application/json'
	));
	curl_setopt($curl, CURLOPT_POSTFIELDS,json_encode($data));
	$out = curl_exec($curl);
	curl_close($curl);
	$stocks = json_decode($out, true);
	return $stocks['result']['rows'];	
}


function api_order($fromdata, $todata, $offset){
	global $client_id;
	global $api_key;
	$headers = array(
        'Client-Id: '.$client_id,
        'Api-Key: '.$api_key,
        'Content-Type: application/json'
    );
	$data = array(
		'date_from' => $fromdata,
		'date_to' 	=> $todata,
		'metrics' 	=> array('ordered_units'),
		'dimension' => array('sku'),
		'sort'		=> array(
						array(
							'key' 	=> 'ordered_units',
							'order' => 'DESC',
						),	
					),
		'limit' 	=> 1000,
		'offset'	=> $offset
	);
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, 'https://api-seller.ozon.ru/v1/analytics/data');
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array(
		'Client-Id: '.$client_id,
        'Api-Key: '.$api_key,
        'Content-Type: application/json'
	));
	curl_setopt($curl, CURLOPT_POSTFIELDS,json_encode($data));
	$out = curl_exec($curl);
	curl_close($curl);
	$tmp = json_decode($out, true);
	sleep(30);
	return $tmp['result']['data'];	
}


function api_price(){
	global $client_id;
	global $api_key;
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, 'https://api-seller.ozon.ru/v4/product/info/prices');
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array(
		'Client-Id: '.$client_id,
        'Api-Key: '.$api_key,
        'Content-Type: application/json'
	));
	$data = [
		'filter' 	=> [
			"visibility" => "ALL",
		],
		'last_id'	=> '',
		'limit' 	=>1000,
		
	];
	curl_setopt($curl, CURLOPT_POSTFIELDS,json_encode($data));
	$out = curl_exec($curl);
	curl_close($curl);
	$tmp = json_decode($out, true);
	return $tmp['result']['items'];

}	
	

?>