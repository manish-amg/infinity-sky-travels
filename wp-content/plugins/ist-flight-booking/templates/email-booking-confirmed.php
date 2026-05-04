<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Your Flight Booking is Confirmed</title>
<!--[if mso]><noscript><xml><o:OfficeDocumentSettings><o:PixelsPerInch>96</o:PixelsPerInch></o:OfficeDocumentSettings></xml></noscript><![endif]-->
<style>
  body { margin:0; padding:0; background:#f3f4f6; font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; }
  .wrapper { max-width:600px; margin:0 auto; background:#ffffff; }
  .header  { background:linear-gradient(135deg,#1e3a5f 0%,#0f2035 100%); padding:32px 40px; text-align:center; }
  .header img { height:40px; }
  .hero    { background:linear-gradient(135deg,#f97316 0%,#ea580c 100%); padding:32px 40px; text-align:center; }
  .hero h1 { color:#fff; margin:0 0 8px; font-size:1.6rem; font-weight:700; }
  .hero p  { color:rgba(255,255,255,.85); margin:0; font-size:.95rem; }
  .ref-box { background:#fff7ed; border:2px solid #f97316; border-radius:10px; display:inline-block; padding:12px 32px; margin-top:16px; }
  .ref-box .label { font-size:.75rem; text-transform:uppercase; letter-spacing:.1em; color:#9a3412; margin-bottom:4px; }
  .ref-box .ref   { font-size:1.8rem; font-weight:700; color:#f97316; letter-spacing:.1em; }
  .content { padding:32px 40px; }
  .section-title { font-size:.7rem; text-transform:uppercase; letter-spacing:.12em; color:#6b7280; font-weight:600; margin:0 0 12px; }
  .info-grid { border:1px solid #e5e7eb; border-radius:8px; overflow:hidden; margin-bottom:24px; }
  .info-row  { display:flex; border-bottom:1px solid #e5e7eb; }
  .info-row:last-child { border-bottom:none; }
  .info-label { background:#f9fafb; padding:10px 16px; font-size:.8rem; color:#6b7280; font-weight:600; width:40%; box-sizing:border-box; }
  .info-value { padding:10px 16px; font-size:.85rem; color:#111827; width:60%; box-sizing:border-box; }
  .route-strip { background:#1e3a5f; border-radius:8px; padding:20px 24px; margin-bottom:24px; display:flex; justify-content:space-between; align-items:center; }
  .route-strip .airport { color:#fff; text-align:center; }
  .route-strip .airport .code { font-size:1.8rem; font-weight:700; color:#fff; }
  .route-strip .airport .name { font-size:.75rem; color:rgba(255,255,255,.65); margin-top:2px; }
  .route-strip .arrow { color:#f97316; font-size:1.4rem; }
  .pax-table { width:100%; border-collapse:collapse; font-size:.8rem; margin-bottom:24px; }
  .pax-table th { background:#f9fafb; padding:8px 12px; text-align:left; color:#6b7280; font-weight:600; border-bottom:2px solid #e5e7eb; }
  .pax-table td { padding:8px 12px; border-bottom:1px solid #f3f4f6; color:#374151; }
  .steps-grid { display:flex; gap:16px; margin-bottom:24px; }
  .step { flex:1; background:#f9fafb; border-radius:8px; padding:16px; text-align:center; }
  .step .num { width:28px; height:28px; border-radius:50%; background:#f97316; color:#fff; font-weight:700; font-size:.8rem; display:inline-flex; align-items:center; justify-content:center; margin-bottom:8px; }
  .step p { margin:0; font-size:.8rem; color:#374151; line-height:1.5; }
  .cta-block { text-align:center; background:#f9fafb; border-radius:8px; padding:24px; margin-bottom:24px; }
  .btn { display:inline-block; background:#f97316; color:#fff; text-decoration:none; padding:12px 28px; border-radius:6px; font-weight:700; font-size:.9rem; margin:4px; }
  .btn-outline { display:inline-block; border:2px solid #1e3a5f; color:#1e3a5f; text-decoration:none; padding:10px 24px; border-radius:6px; font-weight:700; font-size:.9rem; margin:4px; }
  .notice { background:#fffbeb; border:1px solid #fde68a; border-radius:8px; padding:16px 20px; margin-bottom:24px; font-size:.85rem; color:#92400e; }
  .footer { background:#1e3a5f; padding:24px 40px; text-align:center; }
  .footer p { color:rgba(255,255,255,.6); font-size:.75rem; margin:4px 0; }
  .footer a { color:#f97316; text-decoration:none; }
  @media (max-width:600px) {
    .header, .hero, .content { padding:24px 20px; }
    .steps-grid { flex-direction:column; }
    .route-strip { flex-direction:column; gap:12px; text-align:center; }
    .info-label, .info-value { width:100%; }
    .info-row { flex-direction:column; }
  }
</style>
</head>
<body>
<div class="wrapper">

  <!-- Header -->
  <div class="header">
    <div style="color:#fff;font-size:1.4rem;font-weight:700;letter-spacing:.05em;">
      ✈ Infinity Sky Travels
    </div>
    <div style="color:rgba(255,255,255,.6);font-size:.8rem;margin-top:4px;">infinityskytravels.com</div>
  </div>

  <!-- Hero -->
  <div class="hero">
    <div style="font-size:2rem;margin-bottom:8px;">🎉</div>
    <h1>Your Flight is Confirmed!</h1>
    <p>Your booking has been placed successfully. Please save your reference number.</p>
    <div class="ref-box">
      <div class="label">Booking Reference</div>
      <div class="ref"><?php echo esc_html( $meta['reference'] ); ?></div>
    </div>
  </div>

  <div class="content">

    <!-- Route strip -->
    <p class="section-title">Flight Details</p>
    <div class="route-strip">
      <div class="airport">
        <div class="code"><?php echo esc_html( $meta['dep_airport'] ); ?></div>
        <div class="name">Departure</div>
      </div>
      <div class="arrow">✈ ──────</div>
      <div class="airport">
        <div class="code"><?php echo esc_html( $meta['arr_airport'] ); ?></div>
        <div class="name">Arrival</div>
      </div>
    </div>

    <!-- Flight info grid -->
    <div class="info-grid">
      <div class="info-row">
        <div class="info-label">Airline</div>
        <div class="info-value"><?php echo esc_html( $meta['carrier'] ?: 'Domestic Airline' ); ?></div>
      </div>
      <div class="info-row">
        <div class="info-label">Departure Date & Time</div>
        <div class="info-value"><?php echo esc_html( $meta['dep_time'] ? date( 'D, d M Y H:i', strtotime( $meta['dep_time'] ) ) : 'To be confirmed' ); ?></div>
      </div>
      <div class="info-row">
        <div class="info-label">Total Amount</div>
        <div class="info-value" style="font-weight:700;color:#f97316;"><?php echo esc_html( $meta['currency'] . ' ' . $meta['total'] ); ?></div>
      </div>
      <div class="info-row">
        <div class="info-label">Payment Method</div>
        <div class="info-value"><?php echo esc_html( ucfirst( str_replace( '_', ' ', $meta['payment_method'] ?: 'online' ) ) ); ?></div>
      </div>
      <div class="info-row">
        <div class="info-label">Booking Date</div>
        <div class="info-value"><?php echo esc_html( date( 'D, d M Y', strtotime( $meta['created'] ) ) ); ?></div>
      </div>
    </div>

    <!-- Passenger list -->
    <?php if ( ! empty( $meta['passengers'] ) && is_array( $meta['passengers'] ) ) : ?>
    <p class="section-title">Passengers</p>
    <table class="pax-table">
      <thead>
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Date of Birth</th>
          <th>Passport</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ( $meta['passengers'] as $i => $pax ) : ?>
        <tr>
          <td><?php echo $i + 1; ?></td>
          <td><?php echo esc_html( ( $pax['title'] ?? '' ) . ' ' . ( $pax['given_name'] ?? '' ) . ' ' . ( $pax['family_name'] ?? '' ) ); ?></td>
          <td><?php echo esc_html( $pax['born_on'] ?? '—' ); ?></td>
          <td><?php echo esc_html( $pax['passport_number'] ?? '—' ); ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php endif; ?>

    <!-- Next steps -->
    <p class="section-title">What Happens Next</p>
    <div class="steps-grid">
      <div class="step">
        <div class="num">1</div>
        <p><strong>E-ticket sent</strong><br>Your ticket will arrive in a separate email within 24 hours.</p>
      </div>
      <div class="step">
        <div class="num">2</div>
        <p><strong>Check in online</strong><br>Use your booking reference to check in 24 hours before departure.</p>
      </div>
      <div class="step">
        <div class="num">3</div>
        <p><strong>Arrive early</strong><br>Domestic flights: arrive at the airport at least 1 hour before departure.</p>
      </div>
    </div>

    <!-- Mountain flight notice -->
    <?php
    $mountain_routes = [ 'LUA', 'LTP', 'MWP', 'PPL', 'JMO', 'JIR', 'NAM', 'PHK', 'BJH', 'IMK', 'SIH', 'TPJ', 'RJB', 'RUK', 'RUM', 'SIF', 'TMI', 'BGL', 'BJU', 'DNP', 'GKH', 'LDN', 'MEY', 'NGX' ];
    $is_mountain = in_array( $meta['dep_airport'], $mountain_routes, true ) || in_array( $meta['arr_airport'], $mountain_routes, true );
    if ( $is_mountain ) : ?>
    <div class="notice">
      <strong>⚠ Mountain Airport Advisory</strong><br>
      Mountain airports are subject to weather delays and cancellations. We strongly recommend travel insurance and flexible plans. Our team will notify you immediately of any changes.
    </div>
    <?php endif; ?>

    <!-- CTA -->
    <div class="cta-block">
      <p style="margin:0 0 16px;font-size:.9rem;color:#374151;">Need help? We're here for you.</p>
      <a href="https://wa.me/9779851234567?text=Hi%2C+my+booking+ref+is+<?php echo urlencode( $meta['reference'] ); ?>" class="btn">
        💬 WhatsApp Us
      </a>
      <a href="mailto:infinityskytravels8@gmail.com?subject=Booking+<?php echo urlencode( $meta['reference'] ); ?>" class="btn-outline">
        Email Support
      </a>
    </div>

    <!-- Add-ons -->
    <?php if ( ! empty( $meta['addons'] ) && is_array( $meta['addons'] ) ) : ?>
    <p class="section-title">Add-ons Included</p>
    <ul style="margin:0 0 24px;padding-left:20px;font-size:.85rem;color:#374151;">
      <?php foreach ( $meta['addons'] as $addon ) :
          $name = is_array( $addon ) ? ( $addon['name'] ?? '' ) : $addon;
          echo '<li style="margin-bottom:4px;">' . esc_html( $name ) . '</li>';
      endforeach; ?>
    </ul>
    <?php endif; ?>

  </div><!-- /.content -->

  <!-- Footer -->
  <div class="footer">
    <p style="color:#fff;font-weight:600;margin-bottom:8px;">Infinity Sky Travels</p>
    <p>Kathmandu, Nepal &nbsp;|&nbsp; <a href="tel:+9779851234567">+977 985 1234567</a></p>
    <p><a href="mailto:infinityskytravels8@gmail.com">infinityskytravels8@gmail.com</a> &nbsp;|&nbsp; <a href="https://infinityskytravels.com">infinityskytravels.com</a></p>
    <p style="margin-top:16px;">This email was sent to <strong style="color:rgba(255,255,255,.8);"><?php echo esc_html( $meta['lead_email'] ); ?></strong> because you made a booking with us.</p>
  </div>

</div><!-- /.wrapper -->
</body>
</html>
