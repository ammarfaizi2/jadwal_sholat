<?php
header("Content-type:application/json");
$ch = curl_init("https://jadwalsholat.pkpu.or.id/");
curl_setopt_array($ch, [
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_SSL_VERIFYHOST => false,
		CURLOPT_FOLLOWLOCATION => true
	]);
$a = curl_exec($ch);
$err = curl_error($ch) and die(json_encode(["error" => $err],128));
curl_close($ch);
$b = explode('<b>Isya</b>', $a);
$b = explode('<table width="100%">', $b[1], 2);
$jadwal = [];
foreach (explode("\n",$b[0]) as $val) {
	$c = explode('" align="center"><td><b>', $val) and 
	(count($c)>1) and (function(&$jadwal) use ($c){
		$c = explode('</b></td><td>', $c[1]);
		$d = explode('</td><td>', $c[1]);
		$jadwal[$c[0]] = [
			"subuh" => $d[0],
			"dzuhur" => $d[1],
			"ashar" => $d[2],
			"magrib" => $d[3],
			"isya" => substr($d[4],0,5),
		];
	})($jadwal);
}
print($a = json_encode($jadwal, 128));
file_put_contents("jadwal_".time().".json", $a, LOCK_EX);