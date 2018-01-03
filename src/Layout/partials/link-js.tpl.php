<?php if(isset($_SERVER['appEnvironment']) && $_SERVER['appEnvironment'] === 'prod') : ?>

<script src="/assets/js/medqwik.min.js?<?php echo mt_rand() ?>"></script>

<?php else : ?>

<script src="/assets/js/jquery.js"></script> 
<script src="/assets/js/jquery-ui-1.10.4.min.js"></script> 
<script src="/assets/js/bootstrap.min.js"></script> 

<script src="/assets/js/jquery.scrollTo.min.js"></script> 
<script src="/assets/js/jquery.nicescroll.js" type="text/javascript"></script> 

<script src="/assets/js/bootbox.min.js"></script>
<script src="/assets/js/jauto/jquery-migrate-1.0.0.js"></script>

<script src="/assets/js/jqplot1.0.9/jquery.jqplot.min.js"></script>
<script src="/assets/js/jqplot1.0.9/plugins/jqplot.barRenderer.js"></script>
<script src="/assets/js/jqplot1.0.9/plugins/jqplot.categoryAxisRenderer.js"></script>
<script src="/assets/js/jqplot1.0.9/plugins/jqplot.dateAxisRenderer.js"></script>
<script src="/assets/js/jqplot1.0.9/plugins/jqplot.pieRenderer.js"></script>
<script src="/assets/js/jqplot1.0.9/plugins/jqplot.pointLabels.js"></script>

<script src="/assets/js/jquerymask/jquery.inputmask.bundle.js"></script>

<script src="/assets/js/jquery.tabledit.min.js"></script>

<script src="/assets/js/jquery.floatThead.min.js"></script>

<script src="/assets/js/scripts.js"></script>

<script src="/assets/datetime/datepicker/js/bootstrap-datepicker.js"></script> 
<script src="/assets/datetime/timepicker/js/bootstrap-timepicker.js"></script>

<!--[if lt IE 9]><script src="/assets/js/jqplot1.0.9/excanvas.js"></script><![endif]-->

<?php endif; ?>

<!--load Autocomplete plugin -->
<script src="/assets/js/jauto/jquery.autocomplete.js"></script>
