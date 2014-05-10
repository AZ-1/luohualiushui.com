<div class="pageContent">
	<form method="post" action="/cosmetic/add_goods?isUp=1" class="pageForm required-validate" enctype="multipart/form-data"onsubmit="return iframeCallback(this, navTabAjaxDone)";>
		<div class="formBar">
			<ul>
				<!--<li><a class="buttonActive" href="javascript:;"><span>保存</span></a></li>-->
				<li><div class="buttonActive"><div class="buttonContent"><button type="submit">保存</button></div></div></li>
				<li>
					<div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div>
				</li>
			</ul>
		</div>
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
							<td><input type="text" name="pro_name" />
							</td><td><input type="text" name="price"/>
							</td><td><input type="text" name="succession"/>
							</td><td><select name="brand_id" >
								<?php foreach($allBrands as $item){?>
									<option value="<?php echo $item->id;?>"><?php echo $item->chi_name;?></option>
								<?php }?>
							</select>
							</td><td><select name="classify_id" >
								<?php foreach($allClassify as $item){?>
									<option value="<?php echo $item->id;?>" ><?php echo $item->base->classify_name;?></option>
									<?php $child = $item->child;?>
									<?php foreach($child as $subItem){?>
										<option value="<?php echo $subItem->id;?>" ><?php echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"."$subItem->classify_name";?></ option>
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
									<input name="label[]" width="5%" type="checkbox" value="<?php echo $subItem->id;?>" > <?php echo "$subItem->des_info";?>						
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
						<textarea name="pro_mult_assess" rows="10" >
							<?php echo "实际效果:\r\n外观质地:\r\n细胞活性:\r\n抗氧化性:\r\n肌肤保湿:\r\n";?>
						</textarea>
					</td>
					<td>
						<textarea name="detail_mult_assess" rows="10" >
							<?php echo "吸收性:\r\n肤保湿:\r\n美白度:\r\n抗衰老:\r\n性价比:\r\n";?>
						</textarea>
					</td>
					<td>
						<textarea name="skin_mult_assess" rows="10" >
							<?php echo "混合性:\r\n干性:\r\n中性:\r\n油性:\r\n敏感性:\r\n";?>
						</textarea>
					</td>
					<td>
						<textarea name="age_mult_assess" rows="10" >
							<?php echo "26-30岁:\r\n31-40岁:\r\n20-25岁:\r\n20以下:\r\n40以上:\r\n"?>
						</textarea>
					</td>
				</tr>
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
