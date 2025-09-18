<?php

namespace VAS2Nets\B2bSDK;

use GuzzleHttp\Client;
use VAS2Nets\B2bSDK\Exceptions\B2bSDKException;

class B2bSDK
{
    protected $client;
    protected $Url;
    protected $defaultUsername;
    protected $defaultPassword;

    public function __construct(string $queryString, string $defaultUsername, string $defaultPassword)
    {
        //new updates
        if ($queryString === 'dev') {
            $this->Url = "https://b2bapi.v2napi.com/$queryString/";
        } else if ($queryString === 'v1') {
            $this->Url = "https://b2bapi.v2napi.com/$queryString/";
        } else {
            throw new B2bSDKException('Invalid query string');
        }
        // $this->baseUrl = $baseUrl;
        $this->defaultUsername = $defaultUsername;
        $this->defaultPassword = $defaultPassword;
        $this->client = new Client([
            'base_uri' =>  $this->Url,
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);
    }

    // Generic method to make API requests
    public function request(string $method, string $endpoint, array $data = [], ?string $username = null, ?string $password = null): array
    {
        try {
            // Use provided credentials, temporary credentials, or default credentials
            $authUsername = $username  ?? $this->defaultUsername;
            $authPassword = $password ?? $this->defaultPassword;

            if (empty($authUsername) || empty($authPassword)) {
                throw new B2bSDKException('Username and password are required for Basic Authentication');
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
            throw new B2bSDKException(
                $error['message'] ?? 'API request failed',
                $e->getResponse() ? $e->getResponse()->getStatusCode() : 500
            );
        }
    }

    // Developer-friendly methods.
    public function getProfileDetails(?string $username = null, ?string $password = null): array
    {
        try {
            $response = $this->request('GET', "meta/getDetails", [], $username, $password);
            return [
                'status' => $response['status'],
                'data' => $response['data'],
                'meta' => $response['data']['meta'] ?? []
            ];
        } catch (B2bSDKException $e) {
            throw new B2bSDKException("Failed to fetch : " . $e->getMessage(), $e->getCode());
        }
    }

    public function getBillerCategories(?string $username = null, ?string $password = null): array
    {
        try {
            $response = $this->request('GET', "meta/getBillerCategories", [], $username, $password);
            return [
                'status' => $response['status'],
                'data' => $response['data'],
                'meta' => $response['data']['meta'] ?? []
            ];
        } catch (B2bSDKException $e) {
            throw new B2bSDKException("Failed to fetch : " . $e->getMessage(), $e->getCode());
        }
    }

    public function getAllAvailableBillers(?string $username = null, ?string $password = null, ?string $category = null): array
    {
        $categoryParam = $category !== null ? "?category=$category" : "";
        try {
            $response = $this->request('GET', "meta/getAllBillers" . $categoryParam, [], $username, $password);
            return [
                'status' => $response['status'],
                'data' => $response['data'],
                'meta' => $response['data']['meta'] ?? []
            ];
        } catch (B2bSDKException $e) {
            throw new B2bSDKException("Failed to fetch : " . $e->getMessage(), $e->getCode());
        }
    }


    //getMyBillers Services
    public function getMyBillers(?string $username = null, ?string $password = null, ?string $category = null, ?string $billerId = null, ?string $isBouquetService = null): array
    {
        $categoryParam = $category !== null ? "?category=$category" : "";
        $billerIdParam = $billerId !== null ? "&billerId=$billerId" : "";
        $isBouquetServiceParam = $isBouquetService !== null ? "&isBouquetService=$isBouquetService" : "";
        try {
            $response = $this->request('GET', "meta/getMyBillers" . $categoryParam . $billerIdParam . $isBouquetServiceParam, [], $username, $password);
            return [
                'status' => $response['status'],
                'data' => $response['data'],
                'meta' => $response['data']['meta'] ?? []
            ];
        } catch (B2bSDKException $e) {
            throw new B2bSDKException("Failed to fetch : " . $e->getMessage(), $e->getCode());
        }
    }

    //get bouquet service
    public function getBouquetService(?string $username = null, ?string $password = null, ?string $category = null, ?string $billerId = null, ?string $type = null): array
    {
        $typeParam = $type !== null ? "?type=$type" : "";
        //  $billerIdParam = $billerId !==null ? "&billerId=$billerId" : "";
        try {
            $response = $this->request('GET', "bouquet/$category/$billerId.$typeParam", [], $username, $password);
            return [
                'status' => $response['status'],
                'data' => $response['data'],
                'meta' => $response['data']['meta'] ?? []
            ];
        } catch (B2bSDKException $e) {
            throw new B2bSDKException("Failed to fetch : " . $e->getMessage(), $e->getCode());
        }
    }


    public function runUserValidation(array $postData, ?string $username = null, ?string $password = null, ?string $category = null): array
    {
        try {
            $response = $this->request('POST', $category . "/validate", $postData, $username, $password);
            return [
                'status' => $response['status'],
                'data' => $response['data'],
                'meta' => $response['data']['meta'] ?? []
            ];
        } catch (B2bSDKException $e) {
            throw new B2bSDKException("Failed to fetch : " . $e->getMessage(), $e->getCode());
        }
    }

    public function makePayment(array $postData, ?string $username = null, ?string $password = null, ?string $category = null): array
    {
        try {
            $response = $this->request('POST', $category . "/payment", $postData, $username, $password);
            return [
                'status' => $response['status'],
                'data' => $response['data'],
                'meta' => $response['data']['meta'] ?? []
            ];
        } catch (B2bSDKException $e) {
            throw new B2bSDKException("Failed to fetch : " . $e->getMessage(), $e->getCode());
        }
    }
}
