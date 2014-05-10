<div class="pageContent">
	<form method="post" action="/cosmetic/edit_brand?isUp=1" class="pageForm required-validate" enctype="multipart/form-data"onsubmit="return iframeCallback(this, navTabAjaxDone)";>
		<div class="pageFormContent" layoutH="56">
			<input name="id" type="hidden" value="<?php echo $brand->id;?>">
			<table>
				<tr>
					<td>首字母</td>
					<td><input type="text" name="first_character" size="1" length="1" maxlength="1" value="<?php echo $brand->first_character; ?>" /></td>
				</tr>
				<tr>	
					<td><label>中文名称:</label></td>
					<td><input name="chi_name" type="text" size="30" value="<?php echo $brand->chi_name; ?>"/><td>
				</tr></tr>
					<td><label>英文名称:</label></td>
					<td><input name="eng_name" type="text" size="30" value="<?php echo $brand->eng_name; ?>"/><td>
				</tr><tr>				
					<td><label>发源地:</label></td>
					<td><input name="birth_place" type="text" size="30" value="<?php echo $brand->birth_place; ?>"/><td>
				</tr><tr>
					<td><label>品牌创建时间:</label></td>
					<td><input name="create_time" type="text" size="30" value="<?php echo $brand->create_time; ?>"/><td>
			    </tr><tr>
					<td><label>创始人:</label></td>
					<td><input name="initator" type="text" size="30" value="<?php echo $brand->initator; ?>"/><td>
				</tr><tr>	
					<td><label>填入图片地址上传:</label></td>
					<td><input name="enter_img_add" type="text"  size="40" /> </td>
				</tr>
				<tr>
					<td><label>选择本地文件上传:</label></td>
					<td><input name="select_img_add" type="file"  size="40" onchange="viewmypic(showimg,this.form.img_add);" /> </td>
				</tr><tr>	
					<td><label>归属地:</label></td>
					<td><input name="brand_classify" type="text" size="30" value="<?php echo $brand->brand_classify; ?>"/><td>
				</tr><tr>	
					<td><label>官网:</label></td>
					<td><input name="official_web" type="text" size="30" value="<?php echo $brand->official_web; ?>"/><td>
				</tr><tr>				
					<td><label>品牌故事:</label></td>
					<td><input name="story" type="text" size="30" value="<?php echo $brand->story; ?>"/><td>
				</tr><tr>			
					<td><label>添加该品牌的时间:</label></td>
					<td><input name="add_time" type="text" size="30" value="<?php echo $brand->add_time; ?>"/><td>
				</tr>
		<tbody>		
			<tr><td><label> 原始图片：</label></td><tr>
			<img width=50 src="<?php echo $brand->img_add;?>">
		</tbody>	
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
