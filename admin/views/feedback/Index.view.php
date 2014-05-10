<form id="pagerForm" method="post" action="">
	<input type="hidden" name="pageNum" value="1" />
</form>
<div class="pageHeader">
	<form onsubmit="return navTabSearch(this);" action="demo_page1.html" method="post">
	<div class="searchBar">
		<table class="searchContent">
		</table>
		<div class="subBar">
			<ul>
				<!--<li><div class="buttonActive"><div class="buttonContent"><button type="submit">检索</button></div></div></li>
				<li><a class="button" href="demo_page6.html" target="dialog" mask="true" title="查询框"><span>高级检索</span></a></li>-->
			</ul>
		</div>
	</div>
	</form>
</div>
<div class="pageContent">
	<div class="panelBar">
		<ul class="toolBar">
			<!--<li><a class="add" href="/feedback/add_feedback" target="navTab"><span>添加</span></a></li>
			<li><a class="delete" href="demo/common/ajaxDone.html?uid={sid_user}" target="ajaxTodo" title="确定要删除吗?"><span>删除</span></a></li>
			<li><a class="edit" href="demo_page4.html?uid={sid_user}" target="navTab"><span>修改</span></a></li>
			<li class="line">line</li>
			<li><a class="icon" href="demo/common/dwz-team.xls" target="dwzExport" targetType="navTab" title="实要导出这些记录吗?"><span>导出EXCEL</span></a></li>-->
		</ul>
	</div>
	<table class="table" width="100%" layoutH="138">
		<thead>
			<tr>
				<th width="80">ID</th>
				<th width="80">用户名称</th>
				<th width="120">反馈时间</th>
				<th width="120">反馈内容</th>
				<th>终端类型</th>
				<th>系统版本</th>
				<th>操作</th>
				<!--<th width="150">发布时间</th>-->
			</tr>
		</thead>
		<tbody>
			<tr style="">
				<td><div style="overflow:hidden;"></div></td>
				<?php for($i = 0;$i < 5;$i++ ){?>	
					<td></td>
				<?php }?>

			</tr>
            <?php foreach($feedbackList as $k=>$v){?>
			<tr target="sid_user" rel="1">
				<td><?php echo $v->id;?></td>
				<td><?php if(isset($v->userInfo)){ echo $v->userInfo->realname;}else{echo '匿名';}?></td>
				<td><?php echo $v->create_time;?></td>
				<td><?php echo mb_substr($v->content,0,20,'utf-8');?></td>
				<td><?php echo $v->client_type;?></td>
				<td><?php echo $v->version;?></td>
				<td>
					<a class="edit" target="_blank" href="/feedback/show_feedback?id=<?php echo $v->id?>" target="navTab" >详情</a>||
					<a class="deleteArticleasd" href="javascript:void(0);" id="<?php echo $v->id ?>"><span>删除</span></a>
				</td>
			</tr>
			<?php }?>
		</tbody>
	</table>
	<?php $this->includeTemplate('', 'pages');?>
</div>
<script>
$('.deleteArticleasd').die('click').live('click' , function(){
		if(confirm('是否删除?')){
			var __this = this;
			$.post('/feedback/del_feedback' , {'id' : $(this).attr('id')} , function(){
				$(__this).parent().parent().parent().remove();
			});
		}
	});
</script>
