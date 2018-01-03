<?php 

  if(isset($submitted_data['patientName']) && $submitted_data['patientName'] !== '' ) {
    $patient_name = $submitted_data['patientName'];
  } else {
    $patient_name = '';
  }
  if(isset($submitted_data['regNo']) && $submitted_data['regNo'] !== '' ) {
    $reg_no = $submitted_data['regNo'];
  } else {
    $reg_no = '';
  }
  if(isset($submitted_data['regType']) && $submitted_data['regType'] !== '' ) {
    $reg_type = $submitted_data['regType'];
  } else {
    $reg_type = '';
  }
  if(isset($submitted_data['age']) && $submitted_data['age'] !== '' ) {
    $age = $submitted_data['age'];
  } else {
    $age = '';
  }
  if(isset($submitted_data['ageCategory']) && $submitted_data['ageCategory'] !== '' ) {
    $age_category = $submitted_data['ageCategory'];
  } else {
    $age_category = '';
  }
  if(isset($submitted_data['gender']) && $submitted_data['gender'] !== '' ) {
    $gender = $submitted_data['gender'];
  } else {
    $gender = '';
  }
  if(isset($submitted_data['mobileNo']) && $submitted_data['mobileNo'] !== '' ) {
    $mobile_no = $submitted_data['mobileNo'];
  } else {
    $mobile_no = '';
  }

  if((int)$business_category===1) {
    $heading = 'Create Patient';
    $list_url = '/patients/list';
    $list_heading = 'Patients List';
    $cp_name = 'Patient';
    $reg_label = 'Registration No.';
  } else {
    $heading = 'Create Customer';
    $list_url = '/customers/list';
    $list_heading = 'Customers List';
    $cp_name = 'Customer';
    $reg_label = 'GST No. (if applicable)';
    unset($reg_types[0]);
    unset($reg_types[2]);        
    unset($reg_types[3]);
  }
?>

<!-- Basic form starts -->
<div class="row">
  <div class="col-lg-12"> 
    
    <!-- Panel starts -->
    <section class="panel">
      <h2 class="hdg-reports text-center"><?php echo $heading ?></h2>
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
        
        <!-- Right links starts -->
        <div class="global-links actionButtons clearfix">
          <div class="pull-right text-right">
            <a href="<?php echo $list_url ?>" class="btn btn-default">
              <i class="fa fa-book"></i> <?php echo $list_heading ?>
            </a>
          </div>
        </div>
        <!-- Right links ends --> 
        
        <!-- Form starts -->
        <form class="form-validate form-horizontal" method="POST">
          <div class="form-group">
            <div class="col-sm-12 col-md-4 col-lg-4 m-bot15">
              <label class="control-label"><?php echo $cp_name ?> name</label>
              <input type="text" class="form-control" name="patientName" id="patientName" value="<?php echo $patient_name ?>" />
              <?php if(isset($errors['patientName'])): ?>
                <span class="error"><?php echo $errors['patientName'] ?></span>
              <?php endif; ?>              
            </div>
            <div class="col-sm-12 col-md-4 col-lg-4 m-bot15">
              <label class="control-label"><?php echo $reg_label ?></label>
              <input type="text" class="form-control" name="regNo" id="regNo" value="<?php echo $reg_no ?>">
              <?php if(isset($errors['regNo'])): ?>
                <span class="error"><?php echo $errors['regNo'] ?></span>
              <?php endif; ?> 
            </div>
            <div class="col-sm-12 col-md-4 col-lg-4 m-bot15">
              <label class="control-label">Registration type</label>
              <div class="select-wrap">
                <select class="form-control" name="regType" id="regType">
                  <?php 
                    foreach($reg_types as $key=>$value): 
                      if($reg_type === $key) {
                        $selected = 'selected="selected"';
                      } else {
                        $selected = '';
                      }  
                  ?>
                    <option value="<?php echo $key ?>" <?php echo $selected ?>><?php echo $value ?></option>
                  <?php endforeach; ?>
                </select>
                <?php if(isset($errors['regType'])): ?>
                  <span class="error"><?php echo $errors['regType'] ?></span>
                <?php endif; ?>
              </div>
            </div>         
          </div>

          <div class="form-group">
            <?php if((int)$business_category===1): ?>
              <div class="col-sm-12 col-md-4 col-lg-4 m-bot15">
                <label class="control-label"><?php echo $cp_name ?> age</label>
                <div class="row">
                  <div class="col-sm-6 col-md-6 col-lg-8">
                    <div class="select-wrap">
                      <select class="form-control" name="age" id="age">
                        <?php 
                          foreach($ages as $key=>$value):
                            if((int)$age === (int)$key) {
                              $selected = 'selected="selected"';
                            } else {
                              $selected = '';
                            }                          
                        ?>
                          <option value="<?php echo $key ?>" <?php echo $selected ?>><?php echo $value ?></option>
                        <?php endforeach; ?>
                      </select>
                      <?php if(isset($errors['age'])): ?>
                        <span class="error"><?php echo $errors['age'] ?></span>
                      <?php endif; ?>                    
                    </div>
                  </div>
                  <div class="col-sm-6 col-md-6 col-lg-4"> 
                    <div class="select-wrap">
                      <select class="form-control" name="ageCategory" id="ageCategory">
                        <?php 
                          foreach($age_categories as $key=>$value):
                            if($age_category === $key) {
                              $selected = 'selected="selected"';
                            } else {
                              $selected = '';
                            }                           
                        ?>
                          <option value="<?php echo $key ?>" <?php echo $selected ?>><?php echo $value ?></option>
                        <?php endforeach; ?>
                      </select>
                      <?php if(isset($errors['ageCategory'])): ?>
                        <span class="error"><?php echo $errors['ageCategory'] ?></span>
                      <?php endif; ?>                     
                    </div> 
                  </div>
                </div>
              </div>
              <div class="col-sm-12 col-md-4 col-lg-4 m-bot15">
                <label class="control-label">Gender (if applicable)</label>
                <div class="select-wrap">
                  <select class="form-control" name="gender" id="gender">
                    <?php 
                      foreach($genders as $key=>$value):
                        if($gender === $key) {
                          $selected = 'selected="selected"';
                        } else {
                          $selected = '';
                        }                      
                    ?>
                      <option value="<?php echo $key ?>" <?php echo $selected ?>><?php echo $value ?></option>
                    <?php endforeach; ?>
                  </select>
                  <?php if(isset($errors['gender'])): ?>
                    <span class="error"><?php echo $errors['gender'] ?></span>
                  <?php endif; ?>                 
                </div>
              </div>
            <?php else: ?>
              <input type="hidden" id="gender" name="gender" value="o" />
              <input type="hidden" id="age" name="age" value="99" />
              <input type="hidden" id="ageCategory" name="ageCategory" value="years" />
            <?php endif; ?>

            <div class="col-sm-12 col-md-4 col-lg-4 m-bot15">
              <label class="control-label">Mobile number</label>
              <input type="text" class="form-control" name="mobile" id="mobile" maxlength="10" value="<?php echo $mobile_no ?>">
              <?php if(isset($errors['mobileNo'])): ?>
                <span class="error"><?php echo $errors['mobileNo'] ?></span>
              <?php endif; ?>              
            </div>
          </div>

          <div class="text-center">
            <button class="btn btn-success" id="Save"><i class="fa fa-save"></i> Save</button>
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
            </div--> */?>