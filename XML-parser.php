<?php

$name = "";
$value = "";
$xml_arr = NULL;

function xml_decode($data) {
	  GLOBAL $xml_arr;

    $parser=xml_parser_create();
	
		xml_set_element_handler($parser,"start","stop");
		xml_set_character_data_handler($parser,"char");

	  xml_parse($parser,$data) or 
	  die (sprintf("XML Error: %s at line %d", 
	  xml_error_string(xml_get_error_code($parser)),
	  xml_get_current_line_number($parser)));

		xml_parser_free($parser);
		
		return $xml_arr;
}

function start($parser,$element_name,$element_attrs) {
	GLOBAL $name;
	
	$name = $element_name;
  }

function stop($parser,$element_name) {
  GLOBAL $xml_arr, $name, $value;
  
  $test = trim($value);
  if (!empty($test)) {
			$xml_arr[$name] = $value;
	}
	 
}

function char($parser,$data) {
	GLOBAL $value;
	
  $value = $data;
  }

?>
