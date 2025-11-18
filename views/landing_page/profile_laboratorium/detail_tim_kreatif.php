<?php include __DIR__ . '/../layout/header.php'; ?>
<section class="section section-sm section-first bg-default text-md-start">
    <div class="container">
        <div class="row row-50 justify-content-center align-items-xl-center">

            <!-- FOTO -->
            <div class="col-md-10 col-lg-5 col-xl-6">
                <img 
                    src="/assets/tim_kreatif/<?php echo htmlspecialchars($foto); ?>" 
                    alt="<?php echo htmlspecialchars($nama); ?>" 
                    width="519" 
                    height="564"
                    style="object-fit: cover; object-position: top; border-radius: 10px;"
                />
            </div>

            <!-- DETAIL -->
            <div class="col-md-10 col-lg-7 col-xl-6">
                
                <h3 class="text-spacing-25 fw-normal title-opacity-2">
                    <?php echo htmlspecialchars($nama); ?>
                </h3>

                <div class="tabs-custom tabs-horizontal tabs-line" id="tabs-4">

                    <!-- Tabs -->
                    <ul class="nav nav-tabs justify-content-center gap-5">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" href="#tabs-4-1" data-bs-toggle="tab">Keahlian</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" href="#tabs-4-2" data-bs-toggle="tab">Portofolio</a>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content">

                        <!-- KEAHLIAN -->
                        <div class="tab-pane fade show active" id="tabs-4-1">
                            <p><?php echo nl2br(htmlspecialchars($keahlian)); ?></p>

                        </div>

                        <!-- PORTOFOLIO -->
                        <div class="tab-pane fade" id="tabs-4-2">
                            <p><?php echo nl2br(htmlspecialchars($portofolio_singkat)); ?></p>

                            
                        </div>

                    </div>

                </div>

                <?php if (!empty($linkedin)): ?>
                            <div class="group-md group-middle">
                                <a class="button button-width-xl-230 button-primary button-pipaluk" 
                                   href="<?php echo htmlspecialchars($linkedin); ?>" 
                                   target="_blank">
                                    LinkedIn
                                </a>
                            </div>
                            <?php endif; ?>
            </div>

        </div>
    </div>
</section>


<?php include __DIR__ . '/../layout/footer.php'; ?>