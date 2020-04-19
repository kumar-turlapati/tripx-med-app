<?php 

namespace PharmaRetail\Reports\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Atawa\Utilities;
use Atawa\Constants;
use Atawa\Template;
use Atawa\PDF;

use PharmaRetail\Sales\Model\Sales;

class ReportsController
{

  protected $views_path;

  public function __construct() {
	$this->views_path = __DIR__.'/../Views/';
  }

  public function printSalesBillSmall(Request $request) {

    # inititate Sales Model
    $sales = new \PharmaRetail\Sales\Model\Sales;
    $user_model = new \PharmaRetail\User\Model\User;

    $billNo = Utilities::clean_string($request->get('billNo'));
    $slno = 0;

    # get user details
    if(isset($_SESSION['uname']) && $_SESSION['uname'] !== '') {
      $operator_name = substr($_SESSION['uname'],0,12);
    } else {
      $operator_name = '';
    }

    $params['billNo'] = $billNo;
    $print_date_time = date("d-M-Y h:ia");

    $sales_response = $sales->get_sales_details($billNo,true);
    $status = $sales_response['status'];
    if($status) {
      $sale_details = $sales_response['saleDetails'];
      $sale_item_details = $sale_details['itemDetails'];
      unset($sale_details['itemDetails']);
    } else {
      die($this->_get_print_error_message());
    }

    $template_vars = array(
      'sale_details' => $sale_details,
      'sale_item_details' => $sale_item_details,
    );
    $controller_vars = array(

    );

    // render template
    $template = new Template($this->views_path);
    return array($template->render_view('print-sale-bill-small', $template_vars), $controller_vars);
  }

  public function printSalesBill(Request $request) {

    # inititate Sales Model
    $sales = new \PharmaRetail\Sales\Model\Sales;
    $user_model = new \User\Model\User;

  	$billNo = Utilities::clean_string($request->get('billNo'));
    $slno = 0;

    # get user details
    if(isset($_SESSION['uname']) && $_SESSION['uname'] !== '') {
      $operator_name = substr($_SESSION['uname'],0,12);
    } else {
      $operator_name = '';
    }

    $params['billNo'] = $billNo;
    $print_date_time = date("d-M-Y h:ia");

    $sales_response = $sales->get_sales_details($billNo,true);
    $status = $sales_response['status'];
    if($status) {
      $sale_details = $sales_response['saleDetails'];
      $sale_item_details = $sale_details['itemDetails'];
      unset($sale_details['itemDetails']);
    } else {
      die($this->_get_print_error_message());
    }

    $bill_date      =    date('d-M-Y',strtotime($sale_details['invoiceDate']));
    $bill_time      =    date('h:ia',strtotime($sale_details['createdTime']));
    $bill_no        =    $sale_details['billNo'];
    $sale_type      =    $sale_details['saleType'];
    $pay_method     =    Constants::$PAYMENT_METHODS[$sale_details['paymentMethod']];
    $doctor_name    =    ($sale_details['doctorName']!=''?$sale_details['doctorName']:'-');
    $sale_type_txt  =    Constants::$SALE_TYPES_NUM[(int)$sale_type];
    $terms_text     =    'Note: [1] Please get your medicines checked by Doctor before use. [2] Production of original bill is mandatory for return of items. [3] Item returns/replacement will not be possible after 48 hours. [4] Total amount is inclusive of all applicable taxes.';
    $bill_amount    =    $sale_details['billAmount'];
    $bill_discount  =    $sale_details['discountAmount'];
    $total_amount   =    $sale_details['totalAmount'];
    $total_amt_r    =    $sale_details['roundOff'];
    $net_pay        =    $sale_details['netPay'];
    $patient_name   =    ($sale_details['patientName']!==null?$sale_details['patientName']:'');
    $patient_age    =    ($sale_details['patientAge']!==null?$sale_details['patientAge']:'');
    $age_category   =    ($sale_details['patientAgeCategory']!==null?$sale_details['patientAgeCategory']:'');
    $gender         =    ($sale_details['patientGender']!==null?$sale_details['patientGender']:'');
    $ipop_ref_no    =    $sale_details['patientRefNumber'];
    $tax_amount     =    $sale_details['taxAmount'];
    $sale_mode      =    Constants::$SALE_MODES[(int)$sale_details['saleMode']];

    if((int)$sale_type===2) {
        $ip_ref_label = 'I.P.No.';
    } elseif((int)$sale_type===3) {
        $ip_ref_label = 'O.P.No.';
    } else {
        $ip_ref_label = 'Ref. No.';
    }

    # start PDF printing.
    $pdf = PDF::getInstance();
    $pdf->AliasNbPages();
    $pdf->AddPage('P','A4');

    # Print Bill Information.
    $pdf->SetFont('Arial','B',16);
    $pdf->Cell(0,0,'Bill of Sale '.'[ '.$sale_mode.' ]','',1,'C');
    
    $pdf->SetFont('Arial','B',9);
    $pdf->Ln(4);

    # first row
    $header_widths = array(70,40,40,40);
    $item_widths = array(12,98,35,15,15,15);
    $totals_width = $item_widths[0]+$item_widths[1]+$item_widths[2]+$item_widths[3]+$item_widths[4];
    $terms1_width = $item_widths[0]+$item_widths[1]+$item_widths[2]+$item_widths[3];
    $terms2_width = $item_widths[4]+$item_widths[5];

    $pdf->Cell($header_widths[0],6,'Name','LRTB',0,'C');
    $pdf->Cell($header_widths[1],6,'Age & Gender','RTB',0,'C');
    $pdf->Cell($header_widths[2],6,$ip_ref_label,'RTB',0,'C');
    $pdf->Cell($header_widths[3],6,'Referred By','RTB',1,'C');
    $pdf->SetFont('Arial','',8);
    $pdf->Cell($header_widths[0],6,substr(strtoupper(strtolower($patient_name)),0,35),'LRTB',0,'C');

    if($patient_age>0) {
        $pdf->Cell($header_widths[1],6,$patient_age.' '.$age_category,'LRTB',0,'C');
    } else {
        $pdf->Cell($header_widths[1],6,' ','LRTB',0,'C');
    }

    $pdf->Cell($header_widths[2],6,$ipop_ref_no,'RTB',0,'C');
    $pdf->Cell($header_widths[3],6,$doctor_name,'RTB',0,'C');
 
    # second row
    $pdf->SetFont('Arial','B',9);
    $pdf->Ln();
    $pdf->Cell($header_widths[0],6,'Bill No.','LRTB',0,'C');
    $pdf->Cell($header_widths[1],6,'Bill Date & Time','RTB',0,'C');
    $pdf->Cell($header_widths[2],6,'Sale Type','RTB',0,'C');
    $pdf->Cell($header_widths[3],6,'Paid Through','RTB',1,'C');
    
    $pdf->SetFont('Arial','B',14);
    $pdf->Cell($header_widths[0],6,$bill_no,'LRTB',0,'C');
    $pdf->SetFont('Arial','',8);
    $pdf->Cell($header_widths[1],6,$bill_date.', '.$bill_time,'RTB',0,'C');
    $pdf->Cell($header_widths[2],6,$sale_type_txt,'RTB',0,'C');    
    $pdf->Cell($header_widths[3],6,$pay_method,'RTB',0,'C');

    # third row(s)
    $pdf->SetFont('Arial','B',9);
    $pdf->Ln();
    $pdf->Cell($item_widths[0],6,'Sl.No.','LRTB',0,'C');
    $pdf->Cell($item_widths[1],6,'Item Name','RTB',0,'C');
    $pdf->Cell($item_widths[2],6,'Batch No.&Expiry','RTB',0,'C');
    $pdf->Cell($item_widths[3],6,'Qty.','RTB',0,'C');
    $pdf->Cell($item_widths[4],6,'Rate','RTB',0,'C');  
    $pdf->Cell($item_widths[5],6,'Amount','RTB',0,'C');
    $pdf->SetFont('Arial','',8);

    foreach($sale_item_details as $item_details) {
      $slno++;
      $amount = $item_details['itemQty']*$item_details['itemRate'];
      $batch_no_a = explode('_',$item_details['batchNo']);
      if(is_array($batch_no_a) && count($batch_no_a)>1) {
        $batch_no = $batch_no_a[1];
      } else {
        $batch_no = $item_details['batchNo'];
      }
        
      $pdf->Ln();
      $pdf->Cell($item_widths[0],6,$slno,'LRTB',0,'R');
      $pdf->Cell($item_widths[1],6,$item_details['itemName'],'RTB',0,'L');
      $pdf->Cell($item_widths[2],6,$batch_no.', '.$item_details['expDate'],'RTB',0,'L');
      $pdf->Cell($item_widths[3],6,number_format($item_details['itemQty'],2),'RTB',0,'R');
      $pdf->Cell($item_widths[4],6,number_format($item_details['itemRate'],2),'RTB',0,'R');  
      $pdf->Cell($item_widths[5],6,number_format($amount,2),'RTB',0,'R');
    }

    $pdf->Ln();
    $pdf->SetFont('Arial','B',9);    
    $pdf->Cell($totals_width,6,'Gross Total','LRTB',0,'R');
    $pdf->Cell($item_widths[5],6,$bill_amount,'LRTB',0,'R');
    $pdf->Ln();

    # print only when there is discount.
    if($bill_discount>0) {
        $pdf->Cell($totals_width,6,'(-) Discount','LRTB',0,'R');
        $pdf->Cell($item_widths[5],6,$bill_discount,'LRTB',0,'R');
        $pdf->Ln();
        $pdf->Cell($totals_width,6,'Total Amount','LRTB',0,'R');
        $pdf->Cell($item_widths[5],6,$total_amount,'LRTB',0,'R');
        $pdf->Ln();    
    }

    /*
    if($tax_amount>0) {
        $net_pay_string = '***************** VAT AMOUNT: Rs.'.number_format($tax_amount,2).'  ***************** Net Pay';
    } else {
        $net_pay_string = "Net Pay";
    }*/

    $net_pay_string = "Net Pay";
    
    $pdf->Cell($totals_width,6,'(+/-) Round Off','LRTB',0,'R');
    $pdf->Cell($item_widths[5],6,$total_amt_r,'RTB',0,'R');
    
    $pdf->Ln();
    $pdf->Cell($totals_width,6,$net_pay_string,'LRTB',0,'R');
    $pdf->Cell($item_widths[5],6,$net_pay,'RTB',0,'R');
    
    $pdf->Ln();
    $pdf->SetFont('Arial','',8);
    $pdf->MultiCell($terms1_width+$terms2_width,4,$terms_text,'LRTB','L');

    $pdf->Cell(57,10,'Print Date & Time: '.$print_date_time,'LRTB',0,'L');
    $pdf->Cell(47,10,'Operator Name: '.$operator_name,'RTB',0,'L');
    $pdf->Cell(86,10,'Authorized Signature: ','RTB',0,'L');    
    $pdf->Output();
  }

  /**
   * report options for each report.
  **/
  public function reportOptions(Request $request) {

    $report_name = Utilities::clean_string($request->get('reportName'));

    if(count($request->request->all())>0) {

        $request_params = $request->request->all();
        $report_url = $request_params['reportHook'];
        unset($request_params['reportHook']);

        $query_params = http_build_query($request_params);
        $redirect_url = $report_url.'?'.$query_params;
        Utilities::redirect($redirect_url);
        
    } else {
        switch($report_name) {
            case 'sales-register':
                $template_name = 'template-1';
                $template_vars = array(
                    'title' => 'Sales Register',
                    'formAction' => '/report-options/sales-register',
                    'reportHook' => '/sales-register',
                );
                $controller_vars = array(
                    'page_title' => 'Sales Register',
                    'icon_name' => 'fa fa-inr',
                );
                break;
            case 'sales-return-register':
                $template_name = 'template-1';
                $template_vars = array(
                    'title' => 'Sales Return Register',
                    'formAction' => '/report-options/sales-return-register',
                    'reportHook' => '/sales-return-register',
                );
                $controller_vars = array(
                    'page_title' => 'Sales Return Register',
                    'icon_name' => 'fa fa-inr',
                );
                break;
            case 'grn-register':
                $template_name = 'template-1';
                $template_vars = array(
                    'title' => 'GRN Register',
                    'formAction' => '/report-options/grn-register',
                    'reportHook' => '/grn-register',
                );
                $controller_vars = array(
                    'page_title' => 'GRN Register',
                    'icon_name' => 'fa fa-laptop',
                );
                break;
            case 'sales-summary-by-month':
                $template_name = 'template-2';
                $template_vars = array(
                    'title' => 'Sales Summary - Monthly',
                    'formAction' => '/report-options/sales-summary-by-month',
                    'reportHook' => '/sales-summary-by-month',
                );
                $controller_vars = array(
                    'page_title' => 'Sales Summary - Daywise',
                    'icon_name' => 'fa fa-inr',
                );
                break;
            case 'day-sales-report':
                $template_name = 'template-3';
                $template_vars = array(
                    'title' => 'Sales Summary - Day',
                    'formAction' => '/report-options/day-sales-report',
                    'reportHook' => '/day-sales-report',
                );
                $controller_vars = array(
                    'page_title' => 'Sales Summary - Day',
                    'icon_name' => 'fa fa-inr',
                );            
                break;
            case 'sales-summary-patient':
                $template_name = 'template-4';
                $template_vars = array(
                    'title' => 'Sales Summary - By Patient',
                    'formAction' => '/report-options/sales-summary-patient',
                    'reportHook' => '/sales-summary-patient',
                    'patient_types' => array(''=>'Choose')+Constants::$PATIENT_TYPES,
                );
                $controller_vars = array(
                    'page_title' => 'Patient Sales Summary',
                    'icon_name' => 'fa fa-inr',
                );            
                break;
            case 'stock-report':
                $template_name = 'template-5';
                $filter_types = array(
                    ''=>'Choose', 'all' => 'All', 'neg' => 'Negative'
                );
                $template_vars = array(
                    'title' => 'Stock Report',
                    'formAction' => '/report-options/stock-report',
                    'reportHook' => '/stock-report-new',
                    'dropDownlabel' => 'Show',
                    'filter_types' => $filter_types,
                );
                $controller_vars = array(
                    'page_title' => 'Stock Report',
                    'icon_name' => 'fa fa-laptop',
                );            
                break;
            case 'stock-report-new':
                $template_name = 'template-5';
                $filter_types = array(
                    ''=>'Choose', 'all' => 'All', 'neg' => 'Negative'
                );
                $template_vars = array(
                    'title' => 'Stock Report',
                    'formAction' => '/report-options/stock-report-new',
                    'reportHook' => '/stock-report-new',
                    'dropDownlabel' => 'Show',
                    'filter_types' => $filter_types,
                );
                $controller_vars = array(
                    'page_title' => 'Stock Report',
                    'icon_name' => 'fa fa-laptop',
                );            
                break;                
            case 'expiry-report':
                $template_name = 'template-2';
                $template_vars = array(
                    'title' => 'Stock Report',
                    'formAction' => '/report-options/expiry-report',
                    'reportHook' => '/expiry-report',
                );
                $controller_vars = array(
                    'page_title' => 'Medicine Expiry Report',
                    'icon_name' => 'fa fa-times',
                );
                break;                
            case 'itemwise-sales-report':
                $template_name = 'template-3';
                $template_vars = array(
                    'title' => 'Itemwise Sales Report',
                    'formAction' => '/report-options/itemwise-sales-report',
                    'reportHook' => '/itemwise-sales-report'
                );
                $controller_vars = array(
                    'page_title' => 'Itemwise Sales Report',
                    'icon_name' => 'fa fa-inr',
                );            
                break;
            case 'itemwise-sales-report-bymode':
              $filter_types = Constants::$SALE_MODES;
              $template_name = 'template-6';
              $template_vars = array(
                'title' => 'Itemwise Sales Report By Sale Mode',
                'formAction' => '/report-options/itemwise-sales-report-bymode',
                'reportHook' => '/itemwise-sales-report-bymode',
                'dropDownlabel' => 'Mode of Sale',
                'filter_types' => $filter_types,                 
              );
              $controller_vars = array(
                'page_title' => 'Itemwise Sales Report By Sale Mode',
                'icon_name' => 'fa fa-inr',
              );
              break;                
            case 'sales-by-mode':
                $template_name = 'template-6';
                $filter_types = array(
                  'all' => 'All','pkg'=>'Package','int'=>'Internal/Self',
                );                
                $template_vars = array(
                    'title' => 'Credit Sales Report',
                    'formAction' => '/report-options/sales-by-mode',
                    'reportHook' => '/sales-by-mode',
                    'dropDownlabel' => 'Mode of Sale',
                    'filter_types' => $filter_types,                    
                );
                $controller_vars = array(
                    'page_title' => 'Credit Sales Report',
                    'icon_name' => 'fa fa-inr',
                );            
                break;
            case 'supplier-payments-due':
                $template_name = 'template-2';
                $template_vars = array(
                    'title' => "Supplier's Payment Due",
                    'formAction' => '/report-options/supplier-payments-due',
                    'reportHook' => '/supplier-payments-due',
                );
                $controller_vars = array(
                    'page_title' => "Supplier's Payment Due -  Monthwise",
                    'icon_name' => 'fa fa-group',
                );
                break;
            case 'itemwise-sales-returns':
                $template_name = 'template-1';
                $template_vars = array(
                  'title' => 'Itemwise Sales Return Register',
                  'formAction' => '/report-options/itemwise-sales-returns',
                  'reportHook' => '/itemwise-sales-returns',
                );
                $controller_vars = array(
                  'page_title' => 'Itemwise Sales Return Register',
                  'icon_name' => 'fa fa-repeat',
                );
                break;
            case 'material-movement':
                $template_name = 'template-7';
                $filter_types = array(
                    'fast' => 'Fast moving','slow'=>'Slow moving',
                );                
                $template_vars = array(
                  'title' => 'Material Movement Register',
                  'formAction' => '/report-options/material-movement',
                  'reportHook' => '/material-movement',
                  'dropDownlabel' => 'Movement Criteria',
                  'filter_types' => $filter_types,
                );
                $controller_vars = array(
                  'page_title' => 'Material Movement Register',
                  'icon_name' => 'fa fa-arrows',
                );
                break;
            case 'io-analysis':
                $template_name = 'template-2';
                $template_vars = array(
                  'title' => 'Inward - Outward Analysis',
                  'formAction' => '/report-options/io-analysis',
                  'reportHook' => '/io-analysis',
                );
                $controller_vars = array(
                  'page_title' => 'Inward - Outward Analysis',
                  'icon_name' => 'fa fa-inr',
                );
                break;
            case 'payables-monthwise':
                $template_name = 'template-2';
                $template_vars = array(
                  'title' => 'Payables - Monthwise',
                  'formAction' => '/report-options/payables-monthwise',
                  'reportHook' => '/payables-monthwise',
                );
                $controller_vars = array(
                  'page_title' => 'Payables - Monthwise',
                  'icon_name' => 'fa fa-inr',
                );
                break;
            case 'inventory-profitability':
                $template_name = 'template-6';
                $filter_types = Constants::$SALE_MODES;  
                $template_vars = array(
                  'title' => 'Inventory Profitability',
                  'formAction' => '/report-options/inventory-profitability',
                  'reportHook' => '/inventory-profitability',
                  'filter_types' => $filter_types,
                  'dropDownlabel' => 'Sale mode',
                  'ssv' => !is_null($request->get('ssv')) ? (int)$request->get('ssv') : 0,
                );
                $controller_vars = array(
                  'page_title' => 'Inventory Profitability',
                  'icon_name' => 'fa fa-level-up',
                );
                break;
            case 'mom-comparison':
                $template_name = 'template-8';
                $filter_types = Constants::$SALE_MODES;  
                $template_vars = array(
                  'title' => 'Month over Month Sales Comparison',
                  'formAction' => '/report-options/mom-comparison',
                  'reportHook' => '/mom-comparison',
                  'filter_types' => $filter_types,
                  'dropDownlabel' => 'Sale mode',
                );
                $controller_vars = array(
                  'page_title' => 'Month over Month Sales Comparison',
                  'icon_name' => 'fa fa-bolt',
                );
                break;                                                        
        }

        $template = new Template($this->views_path);
        return array($template->render_view($template_name, $template_vars), $controller_vars);        
    }
  }

  /**
   * returns error message for the reports.
  **/
  private function _get_print_error_message() {
    return "<h1>Invalid Request</h1>";
  }

}