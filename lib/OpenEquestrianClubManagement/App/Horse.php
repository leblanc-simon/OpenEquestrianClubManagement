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
use OpenEquestrianClubManagement\Model\Horse as HorseObject;

/**
 * Horse controler
 *
 * @package     OpenEquestrianClubManagement\App
 * @version     1.0.0
 * @license     http://opensource.org/licenses/MIT  MIT
 * @author      Simon Leblanc <contact@leblanc-simon.eu>
 */
class Horse extends App
{
    /**
     * Horses listing action
     *
     * @return  Response
     * @access  public
     */
    public function defaultAction()
    {
        $horses = HorseQuery::create()->orderByName()->find();
        
        return $this->render('default.html.twig', array('horses' => $horses));
    }
    
    
    /**
     * Horse add action
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
                
                $horse = new HorseObject();
                $horse->setName($data['name']);
                $horse->setSireNumber($data['sire_number']);
                $horse->setBirthday($data['birthday']);
                $horse->setDescription($data['description']);
                
                $horse->save();
                
                return $this->app->redirect($this->app['url_generator']->generate('horses'));
            }
        }
        
        return $this->render('add.html.twig', array('form' => $form->createView()));
    }
    
    
    /**
     * Horse edit action
     *
     * @return  Response
     * @access  public
     */
    public function editAction()
    {
        $slug = $this->app['request']->get('slug');
        if (empty($slug) === true) {
            throw new Exception\Error404('Le cheval n\'existe pas');
        }
        
        $horse = HorseQuery::create()->findOneBySlug($slug);
        if ($horse === null) {
            throw new Exception\Error404('Le cheval n\'existe pas');
        }
        
        $form = $this->getForm($horse);
        
        if ('POST' === $this->app['request']->getMethod()) {
            $form->bind($this->app['request']);
            
            if ($form->isValid()) {
                $data = $form->getData();
                
                $horse->setName($data['name']);
                $horse->setSireNumber($data['sire_number']);
                $horse->setBirthday($data['birthday']);
                $horse->setDescription($data['description']);
                
                $horse->save();
                
                return $this->app->redirect($this->app['url_generator']->generate('horses'));
            }
        }
        
        return $this->render('edit.html.twig', array('form' => $form->createView(), 'horse' => $horse));
    }
    
    
    /**
     * Get the add/edit form for the horse
     *
     * @param   \OpenEquestrianClubManagement\Model\Horse   $horse  the horse to load in the form (null else)
     * @return  \Symfony\Component\Form\Form                        the form component
     * @access  private
     */
    private function getForm(HorseObject $horse = null)
    {
        $name = null;
        $sire = null;
        $birthday = null;
        $description = null;
        
        if ($horse !== null) {
            $name = $horse->getName();
            $sire = $horse->getSireNumber();
            $birthday = $horse->getBirthday(null);
            $description = $horse->getDescription();
        }
        
        $form = $this->app['form.factory']->createBuilder('form')
                ->add('name', 'text', array(
                    'data' => $name,
                    'required' => true,
                    'label' => 'Nom',
                ))
                ->add('sire_number', 'text', array(
                    'data' => $sire,
                    'required' => false,
                    'label' => 'N° de SIRE',
                ))
                ->add('birthday', 'date', array(
                    'data' => $birthday,
                    'required' => false,
                    'widget' => 'single_text',
                    'label' => 'Date de naissance',
                ))
                ->add('description', 'textarea', array(
                    'data' => $description,
                    'required' => false,
                    'label' => 'Description',
                ))
                ->getForm();
        
        return $form;
    }
}