// ==UserScript==
// @name         Code Saver
// @namespace    http://bjiast.net/
// @version      1.0
// @description  try to take over the world!
// @author       bjiast, me.media.dev
// @match        http://*/*
// @match        https://*/*
// @grant        GM_xmlhttpRequest
// ==/UserScript==

(function () {
    'use strict';


    if (typeof window.$ !== 'undefined' &&
        typeof window.jQuery !== 'undefined') {
        include("https://code.jquery.com/jquery-3.2.1.min.js");
    }

    setTimeout(scriptAction, 1000);


    function scriptAction() {
        console.log('hello');
        let isDown = false;
        var textCode = "";


        $('body').append(`<div id='codeSaver' class='codeSaver_block'>
		<div class="save-block">
		<p><input type="text" maxlength="50" class="add_category_for_item_save" placeholder="Категория"/></p>
		<p><input type="text" maxlength="50" class="add_name_for_item_save" placeholder="Название"/></p>
		<p>Твой текст:</p>
		<pre><code></code></pre>
		<a href='#' id='codeSaver_btn' style='border: 1px solid #bebebe; background: #41ff6a; padding: 6px 20px;'>Сохранить!</a>
		<button id="close_saver_btn" style='border: 1px solid #bebebe; background: #ff6d66; padding: 6px 20px;'>Закрыть!</button>
		</div>
		</div>`);

        $('#codeSaver').css({
            'position': 'fixed',
            'right': '0px',
            'top': 0,
            'z-index': 9999999999,
            'width': '0',
            'background': 'rgba(0,0,0,.2)',
            'height': '100%',
            'padding': '10% 0',
            'text-align': 'center',
            'border-left': '1px solid #000',
            'overflow-y': 'auto',
            'opacity': '0',
            'transition': 'width 180ms ease-out, opacity 120ms ease-in'
        });
        $('#codeSaver p, #codeSaver h4').css({
            'background': '#fff',
            'padding': "10px 0"
        });
        $('#codeSaver code').css({
            'display': 'block',
            'width': '100%',
            'white-space': 'unset',
            'margin-bottom': '40px'
        })

        $("body > *").mousedown(function () {
            isDown = true;
        });

        $('body > *').mouseup(function (e) {

            if (isDown && e.shiftKey) {

                let resultObj = getSelectionText(e),
                    textCode = resultObj.text;

                $('#codeSaver').css({
                    'width': '0',
                    'opacity': 0
                });

                if (textCode !== '') {
                    $('#codeSaver').css({
                        'width': '400px',
                        'opacity': 1
                    });

                    $('#codeSaver code').html(textCode);
                }

                $('#codeSaver_btn').unbind().on('click', uploadCode);
                $('#close_saver_btn').unbind().on('click', function () {
                    $('#codeSaver').css({
                        'width': '0',
                        'opacity': 0
                    });
                });

                isDown = false;
            }

        });

        function uploadCode() {

            textCode = $('#codeSaver code').html();

            let settings = {
                url: 'http://127.0.0.1:8000/api/item/new',
                method: 'post',
                data: {
                    category: $('.add_category_for_item_save').val() || 'New',
                    title: document.title,
                    name: $('.add_name_for_item_save').val() || 'New',
                    text: textCode,
                    link: window.location.href
                },
                success: _success,
                error: _error
            };

            $.ajax(settings);

            console.log('we need to save this ', textCode);

            return false;
        }

    }

    function _success(response) {
        console.log('success');
        console.log(response);
        $('#codeSaver').css({
            'width': '0',
            'opacity': 0
        })
    }

    function _error(response) {
        console.log('error');
        console.log(response);
    }

    function getSelectionText(event) {
        let result = {};
        if (window.getSelection) {
            result['text'] = window.getSelection().toString();
        }

        console.log(result);
        return result;
    }

    function include(url) {
        var script = document.createElement('script');
        script.src = url;
        document.getElementsByTagName('head')[0].appendChild(script);
    }
})();