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
use OpenEquestrianClubManagement\Model\OrderDetail;
use OpenEquestrianClubManagement\Model\OrderDetailPeer;
use OpenEquestrianClubManagement\Model\OrderDetailQuery;
use OpenEquestrianClubManagement\Model\CustomerQuery;
use OpenEquestrianClubManagement\Model\CardQuery;
use OpenEquestrianClubManagement\Model\Card;
use OpenEquestrianClubManagement\Extension\Twig\Format;


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
    
    
    /**
     * Add order action
     *
     * @return  Response
     * @access  public
     */
    public function addAction()
    {
        $form = $this->getForm(null);
        $cards = CardQuery::create()->orderByName()->find();
        $order_details = array();
        
        if ('POST' === $this->app['request']->getMethod()) {
            $form->bind($this->app['request']);
            
            if ($form->isValid()) {
                $data = $form->getData();
                
                $order = new OrderObject();
                $this->saveObject($order, $data);
                
                $this->saveDetails($order);
                
                return $this->app->redirect($this->app['url_generator']->generate('orders'));
            }
        }
        
        return $this->render('add.html.twig', array('form' => $form->createView(), 'cards' => $cards, 'order_details' => $order_details));
    }
    
    
    /**
     * Edit order action
     *
     * @return  Response
     * @access  public
     */
    public function editAction()
    {
        $id = $this->app['request']->get('id');
        if (empty($id) === true) {
            throw new Exception\Error404('La commande n\'existe pas');
        }
        
        $order = OrderQuery::create()->findOneById($id);
        if ($order === null) {
            throw new Exception\Error404('La commande n\'existe pas');
        }
        
        $form = $this->getForm($order);
        
        $cards = CardQuery::create()->orderByName()->find();
        $order_details = $order->getOrderDetails();
        
        if ('POST' === $this->app['request']->getMethod()) {
            $form->bind($this->app['request']);
            
            if ($form->isValid()) {
                $data = $form->getData();
                
                $this->saveObject($order, $data);
                
                $this->saveDetails($order);
                
                return $this->app->redirect($this->app['url_generator']->generate('orders'));
            }
        }
        
        return $this->render('edit.html.twig', array('form' => $form->createView(), 'order' => $order, 'cards' => $cards, 'order_details' => $order_details));
    }
    
    
    /**
     * Show all orders action
     *
     * @return  Response
     * @access  public
     */
    public function showAllAction()
    {
        $orders = OrderQuery::create()
                    ->orderByDate(\Criteria::DESC)
                    ->find();
        
        return $this->render('showAll.html.twig', array('orders' => $orders));
    }
    
    
    /**
     * Print order
     *
     * @return  Response
     * @access  Public
     */
    public function printAction()
    {
        $id = $this->app['request']->get('id');
        if (empty($id) === true) {
            throw new Exception\Error404('La commande n\'existe pas');
        }
        
        $order = OrderQuery::create()->findOneById($id);
        if ($order === null) {
            throw new Exception\Error404('La commande n\'existe pas');
        }
        
        $order_details = $order->getOrderDetails();
        
        $render = $this->render('print.html.twig', array('order' => $order, 'order_details' => $order_details, 'request' => $this->app['request']));
        
        $pdf = new \ApiPrintPdf();
        $pdf->setService(Config::get('print_service'));
        $pdf->setEmail(Config::get('print_email'));
        $pdf->setApiKey(Config::get('print_api_key'));
        $pdf->setContent($render);
        $pdf->setOptions(array(
            'title' => 'Facture '.$order->getNumOrder(),
            'footer-html' => '<div style="text-align:center;font-size:10.5px;border-top:1px solid #ddd">'.Config::get('company_name').' - SIRET : '.Config::get('company_siret').' - TVA. non applicable, art. 293 B du CGI.</div>',
        ));
        
        if ($pdf->callApi() === true) {
            $pdf->download('facture-'.$order->getNumOrder().'.pdf');
        }
        
        return $render;
    }
    
    
    /**
     * Get the add/edit form for the treatment apply
     *
     * @param   \OpenEquestrianClubManagement\Model\Order   $order    the order to load in the form (null else)
     * @return  \Symfony\Component\Form\Form                          the form component
     * @access  private
     */
    private function getForm(OrderObject $order = null)
    {
        $customers = CustomerQuery::create()
                        ->filterByActive(true)
                        ->orderByShowName()
                        ->find();
        
        $customers_list = array();
        
        foreach ($customers as $customer) {
            $customers_list[$customer->getId()] = $customer->getShowName();
        }
        
        $format = new Format();
        $states = OrderPeer::getValueSet(OrderPeer::STATE);
        $payment_methods = OrderPeer::getValueSet(OrderPeer::PAYMENT_METHOD);
        
        $states_list = array();
        $payment_methods_list = array();
        
        for ($i = 0, $count = count($states); $i < $count; $i++) {
            $states_list[$states[$i]] = $format->stateFilter($states[$i]);
        }
        for ($i = 0, $count = count($payment_methods); $i < $count; $i++) {
            $payment_methods_list[$payment_methods[$i]] = $format->paymentFilter($payment_methods[$i]);
        }
        
        $customer_id = null;
        $num_order = OrderPeer::generateNumOrder();
        $total = 0;
        $vat = Config::get('vat');
        $date = new \DateTime();
        $payment_method = null;
        $state = null;
        
        if ($order !== null) {
            $customer_id = $order->getCustomerId();
            $num_order = $order->getNumOrder();
            $total = $order->getTotal();
            $vat = $order->getVat();
            $date = $order->getDate(null);
            $payment_method = $order->getPaymentMethod();
            $state = $order->getState();
        }
        
        $form = $this->app['form.factory']->createBuilder('form')
                    ->add('customer_id', 'choice', array(
                        'data' => $customer_id,
                        'choices' => $customers_list,
                        'required' => true,
                        'label' => 'Client',
                        'empty_value' => 'Choisir un client'
                    ))
                    ->add('num_order', 'text', array(
                        'data' => $num_order,
                        'required' => false,
                        'label' => 'N° de commande',
                        'read_only' => true,
                        'disabled' => true,
                    ))
                    ->add('total', 'money', array(
                        'data' => $total,
                        'required' => false,
                        'label' => 'Montant total de la commande',
                        'read_only' => true,
                        'disabled' => true,
                        'currency' => false,
                    ))
                    ->add('vat', 'percent', array(
                        'data' => $vat,
                        'required' => false,
                        'label' => 'Taux de TVA',
                        'read_only' => true,
                        'disabled' => true,
                        'precision' => 2,
                        'type' => 'integer',
                    ))
                    ->add('date', 'date', array(
                        'data' => $date,
                        'required' => true,
                        'widget' => 'single_text',
                        'label' => 'Date de la commande',
                    ))
                    ->add('payment_method', 'choice', array(
                        'data' => $payment_method,
                        'choices' => $payment_methods_list,
                        'required' => true,
                        'multiple' => false,
                        'expanded' => true,
                        'label' => 'Méthode de paiement',
                    ))
                    ->add('state', 'choice', array(
                        'data' => $state,
                        'choices' => $states_list,
                        'required' => true,
                        'multiple' => false,
                        'expanded' => true,
                        'label' => 'État de la commande',
                    ))
                    ->getForm();
        
        return $form;
    }
    
    
    /**
     * Save the object with the datas of the form
     *
     * @param   \OpenEquestrianClubManagement\Model\Order   $order  the Order object to save
     * @param   array                                       $data   the form's datas
     * @access  private
     */
    private function saveObject(OrderObject $order, $data)
    {
        $order->setCustomerId($data['customer_id']);
        $order->setDate($data['date']);
        $order->setPaymentMethod($data['payment_method']);
        $order->setState($data['state']);
        
        if ($order->isNew()) {
            $order->setNumOrder(OrderPeer::generateNumOrder());
            $order->setVat(Config::get('vat'));
            $order->setTotal(0); // Init to 0 : calculate after
        }
        
        $order->save();
    }
    
    private function saveDetails(OrderObject $order)
    {
        $criteria = new \Criteria();
        $criteria->add(OrderDetailPeer::ORDER_ID, $order->getId());
        OrderDetailPeer::doDelete($criteria);
        
        $order_details = $this->app['request']->get('form_order_detail', array());
        
        if (is_array($order_details) === false) {
            throw new Exception\Security('order_details must be an array');
        }
        
        $card_ids = $order_details['card_id'];
        $quantities = $order_details['quantity'];
        
        if (count($card_ids) !== count($quantities)) {
            throw new Exception\Security('card_id and quantity must have same count');
        }
        
        $total = 0;
        
        for ($i = 0, $count = count($card_ids); $i < $count; $i++) {
            $card = CardQuery::create()->findOneById($card_ids[$i]);
            if ($card === null) {
                throw new Exception\Security('card doesn\'t exist');
            }
            
            $order_detail = new OrderDetail();
            $order_detail->setCard($card);
            $order_detail->setName($card->getName());
            $order_detail->setAmount($card->getPrice());
            $order_detail->setVat(Config::get('vat'));
            $order_detail->setQuantity((int)$quantities[$i]);
            $order_detail->setOrder($order);
            
            $order_detail->save();
            
            $total += $card->getPrice() * $order_detail->getQuantity();
        }
        
        $order->setTotal($total);
        $order->save();
    }
    
    
    private function saveAllowedLesson($order)
    {
        
    }
}