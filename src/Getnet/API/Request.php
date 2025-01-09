<?php
namespace Getnet\API;

use Exception;
use Getnet\API\Exception\GetnetException;

/**
 * Class Request
 *
 * @package Getnet\API
 */
class Request
{

    /**
     * Base url from api
     *
     * @var string
     */
    private $baseUrl = '';

    /**  @var string[] */
    private array $customHeaders = [];

    public const CURL_TYPE_AUTH = "AUTH";

    public const CURL_TYPE_POST = "POST";

    public const CURL_TYPE_PUT = "PUT";

    public const CURL_TYPE_GET = "GET";

    public const CURL_TYPE_DELETE = "DELETE";

    /**
     * Request constructor.
     *
     * @param Getnet $credentials
     * TODO create local variable to $credentials
     */
    public function __construct(Getnet $credentials)
    {
        $this->baseUrl = $credentials->getEnvironment()->getApiUrl();

        if (! $credentials->getAuthorizationToken()) {
            $this->auth($credentials);
        }
    }

    /**
     *
     * @param Getnet $credentials
     * @return Getnet
     * @throws Exception
     */
    public function auth(Getnet $credentials)
    {
        if ($this->verifyAuthSession($credentials)) {
            return $credentials;
        }

        $url_path = "/auth/oauth/v2/token";

        $params = [
            "scope" => "oob",
            "grant_type" => "client_credentials"
        ];

        $querystring = http_build_query($params);

        try {
            $response = $this->send($credentials, $url_path, self::CURL_TYPE_AUTH, $querystring);
        } catch (Exception $e) {
            throw new GetnetException($e->getMessage(), 100);
        }

        $credentials->setAuthorizationToken($response["access_token"]);

        // Save auth session
        if ($credentials->getKeySession()) {
            $response['generated'] = microtime(true);
            $_SESSION[$credentials->getKeySession()] = $response;
        }

        return $credentials;
    }

    /**
     * start session for use
     *
     * @param Getnet $credentials
     * @return boolean
     */
    private function verifyAuthSession(Getnet $credentials)
    {
        if ($credentials->getKeySession() && isset($_SESSION[$credentials->getKeySession()]) && $_SESSION[$credentials->getKeySession()]["access_token"]) {

            $auth = $_SESSION[$credentials->getKeySession()];
            $now = microtime(true);
            $init = $auth["generated"];

            if (($now - $init) < $auth["expires_in"]) {
                $credentials->setAuthorizationToken($auth["access_token"]);

                return true;
            }
        }

        return false;
    }

    /**
     *
     * @param Getnet $credentials
     * @param mixed $url_path
     * @param mixed $method
     * @param mixed $jsonBody
     * @throws Exception
     * @return mixed
     * @throws \Exception
     */
    private function send(Getnet $credentials, $url_path, $method, $jsonBody = null)
    {
        $curl = curl_init($this->getFullUrl($url_path));
        $defaultHeaders = ['Content-Type: application/json; charset=utf-8'];

        // Use in PIX
        if (! empty($credentials->getSellerId())) {
            $defaultHeaders[] = 'seller_id: ' . $credentials->getSellerId();
        }

        // Auth
        if ($method === self::CURL_TYPE_AUTH) {
            $defaultHeaders[0] = 'application/x-www-form-urlencoded';
            curl_setopt($curl, CURLOPT_USERPWD, $credentials->getClientId() . ":" . $credentials->getClientSecret());
        } else {
            $defaultHeaders[] = 'Authorization: Bearer ' . $credentials->getAuthorizationToken();
        }

        // Add custom method
        if (in_array($method, [
            self::CURL_TYPE_DELETE,
            self::CURL_TYPE_PUT
        ])) {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        }

        // Add body params
        if (! empty($jsonBody)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, is_string($jsonBody) ? $jsonBody : json_encode($jsonBody));
        }

        curl_setopt($curl, CURLOPT_ENCODING, "");
        curl_setopt_array($curl, [
            CURLOPT_CONNECTTIMEOUT => 60,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_HTTPHEADER => array_merge($defaultHeaders, $this->customHeaders),
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_SSL_VERIFYPEER => 0
        ]);

        $response = null;
        $errorMessage = '';

        try {
            $response = curl_exec($curl);
        } catch (Exception $e) {
            throw new GetnetException("Request Exception, error: {$e->getMessage()}", 100);
        }

        // Verify error
        if ($response === false) {
            $errorMessage = curl_error($curl);
        }

        $statusCode = (int) curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($statusCode >= 400) {
            // TODO see what it means code 100
            throw new GetnetException($response, 100);
        }

        // Status code 204 don't have content. That means $response will be always false
        // Provides a custom content for $response to avoid error in the next if logic
        if ($statusCode === 204) {
            return [
                'status_code' => 204
            ];
        }

        if (! $response) {
            throw new GetnetException("Empty response, curl_error: $errorMessage", $statusCode);
        }

        $responseDecode = json_decode($response, true);

        if (is_array($responseDecode) && isset($responseDecode['error'])) {
            throw new GetnetException($responseDecode['error_description'], 100);
        }

        return $responseDecode;
    }

    /**
     * Get request full url
     *
     * @param string $url_path
     * @return string $url(config) + $url_path
     */
    private function getFullUrl($url_path)
    {
        if (stripos($url_path, $this->baseUrl, 0) === 0) {
            return $url_path;
        }

        return $this->baseUrl . $url_path;
    }

    /**
     *
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    /**
     *
     * @param Getnet $credentials
     * @param mixed $url_path
     * @return mixed * @throws Exception
     */
    public function get(Getnet $credentials, $url_path)
    {
        return $this->send($credentials, $url_path, self::CURL_TYPE_GET);
    }

    /**
     *
     * @param Getnet $credentials
     * @param mixed $url_path
     * @param mixed $params
     * @return mixed * @throws Exception
     */
    public function post(Getnet $credentials, $url_path, $params)
    {
        return $this->send($credentials, $url_path, self::CURL_TYPE_POST, $params);
    }

    /**
     *
     * @param Getnet $credentials
     * @param mixed $url_path
     * @param mixed $params
     * @return mixed * @throws Exception
     */
    public function put(Getnet $credentials, $url_path, $params)
    {
        return $this->send($credentials, $url_path, self::CURL_TYPE_PUT, $params);
    }

    /**
     *
     * @param Getnet $credentials
     * @param mixed $url_path
     * @return mixed * @throws Exception
     */
    public function delete(Getnet $credentials, $url_path)
    {
        return $this->send($credentials, $url_path, self::CURL_TYPE_DELETE);
    }
    
    public function custom(Getnet $credentials, string $method, string $url_path, $body = null)
    {
        
        if (!in_array($method, [
            self::CURL_TYPE_AUTH,
            self::CURL_TYPE_POST,
            self::CURL_TYPE_PUT,
            self::CURL_TYPE_GET,
            self::CURL_TYPE_DELETE
        ])) {
            throw new GetnetException("Invalid request method: {$method}");
        }
        
        return $this->send($credentials, $url_path, $method, $body);
    }

	public function getCustomHeaders(): array
    {
		return $this->customHeaders;
	}
	
    /** @param string[] $customHeaders */
	public function setCustomHeaders(array $customHeaders): static
    {
		$this->customHeaders = $customHeaders;
	
        return $this;
	}

    public function addCustomHeader(string $header): static
    {
		$this->customHeaders[] = $header;
		
        return $this;
	}
}
