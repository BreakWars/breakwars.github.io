<?php
	const SECRET_KEY = 'dd505646d5fb876662ab9e08f54dc764';

	$data = $_GET;

	$signature = getFormSignature($data['nickname'], "Покупка JCoin'ов", $data['sum'], SECRET_KEY);
	 
	$link = "https://unitpay.ru/pay/184091-0f1cc?sum=" . $data['sum'] . "&account=" . $data['nickname'] . "&desc=Покупка JCoin'ов" . "&signature=" . $signature;

	header("Location: " . $link);

	function getFormSignature($account, $desc, $sum, $secretKey) {
	    $hashStr = $account.'{up}'.$desc.'{up}'.$sum.'{up}'.$secretKey;
	    return hash('sha256', $hashStr);
	}
?>
