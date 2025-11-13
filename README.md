# VAS2Nets B2B Web Services


**Description**

This is software developlent kit(SDK)  which user/client can consume via composer and have access to VAS2Nets b2b web services. Please note that categories(),billers() and bouquetService() methods needs to be called to know services that has been profiled for client/user's account as well as services that are bouquet oriented. i.e biller services that comes with their fixed amount and has thier own bouquet code respectively. Please note that this bouquet code will require during when you are
calling pay() method for this particular service purchase. 

Also note that the status in response payload comes wit three value which could either be Failed, Success or Pending. when a status value is pending, there is a method named requery()
which you have to call to get the final status which could either be Failed or Success. you will find this example at the last item on this documentation.


when you call the categories method, you will see all categories available on b2b web services. Please use their category_id value for any biller profiled for your account that you want to validate or purchase under this category.Follow the following guide for proper use of the kit


- Example of biller ids are MTN-AIRTIME, AIRTEL-AIRTIME, DSTV, IKEJA-PREPAID etc.
  
    - Example of category ids are disco,vtu,tv etc.

you can call categories() and billers() methods to know  categories and billers which has been profiled for your account respectively. if you pass true as a third argument to billers method, you will get all billers available on b2b  platform 





1. Installation


1.1. install [composer](https://getcomposer.org/) first before you run the following 

     ```php
     composer require vas2nets/b2bapi
     ```


2. Getting Started

       use VAS2Nets\B2b\B2b;

       use VAS2Nets\B2b\Exceptions\B2bException;
   

use B2bException exception for error handling, you can get any kind of error coming from the APIs.


    $b2b = B2b::client($username,$password,true);


Please do not pass anything as the third argument if you are in sandbox environment.




2.1 Get User Profile


    $b2b->profile();

2.2 Get User Service categories


    $b2b->categories();

2.3 Get User Service categories


    $b2b->categories();


2.4 Get All Available Billers in VAS2Nets b2b platform. 

Fourt argumnent can be either Active, Inactive or Disabled to get status of all billers 
fifth argument is Yes or No , this indicatw whether you want to get bouguet service or non bouquet service.

	$b2b->billers(
	" ",
	" ",
	{true}
	'Active|Inactive|Disabled',
	'Yes|No',
	);


2.5 Get User Profiled Billers in b2b platform

	$b2b->billers(
	{category_id},
	{billerId},
	);


2.6 Get User Bouquet Service

	$b2b->bouquetService(
	category_id,
	billerId
	);



Validation and Payment method are the same for all b2b web services, what differ is the payload data 
argument passing to the methods which is type of an array

= Generic Validation and make payment methods Formats.

	$b2b->validation(
	{category_id},
	payload(values...)
	);


	$b2b->pay(
	{category_id},
	payload(values..)
	);



- Sample Payload Data to Validate Airtime and Data

		array(
		customerId => '2348132586075', 
		requestId => '4292')

- The customerid is the user phone number and  could be in any format like 2348027839144 or 08027839144.
- This request is is generated from third party platform.








- Sample Payload Data to make Payment for Airtime and Data

Please note that the above validate() call to one of the biller services must be successfull before calling pay method(), 
otherwise it will not make pay() method call to be successfull.

Bouquest service 

	array(
	customerId = '2348027839144',
	requestId ='192939491', 
	'billerId'=> 'MTN-DATA',
	'amount' = '100',
	'bouquetCode'=> 'MTN100MB1Day100')

Non Bouquet service

	array(
	customerId = '2348027839144',
	requestId ='192939491', 
	'billerId'=> 'MTN-DATA',
	'amount' = '100')

Please note that  bouquetCode is included when a payment is only to make to bouquet service like DSTV, DATA etc.



- Sample  Payload Data to Validate Disco meter or account number 


Please note that customerId could be meter number or account number 


	array(
	customerId = '12345678910',
	requestId ='39293995425', 
	'billerId'=> 'AEDCA')
	);




- Sample Payload to Make Payment for Disco Service

		array(
		customerId = '12345678910',
		requestId ='492949392', 
		'billerId'=> 'IBEDCA',
		'amount' = 1500,
		'customerName'=>'CLIFFORD NWIGWE',
		'customerAddress'=>'NYSC AREA 5 Und St. Garki 80')





- Sample Payload to validate tv smartcard number or UIC Number

  Please note that customerId could be Smart Card number or UIC Number 

		array(
		customerId = '7030935900',
		requestId ='48294929492', 
		'billerId'=> 'DSTV');



- Sample Payload to make payment  for tv category

		array(
		customerId = '7030935900',
		requestId ='59385823582', 
		'billerId'=> 'DSTV',
		'amount' = 10450,
		'customerName'=>'Sten Mockett',
		'bouquetCode' => 'DSTVCNFM',
		'customerNumber' =>'71048760',
		'addonCode' => 'FRN11E36');


Please note that customer can choose to select addon for a particular package and vice-versa, addonCode is the code to addon that customer wants.
And bouquet is only available on DSTV/GOTV.


DSTVR/GOTVR are DSTV Renewal and GOTV Renewal Respectively, these service should be available to the user that wants to renew their current package. And Multichoice sometimes
gives commission to their customers and these customers can only benefits from these commissions through the renewal service. i.e a field should always availbe for user
to enter a specific amount to top up their package. Startimes is also a bouquet service. However,startimes gives room for their customer to pay any amount of their choice at
any time, and startimes will charge the customer a daily tariff depending on the bouquet the customer is subscribed to.






- Sample Payload data for ShowMax Voucher Purchase

		array(
		customerId = '08132586075',
		requestId ='49394958245', 
		'billerId'=> 'SHOWMAX',
		'amount' = 11880,
		'bouquetCode' => 'SHOWMAXPRO3MONTH');



- Sample Payload to Validate betting

		array(
		customerId = '34382',
		requestId ='4994924203', 
		'billerId'=> 'BT9J');


- Sample Payload to make payment for betting category

		array(
		customerId = '34382',
		requestId ='49394924544', 
		'billerId'=> 'BT9J',
		'amount' = 1000,
		'customerName'=>'Test Account 2');


- Sample Payload to Validate customer id for internet category

		array(
		customerId = '1402000567',
		requestId ='5939395923', 
		'billerId'=> 'SMILE');


- Sample Payload to make payment for internet category

		array(
		customerId = '1402000567',
		requestId ='385929522', 
		'billerId'=> 'SMILE',
		'amount' = 1200,
		'customerName'=>'Olumide Pablo'),
		'bouquetCode'=>'SMILE2GB30Days'),
		'customerAddress'=>'isolo');


- Sample Payload to Validate customer account number for payment service.

		array(
		customerId = '3057071087',
		requestId ='32932', 
		'billerId'=> 'FTOUTWARD')
		'bankCode'=> '000016');



- Sample Payload to make payment in banking service

		array(
		customerId = '3057071087',
		requestId ='5939949593', 
		'billerId'=> 'FTOUTWARD',
		'amount' => 1000,
		'bankCode'=> '000016',
		'customerName'=>'Gabriel Oluwadamilare Oyetunde'),
		'senderName'=>'Chika Ladipo',
		'beneficiaryReference'=>'22000000089',
		'sessionId'=>'110015231101103400123486131462',
		'kycLevel'=>'3',
		'narration'=>'New B2B test');


- Sample Payload to make payment in Education Category

		array(
		'requestId' =>'49392', 
		'billerId'=> 'WAEC',
		'amount' => 2900,
		'bouquetCode'=> 'WAECPIN');





- Sample Payload to make payment in banking service

		array(
		requestId =>'3949219', 
		'billerId'=> 'MTN-VOUCHER',
		'amount' => 100,
		'bouquetCode'=> 'EPINMTN100');




- Requery payload for pending transactions.

		$b2b->requery([
		'requestId' => '299492939955'
		]);

Please note that the requestId is a transaction request id that previously in pending status after a pay method is called.It advisable to 
called this method every 10 minutes to ascertain the final statis which could either be Success or Failed.
