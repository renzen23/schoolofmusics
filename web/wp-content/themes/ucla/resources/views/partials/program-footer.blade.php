@php
$copyright = get_field('copyright', 'option');
@endphp

<footer class="content-info footer__dark-mode">
  <div class="container">
    <div class="footer__divider"></div>
    <div class="row justify-content-end">
      
      <div class="col-12 col-sm-6 col-md-4">
      
        <div class="footer-copyright">
          <p>&copy; <?php echo date("Y"); ?> {!! $copyright !!}</p>
          <p>Site Design by <a href="http://kley.co/" target="_blank">Kley, Inc</a>.</p>
        </div>

      </div>

    </div>
  </div>
</footer>

@php
    wp_reset_query();
@endphp
