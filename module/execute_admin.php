<?php
/*
 * Setting Screen
 * call: execute_admin($this);
 * -*- Encoding: utf8n -*-
 */
function contentInsteadOfTheExcerpt_execute_admin(&$obj) {
    //print_r($_REQUEST);
    //print_r($obj->model);
    //echo phpinfo();

    if (is_array($_REQUEST)) {
        // Array extract to variable
        extract($_REQUEST);
    }
    
    ?>
    <div class="wrap contentInsteadOfTheExcerpt_admin">
    <div id="icon-plugins" class="icon32"><br/></div>
    <h2><?php _e('Content instead of the excerpt', 'contentinsteadoftheexcerpt'); ?></h2>
    <form name="formCFShoppingcart" method="post">
    <div id="poststuff" class="meta-box-sortables" style="position: relative; margin-top:10px;">
      
    <?php
    if (isset($save)) {
        $msg = save($obj);
    }
    edit($obj, $msg);
    
    echo '</div>';
    echo '</form>';
    echo '</div>';
}

function save(&$obj) {
    //print_r($_REQUEST);
    if (is_array($_REQUEST)) {
        // Array extract to variable
        extract($_REQUEST);
    }
    
    $model = &$obj->model;
    
    $model->set_version($model->get_current_version());
    
    //
    $model->setContentInsteadOfExcerptOnHome($content_instead_of_excerpt_on_home);
    $model->setContentInsteadOfExcerptOnPage($content_instead_of_excerpt_on_page);
    $model->setContentInsteadOfExcerptOnArchive($content_instead_of_excerpt_on_archive);
    $model->setContentInsteadOfExcerptOnSingle($content_instead_of_excerpt_on_single);
    $model->setContentInsteadOfExcerptOnCategoryNumbers($content_instead_of_excerpt_on_category_numbers);
    $model->setContentInsteadOfExcerptOnPageNumbers($content_instead_of_excerpt_on_page_numbers);
    
    //
    $obj->updateWpOption($model); // Save database-model
    $msg .= __('Saved', 'contentinsteadoftheexcerpt');
    return $msg;
}

function contentInsteadOfTheExcerpt_get_query_string_array() {
    $f = array();
    $f = explode('&', getenv('QUERY_STRING'));
    //print_r($f);
    foreach ($f as $i => $v) {
        $g = explode('=', $v, 2);
        $qs[urldecode($g[0])] = urldecode($g[1]);
    }
    return $qs;
}
function contentInsteadOfTheExcerpt_query_string($name, $value) {
    $qs = contentInsteadOfTheExcerpt_get_query_string_array();
    
    if ($value) {
        $qs[$name] = $value;
    } else {
        unset($qs[$name]);
    }
    //
    $q = array();
    foreach ($qs as $n => $v) {
        $q[] = urlencode($n) . '=' . urlencode($v);
    }
    return join('&', $q);
}

function edit(&$obj, $msg = '') {
    global $contentInsteadOfTheExcerpt_common;
    global $wp_version;
    $model = $obj->model;
    ?>


      
  <div class="postbox contentInsteadOfTheExcerpt_postbox">
    <div class="handlediv" title="Click to toggle"><br /></div>
    <h3><?php _e('Options','contentinsteadoftheexcerpt');?></h3>
    <div class="inside">
      
        <table class="form-table">
        

        <tr><th><?php _e('Display to content instead of excerpt if choice or input:', 'contentinsteadoftheexcerpt');?></th><td><input type="checkbox" name="content_instead_of_excerpt_on_home" value="checked" <?php echo $model->getContentInsteadOfExcerptOnHome();?> /> <?php _e('home','contentinsteadoftheexcerpt');?> <input type="checkbox" name="content_instead_of_excerpt_on_page" value="checked" <?php echo $model->getContentInsteadOfExcerptOnPage();?> /> <?php _e('page','contentinsteadoftheexcerpt');?> <input type="checkbox" name="content_instead_of_excerpt_on_archive" value="checked" <?php echo $model->getContentInsteadOfExcerptOnArchive();?> /> <?php _e('archive','contentinsteadoftheexcerpt');?> <input type="checkbox" name="content_instead_of_excerpt_on_single" value="checked" <?php echo $model->getContentInsteadOfExcerptOnSingle();?> /> <?php _e('single','contentinsteadoftheexcerpt');?> <br /><input type="text" name="content_instead_of_excerpt_on_category_numbers" id="content_instead_of_excerpt_on_category_numbers" value="<?php echo join(',',$model->getContentInsteadOfExcerptOnCategoryNumbers());?>" size="20" /> <?php _e('Category numbers (Example: 1,2,..)', 'contentinsteadoftheexcerpt');?><br /><input type="text" name="content_instead_of_excerpt_on_page_numbers" id="content_instead_of_excerpt_on_page_numbers" value="<?php echo join(',',$model->getContentInsteadOfExcerptOnPageNumbers());?>" size="20" /> <?php _e('Page numbers (Example: 1,2,..)', 'contentinsteadoftheexcerpt');?></td></tr>

                   <tr><th><input type="submit" name="save" value="<?php _e('Update Options', 'contentinsteadoftheexcerpt')?>&nbsp;&raquo;" class="button-primary" /></th><td></td></tr>

        </table>
    </div>
  </div>

        <div class="contentInsteadOfTheExcerpt_admin-links"><a href="http://takeai.silverpigeon.jp/">blog</a> | <a href="http://contentInsteadOfTheExcerpt.silverpigeon.jp/">website</a> | <a href="http://takeai.silverpigeon.jp/?page_id=727">donate</a></div>


<?php } ?>
