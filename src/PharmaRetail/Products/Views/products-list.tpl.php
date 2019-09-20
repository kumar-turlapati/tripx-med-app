<?php 

  $pagination_url = '/medicines/list';

  $query_params = [];  
  if(isset($search_params['medname']) && $search_params['medname'] !='') {
    $medname = $search_params['medname'];
    $query_params[] = 'medName='.$medname;
  } else {
    $medname = '';
  }
  if(isset($search_params['composition']) && $search_params['composition'] !='') {
    $composition = $search_params['composition'];
    $query_params[] = 'composition='.$composition;
  } else {
    $composition = '';
  }
  if(isset($search_params['category']) && $search_params['category'] !='' ) {
    $category = $search_params['category'];
    $query_params[] = 'category='.$category;
  } else {
    $category = '';
  }
  if(isset($search_params['mfg']) && $search_params['mfg'] !='' ) {
    $mfg = $search_params['mfg'];
    $query_params[] = 'mfg='.$mfg;
  } else {
    $mfg = '';
  }  

  if($query_params != '') {
    $query_params = '?'.implode('&', $query_params);
  }

  $page_url = '/medicines/list';
?>
<!-- Basic form starts -->
<div class="row">
  <div class="col-lg-12"> 
    
    <!-- Panel starts -->
    <section class="panelBox">
      <h2 class="hdg-reports text-center">Medicines List</h2>
      <div class="panelBody">
        
        <?php echo $flash_obj->print_flash_message(); ?>

        <?php if($page_error !== ''): ?>
          <div class="alert alert-danger" role="alert">
            <strong>Error!</strong> <?php echo $page_error ?> 
          </div>
        <?php endif; ?>

        <!-- Right links starts -->
        <div class="global-links actionButtons clearfix">
          <div class="pull-right text-right">
              <a href="/medicines/create" class="btn btn-default">
                <i class="fa fa-file-text-o"></i> New Medicine
              </a>
          </div>
        </div>
        <!-- Right links ends -->         

		<div class="filters-block">
			<div id="filters-form">
			  <!-- Form starts -->
			 <form class="form-validate form-horizontal" method="POST" action="<?php echo $page_url ?>">
				<div class="form-group">
          <div class="col-sm-12 col-md-1 col-lg-1 text-right">
					  <label class="control-label text-right"><b>Filter by</b></label>          
          </div>
				  <div class="col-sm-12 col-md-2 col-lg-2">
					 <input placeholder="Product name" type="text" name="medname" id="medname" class="form-control" value="<?php echo $medname ?>">
				  </div>
				  <div class="col-sm-12 col-md-2 col-lg-2">
					 <input placeholder="Manufacturer" type="text" name="mfg" id="mfg" class="form-control" value="<?php echo $mfg ?>">
				  </div>              
				  <div class="col-sm-12 col-md-2 col-lg-2">
					<div class="select-wrap">
						<select class="form-control" name="category" id="category">
						  <?php foreach($categories as $key=>$value): ?>
							<option value="<?php echo $key ?>"><?php echo $value ?></option>
						  <?php endforeach; ?>
						</select>
					  </div>
				  </div>
          <div class="col-sm-12 col-md-3 col-lg-3">                         
            <?php include_once __DIR__."/../../../Layout/helpers/filter-buttons.helper.php" ?>
          </div>
				</div>
			  </form>        
			  <!-- Form ends -->
			</div>
        </div>
   
        <div class="table-responsive">
          <table class="table table-striped table-hover" id="itemnames">
            <thead>
              <tr class="font12">
                <th width="5%" class="text-center">Sno.</th>
                <th width="4%" class="text-left">Product code</th>                
                <th width="30%" class="text-left">Product name</th>
                <th width="5%" class="text-center">Threshold<span class="brk">Qty.</span></th>                
                <th width="5%" class="text-center">Units /<span class="brk">pack</span></th>
                <th width="5%" class="text-center">M.R.P <span class="brk">(in Rs.)</span></th>
                <th width="20%" class="text-center">Manufacturer name</th>
              </tr>
            </thead>
            <tbody>
              <?php 
                $cntr = $sl_no;
                foreach($products as $product_details):
                //   dump($product_details);
                // exit;
                  if($product_details['mrp']>0) {
                    $mrp = $product_details['mrp'];
                  } else {
                    $mrp = '';
                  }
                  if($product_details['mfgName'] !== '') {
                    $mfgName = $product_details['mfgName'];
                  } else {
                    $mfgName = '';
                  }
                  if($product_details['compName'] !== '') {
                    $compName = $product_details['compName'];
                  } else {
                    $compName = '';
                  }
                  $thr_qty = $product_details['thrQty'];
                  $item_code = $product_details['itemCode'];
              ?>
                  <tr class="text-uppercase text-right font11">
                    <td class="text-center"><?php echo $cntr ?></td>
                    <td class="text-left"><?php echo $item_code ?></td>                    
                    <td class="text-left med-name">
                      <a 
                        href="/medicines/update/<?php echo $item_code ?>"
                        class="hyperlink"
                        title="click here to Edit medicine details"
                      >
                        <?php echo $product_details['itemName'] ?>
                      </a>
                    </td>
                    <td class="text-left"><?php echo $thr_qty ?></td>
                    <td><?php echo $product_details['unitsPerPack'] ?></td>
                    <td class="text-bold"><?php echo $mrp ?></td>
                    <td class="text-left"><?php echo $mfgName ?></td>
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
<!-- Basic Forms ends -->
