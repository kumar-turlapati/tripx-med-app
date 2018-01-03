<?php

  // dump($form_data, $form_errors);
  // dump($form_errors);
  // dump($form_data);

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
        <?php if($api_error !== ''): ?>
          <div class="alert alert-danger" role="alert">
            <strong>Error!</strong> <?php echo $api_error ?> 
          </div>
        <?php endif; ?>
        <div class="global-links actionButtons clearfix">
          <div class="pull-right text-right">
            <a href="/purchase/list" class="btn btn-default">
              <i class="fa fa-book"></i> Purchase Register
            </a>
          </div>
        </div>

        <form class="form-validate form-horizontal" method="POST" id="inwardEntryForm" autocomplete="off">
          <div class="panel">
            <div class="panel-body">
              <h2 class="hdg-reports borderBottom">Transaction Details</h2>
              <div class="form-group">
                <div class="col-sm-12 col-md-4 col-lg-4 m-bot15">
                  <label class="control-label">Date of purchase (dd-mm-yyyy)</label>
                  <div class="form-group">
                    <div class="col-lg-12">
                      <div class="input-append date" data-date="<?php echo $purchase_date ?>" data-date-format="dd-mm-yyyy">
                        <input class="span2" value="<?php echo $purchase_date ?>" size="16" type="text" readonly name="purchaseDate" id="purchaseDate" />
                        <span class="add-on"><i class="fa fa-calendar"></i></span>
                      </div>
                      <?php if(isset($form_errors['purchaseDate'])): ?>
                        <span class="error"><?php echo $form_errors['purchaseDate'] ?></span>
                      <?php endif; ?>                  
                    </div>
                  </div>
                </div>
                <div class="col-sm-12 col-md-4 col-lg-4 m-bot15">
                  <label class="control-label">Supplier name</label>
                  <div class="select-wrap">
                    <select class="form-control" name="supplierID" id="supplierID">
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
                  <?php if(isset($form_errors['supplierID'])): ?>
                    <span class="error"><?php echo $form_errors['supplierID'] ?></span>
                  <?php endif; ?>              
                </div>
                <div class="col-sm-12 col-md-4 col-lg-4 m-bot15">
                  <label class="control-label">Indent number</label>
                  <input 
                    type="text" 
                    class="form-control noEnterKey" 
                    name="indentNo" 
                    id="indentNo" 
                    value="<?php echo $indent_no ?>"
                  >
                  <?php if(isset($form_errors['billNo'])): ?>
                    <span class="error"><?php echo $form_errors['billNo'] ?></span>
                  <?php endif; ?>
                </div>           
              </div>
              <div class="form-group">
                <div class="col-sm-12 col-md-4 col-lg-4 m-bot15">
                  <label class="control-label">Purchaser order (PO) No.</label>
                  <input 
                    type="text" 
                    class="form-control noEnterKey" 
                    name="poNo" 
                    id="poNo" 
                    value="<?php echo $po_no ?>"
                  >
                  <?php if(isset($form_errors['poNo'])): ?>
                      <span class="error"><?php echo $form_errors['poNo'] ?></span>
                  <?php endif; ?>              
                </div>
                <div class="col-sm-12 col-md-4 col-lg-4 m-bot15">
                  <label class="control-label">Payment method</label>
                  <div class="select-wrap">
                    <select class="form-control" name="paymentMethod" id="paymentMethod">
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
                    <?php if(isset($form_errors['paymentMethod'])): ?>
                      <span class="error"><?php echo $form_errors['paymentMethod'] ?></span>
                    <?php endif; ?>
                  </div>
                </div>
                <div class="col-sm-12 col-md-4 col-lg-4 m-bot15">
                    <label class="control-label">Credit period (in days)</label>
                    <div class="select-wrap">
                      <select class="form-control" name="creditDays" id="creditDays">
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
                      <?php if(isset($form_errors['creditDays'])): ?>
                        <span class="error"><?php echo $form_errors['creditDays'] ?></span>
                      <?php endif; ?>
                    </div>
                  </div>
              </div>
            </div>
          </div>
          <h2 class="hdg-reports">Item Details</h2>
          <?php if(isset($form_errors['itemDetailsError'])): ?>
            <span class="error"><?php echo $form_errors['itemDetailsError'] ?></span>
          <?php endif; ?>          
          <div class="table-responsive">
            <table class="table table-striped table-hover item-detail-table font12" id="purchaseTable">
              <thead>
                <tr>
                  <th style="width:230px;" class="text-center purItem">Item name</th>
                  <th style="width:60px;"  class="text-center">Received<br />qty.</th>
                  <th style="width:60px"   class="text-center">Free<br />qty.</th>
                  <th style="width:60px"   class="text-center">Billed<br />qty.</th>                  
                  <th style="width:60px"   class="text-center">Batch no.</th>
                  <th style="width:55px"   class="text-center">ExpDate<br />(mm/yy)</th>
                  <th style="width:55px"   class="text-center">MRP<br />(Rs.)</th>
                  <th style="width:55px"   class="text-center">Rate / Unit<br />(Rs.)</th>
                  <th style="width:100px"  class="text-center">G.S.T<br />(in %)</th>
                  <th style="width:110px"  class="text-center">Amount<br />(Rs.)</th>
                </tr>
              </thead>
              <tbody>
              <?php
                $items_total =  $total_tax_amount = $items_tot_after_discount = 0;

                for($i=1;$i<=$total_item_rows;$i++):

                  if( isset($form_data['itemName'][$i-1]) && $form_data['itemName'][$i-1] !== '' ) {
                    $item_name = $form_data['itemName'][$i-1];
                  } else {
                    $item_name = '';
                  }
                  if( isset($form_data['inwardQty'][$i-1]) && $form_data['inwardQty'][$i-1] !== '' ) {
                    $inward_qty = $form_data['inwardQty'][$i-1];
                  } else {
                    $inward_qty = '';
                  }
                  if( isset($form_data['freeQty'][$i-1]) && $form_data['freeQty'][$i-1] !== '' ) {
                    $free_qty = $form_data['freeQty'][$i-1];
                  } else {
                    $free_qty = '';
                  }
                  if( isset($form_data['batchNo'][$i-1]) &&  $form_data['batchNo'][$i-1] !== '' ) {
                    $batch_no = $form_data['batchNo'][$i-1];
                  } else {
                    $batch_no = date("dMy").'_'.$utilities::generate_unique_string(12);
                  }
                  if( isset($form_data['expDate'][$i-1]) && $form_data['expDate'][$i-1] !== '' ) {
                    $exp_date = $form_data['expDate'][$i-1];
                  } else {
                    $exp_date = '12/99';
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

                  $billed_qty = $inward_qty-$free_qty;
                  $item_amount = $billed_qty*$item_rate;
                  $items_total += $item_amount;
                  if($discount_percent>0) {
                    $tax_amount = ($item_amount-($item_amount*$discount_percent/100))*$tax_percent/100;
                  } else {
                    $tax_amount = $item_amount*$tax_percent/100;
                  }
                  $total_tax_amount += $tax_amount;
              ?>
                <tr class="purchaseItemRow">
                  <td style="width:300px;">
                    <input 
                      type="text" 
                      name="itemName[]" 
                      class="form-control inameAc noEnterKey purItem"
                      style="font-size:12px;"
                      id="itemName_<?php echo $i ?>"
                      value="<?php echo $item_name ?>"
                    />
                    <?php if( isset($form_errors['itemDetails'][$i-1]['itemName']) ) :?>
                      <span class="error">Invalid</span>
                    <?php endif; ?>
                  </td>
                  <td style="width:60px;">
                    <input
                      type="text"
                      class="form-control inwRcvdQty noEnterKey"
                      name="inwardQty[]"
                      placeholder="Rcvd."
                      style="width:60px;font-size:12px;"
                      id="inwRcvdQty_<?php echo $i ?>"
                      value="<?php echo $inward_qty ?>"                      
                    />
                    <?php if( isset($form_errors['itemDetails'][$i-1]['inwardQty']) ) :?>
                      <span class="error">Invalid</span>
                    <?php endif; ?>                    
                  </td>
                  <td style="width:60px;">
                    <input
                      type="text"
                      class="form-control inwFreeQty noEnterKey" 
                      name="freeQty[]" 
                      placeholder="Free"
                      style="width:60px;font-size:12px;"
                      id="inwFreeQty_<?php echo $i ?>"
                      value="<?php echo $free_qty ?>"                    
                    />
                    <?php if( isset($form_errors['itemDetails'][$i-1]['freeQty']) ) :?>
                      <span class="error">Invalid</span>
                    <?php endif; ?>                     
                  </td>
                  <td style="width:60px;">
                    <input 
                      type="text"
                      id="inwBillQty_<?php echo $i ?>"
                      class="form-control inwBillQty noEnterKey"
                      value="<?php echo $billed_qty ?>"
                      disabled
                    />
                    <?php if( isset($form_errors['itemDetails'][$i-1]['inwBillQty']) ) :?>
                      <span class="error">Invalid</span>
                    <?php endif; ?>                    
                  </td>
                  <td style="width:60px;">
                    <input 
                      type="text"
                      name="batchNo[]"
                      placeholder="Batch no."
                      class="form-control noEnterKey"
                      style="width:70px;font-size:12px;"
                      id="batchNo_<?php echo $i ?>"
                      value="<?php echo $batch_no ?>"
                      maxlength="12"                     
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
                    />
                    <?php if( 
                              isset($form_errors['itemDetails'][$i-1]['expDate']['m']) ||
                              isset($form_errors['itemDetails'][$i-1]['expDate']['y'])
                            ) :?>
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
                    />
                    <?php if( isset($form_errors['itemDetails'][$i-1]['itemRate']) ) :?>
                      <span class="error">Invalid</span>
                    <?php endif; ?>                     
                  </td>
                  <td style="width:100px;">
                    <div class="select-wrap">                        
                      <select 
                        class="form-control inwItemTax" 
                        id="inwItemTax_<?php echo $i ?>" 
                        name="taxPercent[]"
                        style="font-size:12px;"
                      >
                        <?php 
                          foreach($taxes as $key=>$value):
                            if((float)$value === (float)$tax_percent) {
                              $selected = 'selected="selected"';
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
                  <td style="width:110px;" align="right">
                    <input
                      type="text"
                      name="amount[]"
                      id="inwItemAmount_<?php echo $i ?>"
                      class="form-control inwItemAmount"
                      placeholder="Amount"
                      style="width:100px;font-size:12px;text-align:right;"
                      disabled
                      value="<?php echo round($item_amount,2) ?>"
                    />
                  </td>
                  <input 
                    type="hidden" 
                    id="inwItemTaxAmt_<?php echo $i ?>"
                    data-rate="<?php echo $tax_percent ?>"
                    value="<?php echo $tax_amount ?>"
                    class="inwItemTaxAmount"
                  />
                </tr>
              <?php 
                endfor;

                $items_tot_after_discount = $items_total-($items_total*$discount_percent)/100;
                $grand_total = $items_tot_after_discount+$total_tax_amount+
                               $other_taxes+$shipping_charges;

                $net_pay = $grand_total+$adjustment;
              ?>
                <tr>
                  <td colspan="9" align="right">Items total</td>
                  <td id="inwItemsTotal" align="right"><?php echo round($items_total, 2) ?></td>
                </tr>
                <tr>
                  <td style="vertical-align:middle;" colspan="8" align="right">(-) Discount (in %)</td>
                  <td style="vertical-align:middle;">
                    <input
                      type = "text"
                      class="form-control noEnterKey"
                      maxlength="5"
                      id="inwDiscountPercent"
                      name="discountPercent"
                      value="<?php echo round($discount_percent,2) ?>"
                    >
                  </td>
                  <td style="vertical-align:middle;text-align:right;" id="inwDiscountValue"></td>
                </tr>
                <tr>
                  <td style="vertical-align:middle;font-weight:bold;" colspan="9" align="right">Items total (after discount)</td>
                  <td style="vertical-align:middle;text-align:right;" id="inwItemValueFinal"><?php echo round($items_tot_after_discount, 2) ?></td>
                </tr>                
                <?php if( is_array($taxes_raw) && count($taxes_raw)>0 ): ?>
                  <?php
                    foreach($taxes_raw as $tax_details):
                      $tax_label = $tax_details['taxLabel'];
                      $tax_percent = (float)$tax_details['taxPercent'];
                      $tax_code = $tax_details['taxCode'];
                  ?>
                    <tr>
                      <td colspan="9" align="right">(+) <?php echo $tax_label ?></td>
                      <td align="right" id="taxAmount_<?php echo $tax_code ?>" class="taxAmounts">&nbsp;</td>
                    </tr>
                    <input type="hidden" value="<?php echo $tax_percent ?>" id="<?php echo $tax_code ?>" class="taxPercents" />
                  <?php endforeach; ?>
                <?php endif;?>
                <tr>
                  <td style="vertical-align:middle;" colspan="9" align="right">(+) Other taxes</td>
                  <td style="vertical-align:middle;text-align:right;">
                    <input
                      type = "text"
                      class="form-control noEnterKey"
                      maxlength="10"
                      id="inwAddlTaxes"
                      name="otherTaxes"
                      value="<?php echo round($other_taxes,2) ?>"
                      style="text-align:right;"
                    >
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align:middle;" colspan="9" align="right">(+) Freight / Shipping charges</td>
                  <td style="vertical-align:middle;text-align:right;">
                    <input
                      type = "text"
                      class="form-control noEnterKey"
                      maxlength="10"
                      id="inwShippingCharges"
                      name="shippingCharges"
                      value="<?php echo round($shipping_charges,2) ?>"
                      style="text-align:right;"
                    >
                  </td>
                </tr>                               
                <tr>
                  <td style="vertical-align:middle;font-weight:bold;" colspan="9" align="right">Grand total</td>
                  <td style="vertical-align:middle;text-align:right;" id="inwTotalAmount"><?php echo round($grand_total,2) ?></td>
                </tr>
                <tr>
                  <td style="vertical-align:middle;font-weight:bold;" colspan="9" align="right">(+/-) Adjustments</td>
                  <td style="vertical-align:middle;text-align:right;">
                    <input
                      type = "text"
                      class="form-control noEnterKey"
                      maxlength="10"
                      id="inwAdjustment"
                      name="adjustment"
                      value="<?php echo round($adjustment, 2) ?>"
                      style="text-align:right;"
                    >
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align:middle;font-weight:bold;" colspan="9" align="right">Round off</td>
                  <td style="vertical-align:middle;text-align:right;" id="roundOff">&nbsp;</td>
                </tr>                
                <tr>
                  <td style="vertical-align:middle;font-weight:bold;font-size:14px;" colspan="9" align="right">Total amount</td>
                  <td style="vertical-align:middle;text-align:right;font-size:14px;" id="inwNetPay"><?php echo round($net_pay,2) ?></td>
                </tr>
                <tr>
                  <td style="vertical-align:middle;font-weight:bold;" align="center">Notes / Comments</td>
                  <td style="vertical-align:middle;text-align:right;" colspan="10">
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

          <div class="text-center">
            <button class="btn btn-danger" id="inwCancel">
              <i class="fa fa-times"></i> Cancel
            </button>
            <button class="btn btn-primary" id="inwSave">
              <i class="fa fa-save"></i> Save
            </button>
          </div>
          
        </form>

      </div>
    </section>
  </div>
</div>