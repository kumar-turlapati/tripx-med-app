<?php

namespace PharmaRetail\Sales\Model;

use Atawa\ApiCaller;
use Atawa\Utilities;

class Sales 
{

	public function createSale($params = array()) 
	{
		$valid_result = $this->_validateFormData($params);
		if($valid_result['status'] === false) {
			return $valid_result;
		}

		$params['clientID'] = Utilities::get_current_client_id();

		// call api.
		$api_caller = new ApiCaller();
		$response = $api_caller->sendRequest('post','sales-entry',$params);
		$status = $response['status'];
		if ($status === 'success') {
			return array('status' => true,  'invoiceCode' => $response['response']['invoiceCode'], 'billNo' => $response['response']['billNo'] );
		} elseif($status === 'failed') {
			return array('status' => false, 'apierror' => $response['reason']);
		}
	}

	public function createSaleLc($params = array()) 
	{
		$valid_result = $this->_validateFormData($params);
		if($valid_result['status'] === false) {
			return $valid_result;
		}

		$params['clientID'] = Utilities::get_current_client_id();

		// call api.
		$api_caller = new ApiCaller();
		$response = $api_caller->sendRequest('post','/sales-entry-lc',$params);
		$status = $response['status'];
		if ($status === 'success') {
			return array('status' => true,  'invoiceCode' => $response['response']['invoiceCode'], 'billNo' => $response['response']['billNo'] );
		} elseif($status === 'failed') {
			return array('status' => false, 'apierror' => $response['reason']);
		}
	}	

	public function updateSale($params = array(), $invoice_code='') 
	{
		// $valid_result = $this->_validateFormData($params);
		// if($valid_result['status'] === false) {
		// 	return $valid_result;
		// }

		$params['clientID'] = Utilities::get_current_client_id();
		$params['invoiceCode'] = $invoice_code;
		
		// dump($params);
		// exit;

		// call api.
		$api_caller = new ApiCaller();
		$response = $api_caller->sendRequest('put','sales-entry',$params);
		$status = $response['status'];
		if ($status === 'success') {
			return array('status' => true);
		} elseif($status === 'failed') {
			return array('status' => false, 'apierror' => $response['reason']);
		}		
	}	

	private function _validateFormData($params = array()) {

		$api_params = $this->_getApiParams();
		$errors = array();

		// dump($params);
		// exit;

		// check for mandatory params
		$mand_param_errors = Utilities::checkMandatoryParams(array_keys($params), $api_params['mandatory']);
		if(is_array($mand_param_errors) && count($mand_param_errors)>0) {
			return array('status' => false, 'errors' => $this->_mapErrorMessages($mand_param_errors) );
		}

		// check for data in optional parameters
		if( isset($params['saleDate']) && $params['saleDate'] != '') {
			if(! Utilities::validateDate($params['saleDate'])) {
				$errors['saleDate'] = $this->_errorDescriptions('saleDate');
			}
		}

		if( isset($params['paymentMethod']) && $params['paymentMethod'] != '') {
			if(! is_numeric($params['paymentMethod'])) {
				$errors['paymentMethod'] = $this->_errorDescriptions('paymentMethod');
			}
		}

		if( isset($params['saleType']) && $params['saleType'] != '') {
			if(	$params['saleType'] != 'GEN' && 
				 	$params['saleType'] != 'OPS' && 
				 	$params['saleType'] != 'IPS'
				 ) 
			{
				$errors['saleType'] = $this->_errorDescriptions('saleType');
			}
		}

		if( isset($params['creditDays']) && $params['creditDays'] != '') {
			if(! is_numeric($params['creditDays'])) {
				$errors['creditDays'] = $this->_errorDescriptions('creditDays');
			}
		}

		// if( isset($params['discountPercent']) && $params['discountPercent'] != '') {
		// 	if(! is_numeric($params['discountPercent'])) {
		// 		$errors['discountPercent'] = $this->_errorDescriptions('discountPercent');
		// 	}
		// }

		if( isset($params['mobileNo']) && $params['mobileNo'] !== '') {
			if(!is_numeric($params['mobileNo'])) {
				$errors['mobileNo'] = $this->_errorDescriptions('mobileNo');
			}
		}

		if( isset($params['age']) && $params['age'] != '') {
			if(! is_numeric($params['age'])) {
				$errors['age'] = $this->_errorDescriptions('age');
			}
		}

		if( isset($params['ageCategory']) && $params['ageCategory'] !== '') {

			if(	$params['ageCategory'] !== 'years' && $params['ageCategory'] !== 'months' && 
				 	$params['ageCategory'] !== 'days'
				) 
			{
				$errors['ageCategory'] = $this->_errorDescriptions('ageCategory');
			}
		}

		if( isset($params['gender']) && $params['gender'] !== '') {
			if(	$params['gender'] !== 'm' && $params['gender'] !== 'f' && 
				 	$params['gender'] !== 'o'
				) 
			{
				$errors['gender'] = $this->_errorDescriptions('gender');
			}
		}

		# validate items information.
		$item_errors = array();
		$entered_items = 0;
		foreach($params['itemDetails']['itemName'] as $key => $item_name ) {
			if($item_name !== '') {
				if( !($params['itemDetails']['batchNo'][$key] !== 'xx99!!' && (int)$params['itemDetails']['itemQty'][$key]>0) ) {
					$item_errors[] = 'Invalid item details in Row No. '.($key+1);
				}
				$entered_items++;
			}
		}

		if($entered_items === 0) {
			$errors['itemDetails'] = 'At least one item is required for Sales transaction.';
		} else if(count($item_errors)>0) {
			$errors['itemDetails'] = implode(',', $item_errors);
		}

		if(count($errors)>0) {
			return array('status' => false, 'errors' => $errors);
		} else {
			return array('status' => true, 'errors' => $errors);
		}

	}

	private function _getApiParams() {
		$api_params = array(
			'mandatory' => array(
				// 'itemDetails' , 'status'
			),
			'optional' => array(
				'saleDate', 'saleType', 'paymentMethod', 'creditDays', 
				'registrationNo', 'patientID', 'doctorID', 'discountPercent',
				'addToOpening', 'mobileNo', 'age', 'ageCategory', 'gender','name'
			),			
		);

		return $api_params;
	}

	private function _mapErrorMessages($form_fields=array()) {

		$errors = array();
		foreach($form_fields as $key=>$field_name) {
			$errors[$field_name] = $this->_errorDescriptions($field_name);
		}

		return $errors;
	}

	private function _errorDescriptions($field_name = '') {

		$descriptions = array(
				'itemDetails' => 'Item details are mandatory',
				'status' => 'Status is required.',
				'saleDate' => 'Sale date should contain digits and dash (-)',
				'saleType' => 'Sale type is required and must be General, OP or IP',
				'paymentMethod' => 'Payment method is required and must be Cash, Credit or Credit card',
				'creditDays' => 'Credit days should contain digits only',
				'registrationNo' => '',
				'patientID' => '',
				'doctorID' => '',
				'discountPercent' => 'Discount percent must be numeric',
				'addToOpening' => '',
				'mobileNo' => 'Mobile number should be numeric with 10 digits',
				'age' => 'Age must be numeric',
				'ageCategory' => 'Age category must be Years, Months or Days',
				'gender' => 'Gender must be Male, Female or Others'
		);

		if($field_name != '') {
			return $descriptions[$field_name];
		} else {
			return $descriptions;
		}
	}

	public function get_doctors() {

		$client_id = Utilities::get_current_client_id();
		$params = array();

		// call api.
		$api_caller = new ApiCaller();
		$response = $api_caller->sendRequest('get','doctors/'.$client_id,$params);
		$status = $response['status'];
		if ($status === 'success') {
			return $response['response']['doctors'];
		} elseif($status === 'failed') {
			return array();
		}		
	}

	public function get_sales($page_no=1, $per_page=200, $search_params = []) {

		// fetch client id
		$client_id = Utilities::get_current_client_id();

		// call api.
		$api_caller = new ApiCaller();
		$response = $api_caller->sendRequest('get','sales-register/'.$client_id,$search_params);
		$status = $response['status'];
		if ($status === 'success') {
			return array(
				'status' => true,
				'sales' => $response['response']['sales'], 
				'total_pages' => $response['response']['total_pages'],
				'total_records' => $response['response']['total_records'],
				'record_count' =>  $response['response']['this_page'],
				'query_totals' => $response['response']['query_totals'],
			);
		} elseif($status === 'failed') {
			return array(
				'status' => false, 
				'apierror' => $response['reason']
			);
		}	
	}

	public function get_sales_by_patient($page_no=1, $per_page=200, $search_params = []) {

		// fetch client id
		$client_id = Utilities::get_current_client_id();

		// call api.
		$api_caller = new ApiCaller();
		$response = $api_caller->sendRequest('get','sales-by-customer',$search_params);
		$status = $response['status'];
		if ($status === 'success') {
			return array(
				'status' => true,
				'sales' => $response['response']['sales'], 
				'total_pages' => $response['response']['total_pages'],
				'total_records' => $response['response']['total_records'],
				'record_count' =>  $response['response']['this_page'],
				'query_totals' => $response['response']['query_totals'],
			);
		} elseif($status === 'failed') {
			return array(
				'status' => false, 
				'apierror' => $response['reason']
			);
		}	
	}	

	public function get_sales_details($invoice_code='', $by_bill_no=false) {
		// fetch client id
		$client_id = Utilities::get_current_client_id();
		if($by_bill_no) {
			$params['billNo'] = $invoice_code;
		} else {
			$params['invoiceCode'] = $invoice_code;
		}

		// call api.
		$api_caller = new ApiCaller();
		$response = $api_caller->sendRequest('get','sales-entry/'.$client_id,$params);
		$status = $response['status'];
		if ($status === 'success') {
			return array(
				'status' => true,  
				'saleDetails' => $response['response']['saleDetails'],
			);
		} elseif($status === 'failed') {
			return array(
				'status' => false, 
				'apierror' => $response['reason']
			);
		}
	}

	/**
	 * Remove Sales Transaction.
	**/
	public function removeSalesTransaction($sales_code='') {

		$client_id = Utilities::get_current_client_id();
		$end_point = 'sales-entry/'.$client_id.'/'.$sales_code;
		$params = array();

		// call api.
		$api_caller = new ApiCaller();
		$response = $api_caller->sendRequest('delete',$end_point,$params);
		$status = $response['status'];
		if ($status === 'success') {
			return array('status' => true);
		} elseif($status === 'failed') {
			return array('status' => false, 'apierror' => $response['reason']);
		}
	}

	public function search_sale_bills($search_params=array()) {
		$client_id = Utilities::get_current_client_id();
		$end_point = 'sales-entry/search/'.$client_id;

		// call api.
		$api_caller = new ApiCaller();
		$response = $api_caller->sendRequest('get',$end_point,$search_params);
		$status = $response['status'];
		if ($status === 'success') {
			return array(
				'status' => true,
				'bills' => $response['response']['bills']
			);
		} elseif($status === 'failed') {
			return array('status' => false, 'apierror' => $response['reason']);
		}
	}

	public function get_sales_summary_bymon($search_params) {

		$client_id = Utilities::get_current_client_id();
		$end_point = 'reports/sales-abs-mon/'.$client_id;

		// call api.
		$api_caller = new ApiCaller();
		$response = $api_caller->sendRequest('get',$end_point,$search_params);
		$status = $response['status'];
		if ($status === 'success') {
			return array(
				'status' => true,
				'summary' => $response['response']['daywiseSales']
			);
		} elseif($status === 'failed') {
			return array('status' => false, 'apierror' => $response['reason']);
		}
	}

	public function get_sales_summary_byday($search_params) {

		$client_id = Utilities::get_current_client_id();
		$end_point = 'reports/daily-sales/'.$client_id;

		// call api.
		$api_caller = new ApiCaller();
		$response = $api_caller->sendRequest('get',$end_point,$search_params);
		$status = $response['status'];
		if ($status === 'success') {
			return array(
				'status' => true,
				'summary' => $response['response']['daySales']
			);
		} elseif($status === 'failed') {
			return array('status' => false, 'apierror' => $response['reason']);
		}
	}

	public function get_patient_sales_summary($search_params=array()) {

		$client_id = Utilities::get_current_client_id();
		$end_point = 'reports/sales-summary-patient/'.$client_id;

		// call api.
		$api_caller = new ApiCaller();
		$response = $api_caller->sendRequest('get',$end_point,$search_params);
		$status = $response['status'];
		if ($status === 'success') {
			return array(
				'status' => true,
				'summary' => $response['response']
			);
		} elseif($status === 'failed') {
			return array('status' => false, 'apierror' => $response['reason']);
		}
	}	

	public function get_itemwise_sales_report($search_params=array()) {
		$client_id = Utilities::get_current_client_id();
		$end_point = 'reports/daily-item-sales/'.$client_id;

		// call api.
		$api_caller = new ApiCaller();
		$response = $api_caller->sendRequest('get',$end_point,$search_params);
		$status = $response['status'];
		if ($status === 'success') {
			return array(
				'status' => true,
				'summary' => $response['response']
			);
		} elseif($status === 'failed') {
			return array('status' => false, 'apierror' => $response['reason']);
		}
	}

	public function get_itemwise_sales_report_bymode($search_params=array()) {
		$client_id = Utilities::get_current_client_id();
		$end_point = 'reports/daily-item-sales-bymode/'.$client_id;

		// call api.
		$api_caller = new ApiCaller();
		$response = $api_caller->sendRequest('get',$end_point,$search_params);
		$status = $response['status'];
		if ($status === 'success') {
			return array(
				'status' => true,
				'summary' => $response['response']
			);
		} elseif($status === 'failed') {
			return array('status' => false, 'apierror' => $response['reason']);
		}
	}	

	public function get_credit_sales_report($search_params=array()) {
		$client_id = Utilities::get_current_client_id();
		$end_point = 'reports/credit-sales/'.$client_id;

		// call api.
		$api_caller = new ApiCaller();
		$response = $api_caller->sendRequest('get',$end_point,$search_params);
		$status = $response['status'];
		if ($status === 'success') {
			return array(
				'status' => true,
				'response' => $response['response']
			);
		} elseif($status === 'failed') {
			return array('status' => false, 'apierror' => $response['reason']);
		}
	}	

}