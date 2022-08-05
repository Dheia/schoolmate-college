<?php

namespace App\Http\Controllers\QuickBooks;

use Illuminate\Http\Request;
use App\Http\Controllers\Client;
use Carbon\Carbon;
use Illuminate\Routing\UrlGenerator;

use QuickBooksOnline\API\Core\ServiceContext;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\PlatformService\PlatformService;
use QuickBooksOnline\API\Core\Http\Serialization\XmlObjectSerializer;

use App\QuickBooksTokenizer;

class QuickBooks
{
	public $dataService;
	public $OAuth2LoginHelper;
	public $accessTokenKey;
	public $refreshTokenKey;
	public $QBORealmID;
	public $scope;


	public function __construct (Request $request)
	{
		$this->scope = env('INTUIT_SCOPE');
		
		if($request->scope !== null) {
			$this->scope = $request->scope;
		} 

		$qbToken = QuickBooksTokenizer::latest()->first();
		$this->accessTokenKey  = null;
		$this->refreshTokenKey = null;
		$this->QBORealmID 	   = null;

		if($qbToken !== null) {

			$this->accessTokenKey  = $qbToken->accessTokenKey;
			$this->refreshTokenKey = $qbToken->refreshTokenKey;
			$this->QBORealmID 	   = $qbToken->realmID;
			$accessTokenKeyDate    = Carbon::parse($qbToken->accessTokenExpiresAt);
			$refreshTokenKeyDate   = Carbon::parse($qbToken->refreshTokenExpiresAt);
			$dataService   		   = DataService::Configure(array(
										'auth_mode' 	  => env('INTUIT_AUTH_MODE'),
										'ClientID' 		  => env('INTUIT_CLIENT_ID'),
										'ClientSecret' 	  => env('INTUIT_CLIENT_SECRET'),
										'scope' 		  => $this->scope,
										'baseUrl' 		  => env('INTUIT_BASE_URL'),
										'RedirectURI' 	  => env('APP_URL') . '/admin/quickbooks/token', 
										'accessTokenKey'  => $this->accessTokenKey,
									    'refreshTokenKey' => $this->refreshTokenKey,
									    'QBORealmID' 	  => $this->QBORealmID,
									));
			$this->dataService 	   = $dataService;
			
			$this->dataService
				 ->getOAuth2LoginHelper()
				 ->getAccessToken()
				 ->updateAccessToken(strtotime($qbToken->accessTokenExpiresAt), $qbToken->refreshTokenKey, strtotime($qbToken->refreshTokenExpiresAt), $qbToken->accessTokenKey);

			$now = Carbon::now();

			if($now > $accessTokenKeyDate && $now < $refreshTokenKeyDate) {
				$resultObj = $this->dataService->getOAuth2LoginHelper()->refreshAccessTokenWithRefreshToken($this->refreshTokenKey);
				$this->updateAccessToken();
			}
			else if ($now > $accessTokenKeyDate && $now > $refreshTokenKeyDate) {
				$this->updateAccessToken();
			} 
			else {

				$status	 = "ERROR";
				$message = "Please Try Again Or Contact The Administrator.";

				return view('quickbooks.layouts.fallbackMessage', compact('status', 'message'));
			}

		} else if ($qbToken === null) {

			$dataService = DataService::Configure(array(
								'auth_mode' 	  => env('INTUIT_AUTH_MODE'),
								'ClientID' 		  => env('INTUIT_CLIENT_ID'),
								'ClientSecret' 	  => env('INTUIT_CLIENT_SECRET'),
								'scope' 		  => env('INTUIT_SCOPE'),
								'baseUrl' 		  => env('INTUIT_BASE_URL'),
								'RedirectURI'     => env('APP_URL'). '/admin/quickbooks/token',

							));
			return $this->dataService = $dataService;

		} else {
    	
    		$message = "This App Is Not Yet Authorized To QuickBooks"; 
    		$status  = "ERROR";
			return view('quickbooks.layouts.fallbackMessage', compact('status', 'message'));
		
		}
	}

	private function hasExistingToken ()
	{
		$qbToken = QuickBooksTokenizer::latest()->first();
		return $qbToken !== null ? true : false;
	}

    private function updateAccessToken() 
    {	
    	try {

	    	$previousAccessToken 	 = QuickBooksTokenizer::orderBy('created_at', 'desc')->first();
	    	$this->OAuth2LoginHelper = $this->dataService->getOAuth2LoginHelper();
	    	QuickBooksTokenizer::where('accessTokenKey', $previousAccessToken->accessTokenKey)
				->update([
			    	'accessTokenKey' 				 => $this->OAuth2LoginHelper->getAccessToken()->getAccessToken(),
			    	'refreshTokenKey' 				 => $this->OAuth2LoginHelper->getAccessToken()->getRefreshToken(),
			    	'accessTokenExpiresAt'  		 => $this->OAuth2LoginHelper->getAccessToken()->getAccessTokenExpiresAt(),
			    	'refreshTokenExpiresAt' 		 => $this->OAuth2LoginHelper->getAccessToken()->getRefreshTokenExpiresAt(),
			    	'accessTokenValidationPeriod'    => $this->OAuth2LoginHelper->getAccessToken()->getAccessTokenValidationPeriodInSeconds(),
			    	'refreshTokenValidationPeriod'   => $this->OAuth2LoginHelper->getAccessToken()->getRefreshTokenValidationPeriodInSeconds(),
			    	'realmID' 						 => $this->OAuth2LoginHelper->getAccessToken()->getRealmID(),
		    	]);

    	}
    	catch (\Exception $e) {

    		$status	 = "ERROR";
    		$message = $e->getMessage();
    		return view('quickbooks.layouts.fallbackMessage', compact('status', 'message'));

    	}
    }

    private function refreshToken ()
    {
    	$OAuth2LoginHelper  				 = $this->dataService->getOAuth2LoginHelper();
    	$refreshTokenObj 					 = $OAuth2LoginHelper->refreshToken();

    	$this->dataService->updateOAuth2Token($refreshTokenObj);

    	$qbt 								 = new QuickBooksTokenizer;
    	$qbt->accessTokenKey 				 = $OAuth2LoginHelper->getAccessToken()->getAccessToken();
    	$qbt->refreshTokenKey 				 = $OAuth2LoginHelper->getAccessToken()->getRefreshToken();
    	$qbt->accessTokenExpiresAt  		 = $OAuth2LoginHelper->getAccessToken()->getAccessTokenExpiresAt();
    	$qbt->refreshTokenExpiresAt 		 = $OAuth2LoginHelper->getAccessToken()->getRefreshTokenExpiresAt();
    	$qbt->accessTokenValidationPeriod    = $OAuth2LoginHelper->getAccessToken()->getAccessTokenValidationPeriodInSeconds();
    	$qbt->refreshTokenValidationPeriod   = $OAuth2LoginHelper->getAccessToken()->getRefreshTokenValidationPeriodInSeconds();
    	$qbt->realmID 						 = $OAuth2LoginHelper->getAccessToken()->getRealmID();
    	$qbt->save();
    }

    public function QBOAuthorize ()
    {
    	$QBOToken = QuickBooksTokenizer::get();

    	if($QBOToken->count() > 0) {
    		QuickBooksTokenizer::truncate();
    	}

	    $dataService 	   = DataService::Configure(array(
									'auth_mode' 	  => env('INTUIT_AUTH_MODE'),
									'ClientID' 		  => env('INTUIT_CLIENT_ID'),
									'ClientSecret' 	  => env('INTUIT_CLIENT_SECRET'),
									'scope' 		  => env('INTUIT_SCOPE'),
									'baseUrl' 		  => env('INTUIT_BASE_URL'),
									'RedirectURI'     => env('APP_URL').'/admin/quickbooks/token',
							 ));

		$this->dataService = $dataService;
    	$OAuth2LoginHelper = $this->dataService->getOAuth2LoginHelper();
		$authorizationUrl  = $OAuth2LoginHelper->getAuthorizationCodeURL();
		
		return redirect($authorizationUrl);
    }

    public function token (Request $request)
    {
    	try {

	    	$code 					 = $request->code;
	    	$realmId 				 = $request->realmId;
	    	$this->OAuth2LoginHelper = $this->dataService->getOAuth2LoginHelper();
	    	$accessToken 			 = $this->OAuth2LoginHelper->exchangeAuthorizationCodeForToken($code, $realmId);

	    	$this->dataService->updateOAuth2Token($accessToken);

	    	$qbt 								 = new QuickBooksTokenizer;
	    	$qbt->accessTokenKey 				 = $this->OAuth2LoginHelper->getAccessToken()->getAccessToken();
	    	$qbt->refreshTokenKey 				 = $this->OAuth2LoginHelper->getAccessToken()->getRefreshToken();
	    	$qbt->accessTokenExpiresAt  		 = $this->OAuth2LoginHelper->getAccessToken()->getAccessTokenExpiresAt();
	    	$qbt->refreshTokenExpiresAt 		 = $this->OAuth2LoginHelper->getAccessToken()->getRefreshTokenExpiresAt();
	    	$qbt->accessTokenValidationPeriod    = $this->OAuth2LoginHelper->getAccessToken()->getAccessTokenValidationPeriodInSeconds();
	    	$qbt->refreshTokenValidationPeriod   = $this->OAuth2LoginHelper->getAccessToken()->getRefreshTokenValidationPeriodInSeconds();
	    	$qbt->realmID 						 = $this->OAuth2LoginHelper->getAccessToken()->getRealmID();
	    	
	    	if($qbt->save()) {
	    		$message = "SUCCESSFULLY AUTHORIZED"; 
	    		$status  = "OK";
				return view('quickbooks.layouts.fallbackMessage', compact('status', 'message'));
	    	}

			$message = "ERROR AUTHORIZED"; 
	    	$status  = 'ERROR';
			return view('quickbooks.layouts.fallbackMessage', compact('status', 'message'));

    	} catch(\Exception $e) {
    		$message = $e->getMessage();
    		$status  = "ERROR";
    		return view('quickbooks.layouts.fallbackMessage', compact('status', 'message'));
    	}

    }
}