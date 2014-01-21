<?php
namespace Application\View\Helper;

use \Zend\View\Helper\AbstractHelper;

/**
 * Gets campaign status
 *
 * @author Jan Tlapák
 * @package Mfcc
 * @subpackage View
 * @category Helper
 */
class CampaignStatus extends AbstractHelper
{
    /**
     * Gets user status
     *
     * @param int $status
     * @return string
     */
    public function __invoke($status)
    {
        $statuses = array(
			0 => 'draft',
			1 => 'preparing',
			2 => 'sending',
			3 => 'sent',
			4 => 'error',
        );
        $statuses_classes = array(
			0 => 'label-default',
			1 => 'label-info',
			2 => 'label-warning',
			3 => 'label-success',
			4 => 'label-important',
        );

        return '<span class="label '.$statuses_classes[$status].'">'.$statuses[$status].'</span>';
    }
}