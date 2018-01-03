<?php

namespace PharmaRetail\AdminOptions\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Atawa\Utilities;
use Atawa\Constants;
use Atawa\Template;
use Atawa\Flash;

use PharmaRetail\Sales\Model\Sales;
use PharmaRetail\Purchases\Model\Purchases;
use PharmaRetail\Inventory\Model\Inventory;
use PharmaRetail\Suppliers\Model\Supplier;
use PharmaRetail\Taxes\Model\Taxes;
use PharmaRetail\Inward\Model\Inward;
use User\Model\User;

class AdminOptionsController
{
	protected $views_path, $template, $sales_model, $purch_model, $flash;

	public function __construct() {
		$this->views_path = __DIR__.'/../Views/';
		$this->template = new Template($this->views_path);
		$this->sales_model = new Sales;
		$this->purch_model = new Purchases;
    $this->inv_model = new Inventory;
    $this->user_model = new User;
    $this->supplier_model = new Supplier;
    $this->taxes_model = new Taxes;
    $this->inward_model = new Inward;    
		$this->flash = new Flash;
	}

	public function askForBillNo(Request $request) { 
		
		$page_error = $page_title = $bill_no = '';

    if(count($request->request->all()) > 0) {
    	$bill_no = Utilities::clean_string($request->get('editBillNo'));
    	$bill_type = Utilities::clean_string($request->get('billType'));
    	if($bill_type === 'sale') {
    		$bill_details = $this->sales_model->get_sales_details($bill_no,true);
    		if($bill_details['status']) {
    			Utilities::redirect('/admin-options/edit-sales-bill?billNo='.$bill_no);
    		} else {
    			$page_error = 'Invalid Bill No.';
    		}    		
    	} elseif($bill_type === 'purc') {
    		$bill_details = $this->purch_model->get_purchase_details($bill_no, true);
    		if($bill_details['status']) {
    			Utilities::redirect('/admin-options/edit-po?poNo='.$bill_no);
    		} else {
          $this->flash->set_flash_message('Invalid PO No. (or) PO does not exist.',1);
          Utilities::redirect('/admin-options/enter-bill-no?billType=purc');
    		}
    	}
    }

    # check for filter variables.
    if(!is_null($request->get('billType')) && 
    	  $request->get('billType')!=='' &&
    	  ($request->get('billType') === 'sale' || $request->get('billType') === 'purc')
    	) {
    	$bill_type = Utilities::clean_string($request->get('billType'));
    } else {
    	$bill_type = 'sale';
    }

    switch ($bill_type) {
    	case 'sale':
    		$page_title = 'Edit Sales Bill';
    		$label_name = 'Enter bill no. to edit';
    		$icon_name = 'fa fa-inr';
    		break;
    	case 'purc':
    		$page_title = 'Edit Purchase Bill';
    		$label_name = 'Enter PO no. to edit';
    		$icon_name = 'fa fa-compass';
    		break;
    }

     // prepare form variables.
    $template_vars = array(
    	'label_name' => $label_name,
    	'page_error' => $page_error,
    	'bill_no' => $bill_no,
    	'bill_type' => $bill_type,
    );

    // build variables
    $controller_vars = array(
      'page_title' => $page_title,
      'icon_name' => $icon_name,
    );

    // render template
    return array($this->template->render_view('ask-for-billno',$template_vars),$controller_vars);
	}

	public function editSalesBillAction(Request $request) {

    $errors = $sales_details = $submitted_data = array();
    $page_error = $page_success = '';

    if($request->get('billNo') && $request->get('billNo')!=='') {
      $bill_no = Utilities::clean_string($request->get('billNo'));
      $sales_response = $this->sales_model->get_sales_details($bill_no,true);
      if($sales_response['status']===true) {
        $sales_details = $sales_response['saleDetails'];
      } else {
        $page_error = $sales_response['apierror'];
        $flash->set_flash_message($page_error,1);
        Utilities::redirect('/admin-options/enter-bill-no');
      }
    } else {
      $this->flash->set_flash_message('Invalid Bill No. (or) Bill does not exist.',1);
      Utilities::redirect('/admin-options/enter-bill-no');
    }

    if(count($request->request->all()) > 0) {
      $submitted_data = $request->request->all();
      $sales_code = $sales_details['invoiceCode'];
      $sales_response = $this->sales_model->updateSale($submitted_data,$sales_code);
     	$status = $sales_response['status'];
     	if($status===false) {
        if(isset($sales_response['errors'])) {
          if(isset($sales_response['errors']['itemDetails'])) {
            $page_error = $sales_response['errors']['itemDetails'];
            unset($sales_response['errors']['itemDetails']);
          }
          $errors = $sales_response['errors'];
        } elseif(isset($sales_response['apierror'])) {
          $page_error = $sales_response['apierror'];
        }
     	} else {
        $this->flash->set_flash_message('Sales transaction with Bill No. <b>'.$sales_details['billNo']. '</b> updated successfully');
        $redirect_url = '/admin-options/edit-sales-bill?billNo='.$bill_no;
        Utilities::redirect($redirect_url);
     	}

    } elseif(count($sales_details)>0) {
      $submitted_data = $sales_details;
    }

    $qtys_a = array(0=>'Sel');
		$doctors_a = array(-1=>'Choose', 0=>'D.M.O')+$this->sales_model->get_doctors();
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
	    'btn_label' => 'Edit sale transaction',
	    'submitted_data' => $submitted_data,
	    'flash' => $this->flash,
    );

    // build variables
    $controller_vars = array(
      'page_title' => 'Edit Sales Bill',
      'icon_name' => 'fa fa-inr',
    );

    // render template
    return array($this->template->render_view('edit-sale-bill',$template_vars),$controller_vars);
	}

	public function editPoAction(Request $request) {

    # initiate variables.
    $credit_days_a = $suppliers_a = $payment_methods = [];
    $taxes_a = $taxes = $taxes_raw = [];
    $form_errors = $form_data = [];
    $page_error = '';
    $grn_no = '';

    $total_item_rows = 25;
    $business_category = Utilities::get_business_category();    

    for($i=1;$i<=365;$i++) {
      $credit_days_a[$i] = $i;
    }

    # check if form is submitted.
    if(count($request->request->all()) > 0) {
      $submitted_data = $request->request->all();
      $validation_status = $this->_validate_grn_form_data($submitted_data);
      if($validation_status['status']===true) {
        $cleaned_params = $validation_status['cleaned_params'];
        # hit api
        $api_response = $this->inward_model->updateInwardAfterGrn($cleaned_params, $purchase_code);
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
    } elseif( !is_null($request->get('poNo')) ) {
      $po_no = Utilities::clean_string($request->get('poNo'));
      $purchase_response = $this->purch_model->get_purchase_details($po_no, true);
      if($purchase_response['status']) {
        $purchase_details = $purchase_response['purchaseDetails'];
        $grn_no = $purchase_details['grnNo'];

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
      } else {
        $this->flash->set_flash_message('Invalid PO No. (or) PO No. does not exist.',1);
        Utilities::redirect('/admin-options/enter-bill-no?billType=purc');
      }
    } else {
      $this->flash->set_flash_message('Type a PO No. to edit');
      Utilities::redirect('/admin-options/enter-bill-no?billType=purc');
    }

    // build variables
    $controller_vars = array(
      'page_title' => 'Edit GRN after PO',
      'icon_name' => 'fa fa-compass',
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
      'business_category' => $business_category,
      'grn_no' => $grn_no,
    );

    // render template
    return array($this->template->render_view('edit-purc-bill',$template_vars),$controller_vars);		
	}

  /** update business information **/
	public function editBusinessInfoAction(Request $request) {

    $form_data = $states = $form_errors = array();

    if(count($request->request->all()) > 0) {
      $form_data = $request->request->all();
      $validation = $this->_validate_businessinfo($form_data);
      $status = $validation['status'];
      if($status) {
        $form_data = $validation['cleaned_params'];
        $result = $this->user_model->update_client_details($form_data);
        if($result['status']===true) {
          $message = 'Information updated successfully. Changes will be updated after you logout from current session.';
          $this->flash->set_flash_message($message);
          Utilities::redirect('/admin-options/edit-business-info');
        } else {
          $message = 'An error occurred while updating your information.';
          $this->flash->set_flash_message($message,1);
          Utilities::redirect('/admin-options/edit-business-info');
        }
      } else {
        $form_errors = $validation['errors'];
        $client_details = $form_data;
      }

    } else {
      // get client details.
      $client_details = $this->user_model->get_client_details()['clientDetails'];
    }

    $states_a = Constants::$LOCATION_STATES;
    asort($states_a);

    // template variables
    $template_vars = array(
      'states' => array(0=>'Choose')+$states_a,
      'page_error' => '',
      'page_success' => '',
      'form_data' => $client_details,
      'form_errors' => $form_errors,
    );

    // build variables
    $controller_vars = array(
      'page_title' => 'Update Business Information',
      'icon_name' => 'fa fa-building',
    );

    // render template
    return array($this->template->render_view('edit-business-info',$template_vars),$controller_vars);
	}

  /** Delete Sale Bill **/
  public function deleteSaleBill(Request $request) {

    $page_error = $page_title = $bill_no = '';

    if(count($request->request->all()) > 0) {
      $bill_no = Utilities::clean_string($request->get('delSaleBill'));
      $bill_details = $this->sales_model->get_sales_details($bill_no,true);
      if($bill_details['status']) {
        # delete sale bill api.
        $api_response = $this->sales_model->removeSalesTransaction($bill_details['saleDetails']['invoiceCode']);
        $status = $api_response['status'];
        if($status===false) {
          if(isset($api_response['errors'])) {
            if(isset($api_response['errors']['itemDetails'])) {
              $page_error = $api_response['errors']['itemDetails'];
              unset($api_response['errors']['itemDetails']);
            }
            $errors = $api_response['errors'];
          } elseif(isset($api_response['apierror'])) {
            $page_error = $api_response['apierror'];
          }
        } else {
          $this->flash->set_flash_message('Sales transaction with Bill No. <b>'.$bill_no. '</b> deleted successfully');
          Utilities::redirect('/admin-options/delete-sale-bill');
        }
      } else {
        $page_error = 'Invalid Bill No. (or) Bill does not exist.';
      }
    }

     // prepare form variables.
    $template_vars = array(
      'page_error' => $page_error,
      'bill_no' => $bill_no,
    );

    // build variables
    $controller_vars = array(
      'page_title' => 'Delete Sale Bill',
      'icon_name' => 'fa fa-times',
    );

    // render template
    return array($this->template->render_view('delete-sale-bill',$template_vars),$controller_vars);
  }  

  public function updateBatchQtys(Request $request) {

    $page_error = $item_name = $item_code = '';

    if(count($request->request->all()) > 0) {
      $submitted_data = $request->request->all();
      $item_name = Utilities::clean_string($submitted_data['itemName']);
      if($item_name !== '') {
        $search_params = array('itemName' => $item_name);
        $item_details = $this->inv_model->get_inventory_item_details($search_params);
        if($item_details['status']===true) {
          $item_code = $item_details['item_details']['itemDetails']['itemCode'];
        } else {
          $page_error = 'Invalid item name.';          
        }
      }
      $search_params = array();
      $search_params['itemCode'] = $item_code;
      $batch_qty_response = $this->inv_model->update_batch_qtys($search_params);
      if($batch_qty_response['status']===true) {
        $this->flash->set_flash_message('Available quantities updated successfully');
      } else {
        $this->flash->set_flash_message('An error occurred while processing this request', 1);
      }
      Utilities::redirect('/admin-options/update-batch-qtys');
    }

    // prepare form variables.
    $template_vars = array(
      'flash' => $this->flash,
      'page_error' => $page_error,
      'item_name' => $item_name,
    );

    // build variables
    $controller_vars = array(
      'page_title' => 'Update Available Quantities',
      'icon_name' => 'fa fa-database',
    );

    // render template
    return array($this->template->render_view('update-batch-qtys',$template_vars),$controller_vars);
  }

  /** validation of business info. **/
  private function _validate_businessinfo($form_data=array()) {
    
    $cleaned_params = $errors = array();
    $image_data = '';

    $states_a = array_keys(Constants::$LOCATION_STATES);

    $business_name = Utilities::clean_string($form_data['businessName']);
    $gst_no = Utilities::clean_string($form_data['gstNo']);
    $dl_no = Utilities::clean_string($form_data['dlNo']);
    $address1 = Utilities::clean_string($form_data['address1']);
    $address2 = Utilities::clean_string($form_data['address2']);
    $state_id = Utilities::clean_string($form_data['locState']);
    $pincode = Utilities::clean_string($form_data['pincode']);
    $phones = Utilities::clean_string($form_data['phones']);

    # check logo information.
    if( isset($_FILES['logoName']) && $_FILES['logoName']['name'] !== '') {
      $file_details = $_FILES['logoName'];
      if( exif_imagetype($file_details['tmp_name']) !== 2 ) {
        $errors['logoName'] = 'Invalid Business Logo. Only .jpg or .jpeg file formats are allowed.';
      } else {
        $image_info = file_get_contents($file_details['tmp_name']);
        $image_data = 'data:' . $file_details['type'] . ';base64,' . base64_encode($image_info);
      }
    }

    if( !ctype_alnum(str_replace(' ', '', $business_name)) ) {
      $errors['businessName'] = 'Invalid business name. Only alphabets and digits are allowed.';
    } else {
      $cleaned_params['businessName'] = $business_name;
    }
    if($gst_no !== '' && strlen(str_replace('_','',$gst_no)) !== 15 ) {
      $errors['gstNo'] = 'Invalid GST No.';
    } else {
      $cleaned_params['gstNo'] = $gst_no;
    }
    if(in_array($state_id, $states_a) === false) {
      $errors['locState'] = 'Invalid State.';
    } else {
      $cleaned_params['locState'] = $state_id;
    }
    if($pincode !== '' && !is_numeric($pincode)) {
      $errors['pincode'] = 'Invalid Pincode.';
    } else {
      $cleaned_params['pincode'] = $pincode;
    }    

    $cleaned_params['dlNo'] = $dl_no;
    $cleaned_params['address1'] = $address1;
    $cleaned_params['address2'] = $address2;
    $cleaned_params['phones'] = $phones;
    $cleaned_params['logoData'] = $image_data;

    if(count($errors)>0) {
      return array('status'=>false, 'errors'=>$errors);
    } else {
      return array('status'=>true, 'cleaned_params'=>$cleaned_params);
    }

  }

  /** validation of GRN form data **/
  private function _validate_grn_form_data($form_data=[]) {

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
      }
    }
    if($is_one_item_found===false) {
      $form_errors['itemDetailsError'] = 'At least one item is required in PO.';
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