<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="utf-8">
    <title>Q10 Tool</title>
    <link rel="stylesheet" type="text/css" href="App/Views/assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="App/Views/assets/css/main.css">
    <style>
      .loader {
          border: 16px solid #f3f3f3;
          border-top: 8px solid #3498db;
          border-radius: 50%;
          width: 50px;
          height: 50px;
          animation: spin 2s linear infinite;
      }

      .loadermini {
          border: 4px solid #f3f3f3;
          border-top: 3px solid #3498db;
          border-radius: 50%;
          width: 25px;
          height: 25px;
          animation: spin 1s linear infinite;
      }

      @keyframes spin {
          0% { transform: rotate(0deg); }
          100% { transform: rotate(360deg); }
      }
    </style>
    </head>
<body>
    <div class="container">
       <div class="row">
          <div class="col-lg-12">
             <h1 class="page-header" style="color: black !important;">
                Q10 Tool
             </h1>
          </div>
       </div>
       <!-- /.row -->
       <div class="row">
          <div class="col-lg-6">
             <div class="panel panel-default">
                <div class="panel-heading">
                   <h3 class="panel-title"><i class="fa fa-clock-o fa-fw"></i> Setup</h3>
                </div>
                <div class="panel-body">
                   <form method="getf" action="" id="import_form" role="form">
                      <input type="hidden" name="action" value="tool_start">
                      <fieldset id="form_filedset">
                         <div class="form-group">
                              <label>Store Url</label>
                              <input type="text" name="store_url" class="form-control">
                         </div>
                         <button id="start" type="submit" name="start" class="btn btn-default" onclick="getAjax(); return false;">GET DATA</button>

                         <!-- <button type="button" onclick="window.location.reload()" id="continue" class="btn btn-danger">CONTINUE GET DATA</button> -->
                      </fieldset>
                   </form>
                </div>
             </div>
          </div>

          <!-- <div class="col-lg-6">
             <div class="panel panel-default">
                <div class="panel-heading">
                   <h3 class="panel-title"><i class="fa fa-clock-o fa-fw"></i><span class="txt_process"> Processing</span><span class="loading-ajax"></span></h3>
                </div>
                <div id="div_result" class="panel-body">

                    All Done !<br>All done ! Showing seller info again.<br>Start count discount product at page 3<br>Start count discount product at page 2<br>Start count discount product at page 1<br>Got product reviews at page 1<br>Start get product reviews<br>Got seller reviews at page 1 and review type positive<br>Getting reviews for Seller<br>Get basic info done! Showing data now...<br>Getting Data... <br><br>

                </div>
             </div>
          </div> -->
       </div>

       <div class="row" id="ajax_put_block">

       </div>

    <script src="App/Views/assets/vendor/jquery/jquery.min.js"></script>
    <script src="App/Views/assets/js/main.js"></script>

  </body>
</html>

<?php //echo "<pre>"; print_r($data); die(); ?>