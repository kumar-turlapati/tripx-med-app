<?php
  use Atawa\Utilities;

  if(isset($template_vars) && is_array($template_vars)) {
    extract($template_vars); 
  }
  
  $query_params = '';  
  if(isset($search_params['doctorName']) && $search_params['doctorName'] !='') {
    $doctorName = $search_params['doctorName'];
    $query_params[] = 'doctorName='.$doctorName;
  } else {
    $doctorName = '';
  }
  if(is_array($query_params) && count($query_params)>0) {
    $query_params = '?'.implode('&', $query_params);
  }

  $pagination_url = '/doctors/list';  
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
        <?php endif; ?>

        <!-- Right links starts -->
        <div class="global-links actionButtons clearfix">
          <div class="pull-right text-right">
            <a href="/doctors/create" class="btn btn-default">
              <i class="fa fa-file-text-o"></i> New Doctor 
            </a> 
          </div>
        </div>
        <!-- Right links ends --> 
        
        <h2 class="hdg-reports text-center">List of all Doctors</h2>
        <div class="table-responsive">
          <table class="table table-striped table-hover font12">
            <thead>
              <tr>
                <th width="5%" class="text-center valign-middle">Sl.No.</th>
                <th width="25%" class="valign-middle">Doctor Name</th>
                <th width="25%" class="valign-middle">Address</th>
                <th width="10%" class="valign-middle">Phone1</span></th>
                <th width="10%" class="valign-middle">Phone2</th>
                <th width="10%" class="valign-middle">Phone3</th>
                <th width="5%" class="text-center valign-middle ">Status</th>
                <th width="10%" class="text-center valign-middle">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php 
                $cntr = $sl_no;
                foreach($doctors as $doctor_details):
                  $doctor_code = $doctor_details['doctorCode'];
                  $status = $doctor_details['status'];
                  if($status) {
                    $status = 'Active';
                  } else {
                    $status = 'Inactive';
                  }
              ?>
                  <tr class="text-right font12">
                    <td align="center" class="valign-middle"><?php echo $cntr ?></td>
                    <td class="text-left valign-middle"><?php echo $doctor_details['doctorName'] ?></td>
                    <td><?php echo $doctor_details['address'] ?></td>
                    <td class="text-bold valign-middle"><?php echo $doctor_details['mobile1'] ?></td>
                    <td class="text-left valign-middle"><?php echo $doctor_details['mobile2'] ?></td>
                    <td class="text-left valign-middle"><?php echo $doctor_details['phone'] ?></td>
                    <td class="text-left valign-middle"><?php echo $status ?></td>
                    <td>
                      <div class="btn-actions-group valign-middle">
                        <?php if($doctor_code !== ''): ?>
                          <a class="btn btn-primary" href="/doctors/update/<?php echo $doctor_code ?>" title="Edit Doctor">
                            <i class="fa fa-pencil"></i>
                          </a>
                          <a class="btn btn-danger delDoctor" href="javascrip:void(0)" title="Remove Doctor" sid="<?php echo $doctor_code ?>">
                            <i class="fa fa-times"></i>
                          </a>                          
                        <?php endif; ?>
                      </div>
                    </td>
                  </tr>
            <?php
              $cntr++;
              endforeach; 
            ?>
            </tbody>
          </table>

          <?php include_once __DIR__."/../../../Layout/helpers/pagination.helper.php" ?>

        </div>
      </div>
    </section>
    <!-- Panel ends -->
  </div>
</div>

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
</div>