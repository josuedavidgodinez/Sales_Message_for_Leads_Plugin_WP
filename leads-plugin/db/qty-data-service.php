<?php

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

function createQtyLocationsTable()
{
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $table = $wpdb->prefix . 'QtyLocations';
    $sql = "CREATE TABLE $table (
      QtyLocations int NOT NULL
    ) $charset_collate;";
    dbDelta($sql);
    $wpdb->insert($table, array('QtyLocations' => 1));
}

function getQtyLocationsTable()
{
    global $wpdb;

    // Table name with WordPress prefix
    $table_name = $wpdb->prefix . 'QtyLocations';

    // Prepare and execute the SQL query
    $query = $wpdb->prepare("SELECT * FROM $table_name ");
    $record = $wpdb->get_row($query, ARRAY_A);

    return (int) $record['QtyLocations'];
}


function DeleteQtyLocationsTable()
{
    global $wpdb;
    $table = $wpdb->prefix . 'QtyLocations';
    $wpdb->query("DROP TABLE IF EXISTS $table");
}


function UpdateQtyLocationsTable($qty)
{
    global $wpdb;
    // Table name with WordPress prefix
    $table_name = $wpdb->prefix . 'QtyLocations';
        $wpdb->query(
            $wpdb->prepare(
                "UPDATE $table_name SET QtyLocations = %s WHERE QtyLocations IS NOT NULL",
                $qty
            )
        );
}
?>