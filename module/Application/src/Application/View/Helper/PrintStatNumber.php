<?php
namespace Application\View\Helper;

use \Zend\View\Helper\AbstractHelper;

/**
 * Print mandrill stat number
 *
 * @author Jan Tlapák
 * @package Mfcc
 * @subpackage View
 * @category Helper
 */
class PrintStatNumber extends AbstractHelper
{
    /**
     * Format stat number
     */
    public function __invoke($num, $colorize = false, $relativesize = null, $relativelength = null)
    {
    	$style = array();
        if($num==0 && $colorize){
        	$style[] = 'color:white';
        }
        if($relativesize && $relativelength){
			if(strlen($num) > $relativelength){
				$style[] = 'font-size: '.ceil($relativesize * ($relativelength / strlen($num))).'px';
			}
        }

        return '<span style="'.join(';', $style).'">'.number_format($num, 0, '.', ' ').'</span>';
    }
}