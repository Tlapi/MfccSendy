<?php
namespace Application\View\Helper;

use \Zend\View\Helper\AbstractHelper;

/**
 * Gets users status
 *
 * @author Jan Tlapák
 * @package Mfcc
 * @subpackage View
 * @category Helper
 */
class UserStatus extends AbstractHelper
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
			0 => 'unknown',
			1 => 'subscribed',
			2 => 'unsubscribed',
			3 => 'soft bounced',
			4 => 'hard bounced',
			5 => 'complained',
        );
        $statuses_classes = array(
			0 => '',
			1 => 'label-success',
			2 => 'label-important',
			3 => 'label-inverse',
			4 => 'label-inverse',
			5 => 'label-warning',
        );

        return '<span class="label '.$statuses_classes[$status].'">'.$statuses[$status].'</span>';
    }
}