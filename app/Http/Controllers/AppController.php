<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AppController extends Controller
{
    public function packingList(Request $request){

      $file = fopen($request->packing_list, "r");

      $data = process_packlist_file($file);

      $boxes = $request->boxes_in_shipment;
      return view('asins', compact("data", "boxes"));

    }


}
