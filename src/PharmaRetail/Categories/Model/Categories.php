<?php

namespace PharmaRetail\Categories\Model;

use Atawa\ApiCaller;
use Atawa\Utilities;

class Categories 
{

	public function get_categories($page_no=1, $per_page=100, $search_params=array()) {

		$params = array();
		$params['pageNo']  = $page_no;
		$params['perPage'] = $per_page;
		if(count($search_params)>0) {
			if(isset($search_params['catname'])) {
				$cat_name = Utilities::clean_string($search_params['catname']);
				$params['catName'] = $cat_name;
			}				
		}

		// call api.
		$api_caller = new ApiCaller();
		$response = $api_caller->sendRequest('get', 'categories/wic', $params);
		$status = $response['status'];
		if ($status === 'success') {
			return array(
				'status' => true,  
				'categories' => $response['response']
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
		$response = $api_caller->sendRequest('get', 'categories');
		$status = $response['status'];
		if ($status === 'success') {
			return $response['response'];
		} elseif($status === 'failed') {
			return array();
		}
	}

}