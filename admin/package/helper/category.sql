	CREATE TABLE IF NOT EXISTS `beauty_category` (
			`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			`name` varchar(32) NOT NULL,
			`pid` int(10) unsigned NOT NULL,
			`level` tinyint(4) NOT NULL,
			`left_num` int(10) unsigned NOT NULL,
			`right_num` int(10) unsigned NOT NULL,
			`status` tinyint(4) NOT NULL,
			`sort` tinyint(3) unsigned NOT NULL,
			`description` varchar(255) NOT NULL,
			`article_num` int(10) unsigned NOT NULL,
			`top_id` int(10) unsigned NOT NULL,
			`navigation_id` int(10) unsigned NOT NULL,
			`source_cats_id` int(10) unsigned NOT NULL,
			`is_end` tinyint(1) NOT NULL,
			`update_time` int(10) unsigned NOT NULL,
			`create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

	INSERT INTO `hitao_beauty`.`beauty_category` (`id` , `name` ,`pid` , `level` , `left_num` , `right_num` , `status` ,`sort` ,`description` ,`article_num` ,`top_id` ,`is_end` ,`update_time` ,`create_time` ) VALUES ('1', 'root',  '0', '0', '1', '2', '', '', '', '', '0', '0', '',CURRENT_TIMESTAMP );
	INSERT INTO `hitao_beauty`.`beauty_category` (`id` , `name` ,`pid` , `level` , `left_num` , `right_num` , `status` ,`sort` ,`description` ,`article_num` ,`top_id` ,`is_end` ,`update_time` ,`create_time` ) VALUES ('2', 'default',  '0', '0', '10000000', '20000000', '', '', '', '', '0', '0', '',CURRENT_TIMESTAMP );

	
	/*
	// ---------------------_--- 分类的存储过程 -------------------
	//----- add child
	*/
	delimiter $
	CREATE DEFINER=`hitao_beauty`@`localhost` PROCEDURE `hitao_beauty_add_beauty_category_child`(IN `parent_cate_id` INT(10) UNSIGNED, IN `new_cate_name` CHAR(32))
		NO SQL
		BEGIN 
			SELECT @rNum := right_num, @next_level := level, @top_id:=top_id FROM beauty_category WHERE id = parent_cate_id;

			UPDATE beauty_category SET is_end =0,right_num=right_num+2  WHERE id = parent_cate_id;

			INSERT INTO beauty_category( name, left_num, right_num, pid, level, sort, is_end,top_id ) VALUES ( new_cate_name, @rNum, @rNum+1, parent_cate_id, @next_level +1, 60, 1, @top_id);

			IF @next_level < 1 THEN
				SELECT   @newId := LAST_INSERT_ID() ;
				UPDATE beauty_category SET top_id = id WHERE id=@newId;
			END IF;

		END
	$
	delimiter ;

	/*----- del ---*/
	delimiter $
	CREATE DEFINER=`hitao_beauty`@`localhost` PROCEDURE `hitao_beauty_del_beauty_category`( IN `cataId` INT )
		BEGIN 

			SELECT @lNum := left_num, @rNum := right_num, @width := right_num - left_num +1 FROM beauty_category WHERE id = cataId;

			SELECT @defLeftNum :=left_num FROM beauty_category WHERE id=2;

			DELETE FROM beauty_category WHERE left_num BETWEEN @lNum AND @rNum ;

			UPDATE beauty_category SET right_num = right_num - @width WHERE right_num > @rNum AND right_num < @defLeftNum ;

			UPDATE beauty_category SET left_num = left_num - @width WHERE left_num > @rNum AND left_num <  @defLeftNum ;


		END
	$
	delimiter ;

	/*---- move child */
	delimiter $
	CREATE DEFINER=`hitao_beauty`@`localhost` PROCEDURE `hitao_beauty_move_beauty_category_child`(IN `cate_id` INT, IN `to_parent_id` INT)
		NO SQL
		BEGIN
			select @p_level := level, @p_lnum := left_num, @p_rnum := right_num, @p_top_id:=top_id from beauty_category  where id = to_parent_id;

			select @lNum := left_num, @rNum := right_num, @level :=level, @old_pid :=pid from beauty_category  where id = cate_id;

			IF @p_rnum < @lNum THEN

				update beauty_category set left_num = left_num + (@rNum - @lNum + 1) where left_num >= @p_rnum AND left_num < @lNum;
				update beauty_category set right_num = right_num + (@rNum - @lNum + 1) where right_num >= @p_rnum AND right_num < @lNum;
				update beauty_category set level = @p_level+1, pid = to_parent_id, left_num = left_num-( @lNum - @p_rnum), right_num = right_num - (@lNum - @p_rnum) where id = cate_id;
				update beauty_category set level = level + (@p_level+1 - @level), left_num = left_num-(@lNum - @p_rnum), right_num = right_num - (@lNum - @p_rnum) where left_num > @lNum and right_num < @rNum;

			ELSEIF @p_lnum > @rNum THEN

				update beauty_category set left_num = left_num -(@rNum - @lNum + 1) where left_num <= @p_lnum AND left_num > @rNum;
				update beauty_category set right_num = right_num -( @rNum - @lNum + 1) where right_num <= @p_lnum AND right_num > @rNum;
				update beauty_category set level = @p_level+1, pid = to_parent_id, left_num = left_num+(@p_lnum - @rNum), right_num = right_num+(@p_lnum - @rNum) where id = cate_id;
				update beauty_category set level = level + (@p_level+1 - @level), left_num = left_num+(@p_lnum - @rNum), right_num = right_num+(@p_lnum - @rNum) where left_num > @lNum and right_num < @rNum;

			END IF;
			update beauty_category set is_end=0 where id = to_parent_id and left_num<right_num-1;
			update beauty_category set is_end=1 where id = @old_pid and left_num=right_num -1;
			update beauty_category set top_id = @p_top_id where id = cate_id;

		END	
	$
	delimiter ;
