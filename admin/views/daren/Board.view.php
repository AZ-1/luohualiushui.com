<form id="pagerForm" method="post" action="">
	<input type="hidden" name="pageNum" value="1" />
</form>
<div class="pageHeader">
	<form method="post" action="/daren/board" enctype="multipart/form-data" class="pageForm required-validate" onsubmit="return iframeCallback(this,navTabAjaxDone);">
		<div class="searchBar">
			<table class="searchContent">
				<tr>
					<td>
						更改公告图：<input type="file" name="board" />
						<input type="hidden" name="isUp" value="1"/>
						<img width="100" src="<?php echo $boardUrl ?>" />
					</td>
				</tr>
			</table>
			<div class="subBar">
				<ul>
					<li><div class="buttonActive"><div class="buttonContent"><button type="submit">上传</button></div></div></li>
				</ul>
			</div>
		</div>
	</form>
</div>
<div class="pageContent">
	<div class="panelBar">
		<ul class="toolBar">
			<li></li>
			<li class="line">line</li>
		</ul>
	</div>
</div>
