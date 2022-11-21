    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        500 Error Page
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">500 error</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="error-page">
        <h2 class="headline text-red"> 500</h2>

        <div class="error-content"><br><br>
          <h3><i class="fa fa-warning text-red"></i> Oops! We've encountered an error...</h3>
          <p>
            Something went wrong here. Try again.<br>
              <strong><?php echo isset($msg) ? $msg : '';?></strong><br><br>
            Meanwhile, you may <a href="<?php echo URL;?>">return to home</a>.
          </p>
        </div>
        <!-- /.error-content -->
      </div>
      <!-- /.error-page -->
    </section>
    <!-- /.content -->