<?php

namespace VAS2Nets\B2bSDK\tests;

use PHPUnit\Framework\TestCase;
use VAS2Nets\B2bSDK\B2bSDK;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class B2bSDKTest extends TestCase
{
    public function testGetUserWithDirectCredentials()
    {
        // $mock = new MockHandler([
        //     new Response(200, [], json_encode(['id' => 1, 'name' => 'John Doe']))
        // ]);
        // $handler = HandlerStack::create($mock);
        // $client = new Client(['handler' => $handler]);


        // $response = $sdk->getProfileDetails('benardTest','benardTest', 'BENARD@1990');
        $sdk = new B2bSDK('dev','benardTest','BENARD');
        // $sdk->setClient($client); // Inject mock client for testing
        //pass the type for daily data plan

        // $data = array(
        //      'requestId'  => '2380917',
        //      'billerId'   => 'MTN-VOUCHER',
        //      'amount'    => 100,
        //      'bouquetCode' => 'EPINMTN100'
        // );

        $response = $sdk->getProfileDetails('benardTest', 'BENARD@1990');
        $this->assertEquals(200, $response['status']);
        // $this->assertEquals('John Doe', $response['data']['name']);
    }
}



