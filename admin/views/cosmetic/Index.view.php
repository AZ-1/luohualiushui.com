<form id="pagerForm" method="post" action="">
	<input type="hidden" name="pageNum" value="1" />
	<input type="hidden" name="brand_name" value="<?php echo $brand_name;?>"/>
	<input type="hidden" name="brand_id" value="<?php echo $brand_id;?>"/>
</form>
<div class="pageHeader">
	<form onsubmit="return navTabSearch(this);" action="/cosmetic/index" method="post">
	<div class="searchBar">
		<table class="searchContent">
			<tr>
				<td>
					品牌名称：<input type="text" name="brand_name" value="<?php echo $brand_name;?>" />
					品牌ID：<input type="text" name="brand_id" value="<?php echo $brand_id;?>" />
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
	<div class="panelBar">
		<ul class="toolBar">
			<li><a class="add" href="/cosmetic/add_brand" target="dialog" mask="true" ><span>添加品牌</span></a></li>
			<li class="line">line</li>
		</ul>
	</div>
	<table class="table" width="100%" layoutH="138">
		<thead>
			<tr>
				<th >ID</th>
				<th>中文首字母</th>
				<th >中文名称</th>
				<th >英文名称</th>
				<th >品牌添加时间</th>
				<th >归属地</th>
				<th >操作</th>
			</tr>
		</thead>
		<tbody>
            <?php foreach($brandList as $k=>$v){?>
				<tr target="article_id" rel="<?php echo $v->id; ?>">
					<td><?php echo $v->id;?></td>
					<td><?php echo $v->first_character;?></td>
					<td><?php echo $v->chi_name;?></td>
					<td><?php echo $v->eng_name;?></td>
					<td><?php echo $v->add_time;?></td>	
					<td><?php echo $v->brand_classify;?>	
					<td><a class="edit" target="navTab" mask=true href="/cosmetic/edit_brand?id=<?php echo $v->id;?>">修改</a> || 
						<a title="确定要删除吗?" class="delete" target="ajaxTodo" href="/cosmetic/del_brand?del_id=<?php echo $v->id;?>">删除</a></td>
			</tr>
			<?php }?>
		</tbody>
	</table>
	<?php $this->includeTemplate('', 'pages');?>
</div>
