<?php
  use Atawa\Utilities;
  use Atawa\Constants;
  use Atawa\Flash;
  $flash_obj = new Flash;

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
  if(isset($submitted_data['discountPercent']) && $submitted_data['discountPercent'] !== '') {
    $discount_percent = (int)$submitted_data['discountPercent'];
  } else {
    $discount_percent = 0;
  }

  if(isset($_SESSION['utype']) && (int)$_SESSION['utype'] === 3) {
    $disable_discount = false;
  } else {
    $disable_discount = true;
  }
  // dump($submitted_data);
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
          <!-- Button style -->
          <div class="pull-right text-right">
            <a href="/sales/list" class="btn btn-default"><i class="fa fa-book"></i> Daywise Sales List</a>
            <a href="/sales/entry" class="btn btn-default"><i class="fa fa-file-text-o"></i> New Sale </a> 
          </div>
          <!-- Button style --> 
        </div>
        
        <!-- Right links ends --> 
        
        <!-- Form starts -->
        <form class="form-validate form-horizontal" method="POST">
          <div class="panel">
          <div class="panel-body">
          <h2 class="hdg-reports borderBottom">Transaction Details</h2>
          <div class="form-group">
            <div class="col-sm-12 col-md-4 col-lg-4 m-bot15">
              <label class="control-label">Sale category </label>
              <div class="select-wrap">
                <select class="form-control" id="saleType" name="saleType" <?php echo $disabled ?>>
                  <?php 
                    foreach($sale_types as $key=>$value): 
                        if($saleType === $key) {
                          $selected = 'selected="selected"';
                        } else {
                          $selected = '';
                        }                       
                  ?>
                    <option value="<?php echo $key ?>" <?php echo $selected ?>><?php echo $value ?></option>
                  <?php endforeach; ?>
                </select>
                <?php if(isset($errors['saleType'])): ?>
                  <span class="error"><?php echo $errors['saleType'] ?></span>
                <?php endif; ?>                
              </div>
            </div>
            <div class="col-sm-12 col-md-4 col-lg-4 m-bot15">
              <label class="control-label">Patient reference no. (for IP and OP transactions)</label>
              <input type="text" class="form-control" name="registrationNo" id="registrationNo" value="<?php echo $patientRefNumber ?>">
            </div>
            <div class="col-sm-12 col-md-4 col-lg-4 m-bot15">
              <label class="control-label">Mode of Sale </label>
              <div class="select-wrap">
                <select class="form-control" id="saleMode" name="saleMode">
                  <?php 
                    foreach($sale_modes as $key=>$value): 
                      if((int)$saleMode === $key) {
                        $selected = 'selected="selected"';
                      } else {
                        $selected = '';
                      }                       
                  ?>
                    <option value="<?php echo $key ?>" <?php echo $selected ?>><?php echo $value ?></option>
                  <?php endforeach; ?>
                </select>
                <?php if(isset($errors['saleMode'])): ?>
                  <span class="error"><?php echo $errors['saleMode'] ?></span>
                <?php endif; ?>              
              </div>
            </div>                      
          </div>

          <div class="form-group">
            <?php if( (int)$_SESSION['utype'] === 3): ?>
              <div class="col-sm-12 col-md-4 col-lg-4">
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
            <?php else: ?>
              <div class="col-sm-12 col-md-4 col-lg-4">
                <label class="control-label">Date of sale (dd-mm-yyyy)</label>
                <div class="form-group">
                  <div class="col-lg-12" style="font-size: 18px; color: #225992"><?php echo $current_date ?></div>
                </div>
              </div>
              <input type="hidden" name="saleDate" id="saleDate" value="<?php echo $current_date ?>" />
            <?php endif; ?>

            <?php if( (int)$paymentMethod === 0 && (int)$_SESSION['utype'] === 3 ): ?>
              <div class="col-sm-12 col-md-4 col-lg-4 m-bot15">
                <label class="control-label">Payment method</label>
                <div class="select-wrap">
                  <select class="form-control" name="paymentMethod" id="paymentMethod">
                    <?php 
                        foreach($payment_methods as $key=>$value):
                          if((int)$paymentMethod === $key) {
                            $selected = 'selected="selected"';
                          } else {
                            $selected = '';
                          }                        
                    ?>
                      <option value="<?php echo $key ?>" <?php echo $selected ?>>
                        <?php echo $value ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                  <?php if(isset($errors['paymentMethod'])): ?>
                    <span class="error"><?php echo $errors['paymentMethod'] ?></span>
                  <?php endif; ?>
                </div>
              </div>
            <?php else: ?>
              <div class="col-sm-12 col-md-4 col-lg-4 m-bot15">
                <label class="control-label">Payment method</label>
                <div class="form-group">
                  <div class="col-lg-12" style="font-size: 18px; color: #225992"><?php echo $payment_methods[$paymentMethod] ?></div>
                </div>                
                <input type="hidden" name="paymentMethod" id="paymentMethod" value="<?php echo $paymentMethod ?>" />
              </div>
            <?php endif; ?>

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

            <div class="col-sm-12 col-md-4 col-lg-4">
              <label class="control-label">Patient name</label>
              <input type="text" class="form-control" name="name" id="name" value="<?php echo $patientName ?>">
            </div>
            <div class="col-sm-12 col-md-4 col-lg-4">
              <label class="control-label">Patient age</label>
              <div class="row">
                <div class="col-sm-6 col-md-6 col-lg-8">
                  <div class="select-wrap">
                    <select class="form-control" name="age" id="age">
                      <?php 
                        foreach($ages as $key=>$value):
                          if((int)$patientAge === (int)$key) {
                            $selected = 'selected="selected"';
                          } else {
                            $selected = '';
                          }                          
                      ?>
                        <option value="<?php echo $key ?>" <?php echo $selected ?>><?php echo $value ?></option>
                      <?php endforeach; ?>
                    </select>
                    <?php if(isset($errors['age'])): ?>
                      <span class="error"><?php echo $errors['age'] ?></span>
                    <?php endif; ?>                    
                  </div>
                </div>
                <div class="col-sm-6 col-md-6 col-lg-4"> 
                  <div class="select-wrap">
                    <select class="form-control" name="ageCategory" id="ageCategory">
                      <?php 
                        foreach($age_categories as $key=>$value):
                          if($patientAgeCategory === $key) {
                            $selected = 'selected="selected"';
                          } else {
                            $selected = '';
                          }                           
                      ?>
                        <option value="<?php echo $key ?>" <?php echo $selected ?>><?php echo $value ?></option>
                      <?php endforeach; ?>
                    </select>
                    <?php if(isset($errors['ageCategory'])): ?>
                      <span class="error"><?php echo $errors['ageCategory'] ?></span>
                    <?php endif; ?>                     
                  </div> 
                </div>
              </div>
            </div>
            <div class="col-sm-12 col-md-4 col-lg-4 m-bot15">
              <label class="control-label">Gender</label>
              <div class="select-wrap">
                <select class="form-control" name="gender" id="gender">
                  <?php 
                    foreach($genders as $key=>$value):
                      if($patientGender === $key) {
                        $selected = 'selected="selected"';
                      } else {
                        $selected = '';
                      }                      
                  ?>
                    <option value="<?php echo $key ?>" <?php echo $selected ?>><?php echo $value ?></option>
                  <?php endforeach; ?>
                </select>
                <?php if(isset($errors['gender'])): ?>
                  <span class="error"><?php echo $errors['gender'] ?></span>
                <?php endif; ?>                 
              </div>
            </div>
           
          </div>

          <div class="form-group">
            <div class="col-sm-12 col-md-4 col-lg-4 m-bot15">
              <label class="control-label">Mobile number</label>
              <input type="text" class="form-control" name="mobileNo" id="mobileNo" maxlength="10" value="<?php echo $patientMobileNumber ?>">
              <?php if(isset($errors['mobileNo'])): ?>
                <span class="error"><?php echo $errors['mobileNo'] ?></span>
              <?php endif; ?>              
            </div>
            <div class="col-sm-12 col-md-4 col-lg-4 m-bot15">
              <label class="control-label">Referred by (Doctor name) </label>
              <div class="select-wrap">
                <select class="form-control" id="doctorID" name="doctorID">
                  <?php 
                    foreach($doctors as $key=>$value):
                     if((int)$doctorID === (int)$key) {
                      $selected = 'selected="selected"';
                     } else {
                      $selected = '';
                     }                      
                  ?>
                    <option value="<?php echo $key ?>" <?php echo $selected ?>><?php echo $value ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="col-sm-12 col-md-4 col-lg-4 m-bot15">
              <label class="control-label">Transaction status</label>
              <div class="select-wrap">
                <select class="form-control" name="status" id="status">
                  <?php foreach($status as $key=>$value): ?>
                    <option value="<?php echo $key ?>"><?php echo $value ?></option>
                  <?php endforeach; ?>
                </select>
                <?php if(isset($errors['status'])): ?>
                  <span class="error"><?php echo $errors['status'] ?></span>
                <?php endif; ?>
              </div>
            </div>            
          </div>
          <?php /*
          <div class="form-group">
            <div class="col-sm-12 col-md-4 col-lg-4">
              <label class="control-label">Discount allowed?</label>
              <div class="row">
                <div class="col-sm-6">
                  <div>
                    <input type="radio" name="discount" value="1" id="discountRadio1" <?php echo ($discount_amount>0?"checked":"") ?> disabled>
                    <label class="radio radio-inline" for="discountRadio1">Yes</label>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div>
                    <input type="radio" name="discount" value="0" id="discountRadio0" disabled>
                    <label class="radio radio-inline" for="discountRadio0">No</label>
                  </div>
                </div>
              </div>
            </div>
          </div>*/ ?>

            <?php if(!$disable_discount): ?>
            <div class="col-sm-12 col-md-4 col-lg-4">
              <label class="control-label">Discount percent</label>
              <div class="row">
                <div class="col-sm-3">
                  <div>
                    <input type="radio" name="discount" value="0" id="discountRadio0" <?php echo ($discount_percent === 0 ?"checked" : "") ?>>
                    <label class="radio radio-inline" for="discountRadio0">0%</label>
                  </div>
                </div>                    
                <div class="col-sm-3">
                  <div>
                    <input type="radio" name="discount" value="5" id="discountRadio5" <?php echo ($discount_percent === 5 ?"checked" : "") ?>>
                    <label class="radio radio-inline" for="discountRadio5">5%</label>
                  </div>
                </div>
                <div class="col-sm-3">
                  <div>
                    <input type="radio" name="discount" value="10" id="discountRadio10" <?php echo ($discount_percent === 10 ?"checked" : "") ?>>
                    <label class="radio radio-inline" for="discountRadio10">10%</label>
                  </div>
                </div>
                <div class="col-sm-3">
                  <div>
                    <input type="radio" name="discount" value="15" id="discountRadio15" <?php echo ($discount_percent === 15 ?"checked" : "") ?>>
                    <label class="radio radio-inline" for="discountRadio15">15%</label>
                  </div>
                </div>
              </div>
            </div>
            <?php else: ?>
              <input type="hidden" value="<?php echo $discount_percent ?>" name="discount" />
            <?php endif; ?>

          </div>
          </div>
          <h2 class="hdg-reports">Item Details</h2>
          <?php if(isset($errors['itemDetails'])): ?>
            <span class="error"><?php echo $errors['itemDetails'] ?></span>
          <?php endif; ?>          
          <div class="table-responsive">

            <table class="table table-striped table-hover">
              <thead>
                <tr>
                  <th width="5%" class="text-center">Sl.<br />No.</th>                  
                  <th width="30%" class="text-center">Item Name</th>
                  <th width="10%" class="text-center">Batch No.</th>
                  <th width="10%" class="text-center">Ordered<br />Qty.</th>
                  <th width="10%" class="text-center">Expiry Date (mm/yy)</th>
                  <th width="10%" class="text-center">Item Rate<br />(in Rs.)</th>
                  <th width="10%" class="text-center">Amount<br />(in Rs.)</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td colspan="6" align="right">Net Pay</td>
                  <td id="netPay" align="right" class="netPay"><?php echo ($netpay>0?number_format($netpay,2):'') ?></td>
                </tr>                
                <?php
                  for($i=1;$i<=count($submitted_data['itemDetails']);$i++):
                    $ex_index = $i-1;
                    // dump($submitted_data['itemDetails']);
                    $batch_no_a = array('xx99!!'=>'Sel');
                    if(isset($submitted_data['itemDetails'][$ex_index])) {
                      $item_name = $submitted_data['itemDetails'][$ex_index]['itemName'];
                      $item_qty = $submitted_data['itemDetails'][$ex_index]['itemQty'];
                      $item_rate = $submitted_data['itemDetails'][$ex_index]['itemRate'];
                      $batch_no = $submitted_data['itemDetails'][$ex_index]['batchNo'];
                      $exp_date = $submitted_data['itemDetails'][$ex_index]['expDate'];
                      $batch_no_a["$batch_no"] = $batch_no;
                      $item_total = round($item_qty*$item_rate,2);
                      $ava_qty = '-';
                      $item_total = number_format($item_total,2);
                    } else {
                      $item_name = '';
                      $item_qty = 0;
                      $item_rate = '';
                      $batch_no = '';
                      $item_total = '';
                    }                    
                ?>
                  <tr>
                    <td align="right"><?php echo $i ?></td>
                    <td><?php echo $item_name ?></td>
                    <td><?php echo $batch_no ?></td>                    
                    <td align="right"><?php echo $item_qty ?></td>
                    <td class="expDate" id="expdate_<?php echo $i-1 ?>" index="<?php echo $i-1 ?>" align="right"><?php echo $exp_date ?></td>
                    <td class="mrp text-right" id="mrp_<?php echo $i-1 ?>" index="<?php echo $i-1 ?>">
                      <?php echo $item_rate ?>
                    </td>
                    <td class="itemTotal text-right" id="itemtotal_<?php echo $i-1 ?>" index="<?php echo $i-1 ?>">
                      <?php echo $item_total ?>                      
                    </td>
                  </tr>
                <?php endfor; ?>
                  <tr>
                    <td colspan="6" align="right">Bill Amount</td>
                    <td id="billAmount" align="right" class="billAmount">
                      <?php echo ($bill_amount>0?number_format($bill_amount,2):'') ?> 
                    </td>
                  </tr>
                  <tr>
                    <td colspan="6" align="right">(-) Discount</td>
                    <td align="right" id="discount">
                      <?php echo ($discount_amount>0?number_format($discount_amount,2):'') ?>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="6" align="right">Total Amount</td>
                    <td id="totalAmount" align="right" class="totalAmount">
                      <?php echo ($total_amount>0?number_format($total_amount,2):'') ?>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="6" align="right"> (+/-)Round off</td>
                    <td id="roundOff" align="right" class="roundOff">
                      <?php echo ($round_off>0?number_format($round_off,2):'') ?>
                    </td>
                  </tr>                                   
                  <tr>
                    <td colspan="6" align="right">Net Pay</td>
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
          </div>
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
      var printUrl = '/print-sales-bill?billNo='+<?php echo $bill_to_print ?>;
      window.open(printUrl, "_blank", "scrollbars=yes, titlebar=yes, resizable=yes, width=400, height=400");     
    })();
  </script>
<?php endif; ?>
