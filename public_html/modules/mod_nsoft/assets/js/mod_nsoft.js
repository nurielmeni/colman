var LoadingIndicator = LoadingIndicator || (function ($, id) {
  function show() {
    $('#' + id).show();
  }

  function hide() {
    $('#' + id).hide();
  }

  $(document).ajaxStart(show);
  $(document).ajaxSend(show);
  $(document).ajaxStop(hide);

  return {
    show: show,
    hide, hide
  }
})(jQuery, 'progress-loading');

(function () {
  var $ = jQuery;

  getBoardComponentsCounts();
  // $.ajax({
  //   method: "POST",
  //   url: "",
  //   dataType: "json",
  //   data: { type: "get_api", action: "myAction", controller: "jobs", component: "new-jobs" },
  // }).done(
  //   function (res) {
  //     if (
  //       res != null &&
  //       res.hasOwnProperty("result") &&
  //       res.result === "auth required"
  //     ) {
  //       alert("Session Expired, please log in again.");
  //       location.reload();
  //     }
  //     //console.log(res);
  //     var obj = res;

  //     $("#filled-jobs .board-modul-count").html(obj.applayedJobsCount);
  //     if ($(".popup_head_numb #filled-jobs_popup").length > 0) {
  //       $(".popup_head_numb #filled-jobs_popup").html(
  //         $("#filled-jobs .board-modul-count").html()
  //       );
  //     }

  //     $("#new-jobs .board-modul-count").html(obj.newJobsCount);
  //     if ($(".popup_head_numb #new-jobs_popup").length > 0) {
  //       $(".popup_head_numb #new-jobs_popup").html(
  //         $("#new-jobs .board-modul-count").html()
  //       );
  //     }

  //     $("#cv .board-modul-count").html(obj.resumeCount);
  //     if ($(".popup_head_numb #cv_popup").length > 0) {
  //       $(".popup_head_numb #cv_popup").html(
  //         $("#cv .board-modul-count").html()
  //       );
  //     }
  //     $("#files .board-modul-count").html(obj.fileCount);
  //     if ($(".popup_head_numb #files_popup").length > 0) {
  //       $(".popup_head_numb #files_popup").html(
  //         $("#files .board-modul-count").html()
  //       );
  //     }
  //     $(".board-modul-title-container .board-modul-total-jobs span").html(
  //       obj.totalJobsCount
  //     );
  //   }
  // ).fail(function (e) {
  //   console.log("JOBS: myAction: fail:", e);
  // });

  function getBoardComponentsCounts() {
    getComponent('resumeCount', 'cv');
    getComponent('applayedJobsCount', 'filled-jobs');
    getComponent('fileCount', 'files');
    getComponent('newJobsCount', 'new-jobs');
    getComponent('totalJobsCount', 'total');
  }

  function getComponent(component, id) {
    $.ajax({
      method: "POST",
      url: "",
      dataType: "json",
      data: { type: "get_api", action: "myAction", controller: "jobs", component: component },
      beforeSend: LoadingIndicator.show
    })
      .done(function (res) {
        if (
          res != null &&
          res.hasOwnProperty("result") &&
          res.result === "auth required"
        ) {
          alert("Session Expired, please log in again.");
          location.reload();
        }

        if (id === 'total') {
          $(".board-modul-title-container .board-modul-total-jobs span").html(res.TotalHits);
        } else {
          updateBoardCounter(id, res);
        }
      })
      .fail(function (e) {
        console.log("Board: component: ", component, e);
      });
  }

  function updateBoardCounter(id, res) {
    $("#" + id + " .board-modul-count").html(res);
    if ($(".popup_head_numb #" + id + "_popup").length > 0) {
      $(".popup_head_numb #" + id + "_popup").html(
        $("#" + id + " .board-modul-count").html()
      );
    }
  }

  function getJobListCont(thisId, obj, countPerPage) {
    var container = $(document.createElement("ul")).attr(
      "id",
      "jobListContainer"
    );
    container.jobList({
      objList: obj,
      id_pref: thisId,
      controller: "jobs",
      countPerPage: countPerPage,
      applyForJob: function (itemId) {
        jQuery
          .ajax({
            method: "POST",
            url: "",
            data: {
              type: "get_api",
              action: "applyForJobAction",
              controller: "jobs",
              itemId: itemId,
            },
            beforeSend: LoadingIndicator.show
          })
          .done(function (res) {
            if (
              res != null &&
              res.hasOwnProperty("result") &&
              res.result === "auth required"
            ) {
              alert("Session Expired, please log in again.");
              location.reload();
            }
            //console.log('applyForJob:mod_nsoft');
            //alert(res);
          });
      },
    });
    return container;
  }

  function getFileListCont(thisId, obj, countPerPage, pageNumJob) {
    var container = $(document.createElement("ul")).attr(
      "id",
      "file_list_container"
    );
    container.fileList({
      objList: obj,
      id_pref: thisId,
      countPerPage: countPerPage,
      downloadFunction: function (itemId) { },
      //            uploadFunction: function (formId) {
      //                var formData = new FormData(formId[0]);
      //
      //                $.ajax({
      //                    type: 'POST',
      //                    data: formData,
      //                    //async: false,
      //                    success: function (result) {
      //                        // ... Process the result ...
      //                        //alert(result);
      //                        var res =result;
      //                        if (res != null && res.hasOwnProperty("result") && res.result === "auth required")
      //                        {
      //                            alert("Session Expired, please log in again.");
      //                            location.reload();
      //                        }
      //                    },
      //                    cache: false,
      //                    contentType: false,
      //                    processData: false
      //                });
      //            },
      deleteFunction: function (itemId) {
        jQuery
          .ajax({
            method: "POST",
            url: "",
            data: {
              type: "get_api",
              action: "deleteFileAction",
              controller: "jobs",
              itemId: itemId,
              cont_type: thisId,
            },
            beforeSend: LoadingIndicator.show
          })
          .done(function (res) {
            if (
              res != null &&
              res.hasOwnProperty("result") &&
              res.result === "auth required"
            ) {
              alert("Session Expired, please log in again.");
              location.reload();
            }
            $("#" + thisId + "_popup").text(res);
            $("#" + thisId + " .board-modul-count").text(res);
            getContent(thisId, pageNumJob, countPerPage, true);
          });
      },
    });
    return container;
  }

  function uploadFunction(formId, thisId) {
    var formData = new FormData(formId[0]);

    $.ajax({
      type: "POST",
      data: formData,
      //async: false,
      success: function (result) {
        // ... Process the result ...
        //alert(result);
        var res = result;
        if (
          res != null &&
          res.hasOwnProperty("result") &&
          res.result === "auth required"
        ) {
          alert("Session Expired, please log in again.");
          location.reload();
        }
        $("#" + thisId + "_popup").text(JSON.parse(result).fileList.count);
        $("#" + thisId + " .board-modul-count").text(
          JSON.parse(result).fileList.count
        );
        getContent(thisId, 0, 10, true);
      },
      beforeSend: LoadingIndicator.show,
      cache: false,
      contentType: false,
      processData: false,
    });
  }

  function getContent(thisId, pageNumJob, countPerPage, isContentChange) {
    jQuery
      .ajax({
        method: "POST",
        url: "",
        dataType: "json",
        //async: false,
        data: {
          type: "get_api",
          action: "getJobContentAction",
          controller: "jobs",
          cont_type: thisId,
          lastId: pageNumJob,
          countPerPage: countPerPage + 1,
        },
        beforeSend: LoadingIndicator.show
      })
      .fail(function (res) {
        alert(res);
        console.log(JSON.stringify(res));
      })
      .done(function (res) {
        if (
          res != null &&
          res.hasOwnProperty("result") &&
          res.result === "auth required"
        ) {
          alert("Session Expired, please log in again.");
          location.reload();
        }
        console.log(res);
        //  var obj = JSON.parse( res );
        //  debugger;
        obj = res;

        if (isContentChange) {
          var content = $(document.createElement("div")).addClass(
            "popup_content"
          );
          if (thisId == "filled-jobs" || thisId == "new-jobs") {
            content.html(getJobListCont(thisId, obj, countPerPage));
            paginationDiv = $(document.createElement("div")).attr(
              "id",
              "paginationContJobs"
            );
            paginationDiv.pagination({
              id_pref: "job_list_",
              pageNumber: pageNumJob,
              scrollTo: "popup_div",
              haveNext: obj.jobs.length > countPerPage ? true : false,
              ajaxChangePage: function (page) {
                pageNumJob = page;
                getContent(thisId, page, countPerPage, true);
              },
            });
            content.append(paginationDiv);
          } else {
            content.html(
              getFileListCont(thisId, obj, countPerPage, pageNumJob)
            );
            if (thisId != "cv") {
              paginationDiv = $(document.createElement("div")).attr(
                "id",
                "paginationContFiles"
              );
              paginationDiv.pagination({
                id_pref: "file_list_",
                pageNumber: pageNumJob,
                scrollTo: "popup_div",
                haveNext: obj.files.length > countPerPage ? true : false,
                ajaxChangePage: function (page) {
                  pageNumJob = page;
                  getContent(thisId, page, countPerPage, true);
                },
              });
              content.append(paginationDiv);
            }
          }

          $("#popup_div." + thisId)
            .find(".popup_content")
            .html(content);
        } else {
          var allContent = $(document.createElement("div"))
            .addClass(thisId)
            .attr({ id: "popup_div", dir: "rtl" });

          if (thisId == "filled-jobs" || thisId == "new-jobs") {
            var source =
              "plugins/system/nsoft/assets/js/jobList/html/job-list-head.html";
            $.get(source, function (data) {
              longDetailsHtml = $(data);

              allContent.html(longDetailsHtml);
              allContent
                .find(".popup_head_numb")
                .attr("id", thisId + "_popup")
                .html($("#" + thisId + " .board-modul-count").html());
              allContent
                .find(".popup_head_text")
                .attr("id", thisId + "_popup")
                .html($("#" + thisId + " .board-modul-box-name").html());

              // Appends the Agent Button
              var agentButton = $(
                "<button class=\"popup_btn agent pull-left\" onClick=\"document.getElementsByClassName('popup_close')[0].click();document.getElementById('menu_mod_searcha_advanced_section').click();\">סוכן חכם</button>"
              );

              if (thisId == "new-jobs")
                allContent.find(".popup_head").append(agentButton);
              var content = $(document.createElement("div")).addClass(
                "popup_content"
              );

              content.html(getJobListCont(thisId, obj, countPerPage));

              paginationDiv = $(document.createElement("div")).attr(
                "id",
                "paginationContJobs"
              );
              paginationDiv.pagination({
                id_pref: "job_list_",
                pageNumber: pageNumJob,
                scrollTo: "popup_div",
                haveNext: obj.jobs.length > countPerPage ? true : false,
                ajaxChangePage: function (page) {
                  pageNumJob = page;
                  getContent(thisId, page, countPerPage, true);
                },
              });

              content.append(paginationDiv);
              allContent.append(content);

              $.magnificPopup.open(
                {
                  items: {
                    src: allContent,
                  },
                  type: "inline",
                },
                0
              );

              allContent.find(".popup_close").click(function (e) {
                e.preventDefault();
                $.magnificPopup.close();
              });
            });
          } else {
            var source =
              "plugins/system/nsoft/assets/js/fileList/html/file-list-template.html";
            $.get(source, function (data) {
              longDetailsHtml = $(data);
              allContent.html(longDetailsHtml);
              allContent
                .find(".popup_head_numb")
                .attr("id", thisId + "_popup")
                .html($("#" + thisId + " .board-modul-count").html());
              allContent
                .find(".popup_head_text")
                .attr("id", thisId + "_popup")
                .html($("#" + thisId + " .board-modul-box-name").html());

              allContent
                .find(".popup_head .popup_btn.upload")
                .on("click", function () {
                  allContent.find("#uploadFile").click();
                });

              allContent.find("#uploadFile").on("change", function () {
                if ($(this).val().length > 0)
                  uploadFunction(
                    allContent.find("#popupUploadFileForm"),
                    thisId
                  );
                $(this).val("");
              });

              var content = $(document.createElement("div")).addClass(
                "popup_content"
              );
              content.html(
                getFileListCont(thisId, obj, countPerPage, pageNumJob)
              );

              if (thisId == "cv") {
                allContent.find("input#isCV").val("true");
              } else {
                // Set the isCV filed
                allContent.find("input#isCV").val("false");
                paginationDiv = $(document.createElement("div")).attr(
                  "id",
                  "paginationContFiles"
                );
                paginationDiv.pagination({
                  id_pref: "file_list_",
                  pageNumber: pageNumJob,
                  scrollTo: "popup_div",
                  haveNext: obj.files.length > countPerPage ? true : false,
                  ajaxChangePage: function (page) {
                    pageNumJob = page;
                    getContent(thisId, page, countPerPage, true);
                  },
                });

                content.append(paginationDiv);
              }

              allContent.append(content);

              $.magnificPopup.open(
                {
                  items: {
                    src: allContent,
                  },
                  type: "inline",
                },
                0
              );

              allContent.find(".popup_close").click(function (e) {
                e.preventDefault();
                $.magnificPopup.close();
              });
            });
          }
        }
      });
  }

  $(document).ready(function () {
    $(".board-modul-box").click(function () {
      getContent($(this).attr("id"), 0, countPerPageNSoft, false);
    });
  });
})();
