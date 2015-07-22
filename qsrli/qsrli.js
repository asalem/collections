/*
 * this code is to handle the creation of  twitter shortened URL using a customized url shortener .
 * by abdelrahman salem <abedsalem2003@maktoob.com>
 */
	if(!tweet_page_url || tweet_page_url == ''){ var tweet_page_url = document.URL;}
	if(!tweet_page_title || tweet_page_title == ''){ var tweet_page_title = document.title;} 
	if(!tweet_Place_Holder || tweet_Place_Holder == ''){ var tweet_Place_Holder = 'tweet_palce_holder';} 
	if (!tweet_shorten || tweet_shorten == ''){var tweet_shorten =false;}
twitter_tweet = function() { 

	jsnode = function (src) {
		var tag = document.createElement("script");
		tag.type = "text/javascript";
		tag.src = src;
		document.getElementsByTagName("HEAD")[0].appendChild(tag);
	};
	
	updateTweetCount = function () {
		twitter_tweet.jsnode('http://urls.api.twitter.com/1/urls/count.json?url='+encodeURIComponent(tweet_page_url)+'&callback=twitter_tweet.twCounters&rn=' + Math.random());
	};
	
	twCounters = function (result) {
		var tmp = document.getElementById(tweet_Place_Holder);
		if(tmp) {
			var NewValue = Math.floor(result.count);
			
			//assume it returns nothing!
			if(NewValue < 0 ){
				NewValue = 0;
			}
			
			tmp.innerHTML =  NewValue;
			
		}

	};
	
	add_event = function (elm, evType, fn, useCapture){
		if (elm.addEventListener){
			elm.addEventListener(evType, fn, useCapture);
			return true;
		}
		else if (elm.attachEvent) {
			var r = elm.attachEvent('on' + evType, fn);
			return r;
	    }
		else {
			elm['on' + evType] = fn;
		}
	} ;
	
	update_tweet_page_url = function(){
		twitter_tweet.jsnode('http://dev.qsr.li/api.php?token=3c56fbdeb9225c74f2275a7583e2137e&action=shorturl&format=jsonp&url='+encodeURIComponent(tweet_page_url)+'&rn='+Math.random()+'&callback=twitter_tweet.update_shortenUrl');
	
	} ;
	
	update_shortenUrl = function (results){
		
		if (results.shorturl != ''){
			tweet_page_url = results.message.shorturl;
			alert('im heree');
		}
		console.log(tweet_page_url);
		//alert(tweet_page_url);
	} ;

	return{jsnode:jsnode, updateTweetCount:updateTweetCount , twCounters:twCounters,add_event:add_event,update_tweet_page_url:update_tweet_page_url,update_shortenUrl:update_shortenUrl}

}();
if (tweet_shorten){
	twitter_tweet.update_tweet_page_url();
}
twitter_tweet.add_event(window,'load',twitter_tweet.updateTweetCount,false);