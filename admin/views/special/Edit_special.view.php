<div class="pageContent">
	<form method="post" action="/special/edit_special" class="pageForm required-validate" onsubmit="return iframeCallback(this,navTabAjaxDone);" enctype="multipart/form-data" >
		<div class="pageFormContent" layoutH="56">
		 <input type="hidden" name="is_edit" value="1" />
		 <input name="id" value="<?php echo $specialInfo->special_id; ?>" type="hidden"/>
		 <div style="clear:both;">
			 <label>标题：</label>
			 <input name="title" class="required" type="text" style="width:1211px;height=111px" value="<?php echo $specialInfo->title; ?>" />
		 </div>
		
		<div style="clear:both; margin-top:20px; height:1px; overflow:hidden;"></div>
		<div>
			<textarea style="width:95%;height:1000px" class="textInput" name="description"><?php echo $specialInfo->description; ?></textarea>
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
