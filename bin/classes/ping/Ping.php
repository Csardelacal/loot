<?php namespace ping;

class Ping
{
	
	private $endpoint;
	
	private $appId;
	private $sso;
	
	public function __construct($endpoint, $sso) {
		$reflection = URLReflection::fromURL($endpoint);
		
		$this->endpoint  = rtrim($reflection->getProtocol() . '://' . $reflection->getServer() . ':' . $reflection->getPort() . $reflection->getPath(), '/');
		$this->appId     = $reflection->getUser();
		
		$this->sso = $sso;
	}
	
	public function push(\auth\Token$token, $target, $content, $url = null, $media = null, $explicit = false) {
		
		$curl = $this->endpoint . '/ping/push.json?' . http_build_query(Array(
			'signature'  => (string)$this->sso->makeSignature($this->appId),
			'token' => $token->getId()
		));
		
		/*
		 * Fetch the JSON message from the endpoint. This should tell us whether 
		 * the request was a success.
		 */
		$ch = curl_init($curl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(Array(
			 'target'   => $target,
			 'content'  => $content,
			 'url'      => $url,
			 'media'    => $media,
			 'explicit' => $explicit? 1 : 0
		)));
		
		$response = curl_exec($ch);

		$http_response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		if ($http_response_code !== 200) {
			throw new \Exception('Ping rejected the request' . $response, 1605141533);
		}
		
		$json = json_decode($response);
		return $json->payload->id;
	}
	
	public function url($url) {
		
		$curl = $this->endpoint . '/ping/url.json?' . http_build_query(Array(
			'url'  => $url
		));
		
		/*
		 * Fetch the JSON message from the endpoint. This should tell us whether 
		 * the request was a success.
		 */
		$ch = curl_init($curl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		
		$response = curl_exec($ch);

		$http_response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		if ($http_response_code !== 200) {
			throw new \Exception('Ping rejected the request' . $response, 1605141533);
		}
		
		$json = json_decode($response);
		
		if (!isset($json->payload)) {
			var_dump($response);
			var_dump($json);
			die();
		}
		
		return $json->payload;
	}
	
	
	public function media($media) {
		
		$curl = $this->endpoint . '/media/upload.json?' . http_build_query(Array(
			 'signature'  => (string)$this->sso->makeSignature($this->appId)
		));
		
		if ($media instanceof \spitfire\storage\objectStorage\FileInterface) {
			$tmp = storage()->dir('file://tmp/')->make(rand());
			$tmp->write($media->read());
			$tmpfile = $tmp->getPath();
		}
		else {
			$tmpfile = $media;
		}

		/*
		 * Fetch the JSON message from the endpoint. This should tell us whether 
		 * the request was a success.
		 */
		$ch = curl_init($curl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, Array(
			 'file'  => new \CURLFile($tmpfile),
			 'type'  => 'image'
		));
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		
		$response = curl_exec($ch);

		$http_response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		
		if ($http_response_code !== 200) {
			throw new \Exception('Ping rejected the request' . $response, 1605141533);
		}
		
		$json = json_decode($response);
		
		return $json->id . ':' . $json->secret;
	}
	
	public function activity($src, $target, $content, $url = null) {
		
		$curl = $this->endpoint . '/activity/push.json?' . http_build_query(Array(
			 'signature'  => (string)$this->sso->makeSignature($this->appId)
		));
		
		/*
		 * Fetch the JSON message from the endpoint. This should tell us whether 
		 * the request was a success.
		 */
		$ch = curl_init($curl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(Array(
			 'src'      => $src,
			 'target'   => $target,
			 'content'  => $content,
			 'url'      => $url
		)));
		
		$response = curl_exec($ch);

		$http_response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		if ($http_response_code !== 200) {
			throw new \Exception('Ping rejected the request' . $response, 1605141533);
		}
	}
	
	public function feedback(\auth\Token$token, $pingid, $reaction) {
		
		$curl = $this->endpoint . '/feedback/push/' . $pingid . '.json?' . http_build_query(Array(
			'signature'  => (string)$this->sso->makeSignature($this->appId),
			'token' => $token->getId(),
			'reaction' => $reaction
		));;
		
		/*
		 * Fetch the JSON message from the endpoint. This should tell us whether 
		 * the request was a success.
		 */
		$ch = curl_init($curl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		
		$response = curl_exec($ch);

		$http_response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		if ($http_response_code !== 200) {
			throw new \Exception('Ping rejected the request' . $response, 1605141533);
		}
		
		return json_decode($response)->payload;
	}
	
	public function getURL() {
		return $this->endpoint;
	}
	
}