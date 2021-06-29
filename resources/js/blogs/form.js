if($('#blog-form-content').length) {
    var toolbarOptions = [
        ['bold', 'italic', 'underline'],
        ['blockquote', 'code-block'],
        ['link', 'image']
    ]
    var quill = new Quill('#blog-form-content', {
        theme: 'snow',
        modules: {
            toolbar: toolbarOptions
        }
    })
    // If editing
    if($('#blog-form-content').data('editing') == 1) {
        var content = $('#blog-form-content').data('content')
        quill.setContents(content)
    }
    // Submit
    $('#blog-form-submit-btn').on('click', function(e) {
        // Remove errors
        removeErrors()
        // Disable form buttons
        toggleFormBtns(false)
        var route = $('#blog-form-content').data('route')
        e.preventDefault()
        var delta = JSON.stringify(quill.getContents())

        // AJAX
        $.ajaxSetup({
            url: route,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: $('#blog-form-content').data('method'),
            dataType: 'json'
        })
        $.ajax({
            data: {
                title: $('#title').val(),
                content: delta
            }
        })
            .done(function( response ) {
                if(response.route) {
                    window.location.replace(response.route)
                    // Redirect to blogs.show
                }
                else {
                    // Show errors on UI
                    showErrors(response.errors)
                    // Enable form btns
                    toggleFormBtns(true)
                }
            })
            .fail(function(errors) {
                console.log(errors)
            })
    })

    $("#blog-form-reset-btn").on('click', function(e) {
        // Remove errors
        removeErrors()
        // Disable form buttons
        toggleFormBtns(false)
        // Reset title
        $("input#title").val($("input#title").data('title'))
        // Reset content
        var content = $('#blog-form-content').data('content')
        quill.setContents(content)
        // Enable form buttons
        toggleFormBtns(true)
    })

    function showErrors(errors) {
        if(errors.title) {
            $("#title-error").html(errors.title)
            $("#title-error").show()
        }
        if(errors.content) {
            $("#content-error").html(errors.content)
            $("#content-error").show()
        }
    }

    function removeErrors() {
        $("#title-error").hide()
        $("#content-error").hide()
    }

    function toggleFormBtns(enable) {
        // Submit btn
        $("#blog-form-submit-btn").attr("disabled", !enable)
        // Reset button
        $("#blog-form-reset-btn").attr("disabled", !enable)
    }
}