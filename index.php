<?php
require_once 'header.php';
$page_title = '首頁';

// die(var_dump($_SESSION));

$op = isset($_REQUEST['op']) ? filter_var($_REQUEST['op']) : '';
$sn = isset($_REQUEST['sn']) ? (int) $_REQUEST['sn'] : 0;
switch ($op) {

    default:
        if ($sn) {
            show_article($sn);
            $op = 'show_article';
        } else {
            list_article();
            $op = 'list_article';
        }
        break;
}

require_once 'footer.php';

/*************函數區**************/

//讀出單一文章
function show_article($sn)
{
    global $db, $smarty;

    $sql    = "SELECT * FROM `article` WHERE `sn`='$sn'";
    $result = $db->query($sql) or die($db->error);
    $data   = $result->fetch_assoc();
    $smarty->assign('article', $data);
}

//讀出所有文章
function list_article()
{
    global $db, $smarty;

    $sql    = "SELECT * FROM `article` ORDER BY `update_time` DESC";
    $result = $db->query($sql) or die($db->error);
    $all    = [];
    $i      = 0;
    while ($data = $result->fetch_assoc()) {
        $all[$i] = $data;
        $wkstr1  = mb_substr(strip_tags($data['content']), 0, 90);
        if (mb_strlen(strip_tags($data['content'])) > mb_strlen($wkstr1)) {
            $wkstr1 .= "<span style='color:red;font-size:0.6em;font-weight:900;'><<更多.......>></span>";
        }
        $all[$i]['summary'] = $wkstr1;
        $i++;
    }
    // die(var_export($all));
    $smarty->assign('all', $all);
}
