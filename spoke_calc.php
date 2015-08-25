<?php
// simple spoke length calculator based on Damon Rinard's
// spokecalc express excel spreadsheet
// when you're done just put it all into an iframe
// <iframe src="spoke_calc.php"></iframe>
// some error checking
ini_set('display_errors',1);
error_reporting(E_ALL & ~E_NOTICE);

$inc_path = $_SERVER['DOCUMENT_ROOT'].'/wheels/v4_inc';
//require_once ($inc_path.'/functions.php');


// form or results
if (isset($_POST['submit'])){

  // set all the variables and get $_POST submitted values
  $spoke_count = $_POST['spoke_count'];
  $erd = $_POST['erd'];
  $centre_to_flange = $_POST['centre_to_flange'];
  $flange_diameter = $_POST['flange_diameter'];
  $hole_size = $_POST['hole_size'];

  // to allow whole number integers to be entered we can convert to float
  // by multiplying by 1.0 which also keeps the value the same
  $centre_to_flange = $centre_to_flange * 1.0;
  $flange_diameter = $flange_diameter * 1.0;

  // define the cross numbers
  // it's extra code and memory
  // but helps to know where you are
  define('ZERO_CROSS', 0);
  define('ONE_CROSS', 1);
  define('TWO_CROSS', 2);
  define('THREE_CROSS', 3);
  define('FOUR_CROSS', 4);


  // the intermediate calculations
  // these are hidden columns on the spreadsheet
  $column_c = $centre_to_flange;

  $column_a0 = $flange_diameter/2*sin(2*M_PI*ZERO_CROSS/($spoke_count/2));
  $column_a1 = $flange_diameter/2*sin(2*M_PI*ONE_CROSS/($spoke_count/2));
  $column_a2 = $flange_diameter/2*sin(2*M_PI*TWO_CROSS/($spoke_count/2));
  $column_a3 = $flange_diameter/2*sin(2*M_PI*THREE_CROSS/($spoke_count/2));
  $column_a4 = $flange_diameter/2*sin(2*M_PI*FOUR_CROSS/($spoke_count/2));

  $column_b0 = $erd/2-(($flange_diameter/2)*cos(2*M_PI*ZERO_CROSS/($spoke_count/2)));
  $column_b1 = $erd/2-(($flange_diameter/2)*cos(2*M_PI*ONE_CROSS/($spoke_count/2)));
  $column_b2 = $erd/2-(($flange_diameter/2)*cos(2*M_PI*TWO_CROSS/($spoke_count/2)));
  $column_b3 = $erd/2-(($flange_diameter/2)*cos(2*M_PI*THREE_CROSS/($spoke_count/2)));
  $column_b4 = $erd/2-(($flange_diameter/2)*cos(2*M_PI*FOUR_CROSS/($spoke_count/2)));

  // the actual spoke lengths
  $length_0 = sqrt(pow($column_a0,2)+pow($column_b0,2)+pow($column_c,2))-($hole_size/2);
  $length_1 = sqrt(pow($column_a1,2)+pow($column_b1,2)+pow($column_c,2))-($hole_size/2);
  $length_2 = sqrt(pow($column_a2,2)+pow($column_b2,2)+pow($column_c,2))-($hole_size/2);
  $length_3 = sqrt(pow($column_a3,2)+pow($column_b3,2)+pow($column_c,2))-($hole_size/2);
  $length_4 = sqrt(pow($column_a4,2)+pow($column_b4,2)+pow($column_c,2))-($hole_size/2);


  // get each length to one decimal place
  $length_0 = number_format($length_0,1);
  $length_1 = number_format($length_1,1);
  $length_2 = number_format($length_2,1);
  $length_3 = number_format($length_3,1);
  $length_4 = number_format($length_4,1);


  // now to print it all out - first the results

  print"
<style type='text/css'>
  #your_quote{width:160px;font-family:Georgia,Times,serif;line-height:1.4em;}
  #your_quote h2,span{color:#666;}
  #your_quote li, #your_quote ul{list-style-type:none;margin-left:0;padding-left:0;}
  #your_quote a{color:#333;text-decoration:none;font-weight:bold;}
  #your_quote a:hover{border-bottom:2px solid;padding-bottom:3px;}
  span{font-style:italic;font-size:0.9em;}
</style>
<div id=\"your_quote\">
  <h2>Your Lengths</h2>

  <ul>
    <li><span>Radial</span></li>
    <li>$length_0 mm</li>
    <li>&nbsp;</li>
    <li><span>One cross</span></li>
    <li>$length_1 mm</li>
    <li>&nbsp;</li>
    <li><span>Two cross</span></li>
    <li>$length_2 mm</li>
    <li>&nbsp;</li>
    <li><span>Three cross</span></li>
    <li>$length_3 mm</li>
    <li>&nbsp;</li>
    <li><span>Four cross</span></li>
    <li>$length_4 mm</li>
    <li>&nbsp;</li>
  </ul>

  <a href=\"javascript: history.go(-1)\"><- back</a>
<div>";

} else {//print out the form
  print'
  <style type="text/css">
    #quote{width:160px;font-family:Georgia,Times, serif;}
    #quote h2 {color:#666;font-variant:small-caps;font-size:1.2em;border-bottom:1px solid #999;padding-bottom:2px;}
    #quote span{font-size:0.7em;}
    form p{margin-bottom:1px;color:#666;font-size:1em;}
    form select{width:155px;font-size:60%;}
    form input{width:155px;text-align:center;margin-top:3px;}
  </style>

<div id="quote">
<script type="text/javascript" charset="utf-8">
  function validateForm()
  {
  var x=document.forms["spokeme"]["spoke_count"].value;
  var y=document.forms["spokeme"]["erd"].value;
  var z=document.forms["spokeme"]["centre_to_flange"].value;
  var a=document.forms["spokeme"]["flange_diameter"].value;
  var b=document.forms["spokeme"]["hole_size"].value;

  var decimal =/^[0-9]+(\.[0-9]+)+$/;
  var number =/^[0-9]+$/;
  var isEmpty = function(p) {
    return p === null || p === "" || p === undefined;
  }
  var isNumber = function(n) {
    return n.match(number);
  }

  // spoke count field - x
  // is the field empty
  if (isEmpty(x)) {
    alert("You need to enter the no. of spokes");
    return false;
    }
  // is it a non numeric or decimal
  if (!isNumber(x)){
    alert("You need to enter a numeric whole number value for spoke count\nNo decimal numbers as there are no fractions of holes");
    return false;
  }
  // is it an odd number
  if (x % 2 != 0){
    alert("You\'ve entered an odd number for No. of spokes, should be even");
    return false;
  }

  // ERD field - y
  // is the field empty
  if (isEmpty(y)) {
    alert("You need to enter the Effective Rim Diameter");
    return false;
    }
  // is it non numeric or decimal
  if (!isNumber(y)){

    alert("You need to enter a numeric whole number ERD value\nE.g. Mavic Open Pro - 604 mm");
    return false;
  }

  // Centre to flange field - z
  // is the field empty
  if (isEmpty(z)) {
    alert("You need to enter the centre to flange");
    return false;
    }
  // allow both whole numbers and decimals
  if (!z.match(decimal) && !isNumber(z)){

    alert("You need to enter a number for the Centre to Flange value\nWhole numbers or decimals are allowed\nE.g. Zenith Track Front - 34.3 mm");
    return false;
  }

  // Flange Diameter field - a
  // is the field empty
  if (isEmpty(a)) {
    alert("You need to enter the flange diameter");
    return false;
    }
  // allow both whole numbers and decimals
  if (!a.match(decimal) && !isNumber(a)){

    alert("You need to enter a number for the Flange Diameter value\nWhole numbers or decimals are allowed\nE.g. Zenith Track - 62 mm");
    return false;
  }

  // Hub hole size field - b
  // is the field empty
  if (isEmpty(b)) {
    alert("You need to enter the hub hole size");
    return false;
    }
  // allow only decimal numbers
  if (!b.match(decimal)){

    alert("You need to enter a decimal number for the hub hole size\nTypically 2.3 - 2.9 mm");
    return false;
  }
  }
</script>
<h2>Spoke Length <br /> Calculator <span>1.3</span></h2>
<p>Arup\'s spoke length calculator based on Damon Rinard\'s SpocalcExpress.xls.<br /><br />Calculates only one side.<br /><br />All values in mm.</p>
<form name="spokeme" method="post" action="spoke_calc.php" onsubmit="return validateForm()">

  <p>No. of spokes</p>
  <input type="number" name="spoke_count" value="" id="spoke_count" min="16" step="2" />

  <p>Effective Rim Diameter</p>
  <input type="number" name="erd" value="" id="erd" />

  <p>Centre to Flange</p>
  <input type="number" name="centre_to_flange" value="" id="centre_to_flange" />

  <p>Flange Diameter or <abbr title="Pitch Circle Diameter">PCD</abbr></p>
  <input type="number" name="flange_diameter" value="" id="flange_diameter" />

  <p>Spoke hole diameter</p>
  <input type="number" name="hole_size" value="" id="hole_size" min="2.0" step="0.1" />

  <br /><br />

  <input name="submit" value="Calculate!" type="submit" />
</form>
</div>';
} ?>
