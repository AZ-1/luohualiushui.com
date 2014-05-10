<div class="pageContent">
	<form method="post" action="/index/Edit_banner_m" class="pageForm required-validate" enctype="multipart/form-data" onsubmit="return iframeCallback(this, navTabAjaxDone)";>
	<div class="pageFormContent" layoutH="56">
			<p>
				<input type="hidden" name="id" value="<?php echo $banner->id;?>" />
				<label>标题：</label>
				<input name="title" class="required" style="width:311px;height=111px"  tyype="text" value="<?php echo $banner->title;?>"/>
			</p>
			<p>
				<label>链接路径：</label>
				<input name="link_url" type="text" size="30" value="<?php echo $banner->link_url;?>"/>
			</p>
			<p>
				<label>图片路径：</label>
				<input name="pic_url" type="file" />
			</p>
			<p>
				<label>原图样式:</label>
				<img src="<?php echo $banner->pic_url;?>"/>
			</p>
		<div class="formBar">
			<ul>
				<!--<li><a class="buttonActive" href="javascript:;"><span>保存</span></a></li>-->
				<li><div class="buttonActive"><div class="buttonContent"><button type="submit">保存</button></div></div></li>
               <!--
				<li>
					<div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div>
				</li> -->
			</ul>
		</div>
	</form>
</div>
