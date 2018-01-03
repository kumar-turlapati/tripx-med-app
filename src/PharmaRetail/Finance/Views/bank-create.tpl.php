<?php
  use Atawa\Utilities;
?>
<!-- Basic form starts -->
<div class="row">
  <div class="col-lg-12"> 
    
    <!-- Panel starts -->
    <section class="panel">
      <h2 class="hdg-reports text-center">Create Bank</h2>
      <div class="panel-body">

        <?php echo Utilities::print_flash_message() ?>

        <!-- Right links starts -->
        <div class="global-links actionButtons clearfix">
          <div class="pull-right text-right">
            <a href="/fin/bank/list" class="btn btn-default">
              <i class="fa fa-book"></i> Banks List
            </a>
          </div>
        </div>
        <!-- Right links ends --> 
        
        <!-- Form starts -->
        <form class="form-validate form-horizontal" method="POST">
          <div class="form-group">
            <div class="col-sm-12 col-md-4 col-lg-4 m-bot15">
              <label class="control-label">Account name</label>
              <input
                type="text" 
                class="form-control" 
                name="accountName" 
                id="accountName"
                value="<?php echo (isset($submitted_data['accountName'])?$submitted_data['accountName']:'') ?>"
              >
              <?php if(isset($form_errors['accountName'])): ?>
                <span class="error"><?php echo $form_errors['accountName'] ?></span>
              <?php endif; ?>
            </div>
            <div class="col-sm-12 col-md-4 col-lg-4 m-bot15">
              <label class="control-label">Bank name</label>
              <input 
                type="text" class="form-control" name="bankName" id="bankName" 
                value="<?php echo (isset($submitted_data['bankName'])?$submitted_data['bankName']:'') ?>"
              >
              <?php if(isset($form_errors['bankName'])): ?>
                <span class="error"><?php echo $form_errors['bankName'] ?></span>
              <?php endif; ?> 
            </div>
            <div class="col-sm-12 col-md-4 col-lg-4 m-bot15">
              <label class="control-label">Address</label>
              <input 
                type="text" class="form-control" name="address" id="address" 
                value="<?php echo (isset($submitted_data['address'])?$submitted_data['address']:'') ?>"
              >
              <?php if(isset($form_errors['address'])): ?>
                <span class="error"><?php echo $form_errors['address'] ?></span>
              <?php endif; ?> 
            </div>
          </div>      
          <div class="form-group">
            <div class="col-sm-12 col-md-4 col-lg-4 m-bot15">
              <label class="control-label">Account number</label>
              <input
                type="text" 
                class="form-control" 
                name="accountNo" 
                id="accountNo"
                value="<?php echo (isset($submitted_data['accountNo'])?$submitted_data['accountNo']:'') ?>"
              >
              <?php if(isset($form_errors['accountNo'])): ?>
                <span class="error"><?php echo $form_errors['accountNo'] ?></span>
              <?php endif; ?>
            </div>
            <div class="col-sm-12 col-md-4 col-lg-4 m-bot15">
              <label class="control-label">IFSC code</label>
              <input 
                type="text" class="form-control" name="ifscCode" id="ifscCode" 
                value="<?php echo (isset($submitted_data['ifscCode'])?$submitted_data['ifscCode']:'') ?>"
              >
              <?php if(isset($form_errors['ifscCode'])): ?>
                <span class="error"><?php echo $form_errors['ifscCode'] ?></span>
              <?php endif; ?> 
            </div>
            <div class="col-sm-12 col-md-4 col-lg-4 m-bot15">
              <label class="control-label">Phone</label>
              <input 
                type="text" class="form-control" name="phone" id="phone" 
                value="<?php echo (isset($submitted_data['phone'])?$submitted_data['phone']:'') ?>"
              >
              <?php if(isset($form_errors['phone'])): ?>
                <span class="error"><?php echo $form_errors['phone'] ?></span>
              <?php endif; ?> 
            </div>
          </div>
          <div class="text-center">
            <button class="btn btn-success" id="Save">
              <i class="fa fa-save"></i> Save
            </button>
          </div>          
        </form>
        <!-- Form ends -->
      </div>
    </section>
    <!-- Panel ends --> 
  </div>
</div>
<!-- Basic Forms ends -->