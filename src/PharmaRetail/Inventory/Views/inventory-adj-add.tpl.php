<?php
  use Atawa\Utilities;

  if(isset($template_vars) && is_array($template_vars)) {
    extract($template_vars); 
  }

  if(isset($submitted_data['itemName']) && $submitted_data['itemName'] !== '') {
    $item_name = $submitted_data['itemName'];
  } else {
    $item_name = '';
  }
  if(isset($submitted_data['batchNo']) && $submitted_data['batchNo'] !== '') {
    $batch_no = $submitted_data['batchNo'];
  } else {
    $batch_no = '';
  }
  if(isset($submitted_data['adjQty']) && $submitted_data['adjQty'] !== '') {
    $adj_qty = $submitted_data['adjQty'];
  } else {
    $adj_qty = '';
  }
  if(isset($submitted_data['adjReasonCode']) && $submitted_data['adjReasonCode'] !== '') {
    $adj_reason_code = $submitted_data['adjReasonCode'];
  } else {
    $adj_reason_code = '';
  }
  if(isset($submitted_data['adjDate']) && $submitted_data['adjDate']!=='') {
    $current_date = date("d-m-Y", strtotime($submitted_data['adjDate']));
  } else {
    $current_date = date("d-m-Y");
  }
?>

<!-- Basic form starts -->
<div class="row">
  <div class="col-lg-12"> 
    
    <!-- Panel starts -->
    <section class="panel">
      <div class="panel-body">

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
            <a href="/inventory/stock-adjustments-list" class="btn btn-default">
              <i class="fa fa-book"></i> Stock Adjustments List
            </a>
            <!-- <a href="/inventory/stock-adjustment" class="btn btn-default">
              <i class="fa fa-file-text-o"></i> Add Stock Adjustment
            </a> --> 
          </div>
        </div>
        <!-- Right links ends --> 
        
        <!-- Form starts -->
        <form class="form-validate form-horizontal" method="POST">
          <h2 class="hdg-reports borderBottom">Item Details</h2>
          <div class="form-group">
            <div class="col-sm-12 col-md-4 col-lg-4 m-bot15">
              <label class="control-label">Item name</label>
              <input type="text" class="form-control inameAc" name="itemName" id="itemName" value="<?php echo $item_name ?>">
              <?php if(isset($errors['itemName'])): ?>
                <span class="error"><?php echo $errors['itemName'] ?></span>
              <?php endif; ?>           
            </div>
            <div class="col-sm-12 col-md-4 col-lg-4 m-bot15">
              <label class="control-label">Batch No.</label>
              <input type="text" class="form-control" name="batchNo" id="batchNo" value="<?php echo $batch_no ?>">
              <?php if(isset($errors['batchNo'])): ?>
                <span class="error"><?php echo $errors['batchNo'] ?></span>
              <?php endif; ?>              
            </div>
            <div class="col-sm-12 col-md-4 col-lg-4 m-bot15">
              <label class="control-label">Adjustment Qty.</label>
                <input type="text" class="form-control" name="adjQty" id="adjQty" value="<?php echo $adj_qty ?>">
                <?php if(isset($errors['adjQty'])): ?>
                  <span class="error"><?php echo $errors['adjQty'] ?></span>
                <?php endif; ?>
            </div>
          </div>

          <div class="form-group">

            <div class="col-sm-12 col-md-4 col-lg-4 m-bot15">
              <label class="control-label">Reason for Adjustment</label>
              <div class="select-wrap">
                <select class="form-control" name="adjReasonCode" id="adjReasonCode">
                  <?php 
                    foreach($adj_reasons as $key=>$value):
                      $adj_a = explode('_', $value);
                      if($adj_reason_code === $key) {
                        $selected = 'selected="selected"';
                      } else {
                        $selected = '';
                      }
                      if( is_array($adj_a) && isset($adj_a[1])>1 ) {
                        $disabled = 'disabled';
                      } else {
                        $disabled = '';
                      }
                  ?>
                    <option value="<?php echo $key ?>" <?php echo $selected.' '.$disabled ?>><?php echo $adj_a[0] ?></option>
                  <?php endforeach; ?>
                </select>
                <?php if(isset($errors['adjReasonCode'])): ?>
                  <span class="error"><?php echo $errors['adjReasonCode'] ?></span>
                <?php endif; ?>
              </div>             
            </div>

            <div class="col-sm-12 col-md-4 col-lg-4 m-bot15">
              <label class="control-label">Adjustment Date (dd-mm-yyyy)</label>
              <div class="form-group">
                <div class="col-lg-12">
                  <div class="input-append date" data-date="<?php echo $current_date ?>" data-date-format="dd-mm-yyyy">
                    <input class="span2" value="<?php echo $current_date ?>" size="16" type="text" readonly name="adjDate" id="adjDate" />
                    <span class="add-on"><i class="fa fa-calendar"></i></span>
                  </div>
                  <?php if(isset($errors['adjDate'])): ?>
                    <span class="error"><?php echo $errors['adjDate'] ?></span>
                  <?php endif; ?>                  
                </div>
              </div>
            </div>
          </div>
          <div class="text-center">
            <button class="btn btn-primary" id="Save">
              <i class="fa fa-save"></i> Add Adjustment
            </button>
          </div>          
        </form>  
      </div>
    </section>
    <!-- Panel ends --> 
  </div>
</div>
<!-- Basic Forms ends -->