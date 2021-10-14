<?php
	try {
		$dbe = new PDO(
			'mysql:host=mc.jcraft.su;dbname=jcraft_economy;charset=UTF8',
			'root',
				'TZYzXZdAVyqyO8gn'
		);
		$dbe->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch (PDOException $e) {}

	const SECRET_KEY = 'dd505646d5fb876662ab9e08f54dc764';

	$data = $_GET;
	if (empty($data)) {
		header('Location: /home');
		die();
	}

	$sql = "SELECT buy FROM `economy_course` ORDER BY date DESC LIMIT 1";
	$tmp = $dbe->query($sql);
	$dt = $tmp->fetch();
	$multiplier = $dt[0];

	$params = $data['params'];
	$nickname = $params['account'];
	$count = $params['orderSum'] * $multiplier;
	$unitpayId = $params['unitpayId'];

	$signature = getSignature($data['method'], $params, SECRET_KEY);

	if ($data['method'] == 'pay') {
		if ($signature != $params['signature']) {
			echo 'Платеж отменен.';
		}
		$sql = "INSERT INTO economy_donate (nickname,count,unitpayId) VALUES ('" . $nickname . "','" . $count . "','" . $unitpayId . "');";
		$dbe->query($sql);
		$sql = "INSERT INTO economy (name,balance) VALUES ('" . $nickname . "','" . $count . "') ON DUPLICATE KEY UPDATE balance = balance + " . $count . ";";
		$dbe->query($sql);
	}

	echo json_encode(array('result' => array('message' => 'Запрос успешно обработан',),));

	function getSignature($method, array $params, $secretKey) {
	    ksort($params);
	    unset($params['sign']);
	    unset($params['signature']);
	    array_push($params, $secretKey);
	    array_unshift($params, $method);
	    return hash('sha256', join('{up}', $params));
	}
?>
