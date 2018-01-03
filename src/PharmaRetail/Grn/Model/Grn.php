<?php

namespace PharmaRetail\Grn\Model;

use Atawa\ApiCaller;
use Atawa\Utilities;

class Grn
{

	/** Creates GRN in the system **/
	public function createGrn($params=array(),$po_code='') {

		$valid_result = $this->_validateFormData($params);
		if($valid_result['status'] === false) {
			return $valid_result;
		}

		$client_id = Utilities::get_current_client_id();
		$po_code = $params['poCode'];

		$request_uri = 'grn/'.$client_id.'/'.$po_code;

		// call api.
		$api_caller = new ApiCaller();
		$response = $api_caller->sendRequest('post',$request_uri,$params);
		
		// dump($response);
		// exit;

		$status = $response['status'];
		if ($status === 'success') {
			return array('status' => true,  'grnCode' => $response['response']['grnCode']);
		} elseif($status === 'failed') {
			return array('status' => false, 'apierror' => $response['reason']);
		}		
	}

	/** Removes GRN from the system **/
	public function removeGrn($supplier_code='',$params = array()) {

		$params['clientID'] = Utilities::get_current_client_id();

		// call api.
		$api_caller = new ApiCaller();
		$response = $api_caller->sendRequest('delete', 'suppliers/'.$supplier_code, $params);
		$status = $response['status'];
		if ($status === 'success') {
			return array('status' => true);
		} elseif($status === 'failed') {
			return array('status' => false, 'apierror' => $response['reason']);
		}
	}

	/** get GRN Details by GRN No or GRN Code **/
	public function get_grn_details($grn_code = '', $fetch_by = '') {
		// fetch client id
		$client_id = Utilities::get_current_client_id();

		$request_uri = 'grn/details/'.$client_id.'/'.$grn_code.'?fetchBy='.$fetch_by;

		// call api.
		$api_caller = new ApiCaller();
		$response = $api_caller->sendRequest('get',$request_uri,array());
		$status = $response['status'];
		if ($status === 'success') {
			return array(
				'status' => true,  
				'grnDetails' => $response['response']['grnDetails'],
			);
		} elseif($status === 'failed') {
			return array(
				'status' => false, 
				'apierror' => $response['reason']
			);
		}
	}

	/** Get GRNs from the portal */
	public function get_grns($page_no=1,$per_page=100,$search_params=array()) {

		$params = array();
		$params['pageNo']  = $page_no;
		$params['perPage'] = $per_page;
		if(count($search_params)>0) {
			if(isset($search_params['fromDate'])) {
				$fromDate = Utilities::clean_string($search_params['fromDate']);
				$params['fromDate'] = $fromDate;
			}
			if(isset($search_params['toDate'])) {
				$toDate = Utilities::clean_string($search_params['toDate']);
				$params['toDate'] = $toDate;
			}			
			if(isset($search_params['supplierID'])) {
				$supplierID = Utilities::clean_string($search_params['supplierID']);
				$params['supplierID'] = $supplierID;
			}	
		}

		// dump($search_params);
		// exit;

		// fetch client id
		$client_id = Utilities::get_current_client_id();
		$request_uri = 'grn/register/'.$client_id;

		// call api.
		$api_caller = new ApiCaller();
		$response = $api_caller->sendRequest('get',$request_uri,$params);
		$status = $response['status'];
		if ($status === 'success') {
			return array(
				'status' => true,  
				'grns' => $response['response']['grns'], 
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

	/**************************************************************************************
	 Private functions should start from here.
	***************************************************************************************/
	private function _validateFormData($params = array()) {
		$api_params = $this->_getApiParams();
		$errors = array();

		// check for mandatory params
		$mand_param_errors = Utilities::checkMandatoryParams(array_keys($params), $api_params['mandatory']);
		if(is_array($mand_param_errors) && count($mand_param_errors)>0) {
			return array('status' => false, 'errors' => $this->_mapErrorMessages($mand_param_errors) );
		}

		if($params['billNo'] === '') {
			$errors['billNo'] = $this->_errorDescriptions('billNo');
		}

		if(count($errors)>0) {
			return array('status' => false, 'errors' => $errors);
		} else {
			return array('status' => true, 'errors' => $errors);
		}
	}

	private function _getApiParams() {
    $api_params = array(
    	'mandatory' => array('billNo'),
      'optional' => array()
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
				'billNo' => 'Bill No is mandatory',
				'itemDetails' => 'Item details are mandatory.',
		);

		if($field_name != '') {
			return $descriptions[$field_name];
		} else {
			return $descriptions;
		}
	}
}