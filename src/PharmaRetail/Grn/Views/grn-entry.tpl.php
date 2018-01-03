<?php 
  use Atawa\Utilities;

  if(isset($template_vars) && is_array($template_vars)) {
    extract($template_vars); 
  }

  $current_date = date("d-m-Y");
?>

<!-- Basic form starts -->
<div class="row">
  <div class="col-lg-12"> 
    
    <!-- Panel starts -->
    <section class="panelBox">
      <div class="panelBody">

        <?php echo Utilities::print_flash_message() ?>
        
        <!-- Right links starts -->
        <div class="global-links actionButtons clearfix">
          <div class="pull-right text-right">
            <a href="/grn/list" class="btn btn-default">
              <i class="fa fa-book"></i> GRN Register
            </a>
            <!-- <a href="/grn/entry" class="btn btn-default">
              <i class="fa fa-file-text-o"></i> New GRN Entry 
            </a> -->
          </div>
        </div>
        <!-- Right links ends --> 
        
        <!-- Form starts -->
        <form class="form-validate form-horizontal" method="POST" id="grnForm">
          <div class="panel">
          <div class="panel-body">
          <h2 class="hdg-reports borderBottom">Transaction Details</h2>
          <div class="form-group">
            <div class="col-sm-12 col-md-4 col-lg-4 m-bot15">
              <label class="control-label">Purchaser order (PO) No.</label>
              <input type="text" class="form-control" name="poNo" id="poNoGrn" 
              value="<?php echo (isset($submitted_data['poNo'])?$submitted_data['poNo']:'') ?>"
              >
              <?php if(isset($errors['poNo'])): ?>
                  <span class="error"><?php echo $errors['poNo'] ?></span>
              <?php endif; ?>
            </div>
            <div class="col-sm-12 col-md-4 col-lg-4 m-bot15">
              <label class="control-label">Supplier name</label>
              <div class="select-wrap">
                <select class="form-control" name="supplierID" id="supplierID" disabled>
                  <?php foreach($suppliers as $key=>$value): ?>
                    <option value="<?php echo $key ?>"><?php echo $value ?></option>
                  <?php endforeach; ?>
                </select>
              </div>         
            </div>
            <div class="col-sm-12 col-md-4 col-lg-4 m-bot15">
              <label class="control-label">Payment method</label>
              <div class="select-wrap">
                <select class="form-control" name="paymentMethod" id="paymentMethod" disabled>
                  <?php foreach($payment_methods as $key=>$value): ?>
                    <option value="<?php echo $key ?>"><?php echo $value ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>            
          </div>
          <div class="form-group">
            <div class="col-sm-12 col-md-4 col-lg-4 m-bot15">
              <label class="control-label">Credit period (in days)</label>
              <div class="select-wrap">
                <select class="form-control" name="creditDays" id="creditDays" disabled>
                  <?php foreach($credit_days_a as $key=>$value): ?>
                    <option value="<?php echo $key ?>"><?php echo $value ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="col-sm-12 col-md-4 col-lg-4 m-bot15">
              <label class="control-label">Bill number</label>
              <input type="text" class="form-control" name="billNo" id="billNo" value="<?php echo (isset($submitted_data['billNo'])?$submitted_data['billNo']:'') ?>">
              <?php if(isset($errors['billNo'])): ?>
                <span class="error"><?php echo $errors['billNo'] ?></span>
              <?php endif; ?>
            </div>              
            <div class="col-sm-12 col-md-4 col-lg-4 m-bot15">
              <label class="control-label">GRN date (dd-mm-yyyy)</label>
              <div class="form-group">
                <div class="col-lg-12">
                  <div class="input-append date" data-date="<?php echo $current_date ?>" data-date-format="dd-mm-yyyy">
                    <input class="span2" value="<?php echo $current_date ?>" size="16" type="text" readonly name="grnDate" id="grnDate" />
                    <span class="add-on"><i class="fa fa-calendar"></i></span>
                  </div>                 
                </div>
              </div>
            </div>              
          </div>
          </div>
          </div>
          <h2 class="hdg-reports">Item Details</h2>        
          <div class="table-responsive">

            <table class="table table-striped table-hover item-detail-table">
              <thead>
                <tr>
                  <th width="230px" class="text-left">Item Name</th>
                  <th width="100px" class="text-center">Available Qty.<br /></th>
                  <th width="100px" class="text-center">Accepted<br />Qty.</th>
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
                  for($i=0;$i<=24;$i++):
                ?>
                    <tr>
                      <td id="grnItemName_<?php echo $i ?>">&nbsp;</td>
                      <td id="grnRcvdQty_<?php echo $i ?>" class="text-right">&nbsp;</td>
                      <td>
                        <div class="select-wrap">
                          <select class="form-control" name="grnAccQty_<?php echo $i ?>" id="grnAccQty_<?php echo $i ?>">
                            <?php foreach($qtys_a as $key=>$value): ?>
                              <option value="<?php echo $key ?>"><?php echo $value ?></option>
                            <?php endforeach; ?>
                          </select>
                        </div>
                      </td>
                      <td id="grnBatchNo_<?php echo $i ?>" class="text-right">&nbsp;</td>
                      <td id="grnExpDate_<?php echo $i ?>" class="text-right">&nbsp;</td>
                      <td id="grnMrp_<?php echo $i ?>" class="text-right">&nbsp;</td>
                      <td id="grnItemRate_<?php echo $i ?>" class="text-right">&nbsp;</td>
                      <td id="grnTaxRate_<?php echo $i ?>" class="text-right">&nbsp;</td>
                      <td id="grnItemAmount_<?php echo $i ?>" class="text-right">&nbsp;</td>
                    </tr>
                <?php endfor; ?>
                    <tr>
                      <td colspan="8" align="right"><b>Item Totals</b></td>
                      <td class="grandTotal" id="grandTotal" align="right"></td>
                    </tr>
                    <tr>
                      <td colspan="8" align="right"><b>Adjustments(-)</b></td>
                      <td class="adjAmount" id="adjAmount" align="right"></td>
                    </tr>                    
                    <tr>
                      <td colspan="8" align="right"><b>Round Off (+/-)</b></td>
                      <td class="roundOff" id="roundOff" align="right"></td>
                    </tr>
                    <tr>
                      <td colspan="8" align="right"><b>Bill Amount</b></td>
                      <td class="netPay" id="netPay" align="right"></td>
                    </tr>                
              </tbody>
            </table>
          </div>          
          <div class="text-center">
            <button class="btn btn-primary" id="Save">
              <i class="fa fa-save"></i> Save
            </button>
            <button class="btn btn-primary" id="Save&Print">
              <i class="fa fa-print"></i> Save &amp; Print
            </button>            
          </div>
          <input type="hidden" name="poCode" id="poCode" value="" />     
        </form>  
      </div>
    </section>
    <!-- Panel ends --> 
  </div>
</div>
<!-- Basic Forms ends -->