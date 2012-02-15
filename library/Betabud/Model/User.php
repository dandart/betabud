<?php
class Betabud_Model_User extends Betabud_Model_Abstract_Base
{
    const FIELD_Username = Betabud_Dao_Mongo_Abstract::FIELD_Id;
    const FIELD_Password = 'Password';
    const FIELD_Nick = 'Nick';
    const FIELD_Email = 'Email';
    const CHILD_ASSOC_Credentials = 'Credentials';

    const SALT = 'dguqwtduR^%$*%%';

    protected static $_arrFields = array(
        self::FIELD_Username => 'Betabud_Model_Field_FieldId',
        self::FIELD_Password => 'Betabud_Model_Field_Field',
        self::FIELD_Nick => 'Betabud_Model_Field_Field',
        self::FIELD_Email => 'Betabud_Model_Field_Field',
        self::CHILD_ASSOC_Credentials => 'Betabud_Model_Field_Collection_Assoc'
    );

    public static function create($strUsername, $strPassword)
    {
        $modelUser = new self();
        $modelUser->_setField(self::FIELD_Username, self::encodeUsername($strUsername));
        $modelUser->_setField(self::FIELD_Password, self::encodePassword($strPassword));
        return $modelUser;
    }

    public static function encodeUsername($strUsername)
    {
        return strtolower($strUsername);
    }

    /** I'm not sure about this */
    public static function encodePassword($strPassword)
    {
        return sha1(self::SALT.$strPassword);
    }
    
    public function setPassword($strPassword)
    {
        $this->_setField(self::FIELD_Password, self::encodePassword($strPassword));
    }

    public function getUsername()
    {
        return $this->_getField(self::FIELD_Username, null);
    }

    public function getNick()
    {
        return $this->_getField(self::FIELD_Nick, null);
    }

    public function setNick($strNick)
    {
        $this->_setField(self::FIELD_Nick, $strNick);
    }

    public function getEmail()
    {
        return $this->_getField(self::FIELD_Email, null);
    }

    public function setEmail($strEmail)
    {
        $this->_setField(self::FIELD_Email, $strEmail);
    }

    public function __get($strProperty)
    {
        return $this->_getField($strProperty, null);
    }

    public function __set($strProperty, $strValue)
    {
        $this->_setField($strProperty, $strValue);
    }

    public function save()
    {
        return Betabud_Gateway::getInstance()->getUser()->save($this);
    }
}
