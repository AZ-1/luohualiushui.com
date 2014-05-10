             <?php $identity['super'] = array(1); $identity['admin'] = array(1,2); $identity['editor'] = array(1,2,3)?>              
				<div class="toggleCollapse"><h2>主菜单</h2><div>收缩</div></div>
				<div class="accordion" fillSpace="sidebar">
					<div class="accordionHeader">
						<h2><span>Folder</span>网站内容</h2>
					</div>
					<div class="accordionContent">
						<ul class="tree treeFolder">
							<?php  if(in_array($this->user->identity, $identity['admin'])): ?>
								<li><a>广场</a>
									<ul>
										<li><a href="/index/banner" target="navTab" rel="index_banner">焦点图</a></li>
										<li><a href="/index/article" target="navTab" rel="index_article">推荐热门文章</a></li>
										<li><a href="/index/daren" target="navTab" rel="index_daren">推荐热门达人</a></li>
										<li><a href="/index/topic" target="navTab" rel="index_topic">推荐热门话题</a></li>
										<!--<li><a href="/index/category" target="navTab" rel="index_category">推荐分类</a></li>
										<li><a href="/index/category_article" target="navTab" rel="index_category_article">推荐分类文章</a></li>-->
									</ul>
								</li>
								<li><a>达人</a>
									<ul>
										<li><a href="/daren/banner" target="navTab" rel="daren_banner">焦点图</a></li>
										<li><a href="/daren/board" target="navTab" rel="daren_board">公告</a></li>
										<li><a href="/daren/user" target="navTab" rel="daren_user">达人管理</a></li>
										<li><a href="/daren/grade" target="navTab" rel="daren_board">达人身份</a></li>
										<li><a href="/daren/user_article" target="navTab" rel="daren_user_article">推荐达人文章</a></li>
										<li><a href="/daren/user_tag" target="navTab" rel="daren_user_tag">推荐达人标签</a></li>
										<li><a href="/daren/user_category" target="navTab" rel="daren_user_category">推荐达人分类</a></li>
										<li><a href="/daren/brands" target="navTab" rel="daren_brands">推荐品牌</a></li>
									</ul>
								</li>
								<li><a>话题</a>
									<ul>
										<li><a href="/topic/index" target="navTab" rel="topic_index">话题列表</a></li>
									</ul>
								</li>
								<li><a>专题</a>
									<ul>
										<li><a href="/special/index" target="navTab" rel="special_index">专题列表</a></li>
									</ul>
								</li>
								<li><a >文章管理</a>
									<ul>
										<li><a href="/article/category" target="navTab" rel="article_categoroy">分类</a></li>
										<li><a href="/article/tag" target="navTab" rel="article_tag">标签</a></li>
										<li><a href="/article/index" target="navTab" rel="article_index">文章列表</a></li>
										<li><a href="/article/recycle?is_delete=1" target="navTab" rel="article_index">回收站</a></li>
										<li><a href="/article/comment" target="navTab" rel="article_index">评论管理</a></li>
									</ul>
								</li>
								<li><a >友链管理</a>
									<ul>
										<li><a href="/links/Addlinks" target="navTab" rel="links_Addlinks">添加友链</a></li>
										<li><a href="/links/Listlinks" target="navTab" rel="links_Listlinks">友链列表</a></li>
									</ul>
								</li>
							<?php endif; ?>
							<?php  if(in_array($this->user->identity, $identity['super'])): ?>
								<li><a>用户管理</a>
									<ul>
										<li><a href="/user/user" target="navTab" rel="daren_user">用户列表</a></li>
										<li><a href="/user/identity" target="navTab" rel="daren_board">身份</a></li>
										<li><a href="/user/statistics" target="navTab" rel "daren_statistics">新增用户</a></li>
										<li><a href="/user/Analysis_activity" target="navTab" rel "daren_analysis">活跃用户</a></li>
									</ul>
								</li>
                                  	<li><a>活动管理</a>
									<ul>
										<li><a href="/activity/lottery" target="navTab" rel="activity_lottery">获奖信息</a></li>
								        <li><a href="/activity/shoppinglist" target="navTab" rel="activity_shoppingList">优惠列表</a></li>
									</ul>
								</li>

							<?php endif; ?>
						</ul>
					</div>
					<div class="accordionHeader">
						<h2><span>Folder</span>手机内容</h2>
					</div>
					<div class="accordionContent">
						<ul class="tree treeFolder">
							<?php  if(in_array($this->user->identity, $identity['editor'])): ?>
								<li><a >化妆品库</a>
									<ul>
										<li><a href="/cosmetic/index" target="navTab" rel="brand_index">品牌分类</a></li>
										<li><a href="/cosmetic/funtionality" target="navTab" rel="funtionality">功能分类</a></li>
										<li><a href="/cosmetic/ranking" target="navTab" rel="ranking">排行榜</a></li>
										<li><a href="/cosmetic/comment" target="navTab" rel="ranking">产品评论</a></li>
										<li><a href="/cosmetic/Goods" target="navTab" rel="Goods">产品管理</a></li>
										<li><a href="/cosmetic/benefits" target="navTab" rel="benefits">功效管理</a></li>
										<li><a href="/cosmetic/banner" target="navTab" rel="benefits">焦点图</a></li>
									</ul>
								</li>
							<?php endif; ?>
							<li><a>广场</a>
								<ul>
									<li><a href="/index/banner_m" target="navTab" rel="index_banner">焦点图</a></li>
									<li><a href="/index/article" target="navTab" rel="index_article">热门文章</a></li>
								</ul>
							</li>
							<li><a>达人</a>
								<ul>
									<li><a href="/daren/banner_m" target="navTab" rel="daren_banner">焦点图</a></li>
								</ul>
							</li>
							<li><a >反馈意见</a>
								<ul>
									<li><a href="/feedback/index" target="navTab" rel="feedback_index">反馈意见</a></li>
								</ul>
							</li>
						</ul>
					</div>
				</div>
