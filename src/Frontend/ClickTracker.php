<?php

namespace AllureClinics\Frontend;

class ClickTracker {

    public function __construct() {
        add_action('wp_footer', [$this, 'inject_tracking_script'], 100);
    }

    public function inject_tracking_script() {
        ?>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            var telLinks = document.querySelectorAll('a[href^="tel:"]');
            
            telLinks.forEach(function(link) {
                link.addEventListener('click', function(e) {
                    var source = 'website';
                    // Check if we are on a landing page (simple heuristic: url contains utm_source or is a known landing page, or we just log 'website' and let the dashboard see the URL)
                    if (window.location.search.indexOf('utm_') !== -1) {
                        source = 'landing_page';
                    }

                    var payload = {
                        source: source,
                        page_url: window.location.href
                    };

                    fetch('<?php echo esc_url(rest_url('allure/v1/call-click')); ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(payload),
                        keepalive: true // Ensure the request completes even if the page unloads
                    }).catch(function() {
                        // Silent fail
                    });
                });
            });
        });
        </script>
        <?php
    }
}
