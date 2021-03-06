<?php
class Betabud_Model_User_Helper_UserType
{
    const TYPE_GUEST = 'Guest';
    const TYPE_USER = 'User';
    const TYPE_SILVER = 'Silver Subscriber';
    const TYPE_GOLD = 'Gold Subscriber';
    const TYPE_MINION = 'Minion';
    const TYPE_GOD = 'God';

    const ID_GUEST = 0;
    const ID_USER = 1;
    const ID_SILVER = 2;
    const ID_GOLD = 3;
    const ID_MINION = 4;
    const ID_GOD = 5;

    private static $_arrTypes = array(
        self::ID_GUEST => self::TYPE_GUEST,
        self::ID_USER => self::TYPE_USER,
        self::ID_SILVER => self::TYPE_SILVER,
        self::ID_GOLD => self::TYPE_GOLD,
        self::ID_MINION => self::TYPE_MINION,
        self::ID_GOD => self::TYPE_GOD
    );

    private $_intTypeId;

    public function __construct($intTypeId)
    {
        if(!isset(self::$_arrTypes[$intTypeId])) {
            throw new Betabud_Model_User_Helper_Exception_UnknownType($intTypeId);
        }
        $this->_intTypeId = $intTypeId;
    }

    public function getUserTypeId()
    {
        return $this->_intTypeId;
    }

    public function getUserType()
    {
        return self::$_arrTypes[$this->_intTypeId];
    }
}
