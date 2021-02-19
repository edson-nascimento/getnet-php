<?php
namespace Getnet\API;

use Exception;

/**
 * Class Request
 *
 * @package Getnet\API
 */
class Request {

    /**
     * Base url from api
     *
     * @var string
     */
    private $baseUrl = '';

    const CURL_TYPE_AUTH   = "AUTH";
    const CURL_TYPE_POST   = "POST";
    const CURL_TYPE_PUT    = "PUT";
    const CURL_TYPE_GET    = "GET";
    const CURL_TYPE_DELETE = "DELETE";

    /**
     * Request constructor.
     *
     * @param Getnet $credentials
     */
    public function __construct(Getnet $credentials) {
        $this->baseUrl = $credentials->getEnvironment()->getApiUrl();

        if (!$credentials->getAuthorizationToken()) {
            $this->auth($credentials);
        }
    }

    /**
     *
     * @param Getnet $credentials
     * @return Getnet
     * @throws Exception
     */
    public function auth(Getnet $credentials) {

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
            throw new Exception($e->getMessage(), 100);
        }

        $credentials->setAuthorizationToken($response["access_token"]);

        //Save auth session
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
    private function verifyAuthSession(Getnet $credentials){

        if ($credentials->getKeySession() && isset($_SESSION[$credentials->getKeySession()]) && $_SESSION[$credentials->getKeySession()]["access_token"]) {

            $auth = $_SESSION[$credentials->getKeySession()];
            $now  = microtime(true);
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
     * @param mixed $json
     * @throws Exception
     * @return mixed
     * @throws \Exception
     */
    private function send(Getnet $credentials, $url_path, $method, $json = NULL) {
        $curl = curl_init($this->getFullUrl($url_path));

        $defaultCurlOptions = array(
            CURLOPT_CONNECTTIMEOUT => 60,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json; charset=utf-8'
            ),
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_SSL_VERIFYPEER => 0
        );

        if ($method == self::CURL_TYPE_POST) {
            $defaultCurlOptions[CURLOPT_HTTPHEADER][] = 'Authorization: Bearer ' . $credentials->getAuthorizationToken();
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
        } elseif ($method == self::CURL_TYPE_GET) {
            $defaultCurlOptions[CURLOPT_HTTPHEADER][] = 'Authorization: Bearer ' . $credentials->getAuthorizationToken();
        } elseif ($method == self::CURL_TYPE_DELETE) {
            $defaultCurlOptions[CURLOPT_HTTPHEADER][] = 'Authorization: Bearer ' . $credentials->getAuthorizationToken();
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, self::CURL_TYPE_DELETE);
        } elseif ($method == self::CURL_TYPE_PUT) {
            $defaultCurlOptions[CURLOPT_HTTPHEADER][] = 'Authorization: Bearer ' . $credentials->getAuthorizationToken();
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, self::CURL_TYPE_PUT);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $json);

        } elseif ($method == self::CURL_TYPE_AUTH) {
            $defaultCurlOptions[CURLOPT_HTTPHEADER][0] = 'application/x-www-form-urlencoded';
            curl_setopt($curl, CURLOPT_USERPWD, $credentials->getClientId() . ":" . $credentials->getClientSecret());
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
        }
        curl_setopt($curl, CURLOPT_ENCODING, "");
        curl_setopt_array($curl, $defaultCurlOptions);

        try {
            $response = curl_exec($curl);
        } catch (Exception $e) {
            print "ERROR";
        }

        if (isset(json_decode($response)->error)) {
            throw new Exception(json_decode($response)->error_description, 100);
        }

        if (curl_getinfo($curl, CURLINFO_HTTP_CODE) >= 400) {
            throw new Exception($response, 100);
        }

        // Status code 204 don't have content. That means $response will be always false
        // Provides a custom content for $response to avoid error in the next if logic
        if (curl_getinfo($curl, CURLINFO_HTTP_CODE) == 204) {
            curl_close($curl);
            return ['status_code' => 204];
        }

        if (! $response) {
            print "ERROR";
            EXIT();
        }
        curl_close($curl);

        return json_decode($response, true);
    }

    /**
     * Get request full url
     *
     * @param string $url_path
     * @return string $url(config) + $url_path
     */
    private function getFullUrl($url_path) {
        if (stripos($url_path, $this->baseUrl, 0) === 0) {
            return $url_path;
        }

        return $this->baseUrl . $url_path;
    }

    /**
     *
     * @return string
     */
    public function getBaseUrl() {
        return $this->baseUrl;
    }

    /**
     *
     * @param Getnet $credentials
     * @param mixed $url_path
     * @return mixed
     * * @throws Exception
     */
    public function get(Getnet $credentials, $url_path) {
        return $this->send($credentials, $url_path, self::CURL_TYPE_GET);
    }

    /**
     *
     * @param Getnet $credentials
     * @param mixed $url_path
     * @param mixed $params
     * @return mixed
     * * @throws Exception
     */
    public function post(Getnet $credentials, $url_path, $params) {
        return $this->send($credentials, $url_path, self::CURL_TYPE_POST, $params);
    }

    /**
     *
     * @param Getnet $credentials
     * @param mixed $url_path
     * @param mixed $params
     * @return mixed
     * * @throws Exception
     */
    public function put(Getnet $credentials, $url_path, $params) {
        return $this->send($credentials, $url_path, self::CURL_TYPE_PUT, $params);
    }

    /**
     *
     * @param Getnet $credentials
     * @param mixed $url_path
     * @return mixed
     * * @throws Exception
     */
    public function delete(Getnet $credentials, $url_path) {
        return $this->send($credentials, $url_path, self::CURL_TYPE_DELETE);
    }

}
