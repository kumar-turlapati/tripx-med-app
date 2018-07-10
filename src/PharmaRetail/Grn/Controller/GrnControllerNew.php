<?php 

namespace PharmaRetail\Grn\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Atawa\Utilities;
use Atawa\Constants;
use Atawa\Template;
use Atawa\Flash;

use PharmaRetail\Grn\Model\GrnNew;
use PharmaRetail\Purchases\Model\Purchases;
use PharmaRetail\Suppliers\Model\Supplier;

class GrnControllerNew 
{
  protected $views_path;

  public function __construct() {
    $this->template = new Template(__DIR__.'/../Views/');
    $this->flash = new Flash;
    $this->purchase_model = new Purchases;
    $this->supplier_model = new Supplier;
    $this->grn_model = new GrnNew;    
  }

  /** Grn Entry Action **/
  public function grnEntryCreateAction(Request $request) {
    
    if( is_null($request->get('poNo')) ) {
      $this->flash->set_flash_message('PO Number is required to generate a GRN.', 1);
      Utilities::redirect('/grn/list');
    } else {
      $po_no = Utilities::clean_string($request->get('poNo'));
    }

    # initialize variables.
    $form_data = $form_errors = $suppliers_a = array();
    $total_item_rows = 0;
    $api_error = '';

    if( count($request->request->all()) > 0 ) {
      $submitted_data = $request->request->all();
      $validation_status = $this->_validate_form_data($submitted_data);
      if($validation_status['status']===true) {
        $cleaned_params = $validation_status['cleaned_params'];
        $cleaned_params['poNo'] = $po_no;
        # hit api
        $api_response = $this->grn_model->createGRN($cleaned_params);
        if($api_response['status']===true) {
          $message = 'GRN created successfully with entry code ` '.$api_response['grnCode'].' `';
          $this->flash->set_flash_message($message);
          Utilities::redirect('/grn/list');
        } else {
          $api_error = $api_response['apierror'];
          $form_data = $submitted_data;
        }
      } else {
        $form_errors = $validation_status['form_errors'];
        $form_data = $submitted_data;
      }
    }

    # get PO Details based on PO Number;
    $purchase_response = $this->purchase_model->get_purchase_details($po_no,true);
    if($purchase_response['status']===true) {
      $purchase_details = $purchase_response['purchaseDetails'];

      # convert received item details to template item details.
      $item_names = array_column($purchase_details['itemDetails'],'itemName');
      $inward_qtys = array_column($purchase_details['itemDetails'],'itemQty');
      $free_qtys = array_column($purchase_details['itemDetails'],'freeQty');
      $batch_nos = array_column($purchase_details['itemDetails'],'batchNo');
      $ex_months = array_column($purchase_details['itemDetails'],'expdateMonth');
      $ex_years = array_column($purchase_details['itemDetails'],'expdateYear');
      $mrps = array_column($purchase_details['itemDetails'],'mrp');
      $item_rates = array_column($purchase_details['itemDetails'],'itemRate');
      $tax_percents = array_column($purchase_details['itemDetails'],'vatPercent');
      $item_codes = array_column($purchase_details['itemDetails'],'itemCode');
      $discounts = array_column($purchase_details['itemDetails'], 'discount');
      foreach($ex_months as $key=>$value) {
        if($value<10) {
          $value = '0'.$value;
        }
        $exp_dates[] = $value.'/'.$ex_years[$key];
      }

      # unser item details from api data.
      unset($purchase_details['itemDetails']);

      # create form data variable.
      $form_data = $purchase_details;
      if(isset($form_data['adjAmount'])) {
        $form_data['adjustment'] = $form_data['adjAmount'];
        unset($form_data['adjAmount']);
      } else {
        $form_data['adjustment'] = 0;
      }

      $form_data['itemName'] = $item_names;
      $form_data['inwardQty'] = $inward_qtys;
      $form_data['freeQty'] = $free_qtys;
      $form_data['batchNo'] = $batch_nos;
      $form_data['expDate'] = $exp_dates;
      $form_data['itemRate'] = $item_rates;
      $form_data['taxPercent'] = $tax_percents;
      $form_data['mrp'] = $mrps;
      $form_data['itemCode'] = $item_codes;
      $form_data['discounts'] = $discounts;

    # invalid PO No. redirect user.
    } else {
      $this->flash->set_flash_message('Invalid PO No (or) PO does not exists.');
      Utilities::redirect('purchase/list');
    }

    # loop through credit days
    for($i=1;$i<=365;$i++) {
      $credit_days_a[$i] = $i;
    }

    # get suppliers list
    $suppliers = $this->supplier_model->get_suppliers(0,0,[]);
    if($suppliers['status']) {
      $suppliers_a += $suppliers['suppliers'];
    }

    # theme variables.
    $controller_vars = array(
      'page_title' => 'Godown Receipt Note',
      'icon_name' => 'fa fa-laptop',
    );
    $template_vars = array(
      'utilities' => new Utilities,      
      'form_errors' => $form_errors,
      'form_data' => $form_data,
      'credit_days_a' => array(0=>'Choose')+$credit_days_a,
      'suppliers' => array(''=>'Choose')+$suppliers_a,
      'payment_methods' => Constants::$PAYMENT_METHODS_PURCHASE,
      'total_item_rows' => count($form_data['itemName']),
      'api_error' => $api_error,
    );

    return array($this->template->render_view('grn-create',$template_vars),$controller_vars);
  }

/******************************* Private functions should start from here ***********************/
  private function _validate_form_data($submitted_data=array()) {
    $form_errors = $cleaned_params = [];
    if(isset($submitted_data['billNo']) && $submitted_data['billNo'] != '' ) {
      $cleaned_params['billNo'] = Utilities::clean_string($submitted_data['billNo']);
    } else {
      $form_errors['billNo'] = 'Bill No. is mandatory for GRN';
    }

    if( isset($submitted_data['acceptedQty']) && count($submitted_data['acceptedQty'])>0 ) {
      foreach($submitted_data['acceptedQty'] as $item_code=>$qty) {
        if(!is_numeric($qty)  || $qty<0) {
          $form_errors['acceptedQty'][$item_code] = 'Invalid Qty';
        } else {
          $cleaned_params['grnItems'][$item_code] = $qty;
        }
      }
    } else {
      $form_errors['acceptedQty'] = 'Invalid quantities';      
    }

    if(count($form_errors)>0) {
      return [
        'status' => false,
        'form_errors' => $form_errors,
      ];
    } else {
      return [
        'status' => true,
        'cleaned_params' => $cleaned_params,
      ];      
    }

  }
}