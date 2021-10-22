(function () {
    var $ = jQuery;
    
    function gaSendSuccessSearch(thisId){
        var values = [];
        var profession; //categorySearchId(Popup)
        var target; //jobAreaId(Popup)
        var area; //jobLocationId(Popup)
        
        
        if (thisId === 'searchModId'){// search
            jQuery.each(jQuery('#categorySearchId').parent().find('.ui-multiselect-checkboxes [aria-selected="true"]'),function(){values.push(jQuery(this).next('span').text());});
            profession = values.join('|');
            values = [];

            jQuery.each(jQuery('#jobAreaId').parent().find('.ui-multiselect-checkboxes [aria-selected="true"]'),function(){values.push(jQuery(this).next('span').text());});
            target = values.join('|');
            values = [];

            jQuery.each(jQuery('#jobLocationId').parent().find('.ui-multiselect-checkboxes [aria-selected="true"]'),function(){values.push(jQuery(this).next('span').text());});
            area = values.join('| ');
        }else{// search popup
            jQuery.each(jQuery('#categorySearchIdPopup').parent().find('.ui-multiselect-checkboxes [aria-selected="true"]'),function(){values.push(jQuery(this).next('span').text());});
            profession = values.join('|');
            values = [];

            jQuery.each(jQuery('#jobAreaIdPopup').parent().find('.ui-multiselect-checkboxes [aria-selected="true"]'),function(){values.push(jQuery(this).next('span').text());});
            target = values.join('|');
            values = [];

            jQuery.each(jQuery('#jobLocationIdPopup').parent().find('.ui-multiselect-checkboxes [aria-selected="true"]'),function(){values.push(jQuery(this).next('span').text());});
            area = values.join('|');
        }
        console.log("GA>> profession: " + profession + " target: " + target + " area: " + area );
        ga('send', 'event', 'jobs', 'חיפוש משרה', profession+' - '+area+' - '+target), dataLayer.push({'event':'jobs search','profession':profession ,'target':target ,'area': area});
    }

    function getJobListCont(thisId, obj, countPerPage) {
        var container = $(document.createElement('ul')).attr("id", "jobListContainer");
        container.jobList({
            objList: obj,
            id_pref: thisId,
            controller: "search",
            countPerPage: countPerPage,
            applyForJob: function (itemId) {
                jQuery.ajax({
                    method: "POST",
                    url: "",
                    beforeSend: LoadingIndicator.show,
                    data: {type: "get_api", action: "applyForJobAction", controller: "search", itemId: itemId}
                })
                        .done(function (res) {
                            //alert(res);
                            if (res != null && res.hasOwnProperty("result") && res.result === "auth required")
                            {
                                alert("Session Expired, please log in again.");
                                location.reload();
                            }
                            //console.log('applyForJob:mod_search');
                        });
            }
        });
        return container;
    }

    function getContent(thisId, pageNumJob, countPerPage, isContentChange) {

        if (thisId === "searchModId") {
            searchParams = $('#search-form').serializeArray();
        } else {
            searchParams = $('#search-form-popup').serializeArray();
        }

        var params = {};
        for (var i = 0; i < searchParams.length; i++) {
            var name = searchParams[i].name;
            var value = searchParams[i].value;
            var pos = name.indexOf('[]');

            if (pos != -1) {
                name = name.substr(0, pos);
                if (params[name] === undefined) {
                    params[name] = [];
                }
                params[name].push(value);
            } else if (name.indexOf('multiselect') == -1) {
                params[name] = value;
            }
        }

        jQuery.ajax({
            method: "POST",
            url: "",
            dataType: 'json',
            thisId: thisId, //to decide if modal(searchModId) of form
            beforeSend: LoadingIndicator.show,
            //async: false,
            data: {type: "get_api", action: "getSearchResultAction", controller: "search", lastId: pageNumJob, countPerPage: countPerPage + 1, searchData: params}
        })
                .done(function (res) {
                    $('#get-job-by-id-spinner').remove();

                    if (res != null && res.hasOwnProperty("result") && res.result === "auth required")
                    {
                        alert("Session Expired, please log in again.");
                        location.reload();
                    }
                    //console.log(res.jobs.length);
                    gaSendSuccessSearch(this.thisId);
                    //  var obj = JSON.parse( res );
                    // debugger;
                    obj = res;
                    if (obj.TotalHits == null)
                    {
                        obj.TotalHits = 0;
                    }
                    if (isContentChange) {
                        var content = $(document.createElement('div')).addClass("popup_content");
                        if (obj.jobs.length != 0) {
                            content.html(getJobListCont("searchModId", obj, countPerPage));

                            paginationDiv = $(document.createElement('div')).attr('id', "paginationSearchJobs");
                            paginationDiv.pagination({
                                id_pref: "job_list_",
                                pageNumber: pageNumJob,
                                scrollTo: "popup_div",
                                haveNext: ((obj.jobs.length > countPerPage) ? true : false),
                                ajaxChangePage: function (page) {
                                    pageNumCat = page;
                                    getContent(thisId, page, countPerPage, true);
                                }
                            });

                            content.append(paginationDiv);
                        } else {
                            var noResult = $(document.createElement('p')).addClass("popup_noresult");
                            noResult.html("לא נמצאו משרות מתאימות");
                            content.append(noResult);
                        }

                        $('#popup_div.search').find(".popup_content").html(content);

                        setSearchCount($('#popup_div.search').find(".popup_head").find(".count_placeholder"), obj.TotalHits);
                    } else {
                        var allContent = $(document.createElement('div')).addClass('search').attr({'id': "popup_div", "dir": "rtl"});

                        var source = "plugins/system/nsoft/assets/js/jobList/html/search-result-head.html";
                        $.get(source, function (data) {
                            longDetailsHtml = $(data);
                            //  longDetailsHtml.append($(document.createElement('div')).attr('id', "search_module_popup").html($("#search_modul").html()));
                            longDetailsHtml.find("#searchModIdPopup").click(function () {
                                getContent($(this).attr('id'), 0, countPerPageSearch, true);
                            });
                            longDetailsHtml.find(".search-modul-advanced-search-button").click(function () {

                                location.hash = " ";
                                $("#mod_searcha_advanced_section").css('display', 'inline-block');
                                location.hash = "#mod_searcha_advanced_section";

                            });

                            longDetailsHtml.find("#jobAreaIdPopup").append($("#jobAreaId").html());
                            longDetailsHtml.find("#categorySearchIdPopup").append($("#categorySearchId").html());
                            longDetailsHtml.find("#jobLocationIdPopup").append($("#jobLocationId").html());
                            longDetailsHtml.find("#keyword_search_popup").val($("#keyword_search").val());

                            allContent.html(longDetailsHtml);

                            var content = $(document.createElement('div')).addClass("popup_content");

                            if (obj.jobs.length != 0) {
                                content.html(getJobListCont(thisId, obj, countPerPage));

                                paginationDiv = $(document.createElement('div')).attr('id', "paginationSearchJobs");
                                paginationDiv.pagination({
                                    id_pref: "job_list_",
                                    pageNumber: pageNumJob,
                                    scrollTo: "popup_div",
                                    haveNext: ((obj.jobs.length > countPerPage) ? true : false),
                                    ajaxChangePage: function (page) {
                                        pageNumCat = page;
                                        getContent(thisId, page, countPerPage, true);
                                    }
                                });
                                content.append(paginationDiv);
                            } else {
                                var noResult = $(document.createElement('p')).addClass("popup_noresult");
                                noResult.html("לא נמצאו משרות מתאימות");
                                content.append(noResult);
                            }

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

                            allContent.find('.search-modul-wrap select.selectmenu').each(function () {
                                var text = "";
                                if ($(this).children().length > 0) {
                                    text = $(this).children().first().text();
                                    $(this).children().first().remove();
                                }
                                $(this).multiselect({
                                    appendTo: $(this).parent().get(0),
                                    header: "",
                                    noneSelectedText: text
                                }).multiselectfilter({
                                    label: ""
                                });
                            });

                            setSearchCount(allContent.find(".popup_head").find(".count_placeholder"), obj.TotalHits);

                        });


                    }




                });
    }


    function setSearchCount(where, count)
    {
        where.html(count);
    }

    $(document).ready(function () {
        $('#search_modul .search-modul-wrap select.selectmenu').each(function () {
            var text = "";
            if ($(this).children().length > 0) {
                text = $(this).children().first().text();
                $(this).children().first().remove();
            }
            $(this).multiselect({
                appendTo: $(this).parent().get(0),
                //  header: "",
                noneSelectedText: text
            }).multiselectfilter({
                label: ""
            });
        });

        $(".search-modul-advanced-search-button").click(function () {
            location.hash = " ";
            $("#mod_searcha_advanced_section").css('display', 'inline-block');
            location.hash = "#mod_searcha_advanced_section";
        });

//             $("#searchModId").removeClass('disabled').click(function () {
//                        getContent($(this).attr('id'), 0, countPerPageSearch, false);
//                    });
//

        jQuery.ajax({
            method: "POST",
            url: "",
            dataType: 'json',
            //async: false,
            beforeSend: function() {
                var getJobByIdVal = parseInt(getParameterByName('getJobById'));
                if ( !isNaN(getJobByIdVal) ) {
                    jQuery('#search_modul input.search-modul-keyword').val(getJobByIdVal);
                    $('#mod_nsoft_section').prepend('<p id="get-job-by-id-spinner" style="text-align:center;padding-top: 15px;"><img src="/images/ajax-loader.gif"></p>');
                    getContent('searchModId', 0, 10, false);
                    jQuery('#search_modul input.search-modul-keyword').val('');
                }
            },
            data: {type: "get_api", action: "getSearchOptionsAction", controller: "search"}
        })
                .done(function (res) {

                    if (res != null && res.hasOwnProperty("result") && res.result === "auth required")
                    {
                        alert("Session Expired, please log in again.");
                        location.reload();
                    }
                    //   var obj = JSON.parse( res );
                    //  console.log(res);
                    $.each(res.jobArea, function (i, item) {
                        $("#jobAreaId").append(new Option(item.name, item.id));
                    });

                    $.each(res.categoryList, function (i, item) {
                        $("#categorySearchId").append(new Option(item.name, item.id));
                    });

                    $.each(res.jobLocations, function (i, item) {
                        $("#jobLocationId").append(new Option(item.name, item.id));
                    });

                    $("#searchModId").removeClass('disabled').click(function () {
                        getContent($(this).attr('id'), 0, countPerPageSearch, false);
                    });

                    $('#search_modul .search-modul-wrap select.selectmenu').multiselect("refresh");

                    /**
                     *  If the query string contain "getJobById" param,
                     *  will get the jobid and display to the user onload
                     *  after the search module is ready
                     */
//                    var getJobByIdVal = parseInt(getParameterByName('getJobById'));
//                    if ( !isNaN(getJobByIdVal) ) {
//                        jQuery('#search_modul input.search-modul-keyword').val(getJobByIdVal);
//                        jQuery('#searchModId').click();
//                        jQuery('#search_modul input.search-modul-keyword').val('');
//                    }
                });
    });
})();

