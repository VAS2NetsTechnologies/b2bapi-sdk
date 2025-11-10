<?php

namespace VAS2Nets\B2b;

use GuzzleHttp\Client;
use VAS2Nets\B2b\Exceptions\B2bException;

class B2b
{
    protected $client;
    protected $Url;
    protected $username;
    protected $password;

    private function __construct(bool $production, string $username, string $password)
    {
        //new updates
        if ($production) {
            $this->Url = "https://b2bapi.v2napi.com/V1/";
        } else {
            $this->Url = "https://b2bapi.v2napi.com/dev/";
        }
        // $this->baseUrl = $baseUrl;
        $this->username = $username;
        $this->password = $password;

        $this->client = new Client([
            'base_uri' =>  $this->Url,
            'headers' => [
                'Accept' => 'application/json'
            ],
        ]);
    }

    public static function client(bool $production= false, $username, $password): self
    {
        return new self($production, $username, $password);
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

    public function billers(bool $all = false, ?string $category = null, ?string $status = null, ?string $isBouquetService = null, ?string $billerId = null): array
    {

        $categoryParam = $category !== null ? "?category=$category" : "";
        $isBouquetServiceParam = $isBouquetService !== null ? "&isBouquetService=$isBouquetService" : "";
        $urlEndPoint = '';

        try {
            if ($all) {
                $status = $category !== null ? "?&status=$status" : "";
                $urlEndPoint = "meta/getAllBillers" . $categoryParam . $isBouquetServiceParam . $status;
            } else {
                $billerIdParam = $billerId !== null ? "&billerId=$billerId" : "";
                $urlEndPoint = "meta/getMyBillers" . $categoryParam . $billerIdParam . $isBouquetServiceParam;
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
    public function bouquetService(?string $category = null, ?string $billerId = null, ?string $type = null): array
    {
        $typeParam = $type !== null ? "?type=$type" : "";
        //  $billerIdParam = $billerId !==null ? "&billerId=$billerId" : "";
        try {
            $response = $this->request('GET', "bouquet/$category/$billerId.$typeParam", []);
            return [
                'status' => $response['status'],
                'data' => $response['data'],
                'meta' => $response['data']['meta'] ?? []
            ];
        } catch (B2bException $e) {
            throw new B2bException("Failed to fetch : " . $e->getMessage(), $e->getCode());
        }
    }


    public function validation(?string $category = null, array $postData): array
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
