<?php
// No direct access
defined('_JEXEC') or die;


?>

<section class="advanced-search-modul" id="mod_searcha_advanced_section" dir="rtl">
    <div class="advanced-search-modul-title-container">
        <h1 class="advanced-search-modul-title">סוכן חכם</h1>
        <div class="advanced-search-modul-horizontal-line"></div>
    </div>

    <div class="advanced-search-modul-content">

        <div class="advanced-search-modul-types">
            <div class="advanced-search-modul-right-container">
                <h2 class="advanced-search-modul-type-title">מילות מפתח</h2>
                <div class="advanced-search-modul-type-horizontal-line"></div>
            </div>

            <div class="advanced-search-modul-left-container">
                <h2 class="advanced-search-modul-type-title">חפש לפי</h2>
                <div class="advanced-search-modul-type-horizontal-line"></div>
            </div>
        </div>

        <form method="post" onsubmit="return false" id="search-advanced-form">
            <input type="hidden" id="advancedType" name="type" value="get_api">
            <input type="hidden" id="advancedAction" name="action" value="getSearchResultAction">
            <input type="hidden" id="advancedController" name="controller" value="searchAdvanced">
            <input type="hidden" id="advancedlastId" name="lastId" value="0">
            <input type="hidden" id="advancedCountPerPage" name="countPerPage">

            <ul class="advanced-search-modul-criterias">
                <li>
                    <div class="advanced-search-modul-right-container">
                        <input class="advanced-search-modul-keywords"  disabled name="keyWordsAdvanced[]" placeholder="חפש לפי מילת מפתח: “מנהל לקוחות” “מנהל פרויקטים” “מנהל מוצר”...">
                    </div>

                    <div class="advanced-search-modul-left-container">
                        <div class="advanced-search-modul-wrap advanced-search-modul-skin-search">
                            <select class="selectmenu" disabled name="searchAdvancedType[]">
                                <option value="OneOrMore">מצא לפחות אחד</option>
				<option value="All">מצא הכל</option>
                                <option value="Optional">אופציונלי</option>
                                <option value="ExcludeNumeric">חייב לא להיות </option>
                            </select>
                        </div>
                        <button  class="advanced-search-modul-remove advanced-search-modul-criteria-button">−</button>
                    </div>
                </li>

                <li>
                    <div class="advanced-search-modul-right-container">
                        <input class="advanced-search-modul-keywords" name="keyWordsAdvanced[]" placeholder="חפש לפי מילת מפתח: “מנהל לקוחות” “מנהל פרויקטים” “מנהל מוצר”...">
                    </div>

                    <div class="advanced-search-modul-left-container">
                        <div class="advanced-search-modul-wrap advanced-search-modul-skin-search">
                            <select class="selectmenu" name="searchAdvancedType[]">
                               <option value="OneOrMore">מצא לפחות אחד</option>
				<option value="All">מצא הכל</option>
                                <option value="Optional">אופציונלי</option>
                                <option value="ExcludeNumeric">חייב לא להיות </option>
                            </select>
                        </div>
                        <button  class="advanced-search-modul-remove advanced-search-modul-criteria-button">−</button>
                    </div>
                </li>
                <li>
                    <button class="advanced-search-modul-add advanced-search-modul-criteria-button">+</button>
                </li>
            </ul>

            <div class="advanced-search-modul-select-boxes">
                <div class="advanced-search-modul-right-container">
                    <h2 class="advanced-search-modul-type-title">חיפוש מתקדם</h2>
                    <div class="advanced-search-modul-type-horizontal-line"></div>
                </div>

                <div class="advanced-search-modul-row">
                <!--     <div class="advanced-search-modul-wrap advanced-search-modul-skin-search">
                        <select class="selectmenu" id="companySearchAdvancedId" name="companySearchAdvanced[]" multiple="multiple">
                            <option value="">חברה</option>
                        </select>
                    </div> -->
		    <div class="advanced-search-modul-wrap advanced-search-modul-skin-search">
                        <select class="selectmenu" id="jobLoacationSearchAdvancedId" name="jobLocationSearchAdvanced[]" multiple="multiple">
                            <option value="">מיקום משרה</option>
                        </select>
                    </div>
                    <div class="advanced-search-modul-wrap advanced-search-modul-skin-search">
                        <select class="selectmenu" id="categorySearchAdvancedId" name="categorySearchAdvanced[]" multiple="multiple">
                            <option value="">קטגוריה</option>
                        </select>
                    </div>
                    <div class="advanced-search-modul-wrap advanced-search-modul-skin-search">
                        <select class="selectmenu" id="subCategorySearchAdvancedId" name="subCategorySearchAdvanced[]" multiple="multiple">
                            <option value="">תת קטגוריה</option>
                        </select>
                    </div>
<!--                    <div class="advanced-search-modul-wrap advanced-search-modul-skin-search">
                        <select class="cs-select advanced-search-modul-skin-search" id="jobUpdateDateSearchAdvancedId" name="jobUpdateDateSearchAdvanced" multiple="multiple">
                            <option value="">תאריך עדכון המשרה</option>
                        </select>
                    </div>-->
                    <div class="advanced-search-modul-wrap advanced-search-modul-skin-search">
                        <select class="selectmenu" id="jobTypeSearchAdvancedId" name="jobTypeSearchAdvanced[]" multiple="multiple">
                            <option value="">היקף משרה</option>
                        </select>
                    </div>
                </div>
                <div class="advanced-search-modul-row">
<!--                    <div class="advanced-search-modul-wrap advanced-search-modul-skin-search">
                        <select class="selectmenu" id="employeeTypeSearchAdvancedId" name="employeeTypeSearchAdvanced" multiple="multiple">
                            <option value="">סוג העובד</option>
                        </select>
                    </div>-->
<!--                    <div class="advanced-search-modul-wrap advanced-search-modul-skin-search">
                        <select class="selectmenu" id="expertiseSearchAdvancedId" name="expertiseSearchAdvanced" multiple="multiple">
                            <option value="">מומחיות</option>
                        </select>
                    </div>-->
 <!--                     <div class="advanced-search-modul-wrap advanced-search-modul-skin-search">
                        <select class="selectmenu" id="jobLoacationSearchAdvancedId" name="jobLocationSearchAdvanced[]" multiple="multiple">
                            <option value="">מיקום משרה</option>
                        </select>
                    </div>-->
		    
                </div>
            </div>

            <div class="advanced-search-modul-buttons">
                <div class="advanced-search-modul-wrap">
                    <button id="searchAdvancedModId" class="advanced-search-modul-start-search">חיפוש משרה</button>
                </div>

                <div class="advanced-search-modul-send-smart-agent">
                    <div class="advanced-search-modul-send-checkbox">
                        <input type="checkbox" value="checked" id="advanced-search-modul-send-checkbox" name="sendToAgentSearchAdvanced" />
                        <label for="advanced-search-modul-send-checkbox"></label>
                    </div>
                    <label for="advanced-search-modul-send-checkbox">שמור סוכן חכם</label>
                </div>


<!--                <div class="advanced-search-modul-wrap">
                    <button class="advanced-search-modul-search-type-button">בסיסי</button>
                </div>-->
            </div>
        </form>
    </div>
</section>
