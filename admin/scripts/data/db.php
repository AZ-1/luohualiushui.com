<?php
class db{
	function query($sql){
		//return mysql_unbuffered_query($sql);
		return mysql_query($sql);
	}

	function connect() 
	{
		$host		= 'localhost';
		$username	= 'root';
		$password	= 'duobaohui';
		$db			= 'appmarket';
		//echo 'connecting...'.PHP_EOL.PHP_EOL;;
		$this->mysqlConn= mysql_connect($host, $username, $password, false) or die(mysql_error());
		$sta2 = $this->query("SET NAMES utf8");
		$sta3 = mysql_select_db($db) or die(mysql_error());
	}

	function close_connect()
	{
		mysql_close($this->mysqlConn);
	}

	function select($sql)
	{
		if( !strpos($sql, 'SQL_NO_CACHE')){//脚步执行的mysql不缓存，节省内存开销
			$sql = str_ireplace('select','select SQL_NO_CACHE', $sql);
		}
		$this->connect();
		$result = $this->query($sql) or die(mysql_error().'-------'.$sql);
		$list = array();
		while($row = mysql_fetch_assoc($result))
		{
			$list[] = $row;
		}
		mysql_free_result($result);
		$this->close_connect();
		return $list;
	}

	function fetchAll($sql)
	{
		if( !strpos($sql, 'SQL_NO_CACHE')){//脚步执行的mysql不缓存，节省内存开销
			$sql = str_ireplace('select','SELECT SQL_NO_CACHE', $sql);
		}
		$this->connect();
		$result = $this->query($sql) or die(mysql_error().'----'. $sql);
		$list = array();
		while($row = mysql_fetch_object($result))
		{
			$list[] = $row;
		}
		mysql_free_result($result);
		$this->close_connect();
		return $list;
	}

	function execute($sql)
	{
		$this->connect();
		$rs = $this->query($sql) or die(mysql_error(). '---'. $sql); 
		if(stripos($sql, 'insert')!==false && mysql_insert_id() !== 0){
			$rs = mysql_insert_id();
		}
		$this->close_connect();
		return $rs;
	}
}
