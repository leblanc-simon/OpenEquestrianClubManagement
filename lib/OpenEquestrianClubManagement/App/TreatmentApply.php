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
use OpenEquestrianClubManagement\Model\HorseQuery;
use OpenEquestrianClubManagement\Model\TreatmentQuery;
use OpenEquestrianClubManagement\Model\TreatmentApplyQuery;
use OpenEquestrianClubManagement\Model\TreatmentApply as TreatmentApplyObject;


/**
 * TreatmentApply controler
 *
 * @package     OpenEquestrianClubManagement\App
 * @version     1.0.0
 * @license     http://opensource.org/licenses/MIT  MIT
 * @author      Simon Leblanc <contact@leblanc-simon.eu>
 */
class TreatmentApply extends App
{
    /**
     * Treatment to apply listing action
     *
     * @return  Response
     * @access  public
     */
    public function defaultAction()
    {
        $horses = HorseQuery::create()->orderByName()->find();
        
        $treatment_applys = TreatmentApplyQuery::create()->orderByApplyDate(\Criteria::DESC)->find();
        
        return $this->render('default.html.twig', array('horses' => $horses, 'treatment_applys' => $treatment_applys, 'current_date' => new \DateTime()));
    }
    
    
    /**
     * Treatment to apply add action
     *
     * @return  Response
     * @access  public
     */
    public function addAction()
    {
        $horse = $this->app['request']->get('horse');
        $treatment = $this->app['request']->get('treatment');
        
        $treatment_apply = null;
        
        // Load default value if neccesarry
        if (empty($horse) === false || empty($treatment) === false) {
            $horse = HorseQuery::create()->findOneBySlug($horse);
            $treatment = TreatmentQuery::create()->findOneBySlug($treatment);
            
            if ($horse !== null || $treatment !== null) {
                $treatment_apply = new TreatmentApplyObject();
                $treatment_apply->setHorse($horse);
                $treatment_apply->setTreatment($treatment);
                $treatment_apply->setApplyDate(new \DateTime());
            }
        }
        
        $form = $this->getForm($treatment_apply);
        
        if ('POST' === $this->app['request']->getMethod()) {
            $form->bind($this->app['request']);
            
            if ($form->isValid()) {
                $data = $form->getData();
                
                $treatment_apply = new TreatmentApplyObject();
                $this->saveObject($treatment_apply, $data);
                
                return $this->app->redirect($this->app['url_generator']->generate('treatment-apply'));
            }
        }
        
        return $this->render('add.html.twig', array('form' => $form->createView()));
    }
    
    
    /**
     * Treatment to apply edit action
     *
     * @return  Response
     * @access  public
     */
    public function editAction()
    {
        $id = $this->app['request']->get('id');
        if (empty($id) === true) {
            throw new Exception\Error404('L\'application du soin n\'existe pas');
        }
        
        $treatment_apply = TreatmentApplyQuery::create()->findOneById($id);
        if ($treatment_apply === null) {
            throw new Exception\Error404('L\'application du soin n\'existe pas');
        }
        
        $form = $this->getForm($treatment_apply);
        
        if ('POST' === $this->app['request']->getMethod()) {
            $form->bind($this->app['request']);
            
            if ($form->isValid()) {
                $data = $form->getData();
                
                $this->saveObject($treatment_apply, $data);
                
                return $this->app->redirect($this->app['url_generator']->generate('treatment-apply'));
            }
        }
        
        return $this->render('edit.html.twig', array('form' => $form->createView(), 'treatment_apply' => $treatment_apply));
    }
    
    
    /**
     * Get the add/edit form for the treatment apply
     *
     * @param   \OpenEquestrianClubManagement\Model\TreatmentApply  $treatment_apply    the treatment apply to load in the form (null else)
     * @return  \Symfony\Component\Form\Form                                            the form component
     * @access  private
     */
    private function getForm(TreatmentApplyObject $treatment_apply = null)
    {
        $horses = HorseQuery::create()->orderByName()->find();
        $treatments = TreatmentQuery::create()->orderByName()->find();
        
        $horses_list = array();
        $treatments_list = array();
        
        foreach ($horses as $horse) {
            $horses_list[$horse->getId()] = $horse->getName();
        }
        foreach ($treatments as $treatment) {
            $treatments_list[$treatment->getId()] = $treatment->getName();
        }
        
        $horse_id = null;
        $treatment_id = null;
        $apply_date = new \DateTime();
        $description = null;
        
        if ($treatment_apply !== null) {
            $horse_id = $treatment_apply->getHorseId();
            $treatment_id = $treatment_apply->getTreatmentId();
            $apply_date = $treatment_apply->getApplyDate(null);
            $description = $treatment_apply->getDescription();
        }
        
        $form = $this->app['form.factory']->createBuilder('form')
                    ->add('horse_id', 'choice', array(
                        'data' => $horse_id,
                        'choices' => $horses_list,
                        'required' => true,
                        'label' => 'Cheval',
                        'empty_value' => 'Choisir un cheval'
                    ))
                    ->add('treatment_id', 'choice', array(
                        'data' => $treatment_id,
                        'choices' => $treatments_list,
                        'required' => true,
                        'label' => 'Soin',
                        'empty_value' => 'Choisir un soin'
                    ))
                    ->add('apply_date', 'date', array(
                        'data' => $apply_date,
                        'required' => true,
                        'widget' => 'single_text',
                        'label' => 'Date du soin',
                    ))
                    ->add('description', 'textarea', array(
                        'data' => $description,
                        'required' => false,
                        'label' => 'Description',
                    ))
                    ->getForm();
        
        return $form;
    }
    
    
    /**
     * Save the object with the datas of the form
     *
     * @param   \OpenEquestrianClubManagement\Model\TreatmentApply  $treatment_apply    the Horse object to save
     * @param   array                                               $data               the form's datas
     * @access  private
     */
    private function saveObject(TreatmentApplyObject $treatment_apply, $data)
    {
        $treatment_apply->setHorseId($data['horse_id']);
        $treatment_apply->setTreatmentId($data['treatment_id']);
        $treatment_apply->setApplyDate($data['apply_date']);
        $treatment_apply->setDescription($data['description']);
        
        $treatment_apply->save();
    }
}
