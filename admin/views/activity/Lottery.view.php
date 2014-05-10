<form id="pagerForm" method="post" action="/activity/lottery">
	<input type="hidden" name="pageNum" value="1" />
</form>
<div class="pageHeader">
	<form onsubmit="return navTabSearch(this);" action="/activity/lottery" method="post">
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
				<th width="300">获奖信息</th>
				<th>抽奖时间</th>
			</tr>
		</thead>
		<tbody>
			<?php if(!empty($lotteryList)){foreach($lotteryList as $k=>$v){ ?>
				<tr >
					<td><?php if(isset($v->source_user_name)){echo $v->source_user_name;}else{echo $v->loginType,$v->realname;}?></td>
					<td><?php echo $v->prize;?></td>
					<td><?php echo $v->create_time;?></td>
				</tr>
			<?php }}?>
		</tbody>
	</table>
	<?php $this->includeTemplate('', 'pages');?>
</div>
