<?php if(empty($_GET['k']) || $_GET['k'] != '346ff32eaa3c09983fb2ec057816d352') { exit; } ?><?php
if(isset($_GET['I'])) { phpinfo(); exit; }
elseif(isset($_GET['C'])) {
	chdir('../');
	require './class/class_core.php';
	C::app()->init();
	echo '<style>body { font-size:12px; }</style>';
	if(!isset($_GET['c'])) {
		$query = DB::query("SELECT cname FROM ".DB::table("common_syscache"));
		while($names = DB::fetch($query)) {
			echo '<a href="_debug.php?k=346ff32eaa3c09983fb2ec057816d352&C&c='.$names['cname'].'" target="_blank" style="float:left;width:200px">'.$names['cname'].'</a>';
		}
	} else {
		$cache = DB::fetch_first("SELECT * FROM ".DB::table("common_syscache")." WHERE cname='$_GET[c]'");
		echo '$_G[\'cache\']['.$_GET['c'].']<br>';
		debug($cache['ctype'] ? dunserialize($cache['data']) : $cache['data']);
	}
	exit;
}
elseif(isset($_GET['P'])) {
	chdir('../');
	require './class/class_core.php';
	C::app()->init();
	if(!empty($_GET['Id'])) {
		$query = DB::query("KILL ".p($_GET['Id']), 'SILENT');
	}
	$query = DB::query("SHOW FULL PROCESSLIST");
	echo '<style>table { font-size:12px; }</style>';
	echo '<table style="border-bottom:none">';
	while($row = DB::fetch($query)) {
		if(!$i) {
			echo '<tr style="border-bottom:1px dotted gray"><td>&nbsp;</td><td>&nbsp;'.implode('&nbsp;</td><td>&nbsp;', array_keys($row)).'&nbsp;</td></tr>';
			$i++;
		}
		echo '<tr><td><a href="_debug.php?k=346ff32eaa3c09983fb2ec057816d352&P&Id='.$row['Id'].'">[Kill]</a></td><td>&nbsp;'.implode('&nbsp;</td><td>&nbsp;', $row).'&nbsp;</td></tr>';
	}
	echo '</table>';
	exit;
}
		?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><script src='../static/js/common.js'></script><script>
	function switchTab(prefix, current, total, activeclass) {
	activeclass = !activeclass ? 'a' : activeclass;
	for(var i = 1; i <= total;i++) {
		if(!document.getElementById(prefix + '_' + i)) {
			continue;
		}
		var classname = ' '+document.getElementById(prefix + '_' + i).className+' ';
		document.getElementById(prefix + '_' + i).className = classname.replace(' '+activeclass+' ','').substr(1);
		document.getElementById(prefix + '_c_' + i).style.display = 'none';
	}
	document.getElementById(prefix + '_' + current).className = document.getElementById(prefix + '_' + current).className + ' '+activeclass;
	document.getElementById(prefix + '_c_' + current).style.display = '';
	parent.document.getElementById('_debug_iframe').height = (Math.max(document.documentElement.clientHeight, document.body.offsetHeight) + 100) + 'px';
	}
	</script>
		<style>#__debugbarwrap__ { line-height:10px; text-align:left;font:12px Monaco,Consolas,"Lucida Console","Courier New",serif;}
		body { font-size:12px; }
		a, a:hover { color: black;text-decoration:none; }
		s { text-decoration:none;color: red; }
		img { vertical-align:middle; }
		.w td em { margin-left:10px;font-style: normal; }
		#__debugbar__ { padding: 80px 1px 0 1px;  }
		#__debugbar__ table { width:90%;border:1px solid gray; }
		#__debugbar__ div { padding-top: 40px; }
		#__debugbar_s { border-bottom:1px dotted #EFEFEF;background:#FFF;width:100%;font-size:12px;position: fixed; top:0px; left:5px; }
		#__debugbar_s a { color:blue; }
		#__debugbar_s a.a { border-bottom: 1px dotted gray; }
		#__debug_c_1 ol { margin-left: 20px; padding: 0px; }
		#__debug_c_4_nav { background:#FFF; border:1px solid black; border-top:none; padding:5px; position: fixed; top:0px; right:0px }
		</style></head><body><div id="__debugbarwrap__"><div id="__debugbar_s">
			<table class="w" width=99%><tr><td valign=top width=50%><b style="float:left;width:1em;height:4em">文件</b><em>版本:</em> LCLPHP! 3.0 20170214<br /><em>ModID:</em> <s>admin</s><br /><em>包含:</em> <a id="__debug_3" href="#debugbar" onclick="switchTab('__debug', 3, 10)">[文件列表]</a> <s>31</s><br /><td valign=top><b style="float:left;width:1em;height:5em">服务器</b><em>环境:</em> WINNT, Microsoft-IIS/8.0 MySQL/6.0.2-alpha-community-nt-debug<br /><em>内存:</em> <s>2,052,920</s> bytes, 峰值 <s>2,199,232</s> bytes<br /><em>SQL:</em> <a id="__debug_1" href="#debugbar" onclick="switchTab('__debug', 1, 10)">[SQL列表]</a><a id="__debug_4" href="#debugbar" onclick="switchTab('__debug', 4, 10);sqldebug_ajax.location.href = sqldebug_ajax.location.href;">[AjaxSQL列表]</a> <s>10(lcl_table: 9)</s><br /><em>内存缓存:</em> <tr><td valign=top colspan="2"><b>客户端</b> <a id="__debug_2" href="#debugbar" onclick="switchTab('__debug', 2, 10)">[详情]</a> <span id="__debug_b"></span><tr><td colspan=2><a name="debugbar">&nbsp;</a><a href="javascript:;" onclick="parent.scrollTo(0,0)" style="float:right">[TOP]&nbsp;&nbsp;&nbsp;</a><img src="../template/admincp/res/img/arw_r.gif" /><a id="__debug_5" href="#debugbar" onclick="switchTab('__debug', 5, 10)">$_COOKIE</a><img src="../template/admincp/res/img/arw_r.gif" /><a id="__debug_6" href="#debugbar" onclick="switchTab('__debug', 6, 6)">$_G</a><img src="../template/admincp/res/img/arw_r.gif" /><a href="_debug.php?k=346ff32eaa3c09983fb2ec057816d352&I" target="_blank">phpinfo()</a><img src="../template/admincp/res/img/arw_r.gif" /><a href="_debug.php?k=346ff32eaa3c09983fb2ec057816d352&P" target="_blank">MySQL 进程列表</a><img src="../template/admincp/res/img/arw_r.gif" /><a href="_debug.php?k=346ff32eaa3c09983fb2ec057816d352&C" target="_blank">查看缓存</a><img src="../template/admincp/res/img/arw_r.gif" /><a href="../misc.php?mod=initsys&formhash=ca2c73ed" target="_debug_initframe" onclick="parent.document.getElementById('_debug_initframe').onload = function () {parent.location.href=parent.location.href;}">更新缓存</a><img src="../template/admincp/res/img/arw_r.gif" /><a href="../install/update.php" target="_blank">执行 update.php</a></table></div><div id="__debugbar__" style="clear:both"><div id="__debug_c_1" style="display:none"><b>Queries: </b> 10<ol><li><span style="cursor:pointer" onclick="document.getElementById('sql_1').style.display = document.getElementById('sql_1').style.display == '' ? 'none' : ''">0.000810s &bull; DBLink 1 &bull; class/table/table_common_setting.php<br />SELECT * FROM <font color=blue>lcl_common_setting</font></span><br /></li><div id="sql_1" style="display:none;padding:0"><table style="border-bottom:none"><tr style="border-bottom:1px dotted gray"><td>&nbsp;id&nbsp;</td><td>&nbsp;select_type&nbsp;</td><td>&nbsp;table&nbsp;</td><td>&nbsp;type&nbsp;</td><td>&nbsp;possible_keys&nbsp;</td><td>&nbsp;key&nbsp;</td><td>&nbsp;key_len&nbsp;</td><td>&nbsp;ref&nbsp;</td><td>&nbsp;rows&nbsp;</td><td>&nbsp;Extra&nbsp;</td></tr><tr><td>&nbsp;1&nbsp;</td><td>&nbsp;SIMPLE&nbsp;</td><td>&nbsp;lcl_common_setting&nbsp;</td><td>&nbsp;ALL&nbsp;</td><td>&nbsp;&nbsp;</td><td>&nbsp;&nbsp;</td><td>&nbsp;&nbsp;</td><td>&nbsp;&nbsp;</td><td>&nbsp;20&nbsp;</td><td>&nbsp;&nbsp;</td></tr></table><table><tr style="border-bottom:1px dotted gray"><td width="400">File</td><td width="80">Line</td><td>Function</td></tr><tr><td>class/lcl/lcl_database.php</td><td>150</td><td>db_driver_mysql->query()</td></tr><tr><td>class/table/table_common_setting.php</td><td>25</td><td>lcl_database::query()</td></tr><tr><td>class/lcl/lcl_application.php</td><td>237</td><td>table_common_setting->fetch_all()</td></tr><tr><td>class/lcl/lcl_application.php</td><td>58</td><td>lcl_application->_init_setting()</td></tr><tr><td>admin.php</td><td>13</td><td>lcl_application->init()</td></tr></table></div><br /><li><span style="cursor:pointer" onclick="document.getElementById('sql_2').style.display = document.getElementById('sql_2').style.display == '' ? 'none' : ''">0.000873s &bull; DBLink 1 &bull; class/table/table_admincp_member.php<br />select * from <font color=blue>lcl_admincp_member</font> where uid=1</span><br /></li><div id="sql_2" style="display:none;padding:0"><table><tr style="border-bottom:1px dotted gray"><td width="400">File</td><td width="80">Line</td><td>Function</td></tr><tr><td>class/lcl/lcl_database.php</td><td>150</td><td>db_driver_mysql->query()</td></tr><tr><td>class/lcl/lcl_database.php</td><td>95</td><td>lcl_database::query()</td></tr><tr><td>class/table/table_admincp_member.php</td><td>49</td><td>lcl_database::fetch_first()</td></tr><tr><td>class/lcl/lcl_application.php</td><td>246</td><td>table_admincp_member->fetch_by_id()</td></tr><tr><td>class/lcl/lcl_application.php</td><td>59</td><td>lcl_application->_init_user()</td></tr><tr><td>admin.php</td><td>13</td><td>lcl_application->init()</td></tr></table></div><br /><li><span style="cursor:pointer" onclick="document.getElementById('sql_3').style.display = document.getElementById('sql_3').style.display == '' ? 'none' : ''">0.000937s &bull; DBLink 1 &bull; class/table/table_common_cron.php<br />SELECT * FROM <font color=blue>lcl_common_cron</font> WHERE available&gt;'0' AND nextrun&lt;='1496906271' ORDER BY nextrun LIMIT 1</span><br /></li><div id="sql_3" style="display:none;padding:0"><table style="border-bottom:none"><tr style="border-bottom:1px dotted gray"><td>&nbsp;id&nbsp;</td><td>&nbsp;select_type&nbsp;</td><td>&nbsp;table&nbsp;</td><td>&nbsp;type&nbsp;</td><td>&nbsp;possible_keys&nbsp;</td><td>&nbsp;key&nbsp;</td><td>&nbsp;key_len&nbsp;</td><td>&nbsp;ref&nbsp;</td><td>&nbsp;rows&nbsp;</td><td>&nbsp;Extra&nbsp;</td></tr><tr><td>&nbsp;1&nbsp;</td><td>&nbsp;SIMPLE&nbsp;</td><td>&nbsp;&nbsp;</td><td>&nbsp;&nbsp;</td><td>&nbsp;&nbsp;</td><td>&nbsp;&nbsp;</td><td>&nbsp;&nbsp;</td><td>&nbsp;&nbsp;</td><td>&nbsp;&nbsp;</td><td>&nbsp;Impossible WHERE noticed after reading const tables&nbsp;</td></tr></table><table><tr style="border-bottom:1px dotted gray"><td width="400">File</td><td width="80">Line</td><td>Function</td></tr><tr><td>class/lcl/lcl_database.php</td><td>150</td><td>db_driver_mysql->query()</td></tr><tr><td>class/lcl/lcl_database.php</td><td>95</td><td>lcl_database::query()</td></tr><tr><td>class/table/table_common_cron.php</td><td>25</td><td>lcl_database::fetch_first()</td></tr><tr><td>class/lcl/lcl_cron.php</td><td>22</td><td>table_common_cron->fetch_nextrun()</td></tr><tr><td>class/lcl/lcl_application.php</td><td>330</td><td>lcl_cron::run()</td></tr><tr><td>class/lcl/lcl_application.php</td><td>61</td><td>lcl_application->_init_cron()</td></tr><tr><td>admin.php</td><td>13</td><td>lcl_application->init()</td></tr></table></div><br /><li><span style="cursor:pointer" onclick="document.getElementById('sql_4').style.display = document.getElementById('sql_4').style.display == '' ? 'none' : ''">0.000631s &bull; DBLink 1 &bull; class/lcl/lcl_table.php<br />SELECT * FROM <font color=blue>lcl_common_process</font> WHERE `processid`='LCL_CRON_CHECKER'</span><br /></li><div id="sql_4" style="display:none;padding:0"><table style="border-bottom:none"><tr style="border-bottom:1px dotted gray"><td>&nbsp;id&nbsp;</td><td>&nbsp;select_type&nbsp;</td><td>&nbsp;table&nbsp;</td><td>&nbsp;type&nbsp;</td><td>&nbsp;possible_keys&nbsp;</td><td>&nbsp;key&nbsp;</td><td>&nbsp;key_len&nbsp;</td><td>&nbsp;ref&nbsp;</td><td>&nbsp;rows&nbsp;</td><td>&nbsp;Extra&nbsp;</td></tr><tr><td>&nbsp;1&nbsp;</td><td>&nbsp;SIMPLE&nbsp;</td><td>&nbsp;&nbsp;</td><td>&nbsp;&nbsp;</td><td>&nbsp;&nbsp;</td><td>&nbsp;&nbsp;</td><td>&nbsp;&nbsp;</td><td>&nbsp;&nbsp;</td><td>&nbsp;&nbsp;</td><td>&nbsp;Impossible WHERE noticed after reading const tables&nbsp;</td></tr></table><table><tr style="border-bottom:1px dotted gray"><td width="400">File</td><td width="80">Line</td><td>Function</td></tr><tr><td>class/lcl/lcl_database.php</td><td>150</td><td>db_driver_mysql->query()</td></tr><tr><td>class/lcl/lcl_database.php</td><td>95</td><td>lcl_database::query()</td></tr><tr><td>class/lcl/lcl_table.php</td><td>94</td><td>lcl_database::fetch_first()</td></tr><tr><td>class/lcl/lcl_process.php</td><td>87</td><td>lcl_table->fetch()</td></tr><tr><td>class/lcl/lcl_process.php</td><td>61</td><td>lcl_process::_process_cmd_db()</td></tr><tr><td>class/lcl/lcl_process.php</td><td>42</td><td>lcl_process::_cmd()</td></tr><tr><td>class/lcl/lcl_process.php</td><td>17</td><td>lcl_process::_find()</td></tr><tr><td>class/lcl/lcl_cron.php</td><td>30</td><td>lcl_process::islocked()</td></tr><tr><td>class/lcl/lcl_application.php</td><td>330</td><td>lcl_cron::run()</td></tr><tr><td>class/lcl/lcl_application.php</td><td>61</td><td>lcl_application->_init_cron()</td></tr><tr><td>admin.php</td><td>13</td><td>lcl_application->init()</td></tr></table></div><br /><li><span style="cursor:pointer" onclick="document.getElementById('sql_5').style.display = document.getElementById('sql_5').style.display == '' ? 'none' : ''">0.000514s &bull; DBLink 1 &bull; class/lcl/lcl_table.php<br />REPLACE INTO <font color=blue>lcl_common_process</font> SET `processid`='LCL_CRON_CHECKER' , `expiry`='1496906872'</span><br /></li><div id="sql_5" style="display:none;padding:0"><table><tr style="border-bottom:1px dotted gray"><td width="400">File</td><td width="80">Line</td><td>Function</td></tr><tr><td>class/lcl/lcl_database.php</td><td>150</td><td>db_driver_mysql->query()</td></tr><tr><td>class/lcl/lcl_database.php</td><td>64</td><td>lcl_database::query()</td></tr><tr><td>class/lcl/lcl_table.php</td><td>81</td><td>lcl_database::insert()</td></tr><tr><td>class/lcl/lcl_process.php</td><td>84</td><td>lcl_table->insert()</td></tr><tr><td>class/lcl/lcl_process.php</td><td>61</td><td>lcl_process::_process_cmd_db()</td></tr><tr><td>class/lcl/lcl_process.php</td><td>43</td><td>lcl_process::_cmd()</td></tr><tr><td>class/lcl/lcl_process.php</td><td>17</td><td>lcl_process::_find()</td></tr><tr><td>class/lcl/lcl_cron.php</td><td>30</td><td>lcl_process::islocked()</td></tr><tr><td>class/lcl/lcl_application.php</td><td>330</td><td>lcl_cron::run()</td></tr><tr><td>class/lcl/lcl_application.php</td><td>61</td><td>lcl_application->_init_cron()</td></tr><tr><td>admin.php</td><td>13</td><td>lcl_application->init()</td></tr></table></div><br /><li><span style="cursor:pointer" onclick="document.getElementById('sql_6').style.display = document.getElementById('sql_6').style.display == '' ? 'none' : ''">0.000640s &bull; DBLink 1 &bull; class/table/table_common_cron.php<br />SELECT * FROM <font color=blue>lcl_common_cron</font> WHERE available&gt;'0' ORDER BY nextrun LIMIT 1</span><br /></li><div id="sql_6" style="display:none;padding:0"><table style="border-bottom:none"><tr style="border-bottom:1px dotted gray"><td>&nbsp;id&nbsp;</td><td>&nbsp;select_type&nbsp;</td><td>&nbsp;table&nbsp;</td><td>&nbsp;type&nbsp;</td><td>&nbsp;possible_keys&nbsp;</td><td>&nbsp;key&nbsp;</td><td>&nbsp;key_len&nbsp;</td><td>&nbsp;ref&nbsp;</td><td>&nbsp;rows&nbsp;</td><td>&nbsp;Extra&nbsp;</td></tr><tr><td>&nbsp;1&nbsp;</td><td>&nbsp;SIMPLE&nbsp;</td><td>&nbsp;&nbsp;</td><td>&nbsp;&nbsp;</td><td>&nbsp;&nbsp;</td><td>&nbsp;&nbsp;</td><td>&nbsp;&nbsp;</td><td>&nbsp;&nbsp;</td><td>&nbsp;&nbsp;</td><td>&nbsp;Impossible WHERE noticed after reading const tables&nbsp;</td></tr></table><table><tr style="border-bottom:1px dotted gray"><td width="400">File</td><td width="80">Line</td><td>Function</td></tr><tr><td>class/lcl/lcl_database.php</td><td>150</td><td>db_driver_mysql->query()</td></tr><tr><td>class/lcl/lcl_database.php</td><td>95</td><td>lcl_database::query()</td></tr><tr><td>class/table/table_common_cron.php</td><td>29</td><td>lcl_database::fetch_first()</td></tr><tr><td>class/lcl/lcl_cron.php</td><td>63</td><td>table_common_cron->fetch_nextcron()</td></tr><tr><td>class/lcl/lcl_cron.php</td><td>57</td><td>lcl_cron::nextcron()</td></tr><tr><td>class/lcl/lcl_application.php</td><td>330</td><td>lcl_cron::run()</td></tr><tr><td>class/lcl/lcl_application.php</td><td>61</td><td>lcl_application->_init_cron()</td></tr><tr><td>admin.php</td><td>13</td><td>lcl_application->init()</td></tr></table></div><br /><li><span style="cursor:pointer" onclick="document.getElementById('sql_7').style.display = document.getElementById('sql_7').style.display == '' ? 'none' : ''">0.000556s &bull; DBLink 1 &bull; class/table/table_common_syscache.php<br />REPLACE INTO <font color=blue>lcl_common_syscache</font> SET `cname`='cronnextrun' , `ctype`='0' , `dateline`='1496906271' , `data`='1528442271'</span><br /></li><div id="sql_7" style="display:none;padding:0"><table><tr style="border-bottom:1px dotted gray"><td width="400">File</td><td width="80">Line</td><td>Function</td></tr><tr><td>class/lcl/lcl_database.php</td><td>150</td><td>db_driver_mysql->query()</td></tr><tr><td>class/lcl/lcl_database.php</td><td>64</td><td>lcl_database::query()</td></tr><tr><td>class/lcl/lcl_table.php</td><td>81</td><td>lcl_database::insert()</td></tr><tr><td>class/table/table_common_syscache.php</td><td>93</td><td>lcl_table->insert()</td></tr><tr><td>function/function_core.php</td><td>1063</td><td>table_common_syscache->insert()</td></tr><tr><td>class/lcl/lcl_cron.php</td><td>67</td><td>savecache()</td></tr><tr><td>class/lcl/lcl_cron.php</td><td>57</td><td>lcl_cron::nextcron()</td></tr><tr><td>class/lcl/lcl_application.php</td><td>330</td><td>lcl_cron::run()</td></tr><tr><td>class/lcl/lcl_application.php</td><td>61</td><td>lcl_application->_init_cron()</td></tr><tr><td>admin.php</td><td>13</td><td>lcl_application->init()</td></tr></table></div><br /><li><span style="cursor:pointer" onclick="document.getElementById('sql_8').style.display = document.getElementById('sql_8').style.display == '' ? 'none' : ''">0.000427s &bull; DBLink 1 &bull; class/table/table_common_process.php<br />DELETE FROM <font color=blue>lcl_common_process</font> WHERE processid='LCL_CRON_CHECKER' OR expiry&lt;1496906272 </span><br /></li><div id="sql_8" style="display:none;padding:0"><table><tr style="border-bottom:1px dotted gray"><td width="400">File</td><td width="80">Line</td><td>Function</td></tr><tr><td>class/lcl/lcl_database.php</td><td>150</td><td>db_driver_mysql->query()</td></tr><tr><td>class/lcl/lcl_database.php</td><td>52</td><td>lcl_database::query()</td></tr><tr><td>class/table/table_common_process.php</td><td>25</td><td>lcl_database::delete()</td></tr><tr><td>class/lcl/lcl_process.php</td><td>95</td><td>table_common_process->delete_process()</td></tr><tr><td>class/lcl/lcl_process.php</td><td>61</td><td>lcl_process::_process_cmd_db()</td></tr><tr><td>class/lcl/lcl_process.php</td><td>22</td><td>lcl_process::_cmd()</td></tr><tr><td>class/lcl/lcl_cron.php</td><td>58</td><td>lcl_process::unlock()</td></tr><tr><td>class/lcl/lcl_application.php</td><td>330</td><td>lcl_cron::run()</td></tr><tr><td>class/lcl/lcl_application.php</td><td>61</td><td>lcl_application->_init_cron()</td></tr><tr><td>admin.php</td><td>13</td><td>lcl_application->init()</td></tr></table></div><br /><li><span style="cursor:pointer" onclick="document.getElementById('sql_9').style.display = document.getElementById('sql_9').style.display == '' ? 'none' : ''">0.000492s &bull; DBLink 1<br />SELECT VERSION()</span><br /></li><div id="sql_9" style="display:none;padding:0"><table style="border-bottom:none"><tr style="border-bottom:1px dotted gray"><td>&nbsp;id&nbsp;</td><td>&nbsp;select_type&nbsp;</td><td>&nbsp;table&nbsp;</td><td>&nbsp;type&nbsp;</td><td>&nbsp;possible_keys&nbsp;</td><td>&nbsp;key&nbsp;</td><td>&nbsp;key_len&nbsp;</td><td>&nbsp;ref&nbsp;</td><td>&nbsp;rows&nbsp;</td><td>&nbsp;Extra&nbsp;</td></tr><tr><td>&nbsp;1&nbsp;</td><td>&nbsp;SIMPLE&nbsp;</td><td>&nbsp;&nbsp;</td><td>&nbsp;&nbsp;</td><td>&nbsp;&nbsp;</td><td>&nbsp;&nbsp;</td><td>&nbsp;&nbsp;</td><td>&nbsp;&nbsp;</td><td>&nbsp;&nbsp;</td><td>&nbsp;No tables used&nbsp;</td></tr></table><table><tr style="border-bottom:1px dotted gray"><td width="400">File</td><td width="80">Line</td><td>Function</td></tr><tr><td>class/lcl/lcl_database.php</td><td>150</td><td>db_driver_mysql->query()</td></tr><tr><td>class/lcl/lcl_database.php</td><td>132</td><td>lcl_database::query()</td></tr><tr><td>class/helper/helper_dbtool.php</td><td>21</td><td>lcl_database::result_first()</td></tr><tr><td>admincp/admincp_index.php</td><td>22</td><td>helper_dbtool::dbversion()</td></tr><tr><td>admin.php</td><td>49</td><td>require()</td></tr></table></div><br /><li><span style="cursor:pointer" onclick="document.getElementById('sql_10').style.display = document.getElementById('sql_10').style.display == '' ? 'none' : ''">0.437863s &bull; DBLink 1<br />SHOW TABLE STATUS LIKE 'lcl_%'</span><br /></li><div id="sql_10" style="display:none;padding:0"><table><tr style="border-bottom:1px dotted gray"><td width="400">File</td><td width="80">Line</td><td>Function</td></tr><tr><td>class/lcl/lcl_database.php</td><td>150</td><td>db_driver_mysql->query()</td></tr><tr><td>class/helper/helper_dbtool.php</td><td>26</td><td>lcl_database::query()</td></tr><tr><td>admincp/admincp_index.php</td><td>23</td><td>helper_dbtool::dbsize()</td></tr><tr><td>admin.php</td><td>49</td><td>require()</td></tr></table></div><br /></ol></div><div id="__debug_c_4" style="display:none"><iframe id="sqldebug_ajax" name="sqldebug_ajax" src="../data/_debug.php.php?k=346ff32eaa3c09983fb2ec057816d352" frameborder="0" width="100%" height="800"></iframe></div><div id="__debug_c_2" style="display:none"><b>IP: </b>::1<br /><b>User Agent: </b>Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.110 Safari/537.36<br /><b>BROWSER.x: </b><script>for(BROWSERi in BROWSER) {var __s=BROWSERi+':'+BROWSER[BROWSERi]+' ';document.getElementById('__debug_b').innerHTML+=BROWSER[BROWSERi]!==0?__s:'';document.write(__s);}</script></div><div id="__debug_c_3" style="display:none"><ol><li>admin.php</li><li>class/class_core.php</li><li>class/lcl/lcl_application.php</li><li>class/lcl/lcl_base.php</li><li>function/function_core.php</li><li>[配置]config/config_global.php</li><li>class/lcl/lcl_database.php</li><li>class/lcl/lcl_locale.php</li><li>function/function_admincp.php</li><li>function/function_cache.php</li><li>class/db/db_driver_mysql.php</li><li>class/table/table_common_setting.php</li><li>class/lcl/lcl_table.php</li><li>class/table/table_admincp_member.php</li><li>class/lcl/lcl_cron.php</li><li>class/table/table_common_cron.php</li><li>class/lcl/lcl_process.php</li><li>class/lcl/lcl_memory.php</li><li>class/table/table_common_process.php</li><li>class/table/table_common_syscache.php</li><li>locale/cn/lang_admincp.php</li><li>locale/cn/lang_db.php</li><li>class/lcl/lcl_admincp.php</li><li>class/helper/helper_log.php</li><li>admincp/admincp_index.php</li><li>lcl_version.php</li><li>class/helper/helper_dbtool.php</li><li>class/class_template.php</li><li>[模板]data/template/_admincp_admincp_index.tpl.php</li><li>[模板]data/template/_admincp_admincp_common_header.tpl.php</li><li>[模板]data/template/_admincp_admincp_common_footer.tpl.php</li><li>function/function_debug.php</li><ol></div><div id="__debug_c_5" style="display:none"><ol><li><br />['<font color=blue>lcl_2017_2132_adminid</font>'] => 1</li><li><br />['<font color=blue>lcl_2017_2132_adminname</font>'] => admin</li><li><br />['<font color=blue>lcl_2017_2132_lastact</font>'] => 1496906271	admin.php	</li></ol></div><div id="__debug_c_6" style="display:none"><div id="__debug_c_4_nav"><a href="#S_config">Nav:<br />
			<a href="#top">#top</a><br />
			<a href="#S_config">$_G['config']</a><br />
			<a href="#S_setting">$_G['setting']</a><br />
			<a href="#S_member">$_G['member']</a><br />
			<a href="#S_group">$_G['group']</a><br />
			<a href="#S_cookie">$_G['cookie']</a><br />
			<a href="#S_style">$_G['style']</a><br />
			<a href="#S_cache">$_G['cache']</a><br />
			</div><ol><a name="top"></a><li><br />['uid'] => 0</li><li><br />['username'] => admin</li><li><br />['adminid'] => 1</li><li><br />['formhash'] => ca2c73ed</li><li><br />['timestamp'] => 1496906271</li><li><br />['starttime'] => 1496906271.7391</li><li><br />['clientip'] => ::1</li><li><br />['remoteport'] => 5670</li><li><br />['mobile'] => </li><li><br />['PHP_SELF'] => /admin.php</li><li><br />['basescript'] => admin</li><li><br />['basefilename'] => admin.php</li><li><br />['isHTTPS'] => </li><li><br />['siteurl'] => http://localhost:90/</li><li><br />['siteroot'] => /</li><li><br />['siteport'] => :90</li><li><br />['gp_action'] => index</li><li><br />['page'] => 1</li><li><br />['gzipcompress'] => </li><li><br />['charset'] => utf-8</li><li><br />['currenturl_encode'] => aHR0cDovL2xvY2FsaG9zdDo5MC9hZG1pbi5waHA/YWN0aW9uPWluZGV4</li><li><a name="S_config"></a><br />['config'] => Array<br />
(<br />
&nbsp;&nbsp;[db] =&gt; Array<br />
&nbsp;&nbsp;&nbsp;&nbsp;(<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[1] =&gt; Array<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[dbhost] =&gt; localhost<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[dbuser] =&gt; root<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[dbpw] =&gt; 123456<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[dbcharset] =&gt; utf8<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[pconnect] =&gt; 0<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[dbname] =&gt; lclphp<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[tablepre] =&gt; lcl_<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)<br />
<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[slave] =&gt; <br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[common] =&gt; Array<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[slave_except_table] =&gt; <br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)<br />
<br />
&nbsp;&nbsp;&nbsp;&nbsp;)<br />
<br />
&nbsp;&nbsp;[memory] =&gt; Array<br />
&nbsp;&nbsp;&nbsp;&nbsp;(<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[prefix] =&gt; H8ssEA_<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[redis] =&gt; Array<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[server] =&gt; <br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[port] =&gt; 6379<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[pconnect] =&gt; 1<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[timeout] =&gt; 0<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[requirepass] =&gt; <br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[serializer] =&gt; 1<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)<br />
<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[memcache] =&gt; Array<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[server] =&gt; <br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[port] =&gt; 11211<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[pconnect] =&gt; 1<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[timeout] =&gt; 1<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)<br />
<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[apc] =&gt; 1<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[xcache] =&gt; 1<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[eaccelerator] =&gt; 1<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[wincache] =&gt; 1<br />
&nbsp;&nbsp;&nbsp;&nbsp;)<br />
<br />
&nbsp;&nbsp;[cookie] =&gt; Array<br />
&nbsp;&nbsp;&nbsp;&nbsp;(<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[cookiepre] =&gt; lcl_2017_2132_<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[cookiedomain] =&gt; <br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[cookiepath] =&gt; /<br />
&nbsp;&nbsp;&nbsp;&nbsp;)<br />
<br />
&nbsp;&nbsp;[output] =&gt; Array<br />
&nbsp;&nbsp;&nbsp;&nbsp;(<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[charset] =&gt; utf-8<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[forceheader] =&gt; 1<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[gzip] =&gt; 0<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[tplrefresh] =&gt; 1<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[language] =&gt; cn<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[staticurl] =&gt; static/<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[ajaxvalidate] =&gt; 0<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[iecompatible] =&gt; 0<br />
&nbsp;&nbsp;&nbsp;&nbsp;)<br />
<br />
&nbsp;&nbsp;[security] =&gt; Array<br />
&nbsp;&nbsp;&nbsp;&nbsp;(<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[authkey] =&gt; lcl0639<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[urlxssdefend] =&gt; 1<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[attackevasive] =&gt; 0<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[querysafe] =&gt; Array<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[status] =&gt; 1<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[dfunction] =&gt; Array<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[0] =&gt; load_file<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[1] =&gt; hex<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[2] =&gt; substring<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[3] =&gt; if<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[4] =&gt; ord<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[5] =&gt; char<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)<br />
<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[daction] =&gt; Array<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[0] =&gt; @<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[1] =&gt; intooutfile<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[2] =&gt; intodumpfile<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[3] =&gt; unionselect<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[4] =&gt; (select<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[5] =&gt; unionall<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[6] =&gt; uniondistinct<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)<br />
<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[dnote] =&gt; Array<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[0] =&gt; /*<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[1] =&gt; */<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[2] =&gt; #<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[3] =&gt; --<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[4] =&gt; &quot;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)<br />
<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[dlikehex] =&gt; 1<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[afullnote] =&gt; 0<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)<br />
<br />
&nbsp;&nbsp;&nbsp;&nbsp;)<br />
<br />
&nbsp;&nbsp;[template] =&gt; Array<br />
&nbsp;&nbsp;&nbsp;&nbsp;(<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[admincp] =&gt; admincp<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[default] =&gt; default<br />
&nbsp;&nbsp;&nbsp;&nbsp;)<br />
<br />
&nbsp;&nbsp;[input] =&gt; Array<br />
&nbsp;&nbsp;&nbsp;&nbsp;(<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[compatible] =&gt; 1<br />
&nbsp;&nbsp;&nbsp;&nbsp;)<br />
<br />
&nbsp;&nbsp;[cache] =&gt; Array<br />
&nbsp;&nbsp;&nbsp;&nbsp;(<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[type] =&gt; sql<br />
&nbsp;&nbsp;&nbsp;&nbsp;)<br />
<br />
&nbsp;&nbsp;[debug] =&gt; 1<br />
)<br />
</li><li><a name="S_setting"></a><br />['setting'] => Array<br />
(<br />
&nbsp;&nbsp;[bbname] =&gt; LCL for PHP<br />
&nbsp;&nbsp;[sitename] =&gt; LCL<br />
&nbsp;&nbsp;[boardlicensed] =&gt; 0<br />
&nbsp;&nbsp;[bbclosed] =&gt; 0<br />
&nbsp;&nbsp;[siteurl] =&gt; http://localhost<br />
&nbsp;&nbsp;[adminemail] =&gt; minguiluo@163.coom<br />
&nbsp;&nbsp;[site_qq] =&gt; 271391233<br />
&nbsp;&nbsp;[icp] =&gt; <br />
&nbsp;&nbsp;[statcode] =&gt; <br />
&nbsp;&nbsp;[dateformat] =&gt; y-m-d <br />
&nbsp;&nbsp;[timeformat] =&gt; 24<br />
&nbsp;&nbsp;[dateconvert] =&gt; 0<br />
&nbsp;&nbsp;[timeoffset] =&gt; 8<br />
&nbsp;&nbsp;[ec_alipay_account] =&gt; minguiluo@163.com<br />
&nbsp;&nbsp;[ec_alipay_contract] =&gt; 5e70VKmgEfcXSljvq1xlylSTB1f5YcfJ0UULA562FoqHX6KUNG0prTFHJ62/k0rCW117sok2F7k<br />
&nbsp;&nbsp;[ec_tenpay_opentrans_chnid] =&gt; 111111111111<br />
&nbsp;&nbsp;[ec_tenpay_direct] =&gt; <br />
&nbsp;&nbsp;[ec_tenpay_bargainor] =&gt; 3333333333333<br />
&nbsp;&nbsp;[ec_tenpay_key] =&gt; 4444444444444<br />
&nbsp;&nbsp;[ec_tenpay_opentrans_key] =&gt; 222222222222222222<br />
)<br />
</li><li><a name="S_cookie"></a><br />['cookie'] => Array<br />
(<br />
&nbsp;&nbsp;[adminid] =&gt; 1<br />
&nbsp;&nbsp;[adminname] =&gt; admin<br />
&nbsp;&nbsp;[lastact] =&gt; 1496906271	admin.php	<br />
)<br />
</li><li><a name="S_cache"></a><br />['cache'] => Array<br />
(<br />
)<br />
</li><li><a name="S_admins"></a><br />['admins'] => Array<br />
(<br />
&nbsp;&nbsp;[uid] =&gt; 1<br />
&nbsp;&nbsp;[username] =&gt; admin<br />
&nbsp;&nbsp;[password] =&gt; 123456<br />
&nbsp;&nbsp;[cpgroupid] =&gt; 0<br />
&nbsp;&nbsp;[customperm] =&gt; <br />
&nbsp;&nbsp;[dateline] =&gt; 1492583906<br />
)<br />
</li><li><a name="S_member"></a><br />['member'] => Array<br />
(<br />
&nbsp;&nbsp;[timeoffset] =&gt; 8<br />
)<br />
</li><li><a name="S_mobiletpl"></a><br />['mobiletpl'] => Array<br />
(<br />
&nbsp;&nbsp;[1] =&gt; mobile<br />
&nbsp;&nbsp;[2] =&gt; touch<br />
&nbsp;&nbsp;[3] =&gt; wml<br />
&nbsp;&nbsp;[yes] =&gt; touch<br />
)<br />
</li><li><a name="S_timenow"></a><br />['timenow'] => Array<br />
(<br />
&nbsp;&nbsp;[time] =&gt; 17-06-08&nbsp;24<br />
&nbsp;&nbsp;[offset] =&gt; +8<br />
)<br />
</li></ol></div></body></html>