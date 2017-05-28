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
    <button type="button" class="btn btn-primary btn-lg topcorner" data-toggle="modal" data-target="#myModal">
      View
    </button>
    <!-- Begin page content -->
    <div class="container">
      <div class="page-header">
        <h1><a href="{{ url('/')}}" title="2D Barcode Generator Home">2D Barcode Generator</a></h1>
      </div>

      <p class="lead">Select ASINS (and quantity ) that should go into each box.</p>
      <div class="progress">
        <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
          0%
        </div>
      </div>
      <!-- Button trigger modal -->

      <hr>



    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h4 class="modal-title" id="myModalLabel">Modal title</h4>
          </div>
          <div class="modal-body">
            <table class="table table-stripped table-bordered">
                <thead>
                    <th>Boxes</th>
                    <th>2d_Barcode</th>
                </thead>
                <tbody>
                  @for($i = 0; $i < $boxes; $i++)
                    <tr>
                        <td>Box {{ $i+1 }}</td>
                        <td id="barcode_box_{{ $i+1 }}"></td>
                    </tr>
                  @endfor
                </tbody>
            </table>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Save changes</button>
          </div>
        </div>
      </div>
    </div>

    <div class="row" id="boxes_row" po="{{ $data['header']['shipment_id'] }}" totalskus="{{ $data['header']['total_skus'] }}" totalunits="{{ $data['header']['total_units'] }}">


        @for($i = 0; $i < $boxes; $i++)
          <div class="col-md-6" id="box{{ $i }}">
            <div class="panel panel-default">
              <div class="panel-heading">Box {{ $i+1 }}</div>
              <div class="panel-body">
                  <div class="row" id="box{{ $i+1 }}">
                    @foreach($data["skus"] as $sku)

                          <div class="col-md-6">
                            <div class="checkbox">
                              <label>
                                <input type="checkbox" class="{{ $sku[2] }}" id="box{{ $i+1 . $sku[2] }}" asin="{{ $sku[2] }}" qty="{{ $sku[9] }}" box="{{ $i+1 }}" totalboxes="{{ $boxes }}"> {{ $sku[2] }}
                              </label>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group">
                              <input type="number" class="form-control {{ $sku[2] }}" placeholder="quantity max: {{ $sku[9] }}" name="quantity" max="{{ $sku[9] }}" min="1">
                            </div>
                          </div>

                    @endforeach
                    </div>
              </div>
            </div>
          </div>
        @endfor

      </div>


        <!-- <form method="post" action="{{ url('packingList') }}" enctype="multipart/form-data">

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
      </form> -->
    </div>
    <footer class="footer">

      <div class="container">
        <p class="text-muted">2D Barcode Generator</p>
      </div>
    </footer>


    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="js/app.js"></script>
    <script>

      $(document).ready(function(){

        $("input:checkbox").change(function(){

            if($(this).is( ":checked" ) == false){
              $("div.row#box" + $(this).attr("box")).find("input.form-control." + $(this).attr('asin')).val("");
            }

            if($(this).is( ":checked" ) == true){
              qty = $("div.row#box" + $(this).attr("box")).find("input.form-control." + $(this).attr('asin')).val();
              if(qty == "" || qty == 0 || isNaN(qty)){
                $(this).prop("checked", false);
                alert("provide a value for quatity first");
              }
            }

            //to hold total quantities of asins asigned to a box
            total_qty = 0;

            //to hold maximum quantities of asins that can be asigned to box(es)
            max_qty = parseInt($(this).attr('qty'));
            max_qty = isNaN(max_qty) ? 0 : parseInt(max_qty);

            //to hold current quantity of asins that is to be asigned to the current box
            qty = $("div.row#box" + $(this).attr("box")).find("input.form-control." + $(this).attr('asin')).val();
            qty = qty == "" ? 0 : parseInt(qty);

            //sum all quatity of asins already assigned to box(es)
            for(var i = 1; i <= parseInt($(this).attr("totalboxes")); i++){
              hold_qty = parseInt($("div.row#box" + i).find("input.form-control." + $(this).attr('asin')).val());
              total_qty += isNaN(hold_qty) ? 0 : hold_qty;
            }

            //alert(total_qty);


            if(max_qty == qty){
              //deactivate other checbox and input type number with the same asins in all boxes

              for(var i = 1; i <= parseInt($(this).attr("totalboxes")); i++){
                hold_qty = parseInt($("div.row#box" + i).find("input.form-control." + $(this).attr('asin')).val());
                if(isNaN(hold_qty) || hold_qty == 0 || hold_qty == ""){
                  $("div.row#box" + i).find("input.form-control." + $(this).attr('asin')).attr("disabled", "disabled");
                  $("div.row#box" + i).find("input:checkbox#box" + i + $(this).attr('asin')).attr("disabled", "disabled");
                }
              }

            }else if(max_qty == total_qty){

              for(var i = 1; i <= parseInt($(this).attr("totalboxes")); i++){
                hold_qty = parseInt($("div.row#box" + i).find("input.form-control." + $(this).attr('asin')).val());
                if(isNaN(hold_qty) || hold_qty == 0 || hold_qty == ""){
                  $("div.row#box" + i).find("input.form-control." + $(this).attr('asin')).attr("disabled", "disabled");
                  $("div.row#box" + i).find("input:checkbox#box" + i + $(this).attr('asin')).attr("disabled", "disabled");
                }
              }

            }else{
              //update other input number maximum to the availaible quantities of the asins

              for(var i = 1; i <= parseInt($(this).attr("totalboxes")); i++){

                  $("div.row#box" + i).find("input.form-control." + $(this).attr('asin')).attr("max", (max_qty - total_qty)).attr("placeholder", "quantity max: " + (max_qty - total_qty)).removeAttr("disabled");
                  $("div.row#box" + i).find("input:checkbox#box" + i + $(this).attr('asin')).removeAttr("disabled");

              }
            }
            total_t = 0;


              $("div.row#boxes_row").find("input[type='number']").each(function(){
                  total_t += (isNaN($(this).val()) || $(this).val() == "") ? 0 : parseInt($(this).val());
              });


            total_units = parseInt($("div.row#boxes_row").attr('totalunits'));
            percent_done = Math.round((total_t/total_units) * 100);

            $("div.progress div.progress-bar").text(percent_done + "%");
            $("div.progress div.progress-bar").attr("style", "width: " + percent_done + "%");
            $("div.progress div.progress-bar").attr("aria-valuenow", percent_done);

            if(percent_done == 100){
              $('#myModal').modal('show');
            }


            for(var i = 1; i <= parseInt($(this).attr("totalboxes")); i++){
              hold_barcode = "AMZN,PO:" + $("div.row#boxes_row").attr('po');
                $("div.row#box" + i).find("input:checkbox").each(function(){
                  if($(this).is(":checked")){
                      hold_barcode = hold_barcode.concat(",ASIN:" + $(this).attr('asin') + ",QTY:" + $("div.row#box" + $(this).attr("box")).find("input.form-control." + $(this).attr('asin')).val());
                  }
                });

                $("table tbody tr td#barcode_box_" + i).text(hold_barcode);
                //$("div.row#box" + i).find("input.form-control." + $(this).attr('asin')).attr("max", (max_qty - total_qty)).attr("placeholder", "quantity max: " + (max_qty - total_qty)).removeAttr("disabled");


            }

        });

      });

    </script>

  </body>
</html>
