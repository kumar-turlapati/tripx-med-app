<?php
  use Atawa\Utilities;

  if(isset($template_vars) && is_array($template_vars)) {
    extract($template_vars); 
  }
  
  $query_params = '';  
  if(isset($search_params['medName']) && $search_params['medName'] !='') {
    $medName = $search_params['medName'];
    $query_params[] = 'medName='.$medName;
  } else {
    $medName = '';
  }

  if(isset($search_params['batchNo']) && $search_params['batchNo'] !='') {
    $batchNo = $search_params['batchNo'];
    $query_params[] = 'batchNo='.$batchNo;
  } else {
    $batchNo = '';
  }

  if(is_array($query_params) && count($query_params)>0) {
    $query_params = '?'.implode('&', $query_params);
  }

  $pagination_url = '/inventory/available-qty';  
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

        <div class="panel">
          <div class="panel-body">
          <div id="filters-form">
            <!-- Form starts -->
            <form class="form-validate form-horizontal" method="POST">
              <div class="form-group">
                <div class="col-sm-12 col-md-2 col-lg-1">Filter by</div>
                <div class="col-sm-12 col-md-2 col-lg-2">
                  <input placeholder="Medicine Name" type="text" name="medName" id="medName" class="form-control" value="<?php echo $medName ?>">
                </div>
                <div class="col-sm-12 col-md-2 col-lg-2">
                  <input placeholder="Batch No." type="text" name="batchNo" id="batchNo" class="form-control" value="<?php echo $batchNo ?>">
                </div>
                <div class="col-sm-12 col-md-3 col-lg-3">
                    <button class="btn btn-success"><i class="fa fa-file-text"></i> Filter</button>
                    <button type="reset" class="btn btn-warning" onclick="javascript:resetFilter('/inventory/available-qty')"><i class="fa fa-refresh"></i> Reset </button>
                </div>
              </div>
            </form>        
            <!-- Form ends -->
          </div>
        </div>
        </div>
        
        <h2 class="hdg-reports text-center">List of Available Item Qtys - Batchwise</h2>
        <div class="table-responsive">
          <table class="table table-striped table-hover">
            <thead>
              <tr>
                <th width="5%" class="text-center">Sl.No.</th>
                <th width="35%" class="text-left">Item Name</th>
                <th width="10%" class="text-center">Item Code</th>
                <th width="10%" class="text-center">Batch No.</th>
                <th width="10%" class="text-center">Available<br />Qty.</th>
                <th width="10%" class="text-center">Item Rate</th>
                <th width="5%" class="text-center">Expiry Date</th>
                <th width="10%" class="text-center">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php 
                $cntr = $sl_no;
                foreach($items as $item_details):
                  $item_name = $item_details['itemName'];
                  $item_code = $item_details['itemCode'];
                  $batch_no = $item_details['batchNo'];
                  $ava_qty = $item_details['availableQty'];
                  $item_rate = $item_details['itemRate'];
                  $exp_date = $item_details['expDate'];
              ?>
                  <tr class="text-right font12">
                    <td><?php echo $cntr ?></td>
                    <td class="text-left"><?php echo $item_name ?></td>
                    <td><?php echo $item_code ?></td>
                    <td class="text-bold"><?php echo $batch_no ?></td>
                    <td class="text-right"><?php echo number_format($ava_qty,2) ?></td>
                    <td class="text-right"><?php echo number_format($item_rate,2) ?></td>
                    <td class="text-right"><?php echo $exp_date ?></td>
                    <td>
                      <div class="btn-actions-group">
                        <a class="btn btn-danger" href="#" title="Recalculate Available Qty.">
                          <i class="fa fa-spinner"></i>
                        </a>
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