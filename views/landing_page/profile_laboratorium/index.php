<?php include __DIR__ . '/../layout/header.php'; ?>
<!-- <h3 class="mt-5">Profile Laboratorium</h3> -->
<section class="section section-sm bg-default">
    <div class="container">
       <h3 class="title-block find-car oh">
            <span class="d-inline-block">Profil Laboratorium</span>
        </h3>
        <div class="row row-50 row-sm">
            <div class="col-md-6">
                <article class="quote-lisa">
                    <div class="unit unit-spacing-md align-items-center">
                        <div class="unit-left"></div>
                        <div class="unit-body">
                            <h4 class="team-modern-status">Sejarah</h4>
                        </div>
                    </div>
                    <div class="quote-modern-text">
                        <p class="q"><?= nl2br($profil_web['sejarah']) ?></p>
                    </div>
                </article>

                <article class="quote-lisa mt-4">
                    <div class="unit unit-spacing-md align-items-center">
                        <div class="unit-left"></div>
                        <div class="unit-body">
                            <h4 class="team-modern-status">Visi</h4>
                        </div>
                    </div>
                    <div class="quote-modern-text">
                        <p class="q"><?= nl2br($profil_web['visi']) ?></p>
                    </div>
                </article>

            </div>

            <div class="col-md-6">
                <article class="quote-lisa">
                    <div class="unit unit-spacing-md align-items-center">
                        <div class="unit-left"></div>
                        <div class="unit-body">
                            <h4 class="team-modern-status">Misi</h4>
                        </div>
                    </div>
                    <div class="quote-modern-text">
                        <p class="q"><?= nl2br($profil_web['misi']) ?></p>
                    </div>
                </article>
            </div>

        </div>
        <style>
            .quote-lisa .quote-modern-text .q {
                text-align: left !important;
            }
        </style>

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
          </div>
        </div>
        
    </section>

<?php include __DIR__ . '/../layout/footer.php'; ?>