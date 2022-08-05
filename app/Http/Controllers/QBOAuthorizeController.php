<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use QuickBooksOnline\API\DataService\DataService;

use App\QuickBooksTokenizer;

class QBOAuthorizeController extends Controller
{

	public $dataService;

    public function QBOAuthorize ()
    { 		
	    $dataService = DataService::Configure(array(
								'auth_mode' => 'oauth2',
								'ClientID' => 'Q0OSScHUtIGNhCKQCSxqvUNjBszWjLapScxgRg7ekJ5GFI9kGt',
								'ClientSecret' => 'OEjaHO2ezy4nxjysiQjTmJFjRHLB6kf4DLHRcyOZ',
								'RedirectURI' => request()->getSchemeAndHttpHost() . '/admin/quickbooks/token',
								'scope' => 'com.intuit.quickbooks.accounting', 
								'baseUrl' => 'Development',
							));

		$this->dataService = $dataService;

    	$OAuth2LoginHelper = $this->dataService->getOAuth2LoginHelper();
		$authorizationUrl = $OAuth2LoginHelper->getAuthorizationCodeURL();
		
		return redirect($authorizationUrl);
    }

    public function token (Request $request)
    {
    	dd($request);
    	$code = $request->code;
    	$realmId = $request->realmId;

    	$this->OAuth2LoginHelper = $this->dataService->getOAuth2LoginHelper();
    	$accessToken = $this->OAuth2LoginHelper->exchangeAuthorizationCodeForToken($code, $realmId);

    	$this->dataService->updateOAuth2Token($accessToken);

    	// dd($this->OAuth2LoginHelper->getAccessToken());
    	// dd($this->dataService->getOAuth2LoginHelper()->getAccessToken()->getRealmID());
    	// dd($this->dataService->getOAuth2LoginHelper()->getOAuth2AccessToken());

    	$qbt = new QuickBooksTokenizer;
    	$qbt->accessTokenKey 				 = $this->OAuth2LoginHelper->getAccessToken()->getAccessToken();
    	$qbt->refreshTokenKey 				 = $this->OAuth2LoginHelper->getAccessToken()->getRefreshToken();
    	$qbt->accessTokenExpiresAt  		 = $this->OAuth2LoginHelper->getAccessToken()->getAccessTokenExpiresAt();
    	$qbt->refreshTokenExpiresAt 		 = $this->OAuth2LoginHelper->getAccessToken()->getRefreshTokenExpiresAt();
    	$qbt->accessTokenValidationPeriod    = $this->OAuth2LoginHelper->getAccessToken()->getAccessTokenValidationPeriodInSeconds();
    	$qbt->refreshTokenValidationPeriod   = $this->OAuth2LoginHelper->getAccessToken()->getRefreshTokenValidationPeriodInSeconds();
    	$qbt->realmID 						 = $this->OAuth2LoginHelper->getAccessToken()->getRealmID();
    	$qbt->save();

    	return redirect('admin');
    }
}
