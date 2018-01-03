<?php
  use Atawa\Utilities;
?>
<div class="row">
  <div class="col-lg-12"> 
    <section class="panel">
      <div class="panel-body">
        <?php echo Utilities::print_flash_message() ?>
        <div class="global-links actionButtons clearfix">
          <!-- Button style -->
          <div class="pull-right text-right">
            <a href="/inventory/item-threshold-list" class="btn btn-default">
              <i class="fa fa-book"></i> Threshold Quantities List
            </a> 
          </div>
          <!-- Button style -->
        </div>
      </div>
    </section>
  </div>
</div>