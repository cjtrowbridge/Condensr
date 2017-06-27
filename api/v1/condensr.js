window.onload = function() {
    if (window.jQuery) {  
        
    }else{
        console.log('Condensr API requires JQuery.');
    }
}

function Condensr(txt,num,callback){
  var CondensrPOST = $.post('https://api.condensr.io/v1/',{LongformText: txt,NumberOfSentences: num});
 
  CondensrPOST.done(function(data){
    callback && callback(data);
  });
  CondensrPOST.fail(function(data){
    alert('Condensr Failed: '+data);
  });
}
