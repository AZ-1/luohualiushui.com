<div class="pageContent">
	<form method="post" action="/daren/add_brands" class="pageForm required-validate" enctype="multipart/form-data"onsubmit="return iframeCallback(this, navTabAjaxDone)";>
		<div class="pageFormContent" layoutH="56">
			<p>
				<label>品牌名称：</label>
				<input name="name" class="required" type="text" size="30"/>
			</p>
			<p>
				<label>链接路径：</label>
				<input name="link_url" type="text" size="30"/>
			</p>
			<p>
				<label>图片路径：</label>
				<input name="logo" type="file"/>
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
