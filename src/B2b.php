<?php

namespace VAS2Nets\B2b;

use GuzzleHttp\Client;
use VAS2Nets\B2b\Exceptions\B2bException;

class B2b
{
    protected $client;
    protected $url;
    protected $username;
    protected $password;

    private function __construct(string $username, string $password, bool $production = false)
    {
        //new updates
        if ($production) {
            $this->url = "https://b2bapi.v2napi.com/v1/";
        } else {
            $this->url = "https://b2bapi.v2napi.com/dev/";
        }
        // $this->baseUrl = $baseUrl;
        $this->username = $username;
        $this->password = $password;

        $this->client = new Client([
            'base_uri' =>  $this->url,
            'headers' => [
                'Accept' => 'application/json'
            ],
        ]);
    }

    public static function client($username, $password, $production=false): self
    {
        return new self($$username, $password, $production);
    }

    // Generic method to make API requests
    public function request(string $method, string $endpoint, array $data = []): array
    {
        try {
            // Use provided credentials, temporary credentials, or default credentials
            $authUsername = $this->username;
            $authPassword = $this->password;

            if (empty($authUsername) || empty($authPassword)) {
                throw new B2bException('Username and password are required for Basic Authentication');
            }

            $options = [
                'headers' => [
                    'Authorization' => 'Basic ' . base64_encode($authUsername . ':' . $authPassword),
                ],
            ];
            if (!empty($data)) {
                $options = array_merge($options, $method === 'GET' ? ['query' => $data] : ['json' => $data]);
            }

            $response = $this->client->request($method, $endpoint, $options);
            $result = [
                'status' => $response->getStatusCode(),
                'data' => json_decode($response->getBody(), true),
            ];

            // Clear temporary credentials after request if used
            return $result;
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $error = $e->hasResponse() ? json_decode($e->getResponse()->getBody(), true) : ['message' => $e->getMessage()];
            throw new B2bException(
                $error['message'] ?? 'API request failed',
                $e->getResponse() ? $e->getResponse()->getStatusCode() : 500
            );
        }
    }

    // Developer-friendly methods.
    public function profile(): array
    {
        try {
            $response = $this->request('GET', "meta/getDetails", []);
            return [
                'status' => $response['status'],
                'data' => $response['data'],
                'meta' => $response['data']['meta'] ?? []
            ];
        } catch (B2bException $e) {
            throw new B2bException("Failed to fetch : " . $e->getMessage(), $e->getCode());
        }
    }

    public function categories(): array
    {
        try {
            $response = $this->request('GET', "meta/getBillerCategories", []);
            return [
                'status' => $response['status'],
                'data' => $response['data'],
                'meta' => $response['data']['meta'] ?? []
            ];
        } catch (B2bException $e) {
            throw new B2bException("Failed to fetch : " . $e->getMessage(), $e->getCode());
        }
    }

    public function billers(?string $category = null, ?string $billerId = null, bool $all = false, ?string $status = null, ?string $isBouquetService = null): array
    {

        $categoryParam = $category !== null ? "?category=$category" : "";
        $urlEndPoint = '';

        try {
            if ($all) {
                $isBouquetServiceParam = $isBouquetService !== null ? "&isBouquetService=$isBouquetService" : "";
                $status = $category !== null ? "?&status=$status" : "";
                $urlEndPoint = "meta/getAllBillers" . $categoryParam . $isBouquetServiceParam . $status;
            } else {
                $billerIdParam = $billerId !== null ? "&billerId=$billerId" : "";
                $urlEndPoint = "meta/getMyBillers" . $categoryParam . $billerIdParam;
            }
            $response = $this->request('GET', $urlEndPoint, []);
            return [
                'status' => $response['status'],
                'data' => $response['data'],
                'meta' => $response['data']['meta'] ?? []
            ];
        } catch (B2bException $e) {
            throw new B2bException("Failed to fetch : " . $e->getMessage(), $e->getCode());
        }
    }

    //get bouquet service
    public function bouquetService(string $category, string $billerId): array
    {
        // $typeParam = $type !== null ? "?type=$type" : "";
        //  $billerIdParam = $billerId !==null ? "&billerId=$billerId" : "";
        try {
            $response = $this->request('GET', "bouquet/$category/$billerId", []);
            return [
                'status' => $response['status'],
                'data' => $response['data'],
                'meta' => $response['data']['meta'] ?? []
            ];
        } catch (B2bException $e) {
            throw new B2bException("Failed to fetch : " . $e->getMessage(), $e->getCode());
        }
    }


    public function validation(string $category, array $postData): array
    {
        try {
            $response = $this->request('POST', $category . "/validate", $postData);
            return [
                'status' => $response['status'],
                'data' => $response['data'],
                'meta' => $response['data']['meta'] ?? []
            ];
        } catch (B2bException $e) {
            throw new B2bException("Failed to fetch : " . $e->getMessage(), $e->getCode());
        }
    }

    public function pay(?string $category = null, array $postData): array
    {
        try {
            $response = $this->request('POST', $category . "/payment", $postData);
            return [
                'status' => $response['status'],
                'data' => $response['data'],
                'meta' => $response['data']['meta'] ?? []
            ];
        } catch (B2bException $e) {
            throw new B2bException("Failed to fetch : " . $e->getMessage(), $e->getCode());
        }
    }

    public function requery($postData): array
    {
        try {
            $response = $this->request('GET', "/requery", $postData);
            return [
                'status' => $response['status'],
                'data' => $response['data'],
                'meta' => $response['data']['meta'] ?? []
            ];
        } catch (B2bException $e) {
            throw new B2bException("Failed to fetch : " . $e->getMessage(), $e->getCode());
        }
    }
}
