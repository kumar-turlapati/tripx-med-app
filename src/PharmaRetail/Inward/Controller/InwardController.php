<?php 

namespace PharmaRetail\Inward\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Atawa\Utilities;
use Atawa\Constants;
use Atawa\Template;
use Atawa\Flash;

use PharmaRetail\Purchases\Model\Purchases;
use PharmaRetail\Suppliers\Model\Supplier;
use PharmaRetail\Taxes\Model\Taxes;
use PharmaRetail\Inward\Model\Inward;

class InwardController
{
  private $template, $supplier_model;

  public function __construct() {
    $this->template = new Template(__DIR__.'/../Views/');
    $this->supplier_model = new Supplier;
    $this->taxes_model = new Taxes;
    $this->inward_model = new Inward;
    $this->flash = new Flash;
    $this->purchase_model = new Purchases;
  }

  /* Inward entry action */
  public function inwardEntryAction(Request $request) {

    $credit_days_a = $suppliers_a = $payment_methods = [];
    $taxes_a = $taxes = $taxes_raw = [];
    $form_errors = $form_data = [];
    $api_error = '';
    
    $total_item_rows = 25;

    for($i=1;$i<=365;$i++) {
      $credit_days_a[$i] = $i;
    }

    $suppliers = $this->supplier_model->get_suppliers(0,0,[]);
    if($suppliers['status']) {
      $suppliers_a += $suppliers['suppliers'];
    }

    $taxes_a = $this->taxes_model->list_taxes();
    if($taxes_a['status'] && count($taxes_a['taxes'])>0 ) {
      $taxes_raw = $taxes_a['taxes'];
      foreach($taxes_a['taxes'] as $tax_details) {
        $taxes[$tax_details['taxCode']] = $tax_details['taxPercent'];
      }
    }

    $client_details = Utilities::get_client_details();
    $client_business_state = $client_details['locState'];

    # check if form is submitted.
    if(count($request->request->all()) > 0) {
      $submitted_data = $request->request->all();
      $validation_status = $this->_validate_form_data($submitted_data,false);
      if($validation_status['status']===true) {
        $cleaned_params = $validation_status['cleaned_params'];
        # hit api
        $api_response = $this->inward_model->createInward($cleaned_params);
        if($api_response['status']===true) {
          $message = 'Inward entry created successfully with entry code ` '.$api_response['inwardCode'].' `';
          $this->flash->set_flash_message($message);
          Utilities::redirect('inward-entry');
        } else {
          $api_error = $api_response['apierror'];
          $form_data = $submitted_data;
        }
      } else {
        $form_errors = $validation_status['form_errors'];
        $form_data = $submitted_data;
      }
    }

    # theme variables.
    $controller_vars = array(
      'page_title' => 'Inward Material Entry',
      'icon_name' => 'fa fa-laptop',
    );
    $template_vars = array(
      'utilities' => new Utilities,
      'credit_days_a' => array(0=>'Choose')+$credit_days_a,
      'suppliers' => array(''=>'Choose')+$suppliers_a,
      'payment_methods' => Constants::$PAYMENT_METHODS_PURCHASE,
      'taxes' => $taxes,
      'taxes_raw' => $taxes_raw,
      'form_errors' => $form_errors,
      'form_data' => $form_data,
      'total_item_rows' => $total_item_rows,
      'api_error' => $api_error,
      'states_a' => array(0=>'Choose') + Constants::$LOCATION_STATES,
      'supply_type_a' => array('' => 'Choose', 'inter' => 'Interstate', 'intra' => 'Intrastate'),
      'client_business_state' => $client_business_state,
    );

    return array($this->template->render_view('inward-entry',$template_vars),$controller_vars);
  }
  
  /* Inward entry update action */
  public function inwardEntryUpdateAction(Request $request) {

    # initiate variables.
    $credit_days_a = $suppliers_a = $payment_methods = [];
    $taxes_a = $taxes = $taxes_raw = [];
    $form_errors = $form_data = [];
    $api_error = '';

    $total_item_rows = 25;

    for($i=1;$i<=365;$i++) {
      $credit_days_a[$i] = $i;
    }    

    $client_details = Utilities::get_client_details();
    $client_business_state = $client_details['locState'];    

    # validate purchase code.
    if( is_null($request->get('purchaseCode')) ) {
      $this->flash->set_flash_message('Invalid purchase code.');
      Utilities::redirect('/inward-entry');
    } else {
      $purchase_code = Utilities::clean_string($request->get('purchaseCode'));
      $purchase_response = $this->purchase_model->get_purchase_details($purchase_code);
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
        $discounts = array_column($purchase_details['itemDetails'],'discount');
        foreach($ex_months as $key=>$value) {
          if($value<10) {
            $value = '0'.$value;
          }
          $exp_dates[] = $value.'/'.$ex_years[$key];
        }

        # unset item details from api data.
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
        $form_data['itemDiscount'] = $discounts;
        if($form_data['grnFlag'] === 'yes') {
          $page_error = 'GRN is already generated for PO No. `'.$purchase_details['poNo']."`. You can't edit now.";
          $this->flash->set_flash_message($page_error, 1);
          Utilities::redirect('/purchase/list');
        } else {
          $is_grn_generated = false;
        }
      } else {
        $this->flash->set_flash_message($purchase_response['apierror'], 1);
        Utilities::redirect('/inward-entry');
      }
    }

    $suppliers = $this->supplier_model->get_suppliers(0,0,[]);
    if($suppliers['status']) {
      $suppliers_a += $suppliers['suppliers'];
    }

    $taxes_a = $this->taxes_model->list_taxes();
    if($taxes_a['status'] && count($taxes_a['taxes'])>0 ) {
      $taxes_raw = $taxes_a['taxes'];
      foreach($taxes_a['taxes'] as $tax_details) {
        $taxes[$tax_details['taxCode']] = $tax_details['taxPercent'];
      }
    }

    $client_details = Utilities::get_client_details();
    $client_business_state = $client_details['locState'];    

    # check if form is submitted.
    if(count($request->request->all()) > 0) {
      $submitted_data = $request->request->all();
      $validation_status = $this->_validate_form_data($submitted_data, $is_grn_generated);
      if($validation_status['status']===true) {
        $cleaned_params = $validation_status['cleaned_params'];
        # hit api
        $api_response = $this->inward_model->updateInward($cleaned_params, $purchase_code);
        if($api_response['status']===true) {
          $message = 'Inward entry updated successfully with code `'.$purchase_code.'`';
          $this->flash->set_flash_message($message);
          Utilities::redirect('/inward-entry');
        } else {
          $page_error = $api_response['apierror'];
          $form_data = $submitted_data;
        }
      } else {
        $form_errors = $validation_status['form_errors'];
        $form_data = $submitted_data;
      }
    }

    # theme variables.
    $controller_vars = array(
      'page_title' => 'Inward Material Entry - Update',
      'icon_name' => 'fa fa-laptop',
    );
    $template_vars = array(
      'utilities' => new Utilities,
      'credit_days_a' => array(0=>'Choose')+$credit_days_a,
      'suppliers' => array(''=>'Choose')+$suppliers_a,
      'payment_methods' => Constants::$PAYMENT_METHODS_PURCHASE,
      'taxes' => $taxes,
      'taxes_raw' => $taxes_raw,
      'form_errors' => $form_errors,
      'form_data' => $form_data,
      'total_item_rows' => $total_item_rows,
      'api_error' => $api_error,
      'is_grn_generated' => $is_grn_generated,
      'states_a' => array(0=>'Choose') + Constants::$LOCATION_STATES,
      'supply_type_a' => array('' => 'Choose', 'inter' => 'Interstate', 'intra' => 'Intrastate'),
      'client_business_state' => $client_business_state,      
    );

    return array($this->template->render_view('inward-entry-update',$template_vars),$controller_vars);
  }

  /* Inward entry view action */
  public function inwardEntryViewAction(Request $request) {

    # initiate variables.
    $credit_days_a = $suppliers_a = $payment_methods = [];
    $taxes_a = $taxes = $taxes_raw = [];
    $form_errors = $form_data = [];
    $page_error = '';

    $total_item_rows = 25;

    for($i=1;$i<=365;$i++) {
      $credit_days_a[$i] = $i;
    }

    $client_details = Utilities::get_client_details();
    $client_business_state = $client_details['locState'];    

    # validate purchase code.
    if( is_null($request->get('purchaseCode')) ) {
      $this->flash->set_flash_message('Invalid purchase code.');
      Utilities::redirect('/inward-entry');
    } else {
      $purchase_code = Utilities::clean_string($request->get('purchaseCode'));
      $purchase_response = $this->purchase_model->get_purchase_details($purchase_code);
      if($purchase_response['status']===true) {
        $purchase_details = $purchase_response['purchaseDetails'];
        $total_item_rows = count($purchase_details['itemDetails']);

        # convert received item details to template item details.
        $item_names = array_column($purchase_details['itemDetails'],'itemName');
        $inward_qtys = array_column($purchase_details['itemDetails'],'itemQty');
        $free_qtys = array_column($purchase_details['itemDetails'],'freeQty');
        $batch_nos = array_column($purchase_details['itemDetails'],'batchNo');
        $ex_months = array_column($purchase_details['itemDetails'],'expdateMonth');
        $ex_years = array_column($purchase_details['itemDetails'],'expdateYear');
        $mrps = array_column($purchase_details['itemDetails'],'mrp');
        $item_rates = array_column($purchase_details['itemDetails'],'itemRate');
        $discounts = array_column($purchase_details['itemDetails'],'discount');                
        $tax_percents = array_column($purchase_details['itemDetails'],'vatPercent');
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
        $form_data['itemDiscount'] = $discounts;        
        if($form_data['grnFlag'] === 'yes') {
          $is_grn_generated = true;
        } else {
          $is_grn_generated = false;
        }
      } else {
        $this->flash->set_flash_message($purchase_response['apierror'], 1);
        Utilities::redirect('/inward-entry');
      }
    }

    $suppliers = $this->supplier_model->get_suppliers(0,0,[]);
    if($suppliers['status']) {
      $suppliers_a += $suppliers['suppliers'];
    }

    $taxes_a = $this->taxes_model->list_taxes();
    if($taxes_a['status'] && count($taxes_a['taxes'])>0 ) {
      $taxes_raw = $taxes_a['taxes'];
      foreach($taxes_a['taxes'] as $tax_details) {
        $taxes[$tax_details['taxCode']] = $tax_details['taxPercent'];
      }
    }

    # theme variables.
    $controller_vars = array(
      'page_title' => 'Inward Material Entry - View Transaction',
      'icon_name' => 'fa fa-eye',
    );
    $template_vars = array(
      'utilities' => new Utilities,
      'credit_days_a' => array(0=>'Choose')+$credit_days_a,
      'suppliers' => array(''=>'Choose')+$suppliers_a,
      'payment_methods' => Constants::$PAYMENT_METHODS_PURCHASE,
      'taxes' => $taxes,
      'taxes_raw' => $taxes_raw,
      'form_errors' => $form_errors,
      'form_data' => $form_data,
      'total_item_rows' => $total_item_rows,
      'page_error' => $page_error,
      'is_grn_generated' => $is_grn_generated,
      'states_a' => array(0=>'Choose') + Constants::$LOCATION_STATES,
      'supply_type_a' => array('' => 'Choose', 'inter' => 'Interstate', 'intra' => 'Intrastate'),
      'client_business_state' => $client_business_state,
    );

    return array($this->template->render_view('inward-entry-view',$template_vars),$controller_vars);
  }  

  /**************************************** Private functions ***********************************/
  private function _validate_form_data($form_data=[], $is_grn_generated=false) {

    $form_errors = $cleaned_params = [];
    $is_one_item_found = false;

    # validate supplier name
    if( isset($form_data['supplierID']) && $form_data['supplierID'] === '') {
      $form_errors['supplierID'] = 'Invalid supplier name.';
    } else {
      $cleaned_params['supplierID'] = Utilities::clean_string($form_data['supplierID']);
    }

    # validate PO No
    if( isset($form_data['poNo']) && $form_data['poNo'] === '') {
      $form_errors['poNo'] = 'PO number is mandatory.';
    } else {
      $cleaned_params['poNo'] = Utilities::clean_string($form_data['poNo']);
    }

    # validate payment method
    if( isset($form_data['paymentMethod']) && (int)$form_data['paymentMethod'] === 1) {
      $credit_days = (int)$form_data['creditDays'];
      if($credit_days>0) {
        $cleaned_params['creditDays'] = $credit_days;
        $cleaned_params['paymentMethod'] = 1;
      } else {
        $form_errors['creditDays'] = 'Credit days are mandatory.';
      }
    } else {
      $cleaned_params['paymentMethod'] = Utilities::clean_string($form_data['paymentMethod']);
    }

    # validate discount percent
    if(isset($form_data['billDiscount']) && is_numeric($form_data['billDiscount']) && $form_data['billDiscount'] > 0) {
      $cleaned_params['billDiscount'] = Utilities::clean_string($form_data['billDiscount']);
    } else {
      $cleaned_params['billDiscount'] = 0;      
    }

    # validate other taxes.
    if(isset($form_data['otherTaxes']) && $form_data['otherTaxes']>0) {
      $cleaned_params['otherTaxes'] = Utilities::clean_string($form_data['otherTaxes']);
    } else {
      $cleaned_params['otherTaxes'] = 0;
    }

    # validate shipping charges.
    if(isset($form_data['shippingCharges']) && $form_data['shippingCharges']>0) {
      $cleaned_params['shippingCharges'] = Utilities::clean_string($form_data['shippingCharges']);
    } else {
      $cleaned_params['shippingCharges'] = 0;
    }

    # validate adjustment
    if(isset($form_data['adjustment']) && is_numeric($form_data['adjustment']) ) {
      $cleaned_params['adjustment'] = Utilities::clean_string($form_data['adjustment']);
    } else {
      $cleaned_params['adjustment'] = 0;
    }

    if(isset($form_data['remarks']) && $form_data['remarks'] !=='' ) {
      $cleaned_params['remarks'] = Utilities::clean_string($form_data['remarks']);
    } else {
      $cleaned_params['remarks'] = '';
    }

    # validate line items only if grn is not generated.
    if($is_grn_generated===false) {

      # validate line item details
      $item_names_a = $form_data['itemName'];
      $inward_qtys_a = $form_data['inwardQty'];
      $free_qtys_a = $form_data['freeQty'];
      $batch_nos_a = $form_data['batchNo'];
      $exp_dates_a = $form_data['expDate'];
      $mrps_a = $form_data['mrp'];
      $item_rates_a = $form_data['itemRate'];
      $tax_percents_a = $form_data['taxPercent'];
      $item_discounts = $form_data['itemDiscount'];
      $item_hsnsac_codes_a = $form_data['hsnSacCode'];      

      foreach($item_names_a as $key=>$item_name) {
        if($item_name !== '') {

          $is_one_item_found = true;
          $cleaned_exp_date = '';

          $inward_qty = Utilities::clean_string($inward_qtys_a[$key]);
          $free_qty = Utilities::clean_string($free_qtys_a[$key]);
          $batch_no = Utilities::clean_string($batch_nos_a[$key]);
          $exp_date = Utilities::clean_string($exp_dates_a[$key]);
          $mrp = Utilities::clean_string($mrps_a[$key]);
          $item_rate = Utilities::clean_string($item_rates_a[$key]);
          $tax_percent = Utilities::clean_string($tax_percents_a[$key]);
          $discount_amount = Utilities::clean_string($item_discounts[$key]);
          $hsn_sac_code = Utilities::clean_string($item_hsnsac_codes_a[$key]);

          $cleaned_params['itemDetails']['itemName'][] = $item_name;

          $exp_date_a = explode('/', $exp_date);
          if(is_array($exp_date_a) && count($exp_date_a)===2) {
            if(isset($exp_date_a[0]) && (int)$exp_date_a[0]>=1 && (int)$exp_date_a[0]<=12) {
              $cleaned_exp_date = $exp_date_a[0];
            } else {
              $form_errors['itemDetails'][$key]['expDate']['m'] = 'Invalid expiry month';
            }
            if(isset($exp_date_a[1]) && (int)$exp_date_a[1]>=10 && (int)$exp_date_a[0]<=99) {
              $cleaned_exp_date .= '/'.$exp_date_a[1];
            } else {
              $form_errors['itemDetails'][$key]['expDate']['y'] = 'Invalid expiry year';
            }
            $cleaned_params['itemDetails']['expDate'][] = $cleaned_exp_date;
          } else {
            $form_errors['itemDetails'][$key]['expDate'] = 'Invalid expiry date';
          }

          if( !is_numeric($inward_qty) ) {
            $form_errors['itemDetails'][$key]['inwardQty'] = 'Invalid item qty';
          } else {
            $cleaned_params['itemDetails']['inwardQty'][] = $inward_qty;
          }

          # validate free qty only if value is available.
          if($free_qty !== '') {
            if( !is_numeric($free_qty) ) {
              $form_errors['itemDetails'][$key]['freeQty'] = 'Invalid item qty';
            } elseif($free_qty>$inward_qty) {
              $form_errors['itemDetails'][$key]['freeQty'] = 'Invalid item qty';
            } else {
              $cleaned_params['itemDetails']['freeQty'][] = $free_qty;
            }
          } else {
            $cleaned_params['itemDetails']['freeQty'][] = 0;
          }

          # for non pharmacy businesses batchno already contains _.
          # we need to remove that to pass validation.
          # 0537pm @ 02082017.
          // if($business_category>1) {
          //   $batch_no_a = explode('_', $batch_no);
          //   if(is_array($batch_no_a) && count($batch_no_a)>0) {
          //     $batch_no = $batch_no_a[1];
          //   } else {
          //     $batch_no = 'Invalid99';
          //   }
          // }
          if( !ctype_alnum($batch_no) ) {
            $form_errors['itemDetails'][$key]['batchNo'] = 'Invalid batch no.';
          } else {
            $cleaned_params['itemDetails']['batchNo'][] = $batch_no;
          }

          if( !is_numeric($mrp) || $mrp < $item_rate ) {
            $form_errors['itemDetails'][$key]['mrp'] = 'Invalid MRP';
          } else {
            $cleaned_params['itemDetails']['mrp'][] = $mrp;
          }
          if( !is_numeric($item_rate) ) {
            $form_errors['itemDetails'][$key]['itemRate'] = 'Invalid item rate';
          } else {
            $cleaned_params['itemDetails']['itemRate'][] = $item_rate;
          }
          if( !is_numeric($tax_percent) ) {
            $form_errors['itemDetails'][$key]['taxPercent'] = 'Invalid tax percent';
          } else {
            $cleaned_params['itemDetails']['taxPercent'][] = $tax_percent;
          }
          if( !is_numeric($discount_amount) ) {
            $form_errors['itemDetails'][$key]['itemDiscount'] = 'Invalid discount amount';
          } else {
            $cleaned_params['itemDetails']['itemDiscount'][] = $discount_amount;
          }
          # validate hsn / sac code.
          if( $hsn_sac_code !=='' && !is_numeric(str_replace(' ', '', $hsn_sac_code)) ) {
            $form_errors['itemDetails'][$key]['hsnSacCode'] = 'Invalid HSN or SAC code';
          } else {
            $cleaned_params['itemDetails']['hsnSacCode'][] = $hsn_sac_code;
          }
        }
      }
      if($is_one_item_found===false) {
        $form_errors['itemDetailsError'] = 'At least one item is required in PO.';
      }
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