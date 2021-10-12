(function () {
    var $ = jQuery;
    var searchParams = "";
    
    function gaSendSuccessSearch(thisId){
        var values = [];
        var profession; //categorySearchAdvancedId
//        var subProfession; //subCategorySearchAdvancedId
        var type; //jobTypeSearchAdvancedId
        var area; //jobLoacationSearchAdvancedId
        
        
        jQuery.each(jQuery('#categorySearchAdvancedId').parent().find('.ui-multiselect-checkboxes [aria-selected="true"]'),function(){values.push(jQuery(this).next('span').text());});
        profession = values.join('|');
        values = [];

//        jQuery.each(jQuery('#subCategorySearchAdvancedId').parent().find('.ui-multiselect-checkboxes [aria-selected="true"]'),function(){values.push(jQuery(this).next('span').text());});
//        subProfession = values.join('|');
//        values = [];

        jQuery.each(jQuery('#jobTypeSearchAdvancedId').parent().find('.ui-multiselect-checkboxes [aria-selected="true"]'),function(){values.push(jQuery(this).next('span').text());});
        type = values.join('|');
        values = [];

        jQuery.each(jQuery('#jobLoacationSearchAdvancedId').parent().find('.ui-multiselect-checkboxes [aria-selected="true"]'),function(){values.push(jQuery(this).next('span').text());});
        area = values.join('| ');
    
        console.log("GA Smart Agent>> profession: " + profession + " extent: " + type + " area: " + area );
        ga('send', 'event', 'jobs', 'סוכן חכם', area+' - '+type+' - '+profession), dataLayer.push({'event':'jobs search', 'area': area, 'extent':type, 'profession':profession});
    }

    $(document).ready(function () {
        $('.advanced-search-modul-select-boxes .selectmenu').each(function () {
            var text = "";
            if ($(this).children().length > 0) {
                text = $(this).children().first().text();
                $(this).children().first().remove();
            }

            if ($(this).attr('id') === "categorySearchAdvancedId") {

                $(this).multiselect({
                    appendTo: $(this).parent().get(0),
                    //header: "",
                    noneSelectedText: text,
                    change: function (event, ui) {
                        var values = [];
                        for (var i = 0; i < ui.checked.length; i++) {
                            var value = $(ui.checked[i]).val();
                            values.push(value);
                        }

                        if (values.length == 1) {
                            jQuery.ajax({
                                method: "POST",
                                url: "",
                                dataType: 'json',
                                //async: false,
                                data: {type: "get_api", action: "getSubCategoryOptionsAction", controller: "searchAdvanced", categoryId: values[0]}
                            })
                                    .done(function (res) {

                                        if (res != null && res.hasOwnProperty("result") && res.result === "auth required")
                                        {
                                            alert("Session Expired, please log in again.");
                                            location.reload();
                                        }
                                        curObj = $("#subCategorySearchAdvancedId");
                                        if (res.categoryList.length == 0) {
                                            curObj.multiselect('disable');
                                        } else {
                                            curObj.multiselect('enable');
                                        }

                                        $("#subCategorySearchAdvancedId option").not("option:first").remove();

                                        $.each(res.categoryList, function (i, item) {
                                            curObj.append(new Option(item.name, item.id));
                                        });

                                        curObj.multiselect("refresh");
                                    });
                        } else {
                            $("#subCategorySearchAdvancedId").multiselect('disable');
                            $("#subCategorySearchAdvancedId").empty();
                        }
                    }
                }).multiselectfilter({
                    label: ""
                });
            } else {
                $(this).multiselect({
                    appendTo: $(this).parent().get(0),
                    // header: "",
                    noneSelectedText: text
                }).multiselectfilter({
                    label: ""
                });

                if ($(this).attr('id') === "subCategorySearchAdvancedId") {
                    $(this).multiselect('disable');
                }
            }
        });

        $("#menu_mod_searcha_advanced_section").click(function () {
            location.hash = " ";
            $("#mod_searcha_advanced_section").css('display', 'inline-block');
            location.hash = "#mod_searcha_advanced_section";
        });

        $('.advanced-search-modul-criterias li:not(:first-child)').find('.advanced-search-modul-wrap select.selectmenu').each(function () {
            $(this).selectmenu({
                appendTo: $(this).parent().get(0)
            });
        });

        $('.advanced-search-modul-add').click(function () {

            $clone = $(".advanced-search-modul-criterias>li:first").clone();
            $clone.find(".advanced-search-modul-keywords").removeAttr("disabled");
            $clone.find(".selectmenu").removeAttr("disabled");
            $clone.insertBefore(".advanced-search-modul-criterias>li:last");
            $clone.keypress(function (e)
            {
                var key = e.which || e.keyCode;
                if (key === 13) {
                    e.preventDefault();
                }

            });
            $(".advanced-search-modul-criterias>li:nth-last-child(2) .advanced-search-modul-wrap select.selectmenu").each(function () {
                $(this).selectmenu({
                    appendTo: $(this).parent().get(0)
                });
            });
        });

        $(".advanced-search-modul-keywords").keypress(function (e)
        {
            var key = e.which || e.keyCode;
            if (key === 13) {
                e.preventDefault();
            }

        });

        $(".advanced-search-modul-criterias").on("click", '.advanced-search-modul-remove', function () {

            if ($(".advanced-search-modul-criterias>li").size() > 3) {
                $(this).parents('li').remove();
            } else {
                $(this).parents('li').remove();
                $clone = $(".advanced-search-modul-criterias>li:first").clone();
                $clone.find(".advanced-search-modul-keywords").removeAttr("disabled");
                $clone.find(".selectmenu").removeAttr("disabled");
                $clone.insertBefore(".advanced-search-modul-criterias>li:last");
                $(".advanced-search-modul-criterias>li:nth-last-child(2) .advanced-search-modul-wrap select.selectmenu").each(function () {
                    $(this).selectmenu({
                        appendTo: $(this).parent().get(0)
                    });
                });
            }
        });

        $("#searchAdvancedModId").click(function () {
            getJobContentAdvanced($(this).attr('id'), 0, countPerPageSearch, $(this), false);
        });

        $.ajax({
            method: "POST",
            url: "",
            dataType: 'json',
            //async: false,
            data: {type: "get_api", action: "getSearchOptionsAction", controller: "searchAdvanced"}
        })
                .done(function (res) {
                    if (res != null && res.hasOwnProperty("result") && res.result === "auth required")
                    {
                        alert("Session Expired, please log in again.");
                        location.reload();
                    }
//                    $.each(res.jobArea, function (i, item) {
//                        $("#employeeTypeSearchAdvancedId").append(new Option(item.name, item.id));
//                    });

                    $.each(res.categoryList, function (i, item) {
                        $("#categorySearchAdvancedId").append(new Option(item.name, item.id));
                    });

                    $.each(res.companyList, function (i, item) {
                        $("#companySearchAdvancedId").append(new Option(item.name, item.id));
                    });

//                    $.each(res.jobUpdateDateList, function (i, item) {
//                        $("#jobUpdateDateSearchAdvancedId").append(new Option(item.name, item.id));
//                    });

                    $.each(res.jobTypeList, function (i, item) {
                        $("#jobTypeSearchAdvancedId").append(new Option(item.name, item.id));
                    });

//                    $.each(res.expertiseList, function (i, item) {
//                        var start = item.name.indexOf("(");
//                        var end =item.name.indexOf(")");
//                        var id = item.name.substring(start+1, end);
//                        var name = item.name.substring(0, start);
//                        $("#expertiseSearchAdvancedId").append(new Option(name, id));
//                    });

                    $.each(res.jobLocationList, function (i, item) {
                        $("#jobLoacationSearchAdvancedId").append(new Option(item.name, item.id));
                    });

                    $('.advanced-search-modul-select-boxes .selectmenu').multiselect("refresh");
                    $("#subCategorySearchAdvancedId").multiselect('disable');
                });
    });

    function getJobListContAdvanced(obj, countPerPage) {
        var container = $(document.createElement('ul')).attr("id", "search_advanced_list_container");
        container.jobList({
            objList: obj,
            id_pref: "search_advanced_",
            controller: "searchAdvanced",
            countPerPage: countPerPage,
            applyForJob: function (itemId) {
                $.ajax({
                    method: "POST",
                    url: "",
                    data: {type: "get_api", action: "applyForJobAction", controller: "searchAdvanced", itemId: itemId}
                })
                        .done(function (res) {
                            //alert(res);
                            if (res != null && res.hasOwnProperty("result") && res.result === "auth required")
                            {
                                alert("Session Expired, please log in again.");
                                location.reload();
                            }
                            //console.log('applyForJob:mod_search_advanced');
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
    function getJobContentAdvanced(thisId, pageNumAdvanced, countPerPage, searchAdvancedBlock, isContentChange) {

        $("#advancedlastId").val(pageNumAdvanced);
        $("#advancedCountPerPage").val(countPerPage + 1);
        searchParams = $('#search-advanced-form').serialize();

        console.log(searchParams);
        $.ajax({
            method: "POST",
            url: "",
            dataType: 'json',
            //async: false,
            data: searchParams //{type: "get_api", action: "getContentAction", controller: "category", catId: catId, lastId: pageNumCat, countPerPage: countPerPage+1}
        })
                .done(function (res) {
                    if (res != null && res.hasOwnProperty("result") && res.result === "auth required")
                    {
                        alert("Session Expired, please log in again.");
                        location.reload();
                    }

                    console.log(res);
                    gaSendSuccessSearch(this.thisId);
                    //  var obj = JSON.parse( res );
                    //debugger;
                    obj = res;
                    if (obj.TotalHits == null)
                    {
                        obj.TotalHits = 0;
                    }
                    if (isContentChange) {
                        var content = $(document.createElement('div')).addClass("popup_content");
                        if (obj.jobs.length != 0) {
                            content.html(getJobListContAdvanced(obj, countPerPage));
                            // debugger;
                            paginationDiv = $(document.createElement('div')).attr('id', "paginationContAdvanced");
                            paginationDiv.pagination({
                                id_pref: "search_advanced_",
                                pageNumber: pageNumAdvanced,
                                haveNext: ((obj.jobs.length > countPerPage) ? true : false),
                                scrollTo: "popup_div",
                                ajaxChangePage: function (page) {
                                    pageNumAdvanced = page;
                                    getJobContentAdvanced(thisId, page, countPerPage, searchAdvancedBlock, true);
                                }
                            });
                            content.append(paginationDiv);
                        } else {
                            var noResult = $(document.createElement('p')).addClass("popup_noresult");
                            noResult.html("לא נמצאו משרות מתאימות");
                            content.append(noResult);
                        }

                        $('#popup_div.advanced-search').find(".popup_content").html(content);
                        setSearchCount($('#popup_div.search').find(".popup_head").find(".count_placeholder"), obj.TotalHits);
                    } else {
                        var allContent = $(document.createElement('div')).addClass("advanced-search").attr({'id': "popup_div", "dir": "rtl"});

                        var source = "plugins/system/nsoft/assets/js/jobList/html/job-list-head.html";
                        $.get(source, function (data) {
                            longDetailsHtml = $(data);

                            allContent.html(longDetailsHtml);
                            allContent.find('.popup_head_numb').text("תוצאות חיפוש");
                            allContent.find('.popup_head_text').text("");

                            var content = $(document.createElement('div')).addClass("popup_content");
                            if (obj.jobs.length != 0) {
                                content.html(getJobListContAdvanced(obj, countPerPage));
                                // debugger;
                                paginationDiv = $(document.createElement('div')).attr('id', "paginationContAdvanced");
                                paginationDiv.pagination({
                                    id_pref: "search_advanced_",
                                    pageNumber: pageNumAdvanced,
                                    haveNext: ((obj.jobs.length > countPerPage) ? true : false),
                                    scrollTo: "popup_div",
                                    ajaxChangePage: function (page) {
                                        pageNumAdvanced = page;
                                        getJobContentAdvanced(thisId, page, countPerPage, searchAdvancedBlock, true);
                                    }
                                });
                                content.append(paginationDiv);
                            } else {
                                var noResult = $(document.createElement('p')).addClass("popup_noresult");
                                noResult.html("לא נמצאו משרות מתאימות");
                                content.append(noResult);
                            }

                            allContent.append(content);

                            if (pageNumAdvanced == 0) {
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
                            }

                            setSearchCount(allContent.find(".popup_head").find(".count_placeholder"), obj.TotalHits);
                        });
                    }
                });
    }
})();
