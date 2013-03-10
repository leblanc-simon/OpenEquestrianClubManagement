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

use OpenEquestrianClubManagement\Core\Config;
use OpenEquestrianClubManagement\Core\Exception;
use OpenEquestrianClubManagement\Model\OrderQuery;
use OpenEquestrianClubManagement\Model\Order as OrderObject;
use OpenEquestrianClubManagement\Model\OrderPeer;


/**
 * Order controler
 *
 * @package     OpenEquestrianClubManagement\App
 * @version     1.0.0
 * @license     http://opensource.org/licenses/MIT  MIT
 * @author      Simon Leblanc <contact@leblanc-simon.eu>
 */
class Order extends App
{
    /**
     * Last order action
     *
     * @return  Response
     * @access  public
     */
    public function defaultAction()
    {
        $last_orders = OrderQuery::create()
                        ->orderByDate(\Criteria::DESC)
                        ->limit(Config::get('nb_last_order', 10))
                        ->find();
        
        $orders_in_progress = OrderQuery::create()
                                ->filterByState(OrderPeer::STATE_PROGRESS)
                                ->orderByDate(\Criteria::DESC)
                                ->find();
        
        return $this->render('default.html.twig', array('last_orders' => $last_orders, 'orders_in_progress' => $orders_in_progress));
    }
}