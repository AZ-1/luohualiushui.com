<div class="pageContent">
	<form method="post" action="/article/add_category" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone)";>
		<div class="pageFormContent" layoutH="56">
			<p>
				<?php if($category->name=='root'):?>
					<label>类名：</label>
				<?php else: ?>
				<label>添加 #<?php echo $category->name ?># 的子类：</label>
				<?php endif; ?>

				<input name="name" type="text" size="30" />
				<input name="cid" type="hidden"  value="<?php echo $category->id ?>" />
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
