<?php
/**
 * Admin dashboard, meta boxes, and settings page for the IST Flight Booking plugin.
 */

defined( 'ABSPATH' ) || exit;

// ── Dashboard page ────────────────────────────────────────────────────────────
function ist_fb_dashboard_page(): void {
    $total     = wp_count_posts( 'ist_booking' )->publish ?? 0;
    $confirmed = ist_fb_count_by_status( 'confirmed' );
    $pending   = ist_fb_count_by_status( 'pending' );
    $cancelled = ist_fb_count_by_status( 'cancelled' );

    $recent = get_posts( [
        'post_type'   => 'ist_booking',
        'numberposts' => 10,
        'orderby'     => 'date',
        'order'       => 'DESC',
    ] );
    ?>
    <div class="wrap ist-fb-dashboard">
        <h1 class="wp-heading-inline">
            <span class="dashicons dashicons-airplane" style="font-size:28px;width:28px;height:28px;margin-right:8px;color:#f97316;vertical-align:middle;"></span>
            Flight Bookings Dashboard
        </h1>
        <a href="<?php echo esc_url( admin_url( 'admin.php?page=ist-fb-settings' ) ); ?>" class="page-title-action">Settings</a>
        <hr class="wp-header-end">

        <?php ist_fb_admin_notice(); ?>

        <!-- Stats row -->
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin:24px 0;">
            <?php
            $stats = [
                [ 'label' => 'Total Bookings', 'value' => $total,     'color' => '#6366f1' ],
                [ 'label' => 'Confirmed',       'value' => $confirmed, 'color' => '#22c55e' ],
                [ 'label' => 'Pending',         'value' => $pending,   'color' => '#f97316' ],
                [ 'label' => 'Cancelled',       'value' => $cancelled, 'color' => '#ef4444' ],
            ];
            foreach ( $stats as $s ) :
                ?>
                <div style="background:#fff;border:1px solid #e5e7eb;border-radius:8px;padding:20px;text-align:center;box-shadow:0 1px 3px rgba(0,0,0,.05);">
                    <div style="font-size:2rem;font-weight:700;color:<?php echo esc_attr( $s['color'] ); ?>;">
                        <?php echo esc_html( $s['value'] ); ?>
                    </div>
                    <div style="font-size:.85rem;color:#6b7280;margin-top:4px;"><?php echo esc_html( $s['label'] ); ?></div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Recent bookings table -->
        <div style="background:#fff;border:1px solid #e5e7eb;border-radius:8px;overflow:hidden;">
            <div style="padding:16px 20px;border-bottom:1px solid #e5e7eb;display:flex;justify-content:space-between;align-items:center;">
                <h2 style="margin:0;font-size:1rem;">Recent Bookings</h2>
                <a href="<?php echo esc_url( admin_url( 'edit.php?post_type=ist_booking' ) ); ?>">View All →</a>
            </div>
            <table class="wp-list-table widefat fixed striped" style="border:none;">
                <thead>
                    <tr>
                        <th>Reference</th>
                        <th>Passenger</th>
                        <th>Route</th>
                        <th>Carrier</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ( empty( $recent ) ) : ?>
                        <tr><td colspan="8" style="text-align:center;padding:24px;color:#9ca3af;">No bookings yet.</td></tr>
                    <?php else : ?>
                        <?php foreach ( $recent as $post ) :
                            $meta = IST_Booking_Manager::get_meta( $post->ID );
                            ?>
                            <tr>
                                <td><strong><?php echo esc_html( $meta['reference'] ); ?></strong></td>
                                <td><?php echo esc_html( $meta['lead_name'] ); ?></td>
                                <td><?php echo esc_html( $meta['dep_airport'] . ' → ' . $meta['arr_airport'] ); ?></td>
                                <td><?php echo esc_html( $meta['carrier'] ?: '—' ); ?></td>
                                <td><?php echo esc_html( $meta['currency'] . ' ' . $meta['total'] ); ?></td>
                                <td><?php ist_fb_status_badge( $meta['status'] ); ?></td>
                                <td><?php echo esc_html( substr( $meta['created'], 0, 10 ) ); ?></td>
                                <td>
                                    <a href="<?php echo esc_url( admin_url( 'post.php?post=' . $post->ID . '&action=edit' ) ); ?>">View</a>
                                    <?php if ( $meta['status'] !== 'cancelled' ) : ?>
                                        &nbsp;|&nbsp;
                                        <a href="#" class="ist-fb-cancel" data-id="<?php echo esc_attr( $post->ID ); ?>" style="color:#ef4444;">Cancel</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
    document.querySelectorAll('.ist-fb-cancel').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            if (!confirm('Cancel this booking? This will also attempt to cancel the Duffel order.')) return;
            var id = this.dataset.id;
            fetch(ajaxurl, {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'action=ist_fb_cancel_booking&post_id=' + id + '&nonce=<?php echo esc_js( wp_create_nonce( 'ist_fb_cancel' ) ); ?>'
            })
            .then(r => r.json())
            .then(function(data) {
                if (data.success) { location.reload(); }
                else { alert(data.data || 'Error cancelling booking.'); }
            });
        });
    });
    </script>
    <?php
}

function ist_fb_count_by_status( string $status ): int {
    global $wpdb;
    return (int) $wpdb->get_var( $wpdb->prepare(
        "SELECT COUNT(p.ID) FROM {$wpdb->posts} p
         INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
         WHERE p.post_type = 'ist_booking'
           AND p.post_status = 'publish'
           AND pm.meta_key = '_fb_status'
           AND pm.meta_value = %s",
        $status
    ) );
}

function ist_fb_status_badge( string $status ): void {
    $map = [
        'confirmed' => [ 'Confirmed', '#22c55e', '#f0fdf4' ],
        'pending'   => [ 'Pending',   '#f97316', '#fff7ed' ],
        'cancelled' => [ 'Cancelled', '#ef4444', '#fef2f2' ],
        'refunded'  => [ 'Refunded',  '#6366f1', '#eef2ff' ],
        'failed'    => [ 'Failed',    '#9ca3af', '#f9fafb' ],
    ];
    [ $label, $color, $bg ] = $map[ $status ] ?? [ ucfirst( $status ), '#6b7280', '#f9fafb' ];
    printf(
        '<span style="display:inline-block;padding:2px 10px;border-radius:999px;font-size:.8rem;font-weight:600;color:%s;background:%s;">%s</span>',
        esc_attr( $color ),
        esc_attr( $bg ),
        esc_html( $label )
    );
}

function ist_fb_admin_notice(): void {
    if ( ! defined( 'DUFFEL_API_KEY' ) || empty( DUFFEL_API_KEY ) ) {
        echo '<div class="notice notice-warning is-dismissible"><p><strong>IST Flight Booking:</strong> No Duffel API key found. Add <code>define(\'DUFFEL_API_KEY\', \'your-key-here\');</code> to <code>wp-config.php</code>.</p></div>';
    }
}

// ── Settings page ─────────────────────────────────────────────────────────────
function ist_fb_settings_page(): void {
    if ( isset( $_POST['ist_fb_settings_nonce'] ) && wp_verify_nonce( $_POST['ist_fb_settings_nonce'], 'ist_fb_save_settings' ) ) {
        update_option( 'ist_fb_admin_email',    sanitize_email( $_POST['ist_fb_admin_email']    ?? '' ) );
        update_option( 'ist_fb_from_name',      sanitize_text_field( $_POST['ist_fb_from_name']      ?? '' ) );
        update_option( 'ist_fb_from_email',     sanitize_email( $_POST['ist_fb_from_email']     ?? '' ) );
        update_option( 'ist_fb_currency',       sanitize_text_field( $_POST['ist_fb_currency']       ?? 'USD' ) );
        update_option( 'ist_fb_whatsapp',       sanitize_text_field( $_POST['ist_fb_whatsapp']       ?? '' ) );
        echo '<div class="notice notice-success is-dismissible"><p>Settings saved.</p></div>';
    }
    $admin_email = get_option( 'ist_fb_admin_email',  get_option( 'admin_email' ) );
    $from_name   = get_option( 'ist_fb_from_name',   'Infinity Sky Travels' );
    $from_email  = get_option( 'ist_fb_from_email',  'infinityskytravels8@gmail.com' );
    $currency    = get_option( 'ist_fb_currency',    'USD' );
    $whatsapp    = get_option( 'ist_fb_whatsapp',    '+977 9851234567' );
    ?>
    <div class="wrap">
        <h1>Flight Booking Settings</h1>
        <form method="post">
            <?php wp_nonce_field( 'ist_fb_save_settings', 'ist_fb_settings_nonce' ); ?>
            <table class="form-table">
                <tr>
                    <th><label for="ist_fb_admin_email">Booking Notification Email</label></th>
                    <td>
                        <input type="email" id="ist_fb_admin_email" name="ist_fb_admin_email" value="<?php echo esc_attr( $admin_email ); ?>" class="regular-text">
                        <p class="description">Admin receives new booking notifications at this address.</p>
                    </td>
                </tr>
                <tr>
                    <th><label for="ist_fb_from_name">From Name (Emails)</label></th>
                    <td><input type="text" id="ist_fb_from_name" name="ist_fb_from_name" value="<?php echo esc_attr( $from_name ); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th><label for="ist_fb_from_email">From Email</label></th>
                    <td><input type="email" id="ist_fb_from_email" name="ist_fb_from_email" value="<?php echo esc_attr( $from_email ); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th><label for="ist_fb_currency">Default Currency</label></th>
                    <td>
                        <select id="ist_fb_currency" name="ist_fb_currency">
                            <?php foreach ( [ 'USD', 'NPR', 'EUR', 'GBP', 'AUD' ] as $c ) : ?>
                                <option value="<?php echo esc_attr( $c ); ?>" <?php selected( $currency, $c ); ?>><?php echo esc_html( $c ); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th><label for="ist_fb_whatsapp">WhatsApp Number</label></th>
                    <td>
                        <input type="text" id="ist_fb_whatsapp" name="ist_fb_whatsapp" value="<?php echo esc_attr( $whatsapp ); ?>" class="regular-text" placeholder="+977 98XXXXXXXX">
                        <p class="description">Shown on booking confirmation page and emails.</p>
                    </td>
                </tr>
            </table>
            <p class="description" style="margin-left:0;">
                <strong>Duffel API Key</strong>: Add <code>define('DUFFEL_API_KEY', 'your-key');</code> to <code>wp-config.php</code>.
                <?php echo defined( 'DUFFEL_API_KEY' ) && DUFFEL_API_KEY ? '<span style="color:#22c55e;">✓ Key detected.</span>' : '<span style="color:#ef4444;">✗ Key not found.</span>'; ?>
            </p>
            <?php submit_button( 'Save Settings' ); ?>
        </form>
    </div>
    <?php
}

// ── Meta box on ist_booking edit screen ───────────────────────────────────────
add_action( 'add_meta_boxes', 'ist_fb_add_meta_boxes' );
function ist_fb_add_meta_boxes(): void {
    add_meta_box(
        'ist_fb_booking_details',
        'Booking Details',
        'ist_fb_booking_meta_box',
        'ist_booking',
        'normal',
        'high'
    );
}

function ist_fb_booking_meta_box( WP_Post $post ): void {
    $meta = IST_Booking_Manager::get_meta( $post->ID );
    $nonce = wp_create_nonce( 'ist_fb_update_status' );
    ?>
    <style>
    .ist-fb-meta-grid { display:grid; grid-template-columns:1fr 1fr; gap:0; }
    .ist-fb-meta-grid dt { font-weight:600; padding:8px 12px; background:#f9fafb; border-bottom:1px solid #e5e7eb; font-size:.85rem; }
    .ist-fb-meta-grid dd { padding:8px 12px; border-bottom:1px solid #e5e7eb; margin:0; font-size:.85rem; }
    .ist-fb-section { margin-bottom:20px; }
    .ist-fb-section h4 { margin:0 0 8px; font-size:.9rem; text-transform:uppercase; letter-spacing:.05em; color:#6b7280; }
    </style>

    <div class="ist-fb-section">
        <h4>Flight Info</h4>
        <dl class="ist-fb-meta-grid">
            <dt>Reference</dt><dd><?php echo esc_html( $meta['reference'] ); ?></dd>
            <dt>Route</dt><dd><?php echo esc_html( $meta['dep_airport'] . ' → ' . $meta['arr_airport'] ); ?></dd>
            <dt>Departure</dt><dd><?php echo esc_html( $meta['dep_time'] ?: '—' ); ?></dd>
            <dt>Carrier</dt><dd><?php echo esc_html( $meta['carrier'] ?: '—' ); ?></dd>
            <dt>Duffel Order ID</dt><dd><?php echo esc_html( $meta['duffel_order'] ?: '—' ); ?></dd>
            <dt>Total</dt><dd><?php echo esc_html( $meta['currency'] . ' ' . $meta['total'] ); ?></dd>
            <dt>Payment Method</dt><dd><?php echo esc_html( $meta['payment_method'] ?: '—' ); ?></dd>
            <dt>Created</dt><dd><?php echo esc_html( $meta['created'] ); ?></dd>
        </dl>
    </div>

    <div class="ist-fb-section">
        <h4>Lead Passenger</h4>
        <dl class="ist-fb-meta-grid">
            <dt>Name</dt><dd><?php echo esc_html( $meta['lead_name'] ); ?></dd>
            <dt>Email</dt><dd><a href="mailto:<?php echo esc_attr( $meta['lead_email'] ); ?>"><?php echo esc_html( $meta['lead_email'] ); ?></a></dd>
            <dt>Phone</dt><dd><?php echo esc_html( $meta['lead_phone'] ?: '—' ); ?></dd>
        </dl>
    </div>

    <?php if ( ! empty( $meta['passengers'] ) && is_array( $meta['passengers'] ) ) : ?>
    <div class="ist-fb-section">
        <h4>All Passengers</h4>
        <table class="wp-list-table widefat fixed" style="font-size:.85rem;">
            <thead><tr><th>#</th><th>Name</th><th>DOB</th><th>Passport</th><th>Nationality</th></tr></thead>
            <tbody>
                <?php foreach ( $meta['passengers'] as $i => $pax ) : ?>
                <tr>
                    <td><?php echo $i + 1; ?></td>
                    <td><?php echo esc_html( ( $pax['title'] ?? '' ) . ' ' . ( $pax['given_name'] ?? '' ) . ' ' . ( $pax['family_name'] ?? '' ) ); ?></td>
                    <td><?php echo esc_html( $pax['born_on'] ?? '—' ); ?></td>
                    <td><?php echo esc_html( $pax['passport_number'] ?? '—' ); ?></td>
                    <td><?php echo esc_html( $pax['nationality'] ?? '—' ); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>

    <?php if ( ! empty( $meta['addons'] ) && is_array( $meta['addons'] ) ) : ?>
    <div class="ist-fb-section">
        <h4>Add-ons</h4>
        <ul>
            <?php foreach ( $meta['addons'] as $addon ) :
                $name = is_array( $addon ) ? ( $addon['name'] ?? print_r( $addon, true ) ) : $addon;
                echo '<li>' . esc_html( $name ) . '</li>';
            endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

    <div class="ist-fb-section">
        <h4>Status</h4>
        <div style="display:flex;gap:12px;align-items:center;">
            <select id="ist-fb-status-select" style="padding:6px 10px;border-radius:4px;border:1px solid #d1d5db;">
                <?php
                $statuses = [ 'pending', 'confirmed', 'cancelled', 'refunded', 'failed' ];
                foreach ( $statuses as $s ) {
                    printf(
                        '<option value="%s" %s>%s</option>',
                        esc_attr( $s ),
                        selected( $meta['status'], $s, false ),
                        esc_html( ucfirst( $s ) )
                    );
                }
                ?>
            </select>
            <button type="button" id="ist-fb-update-status" class="button button-primary" data-id="<?php echo esc_attr( $post->ID ); ?>" data-nonce="<?php echo esc_attr( $nonce ); ?>">
                Update Status
            </button>
            <span id="ist-fb-status-msg" style="display:none;color:#22c55e;">✓ Saved</span>
        </div>
    </div>

    <?php if ( $meta['status'] !== 'cancelled' && $meta['duffel_order'] ) : ?>
    <div class="ist-fb-section">
        <h4>Cancellation</h4>
        <button type="button" class="button ist-fb-cancel" data-id="<?php echo esc_attr( $post->ID ); ?>" style="border-color:#ef4444;color:#ef4444;">
            Cancel via Duffel
        </button>
        <p class="description">Sends a cancellation request to Duffel and updates status.</p>
    </div>
    <?php endif; ?>

    <script>
    document.getElementById('ist-fb-update-status')?.addEventListener('click', function() {
        var btn   = this;
        var status = document.getElementById('ist-fb-status-select').value;
        var msg   = document.getElementById('ist-fb-status-msg');
        btn.disabled = true;
        fetch(ajaxurl, {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'action=ist_fb_update_status&post_id=' + btn.dataset.id + '&status=' + status + '&nonce=' + btn.dataset.nonce
        })
        .then(r => r.json())
        .then(function(data) {
            btn.disabled = false;
            if (data.success) { msg.style.display = 'inline'; setTimeout(() => msg.style.display = 'none', 2000); }
            else { alert(data.data || 'Error'); }
        });
    });

    document.querySelector('.ist-fb-cancel')?.addEventListener('click', function() {
        if (!confirm('Cancel this booking and send request to Duffel?')) return;
        var btn = this;
        btn.disabled = true;
        fetch(ajaxurl, {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'action=ist_fb_cancel_booking&post_id=' + btn.dataset.id + '&nonce=<?php echo esc_js( wp_create_nonce( 'ist_fb_cancel' ) ); ?>'
        })
        .then(r => r.json())
        .then(function(data) {
            if (data.success) { location.reload(); }
            else { btn.disabled = false; alert(data.data || 'Error'); }
        });
    });
    </script>
    <?php
}

// ── Custom admin columns for ist_booking ─────────────────────────────────────
add_filter( 'manage_ist_booking_posts_columns', 'ist_fb_booking_columns' );
function ist_fb_booking_columns( array $cols ): array {
    unset( $cols['date'] );
    return array_merge( $cols, [
        'reference'  => 'Reference',
        'route'      => 'Route',
        'passenger'  => 'Passenger',
        'total'      => 'Total',
        'fb_status'  => 'Status',
        'created'    => 'Booked',
    ] );
}

add_action( 'manage_ist_booking_posts_custom_column', 'ist_fb_booking_column_data', 10, 2 );
function ist_fb_booking_column_data( string $col, int $post_id ): void {
    $meta = IST_Booking_Manager::get_meta( $post_id );
    switch ( $col ) {
        case 'reference':  echo '<strong>' . esc_html( $meta['reference'] ) . '</strong>'; break;
        case 'route':      echo esc_html( $meta['dep_airport'] . ' → ' . $meta['arr_airport'] ); break;
        case 'passenger':  echo esc_html( $meta['lead_name'] ) . '<br><a href="mailto:' . esc_attr( $meta['lead_email'] ) . '" style="font-size:.85em;">' . esc_html( $meta['lead_email'] ) . '</a>'; break;
        case 'total':      echo esc_html( $meta['currency'] . ' ' . $meta['total'] ); break;
        case 'fb_status':  ist_fb_status_badge( $meta['status'] ?? 'pending' ); break;
        case 'created':    echo esc_html( substr( $meta['created'], 0, 10 ) ); break;
    }
}

add_filter( 'manage_edit-ist_booking_sortable_columns', 'ist_fb_sortable_columns' );
function ist_fb_sortable_columns( array $cols ): array {
    $cols['fb_status'] = 'fb_status';
    $cols['created']   = 'created';
    return $cols;
}
