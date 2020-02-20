// ==UserScript==
// @name         Code Saver
// @namespace    http://bjiast.net/
// @version      1.01
// @description  try to take over the world!
// @author       bjiast, me.media.dev
// @match        http://*/*
// @match        https://*/*
// @grant        GM_xmlhttpRequest
// ==/UserScript==

(function() {
    'use strict';

    console.log('hello');

    let mouseUpListener = function(e){
        // let button = document.querySelector('#codeSaver_btn');

        // if(button != null){
        //     document.removeEventListener('mouseup', mouseUpListener, false);

        //     return false;
        // }

        let resultObj = getSelectionText(e),
            textCode = resultObj.text,
            mouseX = resultObj.startPosition,
            mouseY = resultObj.endPosition;

        let removeElem = document.getElementsByClassName('codeSaver_block');

        while(removeElem[0]){
            removeElem[0].parentNode.removeChild(removeElem[0]);
        }

        if(textCode !== ''){
            let addElem = document.createElement('div');

            addElem.innerHTML = "<a href='#' id='codeSaver_btn' style='border: 1px solid #bebebe; background: #cecece; padding: 6px 20px;'>Сохранить!</a>";
            addElem.style.position = 'absolute';
            addElem.style.left = mouseX + 'px';
            addElem.style.top = mouseY + 'px';
            addElem.style.zindex = '999999999999';
            addElem.classList.add("codeSaver_block");


            document.body.appendChild(addElem);

            let button = document.querySelector('#codeSaver_btn');
            button.onclick = uploadCode(event);
            console.log(resultObj);
        }
    }


    document.body.addEventListener('mouseup', mouseUpListener, false);


    function getSelectionText(event) {
        let result = {};
        if (window.getSelection) {
            result['text'] = window.getSelection().toString();
            result['startPosition'] = event.screenX + 10;
            result['endPosition'] =  event.screenY - 90;
        }
        return result;
    }

    function uploadCode(e){

        console.log('click');
        e.preventDefault();
    }
})();