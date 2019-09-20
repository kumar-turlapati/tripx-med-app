<?php 

namespace PharmaRetail\Suppliers\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Atawa\Utilities;
use Atawa\Constants;
use Atawa\Template;

use PharmaRetail\Suppliers\Model\Supplier;

class SupplierController {

  protected $supp_model, $template;
  public function __construct() {
    $this->template = new Template(__DIR__.'/../Views/');
    $this->supp_model = new Supplier;
  }

  // create or update supplier
  public function supplierCreateAction(Request $request) {
    $submitted_data = $supplier_details = $errors = [];
    $page_error = $page_success = '';
    $update_flag = false;
    $states_a = Constants::$LOCATION_STATES;
    $countries_a = Constants::$LOCATION_COUNTRIES;

    asort($states_a);

    $client_details = Utilities::get_client_details();
    $client_business_state = $client_details['locState'];
    if( !is_null($request->get('supplierCode')) ) {
      $supplier_code = Utilities::clean_string($request->get('supplierCode'));
      $supplier_response = $this->supp_model->get_supplier_details($supplier_code);
      if($supplier_response['status']) {
        $supplier_details = $supplier_response['supplierDetails'];
        $update_flag = true;
      }
      $page_title = 'Update Supplier '.(isset($supplier_details['supplierName']) ? ' - '.$supplier_details['supplierName'] : '');
      $btn_label = 'Update Supplier';
    } else {
      $supplier_code = '';
      $btn_label = 'Create Supplier';
      $page_title = 'Suppliers';
    }

    if(count($request->request->all()) > 0) {
      $submitted_data = $request->request->all();
      if(count($supplier_details)>0) {
        $new_supplier = $this->supp_model->updateSupplier($request->request->all(), $supplier_code);             
      } else {
        $new_supplier = $this->supp_model->createSupplier($request->request->all());
      }

      $status = $new_supplier['status'];
      if($status === false) {
        if(isset($new_supplier['errors'])) {
          $errors     =   $new_supplier['errors'];
        } elseif(isset($new_supplier['apierror'])) {
          $page_error =   $new_supplier['apierror'];
        }
      } elseif($update_flag===false) {
        $page_success   = 'Supplier information added successfully with code ['.$new_supplier['supplierCode'].']';
        $submitted_data = array();
      } else {
        $page_success   = 'Supplier information updated successfully';
      }
    } elseif(count($supplier_details)>0) {
      $submitted_data = $supplier_details;
    }

    // prepare form variables.
    $template_vars = array(
      'page_error' => $page_error,
      'page_success' => $page_success,
      'supplier_categories' => Constants::$SUPPLIER_TYPES,
      'status' =>  Constants::$RECORD_STATUS,
      'submitted_data' => $submitted_data,
      'errors' => $errors,
      'btn_label' => $btn_label,
      'supplier_code' => $supplier_code,
      'states' => [0 => 'Choose'] + $states_a ,
      'countries' => [ 0 => 'Choose'] + $countries_a,
      'client_business_state' => $client_business_state,
    );

    // build variables
    $controller_vars = array(
      'page_title' => $page_title,
      'icon_name' => 'fa fa-users'
    );

    // render template
    return array($this->template->render_view('supplier-create', $template_vars), $controller_vars);
  }

  // suppliers list
  public function suppliersListAction(Request $request) {

    $suppliers_list = $suppliers= $search_params = [];
    $categories = array(''=>'Choose');
    $total_pages = $total_records = $record_count = $page_no = 0 ;
    $slno = $to_sl_no = $page_links_to_start =  $page_links_to_end = 0;
    $page_success = $page_error = '';
    $search_params = array('pagination'=>'yes');

    $page_no = is_null($request->get('pageNo')) ? 1 : $request->get('pageNo');
    $per_page = is_null($request->get('perPage')) ? 100 : $request->get('perPage');

    if(count($request->request->all()) > 0) {
      if($request->get('suppName')) {
        $search_params['suppName'] = $request->get('suppName');
      }
      if($request->get('category')) {
        $search_params['category'] =  $request->get('category');
      }
    }

      // dump($search_params);

    $suppliers_list = $this->supp_model->get_suppliers($page_no, $per_page, $search_params);
    $api_status = $suppliers_list['status'];

    # check api status
    if($api_status) {
      # check whether we got products or not.
      if(count($suppliers_list['suppliers']) >0) {
        $slno = Utilities::get_slno_start(count($suppliers_list['suppliers']), $per_page, $page_no);
        $to_sl_no = $slno+$per_page;
        $slno++;

        if($page_no<=3) {
          $page_links_to_start = 1;
          $page_links_to_end = 10;
        } else {
          $page_links_to_start = $page_no-3;
          $page_links_to_end = $page_links_to_start+10;
        }

        if($suppliers_list['total_pages']<$page_links_to_end) {
          $page_links_to_end = $suppliers_list['total_pages'];
        }
        if($suppliers_list['record_count'] < $per_page) {
          $to_sl_no = ($slno+$suppliers_list['record_count'])-1;
        }
        $suppliers = $suppliers_list['suppliers'];
        $total_pages = $suppliers_list['total_pages'];
        $total_records = $suppliers_list['total_records'];
        $record_count = $suppliers_list['record_count'];
      } else {
        $page_error = $suppliers_list['apierror'];
      }
    } else {
      $page_error = $suppliers_list['apierror'];
    }               

     // prepare form variables.
    $template_vars = array(
      'page_error' => $page_error,
      'page_success' => $page_success,
      'categories' => Constants::$SUPPLIER_TYPES,
      'suppliers' => $suppliers,
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
      'page_title' => 'Suppliers',
      'icon_name' => 'fa fa-users'
    );

    // render template
    return array($this->template->render_view('supplier-list', $template_vars), $controller_vars);       
  }

  public function supplierRemoveAction(Request $request)
  {
      if($request->get('supplierCode') && $request->get('supplierCode')!='') {
          $supplier_code = Utilities::clean_string($request->get('supplierCode'));
      } else {
          Utilities::set_flash_message("Invalid Supplier Code",1);
          Utilities::redirect('/suppliers/list');
      }

      # initiate supplier model.
      $suppliers = new Supplier;            
      
      $supplier_response = $this->supp_model->get_supplier_details($supplier_code);
      if($supplier_response['status']===true) {
          $supplier_details = $supplier_response['supplierDetails'];
      } else {
          Utilities::set_flash_message("Invalid Supplier Code",1);
          Utilities::redirect('/suppliers/list');                
      }

      $remove_response = $this->supp_model->removeSupplier($supplier_code);
      if($remove_response['status']===true) {
          $flash_message = "Supplier '$supplier_details[supplierName]' removed successfully";
          Utilities::set_flash_message($flash_message);            
      } else {
          $flash_message = "Unable to remove Supplier";
          Utilities::set_flash_message($flash_message,1);
      }
      
      Utilities::redirect('/suppliers/list');
  }

  public function supplierViewAction(Request $request)
  {

      $template_vars = $controller_vars = array();

      // build variables
      $controller_vars = array(
          'page_title' => 'View Supplier',
          'render' => false,
      );        

      // render template
      $template = new Template($this->views_path);
      return array($template->render_view('supplier-view', $template_vars), $controller_vars);        
  }  

}