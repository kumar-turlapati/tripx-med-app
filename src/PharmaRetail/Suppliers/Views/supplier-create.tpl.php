<?php 
  $supplier_name = isset($submitted_data['supplierName']) ? $submitted_data['supplierName'] : '';
  $dl_no = isset($submitted_data['dlNo']) ? $submitted_data['dlNo'] : '';
  $tin_no = isset($submitted_data['tinNo']) ? $submitted_data['tinNo'] : '';
  $address1 = isset($submitted_data['address1']) ? $submitted_data['address1'] : '';
  $address2 = isset($submitted_data['address2']) ? $submitted_data['address2'] : '';
  $pincode = isset($submitted_data['pincode']) ? $submitted_data['pincode'] : '';
  $state_id = isset($submitted_data['stateID']) ? $submitted_data['stateID'] : '';
  $country_id = isset($submitted_data['countryID']) ? $submitted_data['countryID'] : '';
  $city_name = isset($submitted_data['cityName']) ? $submitted_data['cityName'] : '';
  $phone1 = isset($submitted_data['phone1']) ? $submitted_data['phone1'] : '';
  $phone2 = isset($submitted_data['phone2']) ? $submitted_data['phone2'] : '';
  $mobile_no = isset($submitted_data['mobileNo']) ? $submitted_data['mobileNo'] : '';
  $email = isset($submitted_data['mobileNo']) ? $submitted_data['mobileNo'] : '';
  $website = isset($submitted_data['website']) ? $submitted_data['website'] : '';
  $contact_person_name = isset($submitted_data['contactPersonName']) ? $submitted_data['contactPersonName'] : '';
?>
<div class="row">
  <div class="col-lg-12"> 
    <section class="panel">
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
        
        <div class="global-links actionButtons clearfix">
          <div class="pull-right text-right">
            <a href="/suppliers/list" class="btn btn-default">
              <i class="fa fa-book"></i> Suppliers List
            </a>
          </div>
        </div>
        <form class="form-validate form-horizontal" method="POST">
          <h2 class="hdg-reports borderBottom">Supplier Details</h2>
          <div class="form-group">
            <div class="col-sm-12 col-md-4 col-lg-4">
              <label class="control-label">Supplier name</label>
              <input 
                type="text" 
                class="form-control" 
                name="supplierName" 
                id="supplierName" 
                value="<?php echo $supplier_name ?>"
              />
              <?php if(isset($errors['supplierName'])): ?>
                <span class="error"><?php echo $errors['supplierName'] ?></span>
              <?php endif; ?>              
            </div>
            <div class="col-sm-12 col-md-4 col-lg-4">
              <label class="control-label">Supplier category</label>
              <div class="select-wrap">
                <select class="form-control" name="supplierType" id="supplierType">
                  <?php foreach($supplier_categories as $key=>$value): ?>
                    <option value="<?php echo $key ?>"><?php echo $value ?></option>
                  <?php endforeach; ?>
                </select>
                <?php if(isset($errors['supplierType'])): ?>
                  <span class="error"><?php echo $errors['supplierType'] ?></span>
                <?php endif; ?>
              </div>
            </div>           
            <div class="col-sm-12 col-md-4 col-lg-4">
              <label class="control-label">Drug Licence No.</label>
              <input 
                type="text" 
                class="form-control" 
                name="dlNo" 
                id="dlNo" 
                value="<?php echo $dl_no ?>"
              />
              <?php if(isset($errors['dlNo'])): ?>
                  <span class="error"><?php echo $errors['dlNo'] ?></span>
              <?php endif; ?>              
            </div>             
          </div>
          <div class="form-group">
            <div class="col-sm-12 col-md-4 col-lg-4 m-bot15">
              <label class="control-label">GST No.</label>
              <input 
                type="text" 
                class="form-control" 
                name="tinNo" 
                id="tinNo"
                value="<?php echo $tin_no ?>"
                maxlength="15"
              />
              <?php if(isset($errors['tinNo'])): ?>
                  <span class="error"><?php echo $errors['tinNo'] ?></span>
              <?php endif; ?>              
            </div>            
            <div class="col-sm-12 col-md-4 col-lg-4 m-bot15">
              <label class="control-label">Status</label>
              <div class="select-wrap">
                <select class="form-control" name="paymentMethod" id="paymentMethod">
                  <?php foreach($status as $key=>$value): ?>
                    <option value="<?php echo $key ?>"><?php echo $value ?></option>
                  <?php endforeach; ?>
                </select>
                <?php if(isset($errors['status'])): ?>
                  <span class="error"><?php echo $errors['status'] ?></span>
                <?php endif; ?>
              </div>
            </div>         
          </div>
          <h2 class="hdg-reports borderBottom">Location Details</h2>
          <div class="form-group">
            <div class="col-sm-12 col-md-4 col-lg-4">
              <label class="control-label">Address-1</label>
              <input 
                type="text" 
                class="form-control" 
                name="address1" 
                id="address1"
                value="<?php echo $address1 ?>"      
              />
              <?php if(isset($errors['address1'])): ?>
                <span class="error"><?php echo $errors['address1'] ?></span>
              <?php endif; ?>              
            </div>
            <div class="col-sm-12 col-md-4 col-lg-4">
              <label class="control-label">Address-2</label>
              <input 
                type="text" 
                class="form-control" 
                name="address2" 
                id="address2"
                value="<?php echo $address2 ?>"              
              />
              <?php if(isset($errors['address2'])): ?>
                <span class="error"><?php echo $errors['address2'] ?></span>
              <?php endif; ?>              
            </div>          
            <div class="col-sm-12 col-md-4 col-lg-4">
              <label class="control-label">Pincode</label>
              <input 
                type="text" 
                class="form-control" 
                name="pincode" 
                id="pincode"
                value="<?php echo $pincode ?>"              
              />
              <?php if(isset($errors['pincode'])): ?>
                <span class="error"><?php echo $errors['pincode'] ?></span>
              <?php endif; ?>              
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-12 col-md-4 col-lg-4">
              <label class="control-label">Country name</label>
              <div class="select-wrap">
                <select class="form-control" name="countryID" id="countryID">
                  <?php 
                    foreach($countries as $key=>$value):
                      if((int)$country_id === (int)$key) {
                        $selected = 'selected="selected"';
                      } else {
                        $selected = '';
                      } 
                  ?>
                    <option value="<?php echo $key ?>" <?php echo $selected ?>><?php echo $value ?></option>
                  <?php endforeach; ?>
                </select>
                <?php if(isset($errors['countryID'])): ?>
                  <span class="error"><?php echo $errors['countryID'] ?></span>
                <?php endif; ?>                 
              </div>        
            </div>
            <div class="col-sm-12 col-md-4 col-lg-4">
              <label class="control-label">State</label>
              <div class="select-wrap">              
                <select class="form-control" name="stateID" id="stateID">
                  <?php 
                    foreach($states as $key=>$value): 
                      if((int)$state_id === (int)$key) {
                        $selected = 'selected="selected"';
                      } else {
                        $selected = '';
                      }
                  ?>
                    <option value="<?php echo $key ?>" <?php echo $selected ?>><?php echo $value ?></option>
                  <?php endforeach; ?>              
                </select>
              </div>
              <?php if(isset($form_errors['stateID'])): ?>
                <span class="error"><?php echo $form_errors['stateID'] ?></span>
              <?php endif; ?>
            </div>
            <div class="col-sm-12 col-md-4 col-lg-4 m-bot15">
              <label class="control-label">City name</label>
              <input type="text" class="form-control" name="cityName" id="cityName" value="<?php echo $city_name ?>" />
            </div>
          </div>
          <h2 class="hdg-reports borderBottom">Contact Details</h2>
          <div class="form-group">
            <div class="col-sm-12 col-md-4 col-lg-4">
              <label class="control-label">Phone-1</label>
              <input 
                type="text" 
                class="form-control" 
                name="phone1" 
                id="phone1"
                value="<?php echo $phone1 ?>"              
              />
              <?php if(isset($errors['phone1'])): ?>
                <span class="error"><?php echo $errors['phone1'] ?></span>
              <?php endif; ?>                
            </div>
            <div class="col-sm-12 col-md-4 col-lg-4">
              <label class="control-label">Phone-2</label>
              <input 
                type="text" 
                class="form-control"
                name="phone2"
                id="phone2"
                value="<?php echo $phone2 ?>"
              />
              <?php if(isset($errors['phone2'])): ?>
                <span class="error"><?php echo $errors['phone2'] ?></span>
              <?php endif; ?>               
            </div>
            <div class="col-sm-12 col-md-4 col-lg-4">
              <label class="control-label">Mobile No.</label>
              <input 
                type="text" 
                class="form-control" 
                name="mobileNo"
                id="mobileNo"
                value="<?php echo $mobile_no ?>"
                maxlength=10
              />
              <?php if(isset($errors['mobileNo'])): ?>
                <span class="error"><?php echo $errors['mobileNo'] ?></span>
              <?php endif; ?>                
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-12 col-md-4 col-lg-4 m-bot15">
              <label class="control-label">Email ID</label>
              <input 
                type="text" 
                class="form-control" 
                name="email" 
                id="email"
                value="<?php echo $email ?>"              
              />
              <?php if(isset($errors['email'])): ?>
                <span class="error"><?php echo $errors['email'] ?></span>
              <?php endif; ?>     
            </div>
            <div class="col-sm-12 col-md-4 col-lg-4 m-bot15">
              <label class="control-label">Website</label>
              <input 
                type="text" 
                class="form-control" 
                name="website" 
                id="website"
                value="<?php echo $website ?>"              
              />
              <?php if(isset($errors['website'])): ?>
                <span class="error"><?php echo $errors['website'] ?></span>
              <?php endif; ?>               
            </div>
            <div class="col-sm-12 col-md-4 col-lg-4 m-bot15">
              <label class="control-label">Contact person name</label>
              <input 
                type="text" 
                class="form-control" 
                name="contactPersonName" 
                id="contactPersonName"
                value="<?php echo $contact_person_name ?>"
              />
              <?php if(isset($errors['contactPersonName'])): ?>
                <span class="error"><?php echo $errors['contactPersonName'] ?></span>
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
  </div>
</div>