<div class="pageContent">
	<form method="post" action="/article/add_tag" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone)";>
		<div class="pageFormContent" layoutH="56">
			<p>
				<label>标签名称:</label>
				<input name="name" type="text" size="30"  />
			</p>
			<p>
				<label>标签所属分类</label>
				<select name="category" class="required combox">
                <?php foreach($categoryList as $c){?>
					<option value="<?php echo $c->id;?>"><?php echo $c->name;?></option>
					<?php if(isset($c->child)): ?>
						<?php foreach($c->child as $cc): ?>
							<option value="<?php echo $cc->id;?>"><?php echo '&nbsp;&nbsp;'.$cc->name;?></option>
						<?php endforeach; ?>
					<?php endif; ?>
				<?php }?>
				</select>
			</p>
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
