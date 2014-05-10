<div class="pageContent">
	<form method="post" action="/links/Add_links" class="pageForm required-validate" onsubmit="return validateCallback(this, navTabAjaxDone)";>
		<div class="pageFormContent" layoutH="56">
			<p>
				<label>友情链接名称:</label>
				<input name="Lname" class="required" type="text" size="30"/>
			</p>
			<p>
				<label>友情链接地址:</label>
				<input name="Lurl" type="text" size="30"/>
			</p>
		<div class="formBar">
			<ul>
				<!--<li><a class="buttonActive" href="javascript:;"><span>保存</span></a></li>-->
				<li><div class="buttonActive"><div class="buttonContent"><button type="submit">保存</button></div></div></li>
				<li>
					<div class="buttonActive"><div class="buttonContent"><button type="button" class="close">取消</button></div></div>
				</li>
			</ul>
		</div>
	</form>
</div>
