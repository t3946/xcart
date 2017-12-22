// $Id: func.js,v 1.6.2.4 2006/11/28 09:35:37 max Exp $

var OnLoadDelay = 100;
var OnLoadTimes = 300;

var categories = [];
var rootcats = [];
var timeOuts = [];
var currentCat = false;
var span_content = [];

var catrootmenu = null;
var repos2left = false;

var fancy_body_is_load = false;

/*
	Categories onmouseover event 
*/
function fcShowMenu(obj, cat) {

	if (window.fancy_body_is_load === false)
		return;

	// Init menu item
	fcInitItem(obj, cat);

	// Mark menu items as selected
	fcSelect(obj);

	// Quick hide previous menu item
	if (currentCat) {

		// Previous menu item - parent category
		var fromParent = false;
		if (obj.parentLayer.parentCat) {
			fromParent = (currentCat == obj.parentLayer.parentCat.cat);
		}

		// Previous menu item - child subcategory
		var fromChild = false;
		var c = fcGetCat(currentCat);
		if (c && c.parentLayer && c.parentLayer.parentCat)
			fromChild = (c.parentLayer.parentCat.cat == obj.cat);

		if (currentCat != obj.cat && !fromParent && !fromChild) {
			fcHideMenu(currentCat);
		}
	}

	currentCat = obj.cat;

	if (!obj.child)
		return false;

	// Create child layer
	fcCreateLayer(obj);

	if (!obj.submenu)
		return false;

	// Cancel 'hide' procedure for child menu
	fcCancelHideMenu(obj.submenu);
	if (obj.parentLayer.parentCat)
		fcCancelHideMenu(obj.parentLayer.parentCat.parentLayer);

	if (obj.submenu.object.innerHTML.length == 0)
		obj.submenu.loading();

	// Reposition child menu relatively this menu item
	obj.submenu.reposition();

	// Show child menu
	obj.submenu.show();

	// Change ineritance tree
	obj.parentLayer.submenu = obj.submenu;

	// Preload grandchild menus
	if (fcConfig.preload && fcConfig.download)
		fcPreLoad(obj.submenu);
}

/*
	Initialization hide menu routine with delay
*/
function fcInitHideMenu(obj) {
	if (obj)
		obj.tm = setTimeout('fcHideMenu("'+obj.parentCat.cat+'")', fcConfig.delay);
}

/*
	Hide menu: hide all submenus and hide all unselected parent menus
*/
function fcHideMenu(cat) {

	var obj = fcGetCat(cat);
	if (!obj || !obj.submenu)
		return false;

	// Get most far child layer
	while (obj.submenu && obj.submenu.visible) {
		obj = obj.submenu;
	}

	// Get most early non-selected parent layer
	while (obj.parentCat && !obj.selected && !obj.parentCat.parentLayer.selected && obj.parentCat.parentLayer.parentCat) {
		obj = obj.parentCat.parentLayer;
	}

	/* Hide if parent menu item isn't selected */
	if (obj.parentCat && !obj.parentCat.selected)
		obj.hide();

	/* Hide all child menus */
	while (obj.submenu) {
		obj = obj.submenu;
		obj.hide();
	}
}

/*
	Cancel hide menu routine
*/
function fcCancelHideMenu(obj) {
	if (obj && obj.tm)
		clearTimeout(obj.tm);
}

/*
	Load layer content
*/
function fcLoad() {

	// Load layer content 
	if (fcConfig.download) {
		fcHTTPLoad(this);

	// Initialize layer content
	} else {
		fcInitSubmenu(this);
	}
}

/*
	Preload sublayers content
*/
function fcPreLoad(obj) {
	if (!fcConfig.preload || obj.preloaded)
		return false;

	// Get sucategories of this layer
	var subcats = obj.parentCat ? fcGetCat(obj.parentCat.cat).subcats : rootcats;
	for (var c in subcats) {
		var subobj = fcGetCat(c);
		if (!subobj || !subobj.child) {
			continue;
		}

		// Init sucateogry menu item
		fcInitItem(subobj, c);

		// Create subcategory layer
		fcCreateLayer(subobj);
	}

	obj.preloaded = true;
}

/*
	Load layer content throught HTTP
*/
function fcHTTPLoad(obj) {
	var href = fcBlockHref.replace(/__CAT__/, obj.parentCat.cat);

	// Display 'Loading ...' if menu items is selected
	if (obj.parentCat.selected) {
		obj.loading();
		obj.show();
		obj.reposition();
	}

	// Generate container
	var span = document.body.appendChild(document.createElement("SPAN"));
	span.style.display = 'none';
	span.id = catPrefix+'span'+obj.parentCat.cat;
	span.innerHTML = '4IE<s'+'cript></'+'script>';

	// Start request with delayed onload event
	if (isOpera || isIE || isSafari) {
		setTimeout(new Function('', "var s = document.getElementById('"+span.id+"').getElementsByTagName('script')[0]; s.language = 'JavaScript'; if (s.setAttribute) s.setAttribute('src', '"+href+"'); else s.src = '"+href+"';"), 100);
		setTimeout(new Function('', 'fcHTTPCheckLoaded('+obj.parentCat.cat+', "'+span.id+'");'), 100);

	// Start request with embendded onload event
	} else {
		setTimeout(new Function('', "var s = document.getElementById('"+span.id+"').getElementsByTagName('script')[0]; s.language = 'JavaScript'; s.onload = new Function('', 'fcHTTPLoaded("+obj.parentCat.cat+",\""+span.id+"\");'); if (s.setAttribute) s.setAttribute('src', '"+href+"'); else s.src = '"+href+"';"), 10);
	}
}

/*
	Postprocess layer after data loaded
*/
function fcHTTPLoaded(cat, span) {
	var obj = fcGetCat(cat);
	if (!obj || !obj.submenu)
		return false;

	// Write content to layer
	if (span_content[cat]) {
		if (obj.submenu.loadingTO)
			clearTimeout(obj.submenu.loadingTO);
		obj.submenu.write(span_content[cat]);
		span_content[cat] = null;
	}

	/* Initialize layer and layer's menu items */
	fcInitSubmenu(obj.submenu);

	/* Remove SPAN from node list */
	if (isIE || isOpera) {
		obj = document.getElementById(span);
		if (obj)
			obj.removeNode(true);
	}
}

/*
	Check (with period) SCRIPT tag onload event for IE / Opera / Safari
*/
function fcHTTPCheckLoaded(cat, id) {
	if (span_content[cat]) {
		if (timeOuts[cat])
			timeOuts[cat] = null;
		return fcHTTPLoaded(cat, id);
	}

	// Start first delayed onload event check
	if (!timeOuts[cat]) {
		timeOuts[cat] = new Array(setTimeout(new Function('', 'fcHTTPCheckLoaded('+cat+', "'+id+'");'), OnLoadDelay), 1);

	// Continue delayed onload event check
	} else if (timeOuts[cat][1] < OnLoadTimes) {
		timeOuts[cat][0] = setTimeout(new Function('', 'fcHTTPCheckLoaded('+cat+', "'+id+'");'), OnLoadDelay);
		timeOuts[cat][1]++;
	}
}

/*
	Initialized root menu items
*/
function fcInitRootMenu(cat, subcat) {
	var obj = fcGetCat(cat);
	if (!obj)
		return false;

	obj.submenu = false;
	obj.parentLayer = document.getElementById(catPrefix+'rootmenu');
	obj.parentLayer.parentCat = false;
	obj.tm = false;
	obj.child = subcat;
	obj.subcats = [];
	obj.parentLayer.level = 0;
	rootcats[cat] = subcat;
}

/*
	Reposition sublayers relatively parent category item
*/
function fcReposition() {
	var o = this.parentCat;

	var x = getLeft(o);
	var y = getTop(o);
	var o_width = getWidth(o);
	var obj_width = this.getWidth();
	var xo = fcConfig.x_offset;

	if (isMSIE && (isMac || isMacPPC)) {

		// Hack for MS IE 5.x for Mac OS
		this.moveTo(0, 0);
		x -= this.getAbsoluteLeft()-3;
		y -= this.getAbsoluteTop()-3;

	} else if (isIE55) {

		// Hack for MS IE 5.5 for Windows
		y = getTop(o.parentNode);
	}

	// Define direction on next reposition
	if (repos2left) {
		if (x + xo - obj_width > 1) {
			x -= obj_width;
		} else {
			x += o_width;
			xo = xo * -1;
		}
	} else {
		if (x + xo + o_width + obj_width > getDocumentWidth()-1) {
			x -= obj_width;
			xo = xo * -1;
		} else {
			x += o_width;
		}
	}

	// Move layer
	this.moveTo(x + xo, y + fcConfig.y_offset);

}

/*
	BODY onLoad event
*/
function fcOnLoad() {
	catrootmenu = document.getElementById(catPrefix+'rootmenu');

	// Detect initial direction
	var dw = getDocumentWidth();
	var rootx = getLeft(catrootmenu);
	repos2left = (rootx > dw/2);
	if (repos2left) {
		fcConfig.x_offset = fcConfig.x_offset*-1;
	}

	if (fcConfig.preload && fcConfig.download) {
		fcPreLoad(catrootmenu);
	}
}

/*
	Select menu item
*/
function fcSelect(obj) {
	obj.className = "CatMenuItemOn";
	obj.selected = true;
	if (obj.parentLayer)
		obj.parentLayer.selected = true;
}

/*
	Unselect menu item
*/
function fcUnSelect(obj) {
	obj.className = "CatMenuItemOff";
	obj.selected = false;
//	if (obj.parentLayer)
//		obj.parentLayer.selected = false;
}

/*
	Create submenu layer
*/
function fcCreateLayer(obj) {
	if (obj.submenu)
		return obj.submenu;

	// Create new subcateogry DIV container
	if (fcConfig.download || !document.getElementById(obj.id+'sub')) {
		var d = document.body.insertBefore(document.createElement("DIV"), document.body.childNodes[0]);
		d.id = obj.id+'sub';
		d.className = 'CatSubMenu';
		d.style.position = 'absolute';
		d.style.visibility = 'hidden';

	// Get predefined subcateogry DIV container
	} else {
		var d = document.getElementById(obj.id+'sub');
	}

	// Get DIV as layer
	obj.submenu = layer(d.id);
	d.layer = obj.submenu;

	// Initialize menu item and menu item sublayer
	obj.submenu.parentCat = obj;
	obj.submenu.selected = false;
	obj.submenu.load = fcLoad;
	obj.submenu.reposition = fcReposition;
	obj.submenu.loading = fcLoadingAnim;
	obj.submenu.submenu = false;

	// Set level and zIndex
	obj.submenu.level = 1;
	if (obj.parentLayer.parentCat) {
		if (obj.parentLayer.level != undefined)
			obj.submenu.level = obj.parentLayer.level + 1;
		else if (obj.parentLayer.parentCat.parentLayer.level != undefined)
			obj.submenu.level = obj.parentLayer.parentCat.parentLayer.level + 1;
	}
	obj.submenu.setZIndex(50+obj.submenu.level);
	d.style.zIndex = 50+obj.submenu.level;

	// Define layer events
	d.onmouseover = new Function('', 'this.layer.selected = true; fcCancelHideMenu(this.layer); fcCancelHideMenu(this.layer.parentCat.parentLayer);');
	d.onmouseout = new Function('', 'this.layer.selected = false; fcInitHideMenu(this.layer);');

	// Call load procedure for this sublayer
	obj.submenu.load();

	return obj.submenu;
}

/*
	Get menu item by categoryid
*/
function fcGetCat(id) {
	var obj = document.getElementById(catPrefix+id);
	if (!obj)
		return false;

	return obj;
}

/*
	Initialize menu item
*/
function fcInitItem(obj, cat) {
	if (obj.cat)
		return false;

	// Define events for this manu item
	obj.onmouseout = new Function('',"fcUnSelect(this); fcInitHideMenu(this.submenu);");

	obj.cat = cat;

	return true;
}

/*
	Initialize subcategories DIV's in the layer
*/
function fcInitSubmenu(obj) {
	if (!categories[obj.parentCat.cat])
		return false;

	// Leaf 'categories' array with parent categoryid as key
	obj.parentCat.childs = true;
	for (var c in categories[obj.parentCat.cat]) {
		var subobj = fcGetCat(c);

		// Initialize subcategory menu item
		if (subobj) {
			subobj.parentLayer = obj;
			subobj.child = obj.parentCat.subcats[c] = categories[obj.parentCat.cat][c];
			subobj.subcats = new Array();
		}
	}
	categories[obj.parentCat.cat] = null;
}

/*
	'Loading...' label animation
*/
function fcLoadingAnim(cat) {
	if (cat == undefined && this)
		cat = this.parentCat.cat;

	if (!fcConfig.download)
		return;

	var obj = fcGetCat(cat);
	if (!obj)
		return

	obj = obj.submenu;

	if (!obj.ticks || obj.ticks >= 5)
		obj.ticks = 0;

	obj.ticks++;

	var prefix = '';
	for (var x = 0; x < obj.ticks; x++)
		prefix += '.';

	obj.write('&nbsp;&nbsp;'+lbl_loading_menu+' '+prefix);

	obj.loadingTO = setTimeout("fcLoadingAnim('"+cat+"')", 1000);
}
