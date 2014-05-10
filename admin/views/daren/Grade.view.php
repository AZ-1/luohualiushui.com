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
			<li><a class="add" href="/daren/add_grade" target="navTab"><span>添加等级</span></a></li>
			<li></li>
			<li class="line">line</li>
		</ul>
	</div>
	<table class="table" width="100%" layoutH="138">
		<thead>
			<tr>
				<th width="80">ID</th>
				<th>等级名称</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
            <?php foreach($gradeList as $k=>$v){?>
			<tr target="article_id" rel="<?php echo $v->id ?>">
				<td style="height:30px;"><?php echo $v->grade_id;?></td>
				<td><?php echo $v->name;?></td>
				<td><a class="edit" href="/daren/edit_grade?id=<?php echo $v->grade_id; ?>" target="navTab"><span>修改</span></a></td>
			</tr>
			<?php }?>
		</tbody>
	</table>
	<?php $this->includeTemplate('', 'pages');?>
</div>
