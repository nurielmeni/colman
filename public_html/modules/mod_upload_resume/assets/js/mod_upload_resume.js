(function () {
    var $ = jQuery;
    //dropBox start
    var options = {
        // Required. Called when a user selects an item in the Chooser.
        success: function (files) {
            //  console.log(files);
            $("#resume-modul-dropbox-fileName").val(files[0].name);
            $("#resume-modul-dropbox-fileUrl").val(files[0].link);
            //$("#resume-modul-selected-fileName").text(files[0].name);
            $('#resume-modul-upload-dropbox').addClass('selected');
        },
        cancel: function () {

        },
        linkType: "direct", // or "direct"
        multiselect: false, // or true
        extensions: ['.pdf', '.doc', '.docx', '.rtf']
    };

    //Dropbox End

    //OneDrive Start



    function openFromSkyDrive() {
        WL.fileDialog({
            mode: 'open',
            select: 'single'
        }).then(
                function (response) {

                    var files = response.data.files;
                    for (var i = 0; i < files.length; i++) {
                        var file = files[i];

                        $("#resume-modul-skydrive-fileName").val(file.name);
                        $("#resume-modul-skydrive-fileUrl").val(file.source);
                        //$("#resume-modul-selected-fileName").text(file.name);
                        //  console.log(file);
                        $('#resume-modul-upload-skydrive').addClass('selected');
                    }
                },
                function (errorResponse) {
                    log("WL.fileDialog errorResponse = " + JSON.stringify(errorResponse));
                }
        );
    }

    function log(message) {
        var child = document.createTextNode(message);
        var parent = document.getElementById('JsOutputDiv') || document.body;
        parent.appendChild(child);
        parent.appendChild(document.createElement("br"));
    }

    function onUploadFileError(response) {
        document.getElementById("info").innerText =
                "Error getting folder info: " + response.error.message;
    }


    //OneDrive End
    $(document).ready(function () {
        $("#resume-modul-upload-computer").click(function () {
            $("#resume-modul-localFile").click();
        });
        $("#resume-modul-localFile").change(function () {
            var fileName = $("#resume-modul-localFile").val();
            var clean = fileName.split('\\').pop();
            //$("#resume-modul-selected-fileName").text(clean);
            $("#resume-modul-upload-computer").addClass("selected");
        });

        $("#resume-modul-upload-dropbox").click(function () {
            Dropbox.choose(options);
        });

        $("#resume-modul-upload-skydrive").click(function () {
            WL.init({client_id: onedriveClientId, redirect_uri: onedriveRedirectUri});
            WL.login({"scope": "wl.skydrive wl.signin"}).then(
                    function (response) {
                        openFromSkyDrive();
                    },
                    function (response) {
                        log("Failed to authenticate.");
                    }
            );
        });

        $("#resume-modul-submit").click(function () {

            var formData = new FormData($("#resume-modul-upload-form")[0]);
            //  $("#resume-modul-idnumber").val() !== "" && $("#resume-modul-fullname").val() !== "" &&
            if (($("#resume-modul-localFile").val() !== "" || $("#resume-modul-dropbox-fileUrl").val() !== "" || $("#resume-modul-skydrive-fileUrl").val())) {



                $.ajax({
                    url: "",
                    type: 'POST',
                    data: formData,
                    async: true,
                    success: function (result) {
                        var res= result;
                        //console.log("Resume Upload: "+res);
                        if (res != null && res.hasOwnProperty("result") && res.result === "auth required")
                        {
                            alert("Session Expired, please log in again.");
                            location.reload();
                        }


                        $('#resume-modul-upload-dropbox').removeClass('selected');
                        $("#resume-modul-upload-computer").removeClass("selected");
                        $('#resume-modul-upload-skydrive').removeClass('selected');

                        //   $('#resume-modul-fullname').val('');
                        //  $('#resume-modul-idnumber').val('');

                        var allContent = $(document.createElement('div')).addClass('resume-success').attr({'id': "popup_div", "dir": "rtl"});


                        $('.resume-modul-content').hide();
                        $('.resume-module-submit-response.resume-modul-content').fadeIn(500);
                        setTimeout(function() {$('.resume-module-submit-response.resume-modul-content').hide();}, 5000);
                        setTimeout(function() {$('ul.resume-modul-content').fadeIn(500);}, 5000);

                        var resArray = JSON.parse(res);
                        if(typeof(resArray.fileList)!=="undefined"&&typeof(resArray.fileList.type)!=="undefined")
                        switch (resArray.fileList.type){
                            case "cv":
                                console.log("cv: "+resArray.fileList.count);
                                $('.board-modul-box.cv .board-modul-count').text(resArray.fileList.count);
                                break;
                            case "file":
                                console.log("file: "+resArray.fileList.count);
                                $('.board-modul-box.files .board-modul-count').text(resArray.fileList.count);
                                break;
                            default:
                                console.log("No files were uploded");
                                break;
                        }



//                        var source = "plugins/system/nsoft/assets/js/fileList/html/file-list-template.html";
//                        $.get(source, function (data) {
//                            var resuePopupHtml = $(data);
//                            allContent.html(resuePopupHtml);
//                      //      allContent.find('.popup_head_numb').html("Title");
//
//                            var content = $(document.createElement('div')).addClass("popup_content");
//                            content.html("<p class='resume-success-content'>קורות החיים נשלחו בהצלחה!</p>");
//                            
//                            allContent.append(content);
//                            
//                            $.magnificPopup.open({
//                                items: {
//                                    src: allContent
//                                },
//                                type: 'inline'
//                            }, 0);
//
//                            allContent.find('.popup_close').click(function (e) {
//                                e.preventDefault();
//                                $.magnificPopup.close();
//                            });
//                        });
                    },
                    cache: false,
                    contentType: false,
                    processData: false
                });
            }
        });
        
        $("#apply-job-upload-file-type").change(function(e){
            //console.log("Meni: Upload File Type: " + $(this).val());
            switch($(this).val()){
                case "cv":
                    options.extensions = ['.pdf', '.doc', '.docx', '.rtf'];
                    $("#resume-modul-localFile").attr("accept", "application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/pdf,application/rtf");
                    break;
                case "file":
                    options.extensions = ['.pdf', '.doc', '.docx', '.rtf', '.jpg', '.png', '.txt', '.ico'];
                    $("#resume-modul-localFile").attr("accept", "image/*,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/pdf,application/rtf");
                    break;
            }
        });
    });
})();


