<?php
// No direct access
defined('_JEXEC') or die;


?>

<input type="hidden" id="baseURL" value="<?php echo JUri::base(); ?>">
<section class="board-modul" id="mod_nsoft_section" dir="rtl">
    <progress id="progress-loading" class="pure-material-progress-linear" style="display:none"></progress>
    <div class="board-modul-title-container">
        <h1 class="board-modul-title"><?= JText::_('COLMAN_BOARD_TITLE') ?></h1>
        <div class="board-modul-horizontal-line"></div>
        <div class="board-modul-total-jobs"><span></span><?= JText::_('COLMAN_BOARD_TOTAL_JOBS') ?></div>
    </div>

    <ul class="board-modul-content">
        <li class="board-modul-content-container">
            <a href="javascript:void(0);" id="filled-jobs" class="board-modul-box board-modul-large-box filled-jobs">
                <span class="board-modul-box-icon"></span>
                <div class="board-modul-box-info">
                    <h2 class="board-modul-count"></h2>
                    <h3 class="board-modul-box-name">משרות הוגשו</h3>
                </div>
            </a>
        </li>

        <li class="board-modul-content-container">
            <a href="javascript:void(0);" id="new-jobs" class="board-modul-box board-modul-large-box new-jobs">
                <span class="board-modul-box-icon"></span>
                <div class="board-modul-box-info">
                    <h2 class="board-modul-count"></h2>
                    <h3 class="board-modul-box-name"><?= JText::_('COLMAN_NEW_JOBS_FOUND') ?></h3>
                </div>
            </a>
        </li>

        <li class="board-modul-content-container">
            <a href="javascript: void(0);" id="cv" class="board-modul-box cv">
                <span class="board-modul-box-icon"></span>
                <div class="board-modul-box-info">
                    <h2 class="board-modul-count"></h2>
                    <h3 class="board-modul-box-name">קורות חיים</h3>
                </div>
            </a>
            <a href="javascript: void(0);" id="files" class="board-modul-box files">
                <span class="board-modul-box-icon"></span>
                <div class="board-modul-box-info">
                    <h2 class="board-modul-count"></h2>
                    <h3 class="board-modul-box-name">מסמכים נוספים</h3>
                </div>
            </a>
        </li>

    </ul>
</section>