<div class="wrap" id="globo_exim_export_wrap">

    <div class="navbar-wrapper">
        <div class="container">

            <nav class="navbar navbar-inverse navbar-static-top">
                <div class="container">
                    <div class="row">
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                        </div>
                        <div id="navbar" class="navbar-collapse collapse">
                            <ul class="nav navbar-nav">
                                <li><a href="<?php echo esc_url( admin_url( 'admin.php?page=uou_uploader' ) ) ?>"><?php _e('Dashboard', 'globo') ?></a></li>
                                
                                <li class="active dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php _e('Company', 'globo') ?> <span class="caret"></span></a>
                                    <ul class="dropdown-menu">
                                        <li class="active"><a href="<?php echo esc_url( admin_url( 'admin.php?page=uou_uploader_company_import' ) ) ?>"><?php _e('Company Import', 'globo') ?></a></li>
                                        <li><a href="<?php echo esc_url( admin_url( 'admin.php?page=uou_uploader_company_export' ) ) ?>"><?php _e('Company Export', 'globo') ?></a></li>
                                    </ul>
                                </li>
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php _e('Industry', 'globo') ?> <span class="caret"></span></a>
                                    <ul class="dropdown-menu">
                                        <li><a href="<?php echo esc_url( admin_url( 'admin.php?page=uou_uploader_industry_import' ) ) ?>"><?php _e('Industry Import', 'globo') ?></a></li>
                                        <li><a href="<?php echo esc_url( admin_url( 'admin.php?page=uou_uploader_industry_export' ) ) ?>"><?php _e('Industry Export', 'globo') ?></a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>

        </div>
    </div>

    
    <div class="container">
        
        <?php $ajax_action = admin_url( 'admin-ajax.php') . '?action=globo_exim_upload_csv' ?>
        <form id="globo-import" method="post" action="<?php echo esc_url( $ajax_action) ?>" enctype="multipart/form-data">
            <div id="drop">
                <?php _e('Drop CSV Here', 'globo') ?>

                <a><?php _e('Browse CSV', 'globo') ?></a>
                <!-- <input id="csvfile" name="csvfile" type="file" accept="text/csv" /> -->
                <input id="csvfile" name="csvfile" type="file"/>
            </div>

            <ul id="show-upload">
                <!-- The file uploads will be shown here -->
            </ul>
            
            <?php // wp_nonce_field('upload_import_file', 'upload_import_file'); ?>
        </form>
        
        <div id="globo_hide">
            <form id="import" method="post">
                <input id="import-csv-file" name="import-csv-file" type="hidden" value="">
                <button id="read-csv" class="btn btn-danger pull-right mt5"><?php _e('Import Company', 'globo') ?></button>
            </form>

            <div class="alert alert-success">
                <p id="import-result"><span class="globo-exim-icon"><i class="glyphicon glyphicon-refresh fa-spin"></i> <?php _e('Company Importing...', 'globo') ?></span>&nbsp;</p>
            </div>
        </div>
    </div>
</div>
