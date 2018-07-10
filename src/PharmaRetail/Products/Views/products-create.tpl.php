<?php 

  if(isset($submitted_data['itemStatus'])) {
    $sel_status = (int)$submitted_data['itemStatus'];
  } else {
    $sel_status = 1;
  }

  if(isset($submitted_data['mfgCode'])) {
    $sel_mfg_code = $submitted_data['mfgCode'];
  } else {
    $sel_mfg_code = '';
  }

  if(isset($submitted_data['compCode'])) {
    $sel_comp_code = $submitted_data['compCode'];
  } else {
    $sel_comp_code = '';
  }

  if(isset($submitted_data['mrp'])) {
    $sel_mrp = $submitted_data['mrp'];
  } else {
    $sel_mrp = '';
  }

  if(isset($submitted_data['unitsPerPack'])) {
    $sel_upp = $submitted_data['unitsPerPack'];
  } else {
    $sel_upp = '';
  }

  if(isset($submitted_data['catCode'])) {
    $sel_cat = $submitted_data['catCode'];
  } else {
    $sel_cat = '';
  }

  if(isset($submitted_data['isPrescMand'])) {
    $presc_option = $submitted_data['isPrescMand'];
  } else {
    $presc_option = 0;
  }

  if(isset($submitted_data['itemType'])) {
    $item_type = $submitted_data['itemType'];
  } else {
    $item_type = 'i';
  }

  if(isset($submitted_data['taxPercent'])) {
    $tax_rate = $submitted_data['taxPercent'];
  } else {
    $tax_rate = '';
  }

?>

<!-- Basic form starts -->
<div class="row">
  <div class="col-lg-12"> 
    
    <!-- Panel starts -->
    <section class="panel">
      <h2 class="hdg-reports text-center">Manage Product (or) Service</h2>
      <div class="panel-body">
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
        
        <div class="global-links actionButtons clearfix">
          <div class="pull-right text-right">
            <a href="/medicines/list" class="btn btn-default">
              <i class="fa fa-book"></i> Medicines List
            </a>
          </div>
        </div>
        <form class="form-validate form-horizontal" method="POST">
          <div class="form-group">
            <div class="col-sm-12 col-md-4 col-lg-4">
              <label class="control-label">Choose Product or Service</label>
              <div class="select-wrap">
                <select class="form-control" name="itemType" id="itemType">
                  <?php 
                    foreach($item_types_a as $key=>$value):
                      if($item_type === $key) {
                        $selected = 'selected="selected"';
                      } else {
                        $selected = '';
                      }                       
                  ?>
                    <option value="<?php echo $key ?>" <?php echo $selected ?>><?php echo $value ?></option>
                  <?php endforeach; ?>
                </select>
                <?php if(isset($errors['itemType'])): ?>
                  <span class="error"><?php echo $errors['itemType'] ?></span>
                <?php endif; ?>
              </div>
            </div>
            <div class="col-sm-12 col-md-4 col-lg-4">
              <label class="control-label">Product / Service description</label>
              <input 
                type="text" class="form-control" name="itemName" id="itemName" 
                value="<?php echo (isset($submitted_data['itemName'])?$submitted_data['itemName']:'') ?>"
              >
              <?php if(isset($errors['itemName'])): ?>
                <span class="error"><?php echo $errors['itemName'] ?></span>
              <?php endif; ?>              
            </div>
            <div class="col-sm-12 col-md-4 col-lg-4">
              <label class="control-label">SKU Code</label>
              <input
                type="text" class="form-control" name="itemSku" id="itemSku" 
                value="<?php echo (isset($submitted_data['itemSku'])?$submitted_data['itemSku']:'') ?>"
              >
              <?php if(isset($errors['itemSku'])): ?>
                <span class="error"><?php echo $errors['itemSku'] ?></span>
              <?php endif; ?>              
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-12 col-md-4 col-lg-4">
              <label class="control-label">HSN / SAC code</label>
              <input 
                type="text" 
                class="form-control" 
                name="hsnSacCode" 
                id="hsnSacCode" 
                value="<?php echo (isset($submitted_data['hsnSacCode'])?$submitted_data['hsnSacCode']:'') ?>"
                maxlength=8
              >
              <?php if(isset($errors['hsnSacCode'])): ?>
                <span class="error"><?php echo $errors['hsnSacCode'] ?></span>
              <?php endif; ?>
            </div>
            <div class="col-sm-12 col-md-4 col-lg-4">
              <label class="control-label">Tax rate</label>
              <div class="select-wrap">
                <select class="form-control" name="taxPercent" id="taxPercent">
                  <?php 
                    foreach($tax_rates_a as $key=>$value):
                      if($tax_rate == $value) {
                        $selected = 'selected="selected"';
                      } else {
                        $selected = '';
                      }                       
                  ?>
                    <option value="<?php echo $key ?>" <?php echo $selected ?>><?php echo $value ?></option>
                  <?php endforeach; ?>
                </select>
                <?php if(isset($errors['taxRate'])): ?>
                  <span class="error"><?php echo $errors['taxRate'] ?></span>
                <?php endif; ?>
              </div>              
            </div>
            <div class="col-sm-12 col-md-4 col-lg-4">
              <label class="control-label">Status</label>
              <div class="select-wrap">
                <select class="form-control" name="itemStatus" id="itemStatus">
                  <?php 
                    foreach($status as $key=>$value): 
                      if($sel_status === $key) {
                        $selected = 'selected="selected"';
                      } else {
                        $selected = '';
                      }                       
                  ?>
                    <option value="<?php echo $key ?>" <?php echo $selected ?>><?php echo $value ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <?php if(isset($errors['status'])): ?>
                <span class="error"><?php echo $errors['status'] ?></span>
              <?php endif; ?>
            </div>                   
          </div>
          <div class="form-group">
            <div class="col-sm-12 col-md-4 col-lg-4">
              <label class="control-label">Category name</label>
              <div class="select-wrap">
                <select class="form-control" name="categoryID" id="categoryID">
                  <?php 
                    foreach($categories as $key=>$value): 
                      if($sel_cat == $key) {
                        $selected = 'selected="selected"';
                      } else {
                        $selected = '';
                      }                       
                  ?>
                    <option value="<?php echo $key ?>" <?php echo $selected ?>><?php echo $value ?></option>
                  <?php endforeach; ?>
                </select>
                <?php if(isset($errors['categoryID'])): ?>
                  <span class="error"><?php echo $errors['categoryID'] ?></span>
                <?php endif; ?>
              </div>
            </div>
            <div class="col-sm-12 col-md-4 col-lg-4">
              <label class="control-label">Units per pack</label>
              <div class="select-wrap">
                <select class="form-control" name="unitsPerPack" id="unitsPerPack">
                  <?php
                    foreach($upp_a as $key=>$value): 
                      if($sel_upp == $key) {
                        $selected = 'selected="selected"';
                      } else {
                        $selected = '';
                      }                       
                  ?>
                    <option value="<?php echo $key ?>" <?php echo $selected ?>><?php echo $value ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <?php if(isset($errors['unitsPerPack'])): ?>
                <span class="error"><?php echo $errors['unitsPerPack'] ?></span>
              <?php endif; ?>
            </div>
            <?php /*
            <div class="col-sm-12 col-md-4 col-lg-4">
              <label class="control-label">Manufacturer name</label>
              <div class="select-wrap">
                <select class="form-control" name="mfgID" id="mfgID">
                  <?php 
                    foreach($mfgs as $key=>$value): 
                      if($sel_mfg_code === $key) {
                        $selected = 'selected="selected"';
                      } else {
                        $selected = '';
                      }                       
                  ?>
                    <option value="<?php echo $key ?>" <?php echo $selected ?>><?php echo $value ?></option>
                  <?php endforeach; ?>
                </select>
                <?php if(isset($errors['status'])): ?>
                  <span class="error"><?php echo $errors['status'] ?></span>
                <?php endif; ?>
              </div>
            </div>*/ ?>
            <input type="hidden" name="mfgID" id="mfgID" value="" />
          </div>
          <?php /*
          <div class="form-group">
            <div class="col-sm-12 col-md-4 col-lg-4">
              <label class="control-label">Composition name</label>
              <div class="select-wrap">
                <select class="form-control" name="compCode" id="compCode">
                  <?php 
                    foreach($comps as $key=>$value): 
                      if($sel_comp_code === $key) {
                        $selected = 'selected="selected"';
                      } else {
                        $selected = '';
                      }                       
                  ?>
                    <option value="<?php echo $key ?>" <?php echo $selected ?>><?php echo $value ?></option>
                  <?php endforeach; ?>
                </select>
                <?php if(isset($errors['compCode'])): ?>
                  <span class="error"><?php echo $errors['compCode'] ?></span>
                <?php endif; ?>
              </div>
            </div>
          </div>*/ ?>
          <input type="hidden" name="compCode" id="compCode" value="" />
          <div class="text-center">
            <button class="btn btn-success" id="Save">
              <i class="fa fa-save"></i> <?php echo $btn_label ?>
            </button>
          </div>          
        </form>  
      </div>
    </section>
    <!-- Panel ends --> 
  </div>
</div>