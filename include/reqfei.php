<?php
		$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
		if ($hostname != "fei.static.gvt.net.br" && $hostname != "200-232-90-170.customer.tdatabrasil.net.br" && $_SERVER['REMOTE_ADDR'] != "127.0.0.1"){
			header('Location: index.php');
		}
?>