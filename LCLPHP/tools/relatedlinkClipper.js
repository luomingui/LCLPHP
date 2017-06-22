(function() {
    window._UF_ = {
        save: function() {
            var box = document.getElementById('_UF_AddingBox');
            if (!box) {
                var el = document.createElement('div');
                document.body.appendChild(el);
                el.setAttribute("style", "background: #444; color: #fff; font-size: 14px; line-height: 50px; text-align:center; position: absolute; width: 400px; height:100px; padding:30px; z-index: 99999; overflow: hidden; -moz-border-radius:5px; -webkit-border-radius:5px; -khtml-border-radius:5px; border-radius:5px;");
                el.id = "_UF_AddingBox";
                box = el;
            }

            var d = document.body;
            box.style.left = (d.clientWidth - box.offsetWidth) / 2 + "px";
            box.style.top = "100px";
            window.scrollTo(0, 0);

            box.innerHTML = 'Save the web page to "list of articles"...';

            var url = 'http://localhost:90/ajax.php?action=clipper';

            if (window._UF_HighPriority) {
                url += "&highPriority=1";
            }

            //  url = url + '&url=' + encodeURIComponent(document.location.href);

            url = url + '&url=' + document.location.href;

            this.jsonp(url);
        },
        jsonp: function(url, op) {
            var j = document.createElement('script');
            j.setAttribute('type', 'text/javascript');
            if (op) {
                var ar = [];
                for (var it in op)
                    ar.push(it + '=' + op[it]);
                if (url.indexOf('?') > 0)
                    url += '&' + ar.join('&');
                else
                    url += '?' + ar.join('&');
            }
            j.setAttribute('src', url + '#' + new Date().getTime());
            document.body.appendChild(j);
            if (this._jp)
                this._jp.parentNode.removeChild(this._jp);
            this._jp = j;
            return j;
        },
        done: function(result) {
            var box = document.getElementById('_UF_AddingBox');
            if (box) {
                var msg = result.msg;
                if (result.success) {
                    if (!msg || msg.length == 0) {
                        box.innerHTML = "Save and close this window after 3 seconds！";
                    }
                    else {
                        box.innerHTML = msg + "\r\nSave and close this window after 3 seconds！";
                    }
                    setTimeout(function() {
                        box.parentNode.removeChild(box);
                        window.close();
                    }, 3000);
                }
                else {
                    box.innerHTML = msg;
                }
            }
        }
    };

    window._UF_.save();
})();
