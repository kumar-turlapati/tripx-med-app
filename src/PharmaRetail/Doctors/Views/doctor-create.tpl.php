<?php 
  if(isset($template_vars) && is_array($template_vars)) {
    extract($template_vars); 
  }

  if(isset($submitted_data['status'])) {
    $sel_status = (int)$submitted_data['status'];
  } else {
    $sel_status = 1;
  }
?>

<!-- Basic form starts -->
<div class="row">
  <div class="col-lg-12"> 
    
    <!-- Panel starts -->
    <section class="panel">
      <h2 class="hdg-reports text-center">Doctor Details</h2>
      <div class="panel-body">

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
            <a href="/doctors/list" class="btn btn-default">
              <i class="fa fa-book"></i> Doctors List
            </a>
           <!-- <a href="/doctors/create" class="btn btn-default">
              <i class="fa fa-file-text-o"></i> New Doctor 
            </a> -->
          </div>
        </div>
        <!-- Right links ends --> 
        
        <!-- Form starts -->
        <form class="form-validate form-horizontal" method="POST">          
          <div class="form-group">
            <div class="col-sm-12 col-md-4 col-lg-4 m-bot15">
              <label class="control-label">Doctor name</label>
              <input 
                type="text" class="form-control" name="doctorName" id="doctorName" 
                value="<?php echo (isset($submitted_data['doctorName'])?$submitted_data['doctorName']:'') ?>"
              >
              <?php if(isset($errors['doctorName'])): ?>
                <span class="error"><?php echo $errors['doctorName'] ?></span>
              <?php endif; ?>              
            </div>
            <div class="col-sm-12 col-md-4 col-lg-4 m-bot15">
              <label class="control-label">Address</label>
              <input 
                type="text" class="form-control" name="address" id="address" 
                value="<?php echo (isset($submitted_data['address'])?$submitted_data['address']:'') ?>"
              >
              <?php if(isset($errors['address'])): ?>
                <span class="error"><?php echo $errors['address'] ?></span>
              <?php endif; ?> 
            </div>
            <div class="col-sm-12 col-md-4 col-lg-4 m-bot15">
              <label class="control-label">Status</label>
              <div class="select-wrap">
                <select class="form-control" name="status" id="status">
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
                <?php if(isset($errors['status'])): ?>
                  <span class="error"><?php echo $errors['status'] ?></span>
                <?php endif; ?>
              </div>
            </div>             
            <?php /*         
            <div class="col-sm-12 col-md-4 col-lg-4 m-bot15">
              <label class="control-label">Doctor code (Auto)</label>
              <input type="text" class="form-control" name="doctorCode" id="doctorCode" disabled value="<?php echo $doctor_code ?>"
              >
            </div>*/ ?>            
          </div>

          <div class="form-group">
            <div class="col-sm-12 col-md-4 col-lg-4 m-bot15">
              <label class="control-label">Mobile-1</label>
              <input type="text" class="form-control" name="mobile1" id="mobile1" 
              value="<?php echo (isset($submitted_data['mobile1'])?$submitted_data['mobile1']:'') ?>"
              >
              <?php if(isset($errors['mobile1'])): ?>
                  <span class="error"><?php echo $errors['mobile1'] ?></span>
              <?php endif; ?>              
            </div>
            <div class="col-sm-12 col-md-4 col-lg-4 m-bot15">
              <label class="control-label">Mobile2</label>
              <input type="text" class="form-control" name="mobile2" id="mobile2"
               value="<?php echo (isset($submitted_data['mobile2'])?$submitted_data['mobile2']:'') ?>"
              >
              <?php if(isset($errors['mobile2'])): ?>
                  <span class="error"><?php echo $errors['mobile2'] ?></span>
              <?php endif; ?>              
            </div>
            <div class="col-sm-12 col-md-4 col-lg-4 m-bot15">
              <label class="control-label">Phone</label>
              <input type="text" class="form-control" name="phone" id="phone"
              value="<?php echo (isset($submitted_data['phone'])?$submitted_data['phone']:'') ?>"
              >
              <?php if(isset($errors['phone'])): ?>
                <span class="error"><?php echo $errors['phone'] ?></span>
              <?php endif; ?>               
            </div>                     
          </div>
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
<!-- Basic Forms ends -->   


 
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