<?php
/**
// * Created by PhpStorm.
// * User: modieza
// * Date: 1/11/2020
// * Time: 12:40 PM
// */
//
if (!defined('_PS_VERSION_'))
    exit;

include_once(_PS_MODULE_DIR_.'custommyaccountfooter/MyAccountLink.php');

class CustomMyAccountFooter extends Module {

    protected $default_links = array(
        'titles' => array('Account', 'Addresses', 'Contact Us', 'Order'),
        'links' => array('identity', 'addresses', 'contact', 'order'));
    protected $links = array();

    public function __construct()
    {
        $this->name = "custommyaccountfooter";
        $this->tab = "front_office_features";
        $this->version = "1.6.1";
        $this->author = "Modestas Slivinskas";
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => '1.7.99.99');
        $this->bootstrap  = true;
        $this->block = new MyAccountLink();

        parent::__construct();

        $this->displayName = $this->l('Custom My Account Footer');
        $this->description = $this->l('Allows to customize footer my account block.');
        $this->confirmationUninstall = $this->l("Do you really want to uninstall this module?");
    }

    public function install() {
        if(Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }
        if(!parent::install() || !$this->registerHook('footer')) {
            return false;
        }

        $res = $this->createTables();
        $this->installSamples();
        return $res;
    }

    public function uninstall() {
        if(!parent::uninstall())
            return false;
        return $this->deleteTables();
    }

    public function getContent() {
        $this->getLinks();
        return "It works";
    }

    protected function createTables() {
//        Blocks
        $res = (bool)Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'. _DB_PREFIX_.'myaccountblock` (
        `id_block` int(10) unsigned NOT NULL AUTO_INCREMENT,
        `link` varchar(255) NOT NULL,
        `position` int(2) unsigned NOT NULL,
        `active` tinyint(1) unsigned NOT NULL,
        PRIMARY KEY(`id_block`)
        ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
        ');

//        Block names
        $res &= (bool)Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'. _DB_PREFIX_.'myaccountblock_lang` (
        `id_block` int(10) unsigned NOT NULL,
        `id_lang` int(10) unsigned NOT NULL,
        `title` varchar(255) NOT NULL,
        PRIMARY KEY(`id_block`, `id_lang`)
        ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
        ');

        return $res;
    }

    protected function deleteTables() {
        return (bool)Db::getInstance()->execute('
            DROP TABLE IF EXISTS `'._DB_PREFIX_.'myaccountblock`, `'._DB_PREFIX_.'myaccountblock_lang`;
        ');
    }

    public function hookDisplayFooter() {
//        throw new PrestaShopException();
        $this->getLinks();
        $this->context->smarty->assign(array(
            'links' => $this->links
        ));
        return $this->display(__FILE__, 'custommyaccountfooter.tpl');
    }

    protected function installSamples() {
        $languages = Language::getLanguages(false);
        foreach ($this->default_links['links'] as $key => $link) {
            $new_link = new MyAccountLink();
            $new_link->position = $key;
            $new_link->link = $link;
            $new_link->active = 1;
            foreach ($languages as $lang) {
                $new_link->title[$lang['id_lang']] = $this->default_links['titles'][$key];
            }
            $new_link->add();
        }
    }

    protected function getLinks() {
        $rows = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_ . 'myaccountblock`');

        foreach ($rows as $row) {
            $link = new MyAccountLink($row['id_block']);
            array_push($this->links, $link);
        }
    }
}