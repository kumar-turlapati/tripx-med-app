<?php 

namespace PharmaRetail\Async\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Atawa\Utilities;
use Atawa\ApiCaller;

class AsyncController
{
    public function asyncRequestAction(Request $request) {

        $api_caller = new ApiCaller();
        $api_string = Utilities::clean_string($request->get('apiString'));
        $client_id = Utilities::get_current_client_id();
        $params = array();

        if(count($request->request->all()) > 0 && $api_string==='getBatchNos') {
            $params['itemName'] = Utilities::clean_string($request->get('itemname'));
            $end_point = $this->_get_api_end_point($api_string);
            $response = $api_caller->sendRequest('get',$end_point.'/'.$client_id,$params);
            $api_status = $response['status'];
            if($api_status=='success') {
                $batch_nos = $response['response'];
            } else {
                $batch_nos = array();
            }
            Utilities::print_json_response($batch_nos);
        } elseif(count($request->request->all()) > 0 && $api_string==='getBatchNosWithLc') {
            $params['itemName'] = Utilities::clean_string($request->get('itemname'));
            $end_point = $this->_get_api_end_point($api_string);
            $response = $api_caller->sendRequest('get',$end_point.'/'.$client_id,$params);
            $api_status = $response['status'];
            if($api_status=='success') {
                $batch_nos = $response['response'];
            } else {
                $batch_nos = array();
            }
            Utilities::print_json_response($batch_nos);
        } elseif($api_string==='getPatientDetails') {
            $ref_no = $request->get('refNo');
            $by = $request->get('by');
            $patient_details = array();
            if($ref_no !== '') {
                $params['regNo'] = $ref_no;
                if($by==='mobile') {
                    $params['searchBy'] = 'mobile';
                }
                $end_point = $this->_get_api_end_point($api_string).'/'.$client_id;
                $response = $api_caller->sendRequest('get',$end_point,$params,false,true);
                Utilities::print_json_response($response,false);
            }
        } elseif($api_string==='itemsAc') {
            $params['q'] = $request->get('a');
            $response = $api_caller->sendRequest('get','products/ac',$params,false);
            if(count($response)>0 && is_array($response)) {
                echo implode($response,"\n");
            }
        } elseif($api_string==='poDetails') {
            $params['poNo'] = $request->get('poNo');
            $params['clientID'] = $client_id;
            $response = $api_caller->sendRequest('get','purchases',$params,false);
            if(is_array($response)){
                echo json_encode($response);
            } else {
                echo $response;
            }
        } elseif($api_string==='add-thr-qty') {
            $params['itemName'] = $request->get('mCode');
            $params['thrQty'] = $request->get('thQty');
            $params['byCode'] = true;
            $params['clientID'] = $client_id;
            if($params['thrQty']>=0) {
              $api_url = 'inventory/threshold-invqty/'.$client_id;
              $response = $api_caller->sendRequest('post',$api_url,$params,false);
              if(is_array($response)){
                echo json_encode($response);
              } else {
                echo $response;
              }
            }
        } elseif($api_string==='day-sales') {
            $params['saleDate'] = date("d-m-Y");
            $api_url = 'reports/daily-sales/'.$client_id;
            $response = $api_caller->sendRequest('get',$api_url,$params,false);
            header("Content-type: application/json");
            echo $response;
        } elseif($api_string==='monthly-sales') {
            $params['month'] = Utilities::clean_string($request->get('saleMonth'));
            $params['year'] = Utilities::clean_string($request->get('saleYear'));
            $api_url = 'reports/sales-abs-mon/'.$client_id;
            $response = $api_caller->sendRequest('get',$api_url,$params,false);
            header("Content-type: application/json");
            if(is_array($response)) {
              echo json_encode($response);
            } else {
              echo $response;
            }
        } elseif($api_string==='check-inward-item') {
            $params['iN'] = Utilities::clean_string($request->get('iN'));
            $api_url = 'inward-entry/item-history';
            $response = $api_caller->sendRequest('get',$api_url,$params,false);
            header("Content-type: application/json");            
            if(is_array($response)) {
              echo json_encode($response);
            } else {
              echo $response;
            }
        } elseif($api_string==='get-supplier-details' && ctype_alnum($request->get('c'))) {
            $params=[];
            $params['clientID'] = $client_id;
            $supplier_code = Utilities::clean_string($request->get('c'));
            $api_url = 'suppliers/details/'.$supplier_code;
            $response = $api_caller->sendRequest('get',$api_url,$params,false);
            header("Content-type: application/json");            
            if(is_array($response)) {
              echo json_encode($response);
            } else {
              echo $response;
            }                    
        }
        exit;
    }

    protected function _get_api_end_point($resource=null) {
        switch($resource) {
          case 'getBatchNos':
            return 'inventory/batchnos';
            break;
          case 'getBatchNosWithLc':
            return 'inventory/batchnos-lc';
            break;
          case 'getPatientDetails':
            return 'customers/ip-op-details';
            break;
          case '':
            return 'products/ac';
            break;
        }
    }
}