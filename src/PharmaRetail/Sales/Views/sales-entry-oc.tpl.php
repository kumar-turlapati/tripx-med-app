<?php
  use Atawa\Utilities;
  use Atawa\Constants;
  use Atawa\Flash;
  $flash_obj = new Flash;

  /************************************ Extract Form data ***************************/

  if( isset($submitted_data['invoiceCode']) ) {
    $bill_amount = $submitted_data['billAmount'];
    $discount_amount = $submitted_data['discountAmount'];
    $total_amount = $submitted_data['totalAmount'];
    $tax_amount = $submitted_data['taxAmount'];
    $round_off = $submitted_data['roundOff'];
    $netpay = $submitted_data['netPay'];
    $disabled = 'disabled';
  } else {
    $bill_amount = $discount_amount = $total_amount = $tax_amount = $round_off = $netpay = 0;
    $disabled = '';
  }

  if(isset($submitted_data['invoiceDate']) && $submitted_data['invoiceDate']!=='') {
    $current_date = date("d-m-Y", strtotime($submitted_data['invoiceDate']));
  } else {
    $current_date = date("d-m-Y");
  }
  if(isset($submitted_data['creditDays'])) {
    $creditDays = $submitted_data['creditDays'];
  } else {
    $creditDays = 0;
  }
  if(isset($submitted_data['billNo'])) {
    $billNo = $submitted_data['billNo'];
  } else {
    $billNo = '';
  }
  if(isset($submitted_data['paymentMethod'])) {
    $paymentMethod = $submitted_data['paymentMethod'];
  } else {
    $paymentMethod = 0;
  }  
  if( isset($submitted_data['saleType']) && is_numeric($submitted_data['saleType']) ) {
    $saleType = Constants::$SALE_TYPES_FORM[$submitted_data['saleType']];
  } elseif(isset($submitted_data['saleType']) && is_string($submitted_data['saleType'])) {
    $saleType = $submitted_data['saleType'];
  } else {
    $saleType = 'GEN';
  }
  if( isset($submitted_data['saleMode']) && is_numeric($submitted_data['saleMode']) ) {
    $saleMode = $submitted_data['saleMode'];
  } else {
    $saleMode = 0;
  }  

  // dump($submitted_data['itemDetails']['itemName']);
  // dump($saleType);
  // exit;

  if(count($submitted_data)>0) {
    $patientName = (isset($submitted_data['patientName'])?$submitted_data['patientName']:'');
    $patientAge = (isset($submitted_data['patientAge'])?$submitted_data['patientAge']:'');
    $patientAgeCategory = (isset($submitted_data['patientAgeCategory'])?$submitted_data['patientAgeCategory']:'');
    $patientGender = (isset($submitted_data['patientGender'])?$submitted_data['patientGender']:'');
    $patientMobileNumber = (isset($submitted_data['patientMobileNo'])?$submitted_data['patientMobileNo']:'');
    $patientRefNumber = (isset($submitted_data['patientRefNumber'])?$submitted_data['patientRefNumber']:'');
    $doctorID = $submitted_data['doctorID'];
  } else {
    $patientName = '';
    $patientAge = '';
    $patientAgeCategory = 'years';
    $patientGender = '';
    $patientMobileNumber = '';
    $patientRefNumber = '';
    $doctorID = '';
  }
  /************************************ End of Form data ***************************/
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
          <div class="pull-right text-right">
            <a href="/sales/list" class="btn btn-default"><i class="fa fa-book"></i> Daywise Sales List</a>
            <a href="/sales/entry" class="btn btn-default"><i class="fa fa-file-text-o"></i> New Sale </a> 
          </div>
        </div>
        <!-- Right links ends --> 
        
        <!-- Form starts -->
        <form class="form-validate form-horizontal" method="POST">
          <h2 class="hdg-reports">Transaction Details</h2>
          <div class="panel">
            <div class="panel-body">
              <div class="form-group">
                <div class="col-sm-12 col-md-4 col-lg-4 m-bot15">
                  <label class="control-label">Date of sale (dd-mm-yyyy)</label>
                  <div class="form-group">
                    <div class="col-lg-12">
                      <div class="input-append date" data-date="<?php echo $current_date ?>" data-date-format="dd-mm-yyyy">
                        <input class="span2" value="<?php echo $current_date ?>" size="16" type="text" readonly name="saleDate" id="saleDate" />
                        <span class="add-on"><i class="fa fa-calendar"></i></span>
                      </div>
                      <?php if(isset($errors['saleDate'])): ?>
                        <span class="error"><?php echo $errors['saleDate'] ?></span>
                      <?php endif; ?>                  
                    </div>
                  </div>
                </div>
                <div class="col-sm-12 col-md-4 col-lg-4 m-bot15">
                  <label class="control-label">Payment method</label>
                  <div class="select-wrap">
                    <select class="form-control" name="paymentMethod" id="paymentMethod">
                      <?php 
                          foreach($payment_methods as $key=>$value):
                            if($paymentMethod === $key) {
                              $selected = 'selected="selected"';
                            } else {
                              $selected = '';
                            }                        
                      ?>
                        <option value="<?php echo $key ?>" <?php echo $selected ?>><?php echo $value ?></option>
                      <?php endforeach; ?>
                    </select>
                    <?php if(isset($errors['paymentMethod'])): ?>
                      <span class="error"><?php echo $errors['paymentMethod'] ?></span>
                    <?php endif; ?>
                  </div>
                </div>
                <div class="col-sm-12 col-md-4 col-lg-4 m-bot15">
                  <label class="control-label">Credit period (in days)</label>
                  <div class="select-wrap">
                    <select class="form-control" name="creditDays" id="creditDays">
                      <?php 
                        foreach($credit_days_a as $key=>$value):
                            if($creditDays === $key) {
                              $selected = 'selected="selected"';
                            } else {
                              $selected = '';
                            }                      
                      ?>
                        <option value="<?php echo $key ?>" <?php echo $selected ?>><?php echo $value ?></option>
                      <?php endforeach; ?>
                    </select>
                    <?php if(isset($errors['creditDays'])): ?>
                      <span class="error"><?php echo $errors['creditDays'] ?></span>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-12 col-md-4 col-lg-4 m-bot15">
                  <label class="control-label">Customer mobile number</label>
                  <input type="text" class="form-control noEnterKey" name="mobileNo" id="mobileNo" maxlength="10" value="<?php echo $patientMobileNumber ?>">
                  <?php if(isset($errors['mobileNo'])): ?>
                    <span class="error"><?php echo $errors['mobileNo'] ?></span>
                  <?php endif; ?>              
                </div>
                <div class="col-sm-12 col-md-4 col-lg-4 m-bot15">
                  <label class="control-label">Customer name</label>
                  <input type="text" class="form-control noEnterKey" name="name" id="name" value="<?php echo $patientName ?>">
                </div>
                <div class="col-sm-12 col-md-4 col-lg-4 m-bot15">
                  <label class="control-label">Discount allowed?</label>
                  <div class="row">
                    <div class="col-sm-6">
                      <div>
                        <input type="radio" name="discount" value="1" id="discountRadio1" <?php echo ($discount_amount>0?"checked":"") ?>>
                        <label class="radio radio-inline" for="discountRadio1">Yes</label>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div>
                        <input type="radio" name="discount" value="0" id="discountRadio0">
                        <label class="radio radio-inline" for="discountRadio0">No</label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <h2 class="hdg-reports">Item Details</h2>
          <?php if(isset($errors['itemDetails'])): ?>
            <span class="error"><?php echo $errors['itemDetails'] ?></span>
          <?php endif; ?>          
          <div class="table-responsive">

            <table class="table table-striped table-hover font14">
              <thead>
                <tr>
                  <th width="5%" class="text-center">Sno.</th>                  
                  <th width="20%" class="text-left">Item name</th>
                  <th width="10%" class="text-center">Units/<br />pack</th>                  
                  <th width="10%" class="text-center">Batch no.</th>
                  <th width="10%" class="text-center">Ordered<br />qty.</th>
                  <th width="10%" class="text-center">Available<br />qty.</th>
                  <th width="10%" class="text-center">Expiry<br />(mm/yy)</th>
                  <th width="10%" class="text-center">Item rate<br />(in Rs.)</th>
                  <th width="10%" class="text-center">Amount<br />(in Rs.)</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td colspan="8" align="right"><b>Net Pay</b></td>
                  <td id="netPay" align="right" class="netPay"><?php echo ($netpay>0?number_format($netpay,2):'') ?></td>
                </tr>                
                <?php
                  for($i=1;$i<=25;$i++):
                    $ex_index = $i-1;
                    $batch_no_a = array('xx99!!'=>'Sel');
                    if(isset($submitted_data['itemDetails'][$ex_index])) {
                      $item_name = $submitted_data['itemDetails'][$ex_index]['itemName'];
                      $item_qty = $submitted_data['itemDetails'][$ex_index]['itemQty'];
                      $item_rate = $submitted_data['itemDetails'][$ex_index]['itemRate'];
                      $batch_no = $submitted_data['itemDetails'][$ex_index]['batchNo'];
                      $batch_no_a["$batch_no"] = $batch_no;
                      $item_total = round($item_qty*$item_rate,2);
                      $item_total = number_format($item_total,2);
                      $disabled = "disabled";
                    } else {
                      $item_name = (isset($submitted_data['itemDetails']['itemName'][$ex_index])?$submitted_data['itemDetails']['itemName'][$ex_index]:'');
                      $item_qty = (isset($submitted_data['itemDetails']['itemQty'][$ex_index])?$submitted_data['itemDetails']['itemQty'][$ex_index]:0);
                      $item_rate = '';
                      $batch_no = '';
                      $item_total = '';
                      $disabled='';
                    }                    
                ?>

                  <tr>
                    <td align="right">
                      <?php echo $i ?>
                    </td>
                    <td>
                      <input 
                        type="text" 
                        name="itemDetails[itemName][]" 
                        id="iname_<?php echo $i-1 ?>" 
                        size="30" 
                        class="inameAc saleItem noEnterKey" 
                        index="<?php echo $i-1 ?>" 
                        value="<?php echo $item_name ?>"
                        <?php echo $disabled ?>
                      />
                    </td>
                    <td id="upp_<?php echo $i-1 ?>" align="right">&nbsp;</td>
                    <td>
                      <div class="select-wrap">
                        <select 
                          class="form-control batchNo" 
                          name="itemDetails[batchNo][]" 
                          id="batchno_<?php echo $i-1 ?>" 
                          index="<?php echo $i-1 ?>"
                          <?php echo $disabled ?>                          
                        >
                            <?php 
                              foreach($batch_no_a as $key=>$value):
                                if($batch_no == $key) {
                                  $selected = 'selected="selected"';
                                } else {
                                  $selected = '';
                                }                                
                            ?>
                              <option value="<?php echo $key ?>" <?php echo $selected ?>><?php echo $value ?></option>
                            <?php endforeach; ?>
                        </select>
                      </div>
                    </td>                    
                    <td>
                        <div class="select-wrap">
                          <select 
                            class="form-control itemQty" 
                            name="itemDetails[itemQty][]" 
                            id="qty_<?php echo $i-1 ?>" 
                            index="<?php echo $i-1 ?>"
                            <?php echo $disabled ?>                            
                          >
                            <?php 
                              foreach($qtys_a as $key=>$value):
                                 if((int)$item_qty === (int)$key) {
                                  $selected = 'selected="selected"';
                                 } else {
                                  $selected = '';
                                 }                                 
                            ?>
                              <option value="<?php echo $key ?>" <?php echo $selected ?>><?php echo $value ?></option>
                            <?php endforeach; ?>
                          </select>
                        </div>
                    </td>
                    <td class="qtyAvailable text-right" id="qtyava_<?php echo $i-1 ?>" index="<?php echo $i-1 ?>">&nbsp;</td>
                    <td class="expDate" id="expdate_<?php echo $i-1 ?>" index="<?php echo $i-1 ?>">&nbsp;</td>
                    <td class="mrp text-right" id="mrp_<?php echo $i-1 ?>" index="<?php echo $i-1 ?>">
                      <?php echo $item_rate ?>
                    </td>
                    <td class="itemTotal text-right" id="itemtotal_<?php echo $i-1 ?>" index="<?php echo $i-1 ?>">
                      <?php echo $item_total ?>                      
                    </td>
                  </tr>
                <?php endfor; ?>
                  <tr>
                    <td colspan="8" align="right">Bill Amount</td>
                    <td id="billAmount" align="right" class="billAmount">
                      <?php echo ($bill_amount>0?number_format($bill_amount,2):'') ?> 
                    </td>
                  </tr>
                  <tr>
                    <td colspan="8" align="right">(-) Discount</td>
                    <td align="right" id="discount">
                      <?php echo ($discount_amount>0?number_format($discount_amount,2):'') ?>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="8" align="right">Total Amount</td>
                    <td id="totalAmount" align="right" class="totalAmount">
                      <?php echo ($total_amount>0?number_format($total_amount,2):'') ?>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="8" align="right"> (+/-)Round off</td>
                    <td id="roundOff" align="right" class="roundOff">
                      <?php echo ($round_off>0?number_format($round_off,2):'') ?>
                    </td>
                  </tr>                                   
                  <tr>
                    <td colspan="8" align="right">Net Pay</td>
                    <td id="netPay" align="right" class="netPay">
                      <?php echo ($netpay>0?number_format($netpay,2):'') ?>
                    </td>
                  </tr>                                 
              </tbody>
            </table>

          </div>
          <div class="text-center">
            <button class="btn btn-primary" id="Save" name="op" value="Save">
              <i class="fa fa-save"></i> <?php echo $btn_label ?>
            </button>
            <button class="btn btn-primary" id="SaveandPrint" name="op" value="SaveandPrint">
              <i class="fa fa-print"></i> Save &amp; Print
            </button>
            <button class="btn btn-danger btn-sm" id="SaveandPrintBill" name="op" value="SaveandPrintBill">
              <i class="fa fa-files-o"></i> Save &amp; Bill Print
            </button>
          </div>
          <input type="hidden" id="status" name="status" value="1" />
          <input type="hidden" id="saleType" name="saleType" value="GEN" />
          <input type="hidden" name="registrationNo" id="registrationNo" value="" />
          <input type="hidden" id="saleMode" name="saleMode" value="0" />
          <input type="hidden" name="age" id="age" value="99" />
          <input type="hidden" name="ageCategory" id="ageCategory" value="years" />
          <input type="hidden" name="gender" id="gender" value="o" />
          <input type="hidden" id="doctorID" name="doctorID" value="" />          
        </form>  
      </div>
    </section>
    <!-- Panel ends --> 
  </div>
</div>
<!-- Basic Forms ends -->

<?php if($bill_to_print>0) : ?>
  <script>
    (function() {
      <?php if($print_format === 'bill'): ?>
        var printUrl = '/print-sales-bill-small?billNo='+<?php echo $bill_to_print ?>;
        var printWindow = window.open(printUrl, "_blank", "left=0,top=0,width=300,height=300,toolbar=0,scrollbars=0,status=0");
      <?php else: ?>
        var printUrl = '/print-sales-bill?billNo='+<?php echo $bill_to_print ?>;
        var printWindow = window.open(printUrl, "_blank", "scrollbars=yes, titlebar=yes, resizable=yes, width=400, height=400");     
      <?php endif; ?>
    })();
  </script>
<?php endif; ?>

<?php 
/*
                    <td>
                      <div class="select-wrap">
                        <select 
                          class="form-control batchNo" 
                          name="itemDetails[batchNo][]" 
                          id="batchno_<?php echo $i-1 ?>" 
                          index="<?php echo $i-1 ?>"
                          <?php echo $disabled ?>                          
                        >
                            <?php 
                              foreach($batch_no_a as $key=>$value):
                                if($batch_no == $key) {
                                  $selected = 'selected="selected"';
                                } else {
                                  $selected = '';
                                }                                
                            ?>
                              <option value="<?php echo $key ?>" <?php echo $selected ?>><?php echo $value ?></option>
                            <?php endforeach; ?>
                        </select>
                      </div>
                    </td> 
                    <td class="expDate" id="expdate_<?php echo $i-1 ?>" index="<?php echo $i-1 ?>">&nbsp;</td>


                    */?>
