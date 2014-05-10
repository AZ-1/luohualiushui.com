<div class="content">
</div>
<form id="pagerForm" method="post" action="">
	<input type="hidden" name="pageNum" value="1" />
</form>
<div class="pageHeader">
	<form onsubmit="return navTabSearch(this);" action="/user/statistics" method="post">
	<div class="searchBar">
		<table class="searchContent">
			<tr>
				<td>
					搜索：
					<input type="text" name="start_time" placeholder="开始：YYYY-MM-DD"/>
					<input type="text" name="end_time" placeholder="结束：YYYY-MM-DD"/>
				</td>
			</tr>
		</table>
		<div class="subBar">
			<ul>
				<li><div class="buttonActive"><div class="buttonContent"><button type="submit">查询</button></div></div></li>
			</ul>
		</div>
	</div>
	</form>
</div>
	<table class="table" width="100%" layoutH="138">
		<thead>
			<tr>
				<th>用户总数</th>
				<th>新增用户</th>
				<th>查询日期</th>
			</tr>
		</thead>
			<?php if(isset($res_data)) {?>
				<tbody>
					<td style="height:30px;"><?php echo ($res_data['data']["total_res"]); ?></td>
					<td style="height:30px;"><?php echo ($res_data['data']["filter_res"]);?></td>
					<td style="height:30px;"><?php echo ($res_data['data']["duration"]);?></td>
				</tbody>
			<?php } ?>
	</table>
	<?php $this->includeTemplate('', 'pages');?>
</>
