<?php

namespace OpenEquestrianClubManagement\Model;

use OpenEquestrianClubManagement\Model\om\BaseOrderPeer;
use OpenEquestrianClubManagement\Core\Config;

/**
 * Skeleton subclass for performing query and update operations on the 'order' table.
 *
 * Table de gestion des commandes
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.lib.OpenEquestrianClubManagement.Model
 */
class OrderPeer extends BaseOrderPeer
{
    /**
     * Generate an unique num order
     *
     * @return  string  the num order
     * @access  public
     * 
     */
    static public function generateNumOrder()
    {
        $num_template = Config::get('num_order_template');
        $num_search = str_replace(array('%year%', '%increment%'), array(date('Y'), '%'), $num_template);
        
        $last_order = OrderQuery::create()->filterByNumOrder($num_search)->orderByNumOrder(\Criteria::DESC)->findOne();
        if ($last_order === null) {
            $increment = 1;
        } else {
            $increment = (int)substr($last_order->getNumOrder(), strpos($num_search, '%')) + 1;
        }
        
        return str_replace(array('%year%', '%increment%'), array(date('Y'), str_pad($increment, 5, '0', STR_PAD_LEFT)), $num_template);
    }
}
