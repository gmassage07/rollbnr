<?php
$sub_menu = "110500";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'w');

use kr\bartnet as bt;

include_once(G5_LIB_PATH.'/bart/class/util/arr.php');
include_once(G5_LIB_PATH.'/bart/class/html/selectbox.php');

$g5['title'] = '롤링배너관리';
include_once ('./admin.head.php');

if(isset($_POST["actmode"]) && $_POST["actmode"]=="save"){
    
    $data = array();
    $data['cfg']['interval'] = $_POST["b_interval"];
    $data['items'] = array();
    for($i=0; $i<9; $i++){
        
        $item['use'] = isset($_POST["b_use"][$i]) && $_POST["b_use"][$i] == "1" ? $_POST["b_use"][$i] : "0";
        
        $item['list'] = array();
        for($j=0; $j<3; $j++){
            $bnr = array();
            if(isset($_POST["b_img"][$i][$j])){
                $bnr['img'] = $_POST["b_img"][$i][$j];
            }
            if(isset($_POST["b_url"][$i][$j])){
                $bnr['url'] = $_POST["b_url"][$i][$j];
            }
            if(isset($_POST["b_title"][$i][$j])){
                $bnr['title'] = $_POST["b_title"][$i][$j];
            }
            if(isset($_POST["b_alt"][$i][$j])){
                $bnr['alt'] = $_POST["b_alt"][$i][$j];
            }
            if(isset($_POST["b_target"][$i][$j])){
                $bnr['target'] = $_POST["b_target"][$i][$j];
            }
            
            $item['list'][] = $bnr;
        }
        
        $data['items'][] = $item;
    }
    
    
    $str = '<'.'?php exit();?'.'>'.PHP_EOL;
    $str .= bt\util\BArr::toJSON($data);
    
    $fp = fopen(G5_DATA_PATH.'/rollbnr.php', 'w+');
    fwrite($fp, $str);
    fclose($fp);
    
    goto_url('rollbnr.php');
    exit();
}

$file = file(G5_DATA_PATH.'/rollbnr.php');
array_shift($file);

$temp = json_decode(implode('', $file), true);
$cfg = $temp['cfg'];
$items = $temp['items'];

$ts = new bt\html\BSelectbox();
$ts->add('_self', '현재창');
$ts->add('_blank', '새창');
?>

<style type="text/css">
#rollbnr .local_desc:after{content:''; clear:both; display:block;}
#rollbnr .local_desc p{float:left;}
#rollbnr .local_desc label{float:right;}
#rollbnr .tbl_wrap .frm_input{width:100%;}
#rollbnr .cfg{margin:0px 20px 10px 20px; padding:5px; margin-bottom:10px; border:1px solid #ddd;}
</style>


<form name="fbnrform" id="fbnrform" action="./rollbnr.php" method="post" enctype="multipart/form-data">
<input type="hidden" name="actmode" value="save">
<section id="rollbnr">
    <h2 class="h2_frm">롤링배너관리</h2>

    <div class="cfg">
        대기시간: <input type="text" name="b_interval" class="frm_input" value="<?php echo isset($cfg['interval']) ? $cfg['interval'] : 2000;?>"> 1초 = 1000;
    </div>
<?php
for($i=0; $i<9; $i++){
    $b_title = "배너-".($i+1);
    
    $use_checked = '';
    if(isset($items[$i]['use']) && $items[$i]['use']=='1'){
        $use_checked = ' checked="checked"';
    }
?>
    <div class="local_desc01 local_desc">
        <p><strong><?php echo $b_title?></strong></p>
        <label>
            <input type="checkbox" name="b_use[]" class="b_use" value="1"<?php echo $use_checked?>>
            사용함
        </label>
    </div>
    <div class="tbl_head01 tbl_wrap">
        <table>
        <caption><?php echo $b_title ?> 목록</caption>
        <thead>
        <tr>
            <th scope="col">이미지URL</th>
            <th scope="col">링크URL</th>
            <th scope="col">링크타겟</th>
            <th scope="col">title</th>
            <th scope="col">alt</th>
        </tr>
        </thead>
        <tbody>
    <?php
    for($j=0; $j<3; $j++){
        $img = '';
        $url = '';
        $title = '';
        $alt = '';
        $tar_opts = '';
        if(isset($items[$i]["list"][$j])){
            $row = $items[$i]["list"][$j];
            
            if(isset($row['img'])) $img = $row['img'];
            if(isset($row['url'])) $url = $row['url'];
            if(isset($row['title'])) $title = $row['title'];
            if(isset($row['alt'])) $alt = $row['alt'];
            
            if(isset($row['target'])){
                $ts->selectedFromValue = $row['target'];
            }
        }
        $tar_opts = $ts->getOption();
    ?>
        <tr>
            <td><input type="text" name="b_img[<?php echo $i?>][]" class="b_img frm_input" value="<?php echo $img?>"></td>
            <td><input type="text" name="b_url[<?php echo $i?>][]" class="b_url frm_input" value="<?php echo $url?>"></td>
            <td>
                <select name="b_target[<?php echo $i?>][]">
                    <?php echo $tar_opts?>
                </select>
            </td>
            <td><input type="text" name="b_title[<?php echo $i?>][]" class="b_title frm_input" value="<?php echo $title?>"></td>
            <td><input type="text" name="b_alt[<?php echo $i?>][]" class="b_alt frm_input" value="<?php echo $alt?>"></td>
        </tr>
    <?php }?>
        </tbody>
        </table>
    </div>
    
    <div class="btn_confirm01 btn_confirm">
        <input type="submit" value="확인" class="btn_submit" accesskey="s">
    </div>
<?php }?>


</section>
</form>


<?php
include_once ('./admin.tail.php');