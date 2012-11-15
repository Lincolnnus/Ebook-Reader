var IP="api";
var thumbnailurl="pdfimage/";
var pdfurl="pdf";
var readurl="read.html";
//below are functions for public.html and index.html
function gotoAccount()//Go to Personal Library Page
{
    window.location="account.html";
}
function gotoLibrary()//Go to Personal Libary Page
{
    window.location="index.html";
}
function gotoPublic()//Go to the public library page
{
    window.location="public.html";
}
function uploadUrlShow()//Show upload Url Wrapper
{
    $('uploadurlWrapper').show();
}
function uploadUrlHide()//Hide the upload Url Wrapper
{
    $('uploadurlWrapper').hide();
}
function uploadFileShow()//Show the upload File Wrapper
{
    $('uploadfileWrapper').show();
}
function uploadFileHide()//Hide the upload File Wrapper
{
    $('uploadfileWrapper').hide();
}
function libraryShow()//Show the Login Wrapper
{
    if(checkCookie('uid')!=""){
        $('login').hide();
        loginHide();
        getUploadBooks();
    }
    else {
        $('upload').hide();
        loginShow();
    }
}
function storeShow()
{
    if(checkCookie('uid')!=""){
        $('login').hide();
        loginHide();
    }
    else {
        loginShow();
        $('loginWrapper').hide();
    }
    getStoreBooks();
}
function standardLogin()//Standard Login
{
    setCookie('redirect',window.location.href);
    var email=$('email').value;
    var password=$('password').value;
    var remember=$('remember').checked;
    var jsonRequest = new Request.JSON({
                                       url: 'api/login.php',
                                       onSuccess: function(e){
                                       if(e.response_type=='fail'){
                                       showError(e.response_message);}
                                       else {var redirect=getCookie("redirect");
                                       window.location=redirect;}
                                       }
                                       }).post({'email':email,'password':password,'remember':remember});
}
function loginShow()//Show the Login Wrapper
{
    $('loginWrapper').show();
    $('login').show();
    $('action').hide();
}
function loginHide()//Hide the Login Wrapper
{
    $('loginWrapper').hide();
    $('logout').show();
    $('thumbnail').set('src',getCookie('thumbnail'));
    $('action').hide();
    $('action').addEvent('mouseleave',function(){this.hide();});
}
function fbLogin()//Facebook Login
{
    setCookie("redirect",window.location.href);
    window.location='api/fb.php';
}
function actionShow()//Action After Login
{
    $('action').show();
}
function bookDetailHide()
{
    $('bookdetail').hide();
}
function userLogout()//Login Out
{
    if(checkCookie("bid"))
    {var bid=getCookie("bid");}
    deleteAllCookies();
    if(bid){setCookie("bid",bid)};
    window.location.reload();
}
function saveFileShow()//Show the Save File Wrapper
{
    var file=getParameterByName('file');
    if(file!="")
    {
        $('filename').set('value',file);
        $('cover').set('value',file+'.jpg');
        $('uid').set('value',getCookie('uid'));
        $('savefileWrapper').show();
    }else{$('savefileWrapper').hide();}
}
function saveFileHide()//Hide the Save File Wrapper
{
    $('savefileWrapper').hide();
}
function errorShow()//Show the Error Wrapper
{
    var error=getParameterByName("error");
    if (error=="login")
    {loginShow();}
    else if(error!="")
    {
        $('errorWrapper').show();
        $('errormsg').set('html',error);
    }else{$('errorWrapper').hide();}
}
function errorHide()//Hide the Error Wrapper
{
    $('errorWrapper').hide();
}

function getUploadBooks()//Get Upload Books
{
    var uid=getCookie('uid');
    var token=getCookie('token');
    var jsonRequest = new Request.JSON({url: 'api/library.php', onSuccess: function(upload){
                                       for(var i=0;i<upload.length;i++)
                                       {
                                       var book=upload[i];
                                       // Creating an new anchor with an Object
                                       var wid=window.innerWidth;
                                       var hei=window.innerHeight;
                                       var newbook = new Element('li', {
                                                                 id:i,
                                                                 'class': 'bookmeta',
                                                                 html:'',
                                                                 styles: {
                                                                 'background-image':'url('+thumbnailurl+book.cover_url+')',
                                                                 'background-size':wid/6+'px',
                                                                 'background-repeat':'no-repeat',
                                                                 'width':wid/5,
                                                                 'height':hei/3,
                                                                 'display': 'block',
                                                                 'left':(i%4)*wid/4+wid/30,
                                                                 'top':Math.floor(i/4)*hei/2+hei/30,
                                                                 },
                                                                 events: {
                                                                 click: function(){
                                                                 setCookie("bid",upload[this.id].bid);
                                                                 window.location=readurl;
                                                                 },
                                                                 mouseover: function(e){
                                                                 e.stop(e);
                                                                 $('booktitle').set('html','Title:'+upload[this.id].title);
                                                                 $('bookauthor').set('html','Author:'+upload[this.id].author);
                                                                 $('bookdetail').show();
                                                                 $('bookdetail').setStyles({
                                                                                           'width':this.style.width,
                                                                                           'height':this.style.height,
                                                                                           'left':e.event.clientX,
                                                                                           'top':e.event.clientY
                                                                                           });
                                                                 },
                                                                 mouseleave:function()
                                                                 {
                                                                 bookDetailHide();
                                                                 }
                                                                 }
                                                                 }).inject($("books"));
                                       }
                                       }}).get({'uid': uid, 'token': token});
}
function getStoreBooks()
{
    var jsonRequest = new Request.JSON({url: 'api/store.php', onSuccess: function(upload){
                                       for(var i=0;i<upload.length;i++)
                                       {
                                       var book=upload[i];
                                       // Creating an new anchor with an Object
                                       var wid=window.innerWidth;
                                       var hei=window.innerHeight;
                                       var newbook = new Element('li', {
                                                                 id:i,
                                                                 'class': 'bookmeta',
                                                                 html:'',
                                                                 styles: {
                                                                 'background-image':'url('+thumbnailurl+book.cover_url+')',
                                                                 'background-size':wid/6+'px',
                                                                 'background-repeat':'no-repeat',
                                                                 'width':wid/5,
                                                                 'height':hei/3,
                                                                 'display': 'block',
                                                                 'left':(i%4)*wid/4+wid/30,
                                                                 'top':Math.floor(i/4)*hei/2+hei/30,
                                                                 },
                                                                 events: {
                                                                 click: function(){
                                                                 setCookie('bid',upload[this.id].bid);
                                                                 window.location=readurl;
                                                                 },
                                                                 mouseover: function(e){
                                                                 e.stop(e);
                                                                 $('booktitle').set('html','Title:'+upload[this.id].title);
                                                                 $('bookauthor').set('html','Author:'+upload[this.id].author);
                                                                 $('bookdetail').show();
                                                                 $('bookdetail').setStyles({
                                                                                           'width':this.style.width,
                                                                                           'height':this.style.height,
                                                                                           'left':e.event.clientX,
                                                                                           'top':e.event.clientY
                                                                                           });
                                                                 },
                                                                 mouseleave:function()
                                                                 {
                                                                 bookDetailHide();
                                                                 }
                                                                 }
                                                                 }).inject($("books"));
                                       }
                                       }}).get();
}
//above are functions for public.html and index.html
//below are functions for account.html
function getMyProfile()
{
    var uid=getCookie('uid');
    var token=getCookie('token');
    var jsonRequest = new Request.JSON({url: 'api/account.php',
                                       onSuccess: function(e){
                                       if(e.response_type=='succeed')
                                       { displayProfile(e.response_message);}
                                       else {showError(e.response_message);}
                                       }
                                       }).post({'uid':uid,'token':token});
    
}
function showError(error)
{
    console.log(error);
    $('errorWrapper').show();
    $('errormsg').set('html',error);
}
function displayProfile(user)
{
    $('mythumbnail').set('html','<img width="50px" src="'+user.thumbnail+'">');
    $('mythumbnail').addEvent('click',function(){editProfile('thumbnail')});
    $('gender').set('html',user.gender);
    $('genderEdit').addEvent('click',function(){editProfile('gender')});
    $('uname').set('html',user.uname);
    $('unameEdit').addEvent('click',function(){editProfile('uname')});
    $('birthday').set('html',user.birthday);
    $('birthdayEdit').addEvent('click',function(){editProfile('birthday')});
    $('city').set('html',user.city);
    $('cityEdit').addEvent('click',function(){editProfile('city')});
    if(user.fbid){$('fbid').set('html','<a href="http://www.facebook.com/'+user.fbid+'">My Page</a> Change');}
    else{$('fbidEdit').set('html','Add');}
    $('fbidEdit').addEvent('click',function(){addFBfriend();});
    $('register_date').set('html',user.register_at);
    $('localeEdit').addEvent('click',function(){editProfile('locale');});
    $('locale').set('html',user.locale);
    $('timezone').set('html',user.timezone);
    $('timezoneEdit').addEvent('click',function(){editProfile('timezone');});
    
}
function addFBfriend()
{
    $('editWrapper').show();
    $('editField').set('html','To connect with your facebook account,Please Make Sure Your Email Address is Consistent with Your Facebook Email Address');
    var editButton=new Element('button',{html:'Confirm'}).inject($('editField'));
    editButton.addEvent('click',function(){
                        window.location="api/fb.php";
                        });
}
function editProfile(element)
{
    
    var uid=getCookie('uid');
    var token=getCookie('token');
    $('editWrapper').show();
    $('editField').set('html','');
    var edit;
    if(element=='timezone'){ edit=new Element('select',{html:'<option value="1">+1 GMT</option><option value="2">+2 GMT</option><option value="3">+3 GMT</option><option value="4">+4 GMT</option><option value="5">+5 GMT</option><option value="6">+6 GMT</option><option value="7">+7 GMT</option><option value="8">+8 GMT</option><option value="9">+9 GMT</option><option value="10">+10 GMT</option><option value="11">+11 GMT</option><option value="12">+12 GMT</option><option value="-1">-1 GMT</option><option value="-2">-2 GMT</option><option value="-3">-3 GMT</option><option value="-1">-1 GMT</option><option value="-2">-2 GMT</option><option value="-3">-3 GMT</option><option value="-1">-1 GMT</option><option value="-4">-4 GMT</option><option value="-5">-5 GMT</option><option value="-6">-6 GMT</option><option value="-7">-7 GMT</option><option value="-8">-8 GMT</option><option value="-9">-9 GMT</option><option value="-10">-10 GMT</option><option value="-11">-11 GMT</option><option value="0">0 GMT</option>'}).inject($('editField'));}
    else if(element=='locale'){edit=new Element('select',{html:'<option value="English">English</option>'}).inject($('editField'));}
    else if(element=='gender'){edit=new Element('input',{'type':'text','placeholder':'male/female','value':$(element).get('html')}).inject($('editField'));}
    else if(element=='birthday'){edit=new Element('input',{'type':'text','placeholder':'1989/11/01','value':$(element).get('html')}).inject($('editField'));}
    else if(element=='thumbnail'){edit=new Element('input',{'type':'text','placeholder':'http://','value':$(element).get('html')}).inject($('editField'));}
    else { edit=new Element('input',{'type':'text','placeholder':$(element).get('html'),'value':$(element).get('html')}).inject($('editField'));}
    var editButton=new Element('button',{html:'Save Change'}).inject($('editField'));
    editButton.addEvent('click',function(){
                        var jsonRequest = new Request.JSON({url: 'api/editProfile.php',
                                                           onSuccess: function(e){$('editWrapper').hide();$(element).set('html',edit.value);if(element=='thumbnail'){$('thumbnail').set('src',edit.value);$('mythumbnail').set('html','<img width="50px" src="'+edit.value+'">');setCookie('thumbnail',edit.value);}}
                                                           }).post({'uid':uid,'token':token,'field':element,'value':edit.value});
                        });
    
}
function editHide()
{
    $('editWrapper').hide();
}
function inforShow()
{
    if(checkCookie('uid')!=""){
        $('login').hide();
        loginHide();
        $('inforWrapper').show();
        getMyProfile();
    }
    else {
        loginShow();
        $('inforWrapper').hide();
    }
}
function showUser()
{
    loginHide();
    $('inforWrapper').show();
    getUserProfile();
}
function getUserProfile()
{
    var uid=getParameterByName('uid');
    var jsonRequest = new Request.JSON({url: 'api/account.php',
                                       onSuccess: function(e){
                                       if(e.response_type=='succeed')
                                       { displayUser(e.response_message);}
                                       else {showError(e.response_message);}
                                       }
                                       }).get({'uid':uid});
    
}
function displayUser(user)
{
    $('mythumbnail').set('html','<img width="50px" src="'+user.thumbnail+'">');
    $('mythumbnail').addEvent('click',function(){editProfile('thumbnail')});
    $('gender').set('html',user.gender);
    $('uname').set('html',user.uname);
    $('city').set('html',user.city);
    
}
function addFriend()
{
    if(checkCookie('uid'))
    {
        var fid1=getCookie('uid');
        var fid2=getParameterByName('uid');
        if(fid1==fid2)
        {
            showError('Cannot Add Yourself as Friends');
        }else
        {
            var jsonRequest = new Request.JSON({url: 'api/friend.php',
                                               onSuccess: function(e){
                                               if(e.response_type=='succeed')
                                               { alert('success');}
                                               else {showError(e.response_message);}
                                               }
                                               }).post({'fid1':fid1,'fid2':fid2});
        }
    }
    else
    {
        loginShow();
    }
    
}
var color="blue";
var annots=new Array();
function getSelected()//get selected text
{
    if(window.getSelection) { return window.getSelection().toString (); }
    else if(document.getSelection) { return document.getSelection().toString (); }
    else {
        var selection = document.selection && document.selection.createRange();
        if(selection.text) { return selection.text; }
        return false;
    }
    return false;
}
function getAnnot()//get annotations
{
    var jsonRequest = new Request.JSON({url: 'api/annotation.php', onSuccess: function(e){annots=e;}}).get({'bid': bid});
}
var annotPage=null;
function displayAnnot(currentPage)
{
    var bid=getCookie('bid');
    if(annotPage!=currentPage)
    {
        var jsonRequest = new Request.JSON({url: 'api/annotation.php', onSuccess: function(e){
                                           var thisCanvas=$("annotCanvas"+currentPage);
                                           var canvasWidth=thisCanvas.width;
                                           var canvasHeight=thisCanvas.height;
                                           var ctx=thisCanvas.getContext("2d");
                                           ctx.clearRect(0,0,canvasWidth,canvasHeight);
                                           annots=e;
                                           if(checkCookie('uid')!="")
                                           {
                                           showMine();
                                           var uid=getCookie('uid');
                                           var jsonRequest = new Request.JSON({url: 'api/friend.php', onSuccess: function(e){
                                                                              var friends=e;
                                                                              showFriends(friends);
                                                                              }}).get({'uid':uid});
                                           }
                                           showPublic();
                                           for(var i=0;i<annots.length;i++)
                                           {
                                           var startx=parseInt(annots[i].startx*canvasWidth),starty=parseInt(annots[i].starty*canvasHeight),width=Math.abs(parseInt(annots[i].width*canvasWidth)),height=Math.abs(parseInt(annots[i].height*canvasHeight));
                                           switch(annots[i].type)
                                           {
                                           case 'rect':
                                           ctx.globalAlpha = 0.3;
                                           ctx.fillStyle=annots[i].color;
                                           ctx.fillRect(startx,starty,width,height);
                                           break;
                                           case 'pencil':
                                           var points=annots[i].points;
                                           var poly=String.split(points,';');
                                           var point,px,py;
                                           ctx.beginPath();
                                           for (var k=0;k<poly.length;k++)
                                           {
                                           var p=String.split(poly[k],' ');
                                           for (var j=0;j<p.length;j++)
                                           {
                                           point=String.split(p[j],',');
                                           px=point[0]*canvasWidth;
                                           py=point[1]*canvasHeight;
                                           if(j==0) ctx.moveTo(px, py);
                                           else ctx.lineTo(px, py);
                                           ctx.strokeStyle = annots[i].color;
                                           ctx.stroke();
                                           }
                                           }
                                           break;
                                           case 'text':
                                           ctx.globalAlpha = 0.3;
                                           ctx.fillStyle='red';
                                           ctx.fillRect(startx,starty,2,canvasHeight*0.02);
                                           break;
                                           }
                                           }
                                           }}).get({'bid': bid,'pid':currentPage});
        annotPage=currentPage;
    }else {
        var thisCanvas=$("annotCanvas"+currentPage);
        var canvasWidth=thisCanvas.width;
        var canvasHeight=thisCanvas.height;
        var ctx=thisCanvas.getContext("2d");
        ctx.clearRect(0,0,canvasWidth,canvasHeight);
        for(var i=0;i<annots.length;i++)
        {
            var startx=parseInt(annots[i].startx*canvasWidth),starty=parseInt(annots[i].starty*canvasHeight),width=Math.abs(parseInt(annots[i].width*canvasWidth)),height=Math.abs(parseInt(annots[i].height*canvasHeight));
            switch(annots[i].type)
            {
                case 'rect':
                    ctx.globalAlpha = 0.3;
                    ctx.fillStyle=annots[i].color;
                    ctx.fillRect(startx,starty,width,height);
                    break;
                case 'pencil':
                    var points=annots[i].points;
                    var poly=String.split(points,';');
                    var point,px,py;
                    var color=annots[i].color;
                    ctx.beginPath();
                    for (var k=0;k<poly.length;k++)
                    {
                        var p=String.split(poly[k],' ');
                        for (var j=0;j<p.length;j++)
                        {
                            point=String.split(p[j],',');
                            px=point[0]*canvasWidth;
                            py=point[1]*canvasHeight;
                            if(j==0) ctx.moveTo(px, py);
                            else ctx.lineTo(px, py);
                            ctx.strokeStyle = color;
                            ctx.stroke();
                        }
                    }
                    break;
                case 'text':
                    ctx.globalAlpha = 0.3;
                    ctx.fillStyle='red';
                    ctx.fillRect(startx,starty,2,canvasHeight*0.02);
                    break;
            }
        }
    }
}
function shareText(annot,pageY){
    editMode=1;
    $('annotText').set('html',annot.text.substring(0,30)+'...');
    $('delete').hide();
    $('save').set('html','save');
    $('commentList').set('html','');
    $('commentText').set('placeholder','Add Comments?');
    $('commentController').show();
    var top=pageY;
    var currentTop=$('commentController').getStyle('top');
    if (currentTop!=top)
    {
        $('commentController').tween('top', [currentTop, top]);
    }
    $('save').removeEvents('click');
    $('save').addEvent('click', function(){
                       if(checkCookie('uid'))
                       {
                       var uid=getCookie('uid');
                       var bid=getCookie('bid');
                       var token=getCookie('token');
                       var access=$('access').value;
                       var text=$('commentText').value;
                       var aid=annots.length;
                       var cid=0;
                       var pid=PDFView.page;
                       var thisCanvas=$("annotCanvas"+pid);
                       var canvasWidth=thisCanvas.width;
                       var canvasHeight=thisCanvas.height;
                       annot.aid=aid;
                       annot.bid=bid;
                       annot.pid=pid;
                       annot.startx=annot.startx/canvasWidth;
                       annot.starty=annot.starty/canvasHeight;
                       annot.width=annot.width/canvasWidth;
                       annot.height=annot.height/canvasHeight;
                       annot.access=access;
                       annot.uid=uid;
                       annot.status=0;
                       var jsonRequest = new Request.JSON({url: 'api/annotation.php', onSuccess: function(e){
                                                          annots.push(annot);
                                                          var jsonRequest = new Request.JSON({url: 'api/comment.php', onSuccess: function(e){
                                                                                             showThisAnnot(annots.length-1);
                                                                                             showMine();
                                                                                             }}).post({'uid': uid, 'token': token,'bid':bid,'pid':pid,'aid':aid,'cid':cid,'text':text,'operation':'create'});
                                                          }}).post({'uid': uid, 'token': token,'annot':annot,'operation':'create'});
                       }else {loginShow();}
                       editMode=0;
                       });
}

function editAnnot(id)
{
    editMode=1;
    var annot=annots[id];
    var pid=PDFView.page;
    var page=$("page"+pid);
    var canvasHeight=page.height;
    var uid=getCookie('uid');
    var token=getCookie('token');
    var top=document.body.offsetHeight*annot.starty;
    var currentTop=$('commentController').getStyle('top');
    $('commentController').show();
    if (currentTop!=top)
    {
        $('commentController').tween('top', [currentTop, top]);
    }
    $('annotText').set('html',annot['text']);
    $('commentText').set('value','');
    $('commentText').set('placeholder','Edit Text?');
    $('delete').show();
    $('save').set('html',"Save Change");
    $('save').removeEvents('click');
    $('save').addEvent('click', function(){
                       var text=$('commentText').value;
                       var access=$('access').value;
                       annot.text=text;
                       annot.access=access;
                       var jsonRequest = new Request.JSON({url: 'api/annotation.php', onSuccess: function(e){
                                                          annots[id]=annot;
                                                          $('annotText').set('html',annot.text);
                                                          $('commentText').set('value','');
                                                          showMine();
                                                          }}).post({'uid': uid, 'token': token,'annot':annot,'operation':'update'});
                       editMode=0;
                       });
    $('delete').addEvent('click', function(){
                         var jsonRequest = new Request.JSON({url: 'api/annotation.php', onSuccess: function(e){
                                                            commentHide();
                                                            annots[id].status=1;
                                                            showMine();
                                                            }}).post({'uid': uid, 'token': token,'annot':annot,'operation':'delete'});
                         editMode=0;
                         });
}
function showMine()
{
    var uid=getCookie('uid');
    var thumbnail=getCookie('thumbnail');
    var uname=getCookie('uname');
    document.getElements(".myList").destroy();
    $('mine').set('html','<img src="'+thumbnail+'" width="50px" id="myPic" title="'+uname+'">');
    for(var i=0;i<annots.length;i++)
    {
        if((annots[i].uid==uid)&&(annots[i].status==0))
        {
            var list=new Element('li',{id:i,'class':'myList',html:">"+annots[i].text.substring(0,9)}).inject($('mine'));
            list.addEvent('click',function(e){e.stop();editAnnot(this.id);});
            list.set('morph', {duration: 200});
            list.addEvents({
                           mouseenter: function(e){
                           // this refers to the element in an event
                           this.morph({
                                      'background-color': '#666',
                                      'color': '#FF8',
                                      'margin-left': 5
                                      });
                           e.stop();
                           showThisAnnot(this.id);
                           highlight(this.id);
                           },
                           mouseleave: function(){
                           // this refers to the element in an event
                           this.morph({
                                      'background-color': '#333',
                                      'color': '#888',
                                      'margin-left': 0
                                      });
                           }
                           });
        }
        
    }
}
function highlight(id)
{
    var annot=annots[id];
    var thisPage=PDFView.page;
    var page=$("page"+thisPage);
    var canvasWidth=page.width;
    var canvasHeight=page.height;
    var thisCanvas=$("annotCanvas"+thisPage);
    var ctx=thisCanvas.getContext("2d");
    ctx.clearRect(0,0,canvasWidth,canvasHeight);
    var startx=parseInt(annot.startx*canvasWidth),starty=parseInt(annot.starty*canvasHeight),width=Math.abs(parseInt(annot.width*canvasWidth)),height=Math.abs(parseInt(annot.height*canvasHeight));
    switch(annot.type)
    {
        case 'rect':
            ctx.globalAlpha = 0.3;
            ctx.fillStyle=annot.color;
            ctx.fillRect(startx,starty,width,height);
            break;
        case 'pencil':
            var points=annot.points;
            var poly=String.split(points,';');
            var point,px,py;
            ctx.beginPath();
            for (var k=0;k<poly.length;k++)
            {
                var p=String.split(poly[k],' ');
                for (var j=0;j<p.length;j++)
                {
                    point=String.split(p[j],',');
                    px=point[0]*canvasWidth;
                    py=point[1]*canvasHeight;
                    if(j==0) ctx.moveTo(px, py);
                    else ctx.lineTo(px, py);
                    ctx.strokeStyle = annot.color;
                    ctx.stroke();
                }
            }
            break;
        case 'text':
            ctx.globalAlpha = 0.5;
            ctx.fillStyle='red';
            ctx.fillRect(startx,starty,2,canvasHeight*0.02);
            break;
    }
}
function getComments(annot)
{
    var jsonRequest = new Request.JSON({url: 'api/comment.php', onSuccess: function(e){
                                       if(e.response_type=='fail'){showError(e.response_message);}
                                       else if(e.response_type=='succeed'){
                                       var comments=e.response_message;
                                       for(var i=0;i<comments.length;i++)
                                       {
                                       var newComment=new Element('a',{'html':comments[i].text}).inject($('commentList'));
                                       }
                                       return comments.length;
                                       }
                                       }}).get({'uid': annot.uid,'aid':annot.aid,'bid':annot.bid,'pid':annot.pid});
}
function showThisAnnot(id)
{
    $('commentController').show();
    var annot=annots[id];
    var top=document.body.offsetHeight*annots[id].starty;
    var currentTop=$('commentController').getStyle('top');
    $('commentController').tween('top', [currentTop, top]);
    $('annotText').set('html',annot['text']);
    $('commentText').set('value','');
    $('commentText').set('placeholder','Comments?');
    $('commentList').set('html','');
    $('delete').hide();
    $('save').removeEvents('click');
    $('save').set('html','Save');
    var jsonRequest = new Request.JSON({url: 'api/comment.php', onSuccess: function(e){
                                       if(e.response_type=='fail'){showError(e.response_message);}
                                       else if(e.response_type=='succeed'){
                                       var comments=e.response_message;
                                       var cid=comments.length;
                                       $('commentList').set('html','');
                                       for(var i=0;i<comments.length;i++)
                                       {
                                       var list=new Element('li',{'class':'','html':comments[i].text}).inject($('commentList'));
                                       list.set('morph', {duration: 200});
                                       list.addEvents({
                                                      mouseenter: function(){
                                                      // this refers to the element in an event
                                                      this.morph({
                                                                 'background-color': '#666',
                                                                 'color': '#FF8',
                                                                 'margin-left': 5
                                                                 });
                                                      },
                                                      mouseleave: function(){
                                                      // this refers to the element in an event
                                                      this.morph({
                                                                 'background-color': '#333',
                                                                 'color': '#888',
                                                                 'margin-left': 0
                                                                 });
                                                      }
                                                      });
                                       }
                                       $('save').addEvent('click', function(){
                                                          if(checkCookie('uid'))
                                                          {
                                                          var text=$('commentText').value;
                                                          var bid=getCookie('bid');
                                                          var token=getCookie('token');
                                                          var uid=getCookie('uid');
                                                          var aid=annot.aid;
                                                          var pid=annot.pid;
                                                          var jsonRequest = new Request.JSON({url: 'api/comment.php', onSuccess: function(e){
                                                                                             showThisAnnot(id);
                                                                                              }}).post({'uid': uid, 'token': token,'bid':bid,'pid':pid,'aid':aid,'cid':cid,'text':text,'operation':'create'});
                                                          }else {loginShow();}
                                                          editMode=0;
                                                          });
                                       }
                                       }}).get({'uid': annot.uid,'aid':annot.aid,'bid':annot.bid,'pid':annot.pid});
}
function gotoUser(id)
{
    var uid=annots[id].uid;
    window.location='user.html?uid='+uid;
}
function showFriends(friends)
{
    $("friend").set('html','Friends');
    for(var j=0;j<friends.length;j++)
    {
        var newannot=new Element('ul',{'class':'friendList',html:'<img src="'+friends[j].thumbnail+'" title="'+friends[j].uname+'">'}).inject($("friend"));
        for(var i=0;i<annots.length;i++)
        {
            if ((annots[i].uid==friends[j].uid)&&(annots[i].access!='me'))
            {
                var list=new Element('li',{id:i,'class':'myList',html:">"+annots[i].text.substring(0,9)}).inject(newannot);
                list.addEvent('click',function(e){});
                list.set('morph', {duration: 200});
                list.addEvents({
                               mouseenter: function(e){
                               // this refers to the element in an event
                               this.morph({
                                          'background-color': '#666',
                                          'color': '#FF8',
                                          'margin-left': 5
                                          });
                               e.stop();
                               showThisAnnot(this.id);
                               highlight(this.id);
                               },
                               mouseleave: function(){
                               // this refers to the element in an event
                               this.morph({
                                          'background-color': '#333',
                                          'color': '#888',
                                          'margin-left': 0
                                          });
                               },
                               click:function()
                               {
                               gotoUser(this.id);
                               }
                               });
                break;
            }
        }
    }
}
function showPublic()
{
    $('public').set('html','Public');
    document.getElements('.publicList').destroy();
    for(var i=0;i<annots.length;i++)
    {
        if((annots[i].access=='public')&&(annots[i].status==0))
        {
            if(!(checkCookie('uid')&&(annots[i].uid==getCookie('uid'))))
            {
                var list=new Element('li',{id:i,'class':'publicList',html:'>'+annots[i].text.substring(0,5)}).inject($("public"));
                list.set('morph', {duration: 200});
                list.addEvents({
                               mouseenter: function(){
                               // this refers to the element in an event
                               this.morph({
                                          'background-color': '#666',
                                          'color': '#FF8',
                                          'margin-left': 5
                                          });
                               showThisAnnot(this.id);
                               highlight(this.id);
                               },
                               mouseleave: function(){
                               // this refers to the element in an event
                               this.morph({
                                          'background-color': '#333',
                                          'color': '#888',
                                          'margin-left': 0
                                          });
                               },
                               click:function(){
                               gotoUser(this.id);
                               }
                               });
            }
        }
        
    }
}
function readShow()
{
    if(checkCookie('uid')!=""){
        $('login').hide();
        loginHide();
    }
    else {
        loginShow();
        $('loginWrapper').hide();
    }
}
function hideComment()
{
    displayAnnot(PDFView.page);
    editMode=0;
    var currentTop=$('commentController').getStyle('top');
    $('commentController').hide();
    //$('commentController').tween('top', [currentTop,window.innerHeight]);
}
function hideTools()
{
    $("toolController").hide();
    $("footer").addEvent('mouseenter',function(){$("toolController").show();});
    $("footer").addEvent('mouseleave',function(){$("toolController").hide();});
}
function getBook()
{
    var bid = getCookie("bid");
    if(bid==""){window.location="public.html?error=no book selected";}
    else{
        PDFView.initialize();
        var jsonRequest = new Request.JSON({url: 'api/book.php',
                                           onSuccess: function(e){
                                           PDFView.open(e.file, 0);
                                           }}).get({'bid': bid});
    }
}
var commentDrag;
function dragController()
{
    var socialDrag = new Drag('socialController');
    $('socialController').tween('opacity', [0,1]);
    commentDrag = new Drag('commentController');
    $('commentText').addEvent('click', function(){
                              editMode=1;
                              commentDrag.detach();
                              });
    
    var socialSlide = new Fx.Slide('allAnnots', {mode: 'horizontal'});
    $('social').addEvent('mouseenter', function(event){
                         event.stop();
                         socialSlide.toggle();
                         });
    $('socialController').addEvent('click', function(event){
                         event.stop();
                         socialSlide.toggle();
                         });
}
function pickcolor()
{
    var toolController=$("toolController");
    var colorContainer=new Element('div').inject(toolController);
    var blackColor=new Element('img',{'class':'colorButton','title':'black',
                               'styles':{'background-color':'black'},
                               'events':{'click':function(){color='black';colorContainer.destroy();}
                               }}).inject(colorContainer);
    var redColor=new Element('img',{'class':'colorButton','title':'Default',
                             'styles':{'background-color':'red'},
                             'events':{'click':function(){color='red';colorContainer.destroy();}
                             }}).inject(colorContainer);
    var blueColor=new Element('img',{'class':'colorButton','title':'blue',
                              'styles':{'background-color':'blue'},
                              'events':{'click':function(){color='blue';colorContainer.destroy();}
                              }}).inject(colorContainer);
    var greenColor=new Element('img',{'class':'colorButton','title':'lime',
                               'styles':{'background-color':'lime'},
                               'events':{'click':function(){color='lime';colorContainer.destroy();}
                               }}).inject(colorContainer);
    var purpleColor=new Element('img',{'class':'colorButton','title':'purple',
                                'styles':{'background-color':'purple'},
                                'events':{'click':function(){color='purple';colorContainer.destroy();}
                                }}).inject(colorContainer);
    var orangeColor=new Element('img',{'class':'colorButton','title':'orange',
                                'styles':{'background-color':'orange'},
                                'events':{'click':function(){color='orange';colorContainer.destroy();}
                                }}).inject(colorContainer);
    var yellowColor=new Element('img',{'class':'colorButton','title':'yellow',
                                'styles':{'background-color':'yellow'},
                                'events':{ 'click':function(){color='yellow';colorContainer.destroy();}
                                }}).inject(colorContainer);
    var pinkColor=new Element('img',{'class':'colorButton','title':'pink',
                              'styles':{'background-color':'pink'},
                              'events':{'click':function(){color='pink';colorContainer.destroy();}
                              }}).inject(colorContainer);
    var colorButtons=document.getElements(".colorButton");
    for(var i=0;i<colorButtons.length;i++){colorButtons[i].addEvents({'mouseenter':function(){this.addClass('selected')},'mouseleave':function(){this.removeClass('selected')}});}
}
function commentHide()
{$('commentController').hide();}
function saveAnnot(annot,top)
{
    var bid=getCookie('bid');
    var uid=getCookie('uid');
    var token=getCookie('token');
    var currentTop=$('commentController').getStyle('top');
    $('commentList').set('html','');
    editMode=1;
    $('commentController').show();
    if (currentTop!=top)
    {
        $('commentController').tween('top', [currentTop, top]);
    }
    $('save').removeEvents('click');
    $('save').set('html','Save');
    $('save').addEvent('click', function(){
                       if(checkCookie('uid'))
                       {
                       var text=$('commentText').value;
                       var access=$('access').value;
                       var pid=PDFView.page;
                       annot.text=text;
                       annot.bid=bid;
                       annot.pid=pid;
                       annot.access=access;
                       annot.aid=annots.length;
                       annot.uid=uid;
                       annot.status=0;
                       var jsonRequest = new Request.JSON({url: 'api/annotation.php', onSuccess: function(e){
                                                          annots.push(annot);
                                                          editAnnot(annots.length-1);
                                                          showMine();
                                                          }}).post({'uid': uid, 'token': token,'annot':annot,'operation':'create'});
                       }else {loginShow();}
                       editMode=0;
                       });
}
function drawShape(type)
{
    $('commentList').set('value','');
    $('delete').hide();
    $('annotText').set('html','');
    $('commentText').set('placeholder','Add notes?');
    var thisPage=PDFView.page;
    var annotLayer=$("annotLayer"+thisPage);
    var page=$("page"+thisPage);
    var canvasWidth=page.width;
    var canvasHeight=page.height;
    var thisCanvas=$("annotCanvas"+thisPage);
    var textLayer=$("textLayer"+thisPage);
    var commentController=$('commentController');
    thisCanvas.removeEvents('mouseup');
    thisCanvas.removeEvents('mousedown');
    thisCanvas.removeEvents('mousemove');
    textLayer.hide();
    var ctx=thisCanvas.getContext("2d");
    ctx.clearRect(0,0,canvasWidth,canvasHeight);
    switch (type)
    {
        case 'rect':
            var startx,starty,started=false;
            thisCanvas.addEvent('mousedown', function(e){
                                startx=e.event.layerX;
                                starty=e.event.layerY;
                                started=true;
                                ctx.beginPath();
                                ctx.globalAlpha = 0.3;
                                });
            thisCanvas.addEvent('mousemove', function(e){
                                if(started)
                                {
                                ctx.clearRect(0,0,canvasWidth,canvasHeight);
                                ctx.rect(startx,starty,e.event.layerX-startx,e.event.layerY-starty);
                                ctx.fillStyle = color;
                                ctx.fill();
                                }
                                });
            thisCanvas.addEvent('mouseup', function(e){
                                started=false;
                                textLayer.show();
                                var newAnnot= {startx:startx/canvasWidth,starty:starty/canvasHeight,width:(e.event.layerX-startx)/canvasWidth,height:(e.event.layerY-starty)/canvasHeight,type:'rect',color:color};
                                var top=e.event.pageY;
                                saveAnnot(newAnnot,top);
                                });
            break;
        case 'pencil':
            //Draw Pencil
            var started=false;
            
            var pencil=[];//The Pencil Object
            var newpoly=[];//Every Stroke is treated as a Continous Polyline
            thisCanvas.addEvent('mousedown',function(e){
                                newpoly=[];//Clear the Stroke
                                started=true;
                                newpoly.push( {"x":e.event.layerX,"y":e.event.layerY});//The percentage will be saved
                                ctx.globalAlpha = 1;
                                ctx.beginPath();
                                ctx.moveTo(e.event.layerX, e.event.layerY);
                                ctx.strokeStyle = color;
                                ctx.stroke();
                                });
            thisCanvas.addEvent('mousemove',function(e){
                                if(started)
                                {
                                newpoly.push( {"x":e.event.layerX,"y":e.event.layerY});
                                ctx.lineTo(e.event.layerX,e.event.layerY);
                                ctx.stroke();
                                }
                                });
            thisCanvas.addEvent('mouseup',function(e){
                                started=false;
                                pencil.push(newpoly);//Push the Stroke to the Pencil Object
                                newpoly=[];//Clear the Stroke
                                var x,y,w,h;
                                x=pencil[0][0].x;
                                y=pencil[0][0].y;
                                var maxdistance=0;//The Most Remote Point to Determine the Markup Size
                                var points="";
                                for (var i=0;i<pencil.length;i++)
                                {
                                newpoly=pencil[i];
                                for(j=0;j<newpoly.length;j++)
                                {
                                points+=newpoly[j].x/canvasWidth+','+newpoly[j].y/canvasHeight+' ';
                                if ((newpoly[j].x+newpoly[j].y)>maxdistance)
                                {
                                maxdistance=newpoly[j].x+newpoly[j].y;
                                w=Math.abs(newpoly[j].x-x)/canvasWidth;
                                h=Math.abs(newpoly[j].y-y)/canvasHeight;
                                }
                                }
                                points=points.slice(0, -1)
                                points+=';';
                                }
                                var newAnnot= {startx:x/canvasWidth,starty:y/canvasHeight,width:(e.event.layerX-x)/canvasWidth,height:(e.event.layerY-y)/canvasHeight,type:'pencil',points:points,color:color};
                                var top=e.event.pageY;
                                saveAnnot(newAnnot,top);
                                });
            break;
    }
}
//above are functions for read.html
function getParameterByName(name)
{
    name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
                          var regexS = "[\\?&]" + name + "=([^&#]*)";
                          var regex = new RegExp(regexS);
                          var results = regex.exec(window.location.search);
                          if(results == null)return "";
                          else return decodeURIComponent(results[1].replace(/\+/g, " "));
                          }

