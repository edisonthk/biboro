(function() {

var KeyEvent = {
    KEY_0_9 : -10,
    KEY_A_Z : -9,
    KEY_0 : 48,
    KEY_9 : 57,
    KEY_A : 65,
    KEY_B : 66,
    KEY_C : 67,
    KEY_D : 68,
    KEY_E : 69,
    KEY_F : 70,
    KEY_G : 71,
    KEY_H : 72,
    KEY_I : 73,
    KEY_J : 74,
    KEY_K : 75,
    KEY_L : 76,
    KEY_M : 77,
    KEY_N : 78,
    KEY_O : 79,
    KEY_P : 80,
    KEY_Q : 81,
    KEY_R : 82,
    KEY_S : 83,
    KEY_T : 84,
    KEY_U : 85,
    KEY_V : 86,
    KEY_W : 87,
    KEY_X : 88,
    KEY_Y : 89,
    KEY_Z : 90,
    KEY_ESC : 27,
    KEY_ENTER : 13,
    KEY_UP: 38,
    KEY_DOWN: 40,
    KEY_DEL: 8,
};


var activeClass  = "active",
    $pane        = $(".tabs > .pane"),
    $content     = $("textarea[name=content]"),
    $title       = $("input[name=title]"),
    $errMessages = $(".tooltips.errMessages")
;


/*
 * =========================
 * Initial action
 * 
 */ 

changeActiveWorkbook($pane[0]);
$pane.click(function(e) {
    $pane.each(function(index, element) {
        $(element).removeClass(activeClass);
    });

   changeActiveWorkbook(this);
});

updateMarkdownTitle($title.val());
updateMarkdownContent($content.val());
$content.keyup(function() {
    updateMarkdownContent($(this).val());
});
$title.keyup(function() {
    updateMarkdownTitle($(this).val());
});

updateMetaKeyIcon();
setupShortcutKey();

$title[0].focus();

var firstColHeight = null;
$(".col-left").each(function(index, element) {
    if(firstColHeight === null) {
        firstColHeight = element.clientHeight; 
    }else {
        console.log(firstColHeight);
        $(element).css("height", firstColHeight + "px");
    }
});

$("form").submit(submitFormByAjax);

/*
 * =========================
 * Prototype are below
 * 
 */ 

function setupShortcutKey() {

    window.addEventListener("keydown", function(e) {
        if(isKeyPressed(e, true, KeyEvent.KEY_S)) {
            e.preventDefault();
            $("form").submit();
        }


    });
}

function updateMarkdownContent(text) {

    var renderer = new marked.Renderer();
    renderer.code = function(code, language) {
        var langCode = "";
        if(language) {
            langCode = " lang-"+language;
        }
        return '<pre class="prettyprint '+langCode+'">'+prettyPrintOne(code)+'</pre>';
    }

    $("#md-content").html(marked(text, {renderer: renderer}));
}
function updateMarkdownTitle(title) {
    $("#md-title").html(title);
}

function changeActiveWorkbook(element) {

    var $element = $(element);
    $element.addClass(activeClass);
    $("input[name=workbook]").val($element.attr("index"));
}

function updateMetaKeyIcon() {
    var metaKey = "Ctrl";
    if(navigator.appVersion.indexOf("Mac") != -1) {
        metaKey = "⌘";
    }
    
    $(".meta").each(function(index, element) {
        $(element).html(metaKey);
    });
}

function isKeyPressed(_event, meta_key, key) {
    var e = _event;
    var keyPressed = e.keyCode;

    // kp is flag to check if correct meta key is used or not
    // if meta_key is true, but ctrlKey or metaKey is not detect, kp will set to false
    // if meta_key is false, but ctrlKey or metaKey is detect, kp will also set to false
    var kp = false;
    if(meta_key) {
        if(e.ctrlKey || e.metaKey){
            kp = true;
        }
    }else{
        if(!(e.ctrlKey || e.metaKey)){
            kp = true;
        }
    }


    // if meta_key and userKey is matching, kp will be set as true
    // for more detail about kp, check it out above
    if(kp) {
        if(key === KeyEvent.KEY_0_9 && keyPressed >= KeyEvent.KEY_0 && keyPressed <= KeyEvent.KEY_9){
            // key => [0-9]
            return true;
        }else if(key === KeyEvent.KEY_A_Z && keyPressed >= KeyEvent.KEY_A && keyPressed <= KeyEvent.KEY_Z) {
            // key => [A-Z]    
            return true;
        }else if(key ==  keyPressed ) {
            // other
            return true;
        }

    }
    return false;
}

function submitFormByAjax(e) {
    e.preventDefault();

    var _form = e.target;
    
    $.ajax({
        url: _form.action,
        method: _form.method,
        data: $(_form).serialize(),
        success: function(data){
            window.close();
        },
        error: function(xhr) {
            var errMessages = [];
            for(var key in xhr.responseJSON.error) {
                for (var i = 0; i < xhr.responseJSON.error[key].length; i++) {
                    errMessages.push(xhr.responseJSON.error[key][i]);
                }
            }

            console.log(errMessages);
            showErrorMessages(errMessages);
        }
    });
}

var timeoutId = null;
function showErrorMessages(errMessages) {
    clearTimeout(timeoutId);
    var ul = $errMessages.find("ul");
    ul.html("");
    for (var i = 0; i < errMessages.length; i++) {
        ul.append("<li>"+errMessages[i]+"</li>");
    }

    $errMessages.addClass("show");
    timeoutId = setTimeout(function() {
        $errMessages.removeClass("show");
    }, 2000);

}

})();