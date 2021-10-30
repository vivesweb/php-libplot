<?php
 /** example.php // example file for php-plotlib.class.php
  *  
  * 
  * @author Rafael Martin Soto
  * @author {@link https://www.inatica.com/ Inatica}
  * @blog {@link https://rafamartin10.blogspot.com/ Blog Rafael Martin Soto}
  * @since October 2021
  * @version 1.0.0
  * @license GNU General Public License v3.0
  *
  * */

require __DIR__ . '/php-plotlib.class.php';
?><!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Example PHP-PlotLib</title>
  <meta name="description" content="Example PHP-PlotLib">
  <meta name="author" content="SitePoint">

  <meta property="og:title" content="Example PHP-PlotLib">
  <meta property="og:type" content="website">
  <meta property="og:url" content="https://www.inatica.com">
  <meta property="og:description" content="Example PHP-PlotLib">

</head>
<body>
<?php

$plt = new php_plotlib();
$plt->plot( [1, 1.5, 2, 1.8, 3] );
$plt->plot( [2, 2.8, 1.7, 2, 2.3] );
$plt->title("Multi Line");
?>

  <img src="<?php echo $plt->output_gd_png_base64( );?>">
  <?php
  
unset($plt);




$plt = new php_plotlib();
$x = $plt->math->linspace( 0, 2, 50 );
$plt->plot( $x, $x, ['label'=>'linear'] );
$plt->plot( $x, $plt->math->pow($x, 2), ['label'=>'quadratic'] );
$plt->plot( $x, $plt->math->pow($x, 3), ['label'=>'cubic'] );
$plt->xlabel('x label');
$plt->ylabel('y label');
$plt->title("Simple Plot. With Legend & Labels X, Y");
$plt->legend( );
?>

  <img src="<?php echo $plt->output_gd_png_base64( );?>">
  <?php
  
unset($plt);


$plt = new php_plotlib();

$plt->bar( [1, 2, 3, 4], [10, 9, 10, 8] );
$plt->bar( [1, 2, 3, 4], [8, 6, 9, 7] );
$plt->bar( [1, 2, 3, 4], [6, 5, 7, 5] );
$plt->bar( [1, 2, 3, 4], [3, 3, 4, 2] );
$plt->axes([0, 6, 0, 20]);
$plt->title( 'Multi Bar & fixed Axis Values' );
?>

  <img src="<?php echo $plt->output_gd_png_base64( );?>">
  <?php
  
unset($plt);



$plt = new php_plotlib();

$plt->bar( [1, 2, 3, 4, 5, 6, 7], [1, 4, 9, 16, 17, 18, 17] );
$plt->plot( [1, 2, 3, 4, 5, 6, 7], [10,8, 5, 10,15, 16, 15] );
$plt->title( 'Bar & Line' );
?>

  <img src="<?php echo $plt->output_gd_png_base64( );?>">
  <?php
  
unset($plt);



$plt = new php_plotlib();
$x = $plt->math->linspace( 0, 5, 20 );
$plt->plot( [ [$x, $x, 'r--'], [$x, $plt->math->pow($x, 2), 'bs'], [$x, $plt->math->pow($x, 3), 'g^'] ] );
$plt->title( 'Colors, disctont. line & markers "--", "square", "^"' );
?>

  <img src="<?php echo $plt->output_gd_png_base64( );?>">
  <?php
  
unset($plt);


$plt = new php_plotlib();

$arr_values = [
    [ [1, 1.5, 2, 2, 3, 4], [10, 9.5, 9, 10, 8, 9] ],
    [ [4, 5, 5.7, 6, 7, 8], [8, 6, 7.3, 8, 7, 8] ],
];

$plt->title( 'Scatter' );
$plt->scatter( $arr_values );
?>

  <img src="<?php echo $plt->output_gd_png_base64( );?>">
  <?php
  
unset($plt);


$plt = new php_plotlib();
$plt->subplots( 2 );
$plt->subplots[0]->bar( [1, 2, 3, 4] );
$plt->subplots[1]->bar( [10, 20, 30, 40] );
?>

  <img src="<?php echo $plt->subplots[0]->output_gd_png_base64( );?>">
  <br />
  <img src="<?php echo $plt->subplots[1]->output_gd_png_base64( );?>">
  <?php
  
unset($plt);
print "<br />";

$plt = new php_plotlib();
$plt->subplots( 2, 2 );
$plt->subplots[0][0]->bar( [1, 2, 3, 4] );
$plt->subplots[0][1]->bar( [10, 20, 30, 40] );
$plt->subplots[1][0]->bar( [100, 200, 300, 400] );
$plt->subplots[1][1]->bar( [11, 12, 13, 14] );
?>

<img src="<?php echo $plt->subplots[0][0]->output_gd_png_base64( );?>">
  <img src="<?php echo $plt->subplots[0][1]->output_gd_png_base64( );?>">
  <br />
  <img src="<?php echo $plt->subplots[1][0]->output_gd_png_base64( );?>">
  <img src="<?php echo $plt->subplots[1][1]->output_gd_png_base64( );?>">
  <?php
  
unset($plt);
print "<br />";

$plt = new php_plotlib();
$plt->subplots( 2, 2, ['figsize'=>[9,6], 'bordertype'=>'halfsquare'] );
$plt->subplots[0][0]->bar( [1, 2, 3, 4] );
$plt->subplots[0][1]->bar( [10, 20, 30, 40] );
$plt->subplots[1][0]->bar( [100, 200, 300, 400] );
$plt->subplots[1][1]->bar( [11, 12, 13, 14] );
?>

<img src="<?php echo $plt->subplots[0][0]->output_gd_png_base64( );?>">
  <img src="<?php echo $plt->subplots[0][1]->output_gd_png_base64( );?>">
  <br />
  <img src="<?php echo $plt->subplots[1][0]->output_gd_png_base64( );?>">
  <img src="<?php echo $plt->subplots[1][1]->output_gd_png_base64( );?>">
  <?php
  
unset($plt);
print "<br />";

// read csv

$dataset = [];
if (($gestor = fopen("ibex35.csv", "r")) !== FALSE) {
  while (($datos = fgetcsv($gestor, 1000, ",")) !== FALSE) {
      $dataset[] = $datos;
  }
  fclose($gestor);
}

$plt = new php_plotlib();
$plt->pairplot( $dataset );?>

<img width="1900" src="<?php echo $plt->output_gd_png_base64( );?>"><?php
//print "<br />";
//$plt->pairplot( $dataset, ['widthmarker'=>6] );
unset($plt);
print "<br />";
?>
</body>
</html>