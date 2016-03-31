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
                                <li class="active"><a href="<?php echo esc_url( admin_url( 'admin.php?page=uou_uploader' ) ) ?>"><?php _e('Dashboard', 'globo') ?></a></li>
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php _e('Company', 'globo') ?> <span class="caret"></span></a>
                                    <ul class="dropdown-menu">
                                        <li><a href="<?php echo esc_url( admin_url( 'admin.php?page=uou_uploader_company_import' ) ) ?>"><?php _e('Company Import', 'globo') ?></a></li>
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
        <h1><?php _e('UOU Bulk Uploader', 'globo') ?></h1>
        <p>
            <?php _e('Please read the documention carefully. Hope you enjoyed the UOU Bulk Uploader Plugin', 'globo') ?>
            <a target="_blank" href="<?php echo esc_url( 'http://188.226.173.21/docs/uou-bulk-uploader/' ) ?>"><?php _e('Visit Documentaion', 'globo') ?></a>
        </p>
    </div>
</div>
