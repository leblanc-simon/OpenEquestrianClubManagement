<?php
/**
 * This file is part of the OpenEquestrianClubManagement package.
 *
 * (c) Simon Leblanc <contact@leblanc-simon.eu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenEquestrianClubManagement\App;

use OpenEquestrianClubManagement\Core\Exception;
use OpenEquestrianClubManagement\Model\CustomerQuery;

/**
 * Customer controler
 *
 * @package     OpenEquestrianClubManagement\App
 * @version     1.0.0
 * @license     http://opensource.org/licenses/MIT  MIT
 * @author      Simon Leblanc <contact@leblanc-simon.eu>
 */
class Customer extends App
{
    /**
     * Homepage action
     *
     * @return  Response
     * @access  public
     */
    public function defaultAction()
    {
        $customers = CustomerQuery::create()->orderByShowName()->find();
        
        return $this->render('default.html.twig', array('customers' => $customers));
    }
}