<?php

namespace PharmaRetail\Customers\Model;

use Atawa\ApiCaller;
use Atawa\Utilities;

class Customers 
{

	public function createCustomer($params = array()) 
	{
		$valid_result = $this->_validateFormData($params);
		if($valid_result['status'] === false) {
			return $valid_result;
		}

		$client_id = Utilities::get_current_client_id();

		// call api.
		$api_caller = new ApiCaller();
		$response = $api_caller->sendRequest('post','customers/'.$client_id,$params);
		$status = $response['status'];
		if ($status === 'success') {
			return array('status' => true,  'regCode' => $response['response']['regCode']);
		} elseif($status === 'failed') {
			return array('status' => false, 'apierror' => $response['reason']);
		}		
	}

	public function updateCustomer($params=array(), $reg_code='') 
	{
		$valid_result = $this->_validateFormData($params);
		if($valid_result['status'] === false) {
			return $valid_result;
		}

		$client_id = Utilities::get_current_client_id();
		$request_uri = 'customers/'.$client_id.'/'.$reg_code;

		// call api.
		$api_caller = new ApiCaller();
		$response = $api_caller->sendRequest('put',$request_uri,$params);

		$status = $response['status'];
		if ($status === 'success') {
			return array('status' => true);
		} elseif($status === 'failed') {
			return array('status' => false, 'apierror' => $response['reason']);
		}		
	}

	public function removeDoctor($doctor_code='') {

		$client_id = Utilities::get_current_client_id();
		$request_uri = 'doctors/'.$client_id.'/'.$doctor_code;

		// call api.
		$api_caller = new ApiCaller();
		$response = $api_caller->sendRequest('delete',$request_uri);
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

		// check for mandatory params
		$mand_param_errors = Utilities::checkMandatoryParams(array_keys($params), $api_params['mandatory']);
		if(is_array($mand_param_errors) && count($mand_param_errors)>0) {
			return array('status' => false, 'errors' => $this->_mapErrorMessages($mand_param_errors) );
		}

		// check for data in posted forms
		if(!Utilities::validateName($params['patientName']) || $params['patientName']=='' ) {
				$errors['patientName'] = $this->_errorDescriptions('patientName');
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
				'patientName'
			),
			'optional' => array(
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
			'patientName' => 'Customer name should contain only alphabets',
		);

		if($field_name != '') {
			return $descriptions[$field_name];
		} else {
			return $descriptions;
		}
	}

	public function get_customer_details($reg_code='') {

		// fetch client id
		$client_id = Utilities::get_current_client_id();

		$request_uri = 'customers/details/'.$client_id.'/'.$reg_code;

		// call api.
		$api_caller = new ApiCaller();
		$response = $api_caller->sendRequest('get',$request_uri);
		// dump($response);
		// exit;
		$status = $response['status'];
		if ($status === 'success') {
			return array(
				'status' => true,  
				'patientDetails' => $response['response']['patientDetails'],
			);
		} elseif($status === 'failed') {
			return array(
				'status' => false, 
				'apierror' => $response['reason']
			);
		}
	}

	public function get_customers($page_no=1,$per_page=100,$search_params=array()) {

		$params = array();

		$params['pageNo']  = $page_no;
		$params['perPage'] = $per_page;

		if(count($search_params)>0) {
			if(isset($search_params['custName'])) {
				$cust_name = Utilities::clean_string($search_params['custName']);
				$params['custName'] = $cust_name;
			}
		}

		// dump($search_params);
		// exit;

		// fetch client id
		$client_id = Utilities::get_current_client_id();

		// call api.
		$api_caller = new ApiCaller();
		$response = $api_caller->sendRequest('get','customers/'.$client_id,$params);
		$status = $response['status'];
		if ($status === 'success') {
			return array(
				'status' => true,  
				'customers' => $response['response']['customers'], 
				'total_pages' => $response['response']['total_pages'],
				'total_records' => $response['response']['total_records'],
				'record_count' =>  $response['response']['this_page']
			);				
		} elseif($status === 'failed') {
			return array(
				'status' => false, 
				'apierror' => $response['reason']
			);
		}	
	}	

}