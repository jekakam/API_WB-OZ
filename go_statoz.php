<?php 

function barcodeOZStat(){ 
	$sku = array(
		'day7_14' => array(),
	);
	$offset = 0;
	$day14_8 = api_order_stat(
		date('Y-m-d', strtotime('-14 days')), 
		date('Y-m-d', strtotime('-8 days')), 
		$offset
	);
	$day7_1 = api_order_stat(
		date('Y-m-d', strtotime('-7 days')), 
		date('Y-m-d'), 
		$offset
	);
	$orders = array();
	foreach ($day14_8 as $i) {
		$orders['day14_8'][$i['dimensions'][0]['id']] = $i['metrics'][0];	
	}
	foreach ($day7_1 as $i) {
		$orders['day7_1'][$i['dimensions'][0]['id']] = $i['metrics'][0];	
	}
	
	$akey1= array_keys($orders['day7_1']);
	$akey2= array_keys($orders['day14_8']);
	$allkey = array_unique(array_merge($akey1,$akey2));
	foreach ($allkey as $i) {
		$sku['day7_14'][$i] = array(
			isset($orders['day7_1'][$i]) ? $orders['day7_1'][$i] : 0,
			isset($orders['day14_8'][$i]) ? $orders['day14_8'][$i] : 0
		);
	}
	return $sku;
}




function api_order_stat($fromdata, $todata, $offset){
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
							'key' 	=> 'sku',
							'order' => 'ASC',
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



?>