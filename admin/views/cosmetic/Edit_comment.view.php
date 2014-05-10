<div class="pageContent">
	<form method="post" action="/cosmetic/edit_comment?isUp=1" enctype="multipart/form-data" class="pageForm required-validate" onsubmit="return iframeCallback(this, navTabAjaxDone)" >
		<div class="pageFormContent" layoutH="56">
			<input name="comment_id" type="hidden" value="<?php echo $comment_id;?>">
			<table>
				</tr><tr>	
					<td><label>评论内容:</label></td>
						<textarea name="comment_content" style="width:90%;height:200px">
						<?php echo $comment_content;?>
						</textarea>
				</tr><tr>				
			</table>
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
