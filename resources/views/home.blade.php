<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">

    <title>AMZ 2D barcode generator</title>

    <!-- App css -->
    <link href="css/app.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/sticky-footer.css" rel="stylesheet">

  </head>

  <body>

    <!-- Begin page content -->
    <div class="container">
      <div class="page-header">
        <h1>2D Barcode Generator</h1>
      </div>
      <p class="lead">The 2d barcode string can be uploaded to a QR generator for amazon packing slips.</p>
      <p>Provide <a href="#"> Information</a> needed to generate required 2D_BARCODE string.</p>
      <hr>


        <form method="post" action="{{ url('packingList') }}" enctype="multipart/form-data">

          {{ csrf_field() }}
          
          <div id='fill-step-1' class="form-group">
            <label for="boxes-in-shipment">How many boxes are in the shipment?</label>
            <input type="number" class="form-control" id="boxes-in-shipment" placeholder="How many boxes are in the shipment?" name="boxes_in_shipment">
          </div>

          <div id='fill-step-2'>
            <label for="amz-packing-list">Upload AMZ Packing list</label>
            <input type="file" class="form-control" id="amz-packing-list" placeholder="Upload AMZ Packing list" name="packing_list">
          </div>


          <div class="button-next">
            <button class="btn btn-block" type="submit"> Continue</button>
          </div>
      </form>
    </div>
    <footer class="footer">

      <div class="container">
        <p class="text-muted">2D Barcode Generator</p>
      </div>
    </footer>


    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="js/app.js"></script>
  </body>
</html>
