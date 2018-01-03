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

class SalesControllerGst
{
	protected $views_path;

	public function __construct() {
    $this->template = new Template(__DIR__.'/../Views/');
    $this->supplier_model = new Supplier;
    $this->taxes_model = new Taxes;
    $this->flash = new Flash;
    $this->purchase_model = new Purchases;
    $this->sales = new Sales;
	}

  /** GST Sales entry **/
  public function salesEntryGstAction(Request $request) {

    # -------- initialize variables -----------------
    $ages_a = $credit_days_a = $qtys_a = [];
    $submitted_data = $form_data = $errors = [];
    $doctors_a = $taxes = $loc_states = [];

    $page_error = $page_success = '';




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

    $states_a = Constants::$LOCATION_STATES;
    asort($states_a);    

    # ---------- check for business category and assign template ----
    $bc = Utilities::get_business_category();
    $tpl_name = (int)$bc === 1 ? 'sales-entry-gst' : 'sales-entry-gst-oc';

    # ---------- for retail medicine domain we show doctors list ----
    $doctors_a = (int)$bc === 1 ?[-1=>'Choose', 0=>'D.M.O']+$this->sales->get_doctors():[];    

    # ---------- get tax percents from api --------------------------
    $taxes_a = $this->taxes_model->list_taxes();
    if($taxes_a['status'] && count($taxes_a['taxes'])>0 ) {
      $taxes_raw = $taxes_a['taxes'];
      foreach($taxes_a['taxes'] as $tax_details) {
        $taxes[$tax_details['taxCode']] = $tax_details['taxPercent'];
      }
    }

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
      $form_data = $request->request->all();
      $validation = $this->_validate_form_data($form_data, $bc);
    }

    # --------------- build variables -----------------
    $controller_vars = array(
      'page_title' => 'Create Invoice',
      'icon_name' => 'fa fa-inr',
    );
    
    # ---------------- prepare form variables. ---------
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
      'btn_label' => 'Create Invoice',
      'taxes' => $taxes,
      'submitted_data' => $form_data,
      'bill_to_print' => $bill_to_print,
      'print_format' => $print_format,
      'states' => $states_a,
    );

    return array($this->template->render_view($tpl_name, $template_vars),$controller_vars);
  }

  /******************************* private functions should go from here ******************************/
  private function _validate_form_data($form_data=array(), $bc=1) {

  }


}