<?php 

namespace PharmaRetail\Sales\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Atawa\Utilities;
use Atawa\Constants;
use Atawa\Template;
use Atawa\Flash;

use PharmaRetail\Sales\Model\Sales;
use PharmaRetail\Purchases\Model\Purchases;
use PharmaRetail\Suppliers\Model\Supplier;
use PharmaRetail\Taxes\Model\Taxes;

class SalesControllerAddOn
{
	protected $views_path;

	public function __construct() {
    $this->template = new Template(__DIR__.'/../Views/');
    $this->supplier_model = new Supplier;
    $this->flash = new Flash;
    $this->sales = new Sales;
	}

  /** Sales entry with landing cost **/
  public function salesEntryAction(Request $request) {

    # -------- initialize variables -----------------
    $ages_a = $credit_days_a = $qtys_a = [];
    $submitted_data = $form_data = $errors = [];
    $doctors_a = $taxes = $loc_states = [];

    $page_error = $page_success = '';

    $sale_modes = Constants::$SALE_MODES;
    $payment_methods = Constants::$PAYMENT_METHODS;

    unset($sale_modes[0]);
    unset($payment_methods[0]);
    unset($payment_methods[2]);

    # ---------- end of initializing variables -------
    $ages_a[0] = 'Choose';
    for($i=1;$i<=500;$i++) {
      if($i<=150) {
        $ages_a[$i] = $i;
      }
      if($i<=365) {
        $credit_days_a[$i] = $i;
      }
      $qtys_a[$i] = $i;
    }

    # ---------- for medicine domain we show doctors list ----
    $doctors_a = [-1=>'Choose', 0=>'D.M.O']+$this->sales->get_doctors();    

    # ---------- check for last bill printing ----
    if($request->get('lastBill') && is_numeric($request->get('lastBill'))) {
      $bill_to_print = $request->get('lastBill');
    } else {
      $bill_to_print = 0;
    }

    # ---------- check for print format ----
    if( $request->get('pFormat') && $request->get('lastBill') && is_numeric($request->get('lastBill')) ) {
      $print_format = 'bill';
    } else {
      $print_format = '';
    }

    # ------------------------------------- check for form Submission --------------------------------
    # ------------------------------------------------------------------------------------------------
    if(count($request->request->all()) > 0) {
      $submitted_data = $request->request->all();
      $sales_response = $this->sales->createSaleLc($submitted_data);
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
      } else {
        $this->flash->set_flash_message('Sales transaction saved successfully with Bill No. <b>'.$sales_response['billNo'].'</b>');
        $redirect_url = $this->_printSalesBill($submitted_data['op'],$sales_response['billNo']);
        Utilities::redirect($redirect_url);        
      }
    }

    # --------------- build variables -----------------
    $controller_vars = array(
      'page_title' => 'New Sales Transaction - With Landing Cost',
      'icon_name' => 'fa fa-inr',
    );
    
    # ---------------- prepare form variables. ---------
    $template_vars = array(
      'sale_types' => Constants::$SALE_TYPES,
      'sale_modes' => $sale_modes,
      'status' => Constants::$RECORD_STATUS,
      'doctors' => $doctors_a,
      'age_categories' => Constants::$AGE_CATEGORIES,
      'genders' => array(''=>'Choose') + Constants::$GENDERS,
      'payment_methods' => $payment_methods,
      'ages' => $ages_a,
      'credit_days_a' => array(0=>'Choose') +$credit_days_a,
      'qtys_a' => $qtys_a,
      'yes_no_options' => array(''=>'Choose', 1=>'Yes', 0=>'No'),
      'errors' => $errors,
      'page_error' => $page_error,
      'page_success' => $page_success,
      'btn_label' => 'Save',
      'taxes' => $taxes,
      'submitted_data' => $form_data,
      'bill_to_print' => $bill_to_print,
      'print_format' => $print_format,
    );

    return array($this->template->render_view('sales-entry-landing-cost', $template_vars),$controller_vars);
  }

  /******************************* private functions should go from here ******************************/
  private function _validate_form_data($form_data=array(), $bc=1) {

  }

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