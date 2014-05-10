<div class="pageContent">
	<form method="post" action="/index/edit_article" class="pageForm required-validate" onsubmit="return validateCallback(this, navTabAjaxDone)";>
	<div class="pageFormContent" layoutH="56">
			<p>
				<input type="hidden" name="aid" value="<?php echo $article->id;?>" />
				<label>文章标题:</label>
				<input readonly name="article_id" class="required" type="text" size="30" value="<?php echo $article->title;?>"/>

			</p>
			<p>
				<label>广场热门分类标签:</label>
				<select name="category_id">
					<?php foreach($categoryList as $c){?>
						<option value="<?php echo $c->id?>" <?php if($c->id == $article->category->id) echo "SELECTED";?>><?php echo $c->name;?></option>
					<?php }?>
				</select>
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
