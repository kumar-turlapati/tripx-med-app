<!-- container section start -->
<?php 
  if(isset($_SESSION['uname']) && $_SESSION['uname'] !== '') {
    $uname = substr($_SESSION['uname'],0,10);
    $logo_url = '/';
  } else {
    $uname = 'My Profile';
    $logo_url = 'https://www.medqwik.com/';
  }
?>
<nav class="navbar navbar-default">
<section id="container" class="">
  <header class="header dark-bg"> 
      <!-- Brand and toggle get grouped for better mobile display -->
      <div class="navbar-header">
        <span type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#user-status" aria-expanded="false"><i class="fa fa-bars"></i></span>
        <!--logo start--> 
        <a href="<?php echo $logo_url ?>" class="logo">
          <img src="/assets/img/logo.png" alt="MedQwik" /> 
        </a>
        <!--logo end-->
      </div>
      
      <!-- Collect the nav links, forms, and other content for toggling -->
    	<div class="collapse navbar-collapse" id="user-status">
      	<div class="top-nav notification-row">
          <?php if( isset($_SESSION['token_valid']) && $_SESSION['token_valid'] ): ?>
            <div class="pull-right last-seen">
              <?php echo date("dS F, Y | h:ia").' (IST)'; ?>
            </div>
            <ul class="nav pull-right top-menu">
              <!-- Helpline number start -->
              <li class="dropdown"> <i class="fa fa-info-circle"></i><a data-toggle="dropdown" class="dropdown-toggle" href="#"> <span class="profile-ava"></span> <span class="username">Helpline</span> <b class="caret"></b> </a>
                <ul class="dropdown-menu extended">
                  <div class="log-arrow-up"></div>
                  <li class="eborder-top ff-contact red"><b>Feel free to contact us:</b></li>
                  <li> <a href="#"><i class="fa fa-phone"></i> 91 90003 77973</a> </li>
                  <li> <a href="mailto:support@atawa.com"><i class="fa fa-envelope"></i> support@atawa.net</a> </li>
                </ul>
              </li>
              <!-- user login dropdown start-->
              <li id="user-info" class="dropdown"> 
                <i class="fa fa-user"></i>
                <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                  <span class="profile-ava"></span> <span class="username"><?php echo $uname ?></span>
                  <b class="caret"></b>
                </a>
                <ul class="dropdown-menu extended logout">
                  <div class="log-arrow-up"></div>
                  <li> <a href="/me"><i class="icon_clock_alt"></i> Edit My Account</a> </li>
                  <li> <a href="/logout"><i class="icon_key_alt"></i> Logout</a> </li>
                </ul>
              </li>
              <!-- user login dropdown end -->
            </ul>
            <!-- notification dropdown end--> 
          <?php endif; ?>
      </div>
      </div>
      <!-- Theme Name Starts -->
      <div class="theme-name">
      	<h1><?php echo (isset($_SESSION['cname'])&&$_SESSION['cname']!=''?$_SESSION['cname']:'') ?></h1>
      </div>
      <!-- Theme Name Ends -->
  </header>
  <!--header end-->
  <!--Header & breadcrumb start-->
  <?php if( isset($_SESSION['token_valid']) && $_SESSION['token_valid'] && $show_page_name ): ?>
    <div class="pageHeader">
      <h3 class="page-header">
        <i class="<?php echo (isset($icon_name) && $icon_name != '' ? $icon_name : '') ?>"></i> 
        <?php echo (isset($page_title) && $page_title != '' ? $page_title : '') ?>
      </h3>
    </div>
  <?php endif; ?> 
</section>
</nav>
<!-- container section start --> 
