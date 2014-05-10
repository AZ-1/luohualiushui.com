<form id="pagerForm" method="post" action="">
	<input type="hidden" name="pageNum" value="1" />
</form>
<div class="pageHeader">
	<form onsubmit="return navTabSearch(this);" action="/daren/user_article" method="post">
	<div class="searchBar">
		<table class="searchContent">
			<tr>
				<td>
					搜索：<input type="text" name="name" placeholder="达人分类名称"/>
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
			<li><a class="add" href="/daren/add_user_article" target="dialog" mask=true><span>添加推荐达人文章</span></a></li>
			<li></li>
			<li class="line">line</li>
		</ul>
	</div>
	<table class="table" width="100%" layoutH="138">
		<thead>
			<tr>
				<th width="80">ID</th>
				<th width="120">推荐达人分类名称</th>
				<th width="120">推荐达人标签名称</th>
				<th>推荐达人文章ID</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td><div style="overflow:hidden;"></div></td>
				<?php for($i = 0;$i < 4;$i++ ){?>	
					<td></td>
				<?php }?>

			</tr>

			<?php foreach($userArticleList as $k=>$v){?>
			<tr target="article_id" rel="<?php echo $v->id ?>">
				<td style="height:30px;"><?php echo $v->id;?></td>
				<td><?php echo $v->hotUserCategoryInfo->name;?></td>
				<td><?php if(isset($v->hotUserTagInfo)) echo $v->hotUserTagInfo->name;?></td>
				<td><?php echo $v->article_id;?></td>
				<td> 
					<a class="deleteDaren" href="javascript:void(0);" id="<?php echo $v->id; ?>"><span>取消推荐</span></a> 
				</td>
			</tr>
			<?php }?>
		</tbody>
	</table>
	<?php $this->includeTemplate('', 'pages');?>
</div>
<script>
$('.deleteDaren').die('click').live('click' , function(){
		if(confirm('是否删除?')){
			var __this = this;
			$.post('/daren/del_user_article' , {'id' : $(this).attr('id')} , function(){
				$(__this).parent().parent().parent().remove();
			});
		}
	});
</script>
