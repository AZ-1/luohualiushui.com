<div class="pageContent">
	<form method="post" action="/index/add_banner_m" enctype="multipart/form-data" class="pageForm required-validate" onsubmit="return iframeCallback(this,navTabAjaxDone);">
		<div class="pageFormContent" layoutH="56">
			<p>
				<label>标题：</label>
				<input name="title" class="required" type="text" size="30"/>
			</p>
			<p>
				<label>链接路径：</label>
				<input name="link_url" type="text" size="30"/>
			</p>
			<p>
				<label>图片路径：</label>
				<input name="pic_url" type="file" size="30"/>
			</p>
<!--
			<p>
				<label>文件路径：</label>
				<input name="file_path" type="text" size="30"/>
			</p>
-->
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
