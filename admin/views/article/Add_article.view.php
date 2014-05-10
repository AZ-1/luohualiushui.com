<div class="pageContent">
	<form method="post" action="/article/add_article" class="pageForm required-validate" onsubmit="return validateCallback(this, navTabAjaxDone)";>
		<div class="pageFormContent" layoutH="56">
			<p>
				<input type="hidden" name="article_id" value="<?php echo $Listarticle->article_id;?>" />
				<label>文章标题：</label>
				<input name="title" type="text" size="30" value="<?php echo $Listarticle->title; ?>" />
			</p>
			<!--<p>
				<label>描述：</label>
				<input name="description" class="required" type="text" size="30"/>
			</p>-->
			<p>
				<label>所属话题：</label>
				<select name="topic" class="required combox">
					<option value="" selected>请选择</option>
                <?php foreach($Listtopic as $v){?>
					<option value="<?php echo $v->topic_id;?>"><?php echo $v->title;?></option>
				<?php }?>
				</select>
				<span class="unit">万元</span>
			</p>
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
