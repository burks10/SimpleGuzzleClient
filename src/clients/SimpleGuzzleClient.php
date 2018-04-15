<?php

// autoload Guzzle
require_once __DIR__ . '/../../vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Command\Guzzle\Description;
use GuzzleHttp\Command\Guzzle\GuzzleClient;
use GuzzleHttp\Command\Guzzle\RequestLocation\QueryLocation;
use GuzzleHttp\Command\Guzzle\QuerySerializer\Rfc3986Serializer;
use GuzzleHttp\Command\Guzzle\Serializer;

/*
 * What can SimpleGuzzleClient do for you?
 * - Create an API Client based on a service description.
 * - Gives you control over all request options (http://docs.guzzlephp.org/en/stable/request-options.html)
 * - Allows you to easily add and remove middleware to your API Client
 * - Gives you control over how query parameters are serialized
 * - Make requests both synchronously and asynchronously
 */

class SimpleGuzzleClient extends GuzzleClient
{
	/**
	 * Creates an API client based on the following parameters
	 * @param $service_description_file: The absolute path to the json file describing the API
	 * @param $request_options: Any configurations needed to build the client
	 * @param $middlewares: Any middleware to be used with this client
	 * @param $querySerializer: Change the way query parameters are serialized (default: RFC3986)
	 */
	public static function create(
		$service_description_file,
		array $request_options=[],
		array $middlewares=[],
		QuerySerializerInterface $querySerializer = null
	)
	{

		// Load the service description file
		try {
			$service_description = new Description(
				(array)json_decode(file_get_contents($service_description_file), TRUE)
			);
		} catch (Exception $e) {
			echo "Error creating service description " . $e->getMessage();
		}

		// Push all the middleware to the HandlerStack then add to client configuration
		$client_config = $request_options;
		if (!empty($middlewares)) {
			$stack = HandlerStack::create();
			foreach ($middlewares as $middleware) {
				$stack->push($middleware);
			}
			$client_config['handler'] = $stack;
		}

		// Set the way query params will be serialized (removes indicies), and create the client.
		// TODO: there sounds like there is a response serializer too? Not sure how useful this would be
		if ($querySerializer === null) {
			$querySerializer = new Rfc3986Serializer(true);
		}
		$queryLocation = new QueryLocation('query', $querySerializer);
		$serializer = new Serializer($service_description, ['query' => $queryLocation]);
		$guzzleClient = new GuzzleClient(new Client($client_config), $service_description, $serializer);

		return $guzzleClient;
	}
}
