<?php
  use Atawa\Utilities;

  $query_params = [];  
  if(isset($search_params['custName']) && $search_params['custName'] !='') {
    $custName = $search_params['custName'];
    $query_params[] = 'custName='.$customerName;
  } else {
    $custName = '';
  }
  if(is_array($query_params) && count($query_params)>0) {
    $query_params = '?'.implode('&', $query_params);
  }

  if((int)$business_category===1) {
    $heading = 'Create Patient';
    $pagination_url = '/patients/list';
    $create_url = '/patients/create';
    $update_url = '/patients/update';
    $list_heading = 'Patients List';
    $cp_name = 'Patient';
    $reg_label = 'Registration No.';
  } else {
    $heading = 'Create Customer';
    $pagination_url = '/customers/list';
    $create_url = '/customers/create';
    $update_url = '/customers/update';    
    $list_heading = 'Customers List';
    $cp_name = 'Customer';
    $reg_label = 'GST No.';
  }
  // dump($customers);
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
            <a href="<?php echo $create_url ?>" class="btn btn-default">
              <i class="fa fa-file-text-o"></i> New <?php echo $cp_name ?> 
            </a> 
          </div>
        </div>
        <!-- Right links ends --> 
        
        <h2 class="hdg-reports text-center"><?php echo $list_heading ?></h2>
        <div class="table-responsive">
          <table class="table table-striped table-hover font12">
            <thead>
              <tr>
                <th width="5%" class="text-center">Sno.</th>
                <th width="25%"><?php echo $cp_name ?> name</th>
                <th width="10%">Registration type</th>
                <th width="10%"><?php echo $reg_label ?></span></th>
                <th width="10%">Mobile no.</th>
                <?php if((int)$business_category===1): ?>
                  <th width="8%">Gender</th>
                  <th width="7%" class="text-left">Age</th>
                <?php endif; ?>
                <th width="10%" class="text-center">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php if(is_array($customers) && count($customers)>0): ?>
                <?php
                  $cntr = $sl_no;
                  foreach($customers as $customer_details):
                    $reg_code = $customer_details['regCode'];
                    $reg_type = $patient_types[$customer_details['regType']];
                    $patient_name = $customer_details['patientName'];
                    $ref_no = $customer_details['refNumber'];
                    $dob = $customer_details['dob'];
                    $age = $customer_details['age'];
                    $age_category = $customer_details['ageCategory'];
                    $mobile_no = $customer_details['mobileNo'];
                    $address = $customer_details['address'];
                    $pin_code = $customer_details['pincode'];
                    $gender = ($customer_details['gender']!=''?$genders[$customer_details['gender']]:'');
                ?>
                    <tr class="text-right font12">
                      <td class="valign-middle"><?php echo $cntr ?></td>
                      <td class="text-left valign-middle"><?php echo $patient_name ?></td>
                      <td class="text-left valign-middle"><?php echo $reg_type ?></td>
                      <td class="text-left text-bold valign-middle"><?php echo $ref_no ?></td>
                      <td class="text-left valign-middle"><?php echo $mobile_no ?></td>
                      <?php if((int)$business_category===1): ?>
                        <td class="text-left valign-middle"><?php echo $gender ?></td>
                        <td class="text-left valign-middle">
                          <?php echo ($age>0?$age.' '.$age_category:'') ?>
                        </td>
                      <?php endif; ?>
                      <td class="valign-middle">
                        <div class="btn-actions-group">
                          <?php if($reg_code !== ''): ?>
                            <a class="btn btn-success" href="<?php echo $update_url.'/'.$reg_code ?>" title="Edit <?php echo $cp_name ?> information">
                              <i class="fa fa-pencil"></i>
                            </a>
                          <?php endif; ?>
                        </div>
                      </td>
                    </tr>
              <?php
                $cntr++;
                endforeach; 
              ?>
            <?php else: ?>
                <tr>
                  <td colspan="<?php echo (int)$business_category===1?8:6 ?>" align="center"><b>No content is available.</b></td>
                </tr>
            <?php endif; ?>
            </tbody>
          </table>

          <?php include_once __DIR__."/../../../Layout/helpers/pagination.helper.php" ?>

        </div>
      </div>
    </section>
    <!-- Panel ends -->
  </div>
</div>

<?php /*
<!-- Modal HTML -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Confirmation</h4>
      </div>
      <div class="modal-body">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>*/ ?>
