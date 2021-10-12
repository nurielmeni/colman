(function ($) {
    var idNum = 1;
    $.fn.fileList = function (options) { // Plugin options default values

        var settings = $.extend({
            "id_pref": "",
            "objList": "",
            "countPerPage": 10,
            "type": "get_api",
            "controller": "jobs",
            "downloadButton": "הורד קובץ",
            "uploadButton": "העלה קובץ",
            "deleteButton": "מחק קובץ",
            downloadFunction: function (fileId) {
            },
//            uploadFunction: function (fileId) {
//            },
            deleteFunction: function (fileId) {
            }
        }, options);


        var listContainer = this;

        listContainer.html("");




        $.fn.fileList.closeWindow = function () {
            listContainer.hide();

        };

        var itemSource = "plugins/system/nsoft/assets/js/fileList/html/file-item-template.html";

        return $.each(settings.objList.files, function (i, item) {
            if (settings.objList.files.length <= settings.countPerPage || i < settings.objList.files.length - 1 || settings.id_pref == "cv") {
                var container = $(document.createElement('li')).addClass("popup_tmb").attr("id", "file-block" + item.id);
                listContainer.append($(container));
                jQuery.ajax({url: itemSource,
                    async: false,
                    success: function (data) {

                        longDetailsHtml = $(data);
                        currentBlock = listContainer.find('#file-block' + item.id);
                        currentBlock.html(longDetailsHtml);
                        if (settings.id_pref == "cv") {
                            fileName = item.date + " " + item.name;
                        } else {
                            fileName = item.name;
                        }

                        currentBlock.find("#file_id").attr('id', 'file_id_' + item.id).val(item.name);
                        currentBlock.find(".popup_filename").html(fileName);
                        currentBlock.find(".popup_tmb_date").text(item.date);
                        currentBlock.find(".popup_tmb_time").html(item.time);
//                        currentBlock.find("#uploadFile").attr('id', "uploadFile-" + item.id);
                        currentBlock.find("#uploadFileForm").attr('id', "uploadFileForm-" + item.id);
                        currentBlock.find("#type").attr('id', "type-" + item.id);
                        currentBlock.find("#action").attr('id', "action-" + item.id);
                        currentBlock.find("#itemId").attr('id', "itemId-" + item.id).val(item.fileId);
                        currentBlock.find("#controller").attr('id', "controller-" + item.id);
                        currentBlock.find("#isCV").attr('id', "isCV-" + item.id).val((settings.id_pref == "cv" ? true : false));
                        currentBlock.find(".upload").attr('id', 'upload_' + item.id).text(settings.uploadButton).click(function () {

                            $("#uploadFile-" + item.id).click();

                        });
//                        currentBlock.find("#uploadFile-" + item.id).change(function () {
//                            settings.uploadFunction($("#uploadFileForm-" + item.id));
//                        });
                                                
                        currentBlock.find(".download").attr('id', 'download_' + item.id).text(settings.downloadButton).click(function (e) {

                            document.location.href = '?type=get_api&action=downloadFileAction&controller=jobs&itemId=' + item.fileId;
                            e.preventDefault();
                            // settings.downloadFunction(item.fileId);
                        });
                        currentBlock.find(".delete").attr('id', 'delete_' + item.id).text(settings.deleteButton).click(function () {
                            settings.deleteFunction(item.fileId);
                        });

                    }
                });
            }

        });
    };
}(jQuery));