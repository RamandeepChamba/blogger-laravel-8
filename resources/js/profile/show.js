const { data } = require("autoprefixer")

function showProfile() {
    if(document.getElementById('profile-form')) {
        document.getElementById('profile-form').addEventListener('submit', function (e) {
            e.preventDefault()
        })
    }

    navigate()
    editSection()
    cancelEditing()
    submitProfile()
    profileAvatar()

    // Navigation
    function navigate() {
        let sectionLabels = document.querySelectorAll('#profile-card-section-labels > button')
        let sections = document.querySelectorAll('.profile-card-section')

        if(sections[0]) {
            sectionLabels.forEach(label => {
                label.addEventListener('click', function (e) {
                    // Disable inputs in active section
                    toggleActiveSectionInputs(false)
                    
                    // Show relative section
                    // - section id example : #profile-card-section-settings
                    const sectionToEnableId = 'profile-card-section-' + this.dataset.sectionId
                    const labelToEnable = this
                    sections.forEach(section => {
                        if(section.id == sectionToEnableId) {
                            // Make label active
                            sectionLabels.forEach(label => {
                                label.classList.remove('active-section-label')
                                labelToEnable.classList.add('active-section-label')
                            })
                            // Show
                            section.classList.remove('hidden')
                            // Disable active section label
                            toggleActiveLabel(false)
                            toggleInactiveLabels(true)
                            // Special case social links
                            if(section.id == 'profile-card-section-social-links') {
                                getSocialLinks()
                            }
                            // Toggle edit button
                            if(section.className.split(" ").indexOf('dontHaveEditBtn') > -1) {
                                // Hide
                                if(document.getElementById('edit-profile-btn')) {
                                    document.getElementById('edit-profile-btn').classList.add('hidden')
                                }
                            }
                            else {
                                // Show
                                if(document.getElementById('edit-profile-btn')) {
                                    document.getElementById('edit-profile-btn').classList.remove('hidden')
                                }
                            }
                        }
                        else {
                            section.classList.add('hidden')
                        }
                    })
                })
            })
        }
    }

    // Ajax - get social links
    function getSocialLinks(editing = false) {
        // Disable Labels
        toggleInactiveLabels(false)

        let profileId = $('#profile-card').data('profileId')

        // Disable inputs
        $('#profile-form #facebook_link').attr('disabled', true)
        $('#profile-form #facebook_link').val('fetching...')
        $('#profile-form #twitter_link').attr('disabled', true)
        $('#profile-form #twitter_link').val('fetching...')

        if(editing) {
            $('#profile-form #facebook_link_absent').addClass('hidden')
            $('#profile-form #twitter_link_absent').addClass('hidden')
            $('#save-profile-btn').html('Wait...')
            $('#save-profile-btn').attr('disabled', true)
            $('#cancel-profile-btn').attr('disabled', true)
        }
        else {
            $('#edit-profile-btn').html('Wait...')
            $('#edit-profile-btn').attr('disabled', true)
        }

        $('#profile-form #facebook_link_absent').html('fetching...')
        $('#profile-form #twitter_link_absent').html('fetching...')

        if(!editing) {
            $('#profile-form #facebook_link_absent').removeClass('hidden')
            $('#profile-form #twitter_link_absent').removeClass('hidden')
        }
        $('#profile-form #facebook_link_anchor').addClass('hidden')
        $('#profile-form #twitter_link_anchor').addClass('hidden')
        
        $.get(`/profile/${profileId}/getSocialLinks`)
            .done(function (data) {
                
                if(editing) {
                    $('#profile-form #facebook_link').val(data.facebook_link)
                    $('#profile-form #twitter_link').val(data.twitter_link)

                    $('#profile-form #facebook_link').attr('disabled', false)
                    $('#profile-form #twitter_link').attr('disabled', false)

                    $('#save-profile-btn').html('Save')
                    $('#save-profile-btn').attr('disabled', false)
                    $('#cancel-profile-btn').attr('disabled', false)
                }
                else {
                    $('#edit-profile-btn').html('Edit')
                    $('#edit-profile-btn').attr('disabled', false)
                }
                
                if(data.facebook_link) {
                    // Show link
                    if(!editing) {
                        $('#profile-form #facebook_link_anchor').attr('href', data.facebook_link)
                        $('#profile-form #facebook_link_anchor').html(data.facebook_link)
                        $('#profile-form #facebook_link_anchor').removeClass('hidden')
                    }

                    // Hide N/A
                    $('#profile-form #facebook_link_absent').addClass('hidden')
                }
                else {
                    // Hide link
                    $('#profile-form #facebook_link_anchor').addClass('hidden')
                    // Show N/A (not if editing)
                    if(!editing) {
                        $('#profile-form #facebook_link_absent').removeClass('hidden')
                        $('#profile-form #facebook_link_absent').html('N/A')
                    }
                }

                if(data.twitter_link) {
                    if(!editing) {
                        $('#profile-form #twitter_link_anchor').attr('href', data.twitter_link)
                        $('#profile-form #twitter_link_anchor').html(data.twitter_link)
                        $('#profile-form #twitter_link_absent').addClass('hidden')
                        $('#profile-form #twitter_link_anchor').removeClass('hidden')
                    }
                    
                    $('#profile-form #twitter_link_absent').addClass('hidden')
                }
                else {
                    $('#profile-form #twitter_link_anchor').addClass('hidden')
                    if(!editing) {
                        $('#profile-form #twitter_link_absent').removeClass('hidden')
                        $('#profile-form #twitter_link_absent').html('N/A')
                    }
                    else {
                        // Hide
                        $('#profile-form #twitter_link_absent').addClass('hidden')
                    }
                }

                // Enable Labels
                if(!editing) {
                    toggleInactiveLabels(true)
                }
            })
            .fail(function (msg) {
                console.log(msg)
            })
    }

    // Editing section
    function editSection() {
        let editBtn = document.getElementById('edit-profile-btn')

        if(editBtn) {
            editBtn.addEventListener('click', function (e) {
                // Special case for social links
                let hasHiddenInputs = false
                if(getActiveSectionId() == 'profile-card-section-social-links') {
                    hasHiddenInputs = true
                }
                // Toggle editing
                toggleEditing(true, hasHiddenInputs)
            })
        }
    }

    // Cancel editing
    function cancelEditing() {
        if(document.getElementById('cancel-profile-btn')) {
            document.getElementById('cancel-profile-btn').addEventListener('click', function (e) {
                // Disable Labels
                toggleInactiveLabels(false)
                // Special case for social links
                let hasHiddenInputs = false
                if(getActiveSectionId() == 'profile-card-section-social-links') {
                    hasHiddenInputs = true
                }
                // Toggle editing
                toggleEditing(false, hasHiddenInputs)
            })
        }
    }

    // Update profile
    function submitProfile() {
        if(document.getElementById('save-profile-btn')) {
            document.getElementById('save-profile-btn').addEventListener('click', function (e) {
                // Disable Labels
                toggleInactiveLabels(false)
                // Disable save profile button
                $('#save-profile-btn').html('Saving...')
                $('#save-profile-btn').attr('disabled', true)
                // Disable cancel profile button
                $('#cancel-profile-btn').attr('disabled', true)
                submitForm()
            })
        }
        

        function submitForm() {
            // Prepare data according to active session
            let data = {
                'profile-card-section-basic-info': {
                    type: 'basic',
                    name: $('#profile-form #name').val()
                },
                'profile-card-section-about': {
                    type: 'about',
                    bio: $('#profile-form #bio').val()
                },
                'profile-card-section-social-links': {
                    type: 'social',
                    facebook_link: $('#profile-form #facebook_link').val(),
                    twitter_link: $('#profile-form #twitter_link').val()
                }
            }

            $.ajaxSetup({
                url: "/profile/update",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                dataType: 'json'
            })
            $.ajax({
                data: Object.assign(data[getActiveSectionId()])
            })
                .done(function( msg ) {
                    // Special case for social links
                    let hasHiddenInputs = false
                    if(getActiveSectionId() == 'profile-card-section-social-links') {
                        hasHiddenInputs = true
                    }
                    // toggle editing
                    toggleEditing(false, hasHiddenInputs)

                    console.log(msg)
                })
                .fail(function(msg) {
                    console.log(msg)
                })
        }
        
    }

    // Get active section id
    function getActiveSectionId() {
        const activeSectionLabel = document.querySelector('#profile-card-section-labels > button.active-section-label')
        const activeSectionId = 'profile-card-section-' + activeSectionLabel.dataset.sectionId
        return activeSectionId
    }

    // Disable/ Enable all inputs in the active section
    function toggleActiveSectionInputs(enable) {
        const activeSectionId = getActiveSectionId()
        const inputs = document.querySelectorAll(`#${activeSectionId} > input, #${activeSectionId} > textarea`)
        inputs.forEach(input => {
            input.disabled = !enable
        })
    }

    // Show/ Hide all hidden inputs and links in the active section
    function toggleActiveSectionHiddenInputs(enable) {
        const activeSectionId = getActiveSectionId()
        const inputsAndLinks = document.querySelectorAll(`#${activeSectionId} > input, #${activeSectionId} > a`)
        inputsAndLinks.forEach(el => {
            el.classList.toggle('hidden')
        })

        // N/A
    }

    // Disable / Enable inactive labels
    function toggleInactiveLabels(enable) {
        const inactiveLabels = document.querySelectorAll('#profile-card-section-labels > button:not(.active-section-label)')
        inactiveLabels.forEach(inactiveLabel => {
            inactiveLabel.disabled = !enable
        })
    }

    // Disable active section
    function toggleActiveLabel(enable) {
        const activeLabel = document.querySelector('#profile-card-section-labels > button.active-section-label')
        activeLabel.disabled = !enable
    }

    // Toggle editing
    function toggleEditing(editing, hasHiddenInputs = false) {
        // Toggle editing buttons
        document.getElementById('edit-profile-btn').classList.toggle('hidden')
        document.getElementById('save-profile-btn').classList.toggle('hidden')
        document.getElementById('cancel-profile-btn').classList.toggle('hidden')

        
        if(editing) {
            if(hasHiddenInputs) {
                // Show inputs
                // Hide links
                toggleActiveSectionHiddenInputs(true)
                getSocialLinks(true)
            }
            else {
                // Enable all input fields of active section
                toggleActiveSectionInputs(true)
            }
            // Disable inactive labels
            toggleInactiveLabels(false)
        }
        else {
            if(hasHiddenInputs) {
                // Hide inputs
                // Show links
                toggleActiveSectionHiddenInputs(false)
                getSocialLinks()
            }
            else {
                // Disable all inputs fields of active section
                toggleActiveSectionInputs(false)
            }
            // Enable inactive labels
            toggleInactiveLabels(true)

            // Enable save profile button
            $('#save-profile-btn').html('Save')
            $('#save-profile-btn').attr('disabled', false)
            $('#cancel-profile-btn').attr('disabled', false)
        }
    }

    // Profile avatar
    function profileAvatar() {
        preview()
        function showEditBtns() {
            $("#avatar-save-btn").show()
            $("#avatar-cancel-btn").show()
        }

        function preview() {
            if($("#profile-card-avatar")) {
                // The very first image until save
                let src = $("#avatar").attr('src')

                $("#profile-card-avatar").change(function() {
                    readURL(this, src)
                })

                // Cancel Avatar
                cancelAvatarHandler(src)
                saveAvatar()
            }
        }
        function readURL(input, src = null) {
            if (input.files && input.files[0]) {
                var reader = new FileReader()
        
                reader.onload = function (e) {
                    $('#avatar').attr('src', e.target.result)
                }
        
                reader.readAsDataURL(input.files[0])
                showEditBtns()
            }
            else {
                cancelAvatar(src)
            }
        }
        // Reset image on cancel
        function cancelAvatarHandler(src = null) {
            if($("#avatar-cancel-btn")) {
                $("#avatar-cancel-btn").click(function () {
                    cancelAvatar(src) 
                })
            }
        }
        function cancelAvatar(src) {
            $('#avatar').attr('src', src)      
            $("#profile-card-avatar").val(null)
            // Hide save and cancel button
            $('#avatar-save-btn').hide()
            $('#avatar-cancel-btn').hide()
        }
        // Save avatar
        function saveAvatar() {
            if($("#avatar-save-btn")) {
                $("#avatar-save-btn").click(function () {
                    // disable cancel and save button and input
                    $(this).attr('disabled', true)
                    $("#avatar-cancel-btn").attr('disabled', true)
                    $("#profile-card-avatar").attr('disabled', true)

                    let formData = new FormData()
                    var d = $('#profile-card-avatar')[0].files[0]
                
                    formData.append('avatar', d)
                    formData.append('type', 'avatar')
                
                    $.ajax({
                        url: '/profile/update',
                        method: 'POST',
                        contentType: false,
                        processData: false,
                        data: formData,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        },
                        success: function(res){
                            $('#avatar').attr('src', res.profile.avatar);
                            // enable cancel and save button and input
                            $("#avatar-save-btn").attr('disabled', false)
                            $("#avatar-save-btn").hide()
                            $("#avatar-cancel-btn").attr('disabled', false)
                            $("#avatar-cancel-btn").hide()
                            $("#profile-card-avatar").attr('disabled', false)
                        },
                        error: function(e){
                            console.log(e)
                        }
                    })
                })
            }
        }
    }
}

showProfile()
