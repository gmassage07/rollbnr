<?php
if(!defined("_GNUBOARD_")) exit("Access Denied");

$file = file(G5_DATA_PATH.'/rollbnr.php');
array_shift($file);

$temp = json_decode(implode('', $file), true);
$cfg = $temp['cfg'];
$items = $temp['items'];

if(!isset($bnr_boxcnt)) $bnr_boxcnt = 3;

add_javascript('<script type="text/javascript" src="'.G5_PLUGIN_URL.'/jquery.easy-ticker/jquery.easing.min.js"></script>');
add_javascript('<script type="text/javascript" src="'.G5_PLUGIN_URL.'/jquery.easy-ticker/jquery.easy-ticker.min.js"></script>');
?>

<style type="text/css">
.rbnr-wrap{content:''; clear:both; display:block; margin-bottom:10px; overflow:hidden; visibility: hidden;}
.rbnr {float:left;}
.rbnr { width:33.333333%; height:60px; margin-bottom:2px;}
.rbnr ul { position:absolute; margin:0; padding:0; list-style:none; font-size:0; }
.rbnr ul li { margin:0; padding:0; list-style:none; }

@media(max-width:540px){
    .rbnr { width:50%; }
}

/*.rbnr1, .rbnr2, .rbnr3 {margin-bottom:10px;}*/
</style>

<div class="rbnr-wrap">
<?php for($i=0; $i<$bnr_boxcnt; $i++){?>
    <?php
    if(!isset($items[$i]['use']) || $items[$i]['use'] != '1') continue;
    ?>
    <div class="rbnr rbnr<?php echo $i+1?>">
        <ul>
    <?php for($j=0; $j<3; $j++){?>
        <?php
        if(!isset($items[$i]["list"][$j])) continue;
        $row = $items[$i]["list"][$j];
        if(!isset($row['img']) || trim($row['img'])=='') continue;
        
        $url = $row['url'];
        
        $str = '<img src="'.$row['img'].'"';
        
        if(isset($row['title']) && trim($row['title']) != '') $str .= ' title="'.$row['title'].'"';
        
        if(isset($row['alt']) && trim($row['alt']) != '') $str .= ' alt="'.$row['alt'].'"';
        
        $str .= ' class="img-responsive">';
        
        if(isset($row['url']) && trim($row['url'])!=''){
            $astr = '<a href="'.$row['url'].'"';
            if(isset($row['target']) && trim($row['target'])!='') $astr .= ' target="'.$row['target'].'"';
            $astr .= '>';
            $str = $astr.$str.'</a>';
        }
        ?>
            <li><?php echo $str?></li>
    <?php }?>
        </ul>
    </div>
<?php }?>


<script type="text/javascript">
<!--
var interval = <?php echo isset($cfg['interval']) ? (int)$cfg['interval'] : 2000?>;
$(function () {
    var dd = $('.rbnr1, .rbnr2, .rbnr3, .rbnr4, .rbnr5, .rbnr6, .rbnr7, .rbnr8, .rbnr9').easyTicker({
        direction: 'up',
        //easing: 'easeInOutBack',
        speed: 'slow',
        interval: interval,
        height: 'auto',
        visible: 1,
        mousePause: 0,
        controls: {
            up: '.up',
            down: '.down',
            toggle: '.toggle',
            stopText: 'Stop !!!'
        }
    }).data('easyTicker'); 
    
    $('.rbnr-wrap').css('visibility', 'visible');
});
//-->
</script>
</div>