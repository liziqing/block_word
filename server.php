<?php

	$serv = new swoole_server("10.105.18.199", 9501);
	$dir = loadDir();
	$serv->set(Array("dir"=>$dir));
	$serv->on('receive', function ($serv, $fd, $from_id, $data) {	
	    $data = replace($data,$serv->setting['dir']);
	    $serv->send($fd, $data);
	    $serv->close($fd);
	});

	$serv->on('shutdown',function(swoole_server $server){
		trie_filter_free($serv->setting['dir']);
	});
	$serv->start();

	function  loadDir()
	{
		$dir = trie_filter_new();
		$path = "dir/dir.txt";
		$handle = fopen($path, "r");
		if ($handle) {
	    		while (!feof($handle)) {
	        		$value = fgets($handle);
        			$value = trim($value);
				if(empty($value) || $value[0]=="#")
				{
					continue;
				}
				$datas = explode("|", $value);
				trie_filter_store($dir, $datas[0]);
			}
		}
		return $dir ;
	}

	function  replace($content,$dir)
	{
		$arrRet = trie_filter_search_all($dir, $content);
		$char = "*";
		foreach($arrRet  as $ret)
		{
			$pos = $ret[0];
			$length = $ret[1];
			$content = substr_replace($content,str_repeat($char,$length),$pos,$length);
		}
		return $content;
	}
?>
