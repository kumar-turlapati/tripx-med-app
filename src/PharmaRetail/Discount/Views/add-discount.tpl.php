<?php
  use Atawa\Utilities;
  if(isset($submitted_data['tvFrom'])) {
    $tv_from = $submitted_data['tvFrom'];
  } else {
    $tv_from = '';
  }
  if(isset($submitted_data['tvTo'])) {
    $tv_to = $submitted_data['tvTo'];
  } else {
    $tv_to = '';
  }
  if(isset($submitted_data['discountPercent'])) {
    $discount_percent = $submitted_data['discountPercent'];
  } else {
    $discount_percent = '';
  }
  if(isset($submitted_data['couponCode'])) {
    $coupon_code = $submitted_data['couponCode'];
  } else {
    $coupon_code = '';
  }
  if(isset($submitted_data['isAllowedForOlo'])) {
    $is_allowed_for_olo = $submitted_data['isAllowedForOlo'];
  } else {
    $is_allowed_for_olo = '';
  }
  if(isset($submitted_data['isAllowedForMao'])) {
    $is_allowed_for_mao = $submitted_data['isAllowedForMao'];
  } else {
    $is_allowed_for_mao = '';
  }
?>
<div class="row">
  <div class="col-lg-12"> 
    <section class="panel">
      <h2 class="hdg-reports text-center">Add Discount Percent</h2>
      <div class="panel-body">
        <?php echo Utilities::print_flash_message() ?>
        <div class="global-links actionButtons clearfix">
          <div class="pull-right text-right">
            <a href="/discount-percent/list" class="btn btn-default">
              <i class="fa fa-book"></i> Discount Percentages List 
            </a> 
          </div>
        </div>
        <form class="form-validate form-horizontal" method="POST">
          <div class="form-group">
            <div class="col-sm-12 col-md-4 col-lg-4">
              <label class="control-label">Transaction value from</label>
              <input
                type="text" 
                class="form-control" 
                name="tvFrom" 
                id="tvFrom"
                value="<?php $tv_from ?>"
              >
              <?php if(isset($form_errors['tvFrom'])): ?>
                <span class="error"><?php echo $form_errors['tvFrom'] ?></span>
              <?php endif; ?>
            </div>
            <div class="col-sm-12 col-md-4 col-lg-4">
              <label class="control-label">Transaction value to</label>
              <input
                type="text" 
                class="form-control" 
                name="tvTo" 
                id="tvTo"
                value="<?php $tv_to ?>"
              >
              <?php if(isset($form_errors['tvTo'])): ?>
                <span class="error"><?php echo $form_errors['tvTo'] ?></span>
              <?php endif; ?>
            </div>
            <div class="col-sm-12 col-md-4 col-lg-4">
              <label class="control-label">Discount percent</label>
              <input
                type="text"
                class="form-control"
                name="discountPercent" 
                id="discountPercent"
                value="<?php $discount_percent ?>"
              >
              <?php if(isset($form_errors['discountPercent'])): ?>
                <span class="error"><?php echo $form_errors['discountPercent'] ?></span>
              <?php endif; ?>
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-12 col-md-4 col-lg-4 m-bot15">
              <label class="control-label">Coupon code</label>
              <input
                type="text" 
                class="form-control" 
                name="couponCode" 
                id="couponCode"
                value="<?php $coupon_code ?>"
                maxlength="6"
              >
              <?php if(isset($form_errors['couponCode'])): ?>
                <span class="error"><?php echo $form_errors['couponCode'] ?></span>
              <?php endif; ?>
            </div>
            <div class="col-sm-12 col-md-4 col-lg-4 m-bot15">
              <label class="control-label">Is allowed for online orders?</label>
              <div class="select-wrap">
                <select class="form-control" name="isAllowedForOlo" id="isAllowedForOlo">
                  <?php 
                    foreach($yes_no_options as $key=>$value):
                      if((int)$is_allowed_for_olo === (int)$key) {
                        $selected = 'selected="selected"';
                      } else {
                        $selected = '';
                      }
                  ?>
                    <option value="<?php echo $key ?>" <?php echo $selected ?>><?php echo $value ?></option>
                  <?php endforeach; ?>
                </select>
                <?php if(isset($form_errors['isAllowedForOlo'])): ?>
                  <span class="error"><?php echo $form_errors['isAllowedForOlo'] ?></span>
                <?php endif; ?> 
              </div>              
            </div>
            <div class="col-sm-12 col-md-4 col-lg-4 m-bot15">
              <label class="control-label">Is allowed for counter sales?</label>
              <div class="select-wrap">
                <select class="form-control" name="isAllowedForMao" id="isAllowedForMao">
                  <?php 
                    foreach($yes_no_options as $key=>$value):
                      if((int)$is_allowed_for_mao === (int)$key) {
                        $selected = 'selected="selected"';
                      } else {
                        $selected = '';
                      }                      
                  ?>
                    <option value="<?php echo $key ?>" <?php echo $selected ?>><?php echo $value ?></option>
                  <?php endforeach; ?>
                </select>
                <?php if(isset($form_errors['isAllowedForMao'])): ?>
                  <span class="error"><?php echo $form_errors['isAllowedForMao'] ?></span>
                <?php endif; ?> 
              </div>              
            </div>
          </div>
          <div class="text-center">
            <button class="btn btn-success" id="Save">
              <i class="fa fa-save"></i> Save
            </button>
          </div>
        </form>
      </div>
    </section>
  </div>
</div>