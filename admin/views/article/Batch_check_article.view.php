<div class="pageContent">
	<form method="post" action="/index/add_hot_daren" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone)";>
		<div class="pageFormContent" layoutH="56">
			<p>
				<label>文章id：</label>
				<input name="article_id" type="text" value="<?php echo $articleIds?>"/>(多个文章用逗号分隔)
			</p>
			<p>
				<label>审核通过</label>
				质量: 
				<select	name="quality">
					<option value="0">请选择</option>
					<option value="1">质量上</option>
					<option value="2">质量中</option>
					<option value="3">质量下</option>
				</select>	
			</p>
			<p>
				<label>审核不通过</label>
				不通过的原因:<input type="text" id="reason" name="reason" style="float:none"/>
			</p>
		</div>
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
