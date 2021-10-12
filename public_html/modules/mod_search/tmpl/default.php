<?php
// No direct access
defined('_JEXEC') or die;


?>
<section class="search-modul" id="search_modul" dir="rtl">
    <form action="#" method="post" id="search-form" onsubmit="return false;">
        <div class="serach-model-criterias">
            <div class="search-modul-basic-search">
                <div class="search-modul-wrap">
                    <input class="search-modul-keyword" type="text" id="keyword_search" name="keyword_search" placeholder="מילת חיפוש">
                </div>
                <div class="search-modul-wrap search-modul-skin-search">
                    <select class="selectmenu" id="categorySearchId" name="category_search[]" multiple="multiple">
                        <option value="" >תחום מקצוע</option>
                    </select>
                </div>
                <div class="search-modul-wrap search-modul-skin-search">
                    <select class="selectmenu" id="jobAreaId" name="jobArea_search[]" multiple="multiple">
                        <option value="" >קהל יעד</option>
                    </select>
                </div>
                <div class="search-modul-wrap search-modul-skin-search">
                    <select class="selectmenu" id="jobLocationId" name="jobLocation_search[]" multiple="multiple">
                        <option value="" >מיקום משרה</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="search-modul-wrap">
            <button class=" search-modul-search-button disabled" id="searchModId">חיפוש משרה</button>
        </div>
        <div class="search-modul-wrap">
            <button class="search-modul-advanced-search-button close">מתקדם</button>
        </div>
    </form>
</section>

