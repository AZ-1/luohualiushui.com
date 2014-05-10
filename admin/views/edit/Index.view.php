	<style>
		table tr td {vertical-align:middle !important;}
	</style>
	<div class="panel panel-default">
		<div class="panel-heading">推荐列表</div>
		<table class="table table-hover">
			<thead>
				<tr>
					<th>应用名称</th>
					<th>文件大小</th>
					<th>版本号</th>
					<th>管理</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($data as $row):?>
					<tr>
						<td>
							<img src="http://www.d9app.com/img<?php echo $row['appiconurl']?>">
							<?php echo $row['appname']?>
						</td>
						<td><?php echo floor($row['appsize'] / 1024 / 1024) . "MB";?></td>
						<td><?php echo $row['version']?></td>
						<td><button type="button" class="btn btn-warning btn-xs btn_del" appid="<?php echo $row['idx']?>">删除</button></td>
					</tr>	
				<?php endforeach;?>
			</tbody>
		</table>	
		<div class="panel-footer">
			<ul class="pagination" style="margin:0">
				<li><a href="/edit/index?p=1">首页</a></li>
				<li><a href="/edit/index?p=<?php echo $prev;?>">上一页</a></li>
				<li><a href="/edit/index?p=<?php echo $next;?>">下一页</a></li>
				<li><a href="/edit/index?p=<?php echo $end;?>">末页</a></li>
			</ul>
			<div style="float:right;line-height:36px;">
				当前显示第
				<select>
					<?php for($i=1;$i<=$end;$i++):?>	
					<option value="<?php echo $i;?>" <?php if($current==$i) echo 'selected';?> onClick="window.location='/edit/index?p='+this.value">
						<?php echo $i;?>
					</option>
					<?php endfor;?>	
				</select>
				页，共<?php echo $total;?>条
			</div>
		</div>
	</div>
	<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
					<h4 class="modal-title">提示</h4>
				</div>
				<div class="modal-body">
					<h2>确认要删除？</h2>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-success btn_ok">确认</button>
					<button type="button" class="btn btn-danger btn_canel" data-dismiss="modal">取消</button>
				</div>
		</div>
	</div>
	<script>
			$(function(){
				$('.btn_del').on('click',function(){
						$('#confirmModal').modal('show');
						$('.btn_ok').attr('appid',$(this).attr('appid'));
				})
				$('.btn_ok').on('click',function(){
						$.post('/edit/del',{appid:$(this).attr('appid')},function(result){
								if(result == 0){
										alert('删除失败，未知原因!');
										$('#confirmModal').modal('hide');
								} else {
										window.location.reload(true);
								}
						});
				})
			})
	</script>
