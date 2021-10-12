<?php
// No direct access
defined('_JEXEC') or die;


jimport('joomla.application.module.helper');
$attribs['style'] = 'xhtml';

$session = &JFactory::getSession();

$user_credentials = $session->get("user_credentials");
?>
<header dir="rtl">
    <div style="display:none" class="header-top">
        <nav  class="header-top-nav">
            <?php
            $module = JModuleHelper::getModule('menu', 'Header Top Menu');
            echo JModuleHelper::renderModule($module, $attribs);
            ?>
            <ul  class="menu-header-left">
                <li style="display:none"><img class="header-flag" src="modules/mod_header/assets/images/flag.png" alt="heb"><span class="header-lang-name">Heb</span></li>
                <li style="display:none"><a href="#">נגישות<span class="header-access-icon"><i class="fa fa-wheelchair"></i></span></a></li>
                <li>
                    <a style="display:none" href="#" class="header-youtube"><i class="fa fa-youtube-play"></i></a>
                    <a style="display:none" href="#" class="header-twitter"><i class="fa fa-twitter"></i></a>
                    <a style="display:none" href="#" class="header-facbook"><i class="fa fa-facebook"></i></a>
                </li>
            </ul>
        </nav>
    </div>
    <div class="header-bottom">
        <nav class="header-bottom-nav">
            <div class="header-right">
                <div class="header-logo">
                    <a href="#">
                        <img src="modules/mod_header/assets/images/logo.png" alt="">
                    </a>
                </div>

                <?php
                $module = JModuleHelper::getModule('menu', 'Header Main Menu');
                echo JModuleHelper::renderModule($module, $attribs);
                ?>

            </div>

            <div class="header-left">
                <ul class="header-secondary-menu">
                    <li style="display:none"><a href="#"><span id="header-search" class="header-menu-icon"></span><label>חיפוש</label></a></li>
                    <li style="display:none"><a href="#"><span id="header-advice" class="header-menu-icon"></span><label>ייעוץ</label></a></li>
                    <li>
                        <?php if (!empty($user_credentials)): ?>
                            <a style="display:none" href="#"><span id="header-alerts" class="header-menu-icon"></span><label>התראות</label></a>
                        <?php else: ?>
                            <a href="login_saml.php"><span id="header-login" class="header-menu-icon"></span><label>כניסה</label></a>
                        <?php endif; ?>
                    </li>
                    
               
                </ul>
                <?php if (isset($user_credentials)):?>
                <label  class="header-username-label"><?php echo($session->get("user_credentials")["fullName"][0]); ?></label>
                <label  class="header-username-label"><a style="    margin-right: 50px;" href="https://login3.colman.ac.il/AGLogout">יציאה</a></label>
                                            <!--<a class="header-user-avatar" href="#"><img alt="" src="modules/mod_header/assets/images/avatar.jpg"></a>-->
                <?php else: ?>
                    <button style="display:none" class="header-signup-bitton">להרשמה</button>
                <?php endif; ?>
            </div>
        </nav>
    </div>
</header>