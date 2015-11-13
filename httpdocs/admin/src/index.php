<?php
/*
require_once __DIR__.'/facebook.php';

// appId と secret は「マイアプリ」のページで確認できます
// https://www.facebook.com/developers/apps.php
$facebook = new Facebook(array(
    'appId'  => '165774716864201',
	'secret' => '7a722ec21b44e4a2e8d7a495838587b1',
));

// ログイン状態を取得します
$user = $facebook->getUser();

if ($user) {
    // メッセージが投稿されたときは Facebook に送信
    if(isset($_POST['message'])) {
        $facebook->api('/me/feed', 'POST', array(
            'message' => $_POST['message'],
        ));
        header(sprintf('Location: http://%s%s', $_SERVER['HTTP_HOST'], $_SERVER['SCRIPT_NAME']));
        exit;
    }

    // ユーザーの情報を取得します
    $user_profile = $facebook->api('/me');

    // ログインしている場合はログアウトページ
    $logoutUrl = $facebook->getLogoutUrl();
} else {
    // ログインしていない場合はログインページ
    // ウォールに投稿する権限を取得
    $loginUrl = $facebook->getLoginUrl(array(
        'scope' => 'publish_stream',
    ));
}
*/
?>
<!--

<html>
<head>
  <meta charset="utf-8">
  <title>otobank-test</title>
</head>
<body>
<?php if ($user): ?>
  <p><?php echo $user_profile['name'] ?> さんの今日の気分は？</p>
  <form action="" method="post">
    <ul>
      <li><input type="submit" name="message" value="飲みに行こう！" /></li>
      <li><input type="submit" name="message" value="探さないでください" /></li>
      <li><input type="submit" name="message" value="ぎゃふん" /></li>
    </ul>
  </form>
<?php else: ?>
  <p>アプリを使用するには<a target="_top" href="<?php echo $loginUrl ?>">ログイン</a>してください</p>
<?php endif ?>

<p><a target="_blank" href="http://tech.otobank.co.jp/">OTOBANK Developer's Blog</a></p>

</body>
</html>
-->
<?php
    include_once "fbmain.php";
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <title>PHP SDK 3.0 & Graph API base FBConnect Tutorial | Thinkdiff.net</title>

        <script type="text/javascript">
            function streamPublish(name, description, hrefTitle, hrefLink, userPrompt){
                FB.ui({ method : 'feed',
                        message: userPrompt,
                        link   :  hrefLink,
                        caption:  hrefTitle,
                        picture: 'http://thinkdiff.net/ithinkdiff.png'
               });
               //http://developers.facebook.com/docs/reference/dialogs/feed/

            }
            function publishStream(){
                streamPublish("Stream Publish", 'Checkout iOS apps and games from iThinkdiff.net. I found some of them are just awesome!', 'Checkout iThinkdiff.net', 'http://ithinkdiff.net', "Demo Facebook Application Tutorial");
            }

            function newInvite(){
                 var receiverUserIds = FB.ui({
                        method : 'apprequests',
                        message: 'Come on man checkout my applications. visit http://ithinkdiff.net',
                 },
                 function(receiverUserIds) {
                          console.log("IDS : " + receiverUserIds.request_ids);
                        }
                 );
                 //http://developers.facebook.com/docs/reference/dialogs/requests/
            }
        </script>
    </head>
<body>

<style type="text/css">
    .box{
        margin: 5px;
        border: 1px solid #60729b;
        padding: 5px;
        width: 500px;
        height: 200px;
        overflow:auto;
        background-color: #e6ebf8;
    }
</style>

<div id="fb-root"></div>
    <script type="text/javascript" src="http://connect.facebook.net/en_US/all.js"></script>
     <script type="text/javascript">
       FB.init({
         appId  : '<?=$fbconfig['appid']?>',
         status : true, // check login status
         cookie : true, // enable cookies to allow the server to access the session
         xfbml  : true  // parse XFBML
       });

     </script>

    <a href="http://ithinkdiff.net" target="_blank" style="text-decoration: none;">
       <img src="http://ftechdb.com/ithinkdiff.net-banner.jpg" style="border:none" alt="Download iPhone/iPad Dictionaries, Applications & Games" height="92" />
   </a>
   <br />
    <h3>PHP SDK 3.0 & Graph API base FBConnect Tutorial | Thinkdiff.net</h3>
    <a href="http://thinkdiff.net/facebook-connect/php-sdk-3-0-graph-api-base-facebook-connect-tutorial/" target="_blank">Tutorial: PHP SDK 3.0 & Graph API base Facebook Connect Tutorial</a> <br /><br />
    <br />



    <?php if (!$user) { ?>
        You've to login using FB Login Button to see api calling result.
        <a href="<?=$loginUrl?>">Facebook Login</a>
    <?php } else { ?>
        <a href="<?=$logoutUrl?>">Facebook Logout</a>
    <?php } ?>

    <!-- all time check if user session is valid or not -->
    <?php if ($user){ ?>
    <table border="0" cellspacing="3" cellpadding="3">
        <tr>
            <td>
                <!-- Data retrived from user profile are shown here -->
                <div class="box">
                    <b>User Information using Graph API</b>
                    <?php d($userInfo); ?>
                </div>
            </td>
            <td>
                <div class="box">
                    <b>User likes these movies | using graph api</b>
                     <?php d($movies); ?>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="box">
                    <b>Wall Post & Invite Friends</b>
                    <br /><br />
                    <a href="<?=$fbconfig['baseurl']?>?publish=1">Publish Post using PHP </a>
                    <br />
                    You can also publish wall post to user's wall via Ajax.
                    <?php if (isset($_GET['success'])) { ?>
                        <br />
                        <strong style="color: red">Wall post published in your wall successfully!</strong>
                    <?php } ?>

                    <br /><br />
                    <a href="#" onclick="publishStream(); return false;">Publish Wall post using Facebook Javascript</a>

                    <br /><br />
                    <a href="#" onclick="newInvite(); return false;">Invite Friends</a>
                </div>
            </td>
            <td>
                <div class="box">
                    <b>FQL Query Example by calling Legacy API method "fql.query"</b>
                    <?php d($fqlResult); ?>
                </div>
            </td>
        </tr>
    </table>
    <div class="box">
        <form name="" action="<?=$fbconfig['baseurl']?>" method="post">
            <label for="tt">Status update using Graph API</label>
            <br />
            <textarea id="tt" name="tt" cols="50" rows="5">Write your status here and click 'Update My Status'</textarea>
            <br />
            <input type="submit" value="Update My Status" />
        </form>
        <?php if (isset($statusUpdate)) { ?>
            <br />
            <strong style="color: red">Status Updated Successfully! Status id is <?=$statusUpdate['id']?></strong>
         <?php } ?>
    </div>
    <?php } ?>

    </body>
</html>
