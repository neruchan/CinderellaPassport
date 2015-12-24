<?
require_once "./ipfTemplate.php";
$a = new ipfTemplate();

//ループのサンプル
//サンプルのため$valuesという連想配列を作っておく、通常はDBからのデータを入れる
$values[0] = array(
			"name" => "日本太郎",
			"address" => "東京都皇居1-1-1",
			"email" => "taro@nihon.hoge"
);
$values[1] = array(
			"name" => "東京花子",
			"address" => "東京都東京区2-2-2",
			"email" => "hanako@tokyo.hoge"
);

$values2[0] = array(
			"name" => "2日本太郎",
			"address" => "2東京都皇居1-1-1",
			"email" => "2taro@nihon.hoge"
);
$values2[1] = array(
			"name" => "2東京花子",
			"address" => "2東京都東京区2-2-2",
			"email" => "2hanako@tokyo.hoge"
);

$templateData2 = $a->loadTemplate("hoge2.template");//明示的にテンプレートファイルを指定する
foreach($values as $value){
	 $result2 .= $a->makeTemplateData($templateData2, $value);//「.=」である点に注意
}

//本物のループのためのデータ
$valuesForLoop["loophoge"] = $values;
$valuesForLoop["loophoge2"] = $values2;

//print_r($valuesForLoop);

//普通のPRINTのサンプル＆ループの結果を入れる
$templateData = $a->loadTemplate();//デフォルトでスクリプト名と同じテンプレートが読まれる
$forTemplate[hoge] = "テストだよ～";
$forTemplate[hoge2] = "テストだよ～２";
$forTemplate[loop_dayo] = $result2;//ここでループの結果を代入
$result = $a->makeTemplateData($templateData, $forTemplate, $valuesForLoop);


//一度メモリ上に保存(保存順に表示される※複数保存可能)
$a->putMemory($result);
$a->putMemory("<p>こんにちは新宿三郎です。<br>\n");
$a->putMemory("これはメモリの保存順のサンプルです。<br>\n");
$a->putMemory("ちなみにこれはHTMLの終了タグの後に出力されているのでHTMLとしては違反です。<br>\n");

//表示出力
$a->view();

?>