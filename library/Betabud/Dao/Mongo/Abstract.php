<?php
abstract class Betabud_Dao_Mongo_Abstract implements Betabud_Dao_Interface
{
    const FIELD_Id = Mongo_Connection::MONGO_FIELD_ID;

    protected $_strCollection = null; // You must set this!
    protected $_strDatabase = 'Betabud';

    private $_mongoCollection;
    private $_mongoConnection;

    public function __construct()
    {
    }

    abstract protected function _getCollectionName();

    protected function _save(Betabud_Model_Abstract_Base $modelBase)
    {
        $arrCriteria = array(self::FIELD_Id => $modelBase->getId());
        $arrUpdate = array();
        $this->_saveCollection($modelBase->preUpdateFromDao($this), $arrUpdate);
        if(isset($arrUpdate['$set'][self::FIELD_Id])) {
            unset($arrUpdate['$set'][self::FIELD_Id]);
        }

        $this->_getCollection()->updateArray($arrCriteria, $arrUpdate);
    }

    private function _saveCollection(Betabud_Model_Field_Collection_Abstract $collection, &$arrUpdate, $strPrefix = '')
    {
        if(!$collection->isEmpty()) {
            foreach($collection as $strFieldName => $objField) {
                if($objField->isDirty()) {
                    $location = $strPrefix.$strFieldName;
                    $strClass = get_class($objField);
                    switch($strClass) {
                        case 'Betabud_Model_Field_Collection_Assoc':
                            $this->_saveCollection($objField, $arrUpdate, $strPrefix.'.');
                            break;
                        case 'Betabud_Model_Field_Field':
                        case 'Betabud_Model_Field_FieldId':
                            $arrUpdate['$set'][$location] = $objField->getValue(null);
                            break;
                        case 'Betabud_Model_Field_Array':
                            $arrUpdate['$addToSet'][$location] = array(
                                '$each' => array(
                                    $objField->getValue()
                                )
                            );
                            break;
                        default:
                            throw new Exception('Not Implemented class '.$strClass);
                    }
                }
            }
        }
    }
    
    public function setMongoCollection(Mongo_Collection $mongoCollection)
    {
        $this->_mongoCollection = $mongoCollection;
    }  
    protected function _getCollection()
    {
        if(is_null($this->_mongoCollection))
            $this->_mongoCollection = new Mongo_Collection($this->_strDatabase, $this->_getCollectionName());
        return $this->_mongoCollection;
    }

    public function setMongoConnection(Mongo_Connection $mongoConnection)
    {
        $this->_mongoConnection = $mongoConnection;
    }   
    protected function _getConnection()
    {   
        if(is_null($this->_mongoConnection))
            $this->_mongoConnection = new Mongo_Connection();
        return $this->_mongoConnection;
    }

    protected function _runDistinct($strCollection, $strKey, Array $arrCommand)
    {
        return $this->_getConnection()->distinct($this->_strDatabase, $strCollection, $strKey, $arrCommand);
    }

   // abstract protected function _convertToModel(Array $arrMongo);

    // You must implement save()
}
