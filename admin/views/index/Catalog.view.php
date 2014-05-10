<style type="text/css">
.attrList{float:left;width:200px;margin:10px;}
.attrList .checkboxPanel{height:300px;overflow-y:scroll;border:1px solid #CFCACA;}
.attrList .cataName{text-align:center;}
.selectPanel{position:fixed;width:200px; right:0px;background:#fff;padding:10px;border:1px solid #CFCACA;border-radius:5px;max-height:400px;overflow-y:scroll;}
.checkboxPanel{float:left;overflow:hidden;width:100%;margin-top:10px;}
.attrCheckBox{float:left;width:155px; overflow:hidden;height:20px;padding-left:5px;padding-right:5px;border:1px solid #fff;margin-left:10px;margin-top:5px;}
.cataName{margin-top:10px;float:left;}
.filterPanel input{ width:60px;margin-right:10px;}
.filterPanel td{padding:5px;}
.defaultSelect td{padding:10px;}
.content .attrCheckBox a{color:#025A8D}
.red{color:#CB2027}
</style>
<div class="content">
	<h3 class="cataName">已生成列表</h3>
	<div class="checkboxPanel">
		<?php  
		foreach($list as $v){?>
			<p  class="attrCheckBox">
			<a href="javascript:void(0)" class="showDelItem"  attrId="<?php  echo $v -> id?>"><?php  echo $v->name?></a>	
			&nbsp;<a href="javascript:void(0)" class="delNav" style="color:#CB2027">x</a>	
			</p>
		<?php  }?>
	</div>
	<div class="attrPanel">	
		<div class="attrList">
			<h3 class="cataName">类目 <input type="checkbox" class="selectAll"> 全选</h3>
			<div class="checkboxPanel catalogCheck">
			<?php  
			$attr = $this->view->attr;
			$key = $this->view->key;
			foreach($attr as $v){?>
				<p class="attrCheckBox">
				<input type="checkbox" class="checkbox" name="cata[]" value="<?php  echo $v -> id?>" /><a title="" href="javascript:void(0)" class="showAttr" attrId="<?php  echo $v -> id?>"><?php  echo $v->name?></a>	
				</p>
			<?php  }?>
			</div>
		</div>
	</div>

</div>
