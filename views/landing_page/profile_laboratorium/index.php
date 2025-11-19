<?php include __DIR__ . '/../layout/header.php'; ?>
    <!-- Profile Laboratorium-->
      <section class="section section-sm section-first bg-default">
        <div class="container">
          <h3>Profile Laboratorium</h3>
          <div class="row row-50 row-sm">
            <div class="col-md-6">
              <!-- Visi-->
              <article class="quote-lisa">
                <div class="unit unit-spacing-md align-items-center">
                  <div class="unit-left"></div>
                  <div class="unit-body">
                    <h5 class="team-modern-status">Visi</h5>
                  </div>
                </div>
                <div class="quote-modern-text">
                  <p class="q">Ut venenatis tellus in metus. Sed odio morbi quis commodo odio aenean. Ut ornare lectus sit amet est. Tellus orci ac auctor augue mauris augue neque gravida ullamcorper a lacus.</p>
                </div>
              </article>
            </div>
            <div class="col-md-6">
              <!-- Misi-->
              <article class="quote-lisa">
                <div class="unit unit-spacing-md align-items-center">
                  <div class="unit-left"></div>
                  <div class="unit-body">
                    <h5 class="team-modern-status">Misi</h5>
                  </div>
                </div>
                <div class="quote-modern-text">
                  <p class="q">Fermentum dui faucibus in ornare quam viverra. Tincidunt nunc pulvinar sapien et ligula ullamcorper malesuada proin libero. Cursus in hac habitasse platea dictumst quisque.</p>
                </div>
              </article>
            </div>
          </div>
        </div>
    </section>
    <!-- Sejarah Laboratorium-->
    <section class="section section-sm section-last bg-default">
        <div class="container">
          <div class="owl-carousel owl-modern" data-items="1" data-stage-padding="15" data-margin="30" data-dots="true" data-animation-in="fadeIn" data-animation-out="fadeOut" data-autoplay="true">
            <article class="quote-lisa">
                <div class="unit-body">
                    <h5 class="team-modern-status">Sejarah</h5>
                </div>
                <div class="quote-modern-text">
                  <p class="q">Pharetra vel turpis nunc eget lorem dolor sed viverra ipsum. Diam phasellus vestibulum lorem sed risus ultricies. Aenean et tortor at risus viverra adipiscing. Aliquet enim tortor at auctor urna. Tortor aliquam nulla facilisi cras fermentum. Malesuada pellentesque elit eget gravida cum sociis natoque.</p>
                </div>
            </article>
        </div>
    </section>

    <!-- Tim Kreatif-->
<section class="section section-sm bg-default">
    <div class="container">
        <h3 class="title-block find-car oh">
            <span class="d-inline-block">Tim Kreatif</span>
        </h3>

        <div class="row row-xs row-40 justify-content-center justify-content-lg-start">

            <?php foreach ($tim as $row): ?>
                <div class="col-sm-6 col-md-5 col-lg-3">
                    <article class="team-modern">

                        <div class="team-modern-header">
                            <a class="team-modern-figure" href="/profile-lab/tim-kreatif/<?php echo $row['id']; ?>">
                                <img
                                    class="img-circles"
                                    src="/assets/tim_kreatif/<?php echo htmlspecialchars($row['foto']); ?>"
                                    alt="<?php echo htmlspecialchars($row['nama']); ?>"
                                    width="118"
                                    height="118"
                                    style="
                                        width:118px;
                                        height:118px;
                                        object-fit:cover;
                                        object-position: top;
                                        border-radius:50%;
                                    "
                                />
                            </a>

                            <svg x="0px" y="0px" width="270px" height="70px" viewBox="0 0 270 70">
                                <g>
                                    <path fill="#161616"
                                        d="M202.085,0C193.477,28.912,166.708,50,135,50S76.523,28.912,67.915,0H0v70h270V0H202.085z">
                                    </path>
                                </g>
                            </svg>
                        </div>

                        <div class="team-modern-caption">
                            <h6 class="team-modern-name">
                                <a href="/profile-lab/tim-kreatif/<?php echo $row['id']; ?>">
                                    <?php echo htmlspecialchars($row['nama']); ?>
                                </a>
                            </h6>

                            <p class="team-modern-status">
                                <?php echo htmlspecialchars($row['jabatan']); ?>
                            </p>
                        </div>

                    </article>
                </div>
            <?php endforeach; ?>

        </div>
    </div>
</section>


    <!-- Partner Kolabolator-->
    <section class="section section-sm bg-default">
        <div class="container">
          <h3>Partner Kolabolator</h3>
          <div class="row row-xxl row-70 justify-content-center">
            <?php 
            // Inisialisasi counter untuk animasi delay
            $i = 0; 
            ?>

            <?php foreach ($partner as $row): ?>
                <div class="col-sm-10 col-md-6 col-lg-5 col-xl-4 wow fadeInRight" data-wow-delay="<?php echo $i * 0.1; ?>s">
                    
                    <article class="quote-creative">
                        
                        <a class="quote-creative-figure" href="/profile-lab/partner-kolaborator/<?php echo $row['id']; ?>">
                            <img 
                                class="img-circles" 
                                src="/assets/partner_kolaborator/<?php echo htmlspecialchars($row['logo']); ?>" 
                                alt="<?php echo htmlspecialchars($row['nama_partner']); ?>" 
                                width="87" 
                                height="87"
                                style="object-fit: cover;" 
                            />
                        </a>

                        <h5 class="quote-creative-cite">
                            <a href="/profile-lab/partner-kolaborator/<?php echo $row['id']; ?>">
                                <?php echo htmlspecialchars($row['nama_partner']); ?>
                                <?php echo " "; ?>
                            </a>
                        </h5>

                    </article>
                </div>

                <?php 
                // Tambah counter setiap perulangan agar delay animasi bertambah (0.1s, 0.2s, dst)
                $i++; 
                ?>
            <?php endforeach; ?>
    </section>

<?php include __DIR__ . '/../layout/footer.php'; ?>