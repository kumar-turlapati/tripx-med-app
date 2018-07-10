<?php 

namespace PharmaRetail\Products\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Atawa\Utilities;
use Atawa\Constants;
use Atawa\Template;
use Atawa\Flash;

use PharmaRetail\Products\Model\Products;
use PharmaRetail\Taxes\Model\Taxes;

class ProductsController
{
    protected $views_path;

    public function __construct() {
        $this->views_path = __DIR__.'/../Views/';
        $this->taxes_model = new Taxes;        
    }

    public function createMedicines(Request $request) {

        $errors = $taxes = $taxes_a = [];
        $page_error = $page_success = $item_code = '';
        $upp_a = $categories_a = array(''=>'Choose');
        $update_flag=false;
        $submitted_data = $product_details = [];

        $category_name = 'Product / Service';
        $create_url = '/medicines/create';
        $update_url = '/medicines/update';
        $list_url = '/medicines/list';
        
        # initiate classes.
        $products_api_call = new Products;
        $flash = new Flash;

        for($i=1;$i<=1000;$i++) {
            $upp_a[$i] = $i;
        }

        $taxes_a = $this->taxes_model->list_taxes();
        if($taxes_a['status'] && count($taxes_a['taxes'])>0 ) {
          $taxes_raw = $taxes_a['taxes'];
          foreach($taxes_a['taxes'] as $tax_details) {
            $taxes[$tax_details['taxCode']] = $tax_details['taxPercent'];
          }
        }

        if($request->get('itemCode') && $request->get('itemCode')!='') {
            $item_code = Utilities::clean_string($request->get('itemCode'));
            $products_api_response = $products_api_call->get_product_details($item_code);
            if($products_api_response['status']===true) {
                $product_details = $products_api_response['productDetails'];
                $update_flag = true;
            } else {
                $flash->set_flash_message("Invalid Medicine code.",1);
                Utilities::redirect($list_url);
            }
            $page_title = "Update $category_name".(isset($product_details['itemName'])?' - '.$product_details['itemName']:'');
            $btn_label = "Update $category_name";
        } else {
            $btn_label = "Create $category_name";
            $page_title = 'Product / Service';
        }

        $categories_a = $categories_a+$products_api_call->get_product_categories();

        if(count($request->request->all()) > 0) {
            $submitted_data = $request->request->all();
            if(count($product_details)>0) {
              $new_product = $products_api_call->updateProduct($request->request->all(), $item_code);             
            } else {
              $new_product = $products_api_call->createProduct($request->request->all());
            }

            $status = $new_product['status'];
            if($status === false) {
                if(isset($new_product['errors'])) {
                    $errors     =   $new_product['errors'];
                } elseif(isset($new_product['apierror'])) {
                    $page_error =   $new_product['apierror'];
                }
            } elseif($update_flag===false) {
                $page_success   = 'Product information added successfully with code ['.$new_product['itemCode'].']';
                $flash->set_flash_message($page_success);
                Utilities::redirect($create_url);
            } else {
                $page_success   = 'Product information updated successfully';
                $flash->set_flash_message($page_success);
                Utilities::redirect($update_url.'/'.$item_code);
            }

        } else {
            $submitted_data = $product_details;            
        }

        // prepare form variables.
        $template_vars = array(
            'page_error' => $page_error,
            'page_success' => $page_success,
            'status' =>  Constants::$RECORD_STATUS,
            'submitted_data' => $submitted_data,
            'errors' => $errors,
            'btn_label' => $btn_label,
            'item_code' => $item_code,
            'flash_obj' => $flash,
            'mfgs' => array(),
            'categories' => $categories_a,
            'comps' => array(),
            'upp_a' => $upp_a,
            'presc_options_a' => [0 => 'No', 1 => 'Yes'],
            'item_types_a' => ['p' => 'Product', 's' => 'Service'],
            'tax_rates_a' => ['' => 'Choose'] + $taxes,
        );

        // build variables
        $controller_vars = array(
            'page_title' => $page_title,
            'icon_name' => 'fa fa-tasks',
        );

        // render template
        $template = new Template($this->views_path);
        return array($template->render_view('products-create', $template_vars), $controller_vars);
    }

    public function listMedicines(Request $request) {
        $products_list = $search_params = $products = array();
        $categories = array(''=>'Choose');
        $total_pages = $total_records = $record_count = $page_no = 0 ;
        $slno = $to_sl_no = $page_links_to_start =  $page_links_to_end = 0;
        $page_success = $page_error = '';
        $show_add_link = false;

        $flash = new Flash;        

        $product_api_call = new Products;
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
        } elseif($request->get('medname')) {
            $search_params['medname'] = $request->get('medname');
        } elseif($request->get('composition')) {
            $search_params['composition'] =  $request->get('composition');
        } elseif($request->get('category')) {
            $search_params['category'] =  $request->get('category');
        } elseif($request->get('mfg')) {
            $search_params['mfg'] =  $request->get('mfg');            
        } else {
            $search_params = array();
        }

        $products_list = $product_api_call->get_products($page_no, $per_page, $search_params);
        $api_status = $products_list['status'];
        $client_id = Utilities::get_current_client_id();
        /*
        if($client_id==='GxhJXWNSC3MNALH') {
          $show_add_link = true;
        }*/

        # check api status
        if($api_status) {

            # check whether we got products or not.
            if(count($products_list['products']) >0) {
                $categories = array(''=>'Choose')+$product_api_call->get_product_categories();
                $slno = Utilities::get_slno_start(count($products_list['products']), $per_page, $page_no);
                $to_sl_no = $slno+$per_page;
                $slno++;

                if($page_no<=3) {
                    $page_links_to_start = 1;
                    $page_links_to_end = 10;
                } else {
                    $page_links_to_start = $page_no-3;
                    $page_links_to_end = $page_links_to_start+10;            
                }

                if($products_list['total_pages']<$page_links_to_end) {
                    $page_links_to_end = $products_list['total_pages'];
                }

                if($products_list['record_count'] < $per_page) {
                    $to_sl_no = ($slno+$products_list['record_count'])-1;
                }

                $products = $products_list['products'];
                $total_pages = $products_list['total_pages'];
                $total_records = $products_list['total_records'];
                $record_count = $products_list['record_count'];
            } else {
                $page_error = $products_list['apierror'];
            }

        } else {
            $page_error = $products_list['apierror'];
        }

        // build variables
        $controller_vars = array(
          'page_title' => 'Products',
          'icon_name' => 'fa fa-tasks',
        );
        $template_vars = array(
          'products' => $products,
          'categories' => $categories,
          'total_pages' => $total_pages ,
          'total_records' => $total_records,
          'record_count' =>  $record_count,
          'sl_no' => $slno,
          'to_sl_no' => $to_sl_no,
          'page_links_to_start' => $page_links_to_start,
          'page_links_to_end' => $page_links_to_end,
          'current_page' => $page_no,
          'search_params' => $search_params,
          'page_error' => $page_error,
          'page_success' => $page_success,
          'flash_obj'  => $flash
        );

        // render template
        $template = new Template($this->views_path);
        return array($template->render_view('products-list', $template_vars), $controller_vars);        
    }

}