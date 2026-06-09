<?php
$max = date('Y-m-d');
$min = date("Y-m-d", strtotime("-7 days", strtotime($max)));

$min_expiry = date("Y-m-d");

$minDate = date("Y-m-d", strtotime("-30 days", strtotime($max)));

$min_date = date("Y-m-d", strtotime("-7 days", strtotime($max)));

$formatted_date = date('F j', strtotime($max));

$formatted_month = date('F', strtotime($max));

?>