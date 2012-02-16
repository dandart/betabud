<?php
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initAutoload()
    {
        $autoloader = Zend_Loader_Autoloader::getInstance();
        $autoloader->registerNamespace('Betabud_');
        $autoloader->registerNamespace('Mongo_');
    }

    protected function _initAcl()
    {
        $acl = new Zend_Acl();

        $acl->addRole(
            new Zend_Acl_Role(
                Betabud_Model_User_Helper_UserType::TYPE_GUEST
            )
        );

        $acl->addRole(
            new Zend_Acl_Role(
                Betabud_Model_User_Helper_UserType::TYPE_USER
            ),
            Betabud_Model_User_Helper_UserType::TYPE_GUEST
        );

        $acl->addRole(
            new Zend_Acl_Role(
                Betabud_Model_User_Helper_UserType::TYPE_SILVER
            ),
            Betabud_Model_User_Helper_UserType::TYPE_USER
        );

        $acl->addRole(
            new Zend_Acl_Role(
                Betabud_Model_User_Helper_UserType::TYPE_GOLD
            ),
            Betabud_Model_User_Helper_UserType::TYPE_SILVER
        );

        $acl->addRole(
            new Zend_Acl_Role(
                Betabud_Model_User_Helper_UserType::TYPE_MINION
            ),
            Betabud_Model_User_Helper_UserType::TYPE_GOLD
        );

        $acl->addRole(
            new Zend_Acl_Role(
                Betabud_Model_User_Helper_UserType::TYPE_GOD
            ),
            Betabud_Model_User_Helper_UserType::TYPE_MINION
        );
        
        $this->bootstrap('frontController');
        $this->frontController->registerPlugin(new Betabud_Controller_Plugin_Acl($acl));
        Zend_View_Helper_Navigation_HelperAbstract::setDefaultAcl($acl);
        Zend_View_Helper_Navigation_HelperAbstract::setDefaultRole(Betabud_Model_User_Helper_UserType::TYPE_GUEST);
        Zend_Registry::set('acl', $acl);
        
    }

    protected function _initConstants()
    {
        $config = new Zend_Config_Ini(APPLICATION_PATH . '/config/zetabud.ini');

        foreach($config->zb->toArray() as $key => $value)
        {
            define(strtoupper($key), $value);
        }
    }

    protected function _initRoutes()
    {
        $router = Zend_Controller_Front::getInstance()->getRouter();
/*
        $route = new Zend_Controller_Router_Route_Regex('.*-p(\d+).htm',
            array(
                'controller' => 'product',
                'action'     => 'index'
            ),
            array(1 => 'id')
        );
        $router->addRoute('product', $route);
        */
        $route = new Zend_Controller_Router_Route_Static(
            'logout',
            array(
                'controller' => 'login',
                'action' => 'logout'
            )
        );

        $router->addRoute('logout', $route);
        
        $route = new Zend_Controller_Router_Route_Static(
            'userinfo',
            array(
                'controller' => 'login',
                'action' => 'userinfo'
            )
        );

        $router->addRoute('userinfo', $route);
    }

    protected function _bootstrap($resource = null)
    {
        try {
            parent::_bootstrap($resource);
        } catch(Exception $e) {
            echo $e->getMessage();
        }
    } 

    public function run()
    {
       try {
           parent::run();
       } catch(Exception $e) {
           echo $e->getMessage();
       }
    }
}
