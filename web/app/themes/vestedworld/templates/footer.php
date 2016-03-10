<footer class="site-footer" role="contentinfo">
	<h2>Contact</h2>
	<section class="copyright">
        <h3><svg class="icon icon-vestedworld" role="img"><title><?php bloginfo('name'); ?></title><use xlink:href="#icon-vestedworld"></use></svg></h3>
        <p>&copy; <?php echo date('Y'); ?> VestedWorld, Inc.<br>
        all rights reserved.</p>
	</section>
	<section>
		<h3>Office</h3>
		<address class="vcard">
			<a target="_blank" href="https://goo.gl/maps/WNv9ytDxkuL2">
				<span class="adr">
					<span class="street-address">222 W. Merchandise Mart Plaza, Suite 1212</span><br> 
					<span class="locality">Chicago</span>, <span class="region">Illinois</span> <span class="postal-code">60654</span>
				</span>
			</a>
		</address>
	</section>
	<section>
		<h3>Info</h3>
		<ul>
			<li><a target="_blank" href="mailto:<?php echo get_option( 'contact_email' ); ?>"><?php echo get_option( 'contact_email' ); ?></a></li>
			<li>+1 333 000 0000</li>
		</ul>
	</section>
	<section>
		<h3>Social</h3>
	   	<ul>
          	<li><a href="https://www.linkedin.com/<?php echo get_option( 'linkedin_id', 'vestedworld' ); ?>">LinkedIn</a></li>
          	<li><a href="https://www.facebook.com/<?php echo get_option( 'facebook_id', 'vestedworld' ); ?>">Facebook</a></li>
          	<li><a href="https://www.twitter.com/<?php echo get_option( 'twitter_id', 'vestedworld' ); ?>">Twitter</a></li>
        </ul>
	</section>
</footer>