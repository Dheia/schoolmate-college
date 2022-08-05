<?php

namespace App\Http\Controllers;

class BigBlueButton 

{
	private $bbb_url;
	private $bbb_secret;

	public function __contruct() {

		if(env('BBB_URL') && env('BBB_URL')){
			$bbb_url = env('BBB_URL');
			$bbb_secret = env('BBB_SHARED_SECRET');

		}else {
			abort(403);
		}

	}

	/**
     * @return ApiVersionResponse
     *
     * @throws \RuntimeException
     */
    public function getApiVersion()
    {
        $xml = $this->processXmlResponse($this->urlBuilder->buildUrl());

        return new ApiVersionResponse($xml);
    }


	public function createMeeting($meetingID){

	}


	 /**
     * A private utility method used by other public methods to process XML responses.
     *
     * @param  string            $url
     * @param  string            $payload
     * @param  string            $contentType
     * @return SimpleXMLElement
     * @throws \RuntimeException
     */
    private function processXmlResponse($url, $payload = '', $contentType = 'application/xml')
    {
        if (extension_loaded('curl')) {
            $ch = curl_init();
            if (!$ch) {
                throw new \RuntimeException('Unhandled curl error: ' . curl_error($ch));
            }
            $timeout = 10;

            // Needed to store the JSESSIONID
            $cookiefile     = tmpfile();
            $cookiefilepath = stream_get_meta_data($cookiefile)['uri'];

            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
            curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_COOKIEFILE, $cookiefilepath);
            curl_setopt($ch, CURLOPT_COOKIEJAR, $cookiefilepath);
            if (!empty($payload)) {
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Content-type: ' . $contentType,
                    'Content-length: ' . mb_strlen($payload),
                ]);
            }
            $data = curl_exec($ch);
            if ($data === false) {
                throw new \RuntimeException('Unhandled curl error: ' . curl_error($ch));
            }
            curl_close($ch);

            $cookies = file_get_contents($cookiefilepath);
            if (strpos($cookies, 'JSESSIONID') !== false) {
                preg_match('/(?:JSESSIONID\s*)(?<JSESSIONID>.*)/', $cookies, $output_array);
                $this->setJSessionId($output_array['JSESSIONID']);
            }

            return new SimpleXMLElement($data);
        } else {
            throw new \RuntimeException('Post XML data set but curl PHP module is not installed or not enabled.');
        }
    }


}


