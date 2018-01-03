<?php

namespace PharmaRetail\Grn\Model;

use Atawa\ApiCaller;
use Atawa\Utilities;

class GrnNew
{
	# create GRN api.
	public function createGRN($params=array()) {
		$client_id = Utilities::get_current_client_id();
		$request_uri = 'grn/v2/'.$client_id;
		// call api.
		$api_caller = new ApiCaller();
		$response = $api_caller->sendRequest('post',$request_uri,$params);
		$status = $response['status'];
		if ($status === 'success') {
			return array('status' => true,  'grnCode' => $response['response']['grnCode']);
		} elseif($status === 'failed') {
			return array('status' => false, 'apierror' => $response['reason']);
		}
	}
}