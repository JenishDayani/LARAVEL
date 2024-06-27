</section>

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    <div class="copyright">
      &copy; Copyright <strong><span>Blogs</span></strong>. All Rights Reserved
    </div>
    <div class="credits">
      <!-- All the links in the footer should remain intact. -->
      <!-- You can delete the links only if you purchased the pro version. -->
      <!-- Licensing information: https://bootstrapmade.com/license/ -->
      <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ -->
      Designed by Jenish Dayani
    </div>
  </footer><!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="{{asset('storage/Admin')}}/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="{{asset('storage/Admin')}}/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="{{asset('storage/Admin')}}/vendor/chart.js/chart.umd.js"></script>
  <script src="{{asset('storage/Admin')}}/vendor/echarts/echarts.min.js"></script>
  <script src="{{asset('storage/Admin')}}/vendor/quill/quill.js"></script>
  <script src="{{asset('storage/Admin')}}/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="{{asset('storage/Admin')}}/vendor/tinymce/tinymce.min.js"></script>
  <script src="{{asset('storage/Admin')}}/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="{{asset('storage/Admin')}}/js/main.js"></script>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const sliderAlert = document.getElementById('slider-alert');

      setTimeout(() => {
        sliderAlert.classList.add('show');
      }, 100);

      setTimeout(() => {
        sliderAlert.classList.remove('show');
        setTimeout(() => {
          sliderAlert.style.right = '-100%'; // Reset position off screen
        }, 500); // Adjust if needed to match transition duration
      }, 5000); // Adjust duration as needed
    });
  </script>

</body>

</html>