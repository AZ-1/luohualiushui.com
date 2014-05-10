<form id="pagerForm" method="post" action="/activity/Shoppinglist">
	<input type="hidden" name="pageNum" value="1" />
</form>
<div class="pageHeader">
	<form onsubmit="return navTabSearch(this);" action="/activity/Shoppinglist" method="post">
	<div class="searchBar">
		<table class="searchContent">
			<tr>
				<td>
					搜索用户名：<input type="text" name="keyword" placeholder="用户名"/>
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
				<th width="120">名字</th>
				<th width="300">领取信息</th>
				<th>领取时间</th>
			</tr>
		</thead>
		<tbody>
			<?php if(!empty($Shoppinglist)){
                 foreach($Shoppinglist as $k=>$v){ ?>
				<tr >
					<td><?php if($v->source_user_name != ''){echo $v->source_user_name;}else{echo $v->user_id;}?></td>
					<td><?php echo $v->prize;?></td>
					<td><?php echo $v->create_time;?></td>
				</tr>
			<?php }}?>
		</tbody>
	</table>
	<?php $this->includeTemplate('', 'pages');?>
</div>
