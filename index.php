<?php include('controller.php');?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
   <meta name="description" content="Extracting HTML Content With XPath">
   <title>Extracting HTML Content with XPath</title>
   <link rel="stylesheet" href="http://yui.yahooapis.com/2.7.0/build/reset-fonts-grids/reset-fonts-grids.css" type="text/css">
   <link rel="stylesheet" href="http://yui.yahooapis.com/2.7.0/build/base/base.css" type="text/css">  
   <link  href="http://fonts.googleapis.com/css?family=Droid+Serif:regular,italic,bold,bolditalic&subset=latin" rel="stylesheet" type="text/css" >
   <style type="text/css">
     html,body{background:#999;color:#000;font-family: 'Droid Serif', serif;font-size: 14px}
     #doc{background:#fff;border:1em solid #fff;-moz-border-radius:5px;}
     #hd {background: url(http://www.dilemaveche.ro/sites/all/themes/dilema/theme/dilema_two/layouter/dilema_two_homepage/logo.png) no-repeat left; height: 120px;}
     #hd h1{padding-top: 100px;color: #FF8800}
     #bd {margin-top: 30px}
     #bd .node {margin-top: 40px}
     p#intro{margin-top: 20px;margin-bottom: 20px}
     a.more{-moz-border-radius:5px 5px 5px 5px;-moz-box-shadow:-1px 1px 5px rgba(33, 33, 33, 0.6);background:none repeat scroll 0 0 #FFCC66;border:medium none;color:#000000;font-weight:bold;padding:0.2em 1em;text-decoration:none;float: right}
     a.more:hover{ background:#ff9;  -moz-box-shadow:0px 0px 2px rgba(33,33,33,.6);}
     .loading{opacity: 0.4;filter: alpha(opacity=40);}
     a.back{-moz-border-radius:5px 5px 5px 5px;-moz-box-shadow:-1px 1px 5px rgba(33, 33, 33, 0.6);background:none repeat scroll 0 0 #FFCC66;border:medium none;color:#000000;font-weight:bold;padding:0.2em 1em;text-decoration:none;float: left;margin-bottom: 40px}
     a.back:hover{ background:#ff9;  -moz-box-shadow:0px 0px 2px rgba(33,33,33,.6);}
     #ft{font-size:80%;color:#888;text-align:left;margin:2em 0;font-size: 12px}
     #ft p a{color:#93C37D;}
     div.node {clear: both}
   </style>
</head>
<body>
<div id="doc" class="yui-t7">
   <div id="hd" role="banner"><h1>Extracting HTML Content with XPath</h1></div>
        <p id="intro">A key feature of YQL is the abilityto access data from structured data feeds such as RSS and ATOM. However, if no such feed is available, you can specify the source as HTML and use XPath to extract the relevant portions of the HTML page.</p>  
   <div id="bd" role="main">
        <div class="yui-g" id="results">
           <?php echo$results; ?>
	</div>
	</div>
   <div id="ft" role="contentinfo"><p>Created By @<a href="http://twitter.com/thinkphp">thinkphp</a> using <a href="http://developer.yahoo.com/yql/guide/yql-select-xpath.html">XPath</a></p></div>
</div>
<script type="text/javascript" src="ajax.js"></script>
<script type="text/javascript">

var DOMhelp = {
    addEvent: function(elem,evType,fn,useCapture) {
              if(window.addEventListener) {
                   return elem.addEventListener(evType,fn,useCapture);
              } else if(window.attachEvent) {
                var r = elem.attachEvent('on'+evType,fn); 
                return r;
              } else {
                 elem['on'+evType] = fn;
              }
    }, 

    getTarget: function(e) {
         var target = window.event ? window.event.srcElement : e ? e.target : false;
         while(target.nodeType != 1 && target.nodeName.toLowerCase() != 'body') {
               target = target.parentNode; 
         }
         if(!target) {return false;}
       return target;
    },
    cancelClick: function(e) {

           if(window.event) {
              window.event.returnValue = false;
              window.event.cancelBubble = true; 
           }

           if(e && e.preventDefault && e.stopPropagation) {
              e.stopPropagation();
              e.preventDefault();
           }
    },

    addClass: function(elem,c) {

           if(!DOMhelp.hasClass(elem,c)) {
               elem.className += ' ' + c;  
           }
    },

    hasClass: function(elem,c) {

        return elem.className.match(DOMhelp.reg(c));
    },

    removeClass: function(elem,c) {

        if(DOMhelp.hasClass(elem,c)) {
              elem.className = elem.className.replace(DOMhelp.reg(c),'');
        }
    },

    reg: function(c) {

         return new RegExp('(\\s|^)'+ c +'(\\s|$)');
    }, 
    //get elements by class name
    $$: function(searchClass, node, tag) {
           var arr = [];
           if(node == null) {node = document;}
           if(tag == null) {tag = "*";}
           var elems = $(node).getElementsByTagName(tag);
           var n = elems.length;
           var pattern = new RegExp('(^|\\s)'+searchClass+'(\\s|$)');  
           for(var i=0;i<n;i++) {
               if(pattern.test(elems[i].className)) {
                    arr.push(elems[i]);
               }  
           }
     return arr;
  }

};


//get element node with ID 'bd'
var bd = document.getElementById('bd');

    //use Event Delegation to attach handler to the 
    //events 'click' element "a" with class 'more'
    DOMhelp.addEvent(bd,'click',handleClick,false);

    function handleClick(e) {

         var target = DOMhelp.getTarget(e);

         var c = target.className;

         if((DOMhelp.hasClass(target,'more') || DOMhelp.hasClass(target,'back')) && (target.nodeName.toLowerCase() == 'a')) {

            var uri = target.getAttribute('href') + '&sid=' + Math.random()*9999 + '&do=Ajax';
  
            DOMhelp.addClass(bd,'loading'); 

            asyncRequest.REQUEST('GET',uri,function(resp){

                 DOMhelp.removeClass(bd,'loading'); 

                 document.getElementById('results').innerHTML = resp;
            }); 

            DOMhelp.cancelClick(e);               

         }//end if
    }  

  
</script>
</body>
</html>