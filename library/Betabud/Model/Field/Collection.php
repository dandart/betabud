<?php
/**
 * This is a collection of any type of field.
**/
class Betabud_Model_Field_Collection extends Betabud_Model_Field_Abstract implements SeekableIterator, Serializable, Countable
{
    private $_arrFields = array();
    private $_arrKeys = array();
    private $_intOffset = 0;

    public function __construct(Array $arrFields = array())
    {
	$arrObjFields = array();
	// Not sure where this should go but...
	foreach($arrFields as $strKey => $field) {
	    $arrObjFields[$strKey] = new $field;
	}
        $this->_arrFields = $arrObjFields;
        $this->_arrKeys = array_keys($arrFields);
    }

    public function seek($strKey)
    {
        if(!isset($this->_arrFields[$strKey])) {
            throw new OutOfBoundsException($strKey. ' is not a valid key');
        }

        return $this->_arrFields[$strKey];
    }        

    public function current()
    {
        if($this->valid()) {
            throw new OutOfBoundsException($this->_intOffset. ' is not a valid offset');
        }    
        return $this->_arrFields[$this->_arrKeys[$this->_intOffset]];
    }

    public function key()
    {
        return $this->_intOffset;
    }

    public function next()
    {
        $this->_intOffset++;
    }

    public function rewind()
    {
        $this->_intOffset = 0;
    }

    public function valid()
    {
        return isset($this->_arrFields[$this->_arrKeys[$this->_intOffset]]);
    }
    
    public function count()
    {
        return count($this->_arrFields);
    }

    public function serialize()
    {
        return serialize($this->_arrFields);
    }

    public function unserialize($strSerialized)
    {
        $this->_arrFields = unserialize($strSerialized);
        $this->_arrKeys = array_keys($this->_arrFields);
    }
}
