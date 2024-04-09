<?php function update_60_coin() {
    global $coins,$wpdb;
	$url = 'https://api.coinlore.net/api/tickers/';
	$response = wp_remote_get($url);
	if (is_wp_error($response)) {
	} else {
		$body = wp_remote_retrieve_body($response);
		$data = json_decode($body, true);
		$gia_do = get_vnd_to_usd();
		$number = 1;
	    update_option('USDT',$number);
	    update_option('VNDTOUSD',$gia_do);
		
		foreach ($coins as $item => $value){
    		foreach ($data['data'] as $currency) {
                if ($currency['symbol'] === $item) {
                    
                    $number = $currency['price_usd'];
                    $randomNumbers = array();
                    $range = range(1, 60);
                    foreach ($range as $value) {
                        $randomOffset = array('-1', '1');
                        $randomIndex = array_rand($randomOffset);
                        $randomValue = $randomOffset[$randomIndex];
                        $valueRandom = rand(1,4);
                        $current = round($number + ($valueRandom * ($number / 1000) * $randomValue ), 2);
                        $number = $current;
                        $price_array = [];
                        $price_array[] = $number;
                        for ($i = 1; $i <= 3; $i++) {
                            $randomOffset = array(-1, 1);
                            $randomIndex = array_rand($randomOffset);
                            $randomValue = $randomOffset[$randomIndex];
                            $valueRandom = rand(1, 10);
                            $current_price = round($number + ($valueRandom * ($number / 1000) * $randomValue ), 2);
                            $price_array[] = $current_price;
                        }
                        $valueRandomVol = rand(200000,400000);
                        $price_array[] = $valueRandomVol;
                        $randomNumbers[] = $price_array;
                    }
                    foreach ($randomNumbers as $data_item){
                        $table_name = $wpdb->prefix . 'coin_table_'.$item;
                        $coin = $item;
    					$number = $data_item;
    					$tinhtrang = 'pending';
                        $wpdb->insert(
    						$table_name,
    						array(
    							'name' => wp_strip_all_tags($coin),
    							'value' => json_encode($number),
    							'status' => wp_strip_all_tags($tinhtrang)
    						)
    					);
                    }
                    break;
                }
            }    
		}
        
	}
}