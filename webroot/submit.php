<?php
/**
 * This file needs to:
 * 1. Pull in the apiconfig.json file
 * 2. Pull in the API specific json file
 * 3. Confirm the incoming request contains the necesary data
 * 4. Construct the request to the desired server
 * 5. Return the response
*/

$api = "wheresitup";

$config = json_decode(file_get_contents("../src/apiconfig.json"), true);
$apiconfig = json_decode(file_get_contents("../src/{$api}.json"), true);

$config = $config[$api];
//v($config);
//v($apiconfig);
//v($_POST);


$call = new api($config);
$call->setParams($_POST);
$call->genUrl();
$call->genPayload();
$call->genHeaders();
$call->invoke();


$return = array(
	"request" => array(
		"url" => $call->url,
		"headers" => $call->headers,
		"body" => $call->payload

	),
	"response" => array(
		"headers" => $call->response_headers,
		"body" => $call->response_body,
		"status" => $call->response_status,
		"body_raw" => $call->response_body_raw,
		"headers_raw" => $call->response_headers_raw
	)

);

header('Content-Type: application/json');
echo json_encode($return, true);


/*
Data I should return:
{
	"request" : {
		"url" 
		"headers"
		"body"
	}
	"response" : {
		"headers"
		"body"
		"status"
	}
}



*/
class api
{
	public $config;
	public $url;
	public $payload;
	public function __construct($config)
	{
		$this->config = $config;
		if (isset($config['headers']))
		{
			$this->headers = $config['headers'];
		}

	}

	public function configure($params)
	{

	}

	public function setParams($params)
	{
		$this->params = $params;
	}

	public function genHeaders()
	{
		$headers = array(
			'Content-Type' => "application/json",
			"Content-Length" => strlen($this->payload)
		);
		
		if (count($this->headers) > 0)
		{
			$headers = array_merge($headers, $this->headers);
		}
		
		$h = array();
		foreach($headers as $k => $v)
		{
			$h[] = "{$k}: $v";
		}
		$this->headers = $h;
	}

	public function invoke()
	{
		$ch = curl_init();

		// set URL and other appropriate options
		curl_setopt($ch, CURLOPT_URL, $this->url);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $this->payload);  
		curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);

		// execute request, save result
		$raw = curl_exec($ch);

		$data = $this->splitRaw($raw);
		$headers = $this->parseHeaders($data[0]);

		$this->response_headers_raw = $data[0];
		$this->response_body_raw = $data[1];
		$this->response_headers = $headers;
		$this->response_body = json_decode($data[1]);
		$this->response_status = 200;


		// close cURL resource, and free up system resources
		curl_close($ch);
	}

	protected function splitRaw($raw)
	{
		//Seven kinds of wrong, 100 continue headers will frak this to heck.
		return explode("\r\n\r\n", $raw);
	}

	protected function parseHeaders($headers)
	{
		preg_match_all("!(.+): (.+)\s!", $headers, $matches);
		$bits = $matches[1];
		$bobs = $matches[2];
		$output = array();
		foreach ($bobs as $key => $value)
		{
			$output[$bits[$key]] = trim($value);
		}
		return $output;
	}

	public function genPayload()
	{
		if (1)
		{
			$this->payload = $this->genJson();
		}
	}

	public function genJson()
	{
		return json_encode($this->params);
	}


	public function genUrl()
	{
		$url = "{$this->config['protocol']}://{$this->config['baseURL']}/{$this->config['publicPath']}/sources";
		$this->url = $url;
	}
}

function v($var)
{
	echo "<pre>";
	var_dump($var);
	echo "</pre>";
}
