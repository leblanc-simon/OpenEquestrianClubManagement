<?php
/**
 * This file is part of the OpenEquestrianClubManagement package.
 *
 * (c) Simon Leblanc <contact@leblanc-simon.eu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenEquestrianClubManagement\Extension\Twig;

use OpenEquestrianClubManagement\Core\Config as CoreConfig;
use OpenEquestrianClubManagement\Model\OrderPeer;


/**
 * Format extension for twig class
 *
 * @package     OpenEquestrianClubManagement\Extension\Twig
 * @version     1.0.0
 * @license     http://opensource.org/licenses/MIT  MIT
 * @author      Simon Leblanc <contact@leblanc-simon.eu>
 */
class Format extends \Twig_Extension
{
    /**
     * Define twig filters
     *
     * @return  array   the array with the filters define
     * @access  public
     */
    public function getFilters()
    {
        return array(
            'price' => new \Twig_Filter_Method($this, 'priceFilter'),
            'date'  => new \Twig_Filter_Method($this, 'dateFilter'),
            'state'  => new \Twig_Filter_Method($this, 'stateFilter'),
        );
    }
    
    
    /**
     * Get the class name
     *
     * @return  string  the class name
     * @access  public
     */
    public function getName()
    {
        return __CLASS__;
    }
    
    
    /**
     * Formate the price
     *
     * @param   float   $v  the price to formate
     * @return  string      the price formating
     * @access  public
     */
    public function priceFilter($v)
    {
        $fmt = new \NumberFormatter(CoreConfig::get('locale'), \NumberFormatter::CURRENCY);
        return $fmt->formatCurrency($v, 'EUR');
    }
    
    
    /**
     * Formate the date
     *
     * @param   \DateTime  $v   the date to formate
     * @return  string          the date formating
     * @access  public
     */
    public function dateFilter($v)
    {
        setlocale(LC_TIME, CoreConfig::get('locale'));
        return strftime('%A %d %B %Y', $v->format('U'));
    }
    
    
    /**
     * Formate the order's state
     *
     * @param   int     $v  the state of order
     * @return  string      the state formating (human readable)
     * @access  public
     */
    public function stateFilter($v)
    {
        switch ($v) {
            case OrderPeer::STATE_PAID:
                return 'Payée';
                //break
                
            case OrderPeer::STATE_CANCEL:
                return 'Annulée';
                //break
                
            case OrderPeer::STATE_PROGRESS:
                return 'En attente de paiement';
                //break
            
            default:
                return 'Inconnu';
                //break
        }
    }
}