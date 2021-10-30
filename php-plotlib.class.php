<?php
 /** php-plotlib.class.php
  *  
  * Class for generate graphs similar to python matplotlib
  * 
  * @author Rafael Martin Soto
  * @author {@link https://www.inatica.com/ Inatica}
  * @blog {@link https://rafamartin10.blogspot.com/ Blog Rafael Martin Soto}
  * @since October 2021
  * @version 1.0.0
  * @license GNU General Public License v3.0
  *
  * */

 
  require_once __DIR__ . '/graph-php/graph-php.class.php';
 
 class php_plotlib extends graph{



    /**
     * Define array of php_plotlib's
     */
    public $subplots = [];


    
    public function __construct( $cfg = null) {
        parent::__construct( $cfg );
	} // / __construct



    /**
     * Method to divide GD in subplots
     * 
     * @param integer $numyplots = null
     * @param integer $numxplots = null
     * @param string $bordertype = 'square'
     */
    public function subplots( $numyplots = null, $numxplots = null, $cfg = [ 'bordertype' => 'square', 'figsize' => [6.4, 4.8], 'paddingleft' => .2, 'paddingbottom' => .15 ]){
        
        // if all params are null or their values are 1, return. We use our own GD graph

        $yplots = ((is_null($numyplots))?1:$numyplots);
        $xplots = ((is_null($numxplots))?1:$numxplots);

        if( $yplots <= 1 && $xplots <= 1 ){
            return;
        }

        if( !isset($cfg['bordertype']) ){
            $cfg['bordertype'] = 'square';
        }
        if( !isset($cfg['figsize']) ){
            $cfg['figsize'] = [ $this->cfg['width'], $this->cfg['height' ]];
        }
        if( !isset($cfg['paddingleft']) ){
            $cfg['paddingleft'] = .2;
        }
        if( !isset($cfg['paddingbottom']) ){
            $cfg['paddingbottom'] = .15;
        }

        $figurewidth    = $cfg['figsize'][0];
        $figureheight   = $cfg['figsize'][1];

        // Initialize $this->subplots
        $this->subplots = [];

        $widthsubplots = $figurewidth/$xplots;
        $heightsubplots = $figureheight/$yplots;

        $dinamicleftpadding     = $cfg['paddingleft'];
        $dinamicbottompadding   = $cfg['paddingbottom'];

        for( $i=0; $i<$yplots; $i++ ){
            if( $xplots>1 ){
                $subplotsx = [];
                for( $j=0; $j<$xplots; $j++ ){
                    $subplotsx[] = new php_plotlib();
                    $subplotsx[$j]->width( $widthsubplots );
                    $subplotsx[$j]->height( $heightsubplots );

                    if( $j>0 ){
                        $subplotsx[$j]->cfg['yshowlabelticks'] = false;
                        $subplotsx[$j]->cfg['paddingleft'] -= $dinamicleftpadding; // less padding. No need to draw ticks labels
                    } else {
                        //$subplotsx[$j]->cfg['paddingleft'] += $dinamicleftpadding + 2; // Add more padding for draw Y Label
                        //$subplotsx[$j]->width( $widthsubplots + $dinamicleftpadding + 1.5 );
                        $subplotsx[$j]->width( $widthsubplots + $dinamicleftpadding );
                        $subplotsx[$j]->cfg['paddingleft'] += $dinamicleftpadding; // Add more padding for draw Y Label
                    }

                    if( $i<$yplots-1 ){
                        $subplotsx[$j]->cfg['xshowlabelticks'] = false;
                        $subplotsx[$j]->cfg['paddingbottom'] -= $dinamicbottompadding; // less padding. No need to draw ticks labels
                    } else {
                        $subplotsx[$j]->height( $heightsubplots + $dinamicbottompadding );
                        $subplotsx[$j]->cfg['paddingbottom'] += $dinamicbottompadding; // Add more padding for draw Y Label
                    }

                    $subplotsx[$j]->cfg['paddingtop'] = 0; // less padding. No need to draw nothing
                    $subplotsx[$j]->cfg['paddingright'] = 0; // less padding. No need to draw nothing

                    $subplotsx[$j]->cfg['bordertype'] = $cfg['bordertype'];

                }
            } else {
                $subplotsx = new php_plotlib();
                $subplotsx->width( $widthsubplots );
                $subplotsx->height( $heightsubplots );

                if( $i<$yplots-1 ){
                    $subplotsx->cfg['xshowlabelticks'] = false;
                    $subplotsx->cfg['paddingbottom'] -= $dinamicbottompadding; // less padding. No need to draw ticks labels
                } else {
                    $subplotsx->height( $heightsubplots + $dinamicleftpadding );
                    $subplotsx->cfg['paddingbottom'] += $dinamicbottompadding; // Add more padding for draw Y Label
                }

                $subplotsx->cfg['paddingtop'] = 0; // less padding. No need to draw nothing
                $subplotsx->cfg['paddingright'] = 0; // less padding. No need to draw nothing

                $subplotsx->cfg['bordertype'] = $cfg['bordertype'];
            }
            $this->subplots[] = $subplotsx;
        }

        unset( $i );
        unset( $j );
        unset( $yplots );
        unset( $xplots );
        unset( $subplotsx );
        unset( $widthsubplots );
        unset( $heightsubplots );
        unset( $figurewidth );
        unset( $figureheigth );
    } // /subplots()

    /**
     * return a png stream of graph base64
     * 
     * @param array $cfg
     * @return string $png_stream
     */
	public function output_gd_png_base64( $cfg = null){
        
        $yplots = count( $this->subplots );

        if($yplots>0){
            $xplots = count( $this->subplots[0] );
        } else {
            return parent::output_gd_png_base64( $cfg );
        }

        // Generate graphs

        $height = 0;
        for( $i=0; $i<$yplots; $i++ ){
            $widthsubplot = 0;
            for( $j=0; $j<$xplots; $j++ ){
                // Create graph on subplot
                $this->subplots[$i][$j]->draw_to_output( $cfg );

                $widthsubplot += imagesx($this->subplots[$i][$j]->gd);
                $heigthsubplot = imagesy($this->subplots[$i][$j]->gd);
            }
            $height += $heigthsubplot;
        }

        $this->gd = @imagecreatetruecolor( $widthsubplot, $height ) or die("Cannot Initialize new GD image stream");

        $y = 0;
        for( $i=0; $i<$yplots; $i++ ){
            $x = 0;
            for( $j=0; $j<$xplots; $j++ ){
                $widthsubplot   = imagesx($this->subplots[$i][$j]->gd);
                $heigthsubplot  = imagesy($this->subplots[$i][$j]->gd);

                // Copy gd image created in each subplot to $this->gd
                imagecopy( $this->gd, $this->subplots[$i][$j]->gd, $x, $y, 0, 0, $widthsubplot, $heigthsubplot);

                $x += $widthsubplot;
            }
            $y += $heigthsubplot;
        }

        unset( $i );
        unset( $j );
        unset( $x );
        unset( $y );
        unset( $yplots );
        unset( $xplots );
        unset( $widthsubplots );

		return 'data:image/png;base64,' . base64_encode( $this->output_gd_png_raw( ) );
        
        
	} // /output_gd_png_base64()


    /**
     * Method to set width in plot & subplots
     * 
     * @param float $width = 6.4
     */
    public function width( $width = 6.4){
        $this->cfg['width'] = $width;

        $yplots = count( $this->subplots );

        if($yplots>0){
            $xplots = count( $this->subplots[0] );
        } else {
            parent::width( $width );
            return;   
        }


        $widthsubplots = $this->cfg['width']/$xplots;

        for( $i=0; $i<$yplots; $i++ ){
            for( $j=0; $j<$xplots; $j++ ){
                $this->subplots[$i][$j]->width( $widthsubplots );
            }
        }

        unset( $i );
        unset( $j );
        unset( $yplots );
        unset( $xplots );
        unset( $widthsubplots );
    } // /width()


    /**
     * Method to set height in plot & subplots
     * 
     * @param float $height = 4.8
     */
    public function height( $height = 4.8){
        $this->cfg['height'] = $height;

        $yplots = count( $this->subplots );

        if($yplots>0){
            $xplots = count( $this->subplots[0] );
        } else {
            parent::height( $height );
            return;   
        }


        $heightsubplots = $this->cfg['height']/$yplots;

        for( $i=0; $i<$yplots; $i++ ){
            for( $j=0; $j<$xplots; $j++ ){
                $this->subplots[$i][$j]->height( $heightsubplots );
            }
        }

        unset( $i );
        unset( $j );
        unset( $yplots );
        unset( $xplots );
        unset( $heightsubplots );
    } // /height()


    /**
     * Method to create graph between each field in dataset
     * 
     * NOTE: The system assumes that the first line contains the name of each field
     * 
     * @param array $dataset
     */
    public function pairplot( $dataset, $cfg = null ){
        $widthmarker = ((isset($cfg['widthmarker']))?$cfg['widthmarker']:5);

        $colnames = $dataset[0];

        $numcols = count( $colnames );
        $numrows = count( $dataset ) - 1; // First row is colnames. Need to substract it

        $widthsubplot = 6.4 / 2; // Default size
        $heightsubplot = 4.8 / 2; // Default size

        $cfg = ['figsize'=>[$numcols * $widthsubplot, $numcols * $heightsubplot], 'bordertype'=>'halfsquare'];

        $this->subplots( $numcols, $numcols, $cfg );
        
        for( $i=0; $i<$numcols; $i++ ){
            for( $j=0; $j<$numcols; $j++ ){
                $this->subplots[$i][$j]->cfg['width_marker_o']	= $widthmarker;
        		$this->subplots[$i][$j]->cfg['height_marker_o']	= $widthmarker;
        		$this->subplots[$i][$j]->cfg['ymarginleftlabel']	= 10;

                $arrdatasetcolj = $this->math->arrdatasetcol($dataset, $j, 1); // 1 = skip 1 row that is the name fields

                if( $i == $j){
                    $this->subplots[$i][$j]->hist( $arrdatasetcolj );
                } else {
                    $arrdatasetcoli = $this->math->arrdatasetcol($dataset, $i, 1); // 1 = skip 1 row that is the name fields

                    $arr_values = [ [ $arrdatasetcolj, $arrdatasetcoli ] ];

                    $this->subplots[$i][$j]->scatter( $arr_values );
                }

                if( $j==0 ){
                    $this->subplots[$i][$j]->ylabel( $dataset[0][$i] );
                }

                if( $i==$numcols-1 ){
                    $this->subplots[$i][$j]->xlabel( $dataset[0][$j] );
                }
            }
        }

        unset( $colnames );
        unset( $numcols );
        unset( $numrows );
        unset( $i );
        unset( $j );
    } // /pairplot()
}// /php_plotlib
 ?>