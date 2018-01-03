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
            <form class="form-validate form-horizontal" method="POST">
              <div class="form-group">
                <div class="col-sm-12 col-md-6 col-lg-6">
                  <label class="control-label">Type item name (or) blank for all item updates</label>
                  <input type="text" name="itemName" id="itemName" class="form-control inameAc" value="<?php echo $item_name ?>">
                </div>
                <div class="col-sm-12 col-md-3 col-lg-3">
                 <label class="control-label">&nbsp;</label>
                  <button class="btn btn-success"><i class="fa fa-search"></i> Update</button>
                  <button type="reset" class="btn btn-warning" onclick="javascript:resetFilter('/admin-options/update-batch-qtys')"><i class="fa fa-refresh"></i> Reset </button>
                </div>
              </div>
            </form>
            <!-- Form ends -->
          </div>
          <p class="red">If no item is given, all the items are recalculated again.</p>

      </div>
    </section>
    <!-- Panel ends -->
  </div>
</div>