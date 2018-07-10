<?php 

namespace PharmaRetail\Grn\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Atawa\Utilities;
use Atawa\Constants;
use Atawa\Template;
use Atawa\Flash;

use PharmaRetail\Grn\Model\Grn;
use PharmaRetail\Purchases\Model\Purchases;
use PharmaRetail\Suppliers\Model\Supplier;

class GrnController
{
	protected $views_path;

	public function __construct() 
    {
		$this->views_path = __DIR__.'/../Views/';
	}

    public function grnEntryAction(Request $request)
    {
        # initialize variables.
        $po_no = '';
        $submitted_data = $purchase_details = $suppliers = $suppliers_a = array();
        $search_params = array();

        $suppliers_a = array(''=>'Choose');      

        $qtys_a = array(0=>'Sel');
        for($i=1;$i<=1000;$i++) {
            $qtys_a[$i] = $i;
            if($i<=365) {
                $credit_days_a[$i] = $i;
            }
        }

        $supplier_api_call = new Supplier;
        $grn_api_call = new Grn;

        $suppliers = $supplier_api_call->get_suppliers(0,0,$search_params);
        if($suppliers['status']) {
            $suppliers_a += $suppliers['suppliers'];
        }

        # check for form post
        if(count($request->request->all()) > 0) {
            $flash = new Flash;
            $request_data = $this->_mapRequestData($request->request->all());
            $api_response = $grn_api_call->createGrn($request_data);
            // dump($api_response);
            // exit;
            $api_status = $api_response['status'];
            if($api_status === false) {
                if(isset($api_response['errors'])) {
                    $errors     =   implode(',',$api_response['errors']);
                } elseif(isset($api_response['apierror'])) {
                    $errors     =   $api_response['apierror'];
                }
                $flash->set_flash_message($errors,1);
            } else {
                $message = 'GRN created successfully with code ['.$api_response['grnCode'].']';
                $flash->set_flash_message($message);
            }
            Utilities::redirect('/grn/entry'); 
        }

        // prepare form variables.
        $template_vars = array(
            'suppliers' => $suppliers,
            'payment_methods' => Constants::$PAYMENT_METHODS_PURCHASE,
            'credit_days_a' => array(0=>'Choose') +$credit_days_a,
            'suppliers' => $suppliers_a,
            'qtys_a' => $qtys_a,                 
        );

        // build variables
        $controller_vars = array(
            'page_title' => 'GRN Entry',
            'icon_name' => 'fa fa-laptop',
        );

        // render template
        $template = new Template($this->views_path);
        return array($template->render_view('grn-entry',$template_vars),$controller_vars);
    }

    public function grnListAction(Request $request)
    {

        $suppliers= $search_params = $suppliers_a = $grns_a = array();

        $total_pages = $total_records = $record_count = $page_no = 0 ;
        $slno = $to_sl_no = $page_links_to_start =  $page_links_to_end = 0;
        $page_success = $page_error = '';

        $supplier_api_call = new Supplier;
        $grn_api_call = new Grn;
        if( $request->get('pageNo') ) {
            $page_no = $request->get('pageNo');
        } else {
            $page_no = 1;
        }

        if( $request->get('perPage') ) {
            $per_page = $request->get('perPage');
        } else {
            $per_page = 100;
        }

        if(count($request->request->all()) > 0) {
            $search_params = $request->request->all();
        } else {
            if(!is_null($request->get('fromDate'))) {
              $search_params['fromDate'] = $request->get('fromDate');
            }
            if(!is_null($request->get('toDate'))) {
              $search_params['toDate'] =  $request->get('toDate');
            }
            if(!is_null($request->get('supplierID'))) {
              $search_params['supplierID'] =  $request->get('supplierID');
            }
        }

        # search GRN from and to dates.
        if(!isset($search_params['fromDate'])) {
          $search_params['fromDate'] = '01-'.date('m').'-'.date("Y");
        }
        if(!isset($search_params['toDate'])) {
          $search_params['toDate'] = date("d-m-Y");
        }        

        $supplier_api_call = new Supplier;
        $suppliers = $supplier_api_call->get_suppliers(0,0);
        if($suppliers['status']) {
            $suppliers_a = $suppliers['suppliers'];
        }

        $grn_api_response = $grn_api_call->get_grns($page_no,$per_page,$search_params);
        $api_status = $grn_api_response['status'];     

        # check api status
        if($api_status) {

            # check whether we got products or not.
            if(count($grn_api_response['grns'])>0) {
                $slno = Utilities::get_slno_start(count($grn_api_response['grns']), $per_page, $page_no);
                $to_sl_no = $slno+$per_page;
                $slno++;

                if($page_no<=3) {
                    $page_links_to_start = 1;
                    $page_links_to_end = 10;
                } else {
                    $page_links_to_start = $page_no-3;
                    $page_links_to_end = $page_links_to_start+10;            
                }

                if($grn_api_response['total_pages']<$page_links_to_end) {
                    $page_links_to_end = $grn_api_response['total_pages'];
                }

                if($grn_api_response['record_count'] < $per_page) {
                    $to_sl_no = ($slno+$grn_api_response['record_count'])-1;
                }

                $grns_a = $grn_api_response['grns'];
                $total_pages = $grn_api_response['total_pages'];
                $total_records = $grn_api_response['total_records'];
                $record_count = $grn_api_response['record_count'];
            } else {
                $page_error = $grn_api_response['apierror'];
            }

        } else {
            $page_error = $grn_api_response['apierror'];
        }           

         // prepare form variables.
        $template_vars = array(
            'page_error' => $page_error,
            'page_success' => $page_success,
            'suppliers' => array(''=>'Choose')+$suppliers_a,
            'grns' => $grns_a,
            'total_pages' => $total_pages ,
            'total_records' => $total_records,
            'record_count' =>  $record_count,
            'sl_no' => $slno,
            'to_sl_no' => $to_sl_no,
            'search_params' => $search_params,            
            'page_links_to_start' => $page_links_to_start,
            'page_links_to_end' => $page_links_to_end,
            'current_page' => $page_no,
        );

        // build variables
        $controller_vars = array(
            'page_title' => 'GRN Register',
            'icon_name' => 'fa fa-laptop',
        );

        // render template
        $template = new Template($this->views_path);
        return array($template->render_view('grn-register', $template_vars), $controller_vars);       
    }

    public function grnDeleteAction(Request $request)
    {
    }

    public function grnViewAction(Request $request)
    {
        # initialize variables.
        $po_no = '';
        $submitted_data = $grn_details = $suppliers = $suppliers_a = array();
        $search_params = array();

        $suppliers_a = array(''=>'Choose');      

        $qtys_a = array(0=>'Sel');
        for($i=1;$i<=500;$i++) {
            $qtys_a[$i] = $i;
            if($i<=365) {
                $credit_days_a[$i] = $i;
            }
        }

        $supplier_api_call = new Supplier;
        $grn_api_call = new Grn;

        $suppliers = $supplier_api_call->get_suppliers(0,0,$search_params);
        if($suppliers['status']) {
            $suppliers_a += $suppliers['suppliers'];
        }

        if($request->get('grnCode') && $request->get('grnCode')!=='') {
            $grn_code = Utilities::clean_string($request->get('grnCode'));
            $grn_response = $grn_api_call->get_grn_details($grn_code);
            if($grn_response['status']===true) {
                $grn_details = $grn_response['grnDetails'];
            } else {
                $page_error =   $grn_response['apierror'];
                $flash->set_flash_message($page_error,1);
                Utilities::redirect('/grn/list');
            }
            $page_title = 'View GRN Transaction';
        } else {
            $flash->set_flash_message('Invalid GRN',1);
            Utilities::redirect('/grn/list');
        }        

        // prepare form variables.
        $template_vars = array(
            'suppliers' => $suppliers,
            'payment_methods' => Constants::$PAYMENT_METHODS_PURCHASE,
            'credit_days_a' => array(0=>'Choose') +$credit_days_a,
            'suppliers' => $suppliers_a,
            'qtys_a' => $qtys_a,   
            'grn_details' => $grn_details,
        );

        // build variables
        $controller_vars = array(
            'page_title' => 'View GRN Entry',
            'icon_name' => 'fa fa-laptop',
        );

        // render template
        $template = new Template($this->views_path);
        return array($template->render_view('grn-view-new',$template_vars),$controller_vars);        
    }    

    private function _mapRequestData($request_params = array()) {

        $formatted_data = $grn_items = array();

        foreach($request_params['grnItems'] as $key=>$grn_item) {
            if(isset($request_params['grnAccQty_'.$key])) {
                $grn_items[$grn_item] = $request_params['grnAccQty_'.$key];
            }
        }

        $formatted_data['billNo'] = $request_params['billNo'];
        $formatted_data['poCode'] = $request_params['poCode'];
        $formatted_data['grnDate'] = $request_params['grnDate'];      
        $formatted_data['grnItems'] = $grn_items;

        return $formatted_data;
    }

}