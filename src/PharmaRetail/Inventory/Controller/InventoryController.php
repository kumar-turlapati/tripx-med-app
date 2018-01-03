<?php 

namespace PharmaRetail\Inventory\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Atawa\Utilities;
use Atawa\Constants;
use Atawa\Template;
use Atawa\Flash;

use PharmaRetail\Inventory\Model\Inventory;
use Predis\Collection\Iterator;

class InventoryController
{
    protected $views_path;

    public function __construct() {
	   $this->views_path = __DIR__.'/../Views/';
    }

    public function availableQtyList(Request $request) {

        $items_list = $search_params = $items = array();

        $total_pages = $total_records = $record_count = $page_no = 0 ;
        $slno = $to_sl_no = $page_links_to_start =  $page_links_to_end = 0;
        $page_success = $page_error = '';

        $inventory_api_call = new Inventory;

        if(count($request->request->all()) > 0) {
            $search_params = $request->request->all();
        } elseif($request->get('medName')) {
            $search_params['medName'] = $request->get('medName');
        } elseif($request->get('batchNo')) {
            $search_params['batchNo'] =  $request->get('batchNo');
        }

        if($request->get('pageNo') && is_numeric($request->get('pageNo'))) {
            $search_params['pageNo'] = Utilities::clean_string($request->get('pageNo'));
        } else {
            $search_params['pageNo'] = 1;
        }

        if($request->get('perPage') && is_numeric($request->get('perPage'))) {
            $search_params['perPage'] = Utilities::clean_string($request->get('perPage'));
        } else {
            $search_params['perPage'] = 100;
        }        

        $items_list = $inventory_api_call->get_available_qtys($search_params);

        // dump($items_list);
        // dump($search_params);

        $api_status = $items_list['status'];
        $per_page = $search_params['perPage'];
        $page_no = $search_params['pageNo'];

        # check api status
        if($api_status) {

            # check whether we got products or not.
            if( count($items_list['items']) > 0) {
                $slno = Utilities::get_slno_start(count($items_list['items']),$per_page,$page_no);
                $to_sl_no = $slno+$per_page;
                $slno++;

                if($page_no<=3) {
                    $page_links_to_start = 1;
                    $page_links_to_end = 10;
                } else {
                    $page_links_to_start = $page_no-3;
                    $page_links_to_end = $page_links_to_start+10;            
                }

                if($items_list['total_pages']<$page_links_to_end) {
                    $page_links_to_end = $items_list['total_pages'];
                }

                if($items_list['record_count'] < $per_page) {
                    $to_sl_no = ($slno+$items_list['record_count'])-1;
                }

                $items = $items_list['items'];
                $total_pages = $items_list['total_pages'];
                $total_records = $items_list['total_records'];
                $record_count = $items_list['record_count'];
            } else {
                $page_error = 'Unable to fetch data';
            }

        } else {
            $page_error = $items_list['apierror'];
        }

         // prepare form variables.
        $template_vars = array(
            'page_error' => $page_error,
            'page_success' => $page_success,
            'items' => $items,
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
            'page_title' => 'Inventory - Available Quantities',
            'icon_name' => 'fa fa-database',
        );

        // render template
        $template = new Template($this->views_path);
        return array($template->render_view('batch-qtys-list', $template_vars), $controller_vars);        
    }

    public function searchItem(Request $request) {
        
        $search_params = $item_details = array();

        $inventory_api_call = new Inventory;
        $flash = new Flash;        

        if(count($request->request->all()) > 0) {
            $search_params = $request->request->all();
            $api_response = $inventory_api_call->get_inventory_item_details($search_params);
            if($api_response['status']) {
                $item_details = $api_response['item_details'];
            } else {
                $flash->set_flash_message('No details are available.');
                Utilities::redirect('/inventory/search-medicines');                
            }
        }

        // prepare form variables.
        $template_vars = array(
            'item_details' => $item_details,
            'search_params' => $search_params
        );

        // build variables
        $controller_vars = array(
            'page_title' => 'Inventory - Medicine Search',
            'icon_name' => 'fa fa-database',
        );

        // render template
        $template = new Template($this->views_path);
        return array($template->render_view('search-item',$template_vars),$controller_vars);
    }

    public function getAllStockAdjustments(Request $request) {

        $items_list = $search_params = $items = array();

        $total_pages = $total_records = $record_count = $page_no = 0 ;
        $slno = $to_sl_no = $page_links_to_start =  $page_links_to_end = 0;
        $page_success = $page_error = '';       

        if(count($request->request->all()) > 0) {
            $search_params = $request->request->all();
        }

        if($request->get('adjDate') && $request->get('adjDate') !== '') {
            $search_params['adjDate'] = Utilities::clean_string($request->get('adjDate'));
        }

        if($request->get('pageNo') && is_numeric($request->get('pageNo'))) {
            $search_params['pageNo'] = Utilities::clean_string($request->get('pageNo'));
        } else {
            $search_params['pageNo'] = 1;
        }

        if($request->get('perPage') && is_numeric($request->get('perPage'))) {
            $search_params['perPage'] = Utilities::clean_string($request->get('perPage'));
        } else {
            $search_params['perPage'] = 100;
        }
   
        $per_page = $search_params['perPage'];
        $page_no = $search_params['pageNo'];

        $inventory_api_call = new Inventory;
        
        $api_response = $inventory_api_call->get_inventory_adj_reasons();
        if($api_response['status']===true) {
          $adj_reasons = array(''=>'Choose')+$api_response['results'];
        } else {
          $adj_reasons = array(''=>'Choose');
        }

        $items_list = $inventory_api_call->get_inventory_adj_entries($search_params);
        $api_status = $items_list['status'];

        # check api status
        if($api_status) {

            # check whether we got products or not.
            if( count($items_list['results']['adjItems']) > 0) {
                $slno = Utilities::get_slno_start(count($items_list['results']['adjItems']),$per_page,$page_no);
                $to_sl_no = $slno+$per_page;
                $slno++;

                if($page_no<=3) {
                    $page_links_to_start = 1;
                    $page_links_to_end = 10;
                } else {
                    $page_links_to_start = $page_no-3;
                    $page_links_to_end = $page_links_to_start+10;            
                }

                if($items_list['results']['total_pages']<$page_links_to_end) {
                    $page_links_to_end = $items_list['results']['total_pages'];
                }

                if($items_list['results']['total_records'] < $per_page) {
                    $to_sl_no = ($slno+$items_list['results']['total_records'])-1;
                }

                $items = $items_list['results']['adjItems'];
                $total_pages = $items_list['results']['total_pages'];
                $total_records = $items_list['results']['total_records'];
                $record_count = $items_list['results']['this_page'];
            } else {
                $page_error = 'Unable to fetch data';
            }

        } else {
            $page_error = $items_list['apierror'];
        }        

        $template_vars = array(
            'page_error' => $page_error,
            'page_success' => $page_success,
            'items' => $items,
            'total_pages' => $total_pages ,
            'total_records' => $total_records,
            'record_count' =>  $record_count,
            'sl_no' => $slno,
            'to_sl_no' => $to_sl_no,
            'search_params' => $search_params,            
            'page_links_to_start' => $page_links_to_start,
            'page_links_to_end' => $page_links_to_end,
            'current_page' => $page_no,
            'adj_reasons' => $adj_reasons,
        );

        $controller_vars = array(
            'page_title' => 'Inventory - Stock Adjustment',
            'icon_name' => 'fa fa-adjust',
        );

        // render template
        $template = new Template($this->views_path);
        return array($template->render_view('inventory-adj-list',$template_vars),$controller_vars);
    }

    public function addStockAdjustment(Request $request) {

        // Utilities::redirect('/error');        

        $page_error = $page_success = '';
        $errors = $submitted_data = array();

        $inven_api = new Inventory;
        $flash = new Flash;

        $api_response = $inven_api->get_inventory_adj_reasons();
        if($api_response['status']===true) {
            $adj_reasons = array(''=>'Choose')+$api_response['results'];
        } else {
            $adj_reasons = array(''=>'Choose');
        }

        if(count($request->request->all()) > 0) {
            $params = $request->request->all();
            $api_response = $inven_api->add_stock_adjustment($params);
            $status = $api_response['status'];
            if($status === false) {
                if(isset($api_response['errors'])) {
                    $errors     =   $api_response['errors'];
                } elseif(isset($api_response['apierror'])) {
                    $page_error =   $api_response['apierror'];
                }
                $submitted_data = $params;
            } else {
                $adj_code = $api_response['results']['adjCode'];
                $flash->set_flash_message('Adjustment entry added successfully with Code [ '.$adj_code.' ]');
                Utilities::redirect('/inventory/stock-adjustment');                
            }
        }

        $template_vars = array(
            'adj_reasons' => $adj_reasons,
            'errors' => $errors,
            'page_error' => $page_error,
            'page_success' => $page_success,
            'submitted_data' => $submitted_data,            
        );

        $controller_vars = array(
            'page_title' => 'Inventory - Stock Adjustment',
            'icon_name' => 'fa fa-adjust',
        );         

        // render template
        $template = new Template($this->views_path);
        return array($template->render_view('inventory-adj-add',$template_vars),$controller_vars);
    }

    public function updateStockAdjustment(Request $request) {

    }

    public function removeStockAdjustment(Request $request) {

    }

    public function trashExpiredItems(Request $request) {

        $items = array();
        $total_pages = $total_records = $record_count = 0 ;
        $slno = $to_sl_no = $page_links_to_start =  $page_links_to_end = 0;
        $page_error = $page_success = '';

        $inven_api = new Inventory;
        $flash = new Flash;   

        if(count($request->request->all())>0) {
            $params = $request->request->all();
            $def_month = $params['month'] = Utilities::clean_string($params['month']);
            $def_year = $params['year'] = Utilities::clean_string($params['year']);

            $api_response = $inven_api->trash_expired_items($params);
            if($api_response['status']) {
                $flash->set_flash_message($api_response['results'].' expired items are processed successfully.');
            } else {
                $flash->set_flash_message($api_response['apierror'].' ( OR ) Nothing to Trash',1);
            }
            Utilities::redirect('/inventory/trash-expired-items');         
        } elseif($request->get('month')>0 && $request->get('year')>0) {
            $def_month = Utilities::clean_string($request->get('month'));
            $def_year = Utilities::clean_string($request->get('year'));
        } else {
            $def_month = (int)date("m");
            $def_year = (int)date("Y");
        }

        if($request->get('pageNo') && is_numeric($request->get('pageNo'))) {
            $page_no = Utilities::clean_string($request->get('pageNo'));
        } else {
            $page_no = 1;
        }

        if($request->get('perPage') && is_numeric($request->get('perPage'))) {
            $per_page = Utilities::clean_string($request->get('perPage'));
        } else {
            $per_page = 200;
        }

        $params = array(
         'month' => $def_month,
         'year' => $def_year,
        );

        // echo 'def month...'.$def_month.'....'.$def_year.'<br />';
        // exit;

        // dump($params);
        // exit;

        # get expired items.
        $api_response = $inven_api->get_expiry_report($params,$page_no);
        // dump($api_response);
        // exit;

        if($api_response['status']) {
            $items = $api_response['items'];
            $slno = Utilities::get_slno_start(count($items),$per_page,$page_no);
            $to_sl_no = $slno+$per_page;
            $slno++;

            if($page_no<=3) {
              $page_links_to_start = 1;
              $page_links_to_end = 10;
            } else {
              $page_links_to_start = $page_no-3;
              $page_links_to_end = $page_links_to_start+10;            
            }

            if($api_response['total_pages']<$page_links_to_end) {
              $page_links_to_end = $api_response['total_pages'];
            }

            if($api_response['record_count'] < $per_page) {
              $to_sl_no = ($slno+$api_response['record_count'])-1;
            }

            $total_pages = $api_response['total_pages'];
            $total_records = $api_response['total_records'];
            $record_count = $api_response['record_count'];
        } else {
            $page_error = 'No expired Items are available for the month of '.
                          Utilities::get_calender_month_names($def_month).', '.$def_year;
        }

        # prepare template
        $template_vars = array(
            'months' => Utilities::get_calender_months(),
            'years' => Utilities::get_calender_years(1),
            'def_year' => $def_year ,
            'def_month' => $def_month,
            'items' => $items,
            'total_pages' => $total_pages ,
            'total_records' => $total_records,
            'record_count' =>  $record_count,
            'sl_no' => $slno,
            'to_sl_no' => $to_sl_no,
            'page_links_to_start' => $page_links_to_start,
            'page_links_to_end' => $page_links_to_end,
            'current_page' => $page_no,
            'params' => $params,
            'page_error' => $page_error,
            'page_success' => $page_success,
            'month_name' => Utilities::get_calender_month_names($def_month),
        );

        $controller_vars = array(
            'page_title' => 'Inventory - Trash Expired Items',
            'icon_name' => 'fa fa-times',
        );

        # render template
        $template = new Template($this->views_path);
        return array($template->render_view('trash-expired-items',$template_vars),$controller_vars);        
    }

    public function itemThresholdAdd(Request $request) {

        $page_error = $page_success = '';
        $errors = $submitted_data = array();

        $inven_api = new Inventory;
        $flash = new Flash;

        if(count($request->request->all())>0) {
            $params = $request->request->all();
            $submitted_data['itemName'] = Utilities::clean_string($params['itemName']);
            $submitted_data['thrQty'] =  Utilities::clean_string($params['thrQty']);
            $submitted_data['supplierName'] = Utilities::clean_string($params['supplierName']);

            # hit api
            $api_response = $inven_api->add_threshold_qty($submitted_data);
            $status = $api_response['status'];
            if($status === false) {
              if(isset($api_response['errors'])) {
                $errors = $api_response['errors'];
              } elseif(isset($api_response['apierror'])) {
                $page_error = $api_response['apierror'];
              }
            } else {
                $flash->set_flash_message('Threshold qty. added successfully with code [ '.$api_response['thrCode'].' ]');
                Utilities::redirect('/inventory/item-threshold-add');              
            }
        }

        # prepare template
        $template_vars = array(
          'page_error' => $page_error,
          'page_success' => $page_success,
          'submitted_data' => $submitted_data,
          'page_error' => $page_error,
          'page_success' => $page_success,
          'errors' => $errors,
        );

        $controller_vars = array(
          'page_title' => 'Inventory - Threshold',
          'icon_name' => 'fa fa-bullhorn',
        );

        # render template
        $template = new Template($this->views_path);
        return array($template->render_view('add-threshold-qty',$template_vars),$controller_vars);
    }

    public function itemThresholdUpdate(Request $request) {

        $page_error = $page_success = $thr_code = '';
        $errors = $submitted_data = array();

        $inven_api = new Inventory;
        $flash = new Flash;

        if(count($request->request->all())>0) {
            $params = $request->request->all();
            $submitted_data['itemName'] = Utilities::clean_string($params['itemName']);
            $submitted_data['thrQty'] =  Utilities::clean_string($params['thrQty']);
            $submitted_data['supplierName'] = Utilities::clean_string($params['supplierName']);

            # hit api
            $api_response = $inven_api->update_threshold_qty($submitted_data,$params['thrCode']);
            $status = $api_response['status'];
            if($status === false) {
              if(isset($api_response['errors'])) {
                $errors = $api_response['errors'];
              } elseif(isset($api_response['apierror'])) {
                $page_error = $api_response['apierror'];
              }
            } else {
                $flash->set_flash_message('Threshold quantity updated successfully for item [ '.$params['itemName'].' ]');
                Utilities::redirect('/inventory/item-threshold-list');              
            }
        } else {
            $thr_code = $request->get('thrCode');
            $thr_details = $inven_api->get_threshold_itemqty_details($thr_code);
            if($thr_details['status']===true && count($thr_details['thrDetails'])>0) {
                $submitted_data = $thr_details['thrDetails'];
                $thr_code = $submitted_data['thrCode'];
            }
        }

        # prepare template
        $template_vars = array(
          'page_error' => $page_error,
          'page_success' => $page_success,
          'submitted_data' => $submitted_data,
          'page_error' => $page_error,
          'page_success' => $page_success,
          'errors' => $errors,
          'thr_code' => $thr_code,
        );

        $controller_vars = array(
          'page_title' => 'Inventory - Threshold',
          'icon_name' => 'fa fa-bullhorn',
        );

        # render template
        $template = new Template($this->views_path);
        return array($template->render_view('update-threshold-qty',$template_vars),$controller_vars);
    }    

    public function itemThresholdList(Request $request) {

      $items = array();
      $total_pages = $total_records = $record_count = 0 ;
      $slno = $to_sl_no = $page_links_to_start =  $page_links_to_end = 0;
      $page_error = $page_success = '';

      $inven_api = new Inventory;
      $flash = new Flash;

      if($request->get('pageNo') && is_numeric($request->get('pageNo'))) {
        $page_no = Utilities::clean_string($request->get('pageNo'));
      } else {
        $page_no = 1;
      }

      $per_page = 100;

      # hit api
      $api_response = $inven_api->list_threshold_qtys();
      if($api_response['status']) {
        $items = $api_response['results']['results'];
        $slno = Utilities::get_slno_start(count($items),$per_page,$page_no);
        $to_sl_no = $slno+$per_page;
        $slno++;

        if($page_no<=3) {
          $page_links_to_start = 1;
          $page_links_to_end = 10;
        } else {
          $page_links_to_start = $page_no-3;
          $page_links_to_end = $page_links_to_start+10;            
        }

        if($api_response['results']['total_pages']<$page_links_to_end) {
          $page_links_to_end = $api_response['results']['total_pages'];
        }

        if($api_response['results']['this_page'] < $per_page) {
          $to_sl_no = ($slno+$api_response['results']['this_page'])-1;
        }

        $total_pages = $api_response['results']['total_pages'];
        $total_records = $api_response['results']['total_records'];
        $record_count = $api_response['results']['this_page'];
      } else {
        $page_error = 'No threshold item qtys. are available';
      }      

      # prepare template
      $template_vars = array(
        'items' => $items,
        'total_pages' => $total_pages ,
        'total_records' => $total_records,
        'record_count' =>  $record_count,
        'sl_no' => $slno,
        'to_sl_no' => $to_sl_no,
        'page_links_to_start' => $page_links_to_start,
        'page_links_to_end' => $page_links_to_end,
        'current_page' => $page_no,
        'page_error' => $page_error,
        'page_success' => $page_success,
      );

      $controller_vars = array(
        'page_title' => 'Inventory - Threshold',
        'icon_name' => 'fa fa-angle-double-up',
      );

      # render template
      $template = new Template($this->views_path);
      return array($template->render_view('list-threshold-qty',$template_vars),$controller_vars);      
    }

    public function trackItem(Request $request) {
      $page_error = $page_success = $item_name = '';
      $errors = $submitted_data = $item_details = $total_trans = array();

      $inven_api = new Inventory;
      $flash = new Flash;

      if(count($request->request->all())>0) {
        $params = $request->request->all();
        $submitted_data['itemName'] = Utilities::clean_string($params['itemName']);

        # hit api
        $api_response = $inven_api->track_item($submitted_data);
        $status = $api_response['status'];

        // echo '<pre>';
        // print_r($api_response);
        // echo '</pre>';
        // exit;

        if($status === false) {
          if(isset($api_response['errors'])) {
            $errors = $api_response['errors'];
          } elseif(isset($api_response['apierror'])) {
            $page_error = $api_response['apierror'];
          }
        } else {
            $total_trans = $api_response['items'];
            $item_name = ' [ '.$submitted_data['itemName'].' ]';
        }
      }

      # prepare template
      $template_vars = array(
        'total_trans' => $total_trans,
        'submitted_data' => $submitted_data,
      );

      $controller_vars = array(
        'page_title' => 'Inventory - Item Track'.$item_name,
        'icon_name' => 'fa fa-angle-double-up',
      );

      # render template
      $template = new Template($this->views_path);
      return array($template->render_view('item-track',$template_vars),$controller_vars);      
    }

    /** redis integration **/
    public function createCacheAction(Request $request) {

      $client_id = Utilities::get_current_client_id();
      $item_names = [];
      $set_name = $client_id.'_products';

      echo 'set name is...'.$set_name.'<br />';

      $inven_api = new \PharmaRetail\Inventory\Model\Inventory;    
      $search_params = array(
        'perPage' => 300,
      );
      $total_items = array();
      $inven_response = $inven_api->item_master_with_pp($search_params);
      if($inven_response['status']===false) {
        die("<h1>No data is available. Change Report Filters and Try again</h1>");
      } else {
        $total_items = $inven_response['response']['items'];
        $total_pages = $inven_response['response']['total_pages'];
        if($total_pages>1) {
          for($i=2;$i<=$total_pages;$i++) {
            $search_params['pageNo'] = $i;
            $inven_response = $inven_api->item_master_with_pp($search_params);
            if($inven_response['status'] === true) {
              $total_items = array_merge($total_items,$inven_response['response']['items']);
            }
          }
        }
      }

      # get item names from the array.
      $item_names = array_column($total_items, 'itemName');
      $item_names_flipped = array_flip($item_names);

      $redis_client = new \Predis\Client(array(
        'scheme' => 'tcp',
        'host'   => 'localhost',
        'port'   => 6379,
      ));

      $redis_client->zadd($set_name, $item_names_flipped);
      exit;

/*      $template_vars = array(
        'item_names' => $item_names,
      );

      $controller_vars = array(
        'page_title' => 'Inventory - Create Product Cache',
        'icon_name' => 'fa fa-angle-double-up',
      );
      
      # render template
      $template = new Template($this->views_path);
      return array($template->render_view('create-product-cache',$template_vars),$controller_vars);*/
    }

    /** fetch items from redis **/
    public function getProductsFromCacheAction(Request $request) {

      $search_string = !is_null($request->get('q')) ? $request->get('q') : die('Nothing to search');
      
      $products = [];

      $client_id = Utilities::get_current_client_id();        
      $set_name = $client_id.'_products';

      $redis_client = new \Predis\Client(array(
        'scheme' => 'tcp',
        'host'   => 'localhost',
        'port'   => 6379,
      ));

      $pattern = $search_string.'*';
      foreach (new Iterator\SortedSetKey($redis_client, $set_name, $pattern) as $key => $value) {
        $products[] = $key;
      }

      echo count($products)>0 ? implode("\n", $products) : '';
      exit;
    }

}