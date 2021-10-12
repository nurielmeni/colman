(function ($) {
    $.pagination = function (el, options) {
        // To avoid scope issues, use 'base' instead of 'this'
        // to reference this class from internal events and functions.
        var base = this;

        // Access to jQuery and DOM versions of element
        base.$el = $(el);
        base.el = el;

        // Add a reverse reference to the DOM object
        base.$el.data("pagination", base);

        base.init = function () {
            base.options = $.extend({}, $.pagination.defaultOptions, options);
            
            base.$el.addClass("pagination-container");

            if (base.options.pageNumber > 0) {
                var newElem = $("<button/>").attr("id", base.options.id_pref + "prev").addClass("pagination-previous-job").text(base.options.previousButton);
                newElem.click(function () {
                    _goPrevious();
                });
                base.$el.append(newElem);
            }

            if (base.options.haveNext === true) {
                var newElem = $("<button/>").attr("id", base.options.id_pref + "next").addClass("pagination-next-job").text(base.options.nextButton);
                newElem.click(function () {
                    _goNext();
                });
                base.$el.append(newElem);
            }
        };

        var _goPrevious = function () {
            base.options = $.extend({}, $.pagination.defaultOptions, options);
            base.options.pageNumber--;
            base.options.ajaxChangePage(base.options.pageNumber);
        };

        var _goNext = function () {
            base.options = $.extend({}, $.pagination.defaultOptions, options);
            base.options.pageNumber++;
            base.options.ajaxChangePage(base.options.pageNumber);
        };

        base.init();
    };

    $.pagination.defaultOptions = {
        id_pref: "",
        haveNext: false,
        pageNumber: 0,
        scrollTo: "popup_div",
        ajaxChangePage: function (page) {

        },
        nextButton: "הבא>>",
        previousButton: "<<קודם"
    };

    $.fn.pagination = function (options) {
        return this.each(function () {
            (new $.pagination(this, options));
        });
    };

})(jQuery);