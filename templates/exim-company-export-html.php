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
                                        <li><a href="<?php echo esc_url( admin_url( 'admin.php?page=uou_uploader_company_import' ) ) ?>"><?php _e('Company Import', 'globo') ?></a></li>
                                        <li class="active"><a href="<?php echo esc_url( admin_url( 'admin.php?page=uou_uploader_company_export' ) ) ?>"><?php _e('Company Export', 'globo') ?></a></li>
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
        <h1><?php _e('Globo Company Blank CSV Template', 'globo') ?></h1>
        <form>
            <input type="hidden" name="globo_export_download" id="globo_export_download" value="company">
            <button id="globo_export_download_btn" class="btn btn-info"><?php _e('Download Company Blank CSV Template', 'globo') ?></button>
        </form>

        <h1><?php _e('Export Company', 'globo') ?></h1>
        <p><?php _e('Download all companies as a CSV file.', 'globo') ?></p>
        <form>
            <button id="globo_export_company_download_btn" class="btn btn-danger"><?php _e('Company Export', 'globo') ?></button>
        </form>

        
        <form id="hiddenform" method="POST" action="<?php echo esc_url( admin_url( 'admin-ajax.php') . '?action=globo_exim_export_template_download' ) ?>">
            <input type="hidden" id="globo_type" name="globo_type" value="company">
            <input type="hidden" id="filedata" name="data" value="">
        </form>
        
    </div>
</div>
