if($('#blog-form-content').length) {
    var toolbarOptions = [
        ['bold', 'italic', 'underline', 'strike'],
        ['blockquote', 'code-block'],
        ['link', 'image']
    ]
    var quill = new Quill('#blog-form-content', {
        theme: 'snow',
        modules: {
            toolbar: toolbarOptions
        }
    })
    $('#blog-form-submit-btn').on('click', function(e) {
        e.preventDefault()
        var delta = quill.getContents()

        // AJAX
        $.ajaxSetup({
            url: "/blogs",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            dataType: 'json'
        })
        $.ajax({
            data: {
                title: $('#title').val(),
                content: {...delta}
            }
        })
            .done(function( msg ) {
                console.log(msg)
            })
            .fail(function(errors) {
                console.log(errors)
            })
    })
}