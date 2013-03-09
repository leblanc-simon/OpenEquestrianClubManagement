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
use OpenEquestrianClubManagement\Model\Customer as CustomerObject;

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
     * Customer listing action
     *
     * @return  Response
     * @access  public
     */
    public function defaultAction()
    {
        $customers = CustomerQuery::create()->orderByShowName()->find();
        
        return $this->render('default.html.twig', array('customers' => $customers));
    }
    
    
    /**
     * Customer add action
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
                
                $customer = new CustomerObject();
                $this->saveObject($customer, $data);
                
                return $this->app->redirect($this->app['url_generator']->generate('customers'));
            }
        }
        
        return $this->render('add.html.twig', array('form' => $form->createView()));
    }
    
    
    /**
     * Customer edit action
     *
     * @return  Response
     * @access  public
     */
    public function editAction()
    {
        $slug = $this->app['request']->get('slug');
        if (empty($slug) === true) {
            throw new Exception\Error404('Le client n\'existe pas');
        }
        
        $customer = CustomerQuery::create()->findOneBySlug($slug);
        if ($customer === null) {
            throw new Exception\Error404('Le client n\'existe pas');
        }
        
        $form = $this->getForm($customer);
        
        if ('POST' === $this->app['request']->getMethod()) {
            $form->bind($this->app['request']);
            
            if ($form->isValid()) {
                $data = $form->getData();
                
                $this->saveObject($customer, $data);
                
                return $this->app->redirect($this->app['url_generator']->generate('customers'));
            }
        }
        
        return $this->render('edit.html.twig', array('form' => $form->createView(), 'customer' => $customer));
    }
    
    
    /**
     * Get the add/edit form for the customer
     *
     * @param   \OpenEquestrianClubManagement\Model\Customer   $customer    the customer to load in the form (null else)
     * @return  \Symfony\Component\Form\Form                                the form component
     * @access  private
     */
    private function getForm(CustomerObject $customer = null)
    {
        $firstname = null;
        $lastname = null;
        $email = null;
        $phone0 = null;
        $phone1 = null;
        $phone2 = null;
        $parent_name = null;
        $address = null;
        $postal_code = null;
        $city = null;
        $license_number = null;
        $birthday = null;
        $subscribe_date = new \DateTime();
        $description = null;
        $active = true;
        
        if ($customer !== null) {
            $firstname = $customer->getFirstname();
            $lastname = $customer->getLastname();
            $email = $customer->getEmail();
            $phone0 = $customer->getPhone0();
            $phone1 = $customer->getPhone1();
            $phone2 = $customer->getPhone2();
            $parent_name = $customer->getParentName();
            $address = $customer->getAddress();
            $postal_code = $customer->getPostalCode();
            $city = $customer->getCity();
            $license_number = $customer->getLicenseNumber();
            $birthday = $customer->getBirthday(null);
            $subscribe_date = $customer->getSubscribeDate(null);
            $description = $customer->getDescription();
            $active = $customer->getActive();
        }
        
        $form = $this->app['form.factory']->createBuilder('form')
                ->add('firstname', 'text', array(
                    'data' => $firstname,
                    'required' => true,
                    'label' => 'Prénom',
                ))
                ->add('lastname', 'text', array(
                    'data' => $lastname,
                    'required' => true,
                    'label' => 'Nom',
                ))
                ->add('email', 'email', array(
                    'data' => $email,
                    'required' => false,
                    'label' => 'Adresse e-mail',
                ))
                ->add('phone0', 'text', array(
                    'data' => $phone0,
                    'required' => false,
                    'label' => 'N° de téléphone principal',
                ))
                ->add('phone1', 'text', array(
                    'data' => $phone1,
                    'required' => false,
                    'label' => 'N° de téléphone',
                ))
                ->add('phone2', 'text', array(
                    'data' => $phone2,
                    'required' => false,
                    'label' => 'N° de téléphone',
                ))
                ->add('parent_name', 'text', array(
                    'data' => $parent_name,
                    'required' => false,
                    'label' => 'Nom des parents',
                ))
                ->add('address', 'textarea', array(
                    'data' => $name,
                    'required' => false,
                    'label' => 'Adresse',
                ))
                ->add('postal_code', 'text', array(
                    'data' => $postal_code,
                    'required' => false,
                    'label' => 'Code postal',
                ))
                ->add('city', 'text', array(
                    'data' => $city,
                    'required' => false,
                    'label' => 'Ville',
                ))
                ->add('license_number', 'text', array(
                    'data' => $license_number,
                    'required' => false,
                    'label' => 'N° de licence',
                ))
                ->add('birthday', 'date', array(
                    'data' => $birthday,
                    'required' => false,
                    'widget' => 'single_text',
                    'label' => 'Date de naissance',
                ))
                ->add('subscribe_date', 'date', array(
                    'data' => $subscribe_date,
                    'required' => false,
                    'widget' => 'single_text',
                    'label' => 'Date d\'inscription',
                ))
                ->add('description', 'textarea', array(
                    'data' => $description,
                    'required' => false,
                    'label' => 'Informations complémentaires',
                ))
                ->add('active', 'checkbox', array(
                    'data' => $active,
                    'required' => false,
                    'label' => 'Actif',
                ))
                ->getForm();
        
        return $form;
    }
    
    
    /**
     * Save the object with the datas of the form
     *
     * @param   \OpenEquestrianClubManagement\Model\Customer    $customer   the Customer object to save
     * @param   array                                           $data       the form's datas
     * @access  private
     */
    private function saveObject(CustomerObject $customer, $data)
    {
        $customer->setFirstname($data['firstname']);
        $customer->setLastname($data['lastname']);
        $customer->setEmail($data['email']);
        $customer->setPhone0($data['phone0']);
        $customer->setPhone1($data['phone1']);
        $customer->setPhone2($data['phone2']);
        $customer->setParentName($data['parent_name']);
        $customer->setAddress($data['address']);
        $customer->setPostalCode($data['postal_code']);
        $customer->setCity($data['city']);
        $customer->setLicenseNumber($data['license_number']);
        $customer->setBirthday($data['birthday']);
        $customer->setSubscribeDate($data['subscribe_date']);
        $customer->setDescription($data['description']);
        $customer->setActive(isset($data['active']) ? true : false);
        
        $customer->save();
    }
}