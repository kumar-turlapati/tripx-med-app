<?php 
  use Atawa\Utilities;

  if(isset($template_vars) && is_array($template_vars)) {
    extract($template_vars); 
  }
  if(isset($submitted_data['purchaseDate']) && $submitted_data['purchaseDate']!=='') {
    $current_date = date("d-m-Y", strtotime($submitted_data['purchaseDate']));
  } else {
    $current_date = date("d-m-Y");
  }
  if(isset($submitted_data['creditDays'])) {
    $creditDays = $submitted_data['creditDays'];
  } else {
    $creditDays = 0;
  }
  if(isset($submitted_data['supplierCode'])) {
    $supplierCode = $submitted_data['supplierCode'];
  } else {
    $supplierCode = '';
  }
  if(isset($submitted_data['paymentMethod'])) {
    $paymentMethod = $submitted_data['paymentMethod'];
  } else {
    $paymentMethod = 0;
  }

  if(isset($submitted_data['billAmount']) && $submitted_data['billAmount']>0) {
    $bill_amount = $submitted_data['billAmount'];
  } else {
    $bill_amount = '';
  }  

  if(isset($submitted_data['adjAmount']) && $submitted_data['adjAmount']>0) {
    $adj_amount = $submitted_data['adjAmount'];
  } else {
    $adj_amount = '';
  }

  if(isset($submitted_data['totalAmount']) && $submitted_data['totalAmount']>0) {
    $total_amount = $submitted_data['totalAmount'];
  } else {
    $total_amount = '';
  }

  if(isset($submitted_data['roundOff']) && $submitted_data['roundOff']<>0) {
    $round_off = $submitted_data['roundOff'];
  } else {
    $round_off = '';
  }

  if(isset($submitted_data['netPay']) && $submitted_data['netPay']>0) {
    $net_pay = $submitted_data['netPay'];
  } else {
    $net_pay = '';
  }

  if(isset($submitted_data['grnFlag']) && $submitted_data['grnFlag']==='yes') {
    $disable_flag = 'disabled';
  } else {
    $disable_flag = '';
  }
  // dump($submitted_data);
?>

<!-- Basic form starts -->
<div class="row">
  <div class="col-lg-12"> 
    
    <!-- Panel starts -->
    <section class="panelBox">
      <div class="panelBody">

        <?php echo Utilities::print_flash_message() ?>

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
            <a href="/purchase/list" class="btn btn-default">
              <i class="fa fa-book"></i> Purchase Register
            </a>
            <a href="/purchase/entry-new" class="btn btn-default">
              <i class="fa fa-file-text-o"></i> New Purchase 
            </a> 
          </div>
        </div>
        <!-- Right links ends --> 
        
        <!-- Form starts -->
        <form class="form-validate form-horizontal" method="POST">
          <div class="panel">
          <div class="panel-body">
          <h2 class="hdg-reports borderBottom">Transaction Details</h2>
          <div class="form-group">
            <div class="col-sm-12 col-md-4 col-lg-4 m-bot15">
              <label class="control-label">Date of purchase (dd-mm-yyyy)</label>
              <div class="form-group">
                <div class="col-lg-12">
                  <div class="input-append date" data-date="<?php echo $current_date ?>" data-date-format="dd-mm-yyyy" <?php echo $disable_flag ?>>
                    <input class="span2" value="<?php echo $current_date ?>" size="16" type="text" readonly name="purchaseDate" id="purchaseDate" />
                    <span class="add-on"><i class="fa fa-calendar"></i></span>
                  </div>
                  <?php if(isset($errors['purchaseDate'])): ?>
                    <span class="error"><?php echo $errors['purchaseDate'] ?></span>
                  <?php endif; ?>                  
                </div>
              </div>
            </div>
            <div class="col-sm-12 col-md-4 col-lg-4 m-bot15">
              <label class="control-label">Supplier name</label>
              <div class="select-wrap">
                <select class="form-control" name="supplierID" id="supplierID" <?php echo $disable_flag ?>>
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
              <?php if(isset($errors['supplierID'])): ?>
                <span class="error"><?php echo $errors['supplierID'] ?></span>
              <?php endif; ?>              
            </div>
            <div class="col-sm-12 col-md-4 col-lg-4 m-bot15">
              <label class="control-label">Indent number</label>
              <input 
                type="text" 
                class="form-control" 
                name="indentNo" 
                id="indentNo" 
                value="<?php echo (isset($submitted_data['indentNo'])?$submitted_data['indentNo']:'') ?>"
                <?php echo $disable_flag ?>                
              >
              <?php if(isset($errors['billNo'])): ?>
                <span class="error"><?php echo $errors['billNo'] ?></span>
              <?php endif; ?>
            </div>           
          </div>
          <div class="form-group">
              <div class="col-sm-12 col-md-4 col-lg-4 m-bot15">
                <label class="control-label">Purchaser order (PO) No.</label>
                <input 
                  type="text" 
                  class="form-control" 
                  name="poNo" 
                  id="poNo" 
                  value="<?php echo (isset($submitted_data['poNo'])?$submitted_data['poNo']:'') ?>"
                  <?php echo $disable_flag ?>                  
                >
                <?php if(isset($errors['poNo'])): ?>
                    <span class="error"><?php echo $errors['poNo'] ?></span>
                <?php endif; ?>              
              </div>
              <div class="col-sm-12 col-md-4 col-lg-4 m-bot15">
                <label class="control-label">Payment method</label>
                <div class="select-wrap">
                  <select class="form-control" name="paymentMethod" id="paymentMethod" <?php echo $disable_flag ?>>
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
                  <?php if(isset($errors['paymentMethod'])): ?>
                    <span class="error"><?php echo $errors['paymentMethod'] ?></span>
                  <?php endif; ?>
                </div>
              </div>
              <div class="col-sm-12 col-md-4 col-lg-4 m-bot15">
                <label class="control-label">Credit period (in days)</label>
                <div class="select-wrap">
                  <select class="form-control" name="creditDays" id="creditDays" <?php echo $disable_flag ?>>
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
                  <?php if(isset($errors['creditDays'])): ?>
                    <span class="error"><?php echo $errors['creditDays'] ?></span>
                  <?php endif; ?>
                </div>
              </div>
          </div>
          <?php /*
          <div class="form-group">          
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
          </div>*/ ?>
          </div>
          </div>
          <h2 class="hdg-reports">Item Details</h2>
          <?php if(isset($errors['itemDetails'])): ?>
            <span class="error"><?php echo $errors['itemDetails'] ?></span>
          <?php endif; ?>          
          <div class="table-responsive">

            <table class="table table-striped table-hover item-detail-table">
              <thead>
                <tr>
                  <th width="230px" class="text-center">Item Name</th>
                  <th width="100px" class="text-center">Received<br />Qty.</th>
                  <th width="100px" class="text-center">Free<br />Qty.</th>
                  <th width="80px" class="text-center">Billed<br />Qty.</th>                  
                  <th width="90px"  class="text-center">Batch No.</th>
                  <th width="90px" class="text-center">Expiry Date<br />(mm/yy)</th>
                  <th width="90px" class="text-center">M.R.P<br />(in Rs.)</th>
                  <th width="90px"  class="text-center">Rate/Unit<br />(in Rs.)</th>
                  <th width="100px" class="text-center">Tax<br />Percent</th>
                  <th width="110px" class="text-center">Amount<br />(in Rs.)</th>                  
                </tr>
              </thead>
              <tbody>
                <?php 
                  for($i=0;$i<=20;$i++):
                    if(isset($submitted_data['itemDetails'][$i])) {

                      $item_name = $submitted_data['itemDetails'][$i]['itemName'];
                      $inward_qty = $submitted_data['itemDetails'][$i]['itemQty'];
                      $free_qty = $submitted_data['itemDetails'][$i]['freeQty'];
                      $batch_no = $submitted_data['itemDetails'][$i]['batchNo'];
                      $exp_date = $submitted_data['itemDetails'][$i]['expdateMonth'].'/'.
                                  $submitted_data['itemDetails'][$i]['expdateYear'];
                      $mrp = $submitted_data['itemDetails'][$i]['mrp'];
                      $item_rate = $submitted_data['itemDetails'][$i]['itemRate'];
                      $vat_percent = $submitted_data['itemDetails'][$i]['vatPercent'];
                      if((int)$submitted_data['itemDetails'][$i]['expdateMonth']<10) {
                        $exp_date = '0'.$exp_date;
                      }
                      $bill_qty = $inward_qty-$free_qty;
                      $item_amount = ($bill_qty*$item_rate);
                      $tax_amount = ($item_amount*$vat_percent)/100;
                      $item_amount1 = number_format($item_amount+$tax_amount,2);

                    } elseif( isset($submitted_data['itemDetails']['itemName'][$i]) ) {

                      $item_name = $submitted_data['itemDetails']['itemName'][$i];
                      $inward_qty = $submitted_data['itemDetails']['inwardQty'][$i];
                      $free_qty = $submitted_data['itemDetails']['freeQty'][$i];
                      $batch_no = $submitted_data['itemDetails']['batchNo'][$i];
                      $exp_date = $submitted_data['itemDetails']['expDate'][$i];
                      $mrp = $submitted_data['itemDetails']['mrp'][$i];
                      $item_rate = $submitted_data['itemDetails']['itemRate'][$i];
                      $vat_percent = $submitted_data['itemDetails']['taxPercent'][$i];

                      $bill_qty = $inward_qty-$free_qty;
                      $item_amount = ($bill_qty*$item_rate);
                      $tax_amount = ($item_amount*$vat_percent)/100;
                      $item_amount1 = number_format($item_amount+$tax_amount,2);

                    } else {

                      $item_name = '';
                      $inward_qty = '';
                      $free_qty = '';
                      $batch_no = '';
                      $exp_date = '';
                      $mrp = '';
                      $item_rate = '';
                      $vat_percent = '';
                      $bill_qty = '';
                      $item_amount1 = '';
                    }
                ?>
                    <tr>
                      <td>
                        <input 
                          type="text" 
                          name="itemDetails[itemName][]" 
                          value="<?php echo $item_name ?>" 
                          class="inameAc noEnterKey"
                          <?php echo $disable_flag ?>
                        />       
                      </td>
                      <td>
                        <div class="select-wrap">
                          <select 
                            class="form-control puRcvdQty" 
                            name="itemDetails[inwardQty][]" 
                            id="puRcvdQty_<?php echo $i ?>"
                            <?php echo $disable_flag ?>                            
                          >
                            <?php 
                              foreach($qtys_a as $key=>$value):
                                if((int)$value === (int)$inward_qty) {
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
                      </td>
                      <td>
                        <div class="select-wrap">
                          <select 
                            class="form-control puFreeQty" 
                            name="itemDetails[freeQty][]" 
                            id="puFreeQty_<?php echo $i ?>"
                            <?php echo $disable_flag ?>                            
                          >
                            <?php 
                              foreach($qtys_a as $key=>$value):
                                if((int)$value === (int)$free_qty) {
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
                      </td>
                      <td id="puBillQty_<?php echo $i ?>" align="right" style="font-weight:bold;"><?php echo $bill_qty ?></td>
                      <td>
                        <input type="text" name="itemDetails[batchNo][]" value="<?php echo $batch_no ?>" <?php echo $disable_flag ?>/>
                      </td>
                      <td>
                        <input type="text" name="itemDetails[expDate][]" value="<?php echo $exp_date ?>" class="puExpDate" <?php echo $disable_flag ?> />
                      </td>
                      <td>
                        <input type="text" name="itemDetails[mrp][]" value="<?php echo $mrp ?>" <?php echo $disable_flag ?> />
                      </td>
                      <td>
                        <input type="text" id="puItemRate_<?php echo $i ?>" class="puItemRate" name="itemDetails[itemRate][]" value="<?php echo $item_rate ?>"  <?php echo $disable_flag ?> />
                      </td>
                      <td>
                        <div class="select-wrap">                        
                          <select class="form-control puItemTax" id="puItemTax_<?php echo $i ?>" name="itemDetails[taxPercent][]" <?php echo $disable_flag ?>>
                            <?php 
                              foreach($tax_a as $key=>$value):
                                if((int)$value === (int)$vat_percent) {
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
                      </td>
                      <td id="puItemAmount_<?php echo $i ?>" align="right" class="puItemAmount">
                        <?php echo $item_amount1 ?>
                      </td>
                    </tr>
                <?php endfor; ?>
                    <tr>
                      <td colspan="9" align="right"><b>Item Totals</b></td>
                      <td class="grandTotal" id="grandTotal" align="right"><?php echo $bill_amount ?></td>
                    </tr>
                    <tr>
                      <td colspan="9" align="right"><b>Adjustments (-)</b></td>
                      <td class="adjContainer" id="adjContainer" align="right">
                        <input 
                          type="text" 
                          class="form-control noEnterKey text-right" 
                          name="adjAmount" 
                          id="adjAmount" 
                          value="<?php echo $adj_amount ?>"
                          <?php echo $disable_flag ?>                          
                        />
                      </td>
                    </tr>                    
                    <tr>
                      <td colspan="9" align="right"><b>Round Off (+/-)</b></td>
                      <td class="roundOff" id="roundOff" align="right"><?php echo $round_off ?></td>
                    </tr>
                    <tr>
                      <td colspan="9" align="right"><b>Total Amount</b></td>
                      <td class="netPay" id="netPay" align="right"><?php echo $net_pay ?></td>
                    </tr>                                         
              </tbody>
            </table>
          </div>          
          <div class="text-center">
            <button class="btn btn-primary" id="Save" <?php echo $disable_flag ?>>
              <i class="fa fa-save"></i> <?php echo $btn_label ?>
            </button>
            <!--button class="btn btn-primary" id="Save&Print">
              <i class="fa fa-print"></i> Save &amp; Print
            </button-->            
          </div>          
        </form>  
      </div>
    </section>
    <!-- Panel ends --> 
  </div>
</div>
<!-- Basic Forms ends -->   
<?php /*
<!--div class="col-sm-12 col-md-4 col-lg-4 m-bot15">
              <label class="control-label">Push item quantities to opening?</label>
              <div class="select-wrap">
                <select class="form-control" name="addToOpening" id="addToOpening">
                  <?php foreach($yes_no_options as $key=>$value): ?>
                    <option value="<?php echo $key ?>"><?php echo $value ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div-->
*/ ?>