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
class PrintMandrillStats extends AbstractHelper
{
    /**
     * Prints mandrill stats
     *
     * @param array $stats
     * @return string
     */
    public function __invoke($stats)
    {
        $template = '<table class="table">
  		<tbody>
  			<tr>
  				<td><b>Sent</b></td>
  				<td>'.$stats['sent'].'</td>
  				<td><b>Hard bounces</b></td>
  				<td>'.$stats['hard_bounces'].'</td>
  				<td><b>Soft bounces</b></td>
  				<td>'.$stats['soft_bounces'].'</td>
  				<td><b>Rejects</b></td>
  				<td>'.$stats['rejects'].'</td>
  				<td><b>Complaints</b></td>
  				<td>'.$stats['complaints'].'</td>
  			</tr>
  			<tr>
  				<td><b>Unsubscribes</b></td>
  				<td>'.$stats['unsubs'].'</td>
  				<td><b>Opens</b></td>
  				<td>'.$stats['opens'].'</td>
  				<td><b>Unique opens</b></td>
  				<td>'.$stats['unique_opens'].'</td>
  				<td><b>Clicks</b></td>
  				<td>'.$stats['clicks'].'</td>
  				<td><b>Unique clicks</b></td>
  				<td>'.$stats['unique_clicks'].'</td>
  			</tr>
  		</tbody>
	</table>';

        return $template;
    }
}