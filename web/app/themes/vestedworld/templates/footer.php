<footer class="site-footer" role="contentinfo">
  <div class="footer-logo">
    <h2 class="tab -gray">Contact</h2>
    <svg class="icon icon-vestedworld" role="img"><title><?php bloginfo('name'); ?></title><use xlink:href="#icon-vestedworld"></use></svg>
  </div>
  <div class="office">
    <h3>Office</h3>
    <address class="vcard">
      <a target="_blank" href="https://goo.gl/maps/WNv9ytDxkuL2">
        <span class="adr">
          <span class="street-address">222 W. Merchandise Mart Plaza, Suite 1212</span><br>
          <span class="locality">Chicago</span>, <span class="region">Illinois</span> <span class="postal-code">60654</span>
        </span>
      </a>
    </address>
  </div>
  <div class="contact" id="contact">
    <h3>Contact</h3>
    <ul>
      <li><a target="_blank" href="mailto:<?php echo get_option( 'contact_email' ); ?>"><?php echo get_option( 'contact_email' ); ?></a></li>
      <li>+1 312 600 7684</li>
    </ul>
  </div>
  <div class="social">
    <h3>Social</h3>
    <ul>
      <li><a href="https://www.linkedin.com/<?php echo get_option( 'linkedin_id', 'vestedworld' ); ?>">LinkedIn</a></li>
      <li><a href="https://www.facebook.com/<?php echo get_option( 'facebook_id', 'vestedworld' ); ?>">Facebook</a></li>
      <li><a href="https://www.twitter.com/<?php echo get_option( 'twitter_id', 'vestedworld' ); ?>">Twitter</a></li>
    </ul>
  </div>
  <div class="copyright">
    <p>&copy; <?php echo date('Y'); ?> VestedWorld, Inc.<br>
    all rights reserved.</p>
  </div>
</footer>