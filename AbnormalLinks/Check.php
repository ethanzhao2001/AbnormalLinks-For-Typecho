<?php
include 'common.php';
include 'header.php';
include 'menu.php';
$stat = Typecho_Widget::widget('Widget_Stat');
$user = Typecho_Widget::widget('Widget_User');
if (!$user->pass('administrator')) {
    die('未登录用户!');
}
?>

<head>
    <style type="text/css">
        .description {
            margin: .5em 0 1em;
            color: #999;
            font-size: .92857em;
        }
    </style>
    <script src="https://cdn.bootcdn.net/ajax/libs/jquery/3.6.0/jquery.js"></script>
    <script type="text/javascript" charset="utf-8">
        var urls_arr = new Array();
        var errurls_arr = new Array();

        function check() {
            $.ajax({
                type: "GET",
                url: "/action/AbnormalLinks_action?action=links",
                success: function(data) {
                    //console.log(data);
                    urls_arr = JSON.parse(data);
                    var num = 0;
                    var i = 0;
                    urls_arr.forEach(function(value, index) {
                        check_get(value).then(function(resolve, reject) {
                            //console.log(resolve);
                            if (resolve === false) {
                                errurls_arr[i] = value;
                                i++;
                            }
                            num++;
                            if (num === urls_arr.length) {
                                errurls_json = JSON.stringify(errurls_arr);
                                //console.log(errurls_json);
                                $.ajax({
                                    type: "POST",
                                    url: "/action/AbnormalLinks_action?action=post",
                                    dataType: "json",
                                    data: {
                                        data: errurls_json,
                                    },
                                    success: setTimeout(window.location.href='/admin/extending.php?panel=AbnormalLinks%2FCheck.php&action=start', 3000),
                                });
                            }
                        });
                    });
                }
            });
        };

        function check_get(value) {
            return new Promise(function(resolve, reject) {
                $.ajax({
                    type: 'GET',
                    url: value.url,
                    dataType: 'jsonp',
                    timeout: 10000,
                    complete: function(res) {
                        if (res.status === 200) {
                            //console.log('有效链接');
                            //console.log(value);
                            resolve(true);
                        } else {
                            //console.log('无效链接');
                            //console.log(value);
                            resolve(false);
                        }
                    }
                });
            });
        }

        function del() { //jquery获取复选框值
            var chk_value = [];
            var chk_value_json;
            $('input[name="select"]:checked').each(function() {
                chk_value.push($(this).val());
                chk_value_json = JSON.stringify(chk_value);
            });
            $.ajax({
                type: "POST",
                url: "/action/AbnormalLinks_action?action=del",
                data: {
                    "data": chk_value_json,
                },
            })
            success: setTimeout(window.location.href='/admin/extending.php?panel=AbnormalLinks%2FCheck.php&action=end',1000);
        }
    </script>
</head>
<div class="main">
    <div class="body container">
        <div class="typecho-page-title">
            <h2>异常友链检查</h2>
            <button onclick="check()" class="btn primary">立即检查</button>
            <p class="description">如果友链较多，检查速度可能较慢</p>
            <?php
            if ($_GET['action'] === 'display') {
                $check = $_SESSION['data'];
                $AbnormalLinks = json_decode($check, true);
                //var_dump($AbnormalLinks[0]);
                if ($AbnormalLinks[0] === NULL) {
                    echo '<h3>没有异常友链</h3>';
                    echo '<p style="color:green">稍后自动返回页面</p>';
                    header("Refresh:6;url=/admin/extending.php?panel=AbnormalLinks%2FCheck.php");
                } else {
                    echo '<h3>异常友链如下</h3>';
                    echo '<form>';
                    foreach ($AbnormalLinks as $invalidlink) {
                        echo '<input type="checkbox" name="select" value="' . $invalidlink['lid'] . '" /> <a href="' . $invalidlink['url'] . '"> ' . $invalidlink['name'] . '</a><br>';
                    }
                    echo '</form>';
                    echo '<button onclick="del()" class="btn primary" style="margin: 1em 0 0">设为失效</button>';
                }
            }
            if ($_GET['action'] === 'end') {
                Typecho_Widget::widget('Widget_Notice')->set(_t("设置失效完成"), 'success');
                header("location:" . Typecho_Common::url('/extending.php?panel=AbnormalLinks%2FCheck.php', Helper::options()->adminUrl));
            }
            if ($_GET['action'] === 'start') {
                Typecho_Widget::widget('Widget_Notice')->set(_t("检查完成"), 'success');
                header("location:" . Typecho_Common::url('/extending.php?panel=AbnormalLinks%2FCheck.php&action=display', Helper::options()->adminUrl));
            }
            ?>
        </div>
    </div>
</div>
<?php
include 'copyright.php';
include 'common-js.php';
include 'footer.php';
?>