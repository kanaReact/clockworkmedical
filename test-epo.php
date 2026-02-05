<?php
require_once __DIR__ . '/wp-load.php';

$id = 43334;

// Test the API function directly
echo "=== API Function Output for clockwork_get_extra_product_options ===\n\n";
$api_options = clockwork_get_extra_product_options($id);
echo "Number of options: " . count($api_options) . "\n\n";
foreach ($api_options as $idx => $opt) {
    echo "Option $idx:\n";
    echo "  Label: " . ($opt['label'] ?? 'N/A') . "\n";
    echo "  Type: " . ($opt['type'] ?? 'N/A') . "\n";
    echo "  Required: " . ($opt['required'] ? 'Yes' : 'No') . "\n";
    echo "  Choices:\n";
    if (!empty($opt['choices'])) {
        foreach ($opt['choices'] as $choice) {
            echo "    - " . ($choice['label'] ?? 'N/A') . " (price: " . ($choice['price'] ?? 0) . ")\n";
        }
    }
    echo "\n";
}
echo "\n\n";

echo "=== EPO Debug for Product $id ===\n\n";

// Check if TM EPO class exists
echo "THEMECOMPLETE_EPO class exists: " . (class_exists('THEMECOMPLETE_EPO') ? 'Yes' : 'No') . "\n";
echo "THEMECOMPLETE_EPO function exists: " . (function_exists('THEMECOMPLETE_EPO') ? 'Yes' : 'No') . "\n\n";

// Try to get EPO data directly
if (function_exists('THEMECOMPLETE_EPO')) {
    $epo = THEMECOMPLETE_EPO();
    echo "EPO Object: " . get_class($epo) . "\n";

    if (method_exists($epo, 'get_product_tm_epos')) {
        $epo_data = $epo->get_product_tm_epos($id, '', false, false);
        echo "\nEPO Data Keys: ";
        print_r(array_keys($epo_data ?: []));

        if (!empty($epo_data)) {
            echo "\n\nFull EPO Data:\n";
            print_r($epo_data);
        } else {
            echo "\nNo EPO data found\n";
        }
    } else {
        echo "get_product_tm_epos method not found\n";
        echo "Available methods: " . implode(', ', get_class_methods($epo)) . "\n";
    }
} else {
    echo "THEMECOMPLETE_EPO function not available\n";

    // Check for other EPO related classes/tables
    global $wpdb;

    // Check for TM EPO tables
    $tables = $wpdb->get_results("SHOW TABLES LIKE '%tm_epo%'");
    echo "\nTM EPO Tables:\n";
    print_r($tables);

    // Check product meta for EPO-related data
    echo "\nProduct Meta related to EPO:\n";
    $meta = $wpdb->get_results($wpdb->prepare(
        "SELECT meta_key, meta_value FROM {$wpdb->postmeta} WHERE post_id = %d AND (meta_key LIKE '%epo%' OR meta_key LIKE '%tm_%' OR meta_key LIKE '%option%')",
        $id
    ));
    print_r($meta);
}

// Check for global EPO posts
global $wpdb;
echo "\n\n=== Global EPO Posts ===\n";
$global_epos = $wpdb->get_results("SELECT ID, post_title, post_status FROM {$wpdb->posts} WHERE post_type = 'tm_global_cp'");
print_r($global_epos);

// Check if there's a connection between the product and global EPO
echo "\n\n=== Product's EPO assignments ===\n";
foreach ($global_epos as $epo_post) {
    $applied_on = get_post_meta($epo_post->ID, 'tm_meta_cpf_applied_on', true);
    echo "EPO ID {$epo_post->ID} ({$epo_post->post_title}): ";
    print_r($applied_on);
    echo "\n";
}
