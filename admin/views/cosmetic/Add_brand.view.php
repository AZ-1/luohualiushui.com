 <div class="pageContent">
	<form method="post" action="/cosmetic/add_brand"  class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);" novalidate="novalidate">
		<div class="pageFormContent" layoutH="56">
			<table>
				<tr>
					<td>首字母</td>
					<td><input name="first_character" type="text" size="1" maxlength="1" /></td>
				</tr>
				<tr>
					<td><label>中文名称:</label></td>
					<td><input name="chi_name" type="text" size="30"  /></td>
				</tr>
				<tr>	
					<td><label>英文名称:</label></td>
					<td><input name="eng_name" type="text" size="30"  /></td>
				</tr>
				<tr>
					<td><label>创始人:</label></td>
					<td><input name="initator" type="text" size="30"  /><td>
				</tr>
				<tr>
					<td><label>发源地:</label></td>
					<td><input name="birth_place" type="text" size="30"  /></td>
				</tr>
				<tr>
					<td><label>归属地:</label></td>
					<td><input name="brand_classify" type="text" size="30"  /></td>
				</tr>
				<tr>
					<td><label>填入图片地址上传:</label></td>
					<td><input name="enter_img_add" type="text"  sie="40" /> </td>
				</tr>
				<tr>
					<td><label>选择本地文件上传:</label></td>
					<td><input name="select_img_add" type="file"  size="40" onchange="viewmypic(showimg,this.form.img_add);" /> </td>
				</tr>
				<tr>
					<td><label>创建时间:</label></td>
					<td><input name="create_time" type="text" size="30"  /><td>
				</tr>
				<tr>
					<td><label>官网:</label></td>
					<td><input name="official_web" type="text" size="30" /><td>
				</tr>
				<tr>
					<td><label>品牌故事:</label></td>
					<td><input name="story" type="text" size="30"  /><td>
				</tr>	
				<img name="showimg" id="showimg" src="" style="display:none;" />       
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
<script>       
function viewmypic(mypic,imgfile) {        
	if (imgfile.value){        
		mypic.src=imgfile.value;        
		mypic.style.display="";        
		mypic.border=1;        
	}        
}        
</script>      
