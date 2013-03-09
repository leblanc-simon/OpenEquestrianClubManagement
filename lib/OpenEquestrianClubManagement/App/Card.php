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
use OpenEquestrianClubManagement\Model\CardQuery;
use OpenEquestrianClubManagement\Model\Card as CardObject;

/**
 * Card controler
 *
 * @package     OpenEquestrianClubManagement\App
 * @version     1.0.0
 * @license     http://opensource.org/licenses/MIT  MIT
 * @author      Simon Leblanc <contact@leblanc-simon.eu>
 */
class Card extends App
{
    /**
     * Card listing action
     *
     * @return  Response
     * @access  public
     */
    public function defaultAction()
    {
        $cards = CardQuery::create()->orderByName()->find();
        
        return $this->render('default.html.twig', array('cards' => $cards));
    }
    
    
    /**
     * Card add action
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
                
                $card = new CardObject();
                $this->saveObject($card, $data);
                
                return $this->app->redirect($this->app['url_generator']->generate('cards'));
            }
        }
        
        return $this->render('add.html.twig', array('form' => $form->createView()));
    }
    
    
    /**
     * Card edit action
     *
     * @return  Response
     * @access  public
     */
    public function editAction()
    {
        $slug = $this->app['request']->get('slug');
        if (empty($slug) === true) {
            throw new Exception\Error404('Le type de carte de séance n\'existe pas');
        }
        
        $card = CardQuery::create()->findOneBySlug($slug);
        if ($card === null) {
            throw new Exception\Error404('Le type de carte de séance n\'existe pas');
        }
        
        $form = $this->getForm($card);
        
        if ('POST' === $this->app['request']->getMethod()) {
            $form->bind($this->app['request']);
            
            if ($form->isValid()) {
                $data = $form->getData();
                
                $this->saveObject($card, $data);
                
                return $this->app->redirect($this->app['url_generator']->generate('cards'));
            }
        }
        
        return $this->render('edit.html.twig', array('form' => $form->createView(), 'card' => $card));
    }
    
    
    /**
     * Get the add/edit form for the card
     *
     * @param   \OpenEquestrianClubManagement\Model\Card    $card   the card to load in the form (null else)
     * @return  \Symfony\Component\Form\Form                        the form component
     * @access  private
     */
    private function getForm(CardObject $card = null)
    {
        $name = null;
        $nb_items = null;
        $nb_week = null;
        $price = null;
        $description = null;
        
        if ($card !== null) {
            $name = $card->getName();
            $nb_items = $card->getNbItems();
            $nb_week = $card->getNbWeek();
            $price = $card->getPrice();
            $description = $card->getDescription();
        }
        
        $form = $this->app['form.factory']->createBuilder('form')
                ->add('name', 'text', array(
                    'data' => $name,
                    'required' => true,
                    'label' => 'Nom',
                ))
                ->add('nb_items', 'integer', array(
                    'data' => $nb_items,
                    'required' => false,
                    'label' => 'Nombre de séances',
                ))
                ->add('nb_week', 'integer', array(
                    'data' => $nb_week,
                    'required' => false,
                    'label' => 'Validité (en semaine)',
                ))
                ->add('price', 'money', array(
                    'data' => $price,
                    'required' => true,
                    'label' => 'Prix',
                    'currency' => false,
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
     * @param   \OpenEquestrianClubManagement\Model\Card    $card   the Card object to save
     * @param   array                                       $data   the form's datas
     * @access  private
     */
    private function saveObject(CardObject $card, $data)
    {
        $card->setName($data['name']);
        $card->setNbItems($data['nb_items']);
        $card->setNbWeek($data['nb_week']);
        $card->setPrice($data['price']);
        $card->setDescription($data['description']);
        
        $card->save();
    }
}