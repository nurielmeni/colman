<?php
// No direct access
defined('_JEXEC') or die;
?>

<section class="resume-modul" id="mod_resume_section" dir="rtl">
    <div class="resume-modul-title-container">
        <h1 class="resume-modul-title">העלאת קורות חיים או קבצים</h1>
        <div class="resume-modul-horizontal-line"></div>
        <h2 class="resume-modul-subtitle ">למאגר המרכז לניהול קריירה</h2>
    </div>
    <form  method="post" enctype="multipart/form-data" id="resume-modul-upload-form" onsubmit="return false">
        <input type="hidden" id="type" name="type" value="get_api">
        <input type="hidden" id="controller" name="controller" value="uploadResume">
        <input type="hidden" id="action" name="action" value="uploadResume">
        <div class="resume-module-submit-response resume-modul-content" style="display: none">נשלח בהצלחה!</div>
        <ul class="resume-modul-content">
            <li class="resume-modul-content-container">
                <div class="resume-modul-box" style="display:none">
                    <div class="resume-modul-top">
                        <p class="resume-modul-number">1</p>
                        <div>
                            <p class="resume-modul-text">מלא פרטים</p>
                        </div>
                    </div>
                    <div class="resume-modul-bottom">
                        <input type="text" id="resume-modul-fullname" name="resume-modul-fullname" required class="resume-modul-input" placeholder="שם מלא">
                        <input type="text" id="resume-modul-idnumber" name="resume-modul-idnumber" required class="resume-modul-input" placeholder="מספר תעודת זהות">
                    </div>
                </div>
            </li>

            <li class="resume-modul-content-container">
                <div class="resume-modul-box">
                    <div class="resume-modul-top">
                        <p class="resume-modul-number">1</p>
                        <div>
                            <p class="resume-modul-text">בחר קובץ קו"ח או אחר</p>
                        </div>
                    </div>
                    <div class="resume-modul-bottom">
                        <select id="apply-job-upload-file-type" class="apply-job-upload-select selectmenu" name="apply-job-upload-file-type" >
                            <option value="cv"> קובץ קו”ח</option>
                            <option value="file">אחר</option>
                        </select>
                    </div>
                </div>
            </li>

            <li class="resume-modul-content-container">
                <div class="resume-modul-box">
                    <div class="resume-modul-top">
                        <p class="resume-modul-number">2</p>
                        <div class="resume-modul-nopadding">
                            <p class="resume-modul-text">בחר את הקובץ להעלאה</p>
                            <p class="resume-modul-subtext">מקומי או משירות אחסון</p>
                        </div>
                    </div>
                    <div class="resume-modul-bottom">
                        <span id="resume-modul-selected-fileName"></span>

                        <input type="file" name="resume-modul-localFile" id="resume-modul-localFile" class="resume-modul-localFile"
                               accept="application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/pdf,application/rtf">
                        <input type="hidden" name="resume-modul-dropbox-fileUrl" id="resume-modul-dropbox-fileUrl"/>
                        <input type="hidden" name="resume-modul-dropbox-fileName" id="resume-modul-dropbox-fileName"/>
                        <input type="hidden" name="resume-modul-skydrive-fileUrl" id="resume-modul-skydrive-fileUrl"/>
                        <input type="hidden" name="resume-modul-skydrive-fileName" id="resume-modul-skydrive-fileName"/>

                        <a href="javascript:void(0)" id="resume-modul-upload-computer" class="apply-job-upload"><span class="apply-job-upload-icon"></span><span class="apply-job-upload-text">העלה קובץ מהמחשב</span></a>
                        <a href="javascript:void(0)" id="resume-modul-upload-dropbox" class="apply-job-upload"><span class="apply-job-upload-icon"></span><span class="apply-job-upload-text">העלה קובץ מהדרופבוקס</span></a>
                        <a href="javascript:void(0)" id="resume-modul-upload-skydrive" class="apply-job-upload"><span class="apply-job-upload-icon"></span><span class="apply-job-upload-text">העלה קובץ מ-Skydrive</span></a>
                    </div>
                </div>
            </li>

            <li class="resume-modul-content-container">
                <div class="resume-modul-box">
                    <div class="resume-modul-top">
                        <p class="resume-modul-number">3</p>
                        <div>
                            <p class="resume-modul-text">שלח אלינו</p>
                        </div>
                    </div>
                    <div class="resume-modul-bottom">
                        <button id="resume-modul-submit" class="resume-modul-submit">שליחה</button>
                    </div>
                </div>
            </li>
        </ul>
    </form>
</section>
<script>
    jQuery('#apply-job-upload-file-type').SumoSelect({});
</script>