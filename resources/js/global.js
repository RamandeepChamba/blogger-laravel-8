$(function() {
    // Prevent multiple form submission
    $('.prevent-multiple-submit').on('click', function (e) {
        // Prevent form submission
        e.preventDefault()
        // Disable submit and reset button
        $(this).attr('disabled', true)
        $('[type="reset"]').attr('disabled', true)
        // Change submit btn text
        $(this).html('...wait')

        // Submit form
        this.closest('form').submit()
    })
})