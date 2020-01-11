<?php
/**
 * Created by PhpStorm.
 * User: modieza
 * Date: 1/11/2020
 * Time: 3:43 PM
 */

class MyAccountLink extends ObjectModel {
    public $link;
    public $position;
    public $active;
    public $title;

    public static $definition = array(
        'table' => 'myaccountblock',
        'primary' => 'id_block',
        'multilang' => true,
        'fields' => array(
            'link'      =>		array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml', 'size' => 255),
            'position'   =>     array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => true),
            'active'     =>     array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => true),
//            Lang
            'title'      =>		array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCleanHtml', 'size' => 255)
        )
    );

    public function __construct($id_block = null, $id_lang = null, $id_shop = null, Context $context = null)
    {
        if($context)
            $this->context = $context;
        else $context = Context::getContext();

        $this->_link = $context->link;
        parent::__construct($id_block, $id_lang, $id_shop);
    }

    public function getLink() {
        return Context::getContext()->link->getPageLink($this->link, true);
    }
}