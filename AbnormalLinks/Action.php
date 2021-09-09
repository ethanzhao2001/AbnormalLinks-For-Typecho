<?php
session_start();
class AbnormalLinks_Action extends Widget_Abstract_Contents implements Widget_Interface_Do
{
    public function action()
    {
        $user = Typecho_Widget::widget('Widget_User');
        if (!$user->pass('administrator')) {
            die('未登录用户!');
        }
        if ($_GET['action'] === 'links') {
            $db = Typecho_Db::get();
            $links = $db->fetchAll($db->select('lid', 'name', 'url')->from('table.links')->where('sort != ?', 'others'));
            echo json_encode($links);
        }
        if ($_GET['action'] === 'del') {
            $dates = json_decode($_POST['data']);
            $db = Typecho_Db::get();
            foreach ($dates as $date) {
                $update = $db->update('table.links')->rows(array('sort'=>'others'))->where('lid = ?',$date);
                $updateRows= $db->query($update);
            }
        }
        if ($_GET['action'] === 'post') {
            $_SESSION['data']=$_POST['data'];
        }
    }
}
