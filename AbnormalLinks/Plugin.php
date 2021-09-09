<?php

/**
 * 异常友链检查（后台管理->友链检查）
 * 
 * @package AbnormalLinks
 * @author 呆小萌
 * @version 1.0.1
 * @link https://www.zhaoyingtian.com/archives/95.html
 */
class AbnormalLinks_Plugin implements Typecho_Plugin_Interface
{
    /**
     * 激活插件方法,如果激活失败,直接抛出异常
     * 
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function activate()
    {
        Helper::addAction('AbnormalLinks_action', 'AbnormalLinks_Action');
        Helper::addPanel(3, 'AbnormalLinks/Check.php', '友链检查', '友链检查', 'administrator');
    }
    
    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     * 
     * @static
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function deactivate(){
        Helper::removeAction('AbnormalLinks_action');
        Helper::removePanel(3, 'AbnormalLinks/Check.php');
    }
    
    /**
     * 获取插件配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form 配置面板
     * @return void
     */
    public static function config(Typecho_Widget_Helper_Form $form)
    {
    }
    
    /**
     * 个人用户的配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form
     * @return void
     */
    public static function personalConfig(Typecho_Widget_Helper_Form $form){}
    
    /**
     * 插件实现方法
     * 
     * @access public
     * @return void
     */
    public static function render()
    {
    }
}
