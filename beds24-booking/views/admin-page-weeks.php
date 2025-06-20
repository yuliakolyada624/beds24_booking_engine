<?php
// Ensure the script is being run within the WordPress context
if (!defined('ABSPATH')) {
    exit;
}

// Handle form submission
if (isset($_POST['save_weeks'])) {
    global $wpdb;
    $table_name = 'beds_pricelist_weeks';

    for ($i = 1; $i <= 52; $i++) {
        $custom_number = sanitize_text_field($_POST["week_name_$i"]);
        $week = 'Vecka ' . $custom_number;
        $start_date = sanitize_text_field($_POST["start_date_$i"]);
        $end_date = sanitize_text_field($_POST["end_date_$i"]);
        $today_date = date('Y-m-d H:i:s');

        // Check if both start_date and end_date are not empty
        if (!empty($start_date) && !empty($end_date)) {
            $existing_week = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE week_name = %s", $week));

            if ($existing_week) {
                // Update existing week
                $wpdb->update(
                    $table_name,
                    array(
                        'start_date' => $start_date,
                        'end_date' => $end_date,
                        'updated_date' => $today_date,
                    ),
                    array('week_name' => $week),
                    array(
                        '%s',
                        '%s',
                        '%s'
                    ),
                    array('%s')
                );
            } else {
                // Insert new week
                $wpdb->insert(
                    $table_name,
                    array(
                        'week_name' => $week,
                        'start_date' => $start_date,
                        'end_date' => $end_date,
                        'created_date'=> $today_date,
                        'updated_date' => $today_date
                    ),
                    array(
                        '%s',
                        '%s',
                        '%s',
                        '%s',
                        '%s'
                    )
                );
            }
        }
    }
}

// Get weeks data
global $wpdb;
$table_name = 'beds_pricelist_weeks';
$weeks_data = $wpdb->get_results("SELECT * FROM $table_name ORDER BY id");

?>

<!-- <div class="wrap">
    <h1>Booking Weeks</h1>
    <form method="post">
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>Week Name</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                </tr>
            </thead>
            <tbody>
                <?php for ($i = 1; $i <= 52; $i++): 
                    $week_name = 'Vecka ' . $i;
                    $week_data = array_filter($weeks_data, function($week) use ($week_name) {
                        return $week->week_name === $week_name;
                    });
                    $week_data = reset($week_data);
                    $start_date = $week_data ? $week_data->start_date : '';
                    $end_date = $week_data ? $week_data->end_date : '';
                    $custom_number = $week_data ? str_replace('Vecka ', '', $week_data->week_name) : '';
                ?>
                    <tr>
                        <td>Vecka<input type="text" name="week_name_<?php echo $i; ?>" value="<?php echo esc_attr($custom_number); ?>"></td>
                        <td><input type="date" name="start_date_<?php echo $i; ?>" value="<?php echo esc_attr($start_date); ?>"></td>
                        <td><input type="date" name="end_date_<?php echo $i; ?>" value="<?php echo esc_attr($end_date); ?>"></td>
                    </tr>
                <?php endfor; ?>
            </tbody>
        </table>
        <p class="submit">
            <input type="submit" name="save_weeks" class="button-primary" value="Save Changes">
        </p>
    </form>
</div> -->


<div class="wrap">
    <h1>Booking Weeks</h1>
    <form method="post">
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>Week Name</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                // Display existing weeks from the database
                for ($i = 0; $i <= count($weeks_data) - 1; $i++): 
                    $week_data = $weeks_data[$i];
                    $custom_number = str_replace('Vecka ', '', $week_data->week_name);
                    $start_date = $week_data->start_date;
                    $end_date = $week_data->end_date;
                ?>
                    <tr>
                        <td>Vecka <input type="text" name="week_name_<?php echo $i + 1; ?>" value="<?php echo esc_attr($custom_number); ?>"></td>
                        <td><input type="date" name="start_date_<?php echo $i + 1; ?>" value="<?php echo esc_attr($start_date); ?>"></td>
                        <td><input type="date" name="end_date_<?php echo $i + 1; ?>" value="<?php echo esc_attr($end_date); ?>"></td>
                    </tr>
                <?php endfor; ?>
                <!-- Fill remaining rows to make the total 52 -->
                <?php for ($i = count($weeks_data); $i <= 52; $i++): ?>
                    <tr>
                        <td>Vecka <input type="text" name="week_name_<?php echo $i + 1; ?>" value=""></td>
                        <td><input type="date" name="start_date_<?php echo $i + 1; ?>" value=""></td>
                        <td><input type="date" name="end_date_<?php echo $i + 1; ?>" value=""></td>
                    </tr>
                <?php endfor; ?>
            </tbody>
        </table>
        <p class="submit">
            <input type="submit" name="save_weeks" class="button-primary" value="Save Changes">
        </p>
    </form>
</div>


