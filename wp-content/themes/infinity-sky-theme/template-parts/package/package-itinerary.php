<?php
/**
 * Package Itinerary tab — accordion per day.
 */

$itinerary    = $args['itinerary']    ?? [];
$duration_days= $args['duration_days'] ?? 12;

// Build a default itinerary if none saved yet
if ( ! $itinerary ) {
    $itinerary = [
        [ 'day_number' => 1,  'title' => 'Arrive Kathmandu', 'altitude' => 1340, 'distance' => 0,  'description' => 'Airport pickup and transfer to your Thamel hotel. Welcome briefing, equipment check, and evening walk through Thamel.', 'accommodation' => 'Hotel in Kathmandu', 'meals' => 'Welcome dinner' ],
        [ 'day_number' => 2,  'title' => 'Kathmandu Sightseeing + Flight Day', 'altitude' => 1340, 'distance' => 0,  'description' => 'Morning cultural sightseeing (Pashupatinath, Boudhanath). Afternoon rest and preparation for early morning flight.', 'accommodation' => 'Hotel in Kathmandu', 'meals' => 'Breakfast' ],
        [ 'day_number' => 3,  'title' => 'Fly to Trailhead / Trek Begins', 'altitude' => 2840, 'distance' => 8,  'description' => 'Early morning domestic flight followed by the first day of trekking. Gentle ascent through rhododendron forests and Sherpa villages. Arrive at first teahouse for dinner and rest.', 'accommodation' => 'Teahouse', 'meals' => 'Breakfast, Lunch, Dinner' ],
        [ 'day_number' => 4,  'title' => 'Acclimatisation Walk', 'altitude' => 3440, 'distance' => 12, 'description' => 'Full day acclimatisation walk to a higher viewpoint then descend to sleep lower. Classic "climb high, sleep low" principle. Excellent panoramic views. Visit a local monastery.', 'accommodation' => 'Teahouse', 'meals' => 'Breakfast, Lunch, Dinner' ],
        [ 'day_number' => 5,  'title' => 'Trek to Namche Bazaar', 'altitude' => 3440, 'distance' => 11, 'description' => 'Steady ascent with several suspension bridge crossings. Arrive in the bustling Sherpa capital — visit the colourful Saturday market if timing aligns. Hot shower, good food, and well-earned rest.', 'accommodation' => 'Teahouse', 'meals' => 'Breakfast, Lunch, Dinner' ],
        [ 'day_number' => 6,  'title' => 'Rest Day / Acclimatisation Hike', 'altitude' => 3440, 'distance' => 8,  'description' => 'Mandatory rest day in Namche Bazaar. Optional morning hike to the Everest View Hotel for your first glimpse of Everest. Afternoon leisure — explore shops and cafés.', 'accommodation' => 'Teahouse', 'meals' => 'Breakfast, Lunch, Dinner' ],
        [ 'day_number' => 7,  'title' => 'Trek Higher', 'altitude' => 3870, 'distance' => 10, 'description' => 'Mostly flat trail with stunning Himalayan panoramas. Pass through yak pastures and mani stone walls into a pristine high-altitude valley. Arrive at teahouse with expansive mountain views.', 'accommodation' => 'Teahouse', 'meals' => 'Breakfast, Lunch, Dinner' ],
        [ 'day_number' => 8,  'title' => 'Approach Viewpoint Village', 'altitude' => 4280, 'distance' => 9,  'description' => 'Continue ascending with thin air requiring a measured pace. Stop at viewpoints to absorb 360° panoramas. Overnight in a classic teahouse frequented by mountaineering expeditions.', 'accommodation' => 'Teahouse', 'meals' => 'Breakfast, Lunch, Dinner' ],
        [ 'day_number' => 9,  'title' => 'Trek to Base Camp / High Point', 'altitude' => 5364, 'distance' => 8,  'description' => 'Early start to reach the highest point of the trek. Breathtaking scenery, glaciers, and — if weather permits — clear views of the summit massif. Celebrate your achievement before descending to a lower camp for the night.', 'accommodation' => 'Teahouse', 'meals' => 'Breakfast, Lunch, Dinner' ],
        [ 'day_number' => 10, 'title' => 'Descent Day 1', 'altitude' => 3440, 'distance' => 16, 'description' => 'Long but rewarding descent back through familiar terrain. The altitude reduction brings renewed energy. Good opportunity to chat with your guide and reflect on the journey.', 'accommodation' => 'Teahouse', 'meals' => 'Breakfast, Lunch, Dinner' ],
        [ 'day_number' => 11, 'title' => 'Descent to Trailhead + Fly Kathmandu', 'altitude' => 2840, 'distance' => 12, 'description' => 'Final descent to the trailhead airport. Afternoon domestic flight back to Kathmandu. Evening free for shopping or restaurant of your choice in Thamel.', 'accommodation' => 'Hotel in Kathmandu', 'meals' => 'Breakfast, Lunch' ],
        [ 'day_number' => 12, 'title' => 'Depart Kathmandu', 'altitude' => 1340, 'distance' => 0,  'description' => 'Transfer to Tribhuvan International Airport for your onward flight. Safe travels — we hope to see you in Nepal again!', 'accommodation' => '—', 'meals' => 'Breakfast' ],
    ];
    $itinerary = array_slice( $itinerary, 0, $duration_days );
}
?>

<div class="ist-pkg-itinerary">

    <div class="ist-pkg-itinerary__header">
        <h3><?php esc_html_e( 'Day-by-Day Itinerary', 'infinity-sky' ); ?></h3>
        <p class="ist-pkg-itinerary__summary">
            <?php printf(
                esc_html__( '%d days of trekking adventure. Click each day to expand details.', 'infinity-sky' ),
                count( $itinerary )
            ); ?>
        </p>
    </div>

    <div class="ist-itinerary-list">
        <?php foreach ( $itinerary as $i => $day ) :
            $day_num    = $day['day_number']    ?? ( $i + 1 );
            $title      = $day['title']         ?? "Day $day_num";
            $alt        = $day['altitude']      ?? '';
            $dist       = $day['distance']      ?? '';
            $desc       = $day['description']   ?? '';
            $accom      = $day['accommodation'] ?? '';
            $meals      = $day['meals']         ?? '';
            $first_open = ( $i === 0 ); ?>

        <div class="ist-itinerary-item <?php echo $first_open ? 'ist-itinerary-item--open' : ''; ?>">
            <button class="ist-itinerary-trigger" type="button"
                    aria-expanded="<?php echo $first_open ? 'true' : 'false'; ?>"
                    aria-controls="itin-body-<?php echo esc_attr( $i ); ?>">

                <span class="ist-itinerary-trigger__day">
                    <?php esc_html_e( 'Day', 'infinity-sky' ); ?> <?php echo esc_html( $day_num ); ?>
                </span>

                <span class="ist-itinerary-trigger__title"><?php echo esc_html( $title ); ?></span>

                <span class="ist-itinerary-trigger__meta">
                    <?php if ( $alt ) : ?>
                    <span class="ist-itinerary-trigger__alt">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
                        <?php echo esc_html( number_format( $alt ) ); ?>m
                    </span>
                    <?php endif; ?>
                    <?php if ( $dist ) : ?>
                    <span class="ist-itinerary-trigger__dist">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        <?php echo esc_html( $dist ); ?> km
                    </span>
                    <?php endif; ?>
                </span>

                <svg class="ist-itinerary-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><polyline points="6 9 12 15 18 9"/></svg>
            </button>

            <div class="ist-itinerary-body <?php echo $first_open ? 'ist-itinerary-body--open' : ''; ?>"
                 id="itin-body-<?php echo esc_attr( $i ); ?>">
                <div class="ist-itinerary-body__inner">

                    <?php if ( $desc ) : ?>
                    <p class="ist-itinerary-body__desc"><?php echo wp_kses_post( $desc ); ?></p>
                    <?php endif; ?>

                    <?php if ( $accom || $meals ) : ?>
                    <div class="ist-itinerary-body__footer">
                        <?php if ( $accom ) : ?>
                        <div class="ist-itin-meta">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                            <span><strong><?php esc_html_e( 'Stay:', 'infinity-sky' ); ?></strong> <?php echo esc_html( $accom ); ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if ( $meals ) : ?>
                        <div class="ist-itin-meta">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/></svg>
                            <span><strong><?php esc_html_e( 'Meals:', 'infinity-sky' ); ?></strong> <?php echo esc_html( $meals ); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>

                </div>
            </div>
        </div><!-- /.ist-itinerary-item -->

        <?php endforeach; ?>
    </div><!-- /.ist-itinerary-list -->

</div><!-- /.ist-pkg-itinerary -->
