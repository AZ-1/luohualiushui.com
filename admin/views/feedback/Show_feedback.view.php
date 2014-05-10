<style>
.check{
	padding-top:5px;
	margin-right:10px;
	cursor:pointer;
}
.nopass{
	display:none;	
}
.quality{
	display:none;
}
</style>
<div class="pageContent" style="width:880px;margin:0 auto;">
		<div class="pageFormContent" layoutH="56">
			<div style="font-size:23px;text-align: center;color:red">
				<?php
					if(isset($feedback->userInfo)){
						echo $feedback->userInfo->realname;
					}else{
						echo '匿名';
					}
					echo '<br>';
					echo $feedback->create_time;
				?>
			</div>
			<div style="border:2px solid #ccc;">
				<?php echo $feedback->content?>
			</div>
		</div>
</div>
		<div class="formBar">
		</div>

<script>
</script>
