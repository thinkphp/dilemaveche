  if(typeof asyncRequest == 'undefined') {

                     asyncRequest = {};
  }

          asyncRequest.REQUEST = function() {

                   function handleReadyState(o,callback) { 

                       o.onreadystatechange = function() {

                           if(o.readyState == 4) {

                                 if(o.status == 200) {

                                      callback(o.responseText);

                                 }
                           }
                       }
                   }
 
                   var XHR = function() {
   
                       var http;

                       try {
 
                             http = new XMLHttpRequest();

                             XHR = function(){return new XMLHttpRequest();}

                           }catch(e) {

                                try {

                                     http = new ActiveXObject("Microsoft.XMLHTTP");

                                     XHR = function(){return new ActiveXObject("Microsoft.XMLHTTP");}
  
                                    }catch(e){} 
                           }

                      return XHR();
                   }

                   return function(method, url, callback, postData) { 

                          var http = XHR();
 
                          http.open(method, url, true);
 
                          if(postData) {

                           //Send the proper header information along with the request
                           http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
 
                           http.setRequestHeader("Content-length", postData.length);

                           http.setRequestHeader("Connection", "close");
                          }

                          handleReadyState(http,callback);
            
                          http.send(postData || null);   

                      return http;                                        
                   }
         }();