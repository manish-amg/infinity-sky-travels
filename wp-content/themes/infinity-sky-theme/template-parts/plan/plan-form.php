<?php
/**
 * Plan My Trip — 4-step multi-step form.
 * $args: packages, prefill_package, prefill_pax, prefill_date
 */

$packages        = $args['packages']        ?? [];
$prefill_package = $args['prefill_package'] ?? 0;
$prefill_pax     = $args['prefill_pax']     ?? 2;
$prefill_date    = $args['prefill_date']    ?? '';

$regions = [
    'everest'      => __( 'Everest / Khumbu',    'infinity-sky' ),
    'annapurna'    => __( 'Annapurna Circuit',   'infinity-sky' ),
    'langtang'     => __( 'Langtang Valley',     'infinity-sky' ),
    'mustang'      => __( 'Upper Mustang',        'infinity-sky' ),
    'manaslu'      => __( 'Manaslu Circuit',     'infinity-sky' ),
    'dolpo'        => __( 'Dolpo (Remote West)', 'infinity-sky' ),
    'rara'         => __( 'Rara Lake',           'infinity-sky' ),
    'cultural'     => __( 'Cultural & City Tour','infinity-sky' ),
    'custom'       => __( 'Let me describe it',  'infinity-sky' ),
];

$accommodation_types = [
    'teahouse' => [ 'label' => __( 'Teahouse', 'infinity-sky' ),       'desc' => __( 'Local teahouse lodges, shared facilities. Authentic experience.', 'infinity-sky' ), 'price_range' => __( 'Budget', 'infinity-sky' ), 'icon' => '🏚' ],
    'lodge'    => [ 'label' => __( 'Mid-Range Lodge', 'infinity-sky' ), 'desc' => __( 'Comfortable en-suite rooms where available, better meals.', 'infinity-sky' ),          'price_range' => __( 'Mid',    'infinity-sky' ), 'icon' => '🏨' ],
    'luxury'   => [ 'label' => __( 'Luxury / Premium', 'infinity-sky' ),'desc' => __( 'Best available facilities at each stop, premium service.', 'infinity-sky' ),           'price_range' => __( 'Luxury', 'infinity-sky' ), 'icon' => '⭐' ],
];

$budgets = [
    'under-800'  => __( 'Under $800 pp',    'infinity-sky' ),
    '800-1500'   => __( '$800 – $1,500 pp', 'infinity-sky' ),
    '1500-2500'  => __( '$1,500 – $2,500 pp','infinity-sky' ),
    '2500-plus'  => __( '$2,500+ pp',       'infinity-sky' ),
    'flexible'   => __( 'Flexible — show me options', 'infinity-sky' ),
];

$activities = [
    'trekking'   => __( '🥾 Trekking',          'infinity-sky' ),
    'climbing'   => __( '🧗 Peak Climbing',      'infinity-sky' ),
    'cycling'    => __( '🚵 Mountain Biking',    'infinity-sky' ),
    'rafting'    => __( '🛶 White-Water Rafting','infinity-sky' ),
    'cultural'   => __( '🏛 Cultural Tours',     'infinity-sky' ),
    'wildlife'   => __( '🐘 Wildlife Safari',    'infinity-sky' ),
    'yoga'       => __( '🧘 Yoga & Wellness',    'infinity-sky' ),
    'photography'=> __( '📷 Photography Trek',   'infinity-sky' ),
];
?>

<div class="ist-plan-form-wrap" id="ist-plan-form-wrap">

    <!-- Step progress bar -->
    <div class="ist-plan-steps" role="progressbar" aria-label="<?php esc_attr_e( 'Form progress', 'infinity-sky' ); ?>" aria-valuemin="1" aria-valuemax="4" aria-valuenow="1">
        <?php
        $step_labels = [
            __( 'Your Trip',   'infinity-sky' ),
            __( 'Preferences', 'infinity-sky' ),
            __( 'Group',       'infinity-sky' ),
            __( 'Contact',     'infinity-sky' ),
        ];
        foreach ( $step_labels as $i => $label ) :
            $n = $i + 1; ?>
        <div class="ist-plan-step <?php echo $n === 1 ? 'ist-plan-step--active' : ''; ?>" data-step="<?php echo $n; ?>">
            <div class="ist-plan-step__dot"><?php echo $n; ?></div>
            <span class="ist-plan-step__label"><?php echo esc_html( $label ); ?></span>
        </div>
        <?php if ( $n < 4 ) : ?>
        <div class="ist-plan-step__line"></div>
        <?php endif; endforeach; ?>
    </div>

    <!-- Form -->
    <form id="ist-plan-form" class="ist-plan-form" novalidate
          data-ajax="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>"
          data-nonce="<?php echo esc_attr( wp_create_nonce( 'ist_plan_trip' ) ); ?>">

        <!-- ── Step 1: Trip basics ──────────────────────────────── -->
        <fieldset class="ist-plan-fieldset ist-plan-fieldset--active" id="ist-step-1" data-step="1">
            <legend class="ist-plan-fieldset__legend">
                <span class="ist-plan-fieldset__num">1</span>
                <?php esc_html_e( 'Tell Us About Your Trip', 'infinity-sky' ); ?>
            </legend>

            <!-- Destination region -->
            <div class="ist-plan-field">
                <label class="ist-plan-label"><?php esc_html_e( 'Where would you like to go?', 'infinity-sky' ); ?> <span aria-hidden="true">*</span></label>
                <div class="ist-plan-region-grid" role="group" aria-label="<?php esc_attr_e( 'Region selection', 'infinity-sky' ); ?>">
                    <?php foreach ( $regions as $val => $name ) : ?>
                    <label class="ist-plan-region-card">
                        <input type="radio" name="region" value="<?php echo esc_attr( $val ); ?>" required>
                        <span class="ist-plan-region-card__inner"><?php echo esc_html( $name ); ?></span>
                    </label>
                    <?php endforeach; ?>
                </div>
                <span class="ist-plan-error" id="error-region"></span>
            </div>

            <!-- Specific package interest (optional, pre-filled from URL) -->
            <?php if ( $packages ) : ?>
            <div class="ist-plan-field">
                <label class="ist-plan-label" for="plan-package">
                    <?php esc_html_e( 'Based on an existing package? (optional)', 'infinity-sky' ); ?>
                </label>
                <select id="plan-package" name="base_package" class="ist-input">
                    <option value=""><?php esc_html_e( '— Starting from scratch —', 'infinity-sky' ); ?></option>
                    <?php foreach ( $packages as $pkg ) : ?>
                    <option value="<?php echo esc_attr( $pkg->ID ); ?>"
                        <?php selected( $prefill_package, $pkg->ID ); ?>>
                        <?php echo esc_html( $pkg->post_title ); ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php endif; ?>

            <!-- Date range -->
            <div class="ist-plan-field-row">
                <div class="ist-plan-field">
                    <label class="ist-plan-label" for="plan-depart">
                        <?php esc_html_e( 'Preferred Departure Date', 'infinity-sky' ); ?> <span aria-hidden="true">*</span>
                    </label>
                    <input type="text" id="plan-depart" name="depart_date" class="ist-input"
                           placeholder="<?php esc_attr_e( 'Select date…', 'infinity-sky' ); ?>"
                           data-flatpickr data-min-date="today" autocomplete="off" required
                           value="<?php echo esc_attr( $prefill_date ); ?>">
                    <span class="ist-plan-error" id="error-depart_date"></span>
                </div>
                <div class="ist-plan-field">
                    <label class="ist-plan-label" for="plan-flexible"><?php esc_html_e( 'Date Flexibility', 'infinity-sky' ); ?></label>
                    <select id="plan-flexible" name="date_flexibility" class="ist-input">
                        <option value="exact"><?php  esc_html_e( 'Fixed — exact date',    'infinity-sky' ); ?></option>
                        <option value="1week" selected><?php esc_html_e( '± 1 week',     'infinity-sky' ); ?></option>
                        <option value="2weeks"><?php esc_html_e( '± 2 weeks',            'infinity-sky' ); ?></option>
                        <option value="month"><?php  esc_html_e( 'Anytime this month',   'infinity-sky' ); ?></option>
                        <option value="season"><?php esc_html_e( 'Anytime this season',  'infinity-sky' ); ?></option>
                    </select>
                </div>
            </div>

            <!-- Duration -->
            <div class="ist-plan-field">
                <label class="ist-plan-label"><?php esc_html_e( 'Trip Duration', 'infinity-sky' ); ?> <span aria-hidden="true">*</span></label>
                <div class="ist-plan-duration-row" role="group">
                    <?php
                    $durations = [
                        'under-7'  => __( 'Under 7 days', 'infinity-sky' ),
                        '7-10'     => __( '7–10 days',    'infinity-sky' ),
                        '11-14'    => __( '11–14 days',   'infinity-sky' ),
                        '15-21'    => __( '15–21 days',   'infinity-sky' ),
                        '21-plus'  => __( '21+ days',     'infinity-sky' ),
                    ];
                    foreach ( $durations as $val => $label ) : ?>
                    <label class="ist-plan-pill-radio">
                        <input type="radio" name="duration" value="<?php echo esc_attr( $val ); ?>" required>
                        <span><?php echo esc_html( $label ); ?></span>
                    </label>
                    <?php endforeach; ?>
                </div>
                <span class="ist-plan-error" id="error-duration"></span>
            </div>

            <div class="ist-plan-fieldset__footer">
                <span></span>
                <button type="button" class="btn-primary ist-plan-next" data-target="2">
                    <?php esc_html_e( 'Next: Preferences', 'infinity-sky' ); ?>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </button>
            </div>
        </fieldset>

        <!-- ── Step 2: Preferences ──────────────────────────────── -->
        <fieldset class="ist-plan-fieldset" id="ist-step-2" data-step="2">
            <legend class="ist-plan-fieldset__legend">
                <span class="ist-plan-fieldset__num">2</span>
                <?php esc_html_e( 'Trip Preferences', 'infinity-sky' ); ?>
            </legend>

            <!-- Activities -->
            <div class="ist-plan-field">
                <label class="ist-plan-label"><?php esc_html_e( 'What activities interest you? (select all that apply)', 'infinity-sky' ); ?></label>
                <div class="ist-plan-activity-grid" role="group" aria-label="<?php esc_attr_e( 'Activities', 'infinity-sky' ); ?>">
                    <?php foreach ( $activities as $val => $label ) : ?>
                    <label class="ist-plan-activity-card">
                        <input type="checkbox" name="activities[]" value="<?php echo esc_attr( $val ); ?>">
                        <span><?php echo esc_html( $label ); ?></span>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Accommodation style -->
            <div class="ist-plan-field">
                <label class="ist-plan-label"><?php esc_html_e( 'Accommodation Style', 'infinity-sky' ); ?> <span aria-hidden="true">*</span></label>
                <div class="ist-plan-accom-grid" role="group">
                    <?php foreach ( $accommodation_types as $val => $a ) : ?>
                    <label class="ist-plan-accom-card">
                        <input type="radio" name="accommodation" value="<?php echo esc_attr( $val ); ?>" required>
                        <span class="ist-plan-accom-card__inner">
                            <span class="ist-plan-accom-card__icon"><?php echo $a['icon']; ?></span>
                            <span class="ist-plan-accom-card__label"><?php echo esc_html( $a['label'] ); ?></span>
                            <span class="ist-plan-accom-card__price"><?php echo esc_html( $a['price_range'] ); ?></span>
                            <span class="ist-plan-accom-card__desc"><?php echo esc_html( $a['desc'] ); ?></span>
                        </span>
                    </label>
                    <?php endforeach; ?>
                </div>
                <span class="ist-plan-error" id="error-accommodation"></span>
            </div>

            <!-- Budget -->
            <div class="ist-plan-field">
                <label class="ist-plan-label"><?php esc_html_e( 'Budget per Person (all inclusive)', 'infinity-sky' ); ?> <span aria-hidden="true">*</span></label>
                <div class="ist-plan-duration-row" role="group">
                    <?php foreach ( $budgets as $val => $label ) : ?>
                    <label class="ist-plan-pill-radio">
                        <input type="radio" name="budget" value="<?php echo esc_attr( $val ); ?>" required>
                        <span><?php echo esc_html( $label ); ?></span>
                    </label>
                    <?php endforeach; ?>
                </div>
                <span class="ist-plan-error" id="error-budget"></span>
            </div>

            <!-- Special requests -->
            <div class="ist-plan-field">
                <label class="ist-plan-label" for="plan-special"><?php esc_html_e( 'Any special requests or notes?', 'infinity-sky' ); ?></label>
                <textarea id="plan-special" name="special_requests" class="ist-input ist-plan-textarea" rows="4"
                          placeholder="<?php esc_attr_e( 'E.g. vegetarian meals, photography focus, anniversary trip, medical conditions, specific peaks to visit…', 'infinity-sky' ); ?>"></textarea>
            </div>

            <div class="ist-plan-fieldset__footer">
                <button type="button" class="btn-outline--dark btn-outline ist-plan-prev" data-target="1">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                    <?php esc_html_e( 'Back', 'infinity-sky' ); ?>
                </button>
                <button type="button" class="btn-primary ist-plan-next" data-target="3">
                    <?php esc_html_e( 'Next: Group Info', 'infinity-sky' ); ?>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </button>
            </div>
        </fieldset>

        <!-- ── Step 3: Group details ─────────────────────────────── -->
        <fieldset class="ist-plan-fieldset" id="ist-step-3" data-step="3">
            <legend class="ist-plan-fieldset__legend">
                <span class="ist-plan-fieldset__num">3</span>
                <?php esc_html_e( 'Your Group', 'infinity-sky' ); ?>
            </legend>

            <!-- Group size -->
            <div class="ist-plan-field">
                <label class="ist-plan-label"><?php esc_html_e( 'Group Composition', 'infinity-sky' ); ?></label>
                <div class="ist-plan-pax-row">
                    <?php
                    $pax_types = [
                        'adults'   => [ 'label' => __( 'Adults',          'infinity-sky' ), 'sub' => __( '18+',         'infinity-sky' ), 'min' => 1, 'default' => $prefill_pax ],
                        'children' => [ 'label' => __( 'Children',        'infinity-sky' ), 'sub' => __( '4–17 years',  'infinity-sky' ), 'min' => 0, 'default' => 0 ],
                        'seniors'  => [ 'label' => __( 'Seniors',         'infinity-sky' ), 'sub' => __( '65+ years',   'infinity-sky' ), 'min' => 0, 'default' => 0 ],
                    ];
                    foreach ( $pax_types as $key => $type ) : ?>
                    <div class="ist-plan-pax-item">
                        <div>
                            <strong><?php echo esc_html( $type['label'] ); ?></strong>
                            <span><?php echo esc_html( $type['sub'] ); ?></span>
                        </div>
                        <div class="ist-plan-stepper">
                            <button type="button" class="ist-plan-stepper__btn" data-action="minus" data-target="pax-<?php echo esc_attr( $key ); ?>" aria-label="<?php esc_attr_e( 'Decrease', 'infinity-sky' ); ?>">−</button>
                            <input type="number" id="pax-<?php echo esc_attr( $key ); ?>" name="pax[<?php echo esc_attr( $key ); ?>]"
                                   class="ist-plan-stepper__val" value="<?php echo esc_attr( $type['default'] ); ?>"
                                   min="<?php echo esc_attr( $type['min'] ); ?>" max="50"
                                   aria-label="<?php echo esc_attr( $type['label'] ); ?>">
                            <button type="button" class="ist-plan-stepper__btn" data-action="plus" data-target="pax-<?php echo esc_attr( $key ); ?>" aria-label="<?php esc_attr_e( 'Increase', 'infinity-sky' ); ?>">+</button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Fitness level -->
            <div class="ist-plan-field">
                <label class="ist-plan-label"><?php esc_html_e( 'Group Fitness Level', 'infinity-sky' ); ?></label>
                <div class="ist-plan-duration-row" role="group">
                    <?php
                    $fitness = [
                        'low'        => __( '🚶 Low — light walking',         'infinity-sky' ),
                        'moderate'   => __( '🏃 Moderate — regular exercise', 'infinity-sky' ),
                        'good'       => __( '⛰ Good — hike regularly',        'infinity-sky' ),
                        'excellent'  => __( '🏔 Excellent — athlete level',   'infinity-sky' ),
                    ];
                    foreach ( $fitness as $val => $label ) : ?>
                    <label class="ist-plan-pill-radio">
                        <input type="radio" name="fitness_level" value="<?php echo esc_attr( $val ); ?>">
                        <span><?php echo esc_html( $label ); ?></span>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Previous experience -->
            <div class="ist-plan-field">
                <label class="ist-plan-label" for="plan-experience"><?php esc_html_e( 'Previous Trekking Experience', 'infinity-sky' ); ?></label>
                <select id="plan-experience" name="experience" class="ist-input">
                    <option value="none"><?php        esc_html_e( 'None — first time trekking',     'infinity-sky' ); ?></option>
                    <option value="beginner"><?php    esc_html_e( 'Beginner — a few day hikes',      'infinity-sky' ); ?></option>
                    <option value="intermediate"><?php esc_html_e( 'Intermediate — multi-day treks', 'infinity-sky' ); ?></option>
                    <option value="advanced"><?php    esc_html_e( 'Advanced — Himalayan experience', 'infinity-sky' ); ?></option>
                </select>
            </div>

            <!-- Group type -->
            <div class="ist-plan-field">
                <label class="ist-plan-label"><?php esc_html_e( 'Travelling As', 'infinity-sky' ); ?></label>
                <div class="ist-plan-duration-row" role="group">
                    <?php
                    $group_types = [
                        'solo'        => __( '🙋 Solo',          'infinity-sky' ),
                        'couple'      => __( '👫 Couple',         'infinity-sky' ),
                        'family'      => __( '👨‍👩‍👧 Family',          'infinity-sky' ),
                        'friends'     => __( '👥 Friends',        'infinity-sky' ),
                        'corporate'   => __( '💼 Corporate Group','infinity-sky' ),
                    ];
                    foreach ( $group_types as $val => $label ) : ?>
                    <label class="ist-plan-pill-radio">
                        <input type="radio" name="group_type" value="<?php echo esc_attr( $val ); ?>">
                        <span><?php echo esc_html( $label ); ?></span>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="ist-plan-fieldset__footer">
                <button type="button" class="btn-outline--dark btn-outline ist-plan-prev" data-target="2">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                    <?php esc_html_e( 'Back', 'infinity-sky' ); ?>
                </button>
                <button type="button" class="btn-primary ist-plan-next" data-target="4">
                    <?php esc_html_e( 'Next: Contact Details', 'infinity-sky' ); ?>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </button>
            </div>
        </fieldset>

        <!-- ── Step 4: Contact details ──────────────────────────── -->
        <fieldset class="ist-plan-fieldset" id="ist-step-4" data-step="4">
            <legend class="ist-plan-fieldset__legend">
                <span class="ist-plan-fieldset__num">4</span>
                <?php esc_html_e( 'Your Contact Details', 'infinity-sky' ); ?>
            </legend>

            <div class="ist-plan-field-row">
                <div class="ist-plan-field">
                    <label class="ist-plan-label" for="plan-first-name"><?php esc_html_e( 'First Name', 'infinity-sky' ); ?> <span aria-hidden="true">*</span></label>
                    <input type="text" id="plan-first-name" name="first_name" class="ist-input"
                           placeholder="Jane" required autocomplete="given-name">
                    <span class="ist-plan-error" id="error-first_name"></span>
                </div>
                <div class="ist-plan-field">
                    <label class="ist-plan-label" for="plan-last-name"><?php esc_html_e( 'Last Name', 'infinity-sky' ); ?> <span aria-hidden="true">*</span></label>
                    <input type="text" id="plan-last-name" name="last_name" class="ist-input"
                           placeholder="Smith" required autocomplete="family-name">
                    <span class="ist-plan-error" id="error-last_name"></span>
                </div>
            </div>

            <div class="ist-plan-field-row">
                <div class="ist-plan-field">
                    <label class="ist-plan-label" for="plan-email"><?php esc_html_e( 'Email Address', 'infinity-sky' ); ?> <span aria-hidden="true">*</span></label>
                    <input type="email" id="plan-email" name="email" class="ist-input"
                           placeholder="jane@example.com" required autocomplete="email">
                    <span class="ist-plan-error" id="error-email"></span>
                </div>
                <div class="ist-plan-field">
                    <label class="ist-plan-label" for="plan-phone"><?php esc_html_e( 'WhatsApp / Phone', 'infinity-sky' ); ?></label>
                    <input type="tel" id="plan-phone" name="phone" class="ist-input"
                           placeholder="+1 555 000 0000" autocomplete="tel">
                </div>
            </div>

            <div class="ist-plan-field">
                <label class="ist-plan-label" for="plan-nationality"><?php esc_html_e( 'Nationality', 'infinity-sky' ); ?></label>
                <input type="text" id="plan-nationality" name="nationality" class="ist-input"
                       placeholder="<?php esc_attr_e( 'e.g. Australian', 'infinity-sky' ); ?>" autocomplete="country-name">
            </div>

            <div class="ist-plan-field">
                <label class="ist-plan-label"><?php esc_html_e( 'How did you hear about us?', 'infinity-sky' ); ?></label>
                <select name="referral_source" class="ist-input">
                    <option value=""><?php          esc_html_e( '— Select —',              'infinity-sky' ); ?></option>
                    <option value="google"><?php    esc_html_e( 'Google Search',            'infinity-sky' ); ?></option>
                    <option value="instagram"><?php esc_html_e( 'Instagram',                'infinity-sky' ); ?></option>
                    <option value="facebook"><?php  esc_html_e( 'Facebook',                 'infinity-sky' ); ?></option>
                    <option value="tripadvisor"><?php esc_html_e( 'TripAdvisor',            'infinity-sky' ); ?></option>
                    <option value="friend"><?php    esc_html_e( 'Friend / Word of Mouth',   'infinity-sky' ); ?></option>
                    <option value="previous"><?php  esc_html_e( 'Previous Customer',        'infinity-sky' ); ?></option>
                    <option value="other"><?php     esc_html_e( 'Other',                    'infinity-sky' ); ?></option>
                </select>
            </div>

            <!-- GDPR consent -->
            <div class="ist-plan-field">
                <label class="ist-plan-consent">
                    <input type="checkbox" name="consent" value="1" required id="plan-consent">
                    <span>
                        <?php printf(
                            esc_html__( 'I agree to the %1$sPrivacy Policy%2$s and consent to Infinity Sky Travels contacting me about my trip enquiry.', 'infinity-sky' ),
                            '<a href="' . esc_url( home_url( '/privacy-policy' ) ) . '" target="_blank" rel="noopener">',
                            '</a>'
                        ); ?>
                    </span>
                </label>
                <span class="ist-plan-error" id="error-consent"></span>
            </div>

            <div class="ist-plan-fieldset__footer">
                <button type="button" class="btn-outline--dark btn-outline ist-plan-prev" data-target="3">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                    <?php esc_html_e( 'Back', 'infinity-sky' ); ?>
                </button>
                <button type="submit" class="btn-primary btn-lg" id="ist-plan-submit">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                    <?php esc_html_e( 'Send My Trip Request', 'infinity-sky' ); ?>
                </button>
            </div>
        </fieldset>

        <!-- ── Success state ───────────────────────────────────── -->
        <div class="ist-plan-success" id="ist-plan-success" hidden aria-live="polite">
            <div class="ist-plan-success__icon">🎉</div>
            <h2><?php esc_html_e( "Your trip request is on its way!", 'infinity-sky' ); ?></h2>
            <p><?php esc_html_e( "We'll have a custom itinerary in your inbox within 24 hours. Check your spam folder just in case.", 'infinity-sky' ); ?></p>
            <div class="ist-plan-success__actions">
                <a href="<?php echo esc_url( home_url( '/packages' ) ); ?>" class="btn-primary">
                    <?php esc_html_e( 'Browse Packages', 'infinity-sky' ); ?>
                </a>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn-outline--dark btn-outline">
                    <?php esc_html_e( 'Return Home', 'infinity-sky' ); ?>
                </a>
            </div>
        </div>

    </form>
</div><!-- /.ist-plan-form-wrap -->
