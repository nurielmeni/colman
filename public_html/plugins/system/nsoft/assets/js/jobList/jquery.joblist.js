(function ($) {

    $.fn.jobList = function (options) { // Plugin options default values

        var settings = $.extend({
            "id_pref": "",
            "objList": "",
            "countPerPage": 10,
            "controller": "jobs",
            "closeButton": "סגור",
            "applyButton": "הגש קו”ח",
            "detailsButton": "הצג משרה",
            applyForJob: function (page) {
            }
        }, options);


        var listContainer = this;

        listContainer.html("");

        showHideDetails = function (id_pref, state, item) {

            if (state == 1) {
                $('#' + id_pref + 'block' + item.id).removeClass('joblist-short-details').addClass('joblist-long-details');
            } else {
                $('#' + id_pref + 'block' + item.id).removeClass('joblist-long-details').addClass('joblist-short-details');
            }

            var source = (state == 1 ? "plugins/system/nsoft/assets/js/jobList/html/single-job-details.html" : "plugins/system/nsoft/assets/js/jobList/html/single-job-short.html");
            jQuery.ajax({
                url: source,
                async: true,
                beforeSend: function () {
                    if (state === 1){
                        $('#' + id_pref + 'block' + item.id).before('<progress class="pure-material-progress-linear"></progress>');
                    }
                },
                success: function (data) {
                    longDetailsHtml = $.parseHTML(data);
                    currentBlock = $('#' + id_pref + 'block' + item.id);
                    currentBlock.html(longDetailsHtml);
                    cvEmailExist = false;

                    if (item.jobStatus == 3) {
                        currentBlock.find('.joblist-apply').parent().append("<p class='joj-is-closed'>משרה סגורה</p>");
                        currentBlock.find('.joblist-apply').remove();
                    } else if (item.status == 1) {
                        currentBlock.find('.joblist-apply').parent().prepend("<p class='already-applied-job'>הוגשו קורות חיים למשרה זו</p>");
                        currentBlock.find('.joblist-apply').remove();

                    } else {
                        currentBlock.find('.joblist-apply').attr("id", id_pref + "apply" + item.id).click(function () {
                            // settings.applyForJob(item.id)
                            if (item.customerSiteLink.length < 1 || cvEmailExist) showApplyBlock(settings.id_pref, item, 1);
                            else showHideDetails(settings.id_pref, 1, item);
                        });
                        currentBlock.find('.joblist-apply').text(settings.applyButton);

                        //                            ADDS THE LINK TO THE CUSTOMER SITE TO APPLY
                        if (item.customerSiteLink.length > 0) {
                            //                                currentBlock.find('.joblist-apply').remove();
                            if (!item.customerSiteLink.startsWith("http://") && !item.customerSiteLink.startsWith("https://"))
                                item.customerSiteLink = "http://" + item.customerSiteLink;
                            $('<button class="popup_btn joblist-apply customer-site" onclick="window.open(\'' + item.customerSiteLink + '\',\'_blank\'\)">הגש קו"ח באתר המעסיק</button>').insertBefore(currentBlock.find('.popup_btn.joblist-close'));
                        }
                    }
                    //console.log("---" + item);
                    currentBlock.find('.joblist-jobName').text(item.name);
                    currentBlock.find('.joblist-jobId').text(item.jobCode);
                    currentBlock.find('.joblist-company').text(item.company);
                    currentBlock.find('.joblist-date').text(item.date);

                    if (state == 1) {
                        currentBlock.find('div.popup_details_buttons').hide();
                        jQuery.ajax({
                            method: "POST",
                            url: "",
                            dataType: 'json',
                            //async: false,
                            data: { type: "get_api", action: "getJobDescription", controller: settings.controller, jobId: item.jobid }
                        })
                        .done(function (res) {
                            if (res.result === "auth required") {
                                alert("Session Expired, please log in again.");
                                location.reload();
                            }
                            var obj = res; //JSON.parse( res );

                            currentBlock.find('.joblist-description').html(obj.Description);
                            currentBlock.find('.joblist-requirement').html(obj.Requirements);
                            currentBlock.find('.joblist-skills').html(obj.Skills);
                            var item = obj.rankList.find(function (elm) { return elm['id'] == obj.Rank; });
                            currentBlock.find('.joblist-rank').html(typeof (item) === 'undefined' ? '' : item.name);
                            cvEmailExist = obj.CvEmail == null || obj.CvEmail.length < 1 ? false : true;
                            if (!cvEmailExist && currentBlock.find('button.popup_btn.joblist-apply.customer-site').length > 0) {
                                currentBlock.find('button.popup_btn.joblist-apply.local').remove();
                            }
                            currentBlock.find('div.popup_details_buttons').show();

                            // Updates the query string so it can be sent to another user
                            updateParameterUrl('getJobById', obj.JobId);
                        })
                        .always(function () {
                            $('#' + id_pref + 'block' + item.id).parent().find('progress').remove();
                        });

                        currentBlock.find('.joblist-close').text(settings.closeButton);
                        currentBlock.find('.joblist-close').attr("id", id_pref + "close" + item.id).click(function () {
                            showHideDetails(id_pref, 0, item);
                        });
                    }
                    else {
                        currentBlock.find('.joblist-details').text(settings.detailsButton);
                        currentBlock.find('.joblist-details').attr("id", id_pref + "details" + item.id);
                        currentBlock.find('#' + id_pref + "details" + item.id).click(function () {
                            showHideDetails(id_pref, 1, item);
                        });
                    }
                }
            });
        };

        getSelectValues = function (contentDiv, id_pref) {

            jQuery.ajax({
                method: "POST",
                url: "",
                dataType: 'json',
                //async: false,
                data: { type: "get_api", action: "getApplyOptionsAction", controller: settings.controller }
            })
                .done(function (res) {

                    if (res.result === "auth required") {
                        alert("Session Expired, please log in again.");
                        location.reload();
                    }

                    var obj = res; //JSON.parse( res );

                    if (res.fileList.length == undefined) {
                        contentDiv.find("#apply-job-upload-file1-" + id_pref).append(new Option(res.fileList.Name, res.fileList.FileId));
                    } else {
                        $.each(res.fileList, function (i, item) {

                            contentDiv.find("#apply-job-upload-file1-" + id_pref).append(new Option(item.Name, item.FileId));
                        });
                    }

                    if (res.cvList.length == undefined) {
                        contentDiv.find("#apply-job-upload-file2-" + id_pref).append(new Option((res.cvList.date + " " + res.cvList.name), res.cvList.id));
                    } else {
                        $.each(res.cvList, function (i, item) {

                            contentDiv.find("#apply-job-upload-file2-" + id_pref).append(new Option((item.date + " " + item.name), item.id));
                        });
                    }


                    contentDiv.find('select.apply-job-upload-select').each(function () {
                        var placeholder = "אין מסמכים נוספים במערכת";
                        if ($(this).attr('name') === 'selectedFile[]' && res.fileList.length > 0) {
                            placeholder = "קבצים מצורפים";
                        }
                        if ($(this).attr('name') === 'selectedCv[]' && res.cvList.length > 0) {
                            placeholder = "קורות חיים";
                        }
                        window.testSelAll2 = $(this).SumoSelect({ selectAll: false, placeholder: placeholder });
                    });
                });
        };

        getApplyBlockData = function (curBlock, item, id_pref) {
            var jobId = item.id;
            var source = "plugins/system/nsoft/assets/js/jobList/html/applyJob.html";
            var options = {
                // Required. Called when a user selects an item in the Chooser.
                success: function (files) {
                    //  console.log(files);
                    curBlock.find("#apply-job-dropbox-fileName-" + jobId).val(files[0].name);
                    curBlock.find("#apply-job-dropbox-fileUrl-" + jobId).val(files[0].link);
                    //curBlock.find("#apply-job-selected-fileName-" + jobId).text(files[0].name);
                    $("#apply-job-upload-dropbox-" + jobId).addClass('selected');
                },
                cancel: function () {

                },
                linkType: "direct", // or "direct"
                multiselect: false, // or true
                extensions: ['.pdf', '.doc', '.docx', '.rtf']
            };
            jQuery.ajax({
                url: source,
                async: true,
                beforeSend: function () {
                    $('#' + id_pref + 'block' + item.id).before('<progress class="pure-material-progress-linear apply-block"></progress>');
                },
                success: function (data) {
                        longDetailsHtml = $.parseHTML(data);
                        curBlock.append(longDetailsHtml);

                        curBlock.find('#action').attr('id', 'action_' + jobId);
                        curBlock.find('#controller').attr('id', 'controller_' + jobId).val(settings.controller);
                        curBlock.find('#type').attr('id', 'type_' + jobId).val("get_api");
                        curBlock.find('#jobId').attr('id', 'jobId_' + jobId).val(jobId);
                        curBlock.find('#jobCode').attr('id', 'jobCode_' + jobId).val(item.jobCode);
                        curBlock.find('#jobTitle').attr('id', 'jobTitle_' + jobId).val(item.name);
                        curBlock.find("#apply-job-localFile").attr('id', 'apply-job-localFile-' + jobId);
                        curBlock.find("#apply-job-upload-computer").attr('id', 'apply-job-upload-computer-' + jobId).click(function () {
                            curBlock.find("#apply-job-localFile-" + jobId).click();
                        });
                        curBlock.find("#apply-job-localFile").attr('id', 'apply-job-localFile-' + jobId);
                        curBlock.find("#apply-job-dropbox-fileUrl").attr('id', 'apply-job-dropbox-fileUrl-' + jobId);
                        curBlock.find("#apply-job-dropbox-fileName").attr('id', 'apply-job-dropbox-fileName-' + jobId);
                        curBlock.find("#apply-job-skydrive-fileUrl").attr('id', 'apply-job-skydrive-fileUrl-' + jobId);
                        curBlock.find("#apply-job-skydrive-fileName").attr('id', 'apply-job-skydrive-fileName-' + jobId);

                        curBlock.find("#apply-job-localFile-" + jobId).change(function () {
                            var fileName = curBlock.find("#apply-job-localFile-" + jobId).val();
                            var clean = fileName.split('\\').pop();
                            //curBlock.find("#apply-job-selected-fileName").attr('id', 'apply-job-selected-fileName-' + jobId).text(clean);
                            $("#apply-job-upload-computer-" + jobId).addClass('selected');
                        });

                        curBlock.find("#apply-job-upload-dropbox").attr('id', 'apply-job-upload-dropbox-' + jobId);
                        curBlock.find("#apply-job-upload-dropbox-" + jobId).click(function () {
                            Dropbox.choose(options);
                        });

                        curBlock.find("#apply-job-upload-skydrive").attr('id', 'apply-job-upload-skydrive-' + jobId);
                        curBlock.find("#apply-job-upload-skydrive-" + jobId).click(function () {
                            WL.init({ client_id: onedriveClientId, redirect_uri: onedriveRedirectUri });
                            WL.login({ "scope": "wl.skydrive wl.signin" }).then(
                                function (response) {
                                    openFromSkyDrivePopup(curBlock, jobId);
                                },
                                function (response) {
                                    log("Failed to authenticate.");
                                }
                            );

                        });
                        curBlock.find("#apply-job-submit").attr('id', 'apply-job-submit-' + jobId);
                        curBlock.find("#apply-job-upload-form").attr('id', 'apply-job-upload-form-' + jobId);

                        curBlock.find("#apply-job-upload-file1").attr('id', 'apply-job-upload-file1-' + jobId);
                        curBlock.find("#apply-job-upload-file2").attr('id', 'apply-job-upload-file2-' + jobId);
                        if (!isLogin) {
                            $(longDetailsHtml).find('.apply-job-content-container')[0].remove();
                        } else {
                            getSelectValues(curBlock, jobId);
                        }

                        curBlock.find("#apply-job-submit-" + jobId).click(function () {
                            if ($("#apply-job-upload-file2-" + jobId).val() != null && $("#apply-job-upload-file2-" + jobId).val() !== "" ||
                                (curBlock.find("#apply-job-upload-file-type").val() == 'cv' &&
                                    (
                                        $("#apply-job-localFile-" + jobId).val() != null && $("#apply-job-localFile-" + jobId).val() !== "" ||
                                        $("#apply-job-dropbox-fileUrl-" + jobId).val() != null && $("#apply-job-dropbox-fileUrl-" + jobId).val() !== "" ||
                                        $("#apply-job-skydrive-fileUrl-" + jobId).val() != null && $("#apply-job-skydrive-fileUrl-" + jobId).val() !== ""
                                    )
                                )) {

                                var formData = new FormData(curBlock.find("#apply-job-upload-form-" + jobId)[0]);

                                $("#apply-job-upload-computer-" + jobId).removeClass('selected');
                                $("#apply-job-upload-dropbox-" + jobId).removeClass('selected');
                                $("#apply-job-upload-skydrive-" + jobId).removeClass("selected");

                                $.ajax({
                                    type: 'POST',
                                    data: formData,
                                    beforeSend: function () {
                                        curBlock.find("#apply-job-submit-" + jobId).before('<progress class="pure-material-progress-linear submit" style="margin-bottom: 20px;"></progress>');
                                    },
                                    async: true,
                                    success: function (result) {
                                        // ... Process the result ...
                                        if (result == 0) {
                                            alert("ERROR: Sorry but there was some problem. Please try again!");


                                        } else {
                                            //  debugger;
                                            var resArray = JSON.parse(result);
                                            $('#apply-job-upload-form-' + jobId).hide();
                                            $(".apply-job-upload-response.apply-job-upload-form").show();

                                            //   showHideDetails(id_pref, 0, item);
                                            location.hash = "";
                                            location.hash = curBlock.attr('id');

                                            // Update files counter if files added
                                            if (typeof (resArray.fileList) !== "undefined" && typeof (resArray.fileList.type) !== "undefined")
                                                switch (resArray.fileList.type) {
                                                    case "cv":
                                                        console.log("cv: " + resArray.fileList.count);
                                                        $('.board-modul-box.cv .board-modul-count').text(resArray.fileList.count);
                                                        break;
                                                    case "file":
                                                        console.log("file: " + resArray.fileList.count);
                                                        $('.board-modul-box.files .board-modul-count').text(resArray.fileList.count);
                                                        break;
                                                    default:
                                                        console.log("No files were uploded");
                                                        break;
                                                }
                                        }
                                    },
                                    cache: false,
                                    contentType: false,
                                    processData: false
                                })
                                .always(function () {
                                    curBlock.find("#apply-job-submit-" + jobId).parent().find('progress.submit').remove();
                                });
                            } else {
                                alert("קובץ קורות חיים לא נבחר:\nעליך לבחור קובץ קורות חיים מהקבצים הקיימים במערכת או להעלות קובץ קורות חיים חדש.");
                            }

                        });

                }
            });

            // Change the file types allowed for selection
            jQuery(document).on("change", "#apply-job-upload-file-type", function (e) {
                //console.log("Meni: Upload File Type: " + $(this).val());
                switch (jQuery(this).val()) {
                    case "cv":
                        options.extensions = ['.pdf', '.doc', '.docx', '.rtf'];
                        jQuery("#apply-job-localFile-" + jobId).attr("accept", "application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/pdf,application/rtf");
                        break;
                    case "file":
                        options.extensions = ['.pdf', '.doc', '.docx', '.rtf', '.jpg', '.png', '.txt', '.ico'];
                        jQuery("#apply-job-localFile-" + jobId).attr("accept", "image/*,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/pdf,application/rtf");
                        break;
                }
            });


        };

        showApplyBlock = function (id_pref, item, state) {
            $('#' + id_pref + 'block' + item.id).removeClass('joblist-short-details').addClass('joblist-long-details');

            var source = "plugins/system/nsoft/assets/js/jobList/html/single-job-details.html";
            jQuery.ajax({
                url: source,
                async: true,
                success: function (data) {
                    longDetailsHtml = $.parseHTML(data);
                    currentBlock = $('#' + id_pref + 'block' + item.id);
                    currentBlock.html(longDetailsHtml);

                    //console.log(item);
                    currentBlock.find('.joblist-apply').remove();

                    currentBlock.find('.joblist-jobName').text(item.name);
                    currentBlock.find('.joblist-jobId').text(item.jobCode);
                    currentBlock.find('.joblist-company').text(item.company);
                    currentBlock.find('.joblist-date').text(item.date);

                    jQuery.ajax({
                        method: "POST",
                        url: "",
                        dataType: 'json',
                        //async: false,
                        data: { type: "get_api", action: "getJobDescription", controller: settings.controller, jobId: item.jobid }
                    })
                    .done(function (res) {

                        if (res.result === "auth required") {
                            alert("Session Expired, please log in again.");
                            location.reload();
                        }

                        var obj = res; //JSON.parse( res );

                        currentBlock.find('.joblist-description').html(obj.Description);
                        currentBlock.find('.joblist-requirement').html(obj.Requirements);
                        currentBlock.find('.joblist-skills').html(obj.Skills);
                        var item = obj.rankList.find(function (elm) { return elm['id'] == obj.Rank; });
                        currentBlock.find('.joblist-rank').html(typeof (item) === 'undefined' ? '' : item.name);

                        // Updates the query string so it can be sent to another user
                        updateParameterUrl('getJobById', obj.JobId);
                    })            
                    .always(function () {
                        $('#' + id_pref + 'block' + item.id).parent().find('progress.apply-block').remove();
                    });

                    currentBlock.find('.joblist-close').text(settings.closeButton);
                    currentBlock.find('.joblist-close').attr("id", id_pref + "close" + item.id).click(function () {
                        showHideDetails(id_pref, 0, item);
                    });
                    //console.log(longDetailsHtml);
                    getApplyBlockData(currentBlock, item, id_pref);

                }
            });

        };

        return $.each(settings.objList.jobs, function (i, item) {
            if (settings.objList.jobs.length <= settings.countPerPage || i < settings.objList.jobs.length - 1) {
                var container = $(document.createElement('li')).addClass("popup_tmb joblist-short-details").attr("id", settings.id_pref + "block" + item.id);
                listContainer.append($(container));

                var source = "plugins/system/nsoft/assets/js/jobList/html/single-job-short.html";
                jQuery.ajax({
                    url: source,
                    async: false,
                    success: function (data) {
                        longDetailsHtml = $.parseHTML(data);
                        currentBlock = listContainer.find('#' + settings.id_pref + 'block' + item.id);
                        currentBlock.html(longDetailsHtml);
                        currentBlock.find('.joblist-jobName').text(item.name);
                        currentBlock.find('.joblist-jobId').text(item.jobCode);
                        currentBlock.find('.joblist-company').text(item.company);
                        currentBlock.find('.joblist-date').text(item.date);
                        currentBlock.find('.joblist-details').text(settings.detailsButton);
                        currentBlock.find('.joblist-details').attr("id", settings.id_pref + "details" + item.id);
                        currentBlock.find('#' + settings.id_pref + "details" + item.id).click(function () {
                            showHideDetails(settings.id_pref, 1, item);
                        });

                        if (item.jobStatus == 3) {
                            currentBlock.find('.joblist-apply').parent().append("<p class='joj-is-closed'>משרה סגורה</p>");
                            currentBlock.find('.joblist-apply').remove();
                        } else if (item.status == 1) {
                            currentBlock.find('.joblist-apply').parent().prepend("<p class='already-applied-job'>הוגשו קורות חיים למשרה זו</p>");
                            currentBlock.find('.joblist-apply').remove();
                        } else {
                            currentBlock.find('.joblist-apply').attr("id", settings.id_pref + "apply" + item.id).click(function () {
                                //settings.applyForJob(item.id)
                                if (item.customerSiteLink.length < 1) showApplyBlock(settings.id_pref, item, 1);
                                else showHideDetails(settings.id_pref, 1, item);
                            });
                            currentBlock.find('.joblist-apply').text(settings.applyButton);
                        }
                    }
                });
            }
        });
    };

    //Dropbox End

    //OneDrive Start



    function openFromSkyDrivePopup(curBlock, jobId) {

        WL.fileDialog({
            mode: 'open',
            select: 'single'
        }).then(
            function (response) {

                var files = response.data.files;
                for (var i = 0; i < files.length; i++) {
                    var file = files[i];

                    $("#apply-job-skydrive-fileName-" + jobId).val(file.name);
                    $("#apply-job-skydrive-fileUrl-" + jobId).val(file.source);
                    //                        $("#apply-job-selected-fileName-" + jobId).text(file.name);
                    //  console.log(file);
                    $("#apply-job-upload-skydrive-" + jobId).addClass("selected");
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

}(jQuery));
