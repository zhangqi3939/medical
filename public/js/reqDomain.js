var reqDomain = 'http://49.233.178.47:8080';
var reqDomain = 'http://114.215.123.101/index.php';
// var reqDomain = '';
var token = window.localStorage.getItem("medicalToken") || '';
$.ajaxSetup({
  headers:{
    'token':token
  },
  complete: function(res1,res2){    
    if(res1.hasOwnProperty('responseJSON')){
      if(res1.responseJSON.code == 200){
        window.localStorage.setItem('count401',0)
      }else if(res1.responseJSON.code == 401){
        if(window.localStorage.getItem('count401') == 0){
          window.localStorage.setItem('count401',1)
          layer.confirm(res1.responseJSON.reason,function(){
            window.location.href = 'index.html'
          });
        }
        setTimeout(function(){
          window.location.href = 'index.html'
        }, 5000);
      }else{
        if(res1.responseJSON.reason){
          layer.msg(res1.responseJSON.reason)
        }
      }
    }else if(res1.hasOwnProperty('responseText')){
      if(JSON.parse(res1.responseText).code == 200){
        window.localStorage.setItem('count401',0)
      }else if(JSON.parse(res1.responseText).code == 401){
        if(window.localStorage.getItem('count401') == 0){
          window.localStorage.setItem('count401',1)
          layer.confirm(JSON.parse(res1.responseText).reason,function(){
            window.location.href = 'index.html'
          });
        }
        setTimeout(function(){
          window.location.href = 'index.html'
        }, 5000);
      }else{
        if(JSON.parse(res1.responseText).reason){
          layer.msg(JSON.parse(res1.responseText).reason)
        }
      }
    }
  }
});