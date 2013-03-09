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
use OpenEquestrianClubManagement\Model\TreatmentQuery;
use OpenEquestrianClubManagement\Model\Treatment as TreatmentObject;

/**
 * Treatment controler
 *
 * @package     OpenEquestrianClubManagement\App
 * @version     1.0.0
 * @license     http://opensource.org/licenses/MIT  MIT
 * @author      Simon Leblanc <contact@leblanc-simon.eu>
 */
class Treatment extends App
{
    /**
     * Treatment listing action
     *
     * @return  Response
     * @access  public
     */
    public function defaultAction()
    {
        $treatments = TreatmentQuery::create()->orderByName()->find();
        
        return $this->render('default.html.twig', array('treatments' => $treatments));
    }
    
    
    /**
     * Treatment add action
     *
     * @return  Response
     * @access  public
     */
    public function addAction()
    {
        $form = $this->getForm();
        
        if ('POST' === $this->app['request']->getMethod()) {
            $form->bind($this->app['request']);
            
            if ($form->isValid()) {
                $data = $form->getData();
                
                $treatment = new TreatmentObject();
                $this->saveObject($treatment, $data);
                
                return $this->app->redirect($this->app['url_generator']->generate('treatments'));
            }
        }
        
        return $this->render('add.html.twig', array('form' => $form->createView()));
    }
    
    
    /**
     * Treatment edit action
     *
     * @return  Response
     * @access  public
     */
    public function editAction()
    {
        $slug = $this->app['request']->get('slug');
        if (empty($slug) === true) {
            throw new Exception\Error404('Le type de soin n\'existe pas');
        }
        
        $treatment = TreatmentQuery::create()->findOneBySlug($slug);
        if ($treatment === null) {
            throw new Exception\Error404('Le type de soin n\'existe pas');
        }
        
        $form = $this->getForm($treatment);
        
        if ('POST' === $this->app['request']->getMethod()) {
            $form->bind($this->app['request']);
            
            if ($form->isValid()) {
                $data = $form->getData();
                
                $this->saveObject($treatment, $data);
                
                return $this->app->redirect($this->app['url_generator']->generate('treatments'));
            }
        }
        
        return $this->render('edit.html.twig', array('form' => $form->createView(), 'treatment' => $treatment));
    }
    
    
    /**
     * Get the add/edit form for the treatment
     *
     * @param   \OpenEquestrianClubManagement\Model\Treatment   $treatment  the treatment to load in the form (null else)
     * @return  \Symfony\Component\Form\Form                        the form component
     * @access  private
     */
    private function getForm(TreatmentObject $treatment = null)
    {
        $name = null;
        $periodicity = null;
        $description = null;
        
        if ($treatment !== null) {
            $name = $treatment->getName();
            $periodicity = $treatment->getPeriodicity();
            $description = $treatment->getDescription();
        }
        
        $form = $this->app['form.factory']->createBuilder('form')
                ->add('name', 'text', array(
                    'data' => $name,
                    'required' => true,
                    'label' => 'Nom',
                ))
                ->add('periodicity', 'integer', array(
                    'data' => $periodicity,
                    'required' => false,
                    'label' => 'Périodicité (en semaine)',
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
     * @param   \OpenEquestrianClubManagement\Model\Treatment   $treatment  the Treatment object to save
     * @param   array                                           $data       the form's datas
     * @access  private
     */
    private function saveObject(TreatmentObject $treatment, $data)
    {
        $treatment->setName($data['name']);
        $treatment->setPeriodicity($data['periodicity']);
        $treatment->setDescription($data['description']);
        
        $treatment->save();
    }
}