<?php
require_once __DIR__ . '/clients/APIClientFactory.php';

// Instantiate API Client
$loginAPIClient = APIClientFactory::getHttpBinAPIClient();

// Synchronous GET's
try {
	$response = $loginAPIClient->getGETData(['foo' => 123, 'bar' => 43432]);
	echo json_encode($response->toArray()) . '<br>';
} catch (\Exception $e) {
	echo $e->getMessage();
}

try {
	$response = $loginAPIClient->getIP();
	echo json_encode($response->toArray()) . '<br>';
} catch (\Exception $e) {
	echo $e->getMessage();
}

// Asynchronous GET
try {
	$promise = $loginAPIClient->getGETDataAsync()->then(
		function($response) {
			echo json_encode($response->toArray()) . '<br>';
		}
	);
	$promise->wait();
} catch (\Exception $e) {
	echo $e->getMessage();
}
