<?php

namespace AllureClinics\Admin;

class GetStartedPage {

    public function render(): void {
        include plugin_dir_path(__FILE__) . 'views/get-started.php';
    }
}
