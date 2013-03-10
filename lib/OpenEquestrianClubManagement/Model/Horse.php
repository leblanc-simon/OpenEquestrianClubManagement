<?php

namespace OpenEquestrianClubManagement\Model;

use OpenEquestrianClubManagement\Model\om\BaseHorse;


/**
 * Skeleton subclass for representing a row from the 'horse' table.
 *
 * Table de gestion des chevaux
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.lib.OpenEquestrianClubManagement.Model
 */
class Horse extends BaseHorse
{
    private $last_treatment = array();
    
    /**
     * Get the treatments to apply to the horse
     *
     * @param   int $before_days    the number of days before to apply the treatments
     * @return  array<\OpenEquestrianClubManagement\Model\Treatment>
     * @access  public
     */
    public function getTreatmentToApplys($before_days = 0)
    {
        static $treatments = array();
        if (isset($treatments[$this->getId()]) === false) {
            $treatments[$this->getId()] = array();
        }
        
        if ($before_days > 0) {
            $before_days *= -1;
        }
        
        if (isset($treatments[$this->getId()][$before_days]) === false) {
            $treatments[$this->getId()][$before_days] = TreatmentQuery::create()
                ->where(TreatmentPeer::ID.' NOT IN (
                    SELECT '.TreatmentApplyPeer::TREATMENT_ID.'
                    FROM '.TreatmentPeer::TABLE_NAME.' INNER JOIN '.TreatmentApplyPeer::TABLE_NAME.'
                        ON '.TreatmentPeer::ID.' = '.TreatmentApplyPeer::TREATMENT_ID.'
                    WHERE '.TreatmentApplyPeer::HORSE_ID.' = ?
                        AND
                        DATEDIFF(NOW(), DATE_ADD('.TreatmentApplyPeer::APPLY_DATE.', INTERVAL '.TreatmentPeer::PERIODICITY.' WEEK)) <= ?
                )', array($this->getId(), $before_days))
                ->find();
        }
                        
        return $treatments[$this->getId()][$before_days];
    }
    
    
    /**
     * Indicate if the horse must have treatment
     * 
     * @param   int $before_days    the number of days before to apply the treatments
     * @return  bool                true if the horse must have treatment, false else
     * @access  public
     */
    public function hasTreatmentToApplys($before_days = 0)
    {
        return (bool)count($this->getTreatmentToApplys());
    }
    
    
    /**
     * Return the last treatment apply for a treatment for the horse
     *
     * @param   \OpenEquestrianClubManagement\Model\Treatment   $treatment  the treatment to search
     * @return  \OpenEquestrianClubManagement\Model\TreatmentApply          the object TreatmentApply
     */
    public function getLastTreatmentApply(Treatment $treatment)
    {
        if (isset($this->last_treatment[$treatment->getId()]) === false) {
            $this->last_treatment[$treatment->getId()] = TreatmentApplyQuery::create()
                ->filterByHorse($this)
                ->filterByTreatment($treatment)
                ->orderByApplyDate(\Criteria::DESC)
                ->findOne();
        }
        
        return $this->last_treatment[$treatment->getId()];
    }
    
    
    /**
     * Return the next date for the treatment
     *
     * @param   \OpenEquestrianClubManagement\Model\Treatment   $treatment  the treatment to search
     * @return  \DateTime                                                   the next date for the treatment (today if it will be the first)
     * @access  public
     */
    public function getNextTreatmentApplyDate(Treatment $treatment)
    {
        $last_treatment_apply = $this->getLastTreatmentApply($treatment);
        if ($last_treatment_apply === null) {
            return new \DateTime();
        }
        
        return $last_treatment_apply->getApplyDate(null)->add(new \DateInterval('P'.$treatment->getPeriodicity().'W'));
    }
}
