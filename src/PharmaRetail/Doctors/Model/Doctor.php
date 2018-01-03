<?php

namespace PharmaRetail\Doctors\Model;

use Atawa\ApiCaller;
use Atawa\Utilities;

class Doctor 
{

	public function createDoctor($params = array()) 
	{
		$valid_result = $this->_validateFormData($params);
		if($valid_result['status'] === false) {
			return $valid_result;
		}

		$client_id = Utilities::get_current_client_id();

		// call api.
		$api_caller = new ApiCaller();
		$response = $api_caller->sendRequest('post','doctors/'.$client_id,$params);
		$status = $response['status'];
		if ($status === 'success') {
			return array('status' => true,  'doctorCode' => $response['response']['doctorCode']);
		} elseif($status === 'failed') {
			return array('status' => false, 'apierror' => $response['reason']);
		}		
	}

	public function updateDoctor($params = array(), $doctor_code='') 
	{
		$valid_result = $this->_validateFormData($params);
		if($valid_result['status'] === false) {
			return $valid_result;
		}

		$client_id = Utilities::get_current_client_id();
		$request_uri = 'doctors/'.$client_id.'/'.$doctor_code;

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
		if(!Utilities::validateName($params['doctorName']) || $params['doctorName']=='' ) {
				$errors['doctorName'] = $this->_errorDescriptions('doctorName');
		}

		if( isset($params['mobile1']) && $params['mobile1'] != '') {
			if(!Utilities::validateMobileNo($params['mobile1'])) {
					$errors['mobile1'] = $this->_errorDescriptions('mobile1');
			}
		}

		if( isset($params['mobile2']) && $params['mobile2'] != '') {
			if(!Utilities::validateMobileNo($params['mobile2'])) {
					$errors['mobile2'] = $this->_errorDescriptions('mobile2');
			}
		}		

		if( isset($params['phone1']) && $params['phone1'] != '') {
			if(!is_numeric($params['phone1'])) 
			{
				$errors['phone1'] = $this->_errorDescriptions('phone1');
			}
		}

		if( isset($params['status']) && $params['status'] != '') {
			if(	$params['status']<0 || $params['status']>1 ) 
			{
				$errors['status'] = $this->_errorDescriptions('status');
			}
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
				'doctorName'
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
				'doctorName' => 'Doctor name is required/Invalid Doctor name',
				'mobile1' => 'Mobile1 should contain only digits',
				'mobile2' => 'Mobile2 should contain only digits',
				'status' => 'Status should be only Active or Inactive',
		);

		if($field_name != '') {
			return $descriptions[$field_name];
		} else {
			return $descriptions;
		}
	}

	public function get_doctor_details($doctor_code='') {

		// fetch client id
		$client_id = Utilities::get_current_client_id();

		$request_uri = 'doctors/details/'.$client_id.'/'.$doctor_code;

		// call api.
		$api_caller = new ApiCaller();
		$response = $api_caller->sendRequest('get',$request_uri);
		$status = $response['status'];
		if ($status === 'success') {
			return array(
				'status' => true,  
				'doctorDetails' => $response['response']['doctorDetails'],
			);
		} elseif($status === 'failed') {
			return array(
				'status' => false, 
				'apierror' => $response['reason']
			);
		}
	}

	public function get_doctors($page_no=1,$per_page=50,$search_params=array()) {

		$params = array();

		$params['pageNo']  = $page_no;
		$params['perPage'] = $per_page;
		$params['pageWise'] = true;		

		if(count($search_params)>0) {
			if(isset($search_params['suppName'])) {
				$supp_name = Utilities::clean_string($search_params['suppName']);
				$params['suppName'] = $supp_name;
			}
			if(isset($search_params['category'])) {
				$category = Utilities::clean_string($search_params['category']);
				$params['category'] = $category;
			}
		}

		// dump($search_params);
		// exit;

		// fetch client id
		$client_id = Utilities::get_current_client_id();

		// call api.
		$api_caller = new ApiCaller();
		$response = $api_caller->sendRequest('get','doctors/'.$client_id,$params);
		$status = $response['status'];
		if ($status === 'success') {
			return array(
				'status' => true,  
				'doctors' => $response['response']['results'], 
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