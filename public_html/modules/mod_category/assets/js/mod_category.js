(function () {
    var $ = jQuery;

    function getJobListContByCat(obj, countPerPage) {
        var container = $(document.createElement('ul')).attr("id", "category_list_container");
        container.jobList({
            objList: obj,
            id_pref: "cat_",
            controller: "category",
            countPerPage: countPerPage,
            applyForJob: function (itemId) {
                //console.log("GA>> ", itemId);
                jQuery.ajax({
                    method: "POST",
                    url: "",
                    data: {type: "get_api", action: "applyForJobAction", controller: "category", itemId: itemId}
                })
                        .done(function (res) {

                            if (res != null && res.hasOwnProperty("result") && res.result === "auth required")
                            {
                                alert("Session Expired, please log in again.");
                                location.reload();
                            }
                            //console.log('applyForJob:mod_category');
                            //alert(res);
                        });
            }
        });

        return container;
    }
    function setSearchCount(where, count)
    {
        where.show();
        where.html(count);
    }
    function getJobContentByCat(catId, pageNumCat, countPerPage, catBlock, isContentChange) {

        jQuery.ajax({
            method: "POST",
            url: "",
            dataType: 'json',
            //async: false,
            data: {type: "get_api", action: "getContentAction", controller: "category", catId: catId, lastId: pageNumCat, countPerPage: countPerPage + 1}
        })
                .done(function (res) {



                    if (res != null && res.hasOwnProperty("result") && res.result === "auth required")
                    {
                        alert("Session Expired, please log in again.");
                        location.reload();
                    }

                    console.log(res);

                    obj = res;
                    if (obj.TotalHits == null)
                    {
                        obj.TotalHits = 0;
                    }
                    if (isContentChange) {
                        var content = $(document.createElement('div')).addClass("popup_content");
                        if (obj.jobs.length != 0) {
                            var content = $(document.createElement('div')).addClass("popup_content");

                            content.html(getJobListContByCat(obj, countPerPage));

                            paginationDiv = $(document.createElement('div')).attr('id', "paginationContCat");
                            paginationDiv.pagination({
                                id_pref: "cat_",
                                pageNumber: pageNumCat,
                                haveNext: ((obj.jobs.length > countPerPage) ? true : false),
                                scrollTo: "popup_div",
                                ajaxChangePage: function (page) {
                                    pageNumCat = page;
                                    getJobContentByCat(catId, page, countPerPage, catBlock, true);
                                }
                            });

                            content.append(paginationDiv);

                        } else {
                            var noResult = $(document.createElement('p')).addClass("popup_noresult");
                            noResult.html("לא נמצאו משרות מתאימות");
                            content.append(noResult);
                        }


                        $('#popup_div.category').find(".popup_content").html(content);
                        setSearchCount($('#popup_div.search').find(".popup_head").find(".count_placeholder"), obj.TotalHits);
                    } else {
                        var allContent = $(document.createElement('div')).addClass("category").attr({'id': "popup_div", "dir": "rtl"});

                        var source = "plugins/system/nsoft/assets/js/jobList/html/job-list-head.html";
                        $.get(source, function (data) {
                            longDetailsHtml = $(data);

                            allContent.html(longDetailsHtml);
                            allContent.find('.popup_head_numb').text(catBlock.text().trim());

                            var content = $(document.createElement('div')).addClass("popup_content");

                            content.html(getJobListContByCat(obj, countPerPage));

                            paginationDiv = $(document.createElement('div')).attr('id', "paginationContCat");
                            paginationDiv.pagination({
                                id_pref: "cat_",
                                pageNumber: pageNumCat,
                                haveNext: ((obj.jobs.length > countPerPage) ? true : false),
                                scrollTo: "popup_div",
                                ajaxChangePage: function (page) {
                                    pageNumCat = page;
                                    getJobContentByCat(catId, page, countPerPage, catBlock, true);
                                }
                            });

                            content.append(paginationDiv);
                            allContent.append(content);

                            $.magnificPopup.open({
                                items: {
                                    src: allContent
                                },
                                type: 'inline'
                            }, 0);

                            allContent.find('.popup_close').click(function (e) {
                                e.preventDefault();
                                $.magnificPopup.close();
                            });

                            setSearchCount(allContent.find(".popup_head").find(".count_placeholder"), obj.TotalHits);
                        });
                    }
                });
    }

    $(document).ready(function () {
        // $(".board-modul-content .board-modul-count").each(function (index) {
        //     $(this).html("<img src='modules/mod_nsoft/assets/images/ajax-loader.gif'>");
        // });

        $(".domain-modul-box").click(function () {
            id = $(this).attr('id');
            getJobContentByCat(id, 0, countPerPageCat, $(this), false);
        });
    });
})();