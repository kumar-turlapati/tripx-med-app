<?php
  use Atawa\Utilities;
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
            <a href="/users/create" class="btn btn-default">
              <i class="fa fa-user"></i> Add New User 
            </a> 
          </div>
        </div>
        <!-- Right links ends --> 
        
        <h2 class="hdg-reports text-center">List of all Users</h2>
        <div class="table-responsive">
          <table class="table table-striped table-hover">
            <thead>
              <tr>
                <th width="5%" class="text-center">Sno.</th>
                <th width="30%">User name</th>
                <th width="25%">Login ID</th>
                <th width="10%">Phone no</th>
                <th width="10%">User type</span></th>
                <th width="10%">Status</th>
                <th width="10%" class="text-center">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php
                if(count($users)>0) {
                    $cntr = 1;
                    foreach($users as $user_details):
                      $user_name = $user_details['userName'];
                      $email = $user_details['email'];
                      $phone = $user_details['userPhone'];
                      $uuid = $user_details['uuid'];
                      if((int)$user_details['userType']!==3) {
                        $user_type = Utilities::get_user_types($user_details['userType']);
                      }
                      if((int)$user_details['status']===0) {
                        $status = 'Disabled';
                      } elseif((int)$user_details['status']===1) {
                        $status = 'Active';
                      } elseif((int)$user_details['status']===0) {
                        $status = 'Inactive';
                      } else {
                        $status = '<span class="red">Blocked</span>';
                      }
                  ?>

                  <?php if( (int)$user_details['userType'] !== 3 ): ?>
                    <tr class="text-right font12">
                      <td align="center"><?php echo $cntr ?></td>
                      <td class="text-left"><?php echo $user_name ?></td>
                      <td class="text-left"><?php echo $email ?></td>
                      <td class="text-bold"><?php echo $phone ?></td>
                      <td class="text-left"><?php echo $user_type ?></td>
                      <td class="text-left"><?php echo $status ?></td>
                      <td>
                        <div class="btn-actions-group">
                          <?php if($uuid !== ''): ?>
                            <a class="btn btn-primary" href="/users/update/<?php echo $uuid ?>" title="Edit user">
                              <i class="fa fa-pencil"></i>
                            </a>
                            <a class="btn btn-danger delUser" href="javascript:void(0)" title="Remove user" uid="<?php echo $uuid ?>">
                              <i class="fa fa-times"></i>
                            </a>                                               
                          <?php endif; ?>
                        </div>
                      </td>
                    </tr>
                  <?php endif; ?>

                <?php
                  $cntr++;
                  endforeach; 
                ?>
            <?php } else { ?>
                <tr>
                  <td colspan="7">No users are available.</td>
                </tr>
            <?php } ?>
            </tbody>
          </table>

        </div>
      </div>
    </section>
    <!-- Panel ends -->
  </div>
</div>