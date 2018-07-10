<?php

namespace PharmaRetail\Discount\Model;

use Atawa\ApiCaller;
use Atawa\Utilities;

class Discount {

	public function add_discount_percent($form_data=array()) {
		$client_id = Utilities::get_current_client_id();
		$api_caller = new ApiCaller();
		$response = $api_caller->sendRequest('post','discount-percent',$form_data);
		$status = $response['status'];
		if ($status === 'success') {
			return array('status' => true, 'discountCode' => $response['response']['discountCode']);
		} elseif($status === 'failed') {
			return array('status' => false, 'apierror' => $response['reason']);
		}
	}

	public function update_discount_percent($form_data=array(), $discount_code='') {
		$client_id = Utilities::get_current_client_id();
		$api_caller = new ApiCaller();
		$response = $api_caller->sendRequest('put','discount-percent/'.$discount_code,$form_data);
		$status = $response['status'];
		if ($status === 'success') {
			return array('status' => true);
		} elseif($status === 'failed') {
			return array('status' => false, 'apierror' => $response['reason']);
		}
	}

	public function list_discount_percents() {
		$api_caller = new ApiCaller();
		$client_id = Utilities::get_current_client_id();
		$response = $api_caller->sendRequest('get','discount-percents/'.$client_id,array());
		if($response['status']==='success') {
			return ['status'=>true,'taxes'=>$response['response']];
		} else {
			return ['status'=>true,'taxes'=>[]];
		}
	}

	public function get_discount_percent_details($tax_code='') {
		$api_caller = new ApiCaller();
		$client_id = Utilities::get_current_client_id();
		$response = $api_caller->sendRequest('get','discount-percent/'.$discount_code,[]);
		if($response['status']==='success') {
			return ['status'=>true,'tax_details'=>$response['response']];
		} else {
			return ['status'=>false,'apierror'=>$response['reason']];
		}
	}
}