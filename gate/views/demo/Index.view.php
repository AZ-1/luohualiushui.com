<?php 
// 调用其他 module接口
$this -> page_title = "大家分享的潮流单品-今日热门宝贝 - 嗨淘潮流单品";
$this->uri; // 路由配置转换后的后端接口需要的uri
?>

<div class="content">
<table>
<?php foreach($data as $k=>$v){?>
	<tr>
		<td><?php echo $v->id;?></td>
		<td><?php echo $v->name;?></td>
		<td><?php echo $v->pwd;?></td>
	</tr>
<?php }?>
</table>
</div>
