<?php 

  $query_params = '';  
  if(isset($search_params['catname']) && $search_params['catname'] !='') {
    $catname = $search_params['catname'];
    $query_params[] = 'catName='.$catname;
  } else {
    $catname = '';
  } 

  if($query_params != '') {
    $query_params = '?'.implode('&', $query_params);
  }
?>
<!-- Basic form starts -->
<div class="row">
  <div class="col-lg-12"> 
    
    <!-- Panel starts -->
    <section class="panelBox">
      <div class="panelBody">

        <?php if($page_error !== ''): ?>
          <div class="alert alert-danger" role="alert">
            <strong>Error!</strong> <?php echo $page_error ?> 
          </div>
        <?php endif; ?>

        <h2 class="hdg-reports text-center">Categories with Item Count</h2>
        <div class="table-responsive">
          <table class="table table-striped table-hover font12">
            <thead>
              <tr>
                <th width="5%" class="text-center">Sno.</th>
                <th width="40%">Category name</th>
                <th width="5%">Category code</th>
                <th width="5%" class="text-center">Total items</th>
              </tr>
            </thead>
            <tbody>
            <?php if(count($categories)>1): ?>
                <?php 
                  $cntr = $sl_no;
                  $total_item_count = 0;
                  foreach($categories as $category_details):
                    $total_item_count += $category_details['totalItems'];
                ?>
                    <tr class="text-uppercase text-right font12">
                      <td align="center"><?php echo $cntr ?></td>
                      <td class="text-left med-name"><?php echo $category_details['categoryName'] ?></td>
                      <td class="text-bold text-left"><?php echo $category_details['categoryCode'] ?></td>
                      <td class="text-right"><?php echo $category_details['totalItems'] ?></td>
                    </tr>
              <?php
                $cntr++;
                endforeach; 
              ?>
                <tr>
                  <td colspan="3" class="text-bold text-right" style="font-size:17px;">Total Items</td>
                  <td class="text-bold text-right" style="font-size:17px;"><?php echo number_format($total_item_count) ?></td>
                </tr>
            <?php else: ?>
                <tr><td colspan="4" align="center" class="font14">No data is available.</td></tr>
            <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </section>
    <!-- Panel ends --> 
  </div>
</div>
<!-- Basic Forms ends -->