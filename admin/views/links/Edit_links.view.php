<div class="pageContent">
	<form method="post" action="/links/Up_links" class="pageForm required-validate" onsubmit="return iframeCallback(this, navTabAjaxDone)";>
		<div class="pageFormContent" layoutH="56">
				<input name="Lid" value="<?php echo $linksInfo->links_id;?>" type="hidden"/>
			<p>
				<label>友情链接标题：</label>
				<input name="Lname" class="required" value="<?php echo $linksInfo->name;?>" type="text" size="30"/>
			</p>
			<p>
				<label>友情链接路径：</label>
				<input name="Lurl" value="<?php echo $linksInfo->url;?>" type="text" size="30"/>
			</p>
		<div class="formBar">
			<ul>
				<li><div class="buttonActive"><div class="buttonContent"><button type="submit">保存</button></div></div></li>
				<li>
					<div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div>
				</li>
			</ul>
		</div>
	</form>
</div>
