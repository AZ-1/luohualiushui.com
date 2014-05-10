<div class="content">
</div>
<form id="pagerForm" method="post" action="">
	<input type="hidden" name="pageNum" value="1" />
	<input type="hidden" name="only_daren" value='<?php if($only_daren)echo "yes";else echo "no"?>'> 
</form>
<div class="pageHeader">
	<form onsubmit="return navTabSearch(this);" action='/user/analysis_activity' method="post">
		<div class="subBar">
			<ul>
			<li> <div class="radioSelected"><div class="select_daren">
				<input type="radio" name="only_daren"  <?php if(!$only_daren)echo 'checked="true"';?> value="no" />全部
				<input type="radio" name="only_daren" <?php if($only_daren)echo 'checked="true"';?> value="yes" />达人
			</div></div></li>	
			</ul>
			<ul>
				<li><div class="buttonActive"><div class="buttonContent"><button type="submit">查看</button></div></li>
			</ul>
		</div>
	</form>
</div>
<div class="pageContent">
	<table class="table" width="100%" layoutH="138">
		<thead>
			<tr>
				<th stylee="height:20px;">名称</th>
				<th style="height:20px;">点赞数</th>
				<th style="height:20px;">发布文章数</th>	
				<th style="height:20px;">评论数</th>
			</tr>
		</thead>
		<tbody>
		<?php if(isset($user_Activity ) AND $user_Activity['status'] === '1') {?>
			<?php $data=$user_Activity['data'];?>
				<?php for(;$item = current($data);next($data)){ ?>
					<tr>
						<td><a class="scan" target ='_blank' href="<?php echo MEI_BASE_URL?>person/index/uid/<?php echo $item->ID; ?>" target="navTab"><span><?php echo $item->NAME?></span></a> </td> 
						<td><?php echo $item->LIKES;?></td>
						<td><?php echo $item->PUBLISHS;?></td>
						<td ><?php echo $item->COMMENTS; ?></td>
					</tr>	
				<?php } ?>
			<?php }?>
		</tbody>
	</table>
	<?php $this->includeTemplate('', 'pages');?>
</div>
