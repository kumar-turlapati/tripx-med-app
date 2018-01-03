<?php 

namespace PharmaRetail\Sales\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Atawa\Utilities;
use Atawa\Constants;
use Atawa\Template;
use Atawa\Flash;

use PharmaRetail\Sales\Model\Sales;

class SalesController
{

  protected $views_path;

  public function __construct() {
  	$this->views_path = __DIR__.'/../Views/';
  }

  public function salesEntryAction(Request $request) {

    # initialize variables.
    $show_be = Utilities::show_batchno_expiry();    

    $errors = $sales_details = $submitted_data = array();
    $page_error = $page_success = '';
    $qtys_a = array(0=>'Sel');

    $tpl_name = $show_be?'sales-entry':'sales-entry-oc';
    $path_info = $request->getpathInfo();

    # check request source.
    $update_flag = strpos($path_info, 'sales/update');

    $ages_a[0] = 'Choose';
    for($i=1;$i<=150;$i++) {
      $ages_a[$i] = $i;
    }
    for($i=1;$i<=365;$i++) {
      $credit_days_a[$i] = $i;
    }
    for($i=1;$i<=500;$i++) {
      $qtys_a[$i] = $i;
    }

    # check for last bill printing.
    if($request->get('lastBill') && is_numeric($request->get('lastBill'))) {
      $bill_to_print = $request->get('lastBill');
    } else {
      $bill_to_print = 0;
    }

    # check for print format
    if( $request->get('pFormat') && $request->get('lastBill') && is_numeric($request->get('lastBill')) ) {
      $print_format   =   'bill';
    } else {
      $print_format   =   '';
    }

    # initialize models.
    $sales = new Sales;
    $flash = new Flash;

    # for retail medicine domain we show doctors list.
    $doctors_a = $show_be?[-1=>'Choose', 0=>'D.M.O']+$sales->get_doctors():[];

    # if update option
    if($update_flag) {
      if($request->get('salesCode') && $request->get('salesCode')!=='') {
        $sales_code = Utilities::clean_string($request->get('salesCode'));
        $sales_response = $sales->get_sales_details($sales_code);
        if($sales_response['status']===true) {
          $sales_details = $sales_response['saleDetails'];
          $tpl_name = $show_be?'sales-update':'sales-update-oc';
        } else {
          $page_error =   $sales_response['apierror'];
          $flash->set_flash_message($page_error,1);
          Utilities::redirect('/sales/entry');
        }
        $page_title = 'Update Sales Transaction { Bill No. '.$sales_details['billNo'].' }';
        $btn_label = 'Save';
      } else {
        $flash->set_flash_message('Invalid Sales transaction for edit operation',1);
        Utilities::redirect('/sales/entry');
      }
    } else {
      $btn_label = 'Save';
      $page_title = 'New Sales Transaction';
    }

    # check for form submission
    if(count($request->request->all()) > 0) {
      $submitted_data = $request->request->all();
      if(count($sales_details)>0) {
        $sales_response = $sales->updateSale($submitted_data,$sales_code);
      } else {
        $sales_response = $sales->createSale($submitted_data);
      }

      $status = $sales_response['status'];
      if($status === false) {
        if(isset($sales_response['errors'])) {
          if(isset($sales_response['errors']['itemDetails'])) {
            $page_error = $sales_response['errors']['itemDetails'];
            unset($sales_response['errors']['itemDetails']);
          }
          $errors = $sales_response['errors'];
        } elseif(isset($sales_response['apierror'])) {
          $page_error =   $sales_response['apierror'];
        }
      } elseif($update_flag) {
        $flash->set_flash_message('Sales transaction with Bill No. <b>'.$sales_details['billNo']. '</b> updated successfully');
        $redirect_url = $this->_printSalesBill($submitted_data['op'],$sales_details['billNo'],$sales_code);
        Utilities::redirect($redirect_url);
      } else {
        $flash->set_flash_message('Sales transaction saved successfully with Bill No. <b>'.$sales_response['billNo'].'</b>');
        $redirect_url = $this->_printSalesBill($submitted_data['op'],$sales_response['billNo']);
        Utilities::redirect($redirect_url);
      }
    } elseif(count($sales_details)>0) {
      $submitted_data = $sales_details;
    }

    # build variables
    $controller_vars = array(
      'page_title' => $page_title,
      'icon_name' => 'fa fa-inr',
    );
    
    # prepare form variables.
    $template_vars = array(
      'sale_types' => Constants::$SALE_TYPES,
      'sale_modes' => Constants::$SALE_MODES,
      'status' => Constants::$RECORD_STATUS,
      'doctors' => $doctors_a,
      'age_categories' => Constants::$AGE_CATEGORIES,
      'genders' => array(''=>'Choose') + Constants::$GENDERS,
      'payment_methods' => Constants::$PAYMENT_METHODS,
      'ages' => $ages_a,
      'credit_days_a' => array(0=>'Choose') +$credit_days_a,
      'qtys_a' => $qtys_a,
      'yes_no_options' => array(''=>'Choose', 1=>'Yes', 0=>'No'),
      'errors' => $errors,
      'page_error' => $page_error,
      'page_success' => $page_success,
      'btn_label' => $btn_label,
      'submitted_data' => $submitted_data,
      'bill_to_print' => $bill_to_print,
      'print_format' => $print_format,
      'show_be' => $show_be,
    );

    # render template
    $template = new Template($this->views_path);
    return array($template->render_view($tpl_name, $template_vars), $controller_vars);
  }

    public function salesViewAction(Request $request)
    {
        $errors = $sales_details = $submitted_data = array();
        $page_error = $page_success = '';
        $path_info = $request->getpathInfo();
        $qtys_a = array(0=>'Choose');
        $flash = new Flash();

        # initialize model.
        $sales = new Sales;

        if($request->get('salesCode') && $request->get('salesCode')!=='') {
            $sales_code = Utilities::clean_string($request->get('salesCode'));
            $sales_response = $sales->get_sales_details($sales_code);
            // dump($sales_response);
            if($sales_response['status']===true) {
                $sales_details = $sales_response['saleDetails'];
            } else {
                $page_error =   $sales_response['apierror'];
                $flash->set_flash_message($page_error,1);
                Utilities::redirect('/sales/entry');
            }
            $page_title = 'View Sales Transaction';
            $btn_label = 'Save';
        } else {
            $flash->set_flash_message('Invalid Sales code',1);
            Utilities::redirect('/sales/entry');
        }

        $doctors_a = array(-1=>'Choose', 0=>'D.M.O')+$sales->get_doctors();
        $submitted_data = $sales_details;

        // build variables
        $controller_vars = array(
            'page_title' => $page_title,
            'icon_name' => 'fa fa-inr',
        );
        
        $ages_a[0] = 'Choose';
        for($i=1;$i<=150;$i++) {
            $ages_a[$i] = $i;
        }
        for($i=1;$i<=365;$i++) {
            $credit_days_a[$i] = $i;
        }
        for($i=1;$i<=500;$i++) {
            $qtys_a[$i] = $i;
        }

        // prepare form variables.
        $template_vars = array(
            'sale_types' => Constants::$SALE_TYPES,
            'status' => Constants::$RECORD_STATUS,
            'doctors' => $doctors_a,
            'age_categories' => Constants::$AGE_CATEGORIES,
            'genders' => array(''=>'Choose') + Constants::$GENDERS,
            'payment_methods' => Constants::$PAYMENT_METHODS,
            'ages' => $ages_a,
            'credit_days_a' => array(0=>'Choose') +$credit_days_a,
            'qtys_a' => $qtys_a,
            'yes_no_options' => array(''=>'Choose', 1=>'Yes', 0=>'No'),
            'errors' => $errors,
            'page_error' => $page_error,
            'page_success' => $page_success,
            'btn_label' => $btn_label,
            'submitted_data' => $submitted_data,            
        );

        // render template
        $template = new Template($this->views_path);
        return array($template->render_view('sales-view', $template_vars), $controller_vars);
    }    

    public function salesListAction(Request $request)
    {

        $search_params = $sales_a = $query_totals = array();

        $total_pages = $total_records = $record_count = $page_no = 0;
        $slno = $to_sl_no = $page_links_to_start =  $page_links_to_end = 0;
        $page_success = $page_error = '';
        $sale_modes = Constants::$SALE_MODES;
        $payment_methods = Constants::$PAYMENT_METHODS;

        $page_no=1;
        $per_page=200;

        # check for filter variables.
        if(is_null($request->get('pageNo'))) {
          $search_params['pageNo'] = 1;
        } else {
          $search_params['pageNo'] = $page_no = (int)$request->get('pageNo');
        }
        if(is_null($request->get('perPage'))) {
          $search_params['perPage'] = 200;
        } else {
          $search_params['perPage'] = $per_page = (int)$request->get('perPage');
        }
        if(is_null($request->get('fromDate'))) {
          $search_params['fromDate'] = date("d-m-Y");
        } else {
          $search_params['fromDate'] = $request->get('fromDate');
        }
        if(is_null($request->get('toDate'))) {
          $search_params['toDate'] = date("d-m-Y");
        } else {
          $search_params['toDate'] = $request->get('toDate');
        }        
        if(is_null($request->get('saleMode'))) {
          $search_params['saleMode'] = '';
        } else {
          $search_params['saleMode'] = $request->get('saleMode');
        }
        if(is_null($request->get('paymentMethod'))) {
          $search_params['paymentMethod'] = 99;
        } elseif( !is_null($request->get('paymentMethod')) && (int)$request->get('paymentMethod')===99) {
          $search_params['paymentMethod'] = '';
        } else {
          $search_params['paymentMethod'] = $request->get('paymentMethod');
        }

        if($search_params['paymentMethod']===99) {
          $search_params['paymentMethod'] = '';
        }

        # initiate Model.
        $sales_model = new Sales;

        # Hit API.
        $sales_api_call = $sales_model->get_sales($page_no,$per_page,$search_params);
        $api_status = $sales_api_call['status'];  

        // dump($sales_api_call);

        # check api status
        if($api_status) {

            # check whether we got products or not.
            if(count($sales_api_call['sales'])>0) {
                $slno = Utilities::get_slno_start(count($sales_api_call['sales']), $per_page, $page_no);
                $to_sl_no = $slno+$per_page;
                $slno++;

                if($page_no<=3) {
                    $page_links_to_start = 1;
                    $page_links_to_end = 10;
                } else {
                    $page_links_to_start = $page_no-3;
                    $page_links_to_end = $page_links_to_start+10;            
                }

                if($sales_api_call['total_pages']<$page_links_to_end) {
                    $page_links_to_end = $sales_api_call['total_pages'];
                }

                if($sales_api_call['record_count'] < $per_page) {
                    $to_sl_no = ($slno+$sales_api_call['record_count'])-1;
                }

                $sales_a = $sales_api_call['sales'];
                $total_pages = $sales_api_call['total_pages'];
                $total_records = $sales_api_call['total_records'];
                $record_count = $sales_api_call['record_count'];
                $query_totals = $sales_api_call['query_totals'];
            } else {
                $page_error = $sales_api_call['apierror'];
            }

        } else {
            $page_error = $sales_api_call['apierror'];
        }           

         // prepare form variables.
        $template_vars = array(
            'sales' => $sales_a,
            'sale_types' => array(''=>'Sale category')+Constants::$SALE_TYPES,            
            'sale_modes' => array(''=>'All sale modes')+$sale_modes,
            'payment_methods' => array(99=>'All payment methods')+$payment_methods,
            'page_error' => $page_error,
            'page_success' => $page_success,
            'total_pages' => $total_pages ,
            'total_records' => $total_records,
            'record_count' =>  $record_count,
            'sl_no' => $slno,
            'to_sl_no' => $to_sl_no,
            'search_params' => $search_params,            
            'page_links_to_start' => $page_links_to_start,
            'page_links_to_end' => $page_links_to_end,
            'current_page' => $page_no,
            'query_totals' => $query_totals,
        );

        // build variables
        $controller_vars = array(
          'page_title' => 'Sales Register',
          'icon_name' => 'fa fa-inr',
        );

        // render template
        $template = new Template($this->views_path);
        return array($template->render_view('sales-register', $template_vars), $controller_vars);       
    }

    /**
     * Remove Sales Transaction.
    **/
    public function salesRemoveAction(Request $request)
    {
        if($request->get('salesCode') && $request->get('salesCode')!='') {
            $sales_code = Utilities::clean_string($request->get('salesCode'));
        } else {
            Utilities::set_flash_message("Invalid Sales Code",1);
            Utilities::redirect('/sales/list');
        }

        # initiate sales model.
        $sales = new Sales; 
        
        $sales_response = $sales->get_sales_details($sales_code);
        if($sales_response['status']===true) {
            $sales_details = $sales_response['saleDetails'];
        } else {
            Utilities::set_flash_message("Invalid Sales Code",1);
            Utilities::redirect('/sales/list');
        }

        $remove_response = $sales->removeSalesTransaction($sales_code);
        if($remove_response['status']===true) {
            $flash_message = "Sale transaction with code [$sales_code] removed successfully";
            Utilities::set_flash_message($flash_message);            
        } else {
            $flash_message = "Unable to remove Sales transaction. Please contact Administrator.";
            Utilities::set_flash_message($flash_message,1);
        }
        
        Utilities::redirect('/sales/list');
    }

    /**
     * Search sale bills.
    **/
    public function saleBillsSearchAction(Request $request) {

        $search_params = $bills = array();
        $search_by_a = array(
            'billno' => 'Bill No.',
            'date' => 'Date',
            'ipno' => 'IP No.',
            'opno' => 'OP No.',
            'name' => 'Name',
            'mobile' => 'Mobile No.',
        );        

        $slno = 0;
        $page_success = $page_error = '';

        # initiate Model.
        $sales_model = new Sales;        

        # check for filter variables.
        if(count($request->request->all()) > 0) {
            $search_params = $request->request->all();

            # Hit API.
            $sales_api_call = $sales_model->search_sale_bills($search_params);
            $api_status = $sales_api_call['status'];

            # check api status
            if($api_status) {
                # check whether we got products or not.
                if(count($sales_api_call['bills'])>0) {
                    $bills = $sales_api_call['bills'];
                } else {
                    $page_error = $sales_api_call['apierror'];
                }
            } else {
                $page_error = $sales_api_call['apierror'];
            }
        }        

         // prepare form variables.
        $template_vars = array(
            'bills' => $bills,
            'page_error' => $page_error,
            'page_success' => $page_success,
            'search_params' => $search_params,            
            'search_by_a' => array(''=>'Choose')+$search_by_a,
        );

        // build variables
        $controller_vars = array(
            'page_title' => 'Search Sale Bills',
            'icon_name' => 'fa fa-search',
        );

        // render template
        $template = new Template($this->views_path);
        return array($template->render_view('search-sale-bills', $template_vars), $controller_vars);
    }

    // function that will output Javascript to print Sales Bill
    private function _printSalesBill($op='',$bill_no='',$sales_code='') {
        if($sales_code !== '') {
            if($op==='SaveandPrint' && $bill_no>0) {
                return '/sales/update/'.$sales_code.'?lastBill='.$bill_no;
            } else {
                return '/sales/update/'.$sales_code.'?lastBill='.$bill_no;
            }
        } else {
            if($op==='SaveandPrint' && $bill_no>0) {
              return '/sales/entry?lastBill='.$bill_no;
            } elseif($op==='SaveandPrintBill' && $bill_no>0) {
              return '/sales/entry?lastBill='.$bill_no.'&pFormat=bill';
            } else {
              return '/sales/entry';
            }            
        }
    }

}