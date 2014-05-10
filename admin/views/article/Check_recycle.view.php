<style>
.check{
	padding-top:5px;
	margin-right:10px;
	cursor:pointer;
}
.nopass{
	display:none;	
}
.quality{
	display:none;
}
</style>
<div class="pageContent" style="width:880px;">
		<div class="pageFormContent" layoutH="56">
			<div style="font-size:23px;text-align: center;color:red">
				<?php echo $article->title;?>
			</div>
			<div>
				<?php echo $article->content?>
			</div>
		</div>
</div><div class="formBar">
			<ul>
				<form method="post" action="/article/check_recycle?check=1" class="pageForm required-validate" onsubmit="return validateCallback(this, navTabAjaxDone)">
				<input type="hidden" name="id" value="<?php echo $article->article_id;?>"/>
				<!--<li><a class="buttonActive" href="javascript:;"><span>保存</span></a></li>-->
				<li>
					<div class="check" id="pass">恢复</div>
				</li>
				<li>
					<div style="float:left;" class="buttonActive quality"><div class="buttonContent"><button type="submit">保存</button></div></div>
				</li>
				</form>
			</ul>
		</div>

<script>
</script>
