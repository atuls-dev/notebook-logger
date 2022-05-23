<?php global $nl_logger; ?>
<div class="nl_frontend_body fixadd">
    <?php
    $logger = get_option("enable_logger", true);
    $view_all = get_option("enable_view_all", true);
    $view_all_page = get_option("view_all_page", true);
    $view_all_link = get_option("view_all_custom_link", true);
    if($logger):
    $post_id = get_post_meta(get_the_id(), "nl_floating_btn", true);
    $carving = get_option("logger_carving_form");
    if($post_id):
    ?>
    <span id="btn-nl-main" class="btn-nl-floating" >
        <div class="icon__wrap">
            <svg viewBox="0 0 89.8 100" style="enable-background:new 0 0 89.8 100;" xml:space="preserve">
            <style type="text/css">
                .st0{fill:#EF4043;}
                .st1{fill:#FFFFFF;}
            </style>
            <g>
            <g>
            <g>
            <g>
            <path class="st0" d="M63,1.2h-7.7v26.2c0,1.8-1.9,2.7-3.3,0.4l-5.1-8.2l-5.1,8.2c-1.5,2.3-3.3,1.4-3.3-0.4V1.2H12.5
                  C6.2,1.2,1.1,6.3,1.1,12.6v67.1c0,6.3,5.1,11.3,11.3,11.3H63c1.4,0,2.7-0.6,3.7-1.5c1-1,1.5-2.3,1.5-3.7V6.4
                  C68.2,3.6,65.9,1.2,63,1.2z M63.9,77.6l-49.3,0.1c-1.1,0.1-1.9,0.9-2,2c-0.1,1.2,0.8,2.2,2,2.3h49.3l0,4c0,0.2-0.1,0.5-0.3,0.6
                  c-0.2,0.2-0.4,0.3-0.6,0.3H12.5c-3.9,0-7-3.1-7-7s3.1-7,7-7H63c0.5,0,0.9,0.4,0.9,0.9V77.6z"/>
            </g>
            </g>
            </g>
            <g>
            <g>
            <rect x="65.6" y="60.8" class="st1" width="7.4" height="37.4"/>

            <rect x="65.6" y="60.8" transform="matrix(6.123234e-17 -1 1 6.123234e-17 -10.1182 148.8232)" class="st1" width="7.4" height="37.4"/>
            </g>
            <g>
            <rect x="66.2" y="61.4" class="st0" width="7.4" height="37.4"/>

            <rect x="66.2" y="61.4" transform="matrix(6.123234e-17 -1 1 6.123234e-17 -10.1182 150.0285)" class="st0" width="7.4" height="37.4"/>
            </g>
            </g>
            </g>
            </svg>

        </div>
        <div class="text__wrap">Add Log</div>
    </span>
    <?php endif;  endif; ?>
    <div class="popup-wrapper" id="nl_poper">

        <div class="popup-wrapper-content">
        <form name="smoking_notebook" class="form_smoking_notebook<?php if($carving){ echo " carving_form";  } ?>" method="POST">
            <span class="b-close">
                <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                     viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
                    <g>
                        <g>
                            <path d="M257,0C116.39,0,0,114.39,0,255s116.39,257,257,257s255-116.39,255-257S397.61,0,257,0z M383.22,338.79
                            c11.7,11.7,11.7,30.73,0,42.44c-11.61,11.6-30.64,11.79-42.44,0L257,297.42l-85.79,83.82c-11.7,11.7-30.73,11.7-42.44,0
                            c-11.7-11.7-11.7-30.73,0-42.44l83.8-83.8l-83.8-83.8c-11.7-11.71-11.7-30.74,0-42.44c11.71-11.7,30.74-11.7,42.44,0L257,212.58
                            l83.78-83.82c11.68-11.68,30.71-11.72,42.44,0c11.7,11.7,11.7,30.73,0,42.44l-83.8,83.8L383.22,338.79z"/>
                        </g>
                    </g>
                </svg>
            </span>
            <!-- SMOKING NOTEBOOK START -->
            <div class="add_smoking_wrapper" >
                <?php
                if($view_all){
                    if(!empty($view_all_link)){
                        $link = $view_all_link;
                    }else if(!empty($view_all_page)){
                        if($view_all_page == 'profile_page'){
                            if($nl_logger->is_buddyboss_active()){
                                $link = bp_core_get_user_domain( get_current_user_id() ) . 'nl-logger/' ;
                            }else{
                                $link = 'javascript:void(0);';
                            }
                        }else{
                            $link = get_the_permalink($view_all_page);
                        }

                    }else{
                        $link = 'javascript:void(0);';
                    }

                }
                ?>
                <div class="popup-wrapper-inner">
                    <div class="popup-head">
                        <div class="title_wrap">
                            <?php if($view_all){ ?>
                            <a href="<?php echo $link; ?>" class="view_entries-link" target="_blank">View all entries
                                <span>
                                    <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                         width="306px" height="306px" viewBox="0 0 306 306" style="enable-background:new 0 0 306 306;" xml:space="preserve">
                                        <g>
                                            <g id="chevron-right">
                                                <polygon points="94.35,0 58.65,35.7 175.95,153 58.65,270.3 94.35,306 247.35,153"/>
                                            </g>
                                        </g>
                                    </svg>
                                </span>
                            </a>
                            <?php } ?>
                            <h2 class="popup-title">Smoking Notebook</h2>
                        </div>


                    </div>
                    <div class="popup-body-wrap">
                        <?php if($carving){ ?>
                        <div class="toggle">
                            <label class="switch">
                                <span class="slider nl_act_smoke active_nl_carv">Smoking</span>
                                <input id="nl_smoke_crav" type="checkbox" value="craving" name="etype">
                                <span class="slider nl_act_crav">Craving</span>
                            </label>
                        </div>
                        <?php } ?>

                        <ul class="smoknote-list">
                            <li class="smoknote-list-item">
                                <div class="smoknote-left">
                                <!-- <a href="javascript:void(0)" id="nl_time"> -->
                                    <div class="smoknote-icon">
                                        <img src="<?php echo NL_ASSETS_URL; ?>img/notebook-time-icon.png" class='nl_png_icons'>
                                    </div>
                                    <div class="smoknote-text">
                                        <input type="hidden" name="timeZone" id="nl_time_zone" value="">
                                        <input type="hidden" name="time" class="nl_smoking_time nl_current_time" id="nl_hidden_time" value="<?php echo date('M d, h:i a'); ?>">
                                        <input type="hidden" name="action" value="nlsubmission">
                                        <input type="hidden" name="notebook_type" id="notebook_types" value="add">
                                        <input type="hidden" name="id" id="notebook_type_id" value="">
                                        <input type="hidden" name="user_id" value="<?php echo get_current_user_id(); ?>">
                                        <span class="smoknote-heading">Time</span>
                                        <span class="smoknote-subheading nl_current_time_view" id="nl_time_front"><?php echo date('F j, g:i a'); ?></span>
                                    </div>
                                <!--  </a> -->
                                </div>
                                <div class="smoknote-right">
                                    <button type="button" id='nl_time' class="smoknote-btn btn-edit nl_edit_time"><img src="<?php echo NL_ASSETS_URL; ?>img/notebook-done-check-icon.png" class="nl_png_icons" width="24" height="24"></button>
                                </div>
                            </li>
                            <li class="smoknote-list-item nl-validate ">
                                <div class="smoknote-left">
                                    <div class="smoknote-icon">
                                        <img src="<?php echo NL_ASSETS_URL; ?>img/notebook-trigger-icon.png" class='nl_png_icons'>
                                    </div>
                                    <div class="smoknote-text">
                                        <span class="smoknote-heading">Triggers</span>
                                        <span class="smoknote-subheading cap_text" id="nl_trigger_front">What triggered my carving?</span>
                                    </div>
                                </div>
                                <div class="smoknote-right">
                                    <button type="button" class="smoknote-btn btn-add nl_edit_trigger nl_edit_option" data-name="trigger">Add</button>
                                </div>
                            </li>
                            <li class="smoknote-list-item nl-validate nl-smoking-field">
                                <div class="smoknote-left">
                                    <div class="smoknote-icon">
                                        <img src="<?php echo NL_ASSETS_URL; ?>img/notebook-emotion-icon.png" class='nl_png_icons'>
                                    </div>
                                    <div class="smoknote-text">
                                        <span class="smoknote-heading">Craving Thought</span>
                                        <span class="smoknote-subheading cap_text" id="nl_emotion_front">How did I feel before smoking?</span>
                                    </div>
                                </div>
                                <div class="smoknote-right">
                                    <button type="button" class="smoknote-btn btn-add nl_edit_emotion nl_edit_option" data-name="emotion">Add</button>
                                </div>
                            </li>
                            <li class="smoknote-list-item nl-validate nl-smoking-field">
                                <div class="smoknote-left">
                                    <div class="smoknote-icon">
                                        <img src="<?php echo NL_ASSETS_URL; ?>img/notebook-reason-icon.png" class='nl_png_icons'>
                                    </div>
                                    <div class="smoknote-text">
                                        <span class="smoknote-heading">Reason</span>
                                        <span class="smoknote-subheading cap_text" id="nl_reason_front">Why did I smoke?</span>
                                    </div>

                                </div>
                                <div class="smoknote-right">
                                    <button type="button" class="smoknote-btn btn-add nl_edit_reason nl_edit_option" data-name="reason">Add</button>
                                </div>
                            </li>
                            <li class="smoknote-list-item nl-craving-field" style="display:none;">
                                <div class="smoknote-left">
                                    <div class="smoknote-icon">
                                        <img src="<?php echo NL_ASSETS_URL; ?>img/notebook-coping-icon.png" class='nl_png_icons'>
                                    </div>
                                    <div class="smoknote-text">
                                        <span class="smoknote-heading ">How did you cope ?</span>
                                        <span class="smoknote-subheading cap_text" id="nl_cope_front">What helped you overcoming the carving?</span>
                                    </div>
                                </div>
                                <div class="smoknote-right">
                                    <button type="button" class="smoknote-btn btn-add nl_edit_cope nl_edit_option" data-name="cope">Add</button>
                                </div>
                            </li>
                        </ul>

                        <div class="smoknote-intensity">
                            <div class="smoknote-left">
                                <div class="smoknote-icon">
                                    <img src="<?php echo NL_ASSETS_URL; ?>img/notebook-intensity-icon.png" class='nl_png_icons'>
                                </div>
                                <div class="smoknote-text">
                                    <span class="smoknote-heading">Craving Intensity</span>
                                </div>
                            </div>
                            <input type="hidden" name="intensity" class="nl_smoking_intensity" value="1">
                            <ul class="steps">
                                <li class="step step--incomplete step--active start-intensity" title="weak" data-val='1' id="nl-intensity_1">
                                    <span class="step__icon">1</span>
                                    <span class="step__label">Weak</span>
                                </li>
                                <li class="step step--incomplete step--inactive" title="weak-medium" data-val='2' id="nl-intensity_2">
                                    <span class="step__icon">2</span>
                                    <span class="step__label"></span>
                                </li>
                                <li class="step step--incomplete step--inactive" title="medium" data-val='3' id="nl-intensity_3">
                                    <span class="step__icon">3</span>
                                    <span class="step__label">Medium</span>
                                </li>
                                <li class="step step--incomplete step--inactive" title="medium-strong" data-val='4' id="nl-intensity_4">
                                    <span class="step__icon">4</span>
                                    <span class="step__label"></span>
                                </li>
                                <li class="step step--incomplete step--inactive" title="strong" data-val='5' id="nl-intensity_5">
                                    <span class="step__icon">5</span>
                                    <span class="step__label">Strong</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="popup-foot">
                        <button type="submit" class="popup-btn nl_add_notebook"><span class="nl_submit">Done</span><span class="nl_wait" style="display:none;"><img src="<?php echo NL_ASSETS_URL; ?>img/loader.gif" style="width: 30px;"></span></button>
                    </div>
                </div>
            </div>

            <!-- SMOKING NOTEBOOK END -->
            <!-- CHOOSE TRIGGER START -->
            <div class="choose_trigger_wrapper">
                <div class="popup-wrapper-inner">
                    <div class="popup-head">
                        <a href="javascript:void(0);" class="back prev_page" prevpage="add_smoking_wrapper" data-name="trigger" data-target="triggers" currentpage="choose_trigger_wrapper">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path d="M16.67 0l2.83 2.829-9.339 9.175 9.339 9.167-2.83 2.829-12.17-11.996z"/></svg>
                        </a>
                        <h2 class="popup-title">Choose a trigger</h2>
                    </div>
                    <div class="popup-body-wrap">
                        <div class="search-wrapper">
                            <div class="search-wrapper-icon"><img src="<?php echo NL_ASSETS_URL; ?>img/notebook-search-icon.png" class='nl_png_icons'></div>
                            <input type="text" name="search" class="search-input search-nl-option" data-option="trigger" placeholder="search for a trigger">
                        </div>
                        <div class="popup-body">
                            <span class="new-trigger nl-trigger-wrapper-main">
                                <h4 class='add-new-trigger'>
                                    <img src="<?php echo NL_ASSETS_URL; ?>img/notebook-add-icon.png" class='nl_png_icons' width="24" height="24">
                                    <input type="text" id="nl_new_trigger" name="trigger" data-target="triggers" class="add-trigger smoknote-left nl_my_options" placeholder="Add a new trigger">
                                </h4>
                            </span>

                            <div class="form-inner nl_my_trigger_wrapper" style="display:none;">
                                <h4>Your triggers</h4>
                                <div class="form-inputs nl_my_trigger">
                                </div>
                            </div>
                            <div class="form-inner nl_other_trigger_wrapper">
                                <h4 style="display:none;">Other triggers</h4>
                                <div class="form-inputs nl_other_trigger">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="popup-foot">
                        <a href="javascript:void(0);" class="popup-btn prev_page" prevpage="add_smoking_wrapper" data-name="trigger" data-target="triggers" currentpage="choose_trigger_wrapper">Save</a>
                    </div>
                </div>
            </div>
            <!-- CHOOSE TRIGGER END -->
            <!-- CHOOSE Emotion START -->
            <div class="choose_emotion_wrapper">
                <div class="popup-wrapper-inner">
                    <div class="popup-head">
                        <a href="javascript:void(0);" class="back prev_page" prevpage="add_smoking_wrapper" data-name="emotion" data-target="emotion" currentpage="choose_emotion_wrapper">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path d="M16.67 0l2.83 2.829-9.339 9.175 9.339 9.167-2.83 2.829-12.17-11.996z"/></svg>
                        </a>
                        <h2 class="popup-title">Choose Craving Thought</h2>
                    </div>
                    <div class="popup-body-wrap">
                        <div class="search-wrapper">
                            <div class="search-wrapper-icon"><img src="<?php echo NL_ASSETS_URL; ?>img/notebook-search-icon.png" class='nl_png_icons'></div>
                            <input type="text" name="search" class="search-input search-nl-option" data-option="emotion" placeholder="search for a emotion">
                        </div>
                        <div class="popup-body">
                            <span class="new-trigger nl-emotion-wrapper-main">
                                <h4 class='add-new-emotion'>
                                    <img src="<?php echo NL_ASSETS_URL; ?>img/notebook-add-icon.png" class='nl_png_icons' width="24" height="24">
                                    <input type="text" id="nl_new_emotion" name="emotion" data-target="emotion" class="add-emotion smoknote-left nl_my_options" placeholder="Add a new Emotion">
                                </h4>
                            </span>
                            <!-- <div class="smoknote-list-item add_emotion_wrapper nl-toggle-wrapper" style="display:none;">
                             <input type="text" id="nl_new_emotion" name="add_emotion" class="add-emotion smoknote-left" placeholder="add a new Emotion">
                             <a href="javascript:void(0);" class="smoknote-btn nl_new_emotion smoknote-right">Done</a>
                            </div> -->
                            <div class="form-inner nl_my_emotion_wrapper" style="display:none;">
                                <h4>Your Craving Thoughts</h4>
                                <div class="form-inputs nl_my_emotion">
                                </div>
                            </div>
                            <div class="form-inner nl_other_emotion_wrapper">
                                <h4 style="display:none;">Other Craving Thoughts</h4>
                                <div class="form-inputs nl_other_emotion">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="popup-foot">
                        <a href="javascript:void(0);" class="popup-btn prev_page" prevpage="add_smoking_wrapper" data-name="emotion" data-target="emotion" currentpage="choose_emotion_wrapper">Save</a>
                    </div>
                </div>
            </div>
            <!-- CHOOSE EMOTION END -->
            <!-- ADD REASON START -->
            <div class="add_reason_wrapper choose_reason_wrapper">
                <div class="popup-wrapper-inner">
                    <div class="popup-head">
                        <a href="javascript:void(0);" class="back prev_page" prevpage="add_smoking_wrapper" data-name="reason" data-target="reason" currentpage="add_reason_wrapper">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path d="M16.67 0l2.83 2.829-9.339 9.175 9.339 9.167-2.83 2.829-12.17-11.996z"/></svg>
                        </a>
                        <h2 class="popup-title">What's Your reason</h2>
                    </div>
                    <div class="popup-body-wrap">

                        <div class="popup-body">
                            <textarea class="nl_reason_area" name='reason' placeholder="Write the reason why you smoked"></textarea>
                        </div>
                    </div>
                    <div class="popup-foot">
                        <a href="javascript:void(0);" class="popup-btn prev_page" prevpage="add_smoking_wrapper" data-name="reason" data-target="reason" currentpage="add_reason_wrapper">Save</a>
                    </div>
                </div>
            </div>
            <!-- ADD REASON END -->
            <!-- CHOOSE COPE START -->
            <div class="choose_cope_wrapper">
                <div class="popup-wrapper-inner">
                    <div class="popup-head">
                        <a href="javascript:void(0);" class="back prev_page" prevpage="add_smoking_wrapper" data-name="cope" data-target="cope" currentpage="choose_cope_wrapper">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path d="M16.67 0l2.83 2.829-9.339 9.175 9.339 9.167-2.83 2.829-12.17-11.996z"/></svg>
                        </a>
                        <h2 class="popup-title">How did you cope?</h2>
                    </div>
                    <div class="popup-body-wrap">
                        <div class="search-wrapper">
                            <div class="search-wrapper-icon"><img src="<?php echo NL_ASSETS_URL; ?>img/notebook-search-icon.png" class='nl_png_icons'></div>
                            <input type="text" name="search" class="search-input search-nl-option" data-option="cope" placeholder="search for a cope">
                        </div>
                        <div class="popup-body">
                            <span class="new-trigger nl-cope-wrapper-main">
                                <h4 class='add-new-cope'>
                                    <img src="<?php echo NL_ASSETS_URL; ?>img/notebook-add-icon.png" class='nl_png_icons' width="24" height="24">
                                    <input type="text" id="nl_new_cope" name="cope" data-target="cope" class="add-cope smoknote-left nl_my_options" placeholder="Add a new cope">
                                </h4>
                            </span>

                            <div class="form-inner nl_my_cope_wrapper" style="display:none;">
                                <h4>Your Cope</h4>
                                <div class="form-inputs nl_my_cope">
                                </div>
                            </div>
                            <div class="form-inner nl_other_cope_wrapper">
                                <h4 style="display:none;">Other cops</h4>
                                <div class="form-inputs nl_other_cope">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="popup-foot">
                        <a href="javascript:void(0);" class="popup-btn prev_page" prevpage="add_smoking_wrapper" data-name="cope" data-target="cope" currentpage="choose_cope_wrapper">Save</a>
                    </div>
                </div>
            </div>
            <!-- CHOOSE COPE START -->
        </form>
        </div>
    </div>
</div>


