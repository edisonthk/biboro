$(function() {
    
    $("form").submit(function(e) {
        e.preventDefault();
        
        submitAjax(e.target);
    });

    function submitAjax(_form) {
        var errorsMessage = $(".form-errors");
        errorsMessage.removeClass("open");

        var f = new FormData(_form);

        // $(".form-errors").addClass("open");
        $.ajax({
            url: _form.action,
            method: 'post',
            data: f,
            processData: false,
            contentType: false,
            success: function(data) {
                var token = data.split(": ");
                if(token.length > 1) {
                    var redirectSite = token[1];

                    location.href = redirectSite;
                }
            },
            error: function(xhr) {

                var messages = [];
                for(var key in xhr.responseJSON) {
                    var errs = xhr.responseJSON[key];
                    if(Array.isArray(errs)) {
                        for (var i = 0; i < errs.length; i++) {
                            messages.push(errs[i]);
                        }    
                    }else{
                        messages.push(errs);
                    }
                    
                }
                
                errorsMessage.addClass("open");
                showAlert(messages);
            }
        });
    }

    function showAlert(messages) {
        var list = $(".form-errors ul");
        list.html("");
        console.log(list);
        for (var i = 0; i < messages.length; i++) {
            list.append("<li>"+messages[i]+"</li>");
        }
    }
    

});