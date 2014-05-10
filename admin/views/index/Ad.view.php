	<style type="text/css">
	.content a{color:#000;}
	.content a:hover {color:#000;text-decoration:underline;}
	.recommend_lsit p{ margin-top:10px;}
	.submit{cursor:pointer;}
	.title{margin-top:20px;}
	.uploadBanner p{margin-top:15px;}
	</style>
	<div class="content">
		<h3 class="title">首页轮播banner</h3>
		<table style="width:600px;">
			<tr>
				<td>图片</td>
				<td>链接</td>
				<td>标题</td>
				<td>管理</td>
			</tr>
			<?php foreach($banner as $item){?>
				<tr height="150">
					<td><img src="<?php echo $item -> pic_url?>" width="100"></td>
					<td><?php echo $item -> link_url?></td>
					<td><?php echo $item -> title?></td>
					<td><a href="/index/ad?is_del=1&id=<?php echo $item -> id?>">删除</a></td>
				</tr>
			<?php }?>

		</table>
		<div class="uploadBanner">
			<form method="POST" enctype="multipart/form-data" action="/index/ad">
				<input type="hidden" name="is_add" value="1" />
				<p>标题:<input type="text" name="title" ></p>
				<p>链接:<input type="text" name="link_url" ></p>
				<p><input type="file" name="attach" /></p>
				<p><input type="submit" value="上传banner" /></p>
			</form>
		</div>
	</div>
