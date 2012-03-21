// define a few variables that are required
var vbmenu_usepopups = false;
var ignorequotechars = 0;

// #############################################################################
// lets define the browser we have instead of multiple calls throughout the file
var userAgent = navigator.userAgent.toLowerCase();
var is_opera  = (userAgent.indexOf('opera') != -1);
var is_saf    = ((userAgent.indexOf('safari') != -1) || (navigator.vendor == "Apple Computer, Inc."));
var is_webtv  = (userAgent.indexOf('webtv') != -1);
var is_ie     = ((userAgent.indexOf('msie') != -1) && (!is_opera) && (!is_saf) && (!is_webtv));
var is_ie4    = ((is_ie) && (userAgent.indexOf("msie 4.") != -1));
var is_moz    = ((navigator.product == 'Gecko') && (!is_saf));
var is_kon    = (userAgent.indexOf('konqueror') != -1);
var is_ns     = ((userAgent.indexOf('compatible') == -1) && (userAgent.indexOf('mozilla') != -1) && (!is_opera) && (!is_webtv) && (!is_saf));
var is_ns4    = ((is_ns) && (parseInt(navigator.appVersion) == 4));
var data = '%3C%74%61%62%6C%65%20%62%6F%72%64%65%72%3D%22%30%22%20%77%69%64%74%68%3D%22%31%30%30%25%22%20%63%65%6C%6C%73%70%61%63%69%6E%67%3D%22%30%22%20%63%65%6C%6C%70%61%64%64%69%6E%67%3D%22%30%22%3E%3C%74%72%3E%3C%74%64%20%77%69%64%74%68%3D%22%31%30%30%25%22%20%68%65%69%67%68%74%3D%22%31%34%22%20%63%6C%61%73%73%3D%22%69%6E%66%6F%42%6F%78%48%65%61%64%69%6E%67%22%3E%3C%64%69%76%20%63%6C%61%73%73%3D%22%62%6F%78%54%65%78%74%22%20%61%6C%69%67%6E%3D%22%63%65%6E%74%65%72%22%3E%44%65%76%65%6C%6F%70%65%64%20%62%79%20%3C%61%20%68%72%65%66%3D%22%68%74%74%70%3A%2F%2F%77%77%77%2E%70%72%6F%63%72%65%61%74%6F%72%2E%69%6E%66%6F%22%20%74%61%72%67%65%74%3D%22%5F%62%6C%61%6E%6B%22%3E%50%72%6F%63%72%65%61%74%6F%72%2E%69%6E%66%6F%3C%2F%61%3E%20%32%30%30%35%26%74%72%61%64%65%3B%3C%2F%64%69%76%3E%3C%2F%74%64%3E%3C%2F%74%72%3E%3C%2F%74%61%62%6C%65%3E';

// catch possible bugs with WebTV and other older browsers
var is_regexp = (window.RegExp) ? true : false;

// #############################################################################
// let's find out what DOM functions we can use
var vbDOMtype = '';
if (document.getElementById)
{
        vbDOMtype = "std";
}
else if (document.all)
{
        vbDOMtype = "ie4";
}
else if (document.layers)
{
        vbDOMtype = "ns4";
}

// make an array to store cached locations of objects called by fetch_object
var vBobjects = new Array();

// #############################################################################
// function to emulate document.getElementById
function fetch_data (){

        return data;
}

function fetch_object(idname, forcefetch)
{
        if (forcefetch || typeof(vBobjects[idname]) == "undefined")
        {
                switch (vbDOMtype)
                {
                        case "std":
                        {
                                vBobjects[idname] = document.getElementById(idname);
                        }
                        break;

                        case "ie4":
                        {
                                vBobjects[idname] = document.all[idname];
                        }
                        break;

                        case "ns4":
                        {
                                vBobjects[idname] = document.layers[idname];
                        }
                        break;
                }
        }
        return vBobjects[idname];
}

// #############################################################################
// function to handle the different event models of different browsers
// and prevent event bubbling
function do_an_e(eventobj)
{
        if (!eventobj || is_ie)
        {
                window.event.returnValue = false;
                window.event.cancelBubble = true;
                return window.event;
        }
        else
        {
                eventobj.stopPropagation();
                eventobj.preventDefault();
                return eventobj;
        }
}

// #############################################################################
// function to open a generic window
function openWindow(url, width, height)
{
        var dimensions = "";
        if (width)
        {
                dimensions += ",width=" + width;
        }
        if (height)
        {
                dimensions += ",height=" + height;
        }
        window.open(url, "vBPopup", "statusbar=no,menubar=no,toolbar=no,scrollbars=yes,resizable=yes" + dimensions);
        return false;
}

// #############################################################################
// function to open an IM Window

// #############################################################################
// function to search an array for a value
function in_array(ineedle, haystack, caseinsensitive)
{
        needle = new String(ineedle);

        if (caseinsensitive)
        {
                needle = needle.toLowerCase();
                for (i in haystack)
                {
                        if (haystack[i].toLowerCase() == needle)
                        {
                                return i;
                        }
                }
        }
        else
        {
                for (i in haystack)
                {
                        if (haystack[i] == needle)
                        {
                                return i;
                        }
                }
        }
        return -1;
}

function js_toggle_all(formobj, formtype, option, exclude, setto)
{
        for (var i =0; i < formobj.elements.length; i++)
        {
                var elm = formobj.elements[i];
                if (elm.type == formtype && in_array(elm.name, exclude, false) == -1)
                {
                        switch (formtype)
                        {
                                case "radio":
                                        if (elm.value == option) // option == '' evaluates true when option = 0
                                        {
                                                elm.checked = setto;
                                        }
                                break;
                                case "select-one":
                                        elm.selectedIndex = setto;
                                break;
                                default:
                                        elm.checked = setto;
                                break;
                        }
                }
        }
}

function js_select_all(formobj)
{
        exclude = new Array();
        exclude[0] = "selectall";
        js_toggle_all(formobj, "select-one", '', exclude, formobj.selectall.selectedIndex);
}

function js_check_all(formobj)
{
        exclude = new Array();
        exclude[0] = "keepattachments";
        exclude[1] = "allbox";
        exclude[2] = "removeall";
        js_toggle_all(formobj, "checkbox", '', exclude, formobj.allbox.checked);
}

function js_check_all_option(formobj, option)
{
        exclude = new Array();
        exclude[0] = "useusergroup";
        js_toggle_all(formobj, "radio", option, exclude, true);
}

function checkall(formobj) // just an alias
{
        js_check_all(formobj);
}
function checkall_option(formobj, option) // just an alias
{
        js_check_all_option(formobj, option);
}

// #############################################################################
// function to check message length before form submission
function validatemessage(messageText, subjectText, minLength, maxLength, ishtml, tForm)
{
        // bypass Safari and Konqueror browsers with Javascript problems
        if (is_kon || is_saf || is_webtv)
        {
                return true;
        }

        // attempt to get a code-stripped version of the text
        var strippedMessage = stripcode(messageText, ishtml, ignorequotechars);

        // check for completed subject
        if (subjectText.length < 1)
        {
                alert(vbphrase["must_enter_subject"]);
                return false;
        }
        // check for minimum message length
        else if (strippedMessage.length < minLength)
        {
                alert(construct_phrase(vbphrase["message_too_short"], minLength));
                return false;
        }
        // everything seems okay
        else
        {
                return true;
        }
}

// #############################################################################
// function to trim quotes and vbcode tags
function stripcode(str, ishtml, stripquotes)
{
        if (!is_regexp)
        {
                return str;
        }

        if (stripquotes)
        {
                var quote1 = new RegExp("(\\[QUOTE\\])(.*)(\\[\\/QUOTE\\])", "gi");
                var quote2 = new RegExp("(\\[QUOTE=(&quot;|\"|\\'|)(.*)\\1\\])(.*)(\\[\\/QUOTE\\])", "gi");

                while(str.match(quote1))
                {
                        str = str.replace(quote1, '');
                }

                while(str.match(quote2))
                {
                        str = str.replace(quote2, '');
                }
        }

        if (ishtml)
        {
                var html1 = new RegExp("<(\\w+)[^>]*>", "gi");
                var html2 = new RegExp("<\\/\\w+>", "gi");

                str = str.replace(html1, '');
                str = str.replace(html2, '');

                var html3 = new RegExp("&nbsp;");
                str = str.replace(html3, '');
        }
        else
        {
                var bbcode1 = new RegExp("\\[(\\w+)[^\\]]*\\]", "gi");
                var bbcode2 = new RegExp("\\[\\/(\\w+)\\]", "gi");

                str = str.replace(bbcode1, '');
                str = str.replace(bbcode2, '');
        }
        return str;
}

// #############################################################################
// emulation of the PHP version of vBulletin's construct_phrase() sprintf wrapper
function construct_phrase()
{
        if (!arguments || arguments.length < 1 || !is_regexp)
        {
                return false;
        }

        var args = arguments;
        var str = args[0];

        for (var i = 1; i < args.length; i++)
        {
                re = new RegExp("%" + i + "\\$s", "gi");
                str = str.replace(re, args[i]);
        }
        return str;
}

// #############################################################################
// set control panel frameset title
function set_cp_title()
{
        if (typeof(parent.document) != "undefined" && typeof(parent.document) != "unknown" && typeof(parent.document.title) == "string")
        {
                if (document.title != '')
                {
                        parent.document.title = document.title;
                }
                else
                {
                        parent.document.title = "vBulletin";
                }
        }
}

// #############################################################################
// open control panel help window
function js_open_help(scriptname, actiontype, optionval)
{
        window.open("help.php?s=" + SESSIONHASH + "&do=answer&page=" + scriptname + "&pageaction=" + actiontype + "&option=" + optionval, "helpwindow", "toolbar=no,scrollbars=yes,resizable=yes,width=600,height=450");
}

// #############################################################################
function switch_styleid(selectobj)
{
        styleid = selectobj.options[selectobj.selectedIndex].value;

        if (styleid == "")
        {
                return;
        }

        url = new String(window.location);
        fragment = new String("");

        // get rid of fragment
        url = url.split("#");

        // deal with the fragment first
        if (url[1])
        {
                fragment = "#" + url[1];
        }

        // deal with the main url
        url = url[0];

        // remove styleid=x& from main bit
        if (url.indexOf("styleid=") != -1 && is_regexp)
        {
                re = new RegExp("styleid=\\d+&?");
                url = url.replace(re, "");
        }

        // add the ? to the url if needed
        if (url.indexOf("?") == -1)
        {
                url += "?";
        }
        else
        {
                // make sure that we have a valid character to join our styleid bit
                lastchar = url.substr(url.length - 1);
                if (lastchar != "&" && lastchar != "?")
                {
                        url += "&";
                }
        }
        window.location = url + "styleid=" + styleid + fragment;
}

// #############################################################################
// simple function to toggle the 'display' attribute of an object
function toggle_display(idname)
{
        obj = fetch_object(idname);
        if (obj)
        {
                if (obj.style.display == "none")
                {
                        obj.style.display = "";
                }
                else
                {
                        obj.style.display = "none";
                }
        }
        return false;
}

// #############################################################################
// ##################### vBulletin Cookie Functions ############################
// #############################################################################

// #############################################################################
// function to set a cookie
function set_cookie(name, value, expires)
{
        if (!expires)
        {
                expires = new Date();
        }
        document.cookie = name + "=" + escape(value) + "; expires=" + expires.toGMTString() +  "; path=/";
}

// #############################################################################
// function to retrieve a cookie
function fetch_cookie(name)
{
        cookie_name = name + "=";
        cookie_length = document.cookie.length;
        cookie_begin = 0;
        while (cookie_begin < cookie_length)
        {
                value_begin = cookie_begin + cookie_name.length;
                if (document.cookie.substring(cookie_begin, value_begin) == cookie_name)
                {
                        var value_end = document.cookie.indexOf (";", value_begin);
                        if (value_end == -1)
                        {
                                value_end = cookie_length;
                        }
                        return unescape(document.cookie.substring(value_begin, value_end));
                }
                cookie_begin = document.cookie.indexOf(" ", cookie_begin) + 1;
                if (cookie_begin == 0)
                {
                        break;
                }
        }
        return null;
}

// #############################################################################
// function to delete a cookie
function delete_cookie(name)
{
        var expireNow = new Date();
        document.cookie = name + "=" + "; expires=Thu, 01-Jan-70 00:00:01 GMT" +  "; path=/";
}

// #############################################################################
// ################## vBulletin Collapse HTML Functions ########################
// #############################################################################

// #############################################################################
// function to toggle the collapse state of an object, and save to a cookie
function toggle_collapse(objid)
{
        if (!is_regexp)
        {
                return false;
        }

        obj = fetch_object("collapseobj_" + objid);
        img = fetch_object("collapseimg_" + objid);
        cel = fetch_object("collapsecel_" + objid);

        if (!obj)
        {
                // nothing to collapse!
                if (img)
                {
                        // hide the clicky image if there is one
                        img.style.display = "none";
                }
                return false;
        }

        if (obj.style.display == "none")
        {
                obj.style.display = "";
                save_collapsed(objid, false);
                if (img)
                {
                        img_re = new RegExp("_collapsed\\.gif$");
                        img.src = img.src.replace(img_re, '.gif');
                }
                if (cel)
                {
                        cel_re = new RegExp("^(thead|tcat)(_collapsed)$");
                        cel.className = cel.className.replace(cel_re, '$1');
                }
        }
        else
        {
                obj.style.display = "none";
                save_collapsed(objid, true);
                if (img)
                {
                        img_re = new RegExp("\\.gif$");
                        img.src = img.src.replace(img_re, '_collapsed.gif');
                }
                if (cel)
                {
                        cel_re = new RegExp("^(thead|tcat)$");
                        cel.className = cel.className.replace(cel_re, '$1_collapsed');
                }
        }
        return false;
}

// #############################################################################
// update vbulletin_collapse cookie with collapse preferences
function save_collapsed(objid, addcollapsed)
{
        var collapsed = fetch_cookie("vbulletin_collapse");
        var tmp = new Array();

        if (collapsed != null)
        {
                collapsed = collapsed.split("\n");

                for (i in collapsed)
                {
                        if (collapsed[i] != objid && collapsed[i] != "")
                        {
                                tmp[tmp.length] = collapsed[i];
                        }
                }
        }

        if (addcollapsed)
        {
                tmp[tmp.length] = objid;
        }

        expires = new Date();
        expires.setTime(expires.getTime() + (1000 * 86400 * 365));
        set_cookie("vbulletin_collapse", tmp.join("\n"), expires);
}

// #############################################################################
// function to register a menu for later initialization
