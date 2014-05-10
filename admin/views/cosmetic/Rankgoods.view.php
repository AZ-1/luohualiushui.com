<form id="pagerForm" method="post" action="">
	<input type="hidden" name="pageNum" value="1" />
	<input type="hidden" name="title" value="<?php echo $title?>" />
	<input type="hidden" name="brand_name" value="<?php echo $brand_name;?>"/>
	<input type="hidden" name="brand_id" value="<?php echo $brand_id;?>"/>
</form>
<div class="pageHeader">
	<form onsubmit="return navTabSearch(this);" action="/cosmetic/rankgoods" method="post">
	<div class="searchBar">
		<table class="searchContent">
			<tr>
				<td>
							商品名称<input type="text" name="title" />
							<input type="hidden" name="cid" value="<?php echo $cid?>" />
				</td>
			</tr>
		</table>
		<div class="subBar">
			<ul>
				<li><div class="buttonActive"><div class="buttonContent"><button type="submit">检索</button></div></div></li>
			</ul>
		</div>
	</div>
	</form>
</div>
<div class="pageContent">
	<table class="table" width="100%" layoutH="138">
		<thead>
			<tr>
				<th width="20%">ID</th>
				<th width="30%">名称</th>
				<th width="50%">排行</th>
			</tr>
		</thead>
		<tbody>
            <?php foreach($goods as $k=>$v){?>
				<tr target="article_id" rel="<?php echo $v->id; ?>">
					<td><?php echo $v->id;?></td>
					<td><?php echo $v->pro_name;?></td>
					
					<td>
						<input type="text" class="goods_op" goods_id="<?php echo $v->id;?>" name="output_priority" value="<?php echo $v->output_priority;?>">
					</td>
				</tr>
			<?php }?>
		</tbody>
	</table>
	<?php $this->includeTemplate('', 'pages');?>
</div>

<script>
$('.goods_op').live('focusin',function(){
	var item = "<input class='goodsButton' type='button' value='确定' />";
	$('body').find('.goodsButton').detach();
	$('body').find('.tishi').detach();
	if($(this).parent().find('.goodsButton').length == 0){
		$(this).parent().append(item);
	}
});
$('.goodsButton').live('click',function(){
	var input = $(this).parent().find('.goods_op');
	var goods_id = input.attr('goods_id');
	var output_priority	= input.val();
	var url = '/cosmetic/update_goods_output_priority';
	var data = {
		'goods_id':goods_id,
		'output_priority':output_priority
	}
	var callback = function(response){
		if(response){
			input.parent().find('.tishi').remove();
			input.parent().append("<span class='tishi' style='color:#F30C28'>修改成功</span>");	
		}else{
			input.parent().find('.tishi').remove();
			input.parent().append("<span class='tishi' style='color:#F30C28'>修改失败</span>");	
		}
	}
	$.post(url,data,callback,'json');
});
$('body').live('click',function(e){
	var target = $(e.target);
	var test = target.closest('.goodsButton').length;
	var test2 = target.closest('.goods_op').length;
	if(test == 0 && test2 == 0)
	{
		$(this).parent().find('.goodsButton').detach();
		$(this).parent().find('.tishi').detach();
	}
});

