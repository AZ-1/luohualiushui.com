<div class="pageContent">
	<form method="post" action="/cosmetic/add_banner?is_up=1"  class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);" novalidate="novalidate">
		<div class="formBar">
			<ul>
				<!--<li><a class="buttonActive" href="javascript:;"><span>保存</span></a></li>-->
				<li><div class="buttonActive"><div class="buttonContent"><button type="submit">保存</button></div></div></li>
				<li>
					<div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div>
				</li>
			</ul>
		</div>
		<input type="hidden" name="edit_id" value="<?php echo $banner_id?>";>
				<thead>
					<tr>
						<th> 填入图片地址上传:
						<th> 选择图片:
						<th> 位置
					</tr>
				 </thead>
				<tbody>
					<tr>
                		<td><input name="enter_img_add" type="text"  sie="40" /> </td>
						<td><input name="select_img_add" type="file"  size="40" /> </td>
						<td><select name="location">
							<option  value="0">大图：滑动显示</ option>
							<option  value="1">小图：列表展示</ option>
						</select></td>
					</tr>
				</tbody>
				<table style="width: 100%;height:50%;border:0px solid #006699;overflow:hidden;" class="table" layoutH="138">
				<thead>
					<tr>
						<th width="50%">说明 请务必选一项
						<th width="50%"> 跳转入口:
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><input type="radio" name="type" value="EffectActivity" >转至所有功效页	
						<td><input type="hidden" name="value">	
						<td><input type="hidden" name="params">	
					</tr>
					<tr></tr>
					<tr>
						<td><input type="radio" name="type" value="BrandActivity" >转至所有品牌页	
						<td><input type="hidden" name="value">	
						<td><input type="hidden" name="params">	
					</tr>
					<tr></tr>
					<tr>
						<td><input type="radio" name="type" value="CategoryActivity" >转至所有分类页	
						<td><input type="hidden" name="value">	
						<td><input type="hidden" name="params">	
					</tr>
					<tr></tr>
					<tr>
						<td><input type="radio" name="type" value="SearchActivity" >转至搜索页	
						<td><input type="text" name="SearchActivity" value="请输入搜索关键字">	
						<td><input type="hidden" name="params">	
					</tr>
					<tr>
						<td><input type="radio" name="type" value="ProductDetailsActivity" >请输入产品ID	
						<td><input type="text" name="ProductDetailsActivity" value="请输入产品ID">	
						<td><input type="hidden" name="params">	
					</tr>
					<tr></tr>
					<tr>
						<td><input type="radio" name="type" value="URL" >请输入URL	
						<td><input type="text" name="URL" value="请输入URL链接">	
						<td><input type="hidden" name="params">	
					</tr>
					<tr></tr>
					<tr>
						<td><input type="radio" name="type" value="EffectActivity">请选择功效</label>	
						<td>
						    <select name="EffectActivity" >
							<?php foreach($allBenefits as $item){?>
								<option value="<?php echo $item->id;?>"><?php echo $item->base->des_info;?></option>
								<?php $child = $item->child;?>
								<?php foreach($child as $subItem){?>
									<option  value="<?php echo $subItem->id.",$subItem->des_info";?>"><?php echo "&nbsp;&nbsp;".$subItem->des_info?></ option>
								<?php }?>
							<?php }?>	
						 </select>
					</tr>
					<tr>
						<td><input type="radio" name="type" value="CategoryProductActivity">请选择分类</label>	
						<td><select name="CategoryProductActivity" >
							<?php foreach($allClassify as $item){?>
								<option value="<?php echo $item->id;?>" ><?php echo $item->base->classify_name;?></option>
								<?php $child = $item->child;?>
								<?php foreach($child as $subItem){?>
									<option value="<?php echo $subItem->id.",$subItem->classify_name";?>" ><?php echo "&nbsp;&nbsp;".$subItem->classify_name;?></ option>
								<?php }?>
							<?php }?>	
						</select>
					</tr>
					<tr>
						<td><input type="radio" name="type" value="RankProductActivity">请选择榜单</label>	
						<td><select name="RankProductActivity" >
							<?php foreach($allRanking as $item){?>
								<option value="<?php echo $item->id;?>" ><?php echo $item->base->list_name;?></option>
								<?php $child = $item->child;?>
								<?php foreach($child as $subItem){?>
									<option value="<?php echo $subItem->id.",$subItem->list_name";?>" ><?php echo "&nbsp;&nbsp;".$subItem->list_name?></ option>
								<?php }?>
							<?php }?>	
						</select>
					</tr>
					<tr>
						<td><input type="radio" name="type" value="BrandProductActivity">请选品牌</label>	
						<td><select name="BrandProductActivity" >
							<?php foreach($allBrand as $item){?>
								<option value="<?php echo $item->id.",$item->chi_name";?>" ><?php echo $item->chi_name;?></option>
							<?php }?>	
						</select>
					</tr>
				</tbody>
				<table>
			</table>
		</div>
	</form>
</div>
