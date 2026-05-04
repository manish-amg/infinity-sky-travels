<?php
/**
 * Template Name: Contact Us
 */

get_header();
?>

<div class="ist-contact-page">

    <!-- ── Hero ─────────────────────────────────────────────────── -->
    <div class="ist-page-hero jarallax" data-jarallax data-speed="0.5"
         style="background-image:url('https://images.unsplash.com/photo-1526772662000-3f88f10405ff?w=1920&q=80&auto=format&fit=crop');">
        <div class="ist-page-hero__overlay" aria-hidden="true"></div>
        <div class="ist-container" style="position:relative;z-index:1;padding-bottom:var(--space-xl);">
            <?php get_template_part( 'template-parts/global/breadcrumb' ); ?>
            <h1 class="ist-text-white"><?php esc_html_e( 'Get in Touch', 'infinity-sky' ); ?></h1>
            <p style="color:rgba(255,255,255,0.75);font-size:1.1rem;max-width:520px;margin-top:8px;">
                <?php esc_html_e( 'Our team is in Kathmandu and responds within 2 hours during business hours.', 'infinity-sky' ); ?>
            </p>
        </div>
    </div>

    <!-- ── Quick contact channels ─────────────────────────────────── -->
    <div class="ist-contact-channels">
        <div class="ist-container">
            <div class="ist-contact-channels__grid">
                <?php
                $channels = [
                    [
                        'icon'  => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.09 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3 1.22h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.09 8.91a16 16 0 0 0 6.35 6.35l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 21.72 16l.2.92z"/></svg>',
                        'label' => __( 'Call / WhatsApp',    'infinity-sky' ),
                        'value' => '+977 9810597893',
                        'href'  => 'tel:+9779810597893',
                        'sub'   => __( 'Mon–Sun, 7am–9pm NPT', 'infinity-sky' ),
                    ],
                    [
                        'icon'  => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>',
                        'label' => __( 'Email',              'infinity-sky' ),
                        'value' => 'infinityskytravels8@gmail.com',
                        'href'  => 'mailto:infinityskytravels8@gmail.com',
                        'sub'   => __( 'Response within 2 hours', 'infinity-sky' ),
                    ],
                    [
                        'icon'  => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>',
                        'label' => __( 'Office',             'infinity-sky' ),
                        'value' => __( 'Thamel, Kathmandu, Nepal', 'infinity-sky' ),
                        'href'  => 'https://maps.google.com/?q=Thamel,Kathmandu,Nepal',
                        'sub'   => __( 'Near Kathmandu Guest House', 'infinity-sky' ),
                    ],
                    [
                        'icon'  => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>',
                        'label' => __( 'Business Hours',     'infinity-sky' ),
                        'value' => __( 'Mon–Sat 7am – 9pm', 'infinity-sky' ),
                        'href'  => '#contact-form',
                        'sub'   => __( 'Nepal Standard Time (UTC+5:45)', 'infinity-sky' ),
                    ],
                ];
                foreach ( $channels as $ch ) : ?>
                <a href="<?php echo esc_url( $ch['href'] ); ?>" class="ist-contact-channel" <?php echo str_starts_with( $ch['href'], 'http' ) ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>>
                    <div class="ist-contact-channel__icon"><?php echo $ch['icon']; ?></div>
                    <div>
                        <p class="ist-contact-channel__label"><?php echo esc_html( $ch['label'] ); ?></p>
                        <p class="ist-contact-channel__value"><?php echo esc_html( $ch['value'] ); ?></p>
                        <p class="ist-contact-channel__sub"><?php echo esc_html( $ch['sub'] ); ?></p>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- ── Form + Map ────────────────────────────────────────────── -->
    <div class="ist-section">
        <div class="ist-container">
            <div class="ist-contact-layout">

                <!-- Form -->
                <div class="ist-contact-form-wrap" id="contact-form">
                    <h2><?php esc_html_e( 'Send Us a Message', 'infinity-sky' ); ?></h2>
                    <p style="color:var(--ist-text-light);margin-bottom:var(--space-lg);">
                        <?php esc_html_e( 'For trip enquiries, use the Plan My Trip form for a faster custom response. For general questions, use this form.', 'infinity-sky' ); ?>
                    </p>

                    <form id="ist-contact-form" class="ist-contact-form" novalidate
                          data-ajax="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>"
                          data-nonce="<?php echo esc_attr( wp_create_nonce( 'ist_contact' ) ); ?>">

                        <div class="ist-contact-form__row">
                            <div class="ist-form-field">
                                <label class="ist-form-label" for="contact-name"><?php esc_html_e( 'Full Name', 'infinity-sky' ); ?> *</label>
                                <input type="text" id="contact-name" name="name" class="ist-input" required
                                       placeholder="Jane Smith" autocomplete="name">
                                <span class="ist-form-error" id="error-contact-name"></span>
                            </div>
                            <div class="ist-form-field">
                                <label class="ist-form-label" for="contact-email"><?php esc_html_e( 'Email Address', 'infinity-sky' ); ?> *</label>
                                <input type="email" id="contact-email" name="email" class="ist-input" required
                                       placeholder="jane@example.com" autocomplete="email">
                                <span class="ist-form-error" id="error-contact-email"></span>
                            </div>
                        </div>

                        <div class="ist-form-field">
                            <label class="ist-form-label" for="contact-subject"><?php esc_html_e( 'Subject', 'infinity-sky' ); ?> *</label>
                            <select id="contact-subject" name="subject" class="ist-input" required>
                                <option value=""><?php                  esc_html_e( '— Select a topic —',           'infinity-sky' ); ?></option>
                                <option value="trip-enquiry"><?php      esc_html_e( 'Trip / Package Enquiry',        'infinity-sky' ); ?></option>
                                <option value="flight-booking"><?php    esc_html_e( 'Domestic Flight Booking',       'infinity-sky' ); ?></option>
                                <option value="booking-change"><?php    esc_html_e( 'Change / Cancel a Booking',     'infinity-sky' ); ?></option>
                                <option value="general"><?php           esc_html_e( 'General Question',              'infinity-sky' ); ?></option>
                                <option value="partnership"><?php       esc_html_e( 'Business / Partnership',        'infinity-sky' ); ?></option>
                                <option value="feedback"><?php          esc_html_e( 'Feedback',                      'infinity-sky' ); ?></option>
                            </select>
                            <span class="ist-form-error" id="error-contact-subject"></span>
                        </div>

                        <div class="ist-form-field">
                            <label class="ist-form-label" for="contact-message"><?php esc_html_e( 'Message', 'infinity-sky' ); ?> *</label>
                            <textarea id="contact-message" name="message" class="ist-input" rows="5" required
                                      placeholder="<?php esc_attr_e( 'Tell us how we can help…', 'infinity-sky' ); ?>"></textarea>
                            <span class="ist-form-error" id="error-contact-message"></span>
                        </div>

                        <div id="ist-contact-success" class="ist-form-success" hidden>
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                            <?php esc_html_e( "Message sent! We'll get back to you within 2 hours.", 'infinity-sky' ); ?>
                        </div>

                        <button type="submit" class="btn-primary btn-lg" id="ist-contact-submit">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                            <?php esc_html_e( 'Send Message', 'infinity-sky' ); ?>
                        </button>

                    </form>
                </div>

                <!-- Map + office info -->
                <div class="ist-contact-map-col">
                    <div class="ist-contact-map">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3531.7!2d85.3100!3d27.7172!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39eb18fcb77fd4bd%3A0x58099b8d0e6a2a67!2sThamel%2C%20Kathmandu%2044600%2C%20Nepal!5e0!3m2!1sen!2snp!4v1700000000000!5m2!1sen!2snp"
                            width="100%" height="320" style="border:0;border-radius:var(--radius-md);" allowfullscreen=""
                            loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                            title="<?php esc_attr_e( 'Infinity Sky Travels office location map', 'infinity-sky' ); ?>">
                        </iframe>
                    </div>

                    <div class="ist-contact-office-info">
                        <h3><?php esc_html_e( 'Visit Our Office', 'infinity-sky' ); ?></h3>
                        <div class="ist-contact-office-detail">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            <span><?php esc_html_e( 'Thamel Marg, Kathmandu 44600, Nepal', 'infinity-sky' ); ?></span>
                        </div>
                        <div class="ist-contact-office-detail">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            <span><?php esc_html_e( 'Mon–Sat 7am–9pm, Sun 9am–6pm (NPT)', 'infinity-sky' ); ?></span>
                        </div>
                        <div class="ist-contact-office-detail">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.09 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3 1.22h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.09 8.91a16 16 0 0 0 6.35 6.35l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 21.72 16l.2.92z"/></svg>
                            <a href="tel:+9779810597893">+977 9810597893</a>
                        </div>
                        <div class="ist-contact-office-detail">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                            <a href="mailto:infinityskytravels8@gmail.com">infinityskytravels8@gmail.com</a>
                        </div>
                        <a href="https://wa.me/9779810597893?text=<?php echo rawurlencode( 'Hi! I have a question about trekking in Nepal.' ); ?>"
                           class="btn-primary" style="margin-top:var(--space-md);width:100%;justify-content:center;" target="_blank" rel="noopener noreferrer">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg>
                            <?php esc_html_e( 'Chat on WhatsApp', 'infinity-sky' ); ?>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div><!-- /.ist-contact-page -->

<script>
(function () {
    var form    = document.getElementById('ist-contact-form');
    var success = document.getElementById('ist-contact-success');
    var submit  = document.getElementById('ist-contact-submit');
    if (!form) return;

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        var name    = form.querySelector('[name="name"]').value.trim();
        var email   = form.querySelector('[name="email"]').value.trim();
        var subject = form.querySelector('[name="subject"]').value;
        var message = form.querySelector('[name="message"]').value.trim();
        var ok = true;

        function err(id, msg) {
            var el = document.getElementById(id);
            if (el) { el.textContent = msg; el.style.display = msg ? 'block' : 'none'; }
            if (msg) ok = false;
        }

        err('error-contact-name',    name    ? '' : 'Please enter your name.');
        err('error-contact-email',   /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)  ? '' : 'Valid email required.');
        err('error-contact-subject', subject ? '' : 'Please select a topic.');
        err('error-contact-message', message ? '' : 'Please enter a message.');

        if (!ok) return;

        submit.disabled = true;
        submit.textContent = 'Sending…';

        var fd = new FormData();
        fd.append('action',  'ist_contact_form');
        fd.append('nonce',   form.dataset.nonce);
        fd.append('name',    name);
        fd.append('email',   email);
        fd.append('subject', subject);
        fd.append('message', message);

        fetch(form.dataset.ajax, { method: 'POST', body: fd })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (data.success) {
                    form.reset();
                    if (success) success.hidden = false;
                }
                submit.disabled = false;
                submit.textContent = 'Send Message';
            })
            .catch(function () {
                submit.disabled = false;
                submit.textContent = 'Send Message';
            });
    });
})();
</script>

<?php get_footer(); ?>
