jQuery(function($) {

    //DIV IDを取得
    var ary = [];
    $('div[id]').each(function(d, e) {
        var  c = $(this).attr('id');
        if (c.indexOf("_") === 0) {
            ary.push(c);
        }
    });
    var ary2 = ary.filter(function (x, i, self) {
        return self.indexOf(x) === i;
    });
    
    ary2.forEach(function(c) {

        var ff = c.substr(1);
        $.ajax({
            type: "GET",
            url: "https://www.rakuten.ne.jp/gold/[shopname]/tagmng/" + ff + '.js?n=' + (new Date()).getTime(),
            dataType: "jsonp",
            jsonpCallback: ff,
            contentType: "application/json; charset=utf-8",
            success: function(j, status, xhr) {
                $('div[id]').each(function(d, e) {
                    var  ff = $(this).attr('id');
                    if (ff.indexOf("_") == 0 && ff == c) {
                        var k = j[0].code;
                        $(this).html(b(k));
                    }
                });
            }
        });
    });
    
	function b(c) {
		return c.replace(/(&lt;)/g, "<").replace(/(&gt;)/g, ">").replace(
			/(&quot;)/g, '"').replace(/(&#39;)/g, "'").replace(/(&amp;)/g, "&")
	}
});