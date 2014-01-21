<?php
namespace Application\View\Helper;

use \Zend\View\Helper\AbstractHelper;

/**
 * Print mandrill reputation number
 *
 * @author Jan Tlapák
 * @package Mfcc
 * @subpackage View
 * @category Helper
 */
class PrintReputationNumber extends AbstractHelper
{
    /**
     * Format stat number
     */
    public function __invoke($num)
    {
    	$color = 'a0d468';
        if($num < 75){
        	$color = 'e5eb3d';
        } elseif($num < 50){
        	$color = 'fda391';
        }

        return '<span style="color: #'.$color.'">'.$num.'</span>';
    }
}