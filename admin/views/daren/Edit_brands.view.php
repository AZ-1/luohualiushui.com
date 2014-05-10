<div class="pageContent">
	<form method="post" action="/daren/up_brands" enctype="multipart/form-data" class="pageForm required-validate" onsubmit="return iframeCallback(this, navTabAjaxDone)";>
		<div class="pageFormContent" layoutH="56">
				<input name="id" value="<?php echo $brandsInfo->brands_id;?>" type="hidden"/>
			<p>
				<label>品牌名称：</label>
				<input name="name" class="required" value="<?php echo $brandsInfo->name;?>" type="text" size="30"/>
			</p>
			<p>
				<label>图片路径：</label>
				<input name="link_url" type="text" value="<?php echo $brandsInfo->link_url?>"/>
			</p>
			<p>
				<label>图片路径：</label>
				<input name="logo" type="file"/>
			</p>
			<p>
				<label>原图样式</label>
				<img width=500 height=400 src="<?php echo $brandsInfo->logo;?>" />
			</p>
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
