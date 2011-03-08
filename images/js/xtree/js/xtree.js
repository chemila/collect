//** Powered by Fason
//** Email: fason_pfx@hotmail.com

var icon={
	root	:'images/tree_ico/root.gif',
	open	:'images/tree_ico/folderopen.gif',
	close	:'images/tree_ico/folder.gif',
	file	:'images/tree_ico/page.gif',
	Rplus	:'images/tree_ico/rplus.gif',
	Rminus	:'images/tree_ico/rminus.gif',
	join	:'images/tree_ico/join.gif',
	joinbottom:'images/tree_ico/joinbottom.gif',
	plus	:'images/tree_ico/tplus.gif',
	plusbottom:'images/tree_ico/plusbottom.gif',
	minus	:'images/tree_ico/tminus.gif',
	minusbottom:'images/tree_ico/minusbottom.gif',
	blank	:'images/tree_ico/blank.gif',
	line	:'images/tree_ico/line.gif'
};
var Global={
	id:0,
	getId:function(){return this.id++;},
	all:[],
	selectedItem:null,
	defaultText:"treeItem",
	defaultAction:"javascript:void(0)",
	defaultTarget:"_self"
}
function preLoadImage(){
	for(i in icon){
		var tem=icon[i];
		icon[i]=new Image()
		icon[i].src=tem
	}
};preLoadImage();

function treeItem(text,action,target,title,Icon)
{
	this.id=Global.getId();
	this.level=0;
	this.text=text?text:Global.defaultText;
	this.action=action?action:Global.defaultAction;
	this.target=target?target:Global.defaultTarget;
	this.title=title?title:this.text;
	this.isLast=true;
	this.childNodes=new Array();
	this.indent=new Array();
	this.parent=null;
	var c =0; 
	if(getCookie("item"+this.id) != null) c = getCookie("item"+this.id);
	this.open=parseInt(c);
	this.load=false;
	this.setuped=false;
	this.JsItem=null;
	this.container=document.createElement("div");
	this.icon=Icon;
	Global.all[Global.all.length]=this;
}

treeItem.prototype.toString = function()
{
	var o = this;
	var oItem = document.createElement("div");
	oItem.id = "treeItem"+this.id
	oItem.className = "treeItem";
	oItem.noWrap = true;
	oItem.onselectstart = function(){ return false;}
	oItem.oncontextmenu = function(){ return false;}
	this.JsItem = oItem;
	this.drawIndents();
	var iIcon = document.createElement("img");
	iIcon.align = "absmiddle";
	iIcon.src = this.childNodes.length>0?(this.open?(this.level>0?(this.isLast?icon.minusbottom.src:icon.minus.src):icon.Rminus.src):(this.level>0?(this.isLast?icon.plusbottom.src:icon.plus.src):icon.Rplus.src)):(this.level>0?(this.isLast?icon.joinbottom.src:icon.join.src):icon.blank.src);
	iIcon.id = "treeItem-icon-handle-" + this.id;
	iIcon.onclick = function(){ o.toggle();};
	oItem.appendChild(iIcon);
	var iIcon = document.createElement("img");
	iIcon.align = "absmiddle";
	iIcon.src = this.icon?this.icon:(this.childNodes.length>0?(this.open?icon.open.src:icon.close.src):icon.file.src);
	iIcon.id = "treeItem-icon-folder-" + this.id;
	iIcon.onclick = function(){ o.select();};
	iIcon.ondblclick = function(){ o.toggle();};
	oItem.appendChild(iIcon);
	var eText = document.createElement("span");
	var eA=document.createElement("a");
	eA.innerHTML = this.text;
	eA.target = this.target;
	eA.href = this.action;
	eA.onkeydown = function(e){ return o.KeyDown(e);}
	if(this.action == Global.defaultAction) eA.onclick = function(){ return false;}
	eText.appendChild(eA);
	eText.id = "treeItem-text-" + this.id;
	eText.className = "treeItem-unselect"
	eText.onclick = function(){ o.select(1);};
	eText.title = this.title;
	oItem.appendChild(eText);
	this.container.id = "treeItem-container-"+this.id;
	this.container.style.display = this.open?"":"none";
	oItem.appendChild(this.container);
	return oItem;
}

treeItem.prototype.root = function()
{
	var p = this;
	while(p.parent)
		p = p.parent;
	return p;
}

treeItem.prototype.setText = function(sText)
{
	if(this.root().setuped)
	{
		var oItem = document.getElementById("treeItem-text-" + this.id);
		oItem.firstChild.innerHTML = sText;
	}
	this.text = sText;
}

treeItem.prototype.setIndent = function(l,v)
{
	for(var i=0;i<this.childNodes.length;i++)
	{
		this.childNodes[i].indent[l] = v;
		this.childNodes[i].setIndent(l,v);
	}
}

treeItem.prototype.drawIndents = function()
{
	var oItem = this.JsItem;
	for(var i=0;i<this.indent.length;i++){
		var iIcon = document.createElement("img");
		iIcon.align = "absmiddle";
		iIcon.id = "treeItem-icon-" + this.id + "-" + i;
		iIcon.src = this.indent[i]?icon.blank.src:icon.line.src;
		oItem.appendChild(iIcon);
	}
}

treeItem.prototype.add = function(oItem)
{
	oItem.parent=this;
	this.childNodes[this.childNodes.length]=oItem;
	oItem.level=this.level+1;
	oItem.indent=this.indent.concat();
	oItem.indent[oItem.indent.length]=this.isLast;
	if(this.childNodes.length>1){
		var o=this.childNodes[this.childNodes.length-2];
		o.isLast=false;
		o.setIndent(o.level,0);
		if(this.root().setuped)o.reload(1);
	}
	else if(this.root().setuped)
		this.reload(0);
	this.container.appendChild(oItem.toString());
	this.container.style.display=this.open?"":"none";
}

treeItem.prototype.loadChildren = function()
{
	//do something
}

treeItem.prototype.remove = function()
{
	var tmp = this.getPreviousSibling();
	//if(tmp){ tmp.select();}
	this.removeChildren();
	var p = this.parent;
	if(!p){ return };
	if(p.childNodes.length>0){
		var o = p.childNodes[p.childNodes.length-1];
		o.isLast = true;
		o.setIndent(o.level,1);
		if(o.root().setuped)o.reload(1);
	}
	else
		p.reload();
}

treeItem.prototype.removeChildren = function ()
{
	if(this == Global.selectedItem){ Global.selectedItem = null;}
	for(var i=this.childNodes.length-1;i>=0;i--)
		this.childNodes[i].removeChildren();
	var o = this;
	var p = this.parent;
	if (p) { p.childNodes = p.childNodes._remove(o);}
	Global.all[this.id] = null
	var oItem = document.getElementById("treeItem"+this.id);
	if (oItem) { oItem.parentNode.removeChild(oItem); }
}

treeItem.prototype.reload = function(flag)
{
	if (flag){
		for(var j=0;j<this.childNodes.length;j++){ this.childNodes[j].reload(1);}
		for(var i=0;i<this.indent.length;i++)
			document.getElementById("treeItem-icon-" +this.id+ "-"+i).src = this.indent[i]?icon.blank.src:icon.line.src;
	}
	document.getElementById("treeItem-icon-handle-" +this.id).src = this.childNodes.length>0?(this.open?(this.level>0?(this.isLast?icon.minusbottom.src:icon.minus.src):icon.Rminus.src):(this.level>0?(this.isLast?icon.plusbottom.src:icon.plus.src):icon.Rplus.src)):(this.level>0?(this.isLast?icon.joinbottom.src:icon.join.src):icon.blank.src);
	if (!this.icon)
		document.getElementById("treeItem-icon-folder-"+this.id).src = this.childNodes.length>0?(this.open?icon.open.src:icon.close.src):icon.file.src;
}

treeItem.prototype.toggle = function()
{
	if(this.childNodes.length>0){
		if(this.open)
			this.collapse();
		else
			this.expand();
	}
}

treeItem.prototype.expand = function()
{
	this.open=1;
	setCookie("item"+this.id,1);
	if(!this.load){
		this.load=true;
		this.loadChildren();
		this.reload(1);
	}
	else 
		this.reload(0);
	this.container.style.display = "";
}

treeItem.prototype.collapse = function()
{
	this.open=0;
	setCookie("item"+this.id,0);
	this.container.style.display = "none";
	this.reload(0);
	this.select(1);
}

treeItem.prototype.expandAll = function()
{
	if(this.childNodes.length>0 && !this.open)this.expand();
	this.expandChildren();
}

treeItem.prototype.collapseAll = function()
{
	this.collapseChildren();
	if(this.childNodes.length>0 && this.open)this.collapse();
}

treeItem.prototype.expandChildren = function()
{
	for(var i=0;i<this.childNodes.length;i++)
	this.childNodes[i].expandAll();
}

treeItem.prototype.collapseChildren = function()
{
	for(var i=0;i<this.childNodes.length;i++)
	this.childNodes[i].collapseAll()
}

treeItem.prototype.openURL=function()
{
	if(this.action!=Global.defaultAction){
		window.open(this.action,this.target);
	}
}

treeItem.prototype.select=function(o)
{
	if (Global.selectedItem) Global.selectedItem.unselect();
	var oItem = document.getElementById("treeItem-text-" + this.id);
	oItem.className = "treeItem-selected";
	oItem.firstChild.focus();
	Global.selectedItem = this;
	if(!o) this.openURL();
}

treeItem.prototype.unselect=function()
{
	var oItem = document.getElementById("treeItem-text-" + this.id);
	oItem.className = "treeItem-unselect";
	oItem.firstChild.blur();
	Global.selectedItem = null;
}

treeItem.prototype.setup = function(oTaget)
{
	oTaget.appendChild(this.toString());
	this.setuped = true;
	if(this.childNodes.length>0 || this.open) this.expand();
}

/**********************************************/
/*
	Arrow Key Event
*/
/**********************************************/

treeItem.prototype.getFirstChild = function()
{
	if(this.childNodes.length>0 && this.open)
		return this.childNodes[0];
	return this;
}

treeItem.prototype.getLastChild = function()
{
	if(this.childNodes.length>0 && this.open)
		return this.childNodes[this.childNodes.length-1].getLastChild();
	return this;
}

treeItem.prototype.getPreviousSibling = function()
{
	if(!this.parent) return null;
	for(var i=0;i<this.parent.childNodes.length;i++)
		if(this.parent.childNodes[i] == this)break;
	if(i == 0) 
		return this.parent;
	else
		return this.parent.childNodes[i-1].getLastChild();
}

treeItem.prototype.getNextSibling = function()
{
	if(!this.parent) return null;
	for(var i=0;i<this.parent.childNodes.length;i++)
		if(this.parent.childNodes[i] == this)break;
	if(i == this.parent.childNodes.length-1)
		return this.parent.getNextSibling();
	else
		return this.parent.childNodes[i+1];
}

treeItem.prototype.KeyDown=function(e){
	var code,o;
	if(!e) e = window.event;
	code = e.which ? e.which : e.keyCode;
	o = this;
	if(code == 37)
	{
		if(o.open) o.collapse();
		else
		{
			if(o.parent) o.parent.select();
		}
		return false;
	}
	else if(code == 38)
	{
		var tmp = o.getPreviousSibling();
		if(tmp) tmp.select();
		return false;
	}
	else if(code == 39)
	{
		if(o.childNodes.length>0)
		{
			if(!o.open) o.expand();
			else
			{
				var tmp = o.getFirstChild();
				if(tmp) tmp.select();
			}
		}
		return false;
	}
	else if(code == 40)
	{
		if(o.open&&o.childNodes.length>0)o.getFirstChild().select();
		else
		{
			var tmp = o.getNextSibling();
			if(tmp) tmp.select();
		}
		return false;
	}
	else if(code == 13)
	{
		o.toggle();
		o.openURL();
		return false;
	}
	return true;
}
/*****************************************************/
Array.prototype.indexOf=function(o){
	for(var i=0;i<this.length;i++)
		if(this[i]==o)return i;
	return -1;
}

Array.prototype.removeAt=function(i){
	return this.slice(0,i).concat(this.slice(i+1))
}

Array.prototype._remove=function(o){
	var i=this.indexOf(o);
	if(i!= -1) return this.removeAt(i)
	return this
}
/*****************************************************/

/*****************************************************/
/*
	xtreeItem Class
*/
/*****************************************************/

function xtreeItem(uid,text,action,target,title,Icon,xml){
	this.uid=uid;
	this.base=treeItem;
	this.base(text,action,target,title,Icon);
	this.Xml=xml;
}
xtreeItem.prototype=new treeItem;

xtreeItem.prototype.parseElement=function(dom){
	return dom.selectSingleNode("/TreeNode");
}

xtreeItem.prototype.addNodesLoop = function(oItem)
{
	for(var i=0;i<oItem.childNodes.length;i++)
	{
		var o = oItem.childNodes[i];
		var tmp = new xtreeItem(o.getAttribute("id"),o.getAttribute("text"),o.getAttribute("href"),o.getAttribute("target"),o.getAttribute("title"),o.getAttribute("icon"),o.getAttribute('Xml'));
		this.add(tmp);
		if(o.getAttribute("Xml")) tmp.add(new treeItem("Loading..."));
		else
		{
			tmp.load=true;
			tmp.addNodesLoop(o);
		}
	}
}

xtreeItem.prototype.loadChildren=function()
{
	var oItem = this;
	var oLoad = oItem.childNodes[0];
	var XmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
	XmlHttp.onreadystatechange=function(){
		if(XmlHttp.readyState==4){
			if(XmlHttp.status==200){
				if(XmlHttp.responseXML.xml == ""){ oLoad.setText("unavaible1");return;}
				var XmlItem=oItem.parseElement(XmlHttp.responseXML.documentElement);
				if(XmlItem.childNodes.length == 0){ oLoad.setText("unavaible") }
				else
				{
					oItem.addNodesLoop(XmlItem);
					for(var i=0;i<oItem.childNodes.length;i++)
					{
						
						if(parseInt(getCookie("item"+oItem.childNodes[i].id)) ==1)
						{ oItem.childNodes[i].expand();}
					}
					if(Global.selectedItem == oItem.childNodes[0])oItem.select();
					oLoad.remove();
				}
			}
			else{
				oLoad.setText("unavaible");
			}
			XmlHttp = null;
			oItem.select(1);
		}
	}
	try{
		XmlHttp.open("get",this.Xml+(/\?/g.test(this.Xml)?"&":"?")+"temp="+Math.random(),true);
		XmlHttp.send();
	}catch(e){ oLoad.setText("unavaible");}
}
xtreeItem.prototype.setup=function(oTarget){
	this.add(new treeItem("Loading..."));
	oTarget.appendChild(this.toString());
	this.setuped=true;
	if(this.childNodes.length>0 || this.open) this.expand();
}
/*****************************************************/
function setCookie(name,value)
{
    var Days = 7; 
    var exp  = new Date();
    exp.setTime(exp.getTime() + Days*24*60*60*1000);
    document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString();
}
function getCookie(name)
{
    var arr = document.cookie.match(new RegExp("(^| )"+name+"=([^;]*)(;|$)"));
    if(arr != null) return unescape(arr[2]); return null;
}
function delCookie(name)
{
    var exp = new Date();
    exp.setTime(exp.getTime() - 1);
    var cval=getCookie(name);
    if(cval!=null) document.cookie= name + "="+cval+";expires="+exp.toGMTString();
}