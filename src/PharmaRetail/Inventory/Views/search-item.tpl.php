<?php
  use Atawa\Utilities;

  if(isset($template_vars) && is_array($template_vars)) {
    extract($template_vars); 
  }
  
  if(isset($search_params['itemName']) && $search_params['itemName'] !='') {
    $medName = $search_params['itemName'];
  } else {
    $medName = '';
  } 
?>

<!-- Basic form starts -->
<div class="row">
  <div class="col-lg-12">
    
    <!-- Panel starts -->
    <section class="panel">
      <div class="panel-body">

        <?php echo Utilities::print_flash_message() ?>
          <div id="filters-form">
            <!-- Form starts -->
            <form class="form-validate form-horizontal" method="POST">
              <div class="form-group">
                <div class="col-sm-12 col-md-6 col-lg-6">
                  <label class="control-label">Type Medicine Name</label>
                  <input type="text" name="itemName" id="itemName" class="form-control inameAc" value="<?php echo $medName ?>">
                </div>
              <div class="col-sm-12 col-md-3 col-lg-3">
                 <label class="control-label">&nbsp;</label>
                  <button class="btn btn-success"><i class="fa fa-search"></i> Search</button>
                  <button type="reset" class="btn btn-warning" onclick="javascript:resetFilter('/inventory/search-medicines')"><i class="fa fa-refresh"></i> Reset </button>
              </div>
              </div>
            </form>
            <!-- Form ends -->
          </div>
        <?php 
          if(count($item_details)>0): 
            $item_name = $item_details['itemDetails']['itemName'];
            $units_per_pack = $item_details['itemDetails']['unitsPerPack'];
            $status = $item_details['itemDetails']['itemStatus'];
            $mrp = $item_details['itemDetails']['mrp'];
            $mfg_name = (!is_null($item_details['itemDetails']['mfgName'])?$item_details['itemDetails']['mfgName']:'');
            $comp = (!is_null($item_details['itemDetails']['compName'])?$item_details['itemDetails']['compName']:'');
            $category = (!is_null($item_details['itemDetails']['catName'])?$item_details['itemDetails']['catName']:'');
            if((int)$item_details['itemDetails']['itemStatus']===1) {
              $status = 'Active';
            } else {
              $status = 'Inactive';
            }
        ?>
          <h2 class="hdg-reports text-center">Medicine Details</h2>
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th width="30%" class="text-center">Item Name</th>
                  <th width="5%" class="text-center">Units Per<br />Pack</th>
                  <th width="10%" class="text-center">MRP</th>
                  <th width="15%" class="text-center">Manufacturer<br />Name</th>
                  <th width="15%" class="text-center">Composition</th>
                  <th width="10%" class="text-center">Category</th>
                  <th width="10%" class="text-center">Status</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td><?php echo $item_name ?></td>
                  <td class="text-right"><?php echo $units_per_pack ?></td>
                  <td class="text-right"><?php echo number_format($mrp,2) ?></td>
                  <td><?php echo $mfg_name ?></td>
                  <td><?php echo $comp ?></td>
                  <td><?php echo $category ?></td>
                  <td class="text-right"><?php echo $status ?></td>
                </tr>
                <tr>
                  <td colspan="7">
                      <table class="table table-bordered">
                        <thead>
                          <tr>
                            <th width="20%" class="text-center">Batch No.</th>
                            <th width="20%" class="text-center">Available Qty.</th>
                            <th width="20%" class="text-center">Rate</th>
                            <th width="20%" class="text-center">Expiry Date</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                            $tot_ava_qty = 0;
                            foreach($item_details['batches'] as $key => $batch_details):
                              $batch_no = $item_details['batches'][$key]['batchNo'];
                              $ava_qty = $item_details['batches'][$key]['availableQty'];
                              $item_rate = $item_details['batches'][$key]['itemRate'];
                              $exp_date =  $item_details['batches'][$key]['expDate'];
                              $tot_ava_qty += $ava_qty;
                          ?>
                            <tr>
                              <td class="text-right"><?php echo $batch_no ?></td>
                              <td class="text-right"><?php echo number_format($ava_qty,2) ?></td>
                              <td class="text-right"><?php echo number_format($item_rate,2) ?></td>
                              <td class="text-right"><?php echo $exp_date ?></td>
                            </tr>
                          <?php endforeach; ?>
                            <tr>
                              <td class="text-right">Total Available Qty.</td>
                              <td class="text-right"><?php echo number_format($tot_ava_qty,2) ?></td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                            </tr>
                        </tbody>
                      </table>
                  </td>
                </tr>
              </tbody>
            </table>            
          </div>
        <?php endif; ?>
      </div>
    </section>
    <!-- Panel ends -->
  </div>
</div>