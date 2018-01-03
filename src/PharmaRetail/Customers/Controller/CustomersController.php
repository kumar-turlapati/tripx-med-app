<?php 

namespace PharmaRetail\Customers\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Atawa\Utilities;
use Atawa\Constants;
use Atawa\Template;
use Atawa\Flash;

use PharmaRetail\Customers\Model\Customers;

class CustomersController
{
	protected $views_path;

	public function __construct() {
		$this->views_path = __DIR__.'/../Views/';
	}

    public function customerCreateAction(Request $request) {
        $errors = array();
        $page_error = $page_success = $cust_code = '';
        $submitted_data = array();

        $business_category = Utilities::get_business_category();
        $object_name = (int)$business_category===1?'Patient':'Customer';

        $ages_a[0] = 'Choose';
        for($i=1;$i<=150;$i++) {
            $ages_a[$i] = $i;
        }        
        
        # initiate customer model.
        $customer_api_call = new Customers;
        $flash_obj = new Flash;

        if( count($request->request->all()) > 0) {
            $submitted_data = $request->request->all();
            $new_customer = $customer_api_call->createCustomer($request->request->all());
            $status = $new_customer['status'];
            if($status === false) {
                if(isset($new_customer['errors'])) {
                    $errors     =   $new_customer['errors'];
                } elseif(isset($new_customer['apierror'])) {
                    $page_error =   $new_customer['apierror'];
                }
            } else {
                $page_success   = $object_name.' information added successfully with code ['.$new_customer['regCode'].']';
            }
        }

        // prepare form variables.
        $template_vars = array(
            'page_error' => $page_error,
            'page_success' => $page_success,
            'submitted_data' => $submitted_data,
            'reg_types' => array(0=>'Choose')+Constants::$PATIENT_TYPES,
            'errors' => $errors,
            'genders' => array(''=>'Choose') + Constants::$GENDERS,
            'ages' => $ages_a,
            'age_categories' => Constants::$AGE_CATEGORIES,            
            'flash_obj' => $flash_obj,
            'business_category' => $business_category,
        );
        
        // build variables
        $controller_vars = array(
            'page_title' => (int)$business_category===1?'Patients':'Customers',
            'icon_name' => 'fa fa-smile-o',
        );

        // render template
        $template = new Template($this->views_path);
        return array($template->render_view('customer-create', $template_vars), $controller_vars);
    }

    public function customerUpdateAction(Request $request) {
        $errors = $submitted_data = array();
        $page_error = $page_success = $cust_code = '';

        $business_category = Utilities::get_business_category();
        $object_name = (int)$business_category===1?'Patient':'Customer';

        $update_url = (int)$business_category===1?'/patients/update':'/customers/update';
        $list_url = (int)$business_category===1?'/patients/list':'/customers/list';

        $ages_a[0] = 'Choose';
        for($i=1;$i<=150;$i++) {
            $ages_a[$i] = $i;
        }
        
        # initiate customer model.
        $customer_api_call = new Customers;
        $flash = new Flash;

        if( count($request->request->all()) > 0) {
            $submitted_data = $request->request->all();
            $reg_code = $request->get('regCode');
            $new_customer = $customer_api_call->updateCustomer($request->request->all(),$reg_code);
            $status = $new_customer['status'];
            if($status === false) {
                if(isset($new_customer['errors'])) {
                    $errors     =   $new_customer['errors'];
                } elseif(isset($new_customer['apierror'])) {
                    $page_error =   $new_customer['apierror'];
                }
                $patient_details = $submitted_data;
                $flash->set_flash_message($page_error,1);           
            } else {
                $page_success = $object_name.' information updated successfully';
                $flash->set_flash_message($page_success);     
                Utilities::redirect($list_url);        
            }
        } elseif($request->get('regCode') && $request->get('regCode')!=='') {
            $reg_code = Utilities::clean_string($request->get('regCode'));
            $api_response = $customer_api_call->get_customer_details($reg_code);
            if($api_response['status']===true) {
                $patient_details = $api_response['patientDetails'];
            } else {
                $page_error =   $api_response['apierror'];
                $flash->set_flash_message($page_error,1);
                Utilities::redirect($list_url);
            }
        } else {
            $flash->set_flash_message("Invalid $object_name Code",1);
            Utilities::redirect($list_url);
        }

        // prepare form variables.
        $template_vars = array(
            'page_error' => $page_error,
            'page_success' => $page_success,
            'submitted_data' => $patient_details,
            'reg_types' => array(0=>'Choose')+Constants::$PATIENT_TYPES,
            'errors' => $errors,
            'genders' => array(''=>'Choose') + Constants::$GENDERS,
            'ages' => $ages_a,
            'age_categories' => Constants::$AGE_CATEGORIES,            
            'flash_obj' => $flash,
            'business_category' => $business_category,
        );

        // build variables
        $controller_vars = array(
          'page_title' => (int)$business_category===1?'Patients':'Customers',
          'icon_name' => 'fa fa-smile-o',
        );

        // render template
        $template = new Template($this->views_path);
        return array($template->render_view('customer-update', $template_vars), $controller_vars);
    }    

    public function customerListAction(Request $request) {

        $customers_list = $customers = $search_params = array();

        $total_pages = $total_records = $record_count = $page_no = 0 ;
        $slno = $to_sl_no = $page_links_to_start =  $page_links_to_end = 0;
        $page_success = $page_error = '';
        $business_category = Utilities::get_business_category();

        $customers_model = new Customers;
        $flash_obj = new Flash;

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

        $customers_list = $customers_model->get_customers($page_no,$per_page,$search_params);
        $api_status = $customers_list['status'];

        # check api status
        if($api_status) {

            # check whether we got products or not.
            if(count($customers_list['customers']) >0) {
                $slno = Utilities::get_slno_start(count($customers_list['customers']),$per_page,$page_no);
                $to_sl_no = $slno+$per_page;
                $slno++;

                if($page_no<=3) {
                    $page_links_to_start = 1;
                    $page_links_to_end = 10;
                } else {
                    $page_links_to_start = $page_no-3;
                    $page_links_to_end = $page_links_to_start+10;            
                }

                if($customers_list['total_pages']<$page_links_to_end) {
                    $page_links_to_end = $customers_list['total_pages'];
                }

                if($customers_list['record_count'] < $per_page) {
                    $to_sl_no = ($slno+$customers_list['record_count'])-1;
                }

                $customers = $customers_list['customers'];
                $total_pages = $customers_list['total_pages'];
                $total_records = $customers_list['total_records'];
                $record_count = $customers_list['record_count'];
            } else {
                $page_error = $customers_list['apierror'];
            }

        } else {
            $page_error = $customers_list['apierror'];
        }               

         // prepare form variables.
        $template_vars = array(
            'page_error' => $page_error,
            'page_success' => $page_success,
            'customers' => $customers,
            'total_pages' => $total_pages ,
            'total_records' => $total_records,
            'record_count' =>  $record_count,
            'sl_no' => $slno,
            'to_sl_no' => $to_sl_no,
            'search_params' => $search_params,            
            'page_links_to_start' => $page_links_to_start,
            'page_links_to_end' => $page_links_to_end,
            'current_page' => $page_no,
            'patient_types' => Constants::$PATIENT_TYPES,
            'genders' => Constants::$GENDERS,
            'business_category' => $business_category,
        );

        // build variables
        $controller_vars = array(
          'page_title' => (int)$business_category===1?'Patients':'Customers',
          'icon_name' => 'fa fa-smile-o',
        );

        // render template
        $template = new Template($this->views_path);
        return array($template->render_view('customers-list', $template_vars), $controller_vars);       
    }


}