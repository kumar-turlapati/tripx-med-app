<?php

  // dump($form_data, $form_errors);
  // dump($form_errors);
  // dump($form_data);
  // exit;

  # create dynamic variables for Tax.
  foreach($taxes as $key=>$value) {
    ${'taxAmount_'.$key} = 0;
  }

  if(isset($form_data['purchaseDate'])) {
    $purchase_date = date("d-m-Y", strtotime($form_data['purchaseDate']));
  } else {
    $purchase_date = date("d-m-Y");
  }
  if(isset($form_data['creditDays'])) {
    $creditDays = $form_data['creditDays'];
  } else {
    $creditDays = 0;
  }
  if(isset($form_data['paymentMethod'])) {
    $paymentMethod = $form_data['paymentMethod'];
  } else {
    $paymentMethod = 0;
  }
  if(isset($form_data['supplierID'])) {
    $supplierCode = $form_data['supplierID'];
  } elseif(isset($form_data['supplierCode'])) {
    $supplierCode = $form_data['supplierCode'];
  } else {
    $supplierCode = '';
  }
  if( isset($form_data['discountPercent']) ) {
    $discount_percent = $form_data['discountPercent'];
  } else {
    $discount_percent = 0;
  }
  if(isset($form_data['otherTaxes'])) {
    $other_taxes = $form_data['otherTaxes'];
  } else {
    $other_taxes = 0;
  }
  if(isset($form_data['adjustment'])) {
    $adjustment = $form_data['adjustment'];
  } else {
    $adjustment = 0;
  }
  if(isset($form_data['shippingCharges'])) {
    $shipping_charges = $form_data['shippingCharges'];
  } else {
    $shipping_charges = 0;
  }
  if(isset($form_data['roundOff'])) {
    $round_off = $form_data['roundOff'];
  } else {
    $round_off = 0;
  }
  if(isset($form_data['poNo'])) {
    $po_no = $form_data['poNo'];
  } else {
    $po_no = '';
  }
  if(isset($form_data['indentNo'])) {
    $indent_no = $form_data['indentNo'];
  } else {
    $indent_no = '';
  }
  if(isset($form_data['remarks'])) {
    $remarks = $form_data['remarks'];
  } else {
    $remarks = '';
  }  
?>
<div class="row">
  <div class="col-lg-12"> 
    <section class="panelBox">
      <div class="panelBody">
        <?php echo $utilities->print_flash_message() ?>
        <?php if($page_error !== ''): ?>
          <div class="alert alert-danger" role="alert">
            <strong>Error!</strong> <?php echo $page_error ?> 
          </div>
        <?php endif; ?>
        <div class="global-links actionButtons clearfix">
          <div class="pull-right text-right">
            <a href="/purchase/list" class="btn btn-default">
              <i class="fa fa-book"></i> Purchase Register
            </a>
          </div>
        </div>

        <form class="form-validate form-horizontal" method="POST">
          <div class="panel">
            <div class="panel-body">
              <h2 class="hdg-reports borderBottom">Transaction Details</h2>
              <div class="form-group">
                <div class="col-sm-12 col-md-4 col-lg-4">
                  <label class="control-label">Date of purchase (dd-mm-yyyy)</label>
                  <div class="form-group">
                    <div class="col-lg-12">
                      <div class="input-append date" data-date="<?php echo $purchase_date ?>" data-date-format="dd-mm-yyyy">
                        <input class="span2" value="<?php echo $purchase_date ?>" size="16" type="text" name="purchaseDate" id="purchaseDate" disabled />
                        <span class="add-on"><i class="fa fa-calendar"></i></span>
                      </div>                  
                    </div>
                  </div>
                </div>
                <div class="col-sm-12 col-md-4 col-lg-4">
                  <label class="control-label">Supplier name</label>
                  <div class="select-wrap">
                    <select class="form-control" name="supplierID" id="supplierID" disabled>
                      <?php 
                        foreach($suppliers as $key=>$value): 
                            if($supplierCode === $key) {
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
                  </div>             
                </div>
                <div class="col-sm-12 col-md-4 col-lg-4">
                  <label class="control-label">Indent number</label>
                  <input 
                    type="text" 
                    class="form-control noEnterKey" 
                    name="indentNo" 
                    id="indentNo" 
                    value="<?php echo $indent_no ?>"
                    disabled
                  >
                </div>           
              </div>
              <div class="form-group">
                <div class="col-sm-12 col-md-4 col-lg-4">
                  <label class="control-label">Purchaser order (PO) No.</label>
                  <input 
                    type="text" 
                    class="form-control noEnterKey" 
                    name="poNo" 
                    id="poNo" 
                    value="<?php echo $po_no ?>"
                    disabled
                  >            
                </div>
                <div class="col-sm-12 col-md-4 col-lg-4">
                  <label class="control-label">Payment method</label>
                  <div class="select-wrap">
                    <select class="form-control" name="paymentMethod" id="paymentMethod" disabled>
                      <?php 
                        foreach($payment_methods as $key=>$value): 
                          if((int)$paymentMethod === (int)$key) {
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
                  </div>
                </div>
                <div class="col-sm-12 col-md-4 col-lg-4">
                    <label class="control-label">Credit period (in days)</label>
                    <div class="select-wrap">
                      <select class="form-control" name="creditDays" id="creditDays" disabled>
                        <?php 
                          foreach($credit_days_a as $key=>$value):
                            if((int)$creditDays === $key) {
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
                    </div>
                  </div>
              </div>
              <div class="form-group">
                <div class="col-sm-12 col-md-4 col-lg-4">
                  <label class="control-label">Bill discount (in %)</label>
                  <input 
                    type="text"
                    class="form-control noEnterKey"
                    name="billDiscount"
                    id="billDiscount"
                    value="<?php //echo $bill_discount ?>"
                    maxlength="5"
                    disabled
                  >
                  <?php if(isset($form_errors['billDiscount'])): ?>
                      <span class="error"><?php echo $form_errors['billDiscount'] ?></span>
                  <?php endif; ?>         
                  <p class="blue" align="justify">To avoid calculation of manual discount per each item add percentage here. This will be applied against all items in this bill.</p>
                </div>
              </div>                
            </div>
          </div>
          <h2 class="hdg-reports">Item Details</h2>
          <?php if(isset($form_errors['itemDetailsError'])): ?>
            <span class="error"><?php echo $form_errors['itemDetailsError'] ?></span>
          <?php endif; ?>          
          <div class="table-responsive">
            <table class="table table-striped table-hover item-detail-table font11" id="purchaseTable">
              <thead>
                <tr>
                  <th style="width:260px;" class="text-center purItem">Item name</th>
                  <th style="width:50px;" class="text-center">Received<br />qty.</th>
                  <th style="width:50px" class="text-center">Free<br />qty.</th>
                  <th style="width:50px" class="text-center">Billed<br />qty.</th>                  
                  <th style="width:50px" class="text-center">Batch no.</th>
                  <th style="width:55px" class="text-center">ExpDate<br />(mm/yy)</th>
                  <th style="width:55px" class="text-center">MRP<br />(Rs.)</th>
                  <th style="width:55px" class="text-center">Rate / Unit<br />(Rs.)</th>
                  <th style="width:55px" class="text-center">Gross Amt.<br />( in Rs. )</th>
                  <th style="width:55px" class="text-center">Discount<br />( in Rs. )</th>                  
                  <th style="width:70px" class="text-center">Taxable Amt.<br />( in Rs. )</th>
                  <th style="width:100px" class="text-center">G.S.T<br />(in %)</th>
                </tr>
              </thead>
              <tbody>
              <?php
                $items_total =  $total_tax_amount = $items_tot_after_discount = $discount_amount = 0;
                $disabled = $is_grn_generated===true?'disabled':'';

                for($i=1;$i<=$total_item_rows;$i++):

                  if( isset($form_data['itemName'][$i-1]) && $form_data['itemName'][$i-1] !== '' ) {
                    $item_name = $form_data['itemName'][$i-1];
                  } else {
                    $item_name = '';
                  }
                  if( isset($form_data['inwardQty'][$i-1]) && $form_data['inwardQty'][$i-1] !== '' ) {
                    $inward_qty = $form_data['inwardQty'][$i-1];
                  } else {
                    $inward_qty = 0;
                  }
                  if( isset($form_data['freeQty'][$i-1]) && $form_data['freeQty'][$i-1] !== '' ) {
                    $free_qty = $form_data['freeQty'][$i-1];
                  } else {
                    $free_qty = 0;
                  }
                  if( isset($form_data['batchNo'][$i-1]) &&  $form_data['batchNo'][$i-1] !== '' ) {
                    $batch_no = $form_data['batchNo'][$i-1];
                  } else {
                    $batch_no = '';
                  }
                  if( isset($form_data['expDate'][$i-1]) && $form_data['expDate'][$i-1] !== '' ) {
                    $exp_date = $form_data['expDate'][$i-1];
                  } else {
                    $exp_date = '';
                  }
                  if( isset($form_data['expDate'][$i-1]) && $form_data['expDate'][$i-1] !== '' ) {
                    $mrp = $form_data['mrp'][$i-1];
                  } else {
                    $mrp = '';
                  }
                  if( isset($form_data['itemRate'][$i-1]) && $form_data['itemRate'][$i-1] !== '' ) {
                    $item_rate = $form_data['itemRate'][$i-1];
                  } else {
                    $item_rate = '';
                  }
                  if( isset($form_data['taxPercent'][$i-1]) && $form_data['taxPercent'][$i-1] !== '' ) {
                    $tax_percent = $form_data['taxPercent'][$i-1];
                  } else {
                    $tax_percent = 0;
                  }
                  if( isset($form_data['itemDiscount'][$i-1]) && $form_data['itemDiscount'][$i-1] !== '' ) {
                    $item_discount = $form_data['itemDiscount'][$i-1];
                  } else {
                    $item_discount = 0;
                  }                  

                  $billed_qty = $inward_qty-$free_qty;
                  $gross_amount = $billed_qty*$item_rate;
                  $item_amount = $gross_amount-$item_discount;

                  $tax_amount = $item_amount*$tax_percent/100;

                  $items_total += $item_amount;
                  $total_tax_amount += $tax_amount;
                  $discount_amount += $item_discount;
              ?>
                <tr class="purchaseItemRow">
                  <td style="width:260px;">
                    <input 
                      type="text" 
                      name="itemName[]" 
                      class="form-control inameAc noEnterKey purItem"
                      style="font-size:12px;"
                      id="itemName_<?php echo $i ?>"
                      value="<?php echo $item_name ?>"
                      disabled
                    />
                    <?php if( isset($form_errors['itemDetails'][$i-1]['itemName']) ) :?>
                      <span class="error">Invalid</span>
                    <?php endif; ?>
                  </td>
                  <td style="width:50px;">
                    <input
                      type="text"
                      class="form-control inwRcvdQty noEnterKey"
                      name="inwardQty[]"
                      placeholder="Rcvd."
                      style="width:60px;font-size:12px;"
                      id="inwRcvdQty_<?php echo $i ?>"
                      value="<?php echo $inward_qty ?>"                      
                      disabled
                    />
                    <?php if( isset($form_errors['itemDetails'][$i-1]['inwardQty']) ) :?>
                      <span class="error">Invalid</span>
                    <?php endif; ?>                    
                  </td>
                  <td style="width:50px;">
                    <input
                      type="text"
                      class="form-control inwFreeQty noEnterKey" 
                      name="freeQty[]" 
                      placeholder="Free"
                      style="width:60px;font-size:12px;"
                      id="inwFreeQty_<?php echo $i ?>"
                      value="<?php echo $free_qty ?>"                    
                      disabled
                    />
                    <?php if( isset($form_errors['itemDetails'][$i-1]['freeQty']) ) :?>
                      <span class="error">Invalid</span>
                    <?php endif; ?>                     
                  </td>
                  <td style="width:55px;">
                    <input 
                      type="text"
                      id="inwBillQty_<?php echo $i ?>"
                      class="form-control inwBillQty noEnterKey"
                      value="<?php echo $billed_qty ?>"
                      style="font-size:12px;"
                      disabled
                    />
                    <?php if( isset($form_errors['itemDetails'][$i-1]['inwBillQty']) ) :?>
                      <span class="error">Invalid</span>
                    <?php endif; ?>                    
                  </td>
                  <td style="width:50px;">
                    <input 
                      type="text"
                      name="batchNo[]"
                      placeholder="Batch no."
                      class="form-control noEnterKey"
                      style="width:70px;font-size:12px;"
                      id="batchNo_<?php echo $i ?>"
                      value="<?php echo substr($batch_no,8) ?>"
                      maxlength="12"         
                      disabled
                    />
                    <?php if( isset($form_errors['itemDetails'][$i-1]['batchNo']) ) :?>
                      <span class="error">Invalid</span>
                    <?php endif; ?>                     
                  </td>
                  <td style="width:55px;">
                    <input 
                      type="text" 
                      name="expDate[]"
                      class="form-control puExpDate noEnterKey"
                      placeholder="Expiry"
                      style="width:60px;font-size:12px;"
                      id="expDate_<?php echo $i ?>"
                      value="<?php echo $exp_date ?>"                      
                      disabled
                    />
                    <?php if(isset($form_errors['itemDetails'][$i-1]['expDate'])) :?>
                      <span class="error">Invalid</span>
                    <?php endif; ?>
                  </td>
                  <td style="width:55px;">
                    <input 
                      type="text" 
                      name="mrp[]"
                      placeholder="M.R.P"
                      class="form-control noEnterKey"
                      style="width:60px;font-size:12px;"
                      id="mrp_<?php echo $i ?>"
                      value="<?php echo $mrp ?>"                      
                      disabled
                    />
                    <?php if( isset($form_errors['itemDetails'][$i-1]['mrp']) ) :?>
                      <span class="error">Invalid</span>
                    <?php endif; ?>               
                  </td>
                  <td style="width:55px;">
                    <input 
                      type="text" 
                      name="itemRate[]"
                      id="inwItemRate_<?php echo $i ?>" 
                      class="form-control inwItemRate noEnterKey"
                      placeholder="Rate/Unit"
                      style="width:80px;font-size:12px;"
                      value="<?php echo $item_rate ?>"                      
                      disabled
                    />
                    <?php if( isset($form_errors['itemDetails'][$i-1]['itemRate']) ) :?>
                      <span class="error">Invalid</span>
                    <?php endif; ?>                     
                  </td>
                  <td style="width:80px;">
                    <input
                      type="text"
                      id="inwItemGrossAmount_<?php echo $i ?>"
                      class="form-control inwItemGrossAmount"
                      placeholder="Gross Amount"
                      style="width:70px;font-size:12px;text-align:right;"
                      disabled
                      value="<?php echo round($gross_amount,2) ?>"
                    />
                  </td>
                  <td style="width:80px;">
                    <input 
                      type="text" 
                      name="itemDiscount[]"
                      id="inwItemDiscount_<?php echo $i ?>" 
                      class="form-control inwItemDiscount noEnterKey"
                      placeholder="Discount"
                      style="font-size:12px;"
                      value="<?php echo $item_discount ?>"                      
                      disabled
                    />
                    <?php if( isset($form_errors['itemDetails'][$i-1]['itemDiscount']) ) :?>
                      <span class="error">Invalid</span>
                    <?php endif; ?>
                  </td>
                  <td style="width:70px;" align="right">
                    <input
                      type="text"
                      name="amount[]"
                      id="inwItemAmount_<?php echo $i ?>"
                      class="form-control inwItemAmount"
                      placeholder="Amount"
                      style="width:70px;font-size:12px;text-align:right;"
                      disabled
                      value="<?php echo round($item_amount,2) ?>"
                    />
                  </td>
                  <td style="width:80px;">
                    <div class="select-wrap">                        
                      <select 
                        class="form-control inwItemTax" 
                        id="inwItemTax_<?php echo $i ?>" 
                        name="taxPercent[]"
                        style="font-size:12px;"
                        disabled
                      >
                        <?php 
                          foreach($taxes as $key=>$value):
                            if((float)$value === (float)$tax_percent) {
                              $selected = 'selected="selected"';
                              if( isset(${"taxAmount_".$key}) ) {
                                ${"taxAmount_".$key} += $tax_amount;
                              } else {
                                ${"taxAmount_".$key} = $tax_amount;                                
                              }
                            } else {
                              $selected = '';
                            }
                        ?>
                          <option value="<?php echo (float)$value ?>" <?php echo $selected ?>>
                            <?php echo $value ?>
                          </option>
                        <?php endforeach; ?>                            
                      </select>
                      <?php if( isset($form_errors['itemDetails'][$i-1]['taxPercent']) ) :?>
                      <span class="error">Invalid</span>
                      <?php endif; ?>                       
                    </div>
                  </td>
                  <input 
                    type="hidden" 
                    id="inwItemTaxAmt_<?php echo $i ?>"
                    data-rate="<?php echo (int)$tax_percent ?>"
                    value="<?php echo $tax_amount ?>"
                    class="inwItemTaxAmount"
                  />
                </tr>
              <?php 
                endfor;
                $grand_total = $items_total+$total_tax_amount+$other_taxes+$shipping_charges;
                $net_pay = $grand_total+$adjustment;
                $round_off = round(round($net_pay)-round($net_pay,2),2);
                $net_pay = round($net_pay,0);
              ?>
                <input type = "hidden" id="inwDiscountPercent" name="discountPercent" value="0" />
                <tr>
                  <td colspan="11" align="right" style="vertical-align:middle;font-weight:bold;font-size:14px;">Total taxable amount</td>
                  <td id="inwItemsTotal" align="right" style="vertical-align:middle;font-weight:bold;font-size:14px;"><?php echo round($items_total-$discount_amount, 2) ?></td>
                </tr>
                <?php if( is_array($taxes_raw) && count($taxes_raw)>0 ): ?>
                  <?php
                    foreach($taxes_raw as $tax_details):
                      $tax_label = $tax_details['taxLabel'];
                      $tax_percent = (float)$tax_details['taxPercent'];
                      $tax_code = $tax_details['taxCode'];
                      if( isset(${"taxAmount_".$tax_code}) ) {
                        $tax_amount = ${"taxAmount_".$tax_code};
                      } else {
                        $tax_amount = 0;
                      }
                  ?>
                    <tr>
                      <td colspan="11" align="right" style="vertical-align:middle;font-weight:bold;font-size:14px;">(+) <?php echo $tax_label ?></td>
                      <td align="right" id="taxAmount_<?php echo $tax_code ?>" class="taxAmounts" style="vertical-align:middle;font-weight:bold;font-size:14px;"><?php echo round($tax_amount,2) ?></td>
                    </tr>
                    <input type="hidden" value="<?php echo $tax_percent ?>" id="<?php echo $tax_code ?>" class="taxPercents" />
                  <?php endforeach; ?>
                <?php endif;?>
                <tr>
                  <td style="vertical-align:middle;font-size:14px;font-weight:bold;" colspan="11" align="right">(+) Other taxes</td>
                  <td style="vertical-align:middle;text-align:right;font-size:14px;font-weight:bold;">
                    <input
                      type = "text"
                      class="form-control noEnterKey"
                      maxlength="10"
                      id="inwAddlTaxes"
                      name="otherTaxes"
                      value="<?php echo round($other_taxes,2) ?>"
                      style="text-align:right;"
                      disabled
                    >
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align:middle;font-weight:bold;font-size:14px;" colspan="11" align="right">(+) Freight / Shipping charges</td>
                  <td style="vertical-align:middle;text-align:right;">
                    <input
                      type = "text"
                      class="form-control noEnterKey"
                      maxlength="10"
                      id="inwShippingCharges"
                      name="shippingCharges"
                      value="<?php echo round($shipping_charges,2) ?>"
                      style="text-align:right;"
                      disabled
                    >
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align:middle;font-weight:bold;font-size:14px;" colspan="11" align="right">Grand total</td>
                  <td style="vertical-align:middle;text-align:right;font-size:14px;font-weight:bold;" id="inwTotalAmount"><?php echo round($grand_total,2) ?></td>
                </tr>
                <tr>
                  <td style="vertical-align:middle;font-weight:bold;font-size:14px;" colspan="11" align="right">(+/-) Adjustments</td>
                  <td style="vertical-align:middle;text-align:right;font-size:14px;">
                    <input
                      type = "text"
                      class="form-control noEnterKey"
                      maxlength="10"
                      id="inwAdjustment"
                      name="adjustment"
                      value="<?php echo round($adjustment, 2) ?>"
                      style="text-align:right;"
                      disabled
                    >
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align:middle;font-weight:bold;font-size:14px;" colspan="11" align="right">Round off</td>
                  <td style="vertical-align:middle;text-align:right;font-size:14px;font-weight:bold;" id="roundOff"><?php echo $round_off ?></td>
                </tr>
                <tr>
                  <td style="vertical-align:middle;font-weight:bold;font-size:14px;font-weight:bold;" colspan="11" align="right">Total amount</td>
                  <td style="vertical-align:middle;text-align:right;font-size:18px;font-weight:bold;" id="inwNetPay"><?php echo round($net_pay,2) ?></td>
                </tr>
                <tr>
                  <td style="vertical-align:middle;font-weight:bold;font-size:14px" align="center">Notes / Comments</td>
                  <td style="vertical-align:middle;text-align:right;font-size:14px" colspan="11">
                    <textarea
                      class="form-control noEnterKey"
                      rows="3"
                      cols="100"
                      id="inwRemarks"
                      name="remarks"
                    ><?php echo $remarks ?></textarea>
                  </td>
                </tr>                 
              </tbody>
            </table>
          </div>          
        </form>

      </div>
    </section>
  </div>
</div>