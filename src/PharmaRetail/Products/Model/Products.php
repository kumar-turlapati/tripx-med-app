<?php

namespace PharmaRetail\Products\Model;

use Atawa\ApiCaller;
use Atawa\Utilities;

class Products 
{

	public function get_products($page_no=1, $per_page=100, $search_params=array()) {

		$params = array();
		$params['pageNo']  = $page_no;
		$params['perPage'] = $per_page;
		if(count($search_params)>0) {
			if(isset($search_params['medname'])) {
				$med_name = Utilities::clean_string($search_params['medname']);
				$params['medName'] = $med_name;
			}
			if(isset($search_params['composition'])) {
				$composition = Utilities::clean_string($search_params['composition']);
				$params['composition'] = $composition;
			}
			if(isset($search_params['category'])) {
				$category = Utilities::clean_string($search_params['category']);
				$params['category'] = $category;
			}
			if(isset($search_params['mfg'])) {
				$mfg = Utilities::clean_string($search_params['mfg']);
				$params['mfg'] = $mfg;
			}				
		}

		// call api.
		$api_caller = new ApiCaller();
		$response = $api_caller->sendRequest('get', 'products', $params);

		$status = $response['status'];
		if ($status === 'success') {
			return array(
				'status' => true,  
				'products' => $response['response']['results'], 
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

	public function get_product_categories() {
		$api_caller = new ApiCaller();
		$response = $api_caller->sendRequest('get','categories');
		$status = $response['status'];
		if ($status === 'success') {
			return $response['response'];
		} elseif($status === 'failed') {
			return array();
		}
	}

	public function get_product_details($product_code='') {
		$api_caller = new ApiCaller();

		$end_point = 'products/details/'.$product_code;

		$response = $api_caller->sendRequest('get',$end_point);
		$status = $response['status'];
		if ($status === 'success') {
			return array(
				'status' => true,
				'productDetails' => $response['response']
			);
		} elseif($status === 'failed') {
			return array(
				'status' => false, 
				'apierror' => $response['reason']
			);
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
		if($params['itemName'] =='') {
				$errors['itemName'] = $this->_errorDescriptions('itemName');
		}

		if($params['unitsPerPack'] == '') {
			$errors['unitsPerPack'] = $this->_errorDescriptions('unitsPerPack');
		}

		if($params['hsnSacCode'] !== '' && (!is_numeric($params['hsnSacCode']) || strlen($params['hsnSacCode']) > 8) ) {
			$errors['hsnSacCode'] = 'Invalid HSN / SAC code.';
		}

		if(count($errors)>0) {
			return array('status' => false, 'errors' => $errors);
		} else {
			return array('status' => true, 'errors' => $errors);
		}

	}

	/**
	 * Create Product
	**/
	public function createProduct($params = array()) {
		$valid_result = $this->_validateFormData($params);
		if($valid_result['status'] === false) {
			return $valid_result;
		}

		// call api.
		$api_caller = new ApiCaller();
		$response = $api_caller->sendRequest('post','products',$params);

		$status = $response['status'];
		if ($status === 'success') {
			return array('status' => true, 'itemCode' => $response['response']['itemCode']);
		} elseif($status === 'failed') {
			return array('status' => false, 'apierror' => $response['reason']);
		}		
	}

	/**
	 * Update Product
	**/
	public function updateProduct($params=array(), $item_code='') {
		$valid_result = $this->_validateFormData($params);
		if($valid_result['status'] === false) {
			return $valid_result;
		}
		$end_point = 'products/'.$item_code;

		// call api.
		$api_caller = new ApiCaller();
		$response = $api_caller->sendRequest('put',$end_point,$params);
		$status = $response['status'];
		if ($status === 'success') {
			return array('status' => true, 'itemCode' => $response['response']['itemCode']);
		} elseif($status === 'failed') {
			return array('status' => false, 'apierror' => $response['reason']);
		}		
	}	

	private function _getApiParams() {
		$api_params = array(
			'mandatory' => array(
				'itemName',
				'unitsPerPack'
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
				'itemName' => 'Item name is required/Invalid Item name',
				'unitsPerPack' => 'Units per pack is required',
		);

		if($field_name != '') {
			return $descriptions[$field_name];
		} else {
			return $descriptions;
		}
	}	

}