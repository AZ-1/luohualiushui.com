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
				<form method="post" action="/article/check_article" class="pageForm required-validate" onsubmit="return validateCallback(this, navTabAjaxDone)">
				<input type="hidden" name="id" value="<?php echo $article->article_id;?>"/>
				<!--<li><a class="buttonActive" href="javascript:;"><span>保存</span></a></li>-->
				<li>
					<div class="check" id="pass">审核通过</div>
				</li>
				<li>
					<div class="quality" style="float:left;">
						质量:<select id="select" name="quality" style="float:none;">
								<option value="0">请选择</option>
								<option value="1">质量上</option>
								<option value="2">质量中</option>
								<option value="3">质量下</option>
							</select>
					</div>
				</li>
				<li>
					<div style="float:left;" class="buttonActive quality"><div class="buttonContent"><button type="submit">保存</button></div></div>
				</li>
				<li>
					<div style="color:#ff0000" class="check" id="nopass">审核不通过</div>
				</li>
				<li>
					<div class="nopass" style="float:right;">
						不通过的原因:<input type="text" id="reason" name="reason" style="float:none"/>
					</div>
				</li>
				<li>
					<div style="float:right;" class="buttonActive nopass"><div class="buttonContent"><button type="submit">保存</button></div></div>
				</li>
				</form>
			</ul>
		</div>

<script>
$("#pass").click(function(){
	$(".quality").show(200);
	$(".nopass").hide(20);
	$("#reason").val("");
});
$("#nopass").click(function(){
	$(".nopass").show(200);
	$(".quality").hide(20);
	$("#select").val("0");
});
</script>
