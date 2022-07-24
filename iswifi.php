<?php
$company_name = geoip_isp_by_name($_SERVER['REMOTE_ADDR']);
$this_is_a_wifi_connection = is_this_wifi($company_name);
?>