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
                <div class="col-sm-12 col-md-4 col-lg-4">
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
                <div class="col-sm-12 col-md-4 col-lg-4">
                  <label class="control-label">Patient reference no. (for IP and OP transactions)</label>
                  <input type="text" class="form-control noEnterKey" name="registrationNo" id="registrationNo" value="<?php echo $patientRefNumber ?>">
                </div>
                <?php /*
                <div class="col-sm-12 col-md-4 col-lg-4 m-bot15">
                  <label class="control-label">Bill number (auto)</label>
                  <input type="text" class="form-control" name="billNo" id="billNo" value="<?php echo $billNo ?>" disabled>
                </div>*/ ?>
                <div class="col-sm-12 col-md-4 col-lg-4">
                  <label class="control-label">Mode of Sale </label>
                  <div class="select-wrap">
                    <select class="form-control" id="saleMode" name="saleMode">
                      <?php 
                        foreach($sale_modes as $key=>$value): 
                          if($saleMode === $key) {
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
    
                <div class="col-sm-12 col-md-4 col-lg-4">
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
    
                <div class="col-sm-12 col-md-4 col-lg-4">
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
                  <input type="text" class="form-control noEnterKey" name="name" id="name" value="<?php echo $patientName ?>">
                </div>
                <div class="col-sm-12 col-md-4 col-lg-4 m-bot15">
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
                <div class="col-sm-12 col-md-4 col-lg-4">
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
                <div class="col-sm-12 col-md-4 col-lg-4">
                  <label class="control-label">Mobile number</label>
                  <input type="text" class="form-control noEnterKey" name="mobileNo" id="mobileNo" maxlength="10" value="<?php echo $patientMobileNumber ?>">
                  <?php if(isset($errors['mobileNo'])): ?>
                    <span class="error"><?php echo $errors['mobileNo'] ?></span>
                  <?php endif; ?>              
                </div>
                <div class="col-sm-12 col-md-4 col-lg-4">
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
                <?php /*
                <div class="col-sm-12 col-md-4 col-lg-4">
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
                </div>*/ ?>
                <div class="col-sm-12 col-md-4 col-lg-4">
                  <label class="control-label">Rate includes GST and Other charges?</label>
                  <div class="row">
                    <div class="col-sm-6">
                      <div>
                        <input type="radio" name="rateIncludesGst" value="1" id="rigRadio1" <?php echo ($discount_amount>0?"checked":"") ?>>
                        <label class="radio radio-inline" for="rigRadio1">Yes</label>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div>
                        <input type="radio" name="rateIncludesGst" value="0" id="rigRadio0">
                        <label class="radio radio-inline" for="rigRadio0">No</label>
                      </div>
                    </div>
                  </div>
                </div>                
              </div>
              <div class="form-group">
                <div class="col-sm-12 col-md-4 col-lg-4">
                  <label class="control-label">Shipping Address-1</label>
                  <input type="text" class="form-control noEnterKey" name="shippingAddr1" id="shippingAddr1" maxlength="10" value="">
                  <?php if(isset($errors['shippingAddr1'])): ?>
                    <span class="error"><?php echo $errors['shippingAddr1'] ?></span>
                  <?php endif; ?>              
                </div>
                <div class="col-sm-12 col-md-4 col-lg-4">
                  <label class="control-label">Shipping Address-2</label>
                  <input type="text" class="form-control noEnterKey" name="shippingAddr1" id="shippingAddr1" maxlength="10" value="">
                  <?php if(isset($errors['shippingAddr1'])): ?>
                    <span class="error"><?php echo $errors['shippingAddr1'] ?></span>
                  <?php endif; ?>
                </div>
                <div class="col-sm-12 col-md-4 col-lg-4">
                  <label class="control-label">Shipping State</label>
                  <select class="form-control" name="locState" id="locState">
                    <?php 
                      foreach($states as $key=>$value): 
                        // if((int)$state_id === $key) {
                        //   $selected = 'selected="selected"';
                        // } else {
                        //   $selected = '';
                        // }
                    ?>
                      <option value="<?php echo $key ?>" <?php echo $selected ?>><?php echo $value ?></option>
                    <?php endforeach; ?>              
                  </select>
                  <?php if(isset($form_errors['locState'])): ?>
                    <span class="error"><?php echo $form_errors['locState'] ?></span>
                  <?php endif; ?>                  
                </div>                
              </div>


            </div>
          </div>
          <h2 class="hdg-reports">Item Details</h2>
          <?php if(isset($errors['itemDetails'])): ?>
            <span class="error"><?php echo $errors['itemDetails'] ?></span>
          <?php endif; ?>          
          <div class="table-responsive">

            <table class="table table-striped table-hover font12 valign-middle">
              <thead>
                <tr>
                  <th width="5%" class="text-center valign-middle">Sno.</th>         
                  <th width="15%" class="text-center valign-middle">Item name</th>
                  <th width="7%" class="text-center valign-middle">Batch no.</th>
                  <th width="7%" class="text-center valign-middle">Ordered<br />qty.</th>
                  <th width="7%" class="text-center valign-middle">Units / Uom</th>
                  <th width="7%" class="text-center valign-middle">Ava. Qty /<br />Expiry</th>
                  <th width="7%" class="text-center valign-middle">Item rate<br />(in Rs.)</th>
                  <th width="7%" class="text-center valign-middle">Gross amt.<br />(in Rs.)</th>
                  <th width="5%" class="text-center valign-middle">Discount<br />(in Rs.)</th>                
                  <th width="7%" class="text-center valign-middle">Taxable amt.<br />(in Rs.)</th>
                  <th width="7%" class="text-center valign-middle">G.S.T<br />(in %)</th>
                </tr>
              </thead>
              <tbody>               
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
                    <td align="center" class="font11 valign-middle"><?php echo $i ?></td>
                    <td class="valign-middle">
                      <input 
                        type="text" 
                        name="itemDetails[itemName][]" 
                        id="iname_<?php echo $i-1 ?>"
                        size="20" 
                        class="inameAc saleItem noEnterKey font11" 
                        index="<?php echo $i-1 ?>" 
                        value="<?php echo $item_name ?>"
                        <?php echo $disabled ?>
                      />
                    </td>
                    <td class="valign-middle">
                      <div class="select-wrap">
                        <select 
                          class="form-control batchNo font11 valign-middle" 
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
                    <td class="valign-middle">
                      <input
                        type="text"
                        class="form-control itemQty font11"
                        name="itemDetails[itemQty][]"
                        id="qty_<?php echo $i-1 ?>"
                        index="<?php echo $i-1 ?>"
                      />
                    </td>
                    <td id="upp_<?php echo $i-1 ?>" align="right" class="valign-middle">&nbsp;</td>
                    <td class="qtyAvailable text-right valign-middle" id="qtyava_<?php echo $i-1 ?>" index="<?php echo $i-1 ?>">&nbsp;</td>
                    <td class="mrp text-right valign-middle" id="mrp_<?php echo $i-1 ?>" index="<?php echo $i-1 ?>">
                      <?php echo $item_rate ?>
                    </td>
                    <td class="valign-middle"><?php echo $item_total ?></td>
                    <td class="valign-middle">
                      <input
                        type="text"
                        class="form-control font11"
                        name="itemDetails[discount][]"
                        id="discount_<?php echo $i-1 ?>"
                        index="<?php echo $i-1 ?>"
                        maxlength="5"
                      />
                    </td>
                    <td class="itemTotal text-right valign-middle" id="itemtotal_<?php echo $i-1 ?>" index="<?php echo $i-1 ?>"></td>
                    <td class="valign-middle">
                      <div class="select-wrap">                        
                        <select 
                          class="form-control inwItemTax" 
                          id="inwItemTax_<?php echo $i ?>" 
                          name="taxPercent[]"
                          style="font-size:12px;"
                        >
                          <?php 
                            foreach($taxes as $key=>$value):
                              // if((float)$value === (float)$tax_percent) {
                              //   $selected = 'selected="selected"';
                              // } else {
                                $selected = '';
                              // }
                          ?>
                            <option value="<?php echo (float)$value ?>" <?php echo $selected ?>>
                              <?php echo $value ?>
                            </option>
                          <?php endforeach; ?>                            
                        </select>
                      </div>
                    </td>
                    <?php /*
                    <td class="valign-middle">
                      <input
                        type="text"
                        class="form-control font11"
                        name="itemDetails[cess][]"
                        id="cess_<?php echo $i-1 ?>"
                        index="<?php echo $i-1 ?>"
                        maxlength="4"
                      />
                    </td> */?>
                  </tr>
                <?php endfor; ?>
                  <tr>
                    <td colspan="10" style="vertical-align:middle;font-weight:bold;font-size:14px;text-align:right;">Bill amount</td>
                    <td colspan="2" id="billAmount" align="right" class="billAmount" style="vertical-align:middle;font-weight:bold;font-size:14px;text-align:right;">
                      <?php echo ($bill_amount>0?number_format($bill_amount,2):'') ?> 
                    </td>
                  </tr>
                  <tr>
                    <td colspan="10" style="vertical-align:middle;font-weight:bold;font-size:14px;text-align:right;">(-) Discount</td>
                    <td colspan="2"  style="vertical-align:middle;font-weight:bold;font-size:14px;text-align:right;" id="discount">
                      <?php echo ($discount_amount>0?number_format($discount_amount,2):'') ?>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="10" style="vertical-align:middle;font-weight:bold;font-size:14px;text-align:right;">Taxable Amount</td>
                    <td colspan="2" id="totalAmount"  style="vertical-align:middle;font-weight:bold;font-size:14px;text-align:right;" class="totalAmount">
                      <?php echo ($total_amount>0?number_format($total_amount,2):'') ?>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="10" style="vertical-align:middle;font-weight:bold;font-size:14px;text-align:right;">G.S.T</td>
                    <td colspan="2" id="totalGst" style="vertical-align:middle;font-weight:bold;font-size:14px;text-align:right;" class="totalGst">&nbsp;</td>
                  </tr>
                  <tr>
                    <td style="vertical-align:middle;font-weight:bold;font-size:14px;text-align:right;" colspan="10">(+) Other taxes</td>
                    <td style="vertical-align:middle;text-align:right;font-size:14px;font-weight:bold;" colspan="2">
                      <input
                        type = "text"
                        class="form-control noEnterKey"
                        maxlength="10"
                        id="saAddlTaxes"
                        name="saAddlTaxes"
                        value=""
                        style="text-align:right;"
                      >
                    </td>
                  </tr>
                  <tr>
                    <td style="vertical-align:middle;font-weight:bold;font-size:14px;text-align:right;" colspan="10">(+) Freight / Shipping charges</td>
                    <td style="vertical-align:middle;text-align:right;font-size:14px;font-weight:bold;" colspan="2">
                      <input
                        type = "text"
                        class="form-control noEnterKey"
                        maxlength="10"
                        id="saShippingCharges"
                        name="saShippingCharges"
                        value=""
                        style="text-align:right;"
                      >
                    </td>
                  </tr>
                  <tr>
                    <td style="vertical-align:middle;font-weight:bold;font-size:14px;text-align:right;" colspan="10">Grand total</td>
                    <td style="vertical-align:middle;text-align:right;font-size:14px;font-weight:bold;" colspan="2" id="saGrandTotal"></td>
                  </tr>     
                  <tr>
                    <td style="vertical-align:middle;font-weight:bold;font-size:14px;text-align:right;" colspan="10">(+/-) Adjustments</td>
                    <td style="vertical-align:middle;text-align:right;font-weight:bold;font-size:14px;" colspan="2">
                      <input
                        type = "text"
                        class="form-control noEnterKey"
                        maxlength="10"
                        id="saAdjustment"
                        name="saAdjustment"
                        value=""
                        style="text-align:right;"
                      >
                    </td>
                  </tr>
                  <tr>
                    <td colspan="10" align="right"> (+/-)Round off</td>
                    <td colspan="2" id="roundOff" align="right" class="roundOff">
                      <?php echo ($round_off>0?number_format($round_off,2):'') ?>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="10" align="right" style="vertical-align:middle;font-weight:bold;font-size:18px;text-align:right;">Net Pay</td>
                    <td colspan="2" id="netPay" style="vertical-align:middle;font-weight:bold;font-size:18px;text-align:right;" class="netPay">
                      <?php echo ($netpay>0?number_format($netpay,2):'') ?>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="12" style="text-align:center;font-weight:bold;font-size:16px;">GST Summary</td>
                  </tr>
                  <tr style="padding:0px;margin:0px;">
                    <td colspan="12" style="padding:0px;margin:0px;">
                      <table class="table table-striped table-hover font12 valign-middle">
                        <thead>
                          <th colspan="3" style="text-align:center;">0%</th>
                          <th colspan="3" style="text-align:center;">5%</th>
                          <th colspan="3" style="text-align:center;">12%</th>
                          <th colspan="3" style="text-align:center;">18%</th>
                          <th colspan="3" style="text-align:center;">28%</th>
                        </thead>
                        <tbody>
                          <tr>
                            <?php foreach($taxes as $tax_code => $tax_percent): ?>
                              <td style="text-align:center;font-weight:bold;">Total amt. (Rs.)</td>
                              <td style="text-align:center;font-weight:bold;">Taxable amt. (Rs.)</td>
                              <td style="text-align:center;font-weight:bold;">Tax (Rs.)</td>
                            <?php endforeach; ?>
                          </tr>
                          <tr>
                            <?php foreach($taxes as $tax_code => $tax_percent): ?>
                              <td style="text-align:center;font-weight:bold;" id="<?php echo $tax_code ?>_total">-</td>
                              <td style="text-align:center;font-weight:bold;" id="<?php echo $tax_code ?>_taxable">-</td>
                              <td style="text-align:center;font-weight:bold;" id="<?php echo $tax_code ?>_tax">-</td>
                            <?php endforeach; ?>
                          </tr>
                        </tbody>
                      </table>
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