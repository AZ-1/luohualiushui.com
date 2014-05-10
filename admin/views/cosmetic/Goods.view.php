<link href="/static/css/core.css" type="text/css" rel="stylesheet"/>
<script src="/static/js/popup_layer.js" type="text/javascript" language="javascript"></script>
<script src="/static/js/article.js" type="text/javascript" language="javascript"></script>
<style>

.confirmCheck{width:400px;height:150px;margin-left:30px;position:absolute;top:200px;left:200px;background-color:#fff;display:none;border:2px solid #ccc;}
</style>
<form id="pagerForm" method="post" action="">
	<input type="hidden" name="pageNum"	value="1" />
	<input type="hidden" name="pro_name" value="<?php echo $pro_name?>" />
	<input type="hidden" name="brand_id" value="<?php echo $selected_brand_id?>" />
	<input  type="hidden" name="classify"	value="<?php echo $classify_id?>" />
	<input  type="hidden" name="benefits" value="<?php echo $benefits_id?>" />
	<input   type="hidden" name="price_id"  value="<?php echo $price_id?>" />
	<input  type="hidden" name="price"  value="<?php echo $price?>" />
</form>
<div class="pageHeader">
	<form onsubmit="return navTabSearch(this);" action="/cosmetic/goods?search=1" method="post">
	<div class="searchBar">
		<table class="searchContent">
			<tr>
				<td>
							产品ＩＤ<input type="text" name="pro_id" value="<?php echo $pro_id?>"/>
							产品名称<input type="text" name="pro_name" value="<?php echo $pro_name?>"/>
							品牌英文名称<select name="eng_brand_id"  id="all_brand_id">
								<option value=''>		
								<?php foreach($allBrands as $item){?>
									<option <?php if($item->id == $selected_brand_id)echo "selected=\"selected\"" ?>value="<?php echo $item->id;?>"><?php echo $item->eng_name;?></option>
								<?php }?>
							</select>
							品牌中文名称<select name="brand_id"  id="all_brand_id">
								<option value=''>		
								<?php foreach($allBrands as $item){?>
									<option <?php if($item->id == $selected_brand_id)echo "selected=\"selected\"" ?>value="<?php echo $item->id;?>"><?php echo $item->chi_name;?></option>
								<?php }?>
							</select>
				</td>
				<td>
						功效<select name="benefits" >
							<?php foreach($allBenefits as $item){?>
								<option value="<?php echo $item->id;?>"><?php echo $item->base->des_info;?></option>
								<?php $child = $item->child;?>
								<?php foreach($child as $subItem){?>
									<option <?php if($subItem->id==$benefits_id)echo "selected='true'"?> value="<?php echo $subItem->id;?>"><?php echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"."$subItem->des_info";?></ option>
								<?php }?>
							<?php }?>	
						</select>
						分类<select name="classify" >
							<?php foreach($allClassify as $item){?>
								<option value="<?php echo $item->id;?>" ><?php echo $item->base->classify_name;?></option>
								<?php $child = $item->child;?>
								<?php foreach($child as $subItem){?>
									<option <?php if($subItem->id == $classify_id)echo "selected='true'";?>value="<?php echo $subItem->id;?>" ><?php echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"."$subItem->classify_name";?></ option>
								<?php }?>
							<?php }?>	
						</select>
						价格区间<select name="price">
								<option <?php if($price_id==0)echo "checked='true'"?> value="" >全部</option>	
								<option <?php if($price_id==1)echo "checked='true'"?> value="100,0" >0-100</option>	
								<option <?php if($price_id==2)echo "checked='true'"?> value="250,100 " >100-250</option>	
								<option <?php if($price_id==3)echo "checked='true'"?> value="500,250" >250-500</option>	
								<option <?php if($price_id==4)echo "checked='true'"?> value="1000,500" >500-1000</option>	
								<option <?php if($price_id==5)echo "checked='true'"?> value="1000" >1000以上</option>	
						</select>
				</td>
			</tr>
		</table>
		<div class="subBar">
			<ul>
				<li><div class="buttonActive"><div class="buttonContent"><button type="submit">检索</button></div></div></li>
			</ul>
		</div>
	</div>
	</form>
</div>
<div class="pageContent">
	<div class="panelBar">
		<ul class="toolBar">
			<li><a class="add" href="/cosmetic/add_goods" target="dialog"  width="1200" height="1000" mask="true" mask="true" ><span>添加产品</span></a></li>
			<li class="line">line</li>
		</ul>
	</div>
	<table style="width: 100%;height:50%;border:0px solid #006699;overflow:hidden;" class="table" layoutH="138">
		<thead>
			<tr >
				<th width="5%">ID</th>
				<th width="10%">产品名称</th>
				<th width="10%">品牌名称</th>
				<th width="10%">分类</th>
				<th width="10%">功效</th>
				<th width="10%">系列</th>
				<th width="5%">价格</th>
				<th width="10%">规格</th>
				<th width="10%">更新时间</th>
				<th width="20%" >操作</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($goodsList as $k=>$v){?>
				<tr target="pro_id" rel="<?php echo $v->pro_id; ?>">
					<td><?php echo $v->pro_id;?></td>
					<td><?php echo $v->pro_name;?></td>
					<td><?php echo $v->brand_name;?></td>
					<td><?php echo $v->classify;?></td>
					<td><?php echo $v->funtionality;?></td>
					<td><?php echo $v->succession;?></td>
					<td><?php echo $v->price;?></td>
					<td><?php echo $v->specify;?></td>
					<td><?php echo $v->create_time;?></td>
					<td>
						<a class="edit" href="/cosmetic/edit_goods?pageNum=<?php echo $pageNum?>&pro_id=<?php echo $v->pro_id;?>" target="dialog"  width="1200" height="1000" mask="true" >编辑</a>||
						<a title="确定要删除吗?" class="delete" target="ajaxTodo" href="/cosmetic/del_goods?del_id=<?php echo $v->pro_id;?>">删除</a></td>
				</tr>
			<?php }?>
		</tbody>
	</table>
	<?php $this->includeTemplate('', 'pages');?>
</div>
