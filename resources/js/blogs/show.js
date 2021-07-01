import registerCustomImage from "./registerCustomImage";

if($('#blog-content').length) {
    var quill = new Quill('#blog-content', {
        theme: 'snow',
        modules: {
            toolbar: false,
        },
        readOnly: true
    })

    registerCustomImage()

    // Set content
    var content = $('#blog-content').data('content')
    quill.setContents(content)
}