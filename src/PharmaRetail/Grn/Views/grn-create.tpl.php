<?php

  // dump($form_data, $form_errors);
  // dump($form_errors);
  // dump($form_data);

  if(isset($form_data['grnDate'])) {
    $grn_date = date("d-m-Y", strtotime($form_data['grnDate']));
  } else {
    $grn_date = date("d-m-Y");
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
  if(isset($form_data['billNo'])) {
    $bill_no = $form_data['billNo'];
  } else {
    $bill_no = '';
  }
  if(isset($form_data['remarks'])) {
    $remarks = $form_data['remarks'];
  } else {
    $remarks = '';
  }
  if(isset($form_data['billAmount'])) {
    $bill_amount = $form_data['billAmount'];
  } else {
    $bill_amount = 0;
  }
  if(isset($form_data['discountAmount'])) {
    $discount_amount = $form_data['discountAmount'];
  } else {
    $discount_amount = 0;
  }
  if(isset($form_data['taxAmount'])) {
    $tax_amount = $form_data['taxAmount'];
  } else {
    $tax_amount = 0;
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
  if(isset($form_data['remarks'])) {
    $remarks = $form_data['remarks'];
  } else {
    $remarks = '';
  }
  if(isset($form_data['roundOff'])) {
    $round_off = $form_data['roundOff'];
  } else {
    $round_off = 0;
  }
  if(isset($form_data['netPay'])) {
    $net_pay = $form_data['netPay'];
  } else {
    $net_pay = 0;
  }

  $items_tot_after_discount = $bill_amount-$discount_amount;
  $grand_total = $items_tot_after_discount+$tax_amount+
                 $other_taxes+$shipping_charges;

  $bill_value = $grand_total+$adjustment;
  $round_off = round(round($bill_value)-round($bill_value,2),2);
  $net_pay = round($bill_value,0);
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
            <a href="/grn/list" class="btn btn-default">
              <i class="fa fa-book"></i> GRN Register
            </a>
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
                <div class="col-sm-12 col-md-4 col-lg-4 m-bot15">
                  <label class="control-label">GRN Date (dd-mm-yyyy)</label>
                  <div class="form-group">
                    <div class="col-lg-12">
                      <div class="input-append date" data-date="<?php echo $grn_date ?>" data-date-format="dd-mm-yyyy">
                        <input class="span2" value="<?php echo $grn_date ?>" size="16" type="text" readonly name="grnDate" id="grnDate" />
                        <span class="add-on"><i class="fa fa-calendar"></i></span>
                      </div>
                      <?php if(isset($form_errors['grnDate'])): ?>
                        <span class="error"><?php echo $form_errors['grnDate'] ?></span>
                      <?php endif; ?>                  
                    </div>
                  </div>
                </div>
                <div class="col-sm-12 col-md-4 col-lg-4 m-bot15">
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
                  <?php if(isset($form_errors['supplierID'])): ?>
                    <span class="error"><?php echo $form_errors['supplierID'] ?></span>
                  <?php endif; ?>              
                </div>
                <div class="col-sm-12 col-md-4 col-lg-4 m-bot15">
                  <label class="control-label">Supplier bill number</label>
                  <input 
                    type="text" 
                    class="form-control noEnterKey" 
                    name="billNo" 
                    id="billNo" 
                    value="<?php echo $bill_no ?>"
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
                    disabled
                  >
                  <?php if(isset($form_errors['poNo'])): ?>
                      <span class="error"><?php echo $form_errors['poNo'] ?></span>
                  <?php endif; ?>              
                </div>
                <div class="col-sm-12 col-md-4 col-lg-4 m-bot15">
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
                    <?php if(isset($form_errors['paymentMethod'])): ?>
                      <span class="error"><?php echo $form_errors['paymentMethod'] ?></span>
                    <?php endif; ?>
                  </div>
                </div>
                <div class="col-sm-12 col-md-4 col-lg-4 m-bot15">
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
                  <th style="width:260px;" class="text-center purItem">Item name</th>
                  <th style="width:50px;"  class="text-center">Available<br />qty.</th>
                  <th style="width:50px"   class="text-center">Accepted<br />qty.</th>
                  <th style="width:50px"   class="text-center">Batch no.</th>
                  <th style="width:50px"   class="text-center">ExpDate<br />(mm/yy)</th>
                  <th style="width:50px"   class="text-center">MRP<br />(Rs.)</th>
                  <th style="width:50px"   class="text-center">Rate / Unit<br />(Rs.)</th>
                  <th style="width:50px"   class="text-center">Gross amt.<br />(Rs.)</th>
                  <th style="width:50px"   class="text-center">Discount<br />(Rs.)</th>
                  <th style="width:50px"   class="text-center">Taxable amt.<br />(Rs.)</th>
                  <th style="width:50px"   class="text-center">G.S.T<br />(in %)</th>
                </tr>
              </thead>
              <tbody>
              <?php
                for($i=1;$i<=$total_item_rows;$i++):
                  if( isset($form_data['itemCode'][$i-1]) && $form_data['itemCode'][$i-1] !== '' ) {
                    $item_code = $form_data['itemCode'][$i-1];
                  } else {
                    $item_code = '';
                  }                  
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
                    $mrp = 0;
                  }
                  if( isset($form_data['itemRate'][$i-1]) && $form_data['itemRate'][$i-1] !== '' ) {
                    $item_rate = $form_data['itemRate'][$i-1];
                  } else {
                    $item_rate = 0;
                  }                  
                  if( isset($form_data['taxPercent'][$i-1]) && $form_data['taxPercent'][$i-1] !== '' ) {
                    $tax_percent = $form_data['taxPercent'][$i-1];
                  } else {
                    $tax_percent = 0;
                  }
                  if( isset($form_data['discounts'][$i-1]) && $form_data['discounts'][$i-1] !== '' ) {
                    $discount = $form_data['discounts'][$i-1];
                  } else {
                    $discount = 0;
                  }                  

                  $gross_amount = round( ($inward_qty-$free_qty)*$item_rate, 2);
                  $taxable_amount = $gross_amount-$discount;

                  $item_key = $item_code.'__'.$batch_no;
              ?>
                <tr class="purchaseItemRow">
                  <td style="vertical-align:middle;"><?php echo $item_name ?></td>
                  <td style="vertical-align:middle;text-align:right;"><?php echo $inward_qty ?></td>
                  <td style="vertical-align:middle;width:70px;" align="center">
                    <input
                      type="text"
                      class="form-control inwFreeQty noEnterKey"
                      name="acceptedQty[<?php echo $item_key ?>]"
                      placeholder="Accepted Qty."
                      style="text-align:right;width:70px;background-color:#f1f442;border:1px solid #000;font-weight:bold;"
                      id="acceptedQty_<?php echo $i ?>"
                      value="<?php echo $inward_qty ?>"
                      readonly
                    />
                    <?php if( isset($form_errors['itemDetails'][$i-1]['acceptedQty']) ) :?>
                      <span class="error">Invalid</span>
                    <?php endif; ?>                     
                  </td>
                  <td style="vertical-align:middle;text-align:right;"><?php echo $batch_no ?></td>
                  <td style="vertical-align:middle;text-align:right;"><?php echo $exp_date ?></td>
                  <td style="vertical-align:middle;text-align:right;"><?php echo number_format($mrp,2) ?></td>
                  <td style="vertical-align:middle;text-align:right;font-weight:bold;"><?php echo number_format($item_rate,2) ?></td>
                  <td style="vertical-align:middle;text-align:right;font-weight:bold;"><?php echo number_format($gross_amount,2) ?></td>       
                  <td style="vertical-align:middle;text-align:right;font-weight:bold;"><?php echo number_format($discount,2) ?></td>
                  <td style="vertical-align:middle;text-align:right;"><?php echo number_format($taxable_amount,2) ?></td>
                  <td style="vertical-align:middle;text-align:right;"><?php echo number_format($tax_percent,2).'%' ?></td>
                </tr>
              <?php endfor; ?>
              <?php /*
                <tr>
                  <td colspan="10" style="vertical-align:middle;text-align:right;font-size:14px;font-weight:bold;">Items total</td>
                  <td id="inwItemsTotal" style="vertical-align:middle;text-align:right;font-size:14px;font-weight:bold;"><?php echo number_format($bill_amount, 2) ?></td>
                </tr>
                <tr>
                  <td style="vertical-align:middle;" colspan="10" align="right">(-) Discount</td>
                  <td style="vertical-align:middle;text-align:right;"><?php echo number_format($discount_amount,2) ?></td>
                </tr>*/?>
                <tr>
                  <td style="vertical-align:middle;font-weight:bold;" colspan="10" align="right">Taxable Value</td>
                  <td style="vertical-align:middle;text-align:right;"><?php echo number_format($items_tot_after_discount, 2) ?></td>
                </tr>
                <tr>
                  <td style="vertical-align:middle;" colspan="10" align="right">G.S.T</td>
                  <td style="vertical-align:middle;text-align:right;"><?php echo number_format($tax_amount, 2) ?></td>
                </tr>
                <?php /*
                <tr>
                  <td style="vertical-align:middle;" colspan="10" align="right">Other taxes</td>
                  <td style="vertical-align:middle;text-align:right;"><?php echo number_format($other_taxes, 2) ?></td>
                </tr>
                <tr>
                  <td style="vertical-align:middle;" colspan="10" align="right">Shipping charges</td>
                  <td style="vertical-align:middle;text-align:right;"><?php echo number_format($shipping_charges, 2) ?></td>
                </tr>
                <tr>
                  <td style="vertical-align:middle;" colspan="10" align="right">(+/-) Adjustment</td>
                  <td style="vertical-align:middle;text-align:right;"><?php echo number_format($adjustment, 2) ?></td>
                </tr>
                <tr>
                  <td style="vertical-align:middle;font-weight:bold;" colspan="10" align="right">Bill value</td>
                  <td style="vertical-align:middle;text-align:right;"><?php echo number_format($bill_value, 2) ?></td>
                </tr> */?>
                <tr>
                  <td style="vertical-align:middle;font-weight:bold;" colspan="10" align="right">Round off</td>
                  <td style="vertical-align:middle;text-align:right;"><?php echo number_format($round_off, 2) ?></td>
                </tr>
                <tr>
                  <td style="vertical-align:middle;font-weight:bold;" colspan="10" align="right">Net pay</td>
                  <td style="vertical-align:middle;text-align:right;font-weight:bold;font-size:18px;"><?php echo number_format($net_pay, 2) ?></td>
                </tr>                
              </tbody>
            </table>
          </div>

          <div class="text-center">
            <button class="btn btn-danger" id="Cancel" onclick="window.location.href = '/purchase/list';return false;">
              <i class="fa fa-times"></i> Cancel
            </button>
            <button class="btn btn-primary" id="Save">
              <i class="fa fa-save"></i> Save
            </button>
          </div>
          
        </form>

      </div>
    </section>
  </div>
</div>