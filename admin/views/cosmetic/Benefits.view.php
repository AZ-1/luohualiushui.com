<div class="pageContent">
	<div class="panelBar">
		<ul class="toolBar">
			<li><a class="add" href="/cosmetic/add_benefits?benefit_id=0" target="dialog" mask="true" ><span>添加分类</span></a></li>
			<li class="line">line</li>
		</ul>
	</div>
	<table class="table" width="100%" layoutH="138">
		<thead>
			<tr>
				<th width="50%">名称</th>
				<th width="50%">操作</th>
			</tr>
		</thead>
		<tbody>
		<?php $i=0;?>
		<?php foreach($allClassify as $item){?>
			<tr target="benefits_id" rel="<?php echo $v->id; ?>">
				<td><?php echo $item->base->des_info;?></td>

				<td>
					<a class="add"  target="dialog" mask="true" href="/cosmetic/add_benefits?benefit_id=<?php echo $item->base->id;?>"  >新建子项</a>||
					<a title="确定要删除吗?" class="delete" target="ajaxTodo" href="/cosmetic/del_benefits?del_id=<?php echo $item->base->id;?>">删除</a>||
					<a class="edit"  target="dialog" mask="true" href="/cosmetic/edit_benefits?benefit_id=<?php echo $item->base->id;?>"  >修改</a>
				</td>
			</tr>
			<?php $child = $item->child;?>
			<?php foreach($child as $subItem){?>
				<tr>
					<td><?php echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"."$subItem->des_info";?></td>
					<td>
						<a class="add"  target="dialog" mask="true" href="/cosmetic/add_benefits?benefit_id=<?php echo $subItem->id;?>"  >新建子项</a>
						<a title="确定要删除吗?" class="delete" target="ajaxTodo" href="/cosmetic/del_benefits?del_id=<?php echo $subItem->id;?>">删除</a> 
						<a class="edit"  target="dialog" mask="true" href="/cosmetic/edit_benefits?benefit_id=<?php echo $subItem->id;?>"  >修改</a>
					</td>
			</tr>
			<?php }?>
		<?php }?>	
		</tbody>
	</table>
</div>
