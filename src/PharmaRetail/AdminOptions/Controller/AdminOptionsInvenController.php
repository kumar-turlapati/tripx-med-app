<?php

namespace PharmaRetail\AdminOptions\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Atawa\Utilities;
use Atawa\Constants;
use Atawa\Template;
use Atawa\Flash;

use Spreadsheet_Excel_Reader;

use PharmaRetail\Openings\Model\Openings;
use PharmaRetail\Grn\Model\Grn;
use PharmaRetail\Suppliers\Model\Supplier;

class AdminOptionsInvenController
{
	
  protected $views_path, $template, $sales_model, $purch_model, $flash;

	public function __construct() {
		$this->views_path = __DIR__.'/../Views/';
		$this->template = new Template($this->views_path);
    $this->openings_model = new Openings();
    $this->grn_model = new Grn();
		$this->flash = new Flash();
    $this->supplier_model = new Supplier();
	}

  # Upload inventory through xls.
  public function uploadInventory(Request $request) {

    $upload_options = array(
      0 => 'Choose',
      1 => 'Delete existing data and Upload',
      2 => 'Add to already existing data',
    );

    $redirect_url = '/admin-options/upload-inventory';

    if( isset($_FILES['fileName']) && count($_FILES['fileName'])>0 && $_FILES['fileName']['name'] !== '' ) {

      $form_data = $request->request->all();
      $upload_type = isset($form_data['uploadType'])?$form_data['uploadType']:0;
      if((int)$upload_type===0) {
        $this->flash->set_flash_message('You must choose Upload type while uploading Inventory.',1);
        Utilities::redirect('/admin-options/upload-inventory');
      }

      # check uploaded file information
      $file_details = $_FILES['fileName'];
      $file_name = $file_details['name'];
      $extension = pathinfo($file_name, PATHINFO_EXTENSION);

      # check if we have valid file extension
      if(strtolower($extension) !== 'xls') {
        $this->flash->set_flash_message('Invalid file uploaded. Only (.xls) file format is allowed',1);
        Utilities::redirect($redirect_url);
      }

      # upload file to server
      $file_upload_path = __DIR__.'/../../../../bulkuploads/inventory';
      $storage = new \Upload\Storage\FileSystem($file_upload_path);
      $file = new \Upload\File('fileName', $storage);

      $uploaded_file_name = $file->getNameWithExtension();
      $uploaded_file_ext = $file->getExtension();
      if($uploaded_file_ext !== 'xls') {
        $this->flash->set_flash_message('Invalid file extension',1);
        Utilities::redirect($redirect_url);        
      }

      # get client code from Session.
      $client_code = Utilities::get_current_client_id();

      # upload file.
      $new_filename = $client_code.'_inv_'.time();
      $file->setName($new_filename);
      try {
        $file->upload();
      } catch (\Exception $e) {
        $this->flash->set_flash_message('Unknown error. Unable to upload your file.',1);
        Utilities::redirect($redirect_url);        
      }

      # build excel file name
      $excel_file_name = $file_upload_path.'/'.$new_filename.'.xls';

      $process_status = $this->_process_uploaded_file($excel_file_name);
      if($process_status['status'] === false) {
        switch ($process_status['code']) {
          case '001':
            $error_message = 'Invalid file format. Though the extension looks like as given it is not valid.';
            break;
          case '002':
            $error_message = 'Bulk upload will be allowed for only 300 products at a time. If you have more products please split into multiple files and upload.';
            break;
        }
        $this->flash->set_flash_message($error_message,1);
        Utilities::redirect($redirect_url);        
      } else {

        # hit api with data.
        $api_response = $this->openings_model->upload_inventory($process_status['products'],$upload_type);
        if($api_response['status']===false) {
          $this->flash->set_flash_message('Unable to upload inventory. Please contact Atawa administrator.',1);
          Utilities::redirect($redirect_url);
        } else {
          $this->flash->set_flash_message('Inventory uploaded successfully.');
          Utilities::redirect($redirect_url);
        }
      }
    }

    # prepare form variables.
    $template_vars = array(
      'upload_options' => $upload_options,
    );

    # build variables
    $controller_vars = array(
      'page_title' => 'Inventory Opening Balances, Item Master, and Category creation through file upload',
      'icon_name' => 'fa fa-upload',
    );

    # render template
    return array($this->template->render_view('upload-inventory',$template_vars),$controller_vars);
  }

  # update GRN
  public function updateGrn(Request $request) {
    
    # initialize variables.
    $form_data = $form_errors = $suppliers_a = array();
    $total_item_rows = 0;
    $api_error = '';

    # check if the form is submitted.
    if(count($request->request->all()) > 0) {



    # check if we get GRN No and it is numeric
    } elseif( is_null($request->get('gn')) || !is_numeric($request->get('gn')) ) {
      Utilities::redirect('/admin-options/enter-grn-no');
    }

    $grn_no = $request->get('gn');
    $api_response = $this->grn_model->get_grn_details($grn_no, 'no');
    if($api_response['status'] === false) {
      $this->flash->set_flash_message('Invalid GRN No.', 1);
      Utilities::redirect('/admin-options/enter-grn-no');
    } else {
      $grn_details = $api_response['grnDetails'];

      # convert received item details to template item details.
      $item_names = array_column($grn_details['itemDetails'],'itemName');
      $inward_qtys = array_column($grn_details['itemDetails'],'itemQty');
      $free_qtys = array_column($grn_details['itemDetails'],'freeQty');
      $batch_nos = array_column($grn_details['itemDetails'],'batchNo');
      $ex_months = array_column($grn_details['itemDetails'],'expdateMonth');
      $ex_years = array_column($grn_details['itemDetails'],'expdateYear');
      $mrps = array_column($grn_details['itemDetails'],'mrp');
      $item_rates = array_column($grn_details['itemDetails'],'itemRate');
      $tax_percents = array_column($grn_details['itemDetails'],'vatPercent');
      $item_codes = array_column($grn_details['itemDetails'],'itemCode');
      $discounts = array_column($grn_details['itemDetails'], 'discount');
      foreach($ex_months as $key=>$value) {
        if($value<10) {
          $value = '0'.$value;
        }
        $exp_dates[] = $value.'/'.$ex_years[$key];
      }

      # unser item details from api data.
      unset($grn_details['itemDetails']);

      # create form data variable.
      $form_data = $grn_details;
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

    # build variables
    $controller_vars = array(
      'page_title' => 'Update GRN',
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

    # render template
    return array($this->template->render_view('grn-update',$template_vars),$controller_vars);    
  }

  # delete GRN
  public function deleteGrn(Request $request) {

  }

  # ask for GRN
  public function askForGrnNo(Request $request) {

    $grn_no = $page_error = '';

    if(count($request->request->all()) > 0) {
      $grn_no = Utilities::clean_string($request->get('editGrnNo'));
      if( !is_numeric($grn_no) ) {
        $page_error = 'Invalid GRN No.';
      } else {
        Utilities::redirect('/admin-options/updateGrn?gn='.$grn_no);
      }
    }

    # build variables
    $controller_vars = array(
      'page_title' => 'GRN Number Input',
      'icon_name' => 'fa fa-laptop',
    );

    $template_vars = array(
      'grn_no' => $grn_no,
      'page_error' => $page_error,
    );

    # render template
    return array($this->template->render_view('ask-for-grnno',$template_vars),$controller_vars);
  }
/************************* private functions should start from here... **********************************/
  private function _process_uploaded_file($file_name='') {
    
    require_once __DIR__.'/../../../../libraries/excel/excel_reader2.php';
    
    $breaks = array("\r\n","\n","\r");

    # read excel sheet.
    $data = new Spreadsheet_Excel_Reader($file_name);
    if(is_null($data->data)) {
      return array(
        'status' => false,
        'code' => '001',
      );
    }

    $iCount = 2;
    $maxRows = $data->rowcount($sheet_index=0);
    if($maxRows>300) {
      return array(
        'status' => false,
        'code' => '002',
      );
    }

    $products = array();

    while($iCount <= $maxRows) {

      if($data->val($iCount,'B') != '' ) {

        $exp_month = 12; $exp_year = 99;

        $item_name = trim(str_replace($breaks,"",$data->val($iCount,'B')));
        $opening_qty = trim(str_replace($breaks,"",$data->val($iCount,'C')));
        $opening_rate = trim(str_replace($breaks,"",$data->val($iCount,'D')));
        $upp = trim(str_replace($breaks,"",$data->val($iCount,'E')));
        $category_name =  trim(str_replace($breaks,"",$data->val($iCount,'F')));
        $batch_no =  trim(str_replace($breaks,"",$data->val($iCount,'G')));
        $exp_date =  trim(str_replace($breaks,"",$data->val($iCount,'H')));
        $tax_percent =  trim(str_replace($breaks,"",$data->val($iCount,'I')));

        if($exp_date !== '') {
          $exp_date_a = explode('/', $exp_date);
          if(is_array($exp_date_a) && count($exp_date_a)==2) {
            $exp_month = $exp_date_a[0];
            $exp_year = $exp_date_a[1];
          }
        }

        if($batch_no === '') {
          $batch_no = date("dMy").'_'.Utilities::generate_unique_string(12);
        }

        if(!is_numeric($upp)) {
          $upp = 1;
        }

        $products[] = array(
          'itemName' => strtoupper($item_name),
          'opQty' => $opening_qty,
          'opRate' => $opening_rate,
          'upp' => $upp,
          'categoryName' => $category_name,
          'batchNo' => $batch_no,
          'expMonth' => $exp_month,
          'expYear' => $exp_year,
          'taxPercent' => $tax_percent,
        );

      } # end of if loop
      $iCount++;
    } # end of while loop

    return array(
      'status' => true,
      'products' => $products,
    );
  }

}