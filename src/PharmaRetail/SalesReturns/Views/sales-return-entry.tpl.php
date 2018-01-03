<?php

  use Atawa\Constants;

  if(isset($template_vars) && is_array($template_vars)) {
    extract($template_vars);
  }

  /************************************ Extract Form data ***************************/

  if(isset($submitted_data['returnDate']) && $submitted_data['returnDate']!=='') {
    $current_date = date("d-m-Y", strtotime($submitted_data['returnDate']));
  } else {
    $current_date = date("d-m-Y");
  }

  if(isset($submitted_data['mrnNo']) && $submitted_data['mrnNo']!=='') {
    $mrn_no       = $submitted_data['mrnNo'];
  } else {
    $mrn_no       = '';
  }

  if(isset($submitted_data['totalReturnAmount']) && $submitted_data['totalReturnAmount']>0) {
    $totalReturnAmount = $submitted_data['totalReturnAmount'];
  } else {
    $totalReturnAmount = 0;
  }

  if(isset($submitted_data['totalReturnAmountRound']) && $submitted_data['totalReturnAmountRound']>0) {
    $totalReturnAmountRound = $submitted_data['totalReturnAmountRound'];
  } else {
    $totalReturnAmountRound = 0;
  }

  if(isset($submitted_data['returnAmount']) && $submitted_data['returnAmount']>0) {
    $returnAmount = $submitted_data['returnAmount'];
  } else {
    $returnAmount = 0;
  }     


  // # calculate total amount if in edit mode.
  // if(isset($sale_details['itemDetails']) && count($sale_details['itemDetails'])>0) {

  // }

  // if(isset($sale_details['invoiceDate']) && $sale_details['invoiceDate']!=='') {
  //   $sale_date = date("d-m-Y", strtotime($sale_details['invoiceDate']));
  // } else {
  //   $sale_date = date("d-m-Y");
  // }
  // if(isset($sale_details['creditDays'])) {
  //   $credit_days = $sale_details['creditDays'];
  // } else {
  //   $credit_days = '';
  // }
  // if(isset($sale_details['billNo'])) {
  //   $bill_no = $sale_details['billNo'];
  // } else {
  //   $bill_no = '';
  // }
  // if(isset($sale_details['paymentMethod'])) {
  //   $payment_method = Constants::$PAYMENT_METHODS[$sale_details['paymentMethod']];
  // } else {
  //   $payment_method = '** INVALID **';
  // }  
  // if(isset($sale_details['saleType'])) {
  //   $sale_type = Constants::$SALE_TYPES_NUM[$sale_details['saleType']];
  // } else {
  //   $sale_type = '** INVALID **';
  // }
  // if(isset($sale_details['creditDays']) && $sale_details['creditDays']>0) {
  //   $credit_days = $sale_details['creditDays'];
  // } else {
  //   $credit_days = '';
  // }

  // if($sale_type==='GEN') {
  //   $patientName = $sale_details['customerName'];
  //   $patientAge = $sale_details['customerAge'];
  //   $patientAgeCategory = $sale_details['customerAgeCategory'];
  //   $patientGender = $sale_details['customerGender'];
  //   $patientMobileNumber = $sale_details['customerMobileNo'];
  //   $patientRefNumber = '';
  // } else {
  //   $patientName = $sale_details['patientName'];
  //   $patientAge = $sale_details['patientAge'];
  //   $patientAgeCategory = $sale_details['patientAgeCategory'];
  //   $patientGender = $sale_details['patientGender'];
  //   $patientMobileNumber = $sale_details['patientMobileNo'];
  //   $patientRefNumber = $sale_details['patientRefNumber'];
  // }

  $total_amount = $net_pay = 0;
  /************************************ End of Form data ***************************/
  if($mrn_no !== '') {
    $disable_form_data = 'disabled';
  } else {
    $disable_form_data = '';
  }

  // dump($tot_return_qtys);
?>

<!-- Basic form starts -->
<div class="row">
  <div class="col-lg-12"> 
    
    <!-- Panel starts -->
    <section class="panelBox">
      <div class="panelBody">
        <?php echo $flash_obj->print_flash_message(); ?>
        <?php if($page_error !== ''): ?>
          <div class="alert alert-danger" role="alert">
            <strong>Error!</strong> <?php echo $page_error ?> 
          </div>
        <?php elseif($page_success !== ''): ?>
          <div class="alert alert-success" role="alert">
            <strong>Success!</strong> <?php echo $page_success ?> 
          </div>
        <?php endif; ?>
        <!-- Right links starts -->
        <div class="global-links actionButtons clearfix"> 
          <!-- Button style -->
          <div class="pull-right text-right">
            <a href="/sales-return/list" class="btn btn-default"><i class="fa fa-book"></i> Daywise Sales Return List</a>
          </div>
          <!-- Button style --> 
        </div>
        <!-- Right links ends --> 
        
        <!-- Form starts -->
        <form class="form-validate form-horizontal" method="POST">
          <div class="panel">
            <div class="panel-body">          
              <h2 class="hdg-reports borderBottom">Return Details</h2>
              <div class="form-group">
                  <div class="col-sm-12 col-md-4 col-lg-4 m-bot15">
                    <label class="control-label">Return date</label>
                    <div class="input-append date" data-date="<?php echo $current_date ?>" data-date-format="dd-mm-yyyy">
                      <input class="span2" value="<?php echo $current_date ?>" size="16" type="text" readonly name="returnDate" id="returnDate" />
                      <span class="add-on"><i class="fa fa-calendar"></i></span>
                    </div>
                  </div>
                  <div class="col-sm-12 col-md-4 col-lg-4 m-bot15">
                    <label class="control-label">MRN No. (Auto)</label>
                    <input type="text" class="form-control" name="mrnNo" id="mrnNo" value="<?php echo $mrn_no ?>" disabled>
                  </div>                     
              </div>
            </div>
          </div>
          <h2 class="hdg-reports borderBottom">Return Item Details</h2>
          <?php if(isset($errors['itemDetails'])): ?>
            <span class="error"><?php echo $errors['itemDetails'] ?></span>
          <?php endif; ?>          
          <div class="table-responsive">
            <table class="table table-striped table-hover">
              <thead>
                <tr>
                  <th width="5%" class="text-center">Sl.No.</th>                  
                  <th width="30%" class="text-center">Item Name</th>
                  <th width="10%" class="text-center">Batch No.</th>
                  <th width="10%" class="text-center">Expiry Date (mm/yy)</th>
                  <th width="10%" class="text-center">Item Rate</th>
                  <th width="10%" class="text-center">Sold<br />Qty.</th>
                  <th width="10%" class="text-center">Previous<br />returns</th>
                  <th width="10%" class="text-center">Current<br />returns</th>
                  <th width="10%" class="text-center">Amount</th>
                </tr>
              </thead>
              <tbody class="font12">               
                <?php
                  $item_total = $total_amount = $item_total = 0;
                  for($i=0;$i<count($sale_item_details);$i++):
                      $item_code = $sale_item_details[$i]['itemCode'];
                      $item_name = $sale_item_details[$i]['itemName'];
                      $item_qty = $sale_item_details[$i]['itemQty'];
                      $item_rate = $sale_item_details[$i]['itemRate'];
                      $batch_no = $sale_item_details[$i]['batchNo'];
                      $exp_date = $sale_item_details[$i]['expDate'];

                      $item_string = $item_name.'$'.$item_code.'$';

                      if(isset($return_items[$item_name]) && $return_items[$item_name]>0) {
                        $return_qty = $return_items[$item_name];
                        $disabled = 'disabled="disabled"';
                      } else {
                        $return_qty = 0;
                        $disabled = '';
                      }

                      if(isset($tot_return_qtys[$item_code]) && $tot_return_qtys[$item_code]>0) {
                        $return_ason_date = $tot_return_qtys[$item_code];
                        $return_allowed_qty = $item_qty - $tot_return_qtys[$item_code];
                      } elseif(isset($tot_return_qtys[$item_code]) && $tot_return_qtys[$item_code]<0) {
                        $return_ason_date = $tot_return_qtys[$item_code];
                        $return_allowed_qty = 0;
                      } else {
                        $return_allowed_qty = $item_qty;
                        $return_ason_date = 0;
                      }

                      $return_qty_a = array_slice($qtys_a,0,($return_allowed_qty+1));
                      $return_value = $item_rate*$return_qty;
                ?>
                  <tr>
                    <td align="right" style="vertical-align:middle;"><?php echo $i+1 ?></td>
                    <td style="vertical-align:middle;"><?php echo $item_name ?></td>
                    <td align="right" style="vertical-align:middle;"><?php echo $batch_no ?></td>                    
                    <td align="right" style="vertical-align:middle;"><?php echo $exp_date ?></td>
                    <td align="right" style="vertical-align:middle;" id="returnRate_<?php echo $i ?>">
                      <?php echo $item_rate ?>
                    </td>
                    <td align="right" style="vertical-align:middle;"><?php echo $item_qty ?></td>
                    <td id="returnason_<?php echo $i ?>" align="right" class="itemReturnValueAson" style="vertical-align:middle;">
                      <?php echo $return_ason_date ?>
                    </td>                    
                    <td style="vertical-align:middle;">
                      <div class="select-wrap">
                        <input type="hidden" name="itemInfo[]" id="<?php echo $item_code ?>" value="<?php echo $item_string ?>" />
                        <select class="form-control returnQty" name="returnQty_<?php echo $item_code.'_'.$i ?>" id="returnQty_<?php echo $i ?>" <?php echo $disabled ?>>
                          <?php 
                            foreach($return_qty_a as $key=>$value): 
                              if((int)$value===(int)$return_qty) {
                                $selected = 'selected="selected"';
                              } else {
                                $selected = '';
                              }
                          ?>
                            <option value="<?php echo $key ?>" <?php echo $selected ?>><?php echo $value ?></option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                      <?php if(isset($errors['returnQty'])): ?>
                        <span class="error"><?php echo $errors['returnQty'] ?></span>
                      <?php endif; ?>                      
                    </td>
                    <td id="returnValue_<?php echo $i ?>" align="right" class="itemReturnValue" style="vertical-align:middle;">
                      <?php echo number_format($return_value,2) ?>
                    </td>                  
                  </tr>
                <?php endfor; ?>
                  <tr>
                    <td colspan="8" align="right">Total Amount</td>
                    <td id="totalAmount" align="right" class="totalAmount">
                      <?php echo number_format($totalReturnAmount,2)?>                      
                    </td>
                  </tr>
                  <tr>
                    <td colspan="8" align="right">Round off</td>
                    <td id="totalAmount" align="right" class="roundOff">
                      <?php echo number_format($totalReturnAmountRound,2)?>
                    </td>
                  </tr>                  
                  <tr>
                    <td colspan="8" align="right">Total Return Value</td>
                    <td id="netPay" align="right" class="netPay"><?php echo number_format($returnAmount,2) ?></td>
                  </tr>                                 
              </tbody>
            </table>
          </div>
          <div class="text-center">
            <button class="btn btn-primary" id="Save" <?php echo $disabled ?>>
              <i class="fa fa-save"></i> <?php echo $btn_label ?>
            </button>
          </div>
          <input type="hidden" id="status" name="status" value="1" />          
        </form>  
      </div>
    </section>
    <!-- Panel ends --> 
  </div>
</div>
<!-- Basic Forms ends -->