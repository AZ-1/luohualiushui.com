<div class="pageContent">
	<form method="post" action="/cosmetic/edit_goods?isUp=1"  class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);" novalidate="novalidate">
		<div class="formBar">
			<ul>
				<!--<li><a class="buttonActive" href="javascript:;"><span>保存</span></a></li>-->
				<li><div class="buttonActive"><div class="buttonContent"><button type="submit">保存</button></div></div></li>
				<li>
					<div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div>
				</li>
			</ul>
		</div>
		<input type="hidden" name="pro_id" value="<?php echo $pro_id?>";>
		<input type="hidden" name="pageNum" value="<?php echo $pageNum?>";>
		<div class="pageFormContent" layoutH="50">
			<table>
				<thead>
					<tr>
						<th >产品名称
						<th >产品价格
						<th >产品系列
						<th >品牌名称
						<th >分类
						<th> 填入图片地址上传:
						<th> 选择图片:
					</tr>
				</thead>
				<tbody>
						<tr>
							<td><input type="text" name="pro_name" value="<?php echo $pro_name?>"/>
							</td><td><input type="text" name="pro_price" value="<?php echo $pro_price?>"/>
							</td><td><input type="text" name="pro_succession" value="<?php echo $pro_succession?>"/>
							</td><td><select name="brand_id" >
								<?php foreach($allBrands as $item){?>
									<option <?php if($item->id == $selected_brand_id)echo "selected=\"selected\"" ?>value="<?php echo $item->id;?>"><?php echo $item->chi_name;?></option>
								<?php }?>
							</select>
							</td><td><select name="pro_classify" >
								<?php foreach($allClassify as $item){?>
									<option value="<?php echo $item->id;?>" ><?php echo $item->base->classify_name;?></option>
									<?php $child = $item->child;?>
									<?php foreach($child as $subItem){?>
										<option <?php if($subItem->id == $selected_classify_id)echo "selected=\"selected\"" ?>value="<?php echo $subItem->id;?>" ><?php echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"."$subItem->classify_name";?></ option>
									<?php }?>
								<?php }?>	
							</select>
						</td>
						<td><input name="enter_img_add" type="text"  sie="40" /> </td>
						<td><input name="select_img_add" type="file"  size="40" onchange="viewmypic(showimg,this.form.img_add);" /> </td>
					</tr>
				</tbody>
				<thead>
					<tr><td> 功效</td></tr>
				</thead>
				<tbody>
					<tr>
						<?php foreach($allBenefits as $item){?>
							<?php $i=5;?>
							<?php foreach($item->child AS $subItem){?>
								<?php if($i-- == 0){$i=5; echo "</tr><tr>";}?>
								<td>
									<?php $flag = false;?>
									<?php foreach($allLabel as $label){?>
										<?php if($label == $subItem->id)$flag=true;?>
									<?php }?>
									<input width="5%" name="label[]" type="checkbox"  <?php if($flag)echo "checked=\"true\"" ?>  value="<?php echo $subItem->id;?>" > <?php echo "$subItem->des_info";?>						
								</td>
							<?php }?>	
						<?php }?>	
					</tr>
				</tbody>
				<thead>
					<tr>
						<th width="35%">专业测评 
						<th width="35%">详细评分 
						<th width="35%">肤质分布
						<th width="35%">年龄分布
						<th width="35%">原始图片	
					</tr>
				</thead>
				<tbody>
				<tr>	
					<td>
						<textarea name="pro_mult_assess" rows="20" >
							<?php echo $pro_assess_content;?>
						</textarea>
					</td>
					<td>
						<textarea name="detail_mult_assess" rows="20" >
							<?php echo $detail_assess_content;?>
						</textarea>
					</td>
					<td>
						<textarea name="skin_mult_assess" rows="20" >
							<?php echo $skin_assess_content;?>
						</textarea>
					</td>
					<td>
						<textarea name="age_mult_assess" rows="20" >
							<?php echo $age_assess_content;?>
						</textarea>
					</td>
					<td>
						<img src="<?php echo "$pro_img_add"?>" height="80" width="90">
					</td>
				</tr>
				</tbody>
			</table>
		</div>
	</form>
</div>
