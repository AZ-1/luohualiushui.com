<form id="pagerForm" method="post" action="/special/index">
	<input type="hidden" name="pageNum" value="1" />
</form>
<div class="pageHeader">
	<form onsubmit="return navTabSearch(this);" action="/special/index" method="post">
	<div class="searchBar">
		<table class="searchContent">
			<tr>
				<td>
					搜索：<input type="text" name="keyword" placeholder="专题标题"/>
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
             <li><a class="add" href="/special/add_special" target="navTab"><span>添加专题</span></a></li> 
             <li></li> 
             <li class="line">line</li> 
         </ul> 
     </div> 
     <table class="table" width="100%" layoutH="138"> 
         <thead> 
             <tr> 
                 <th width="80">ID</th> 
                 <th>标题</th> 
				 <th>创建时间</th>
				 <th colspan="2">操作</th>
             </tr> 
         </thead> 
         <tbody> 
             <?php foreach($specialList as $k=>$v):?> 
             <tr target="special_id" rel="<?php echo $v->id ?>"> 
                 <td width="5%"><?php echo $v->special_id;?></td> 
				 <td width="65%"><a href="<?php echo MEI_BASE_URL.'special/index/id/'.$v->special_id; ?>" target="_blank"><?php echo $v->title;?></a></td>
				 <td width="15%"><?php echo $v->create_time; ?></td>
				 <td width="15%" align="center">
					 <a class="edit" href="/special/edit_special?id=<?php echo $v->special_id; ?>" target="navTab"><span>修改</span></a>
                    <?php if($v->is_online == 1){ ?>
						|| <a class="delete" href="/special/Is_online_special?special_id=<?php echo $v->special_id; ?>&online=0" target="ajaxTodo" style="color:green;">当前上线状态</a>
                    <?php }else{?>
                        || <a class="delete" href="/special/Is_online_special?special_id=<?php echo $v->special_id; ?>&online=1" target="ajaxTodo" style="color:#444;">当前下线状态</a>
                    <?php } ?>
				 </td>
			 </tr> 
             <?php endforeach; ?> 
         </tbody> 
     </table> 
	 <?php $this->includeTemplate('', 'pages');?> 
</div>
