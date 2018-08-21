<?php

$lokalizacja = $_GET["name"];

$ch = curl_init ("http://www.parkrun.pl/" .$lokalizacja. "/rezultaty/historiabiegu/");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$page = curl_exec($ch);

$dom = new DOMDocument();
libxml_use_internal_errors(true);
$dom->loadHTML($page);
libxml_clear_errors();
$xpath = new DOMXpath($dom);

$keys = array();	
$data = array();
$output = array();

$table_cols = $xpath->query('//table[@id="results"]/thead/tr[2]');

$table_rows = $xpath->query('//table[@id="results"]/tbody/tr');

foreach($table_cols as $col => $tr) {
	
	foreach($tr->childNodes as $th) {
		$keys[$col][] = preg_replace('~[\r\n]+~', '', trim($th->nodeValue));					
		
	}
	
	$keys[$col] = array_values(array_filter($keys[$col]));

}

foreach($table_rows as $row => $tr) {
    foreach($tr->childNodes as $td) {
        $data[$row][] = preg_replace('~[\r\n]+~', '', trim($td->nodeValue));
		
    }
	
	$data[$row] = array_values(array_filter($data[$row]));
	$output[$row] = array_combine($keys[0],$data[$row]);
}


header('Content-Type: application/json');
echo json_encode($output);



?>