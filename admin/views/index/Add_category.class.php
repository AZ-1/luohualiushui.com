<div class="pageContent">
	<form method="post" action="/index/add_hot_article" class="pageForm required-validate" onsubmit="return validateCallback(this, navTabAjaxDone)";>
		<div class="pageFormContent" layoutH="56">
			<p>
				<label>文章ID：</label>
				<input name="article_id" class="required" type="text" size="30"/><label>(多个id用','分割)</label>
			</p>
			<p>
				<label>热门分类：</label>
<!------		<select name="hot_category_id">
					<?php foreach($hotCategory as $hc){?>
					<option value="<?php echo $hc->id;?>"><?php echo $hc->name?></option>
					<?php }?>
				</select>
------>
				<input type="text" name="hot_category_id" size="30"/> 
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
