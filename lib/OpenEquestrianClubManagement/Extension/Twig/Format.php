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
     * Get the value of configuration
     *
     * @param   string  $v  the name of the configuration to retrieve
     * @return  string      the value of the configuration
     * @access  public
     */
    public function priceFilter($v)
    {
        $fmt = new \NumberFormatter(CoreConfig::get('locale'), \NumberFormatter::CURRENCY);
        return $fmt->formatCurrency($v, 'EUR');
    }
}