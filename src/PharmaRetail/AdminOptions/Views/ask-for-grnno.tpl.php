<?php
  use Atawa\Utilities; 
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
        <?php endif; ?>
          <div id="filters-form">
            <!-- Form starts -->
            <form class="form-validate form-horizontal" method="POST" autcomplete="off">
              <div class="col-sm-12 col-md-2 col-lg-2">
                <label class="control-label">GRN No.</label>
                <input type="text" name="editGrnNo" id="editGrnNo" class="form-control" value="<?php echo $grn_no ?>">
              </div>
              <div class="col-sm-12 col-md-3 col-lg-3">
                  <label class="control-label">&nbsp;</label>
                  <button class="btn btn-success"><i class="fa fa-pencil-square-o"></i> Edit</button>
                  <button type="reset" class="btn btn-warning" onclick="javascript:resetFilter('/admin-options/enter-grn-no')"><i class="fa fa-refresh"></i> Reset </button>
              </div>
            </form>        
            <!-- Form ends -->
          </div>
          
      </div>
    </section>
    <!-- Panel ends -->
  </div>
</div>