<?php

require_once __DIR__ . '/SimpleGuzzleClient.php';

class APIClientFactory
{
	private static $serviceDescriptionPath = __DIR__ . '/service_descriptions/service.json';
	private static $requestOptions = [
		'headers' => [
			'Content-Type' => 'application/json',
			'Accept' => 'application/json',
		],
	];

	public static function getHttpBinAPIClient() {
		return SimpleGuzzleClient::create(APIClientFactory::$serviceDescriptionPath, APIClientFactory::$requestOptions);
	}
}
