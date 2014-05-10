<form id="pagerForm" method="post" action="">
	<input type="hidden" name="pageNum" value="1" />
</form>
<div class="pageHeader">
	<div class="searchBar">
		<table class="searchContent">
			<tr>
				<td>
				</td>
			</tr>
		</table>
		<div class="subBar">
			<ul>
				<li></li>
			</ul>
		</div>
	</div>
	</form>
</div>
<div class="pageContent">
	<div class="panelBar">
		<ul class="toolBar">
			<li class="line">line</li>
		</ul>
	</div>
	<table class="table" width="100%" layoutH="138">
		<thead>
			<tr>
				<th>链接标题</th>
				<th>链接地址</th>
				<th>操作</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($message as $key => $value){ ?>
			<tr>
				<td><?php echo $value->name;?></td>
				<td><?php echo $value->url;?></td>
				<td>
                     <a class="edit" href="/links/Edit_links?Lid=<?php echo $value->links_id; ?>" target="navTab"><span>修改</span></a>
					||&nbsp;<a class="delete" target="ajaxTodo" title="确定要取消吗?" href="/links/Del_links?id=<?php echo $value->links_id ?>"><span>删除</span></a>


				</td>
			</tr>
			<?php }?>
		</tbody>
	 </table>
	 <?php $this->includeTemplate('','pages');?>
</div>


