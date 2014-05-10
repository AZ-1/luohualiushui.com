<div class="pageContent">
	<form method="post" action="/cosmetic/add_rank" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone)";>
		<div class="pageFormContent" layoutH="56">
			<table>
				<tr>
					<td><label>分类</label></td>
					<td>
						<select name="classify_id">
							<?php foreach($funList as $k=>$v){?>
								<option value="<?php echo $k?>"><?php echo $v->classify_name?></option>
								<?php 
									if(!empty($v->child)){ 
										foreach($v->child as $ck=>$cv){
								?>
											<option value="<?php echo $ck?>"><?php echo '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp'.$cv->classify_name?></option>
								<?php
										}
									}
								?>
							<?}?>
						</select>
					</td>
				</tr>
				<tr>
					<td><label>排行榜名称--选择所属父级</label></td>
					<td>
						<select name="pid">
							<option value="0">自己是最高级</option>
							<?php foreach($topRank as $k=>$v){?>
								<option value="<?php echo $k?>"><?php echo $v->list_name?></option>
								<?php 
									if(!empty($v->child)){ 
										foreach($v->child as $ck=>$cv){
								?>
											<option value="<?php echo $ck?>"><?php echo '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp'.$cv->list_name?></option>
								<?php
										}
									}
								?>
							<?}?>
						</select>
					</td>
				</tr>
				<tr>	
					<td><label>排行榜名称</label></td>
					<td><input name="list_name" type="text" size="30"  /></td>
				</tr>
					
			</table>
		</div>
		<div class="formBar">
			<ul>
				<!--<li><a class="buttonActive" href="javascript:;"><span>保存</span></a></li>-->
				<li><div class="buttonActive"><div class="buttonContent"><button type="submit">保存</button></div></div></li>
				<li>
					<div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div>
				</li>
			</ul>
		</div>
	</form>
</div>
