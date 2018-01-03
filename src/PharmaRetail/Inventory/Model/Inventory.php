<?php

namespace PharmaRetail\Inventory\Model;

use Atawa\ApiCaller;
use Atawa\Utilities;

class Inventory
{
	
	public function get_available_qtys($search_params=array()) {

		$params = array();		

		if(count($search_params)>0) {
			if(isset($search_params['pageNo'])) {
				$params['pageNo'] = $search_params['pageNo'];
			}
			if(isset($search_params['perPage'])) {
				$params['perPage'] = $search_params['perPage'];
			}
			if(isset($search_params['medName'])) {
				$params['medName'] = $search_params['medName'];
			}
			if(isset($search_params['batchNo'])) {
				$params['batchNo'] = $search_params['batchNo'];
			}				
		}

		// fetch client id
		$client_id = Utilities::get_current_client_id();
		$request_uri = 'inventory/qty-available/'.$client_id;

		// call api.
		$api_caller = new ApiCaller();
		$response = $api_caller->sendRequest('get',$request_uri,$params);

		$status = $response['status'];
		if ($status === 'success') {
			return array(
				'status' => true,  
				'items' => $response['response']['batchqtys']['results'], 
				'total_pages' => $response['response']['batchqtys']['total_pages'],
				'total_records' => $response['response']['batchqtys']['total_records'],
				'record_count' =>  $response['response']['batchqtys']['this_page']
			);				
		} elseif($status === 'failed') {
			return array(
				'status' => false, 
				'apierror' => $response['reason']
			);
		}
	}

	public function get_inventory_item_details($search_params=array()) {

		// fetch client id
		$client_id = Utilities::get_current_client_id();
		$request_uri = 'inventory/item-details/'.$client_id;

		// call api.
		$api_caller = new ApiCaller();
		$response = $api_caller->sendRequest('get',$request_uri,$search_params);

		$status = $response['status'];
		if ($status === 'success') {
			return array(
				'status' => true,  
				'item_details' => $response['response'],
			);				
		} elseif($status === 'failed') {
			return array(
				'status' => false, 
				'apierror' => $response['reason']
			);
		}		
	}

	public function get_stock_report($params) {
		// fetch client id
		$client_id = Utilities::get_current_client_id();
		$request_uri = 'reports/stock-report/'.$client_id;
		
		// call api.
		$api_caller = new ApiCaller();
		$response = $api_caller->sendRequest('get',$request_uri,$params);

		$status = $response['status'];
		if ($status === 'success') {
			return array(
				'status' => true,  
				'results' => $response['response'],
			);				
		} elseif($status === 'failed') {
			return array(
				'status' => false, 
				'apierror' => $response['reason']
			);
		}	
	}

	public function get_stock_report_new($params) {
		// fetch client id
		$client_id = Utilities::get_current_client_id();
		$request_uri = 'reports/stock-report-new/'.$client_id;
		
		// call api.
		$api_caller = new ApiCaller();
		$response = $api_caller->sendRequest('get',$request_uri,$params);

		$status = $response['status'];
		if ($status === 'success') {
			return array(
				'status' => true,  
				'results' => $response['response'],
			);				
		} elseif($status === 'failed') {
			return array(
				'status' => false, 
				'apierror' => $response['reason']
			);
		}	
	}

	public function get_expiry_report($params=array(),$page_no=1) {

		// fetch client id
		$client_id = Utilities::get_current_client_id();
		$request_uri = 'reports/expiry-report/'.$client_id;

		$params['pageNo'] = $page_no;


		
		// call api.
		$api_caller = new ApiCaller();
		$response = $api_caller->sendRequest('get',$request_uri,$params);
		$status = $response['status'];

		// dump($params);
		// echo '<pre>';
		// print_r($response);
		// echo '</pre>';
		// exit;

		if ($status === 'success') {
			return array(
				'status' => true, 
				'items' => $response['response']['results'], 
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

	public function get_inventory_adj_reasons($params=array()) {

		// fetch client id
		$client_id = Utilities::get_current_client_id();
		$request_uri = 'inventory/stock-adjustment-reasons';
		
		// call api.
		$api_caller = new ApiCaller();
		$response = $api_caller->sendRequest('get',$request_uri,$params);
		$status = $response['status'];
		if ($status === 'success') {
			return array(
				'status' => true,  
				'results' => $response['response'],
			);				
		} elseif($status === 'failed') {
			return array(
				'status' => false, 
				'apierror' => $response['reason']
			);
		}		
	}

	public function get_inventory_adj_entries($params=array()) {
		// fetch client id
		$client_id = Utilities::get_current_client_id();
		$request_uri = 'inventory/stock-adjustments-list/'.$client_id;
		
		// call api.
		$api_caller = new ApiCaller();
		$response = $api_caller->sendRequest('get',$request_uri,$params);
		$status = $response['status'];
		if ($status === 'success') {
			return array(
				'status' => true,
				'results' => $response['response'],
			);
		} elseif($status === 'failed') {
			return array(
				'status' => false, 
				'apierror' => $response['reason']
			);
		}
	}

	public function trash_expired_items($params=array()) {
		// fetch client id
		$client_id = Utilities::get_current_client_id();
		$request_uri = 'inventory/trash-expired-items/'.$client_id;
		
		// call api.
		$api_caller = new ApiCaller();
		$response = $api_caller->sendRequest('post',$request_uri,$params);
		$status = $response['status'];
		if ($status === 'success') {
			return array(
				'status' => true,  
				'results' => $response['response']['processed'],
			);				
		} elseif($status === 'failed') {
			return array(
				'status' => false, 
				'apierror' => $response['reason']
			);
		}		
	}

	public function get_stock_adj_report($params=array(),$page_no=1) {
		// fetch client id
		$client_id = Utilities::get_current_client_id();
		$request_uri = 'reports/stock-adj-report/'.$client_id;
		
		// call api.
		$api_caller = new ApiCaller();
		$response = $api_caller->sendRequest('get',$request_uri,$params);
		$status = $response['status'];
		if ($status === 'success') {
			return array(
				'status' => true,  
				'results' => $response['response'],
			);				
		} elseif($status === 'failed') {
			return array(
				'status' => false, 
				'apierror' => $response['reason']
			);
		}		
	}

	public function get_material_movement($params=array()) {
		// fetch client id
		$client_id = Utilities::get_current_client_id();
		$request_uri = 'reports/material-movement/'.$client_id;
		
		// call api.
		$api_caller = new ApiCaller();
		$response = $api_caller->sendRequest('get',$request_uri,$params);
		$status = $response['status'];
		if ($status === 'success') {
			return array(
				'status' => true,  
				'results' => $response['response'],
			);				
		} elseif($status === 'failed') {
			return array(
				'status' => false, 
				'apierror' => $response['reason']
			);
		}		
	}

	/**
	 * track item movement.
	**/
	public function track_item($params = array()) {
		// fetch client id
		$client_id = Utilities::get_current_client_id();
		$request_uri = 'inventory/track-item/'.$client_id;

		// call api.
		$api_caller = new ApiCaller();
		$response = $api_caller->sendRequest('post',$request_uri,$params);

		$status = $response['status'];
		if ($status === 'success') {
			return array(
				'status' => true,
				'items' => $response['response'],
			);				
		} elseif($status === 'failed') {
			return array(
				'status' => false,
				'apierror' => $response['reason']
			);
		}
	}

	public function io_analysis($params=array()) {
		// fetch client id
		$client_id = Utilities::get_current_client_id();
		$request_uri = 'reports/io-analysis/'.$client_id;

		// call api.
		$api_caller = new ApiCaller();
		$response = $api_caller->sendRequest('get',$request_uri,$params);

		// collect response
		$status = $response['status'];
		if ($status === 'success') {
			return array(
				'status' => true,
				'response' => $response['response'],
			);				
		} elseif($status === 'failed') {
			return array(
				'status' => false,
				'apierror' => $response['reason']
			);
		}		
	}

	public function item_master_with_pp($params=array()) {
		// fetch client id
		$client_id = Utilities::get_current_client_id();
		$request_uri = '/reports/inventory/items-list/'.$client_id;

		// call api.
		$api_caller = new ApiCaller();
		$response = $api_caller->sendRequest('get',$request_uri,$params);

		// collect response
		$status = $response['status'];
		if ($status === 'success') {
			return array(
				'status' => true,
				'response' => $response['response'],
			);				
		} elseif($status === 'failed') {
			return array(
				'status' => false,
				'apierror' => $response['reason']
			);
		}		
	}	


	/********************************** Threshold Items Qtys.**********************************
	*******************************************************************************************/
	
	public function add_threshold_qty($params=array()) {

		$valid_result = $this->_validate_th_formdata($params);
		if($valid_result['status'] === false) {
			return $valid_result;
		}

		// fetch client id
		$client_id = Utilities::get_current_client_id();
		$request_uri = 'inventory/threshold-invqty/'.$client_id;
		
		// call api.
		$api_caller = new ApiCaller();
		$response = $api_caller->sendRequest('post',$request_uri,$params);
		$status = $response['status'];
		if ($status === 'success') {
			return array(
				'status' => true,  
				'thrCode' => $response['response']['thrCode'],
			);				
		} elseif($status === 'failed') {
			return array(
				'status' => false, 
				'apierror' => $response['reason']
			);
		}
	}

	public function update_threshold_qty($params=array(),$thr_code='') {

		$valid_result = $this->_validate_th_formdata($params);
		if($valid_result['status'] === false) {
			return $valid_result;
		}

		// fetch client id
		$client_id = Utilities::get_current_client_id();
		$request_uri = 'inventory/threshold-invqty/'.$client_id.'/'.$thr_code;
		
		// call api.
		$api_caller = new ApiCaller();
		$response = $api_caller->sendRequest('put',$request_uri,$params);
		$status = $response['status'];
		if ($status === 'success') {
			return array(
				'status' => true,
			);				
		} elseif($status === 'failed') {
			return array(
				'status' => false, 
				'apierror' => $response['reason']
			);
		}
	}	

	public function list_threshold_qtys($params=array()) {

		// fetch client id
		$client_id = Utilities::get_current_client_id();
		$request_uri = 'inventory/threshold-invqty/'.$client_id;
		
		// call api.
		$api_caller = new ApiCaller();
		$response = $api_caller->sendRequest('get',$request_uri,$params);
		$status = $response['status'];
		if ($status === 'success') {
			return array(
				'status' => true,  
				'results' => $response['response'],
			);				
		} elseif($status === 'failed') {
			return array(
				'status' => false, 
				'apierror' => $response['reason']
			);
		}
	}

	public function get_threshold_itemqty_details($thr_code) {
		// fetch client id
		$client_id = Utilities::get_current_client_id();
		$request_uri = 'inventory/threshold-invqty/'.$client_id.'/'.$thr_code;
		
		// call api.
		$api_caller = new ApiCaller();
		$response = $api_caller->sendRequest('get',$request_uri,array());
		$status = $response['status'];
		if ($status === 'success') {
			return array(
				'status' => true,  
				'thrDetails' => $response['response']['thrDetails'],
			);				
		} elseif($status === 'failed') {
			return array(
				'status' => false, 
				'apierror' => $response['reason']
			);
		}		
	}

	public function get_item_thrlevel($search_params=array()) {
		// fetch client id
		$client_id = Utilities::get_current_client_id();
		$request_uri = 'reports/item-thrlevel/'.$client_id;
		
		// call api.
		$api_caller = new ApiCaller();
		$response = $api_caller->sendRequest('get',$request_uri,$search_params);
		$status = $response['status'];
		if ($status === 'success') {
			return array(
				'status' => true,  
				'response' => $response['response'],
			);				
		} elseif($status === 'failed') {
			return array(
				'status' => false, 
				'apierror' => $response['reason']
			);
		}		
	}

	public function update_batch_qtys($params=array()) {
		// fetch client id
		$client_id = Utilities::get_current_client_id();
		$request_uri = 'inventory/update-batch-qtys/'.$client_id;

		// call api.
		$api_caller = new ApiCaller();
		$response = $api_caller->sendRequest('put',$request_uri,$params);
		$status = $response['status'];
		// dump($response);
		// exit;
		
		if($status === 'success') {
			return array(
				'status' => true,  
				'results' => $response['response']['processed'],
			);				
		} elseif($status === 'failed') {
			return array(
				'status' => false, 
				'apierror' => $response['reason']
			);
		}
	}

	/** inventroy profitability **/
	public function inventory_profitability($search_params = array()) {
		// fetch client id
		$client_id = Utilities::get_current_client_id();
		$request_uri = 'reports/inventory-profitability/'.$client_id;

		// call api.
		$api_caller = new ApiCaller();
		$response = $api_caller->sendRequest('get',$request_uri,$search_params);
		$status = $response['status'];		
		if($status === 'success') {
			return array(
				'status' => true,  
				'results' => $response['response'],
			);				
		} elseif($status === 'failed') {
			return array(
				'status' => false, 
				'apierror' => $response['reason']
			);
		}
	}

	/********************************** Stock Adjustments **************************************
	*******************************************************************************************/

	public function add_stock_adjustment($params=array()) {

		$valid_result = $this->_validate_adjustment_form($params);
		if($valid_result['status'] === false) {
			return $valid_result;
		}

		// fetch client id
		$client_id = Utilities::get_current_client_id();
		$request_uri = 'inventory/stock-adjustment/'.$client_id;

		// call api.
		$api_caller = new ApiCaller();
		$response = $api_caller->sendRequest('post',$request_uri,$params);
		$status = $response['status'];
		if ($status === 'success') {
			return array(
				'status' => true,  
				'results' => $response['response']['response'],
			);				
		} elseif($status === 'failed') {
			return array(
				'status' => false, 
				'apierror' => $response['reason']
			);
		}
	}

	public function update_stock_adjustment($params=array(), $adj_code='') {

		// fetch client id
		$client_id = Utilities::get_current_client_id();
		$request_uri = 'inventory/stock-adjustment';

		// call api.
		$api_caller = new ApiCaller();
		$response = $api_caller->sendRequest('put',$request_uri,$params);
		$status = $response['status'];
		if ($status === 'success') {
			return array(
				'status' => true,  
				'results' => $response['response'],
			);				
		} elseif($status === 'failed') {
			return array(
				'status' => false, 
				'apierror' => $response['reason']
			);
		}
	}

	public function list_all_stock_adjustments($search_params=array()) {

	}

	private function _validate_adjustment_form($params=array()) {

		$errors = array();

		// check for data in optional parameters
		if( isset($params['itemName']) && $params['itemName'] == '') {
			$errors['itemName'] = 'Invalid Item name';
		}

		if( isset($params['batchNo']) && $params['batchNo'] == '') {
			$errors['batchNo'] = 'Batch No. is required';
		}

		if( isset($params['adjQty']) && !is_numeric($params['adjQty'])) {
			$errors['adjQty'] = 'Invalid Adjustment Qty';
		}

		if( isset($params['adjReasonCode']) && $params['adjReasonCode'] === '') {
			$errors['adjReasonCode'] = 'Invalid Adjustment Reason';
		}					

		if(count($errors)>0) {
			return array('status' => false, 'errors' => $errors);
		} else {
			return array('status' => true, 'errors' => $errors);
		}
	}

	private function _validate_th_formdata($params=array()) {

		$errors = array();

		// check for data in optional parameters
		if($params['itemName'] == '') {
			$errors['itemName'] = 'Invalid Item name';
		}

		if( $params['thrQty'] == '' || !is_numeric($params['thrQty']) ) {
			$errors['thrQty'] = 'Invalid Qty.';
		}

		if(count($errors)>0) {
			return array('status' => false, 'errors' => $errors);
		} else {
			return array('status' => true, 'errors' => $errors);
		}
	}	

}