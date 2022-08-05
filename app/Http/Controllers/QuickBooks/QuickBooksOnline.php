<?php

namespace App\Http\Controllers\QuickBooks;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

use QuickBooksOnline\API\Core\ServiceContext;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\PlatformService\PlatformService;
use QuickBooksOnline\API\Core\Http\Serialization\XmlObjectSerializer;
use QuickBooksOnline\API\Facades\Item;

use App\Http\Controllers\Client;
use App\QuickBooksTokenizer;

class QuickBooksOnline
{
	public $dataService;
	public $OAuth2LoginHelper;
	public $accessTokenKey;
	public $refreshTokenKey;
	public $QBORealmID;

	public function initialize ()
	{

		$qbToken = QuickBooksTokenizer::latest()->first();

		$this->accessTokenKey  = null;
		$this->refreshTokenKey = null;
		$this->QBORealmID 	   = null;

		if($qbToken !== null) {

			$this->accessTokenKey 	= $qbToken->accessTokenKey;
			$this->refreshTokenKey  = $qbToken->refreshTokenKey;
			$this->QBORealmID 		= $qbToken->realmID;

			$accessTokenKeyDate  = Carbon::parse($qbToken->accessTokenExpiresAt);
			$refreshTokenKeyDate = Carbon::parse($qbToken->refreshTokenExpiresAt);

            if( !env('INTUIT_AUTH_MODE') || 
                !env('INTUIT_CLIENT_ID') || 
                !env('INTUIT_CLIENT_SECRET') || 
                !env('INTUIT_SCOPE') ||
                !env('INTUIT_BASE_URL') ) {
                return null;
            }

			$dataService = DataService::Configure(array(
								'auth_mode' 	  => env('INTUIT_AUTH_MODE'),
								'ClientID' 		  => env('INTUIT_CLIENT_ID'),
								'ClientSecret' 	  => env('INTUIT_CLIENT_SECRET'),
								'scope' 		  => env('INTUIT_SCOPE'),
								'baseUrl' 		  => env('INTUIT_BASE_URL'),
								'RedirectURI' 	  => env('APP_URL'). '/admin/quickbooks/token', 
								'accessTokenKey'  => $this->accessTokenKey,
							    'refreshTokenKey' => $this->refreshTokenKey,
							    'QBORealmID' 	  => $this->QBORealmID,
							));


			$this->dataService = $dataService;
			$this->dataService
				 ->getOAuth2LoginHelper()
				 ->getAccessToken()
				 ->updateAccessToken(strtotime($qbToken->accessTokenExpiresAt), $qbToken->refreshTokenKey, strtotime($qbToken->refreshTokenExpiresAt), $qbToken->accessTokenKey);

			
			$now = Carbon::now();

			if($now > $accessTokenKeyDate && $now < $refreshTokenKeyDate) 
            {
                try {
                    $resultObj = $this->dataService->getOAuth2LoginHelper()->refreshAccessTokenWithRefreshToken($this->refreshTokenKey);
                } catch (\Exception $e) {
                    abort(500, 'Unauthorized QuickBooks');
                }
				$this->updateAccessToken();
			}
			if ($now > $accessTokenKeyDate && $now > $refreshTokenKeyDate) 
            {
				$this->updateAccessToken();
			} 
		
        	return $this->dataService;
			
		} else {
            abort(500, 'Unauthorized QuickBooks');
		}
	}

	private function hasExistingToken ()
	{
		$qbToken = QuickBooksTokenizer::latest()->first();
		return $qbToken !== null ? true : false;
	}

    private function updateAccessToken() 
    {	
    	$previousAccessToken = QuickBooksTokenizer::orderBy('created_at', 'desc')->first();
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

    private function refreshToken ()
    {
    	$OAuth2LoginHelper  = $this->dataService->getOAuth2LoginHelper();
    	$refreshTokenObj 	= $OAuth2LoginHelper->refreshToken();
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

        if($QBOToken->count() > 0) 
        {
            QuickBooksTokenizer::truncate();
        }

	    $dataService = DataService::Configure(array(
								'auth_mode' 	=> env('INTUIT_AUTH_MODE'),
								'ClientID' 		=> env('INTUIT_CLIENT_ID'),
								'ClientSecret' 	=> env('INTUIT_CLIENT_SECRET'),
								'scope' 		=> env('INTUIT_SCOPE'),
								'baseUrl' 		=> env('INTUIT_BASE_URL'),
								'RedirectURI' 	=> env('APP_URL'). '/admin/quickbooks/token',
							));

		$this->dataService = $dataService;

    	$OAuth2LoginHelper = $this->dataService->getOAuth2LoginHelper();
		$authorizationUrl  = $OAuth2LoginHelper->getAuthorizationCodeURL();
		
		return redirect($authorizationUrl);
    }

    public function token (Request $request)
    {
        
    	try {

            $code                    = $request->code;
            $realmId                 = $request->realmId;
            $this->OAuth2LoginHelper = $this->dataService->getOAuth2LoginHelper();
            $accessToken             = $this->OAuth2LoginHelper->exchangeAuthorizationCodeForToken($code, $realmId);

            $this->dataService->updateOAuth2Token($accessToken);

            $qbt                                 = new QuickBooksTokenizer;
            $qbt->accessTokenKey                 = $this->OAuth2LoginHelper->getAccessToken()->getAccessToken();
            $qbt->refreshTokenKey                = $this->OAuth2LoginHelper->getAccessToken()->getRefreshToken();
            $qbt->accessTokenExpiresAt           = $this->OAuth2LoginHelper->getAccessToken()->getAccessTokenExpiresAt();
            $qbt->refreshTokenExpiresAt          = $this->OAuth2LoginHelper->getAccessToken()->getRefreshTokenExpiresAt();
            $qbt->accessTokenValidationPeriod    = $this->OAuth2LoginHelper->getAccessToken()->getAccessTokenValidationPeriodInSeconds();
            $qbt->refreshTokenValidationPeriod   = $this->OAuth2LoginHelper->getAccessToken()->getRefreshTokenValidationPeriodInSeconds();
            $qbt->realmID                        = $this->OAuth2LoginHelper->getAccessToken()->getRealmID();
            
            if($qbt->save()) {
                $message = "SUCCESSFULLY AUTHORIZED"; 
                $status  = "OK";
                return view('quickbooks.layouts.fallbackMessage', compact('status', 'message'));
            }
            abort(500, 'Unauthorize QuickBooks');
            $message = "ERROR AUTHORIZED"; 
            $status  = 'ERROR';
            return view('quickbooks.layouts.fallbackMessage', compact('status', 'message'));

        } catch(\Exception $e) {
            abort(500, $e->getMessage());
        }

    
    }

    public function dataService()
    {
    	return $this->dataService;
    }



    public function bindParentServices ($name)
    {
    	$name 		= ucwords(str_replace('-', ' ', $name));
        $isInArrray = false;
        $configObj  = null;
        try {
            $configObj = config('QBOMandatoryToBind')[$name];
            $isInArrray = true;

        } catch (\Exception $e) {
            $isInArrray = false;
        }

    	if($isInArrray)
    	{	
        	$qbo  			= new QuickBooksOnline;
	    	$dataService    = $qbo->initialize();

            // CHECK IF DATASERVICE IS NULL THEN RETURN UNAUTHORIZED
            if($dataService === null)
            {
                $status  = "ERROR";
                $message = "Unauthorized QuickBooks";

                return view('quickbooks.layouts.fallbackMessage', compact('status', 'message'));
            }

	        $itemsObj 		= $dataService->Query('SELECT * FROM Item');
	        $isExist 		= count(collect($itemsObj)->where('Name', $name)); 

            $dataService->setMinorVersion(4);

	        if($isExist == 0)
	        {
	        	$accountsObj = $dataService->Query("SELECT * FROM Account WHERE Name = 'Services'");

                $items       =  [
                                    "Name"              => $name,
                                    "Type"              => "Category", // CATEGORY / SERVICE
                                    "Sku"               => $configObj['sku'],
                                    "IncomeAccountRef"  =>  [
                                                                "name"  => $accountsObj[0]->Name,
                                                                "value" => $accountsObj[0]->Id
                                                            ]
                                ]; 

	        	$theResourceObj = Item::create($items);
		        $resultingObj   = $dataService->Add($theResourceObj);
		        $error          = $dataService->getLastError();

		        if ($error) {

                    $status  = "ERROR";
                    $message = $error->getResponseBody();
                    return view('quickbooks.layouts.fallbackMessage', compact('status', 'message'));
                    
		        }
		        else {    

                    $status  = "OK";
                    $message = "Successfully Binded To QBO Of " . $name;
		            return view('quickbooks.layouts.fallbackMessage', compact('status', 'message'));
                    return redirect()->to('admin/quickbooks/products-and-services');

		        }

	        }
	        else
	        {	
	        	$status  = "ERROR";
	        	$message = "This " . $name . " Is Already Binded To QBO";
	        	return view('quickbooks.layouts.fallbackMessage', compact('status', 'message'));
	        }

    	}

    	$status  = "ERROR";
    	$message = "This " . $name . " Is Not Eligible To Bind This In QBO";
    	return view('quickbooks.layouts.fallbackMessage', compact('status', 'message'));
    } 


    public function bindChildServices ($model, $id)
    {
        $otherService = $model->crud->model::where('id', $id)->with('schoolYear')->firstOrFail();
        // otherService
        if($otherService == null) {
            \Alert::success('Service Data Not Found')->flash();
            return redirect()->back();
        }

        if ($otherService->qbo_id !== null) {
            \Alert::success('This Service Is Already Registered')->flash();
            return redirect()->back();
        }

        $dataService = $this->initialize();
        // CHECK IF DATASERVICE IS NULL THEN RETURN UNAUTHORIZED
        if($dataService === null)
        {
            $status  = "ERROR";
            $message = "Unauthorized QuickBooks";

            return view('quickbooks.layouts.fallbackMessage', compact('status', 'message'));
        }

        $name        = ucwords(str_replace('-', ' ', $model->crud->entity_name_plural));

        // First: Query The Item And Incom Account Service Entity From QuickBooks
        $parentItemObj    = $dataService->Query("SELECT * FROM Item WHERE Name = '" . $name . "'");
        $itemOfParentObj  = $dataService->Query("SELECT * FROM Item WHERE ParentRef = '" . $parentItemObj[0]->Id . "' ORDER BY Sku DESC");
        $incomeAccountObj = $dataService->Query("SELECT * FROM Account WHERE Name = 'Services'");

        // GET SKU INCREMENTING 
        $sku = $parentItemObj[0]->Sku . '-0001';
        if($itemOfParentObj !== null) {
            if($itemOfParentObj[0]->Sku == null) {
                $sku = $sku;
            } else {
                $extractedSku = str_replace('-', '', $itemOfParentObj[0]->Sku);
                $incremented = (int)$extractedSku + 1;

                // reform to its original pattern (ex. 1-0001, 1-0002, etc...)
                $leftSide  = $parentItemObj[0]->Sku;
                $rightSide = substr($incremented, 1);
                $sku = $leftSide . '-' . $rightSide;
            }
        }

        $items  =   [
                        "SubItem"   => true, 
                        "Type"      => "Service", 
                        "Name"      => $otherService->name_with_school_year, 
                        "Sku"       => $sku,
                        "UnitPrice" => $otherService->amount, 
                        "ParentRef" => 
                        [
                            "name"  => $parentItemObj[0]->Name, 
                            "value" => $parentItemObj[0]->Id
                        ],
                        "IncomeAccountRef"  => [
                            "name"  => $incomeAccountObj[0]->Name,
                            "value" => $incomeAccountObj[0]->Id
                        ]
                    ];

        $theResourceObj = Item::create($items);

        $resultingObj = $dataService->Add($theResourceObj);
        $error        = $dataService->getLastError();

        if ($error) {
            $status   = "OK";
            $message = $error->getResponseBody(); 
            return view('quickbooks.layouts.fallbackMessage', compact('status', 'message'));
        }
        else {
            $otherService->qbo_id = $resultingObj->Id;
            $otherService->save();
            \Alert::success("Successfully Added To QuickBooks")->flash();
            return redirect()->back();
        }
    }
}