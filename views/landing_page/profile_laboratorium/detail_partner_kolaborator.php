<?php include __DIR__ . '/../layout/header.php'; ?>
<section class="section section-sm section-first bg-default text-md-start">
    <div class="container">
        <div class="row row-50 justify-content-center align-items-xl-center">

            <!-- FOTO -->
            <div class="col-md-10 col-lg-5 col-xl-6">
                <img 
                    src="/assets/partner_kolaborator/<?php echo htmlspecialchars($logo); ?>" 
                    alt="<?php echo htmlspecialchars($nama_partner); ?>" 
                    width="519" 
                    height="564"
                    style="object-fit: cover; object-position: top; border-radius: 10px;"
                />
            </div>

            <!-- DETAIL -->
            <div class="col-md-10 col-lg-7 col-xl-6">
                
                <h3 class="text-spacing-25 fw-normal title-opacity-2">
                    <?php echo htmlspecialchars($nama_partner); ?>
                </h3>

                <div class="tabs-custom tabs-horizontal tabs-line" id="tabs-4">
                     <div class="tab-pane fade show active" id="tabs-4-1">
                        <p><?php echo nl2br(htmlspecialchars($deskripsi)); ?></p>
                     </div>

                </div>
            </div>

        </div>
    </div>
</section>


<?php include __DIR__ . '/../layout/footer.php'; ?>