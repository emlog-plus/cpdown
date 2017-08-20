<?php
/*
Plugin Name: 独立文件下载
Version: 1.0
Plugin URL：http://sslcvm.net
Description: 独立的下载页面方便自由化定制，插入广告，独立的查看演示页面，方便插入广告和自由化定制
Author: lonewolf
Author URL: http://sslcvm.com
*/
define('CSS_URL', BLOG_URL .'content/plugins/cpdown-master/');
/**
 * 添加
 *
 * @param array $logData
 * @return int
 */
function Down_addlog($logData) {
    $db = Database::getInstance();
    $kItem = array();
    $dItem = array();
    foreach ($logData as $key => $data) {
        $kItem[] = $key;
        $dItem[] = $data;
    }
    $field = implode(',', $kItem);
    $values = "'" . implode("','", $dItem) . "'";
    $db->query("INSERT INTO " . DB_PREFIX . "cpdown ($field) VALUES ($values)");
    $logid = $db->insert_id();
    return $logid;
}
/**
 * 更新
 *
 * @param array $logData
 * @param int $blogId
 */
function Down_updateLog($logData, $blogId) {
    $db = Database::getInstance();
    $Item = array();
    foreach ($logData as $key => $data) {
        $Item[] = "$key='$data'";
    }
    $upStr = implode(',', $Item);
    $db->query("UPDATE " . DB_PREFIX . "cpdown SET $upStr WHERE logid=$blogId");
}
function Cp_down_setting_create($logid){
    $db = Database::getInstance();
    $data = $db->query("SELECT * FROM ".DB_PREFIX."cpdown WHERE logid ='$logid'");
    $start = isset($_POST['start']) ? addslashes(trim($_POST['start'])) : 'n';
    $name = isset($_POST['name']) ? addslashes(trim($_POST['name'])) : '';
    $size = isset($_POST['size']) ? addslashes(trim($_POST['size'])) : '';
    $up_date = isset($_POST['up_date']) ? addslashes(trim($_POST['up_date'])) : '';
    $version = isset($_POST['version']) ? addslashes(trim($_POST['version'])) : '';
    $author = isset($_POST['dauthor']) ? addslashes(trim($_POST['dauthor'])) : '';
    $yanshi = isset($_POST['yanshi']) ? addslashes(trim($_POST['yanshi'])) : '';
    $baidudown = isset($_POST['baidudown']) ? addslashes(trim($_POST['baidudown'])) : '';
    $baidumima = isset($_POST['baidumima']) ? addslashes(trim($_POST['baidumima'])) : '';
    $xunlei = isset($_POST['xunlei']) ? addslashes(trim($_POST['xunlei'])) : '';
    $tszwp = isset($_POST['tszwp']) ? addslashes(trim($_POST['tszwp'])) : '';
    $tszwpmima = isset($_POST['tszwpmima']) ? addslashes(trim($_POST['tszwpmima'])) : '';
    $other = isset($_POST['other']) ? addslashes(trim($_POST['other'])) : '';
    $web = isset($_POST['web']) ? addslashes(trim($_POST['web'])) : '';
    $ctldown = isset($_POST['ctldown']) ? addslashes(trim($_POST['ctldown'])) : '';
    $general = isset($_POST['general']) ? addslashes(trim($_POST['general'])) : '';
    
    $logData = array('start' => $start,'logid' => $logid,'name' => $name,'size' => $size,'up_date' => $up_date,'version' => $version,'author' => $author,'yanshi' => $yanshi,'baidudown' => $baidudown,'baidumima' => $baidumima,'xunlei' => $xunlei,'tszwp' => $tszwp,'tszwpmima' => $tszwpmima,'other' => $other,'web' => $web,'ctldown' => $ctldown,'general' => $general,);
    $UplogData = array('start' => $start,'name' => $name,'size' => $size,'up_date' => $up_date,'version' => $version,'author' => $author,'yanshi' => $yanshi,'baidudown' => $baidudown,'baidumima' => $baidumima,'xunlei' => $xunlei,'tszwp' => $tszwp,'tszwpmima' => $tszwpmima,'other' => $other,'web' => $web,'ctldown' => $ctldown,'general' => $general,);
    if($db->fetch_array($data) == ""){
        Down_addlog($logData);
    }else{
        Down_updateLog($UplogData, $logid);
    }
}

addAction('save_log', 'Cp_down_setting_create');


function CP_donw_option(){
    $query = $_SERVER["QUERY_STRING"];
    $string = preg_replace('/action=edit&gid=/','',$query);
    $db = Database::getInstance();
    $data = $db->query("SELECT * FROM ".DB_PREFIX."cpdown WHERE logid ='".$string."'");
    $row = $db->fetch_array($data);
    if( $row == ''){
        $logData = array('start' => '','name' => '','size' => '','up_date' => '','version' => '','author' => '','yanshi' => '','baidudown' => '','baidumima' => '','xunlei' => '','tszwp' => '','tszwpmima' => '','other' => '','web' => '','ctldown' => '','general' => '');
    }else{
        $logData = array(
            'start' => htmlspecialchars($row['start']),
            'name' => htmlspecialchars($row['name']),
            'size' => htmlspecialchars($row['size']),
            'up_date' => htmlspecialchars($row['up_date']),
            'version' => htmlspecialchars($row['version']),
            'author' => htmlspecialchars($row['author']),
            'yanshi' => htmlspecialchars($row['yanshi']),
            'baidudown' => htmlspecialchars($row['baidudown']),
            'baidumima' => htmlspecialchars($row['baidumima']),
            'xunlei' => htmlspecialchars($row['xunlei']),
            'tszwp' => htmlspecialchars($row['tszwp']),
            'tszwpmima' => htmlspecialchars($row['tszwpmima']),
            'other' => htmlspecialchars($row['other']),
            'web' => htmlspecialchars($row['web']),
            'ctldown' => htmlspecialchars($row['ctldown']),
            'general' => htmlspecialchars($row['general'])
        );
    }
    
?>
<div class="panel panel-default card-view">
<div class="form-group">
<div class="checkbox checkbox-success ">
<input type="checkbox" name="start" id="statr" value="y"<?php if($logData['start']=='y'){echo ' checked';}else{echo '';}?> >
<label class="control-label mb-10">启用下载</label>
</div>
<div class="form-group">
<label class="control-label mb-10">资源名称</label>
<input type="text" class="form-control" name="name" id="name" value="<?php echo $logData['name']; ?>" size="30" tabindex="30" >
</div>
<div class="form-group">
<label class="control-label mb-10">资源大小</label>
<input type="text" class="form-control" name="size" id="size" value="<?php echo $logData['size']; ?>" size="30" tabindex="30">
</div>
<div class="form-group">
<label class="control-label mb-10">更新时间</label>
<input type="text" class="form-control" name="up_date" id="up_date" value="<?php echo $logData['up_date']; ?>" size="30" tabindex="30">
</div>
<div class="form-group">
<label class="control-label mb-10">适用版本</label>
<input type="text" class="form-control" name="version" id="version" value="<?php echo $logData['version']; ?>" size="30" tabindex="30">
</div>
<div class="form-group">
<label class="control-label mb-10">作者来源</label>
<input type="text" class="form-control" name="dauthor" id="dauthor" value="<?php echo $logData['author']; ?>" size="30" tabindex="30">
</div>
<div class="form-group">
<label class="control-label mb-10">演示地址</label>
<input type="text" class="form-control" name="yanshi" id="yanshi" value="<?php echo $logData['yanshi']; ?>" size="30" tabindex="30" >
</div>
<div class="form-group">
<label class="control-label mb-10">百度网盘</label>
<input type="text" class="form-control" name="baidudown" id="baidudown" value="<?php echo $logData['baidudown']; ?>" size="30" tabindex="30">
</div>
<div class="form-group">
<label class="control-label mb-10">百度网盘密码</label>
<input type="text" class="form-control" name="baidumima" id="baidumima" value="<?php echo $logData['baidumima']; ?>" size="30" tabindex="30">
</div>
<div class="form-group">
<label class="control-label mb-10">百度网盘密码</label>
<input type="text" class="form-control" name="baidumima" id="baidumima" value="<?php echo $logData['baidumima']; ?>" size="30" tabindex="30">
</div>
<div class="form-group">
<label class="control-label mb-10">迅雷快传</label>
<input type="text" class="form-control" name="xunlei" id="xunlei" value="<?php echo $logData['xunlei']; ?>" size="30" tabindex="30">
</div>
<div class="form-group">
<label class="control-label mb-10">360网盘</label>
<input type="text" class="form-control" name="tszwp" id="tszwp" value="<?php echo $logData['tszwp']; ?>" size="30" tabindex="30">
</div>
<div class="form-group">
<label class="control-label mb-10">360网盘密码</label>
<input type="text" class="form-control" name="tszwpmima" id="tszwpmima" value="<?php echo $logData['tszwpmima']; ?>" size="30" tabindex="30" >
</div>
<div class="form-group">
<label class="control-label mb-10">其他网盘</label>
<input type="text" class="form-control" name="other" id="other" value="<?php echo $logData['other']; ?>" size="30" tabindex="30" >
</div>
<div class="form-group">
<label class="control-label mb-10">官方下载</label>
<input type="text" class="form-control" name="web" id="web" value="<?php echo $logData['web']; ?>" size="30" tabindex="30" >
</div>
<div class="form-group">
<label class="control-label mb-10">城通网盘</label>
<input type="text" class="form-control" name="ctldown" id="ctldown" value="<?php echo $logData['ctldown']; ?>" size="30" tabindex="30" >
</div>
<div class="form-group">
<label class="control-label mb-10">普通下载</label>
<input type="text" class="form-control" name="general" id="general" value="<?php echo $logData['general']; ?>" size="30" tabindex="30">
</div>
</div>
</div>

<?php
}

addAction('adm_writelog_head', 'CP_donw_option');


function show_down($logid){
    $db = Database::getInstance();
	$data = $db->query("SELECT * FROM ".DB_PREFIX."cpdown WHERE logid ='$logid'");
	$row = $db->fetch_array($data);
	$logData = array(
		'start' => htmlspecialchars($row['start']),
		'name' => htmlspecialchars($row['name']),
		'size' => htmlspecialchars($row['size']),
		'up_date' => htmlspecialchars($row['up_date']),
		'version' => htmlspecialchars($row['version']),
		'author' => htmlspecialchars($row['author']),
		'yanshi' => htmlspecialchars($row['yanshi']),
	);
	if(empty($logData['yanshi'])){
		$yanshi = '';
	}else{
		$yanshi = '<strong><a class="yanshibtn" rel="external nofollow" href="'.BLOG_URL.'demo.php?id='.$logid.'" target="_blank" title="'.$logData['name'].'">查看演示</a></strong>';
	}
	$content = '<br><style>.down_link{background: none repeat scroll 0 0 #FFFCEF; border: 1px solid #FFBB76; border-radius: 2px; color: #DB7C22; font-size: 14px; margin-bottom: 10px; padding: 5px 10px;}.downbtn{background: none repeat scroll 0 0 #1BA1E2; border: 0 none; border-radius: 2px; color: #FFFFFF; cursor: pointer; font-family: "Open Sans","Hiragino Sans GB","Microsoft YaHei","WenQuanYi Micro Hei",Arial,Verdana,Tahoma,sans-serif; font-size: 14px; margin: -4px 20px 0 0; padding: 8px 30px;text-transform:none;text-decoration:none;}
.downlink a:link{color: #ffffff;}
.downlink a:visited{color: #ffffff;}
.downlink a:hover{color: #ffffff;}
.downlink a:active{color: #ffffff;}
.downbtn{background: none repeat scroll 0 0 #1BA1E2; border: 0 none; border-radius: 2px; color: #FFFFFF !important; cursor: pointer; font-family: "Open Sans","Hiragino Sans GB","Microsoft YaHei","WenQuanYi Micro Hei",Arial,Verdana,Tahoma,sans-serif; font-size: 14px; margin: -4px 20px 0 0; padding: 8px 30px;}
.yanshibtn{background: none repeat scroll 0 0 #d33431; border: 0 none; border-radius: 2px; color: #FFFFFF!important; cursor: pointer; font-family: "Open Sans","Hiragino Sans GB","Microsoft YaHei","WenQuanYi Micro Hei",Arial,Verdana,Tahoma,sans-serif; font-size: 14px; margin: -4px 20px 0 0; padding: 8px 30px;text-transform:none;text-decoration:none;}
.downbtn:hover,.yanshibtn:hover{background: none repeat scroll 0 0 #9B59B6; border: 0 none; border-radius: 2px; color: #FFFFFF!important; cursor: pointer; font-family: "Open Sans","Hiragino Sans GB","Microsoft YaHei","WenQuanYi Micro Hei",Arial,Verdana,Tahoma,sans-serif; font-size: 14px; margin: -4px 20px 0 0; padding: 8px 30px;}
.downbtn a:hover,.yanshibtn a:hover{background: none repeat scroll 0 0 #9B59B6; border: 0 none; border-radius: 2px; color: #FFFFFF; cursor: pointer; font-family: "Open Sans","Hiragino Sans GB","Microsoft YaHei","WenQuanYi Micro Hei",Arial,Verdana,Tahoma,sans-serif; font-size: 14px; margin: -4px 20px 0 0; padding: 8px 30px;}</style>';
	$content .= '<div class="down_link"><p><strong>下载地址：</strong></p><p></p><p>文件名称：'.$logData['name'].'</p><p style="position:relative;">文件大小：'.$logData['size'].'<span style="position:absolute;left:40%;">适用版本：'.$logData['version'].'</span></p><p style="position:relative;">更新日期：'.$logData['up_date'].'<span style="position:absolute;left:40%;">作者信息：'.$logData['author'].'</span></p><p class="downlink"><strong><a class="downbtn" rel="external nofollow" title="'.$logData['name'].'" href="'.BLOG_URL.'download.php?id='.$logid.'" target="_blank">点击下载</a></strong> '.$yanshi.'</p><p></p></div>';
	if($logData['start']=='y'){
		echo $content;
	}else{
		echo '';
	}
}


addAction('down_log', 'show_down');
