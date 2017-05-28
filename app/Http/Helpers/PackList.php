<?php


function process_packlist_file($file){

  $count = 0;
  $header_line = [];
  $rows = [];

  while (!feof($file)) {
    $line = fgets($file);
    if($count < 7){
        $header_line[] = trim($line);
    }else{
      $rows[] = $line;
    }
    $count++;
  }

  $header = get_packlist_headers($header_line);
  $skus   = get_skus($rows);

  $data = ["header" => $header, "skus" => $skus];

  fclose($file);

  return $data;
}

function get_skus($rows){
  $skus = [];

  for($i = 2; $i<count($rows) - 1; $i++){
    $skus[] = explode("\t", trim($rows[$i]));
  }

  return $skus;

}

function get_packlist_headers($header_line){
  $header = [];

  $header["shipment_id"]  = array_last(explode("\t", $header_line[0]));
  $header["name"]         = array_last(explode("\t", $header_line[1]));
  $header["plan_id"]      = array_last(explode("\t", $header_line[2]));
  $header["ship_to"]      = trim(str_replace("Ship To",  ' ', $header_line[3]));
  $header["total_skus"]   = array_last(explode("\t", $header_line[4]));
  $header["total_units"]  = array_last(explode("\t", $header_line[5]));
  $header["pagination"]   = trim(str_replace('Pack list',  ' ', $header_line[6]));

  return $header;
}
