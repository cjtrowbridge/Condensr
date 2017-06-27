window.onload = function(){
  if(!(window.jQuery)){
    console.log('Condensr API helper requires JQuery.');
  }
}

function Condensr(txt,num,callback){
  var CondensrPOST = $.post('https://api.condensr.io/v1/',{LongformText: txt,NumberOfSentences: num});
 
  CondensrPOST.done(function(data){
    callback && callback(data);
  });
  CondensrPOST.fail(function(data){
    console.log('Condensr Failed:');
    console.log(data);
  });
}
