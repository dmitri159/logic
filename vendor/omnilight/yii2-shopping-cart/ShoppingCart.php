<?php

namespace yz\shoppingcart;

use Yii;
use yii\base\Component;
use yii\base\Event;
use yii\di\Instance;
use yii\web\Session;
use app\models\Promocode;


/**
 * Class ShoppingCart
 * @property CartPositionInterface[] $positions
 * @property int $count Total count of positions in the cart
 * @property int $cost Total cost of positions in the cart
 * @property bool $isEmpty Returns true if cart is empty
 * @property string $hash Returns hash (md5) of the current cart, that is uniq to the current combination
 * of positions, quantities and costs
 * @property string $serialized Get/set serialized content of the cart
 * @package \yz\shoppingcart
 */
class ShoppingCart extends Component
{
    /** Triggered on position put */
    const EVENT_POSITION_PUT = 'putPosition';
    /** Triggered on position update */
    const EVENT_POSITION_UPDATE = 'updatePosition';
    /** Triggered on after position remove */
    const EVENT_BEFORE_POSITION_REMOVE = 'removePosition';
    /** Triggered on any cart change: add, update, delete position */
    const EVENT_CART_CHANGE = 'cartChange';
    /** Triggered on after cart cost calculation */
    const EVENT_COST_CALCULATION = 'costCalculation';

    const MALAYSIA = 10;
    const SINGAPORE = 20; 
    const BRUNEI = 25;
    /**
     * If true (default) cart will be automatically stored in and loaded from session.
     * If false - you should do this manually with saveToSession and loadFromSession methods
     * @var bool
     */
    public $storeInSession = true;
    /**
     * Session component
     * @var string|Session
     */
    public $session = 'session';
    /**
     * Shopping cart ID to support multiple carts
     * @var string
     */
    public $cartId = __CLASS__;

    public $cartTotal = 'cartTotal';
    /**
     * @var CartPositionInterfac[]
     */
    protected $_positions = [];

    protected $_retail = 0;

    protected $_total = 0;

    protected $_totalBefore = 0;

    protected $_totalAfter = 0;

    protected $_discount = 0;

    protected $_shipping = 0;

    protected $_country = 0;

    protected $_promocode = '';

    protected $_count = 0;

    public $test = 0;

    public function init()
    {
        if ($this->storeInSession)
            $this->loadFromSession();
    }

    public function setTest($test) {
        $this->test = $test;
    }

    public function getTest() {
        return $this->test;
    }

    /**
     * Loads cart from session
     */
    public function loadFromSession()
    {
        $this->session = Instance::ensure($this->session, Session::className());
        if (isset($this->session[$this->cartId]))
            $this->setSerialized($this->session[$this->cartId]);
        if (isset($this->session[$this->cartTotal]))
            $this->setSerializeTotal($this->session[$this->cartTotal]);
    }

    /**
     * Saves cart to the session
     */
    public function saveToSession()
    {
        $this->session = Instance::ensure($this->session, Session::className());
        $this->session[$this->cartId] = $this->getSerialized();
        $this->session[$this->cartTotal] = $this->getSerializeTotal();
    }

    /**
     * Sets cart from serialized string
     * @param string $serialized
     */
    public function setSerialized($serialized)
    {
        $this->_positions = unserialize($serialized);
    }

    public function setSerializeTotal($serialized)
    {
        $temp = unserialize($serialized);
        $this->_retail = $temp['_retail'];
        $this->_total = $temp['_total'];
        $this->_totalBefore = $temp['_totalBefore'];
        $this->_totalAfter = $temp['_totalAfter'];
        $this->_discount = $temp['_discount'];
        $this->_shipping = $temp['_shipping'];
        $this->_country = $temp['_country'];
        $this->_promocode = $temp['_promocode'];
        $this->_count = $temp['_count'];
    }

    /**
     * @param CartPositionInterface $position
     * @param int $quantity
     */
    public function put($position, $quantity = 1)
    {

        if (isset($this->_positions[$position->getId()])) {
            $this->_positions[$position->getId()]->setQuantity(
                $this->_positions[$position->getId()]->getQuantity() + $quantity);
        } else {
            $position->setQuantity($quantity);
            $this->_positions[$position->getId()] = $position;
        }
        $this->trigger(self::EVENT_POSITION_PUT, new CartActionEvent([
            'action' => CartActionEvent::ACTION_POSITION_PUT,
            'position' => $this->_positions[$position->getId()],
        ]));
        $this->trigger(self::EVENT_CART_CHANGE, new CartActionEvent([
            'action' => CartActionEvent::ACTION_POSITION_PUT,
            'position' => $this->_positions[$position->getId()],
        ]));
        if ($this->storeInSession)
            $this->saveToSession();
    }

    /**
     * Returns cart positions as serialized items
     * @return string
     */
    public function getSerialized()
    {
        return serialize($this->_positions);
    }

    public function getSerializeTotal()
    {   
        return serialize($this->getCartTotal());
    }

    /**
     * @param CartPositionInterface $position
     * @param int $quantity
     */
    public function update($position, $quantity)
    {
        if ($quantity <= 0) {
            $this->remove($position);
            return;
        }

        if (isset($this->_positions[$position->getId()])) {
            $this->_positions[$position->getId()]->setQuantity($quantity);
        } else {
            $position->setQuantity($quantity);
            $this->_positions[$position->getId()] = $position;
        }
        $this->trigger(self::EVENT_POSITION_UPDATE, new CartActionEvent([
            'action' => CartActionEvent::ACTION_UPDATE,
            'position' => $this->_positions[$position->getId()],
        ]));
        $this->trigger(self::EVENT_CART_CHANGE, new CartActionEvent([
            'action' => CartActionEvent::ACTION_UPDATE,
            'position' => $this->_positions[$position->getId()],
        ]));
        if ($this->storeInSession)
            $this->saveToSession();
    }

    /**
     * Removes position from the cart
     * @param CartPositionInterface $position
     */
    public function remove($position)
    {
        $this->removeById($position->getId());
    }

    /**
     * Removes position from the cart by ID
     * @param string $id
     */
    public function removeById($id)
    {
        $this->trigger(self::EVENT_BEFORE_POSITION_REMOVE, new CartActionEvent([
            'action' => CartActionEvent::ACTION_BEFORE_REMOVE,
            'position' => $this->_positions[$id],
        ]));
        $this->trigger(self::EVENT_CART_CHANGE, new CartActionEvent([
            'action' => CartActionEvent::ACTION_BEFORE_REMOVE,
            'position' => $this->_positions[$id],
        ]));
        unset($this->_positions[$id]);
        if ($this->storeInSession)
            $this->saveToSession();
    }

    /**
     * Remove all positions
     */
    public function removeAll()
    {
        $this->_positions = [];
        $this->_retail = 0;
        $this->_total = 0;
        $this->_totalBefore =  0;
        $this->_totalAfter =  0;
        $this->_discount = 0;
        $this->_shipping = 0;
        $this->_country = 0;
        $this->_promocode = '';
        $this->_count = 0;
        $this->trigger(self::EVENT_CART_CHANGE, new CartActionEvent([
            'action' => CartActionEvent::ACTION_REMOVE_ALL,
        ]));
        if ($this->storeInSession)
            $this->saveToSession();
    }

    public function removeProduct()
    {
        $this->_positions = [];
        $this->_retail = 0;
        $this->_total = 0;
        $this->_totalBefore =  0;
        $this->_totalAfter =  0;
        $this->_count = 0;
        $this->trigger(self::EVENT_CART_CHANGE, new CartActionEvent([
            'action' => CartActionEvent::ACTION_REMOVE_ALL,
        ]));
        if ($this->storeInSession)
            $this->saveToSession();
    }

    public function removePromo()
    {
        $this->_promocode = '';
        $this->_discount = 0;
        $this->calculate();
        $this->trigger(self::EVENT_CART_CHANGE, new CartActionEvent([
            'action' => CartActionEvent::ACTION_REMOVE_ALL,
        ]));
        if ($this->storeInSession)
            $this->saveToSession();
    }

    /**
     * Returns position by it's id. Null is returned if position was not found
     * @param string $id
     * @return CartPositionInterface|null
     */
    public function getPositionById($id)
    {
        if ($this->hasPosition($id))
            return $this->_positions[$id];
        else
            return null;
    }

    /**
     * Checks whether cart position exists or not
     * @param string $id
     * @return bool
     */
    public function hasPosition($id)
    {
        return isset($this->_positions[$id]);
    }

    /**
     * @return CartPositionInterface[]
     */
    public function getPositions()
    {
        return $this->_positions;
    }

    /**
     * @param CartPositionInterface[] $positions
     */
    public function setPositions($positions)
    {
        $this->_positions = array_filter($positions, function (CartPositionInterface $position) {
            return $position->quantity > 0;
        });
        $this->trigger(self::EVENT_CART_CHANGE, new CartActionEvent([
            'action' => CartActionEvent::ACTION_SET_POSITIONS,
        ]));
        if ($this->storeInSession)
            $this->saveToSession();
    }

    /**
     * Returns true if cart is empty
     * @return bool
     */
    public function getIsEmpty()
    {
        return count($this->_positions) == 0;
    }

    /**
     * @return int
     */
    public function getCount()
    {
        $count = 0;
        foreach ($this->_positions as $position)
            $count += $position->getQuantity();
        return $count;
    }

    /**
     * Return full cart cost as a sum of the individual positions costs
     * @param $withDiscount
     * @return int
     */
    public function getCost($withDiscount = false)
    {
        $cost = 0;
        foreach ($this->_positions as $position) {
            $cost += $position->getCost($withDiscount);
        }
        $costEvent = new CostCalculationEvent([
            'baseCost' => $cost,
        ]);
        $this->trigger(self::EVENT_COST_CALCULATION, $costEvent);
        if ($withDiscount)
            $cost = max(0, $cost - $costEvent->discountValue);
        return $cost;
    }

    /**
     * Returns hash (md5) of the current cart, that is unique to the current combination
     * of positions, quantities and costs. This helps us fast compare if two carts are the same, or not, also
     * we can detect if cart is changed (comparing hash to the one's saved somewhere)
     * @return string
     */
    public function getHash()
    {
        $data = [];
        foreach ($this->positions as $position) {
            $data[] = [$position->getId(), $position->getQuantity(), $position->getPrice()];
        }
        return md5(serialize($data));
    }

    public function getPrice()
    {
        $cost = 0;
        foreach ($this->_positions as $position) {
            $cost += ($position->getRetail() * $position->getQuantity());
        }
        return $cost;
    }

    public function calculate() {
        $this->_count = $this->getCount();
        $this->_totalBefore = $this->getCost();
        if(isset($this->_promocode)) {
            $query = Promocode::find()->where(['p_code' =>  $this->_promocode])->one();
            if($query) {
                if($query['p_type'] == 'F') {
                    if(($this->_count >= $query['p_quantity']) && ($this->_totalBefore >= $query['p_total'])) {
                        $this->_discount = $query['p_discount'];
                        $this->_totalAfter = $this->_totalBefore - $this->_discount;
                    }
                }
                else if($query['p_type'] == 'P') {
                    if($this->_count >= $query['p_quantity'] && $this->_totalBefore >= $query['p_total']) {
                        $this->_discount = $query['p_discount'];
                        $this->_totalAfter = ($this->_totalBefore * ( 100 - $this->_discount)) / 100;
                    }
                }
            }
            else 
                $this->_totalAfter = $this->_totalBefore;
        }
        else 
            $this->_totalAfter = $this->_totalBefore;
        if(isset($this->_country)) {
            if($this->_country == 1) {
                if($this->_count < 2 || $this->_totalBefore < 150) {
                    $this->_shipping = self::MALAYSIA;
                }
                else 
                    $this->_shipping = 0;
            }
            else if($this->_country == 2) {
                if($this->_totalBefore < 300) {
                    $this->_shipping = self::SINGAPORE;
                }
                else 
                    $this->_shipping = 0;
            } 
            else if($this->_country == 3) {
                if($this->_totalBefore < 300) {
                    $this->_shipping = self::BRUNEI;
                }
                else 
                    $this->_shipping = 0;
            }
            else {
                $this->_shipping = 0;
            }
        }
        else {
            $this->_shipping = 0;
        }
        $this->_total = $this->_totalAfter + $this->_shipping;
        if ($this->storeInSession)
            $this->saveToSession();
    }

    public function calculateCart($post) {
        $message = '';
        $error = false;
        if(isset($post)) {
            if(isset($post['checkout'])) {
            }
            else if(isset($post['validate'])) {
                if(isset($post['promocode'])) {
                    $promocode = strtoupper($post['promocode']);
                    $query = Promocode::find()->where(['p_code' =>  $promocode])->one();
                    if(!$query) {
                        $message = 'Empty';
                        $error = true;
                    }
                    else {
                        if($query['p_type'] == 'F') {
                            if($this->_count >= $query['p_quantity'] && $this->_totalBefore >= $query['p_total']) {
                                $message = 'Success ADDED '.$query['p_code'];
                                $this->_discount = $query['p_discount'];
                                $this->_totalAfter = $this->_totalBefore - $this->_discount;
                                $this->_promocode = $promocode;
                            }
                            else {
                                if($query['p_quantity']) {
                                    $message = 'BUY '.($query['p_quantity'] - $this->_count).' MORE PRODUCTS TO GET DISCOUNT';
                                    $error = true;
                                }
                                else if($query['p_total']) {
                                    $message = 'SPEND '.($query['p_total'] - $this->_totalBefore).' MORE TO GET DISCOUNT';
                                    $error = true;
                                }                                
                            }
                        }
                        else if($query['p_type'] == 'P') {
                            if($this->_count >= $query['p_quantity'] && $this->_totalBefore >= $query['p_total']) {
                                $message = 'Success ADDED '.$query['p_code'];
                                $this->_discount = $query['p_discount'];
                                $this->_totalAfter = ($this->_totalBefore * ( 100 - $this->_discount)) / 100;
                                $this->_promocode = $promocode;
                            }
                            else {
                                if($query['p_quantity']) {
                                    $message = 'BUY '.($query['p_quantity'] - $this->_count).' MORE PRODUCTS TO GET DISCOUNT';
                                    $error = true;
                                }
                                else if($query['p_total']) {
                                    $message = 'SPEND '.($query['p_total'] - $this->_totalBefore).' MORE TO GET DISCOUNT';
                                    $error = true;
                                }                                
                            }
                        }
                        else {
                            $this->_discount = 0;
                            $this->_totalAfter = $this->_totalBefore;
                        }
                    }
                }
                if(isset($post['country'])) {
                    $this->_country = $post['country'];
                    if($this->_country == 1) {
                        if($this->_count < 2 || $this->_totalBefore < 150) {
                            $this->_shipping = self::MALAYSIA;
                        }
                        else 
                            $this->_shipping = 0;
                    }
                    else if($this->_country == 2) {
                        if($this->_totalBefore < 300) {
                            $this->_shipping = self::SINGAPORE;
                        }
                        else 
                            $this->_shipping = 0;
                    } 
                    else if($this->_country == 3) {
                        if($this->_totalBefore < 300) {
                            $this->_shipping = self::BRUNEI;
                        }
                        else 
                            $this->_shipping = 0;
                    }
                    else {
                        $this->_shipping = 0;
                    }
                }
                else {
                    $this->_shipping = 0;
                }
            }
        }
        if($this->_totalAfter)
        $this->_total = $this->_totalAfter + $this->_shipping;
        $this->trigger(self::EVENT_CART_CHANGE, new CartActionEvent([
            'action' => CartActionEvent::ACTION_CALCULATE,
        ]));
        if ($this->storeInSession)
            $this->saveToSession();
        return array(
            'message' => $message,
            'error' => $error,
            'promocode' => $this->_promocode,
            'country' => $this->_country
        );
    }


    public function getCartTotal() {
        return Array(
            '_retail' => $this->getPrice(),
            '_total' => $this->_total,
            '_totalBefore' => $this->_totalBefore,
            '_totalAfter' => $this->_totalAfter,
            '_discount' => $this->_discount,
            '_shipping' => $this->_shipping,
            '_count' => $this->_count,
            '_country' => $this->_country,
            '_promocode' => $this->_promocode
        );
    }
}
