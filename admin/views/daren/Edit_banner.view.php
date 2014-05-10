<div class="pageContent">
	<form method="post" action="/daren/up_banner" enctype="multipart/form-data" class="pageForm required-validate" onsubmit="return iframeCallback(this, navTabAjaxDone)";>
		<div class="pageFormContent" layoutH="56">
				<input name="id" value="<?php echo $bannerInfo->id;?>" type="hidden"/>
			<p>
				<label>标题：</label>
				<input name="title" class="required" value="<?php echo $bannerInfo->title;?>" type="text" size="30"/>
			</p>
			<p>
				<label>链接路径：</label>
				<input name="link_url" value="<?php echo $bannerInfo->link_url;?>" type="text" size="30"/>
			</p>
			<p>
				<label>图片路径：</label>
				<input name="pic_url"  type="file" size="30"/>
			</p>
			<p>
				<label>原图样式：</label>
				<img width=200 height=150 src="<?php echo $bannerInfo->pic_url;?>" />
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
