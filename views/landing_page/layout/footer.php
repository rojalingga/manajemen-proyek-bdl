
           <?php
    require_once __DIR__ . '/../../../app/models/ProfilWeb.php';
    $profil = new ProfilWeb();
    $dataProfil = $profil->getData();
    ?>
        
        <!-- Page Footer-->
        <footer class="section footer-corporate context-dark">
            <div class="footer-corporate-inset">
                <div class="container">
                    <div class="row row-40 justify-content-lg-between">
                        <div class="col-sm-6 col-md-12 col-lg-3 col-xl-6">
                            <div class="oh-desktop">
                                <div class="wow slideInRight" data-wow-delay="0s">
                                    <h6 class="text-spacing-100 text-uppercase">Lokasi</h6>
                                    <article class="post post-minimal-2">
                                        <iframe
                                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3400.7668702723736!2d112.61672182586685!3d-7.945098539609607!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd629dfd58aaf95%3A0xe72a182dfd18e01c!2sGedung%20Teknik%20Sipil%2C%20Teknik%20Informatika%20%26%20Magister%20Terapan%2C%20POLITEKNIK%20NEGERI%20MALANG!5e0!3m2!1sid!2sid!4v1762739368137!5m2!1sid!2sid"
                                            width="500" height="250" frameborder="0"
                                            style="border:0; border-radius:10px;" allowfullscreen="">
                                        </iframe>

                                    </article>

                                    <style>
                                        @media (max-width: 768px) {
                                            article.post-minimal-2 iframe {
                                                width: 100% !important;
                                                height: 200px !important;
                                            }
                                        }
                                    </style>

                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-5 col-lg-3 col-xl-4">
                            <div class="oh-desktop">
                                <div class="wow slideInRight" data-wow-delay="0s">
                                    <h6 class="text-spacing-100 text-uppercase">Kontak Kami</h6>
                                    <ul class="footer-contacts">
                                        <li>
                                          <div class="unit">
                                            <div class="unit-left">
                                              <span class="icon fa fa-location-arrow"></span>
                                            </div>
                                            <div class="unit-body">
                                              <a class="link-location" href="#">
                                                <?= $dataProfil['alamat'] ?> 
                                              </a>
                                            </div>
                                          </div>
                                        </li>

                                        <li>
                                          <div class="unit">
                                            <div class="unit-left">
                                              <span class="icon fa fa-phone"></span>
                                            </div>
                                            <div class="unit-body">
                                              <a class="link-phone" href="tel:+623412345678">
                                                <?= $dataProfil['no_telp'] ?>
                                              </a>
                                            </div>
                                          </div>
                                        </li>

                                        <li>
                                          <div class="unit">
                                            <div class="unit-left">
                                              <span class="icon fa fa-envelope"></span>
                                            </div>
                                            <div class="unit-body">
                                              <a class="link-email">
                                                <?= $dataProfil['email'] ?>
                                              </a>
                                            </div>
                                          </div>
                                        </li>

                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-corporate-bottom-panel">
                <div class="container">
                    <div class="row justfy-content-xl-space-berween row-10 align-items-md-center2">
                        <div class="col-sm-6 col-md-4 text-sm-end text-md-center">
                        </div>
                        <div class="col-sm-6 col-md-6 order-sm-first">
                            <!-- Rights-->
                            <p class="rights"><span>Copyright © 2025 <?= $dataProfil['nama'] ?></span>
                            </p>
                        </div>
                        <div class="col-sm-6 col-md-2 text-md-end">
                            <p class="rights"><a href="privacy-policy.html">Privacy Policy</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    <!-- Global Mailform Output-->
    <div class="snackbars" id="form-output-global"></div>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
   <script src="/template_landing_page/wondertour/js/core.min.js"></script>
<script src="/template_landing_page/wondertour/js/script.js"></script>

<script src="/template_landing_page/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/template_landing_page/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="/template_landing_page/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="/template_landing_page/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="/template_landing_page/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="/template_landing_page/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="/template_landing_page/plugins/jszip/jszip.min.js"></script>
<script src="/template_landing_page/plugins/pdfmake/pdfmake.min.js"></script>
<script src="/template_landing_page/plugins/pdfmake/vfs_fonts.js"></script>
<script src="/template_landing_page/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="/template_landing_page/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="/template_landing_page/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>

</body>

</html>
