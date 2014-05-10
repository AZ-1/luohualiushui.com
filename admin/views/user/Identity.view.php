<div class="content">

</div>
<form id="pagerForm" method="post" action="">
	<input type="hidden" name="pageNum" value="1" />
</form>
<!--<div class="pageHeader">
	<form onsubmit="return navTabSearch(this);" action="/article/list" method="post">
	<div class="searchBar">
		<table class="searchContent">
			<tr>
				<td>
					文章标题：<input type="text" name="keyword" />
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
</div>-->
<div class="pageContent">
	<div class="panelBar">
		<ul class="toolBar">
			<li><a class="add" href="/user/add_identity" target="navTab"><span>添加用户身份</span></a></li>
			<li></li>
			<li class="line">line</li>
		</ul>
	</div>
	<table class="table" width="100%" layoutH="138">
		<thead>
			<tr>
				<th width="80">ID</th>
				<th>身份名称</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
            <?php foreach($identityList as $k=>$v){?>
			<tr target="article_id" rel="<?php echo $v->id ?>">
				<td><?php echo $v->id;?></td>
				<td><?php echo $v->identity;?></td>
				<td><a class="edit" href="/user/edit_identity?id=<?php echo $v->id; ?>" target="navTab"><span>修改</span></a> </td>
			</tr>
			<?php }?>
		</tbody>
	</table>
	<?php $this->includeTemplate('', 'pages');?>
</div>
