<?php
const URL_ROOT = 'http://www.jd.com';

$host = parse_url(URL_ROOT, PHP_URL_HOST) or die('Invalid URL_ROOT');
$url = URL_ROOT . $_SERVER['REQUEST_URI'];

// Request URL
$ch = curl_init($url) or die('curl_init failed.');

// Request Method
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $_SERVER['REQUEST_METHOD']);

// Request Headers
$headers = getallheaders();
$headers['Host'] = $host;
$ch_headers = array();
foreach ($headers as $name => $value)
    $ch_headers[] = "$name: $value";
curl_setopt($ch, CURLOPT_HTTPHEADER, $ch_headers);

// Request Body
curl_setopt($ch, CURLOPT_POSTFIELDS, file_get_contents("php://input"));

// Send
curl_setopt($ch, CURLOPT_VERBOSE, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch) or die('curl_exec failed.');
$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
curl_close($ch);

// Response Header
$header = substr($response, 0, $header_size);
$headers = explode("\r\n", $header);
foreach ($headers as $header)
    header($header);

// Response Body
$body = substr($response, $header_size);
echo $body;

?>
