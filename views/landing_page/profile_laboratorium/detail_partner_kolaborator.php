<?php include __DIR__ . '/../layout/header.php'; ?>
<section class="section section-sm section-first bg-default text-md-start">
    <div class="container">
        <div class="row row-50 justify-content-center align-items-center">
    
            <div class="col-md-10 col-lg-5 col-xl-6">
                <!-- FOTO -->
                <div class="d-flex justify-content-center"> <img 
                        src="/assets/partner_kolaborator/<?php echo htmlspecialchars($logo); ?>" 
                        alt="<?php echo htmlspecialchars($nama_partner); ?>" 
                        style="
                            width: 100%; 
                            max-width: 250px;      
                            height: auto; 
                            object-fit: contain; 
                            border-radius: 10px;
                            box-shadow: 0 5px 15px rgba(0,0,0,0.1); /* Bayangan halus */
                        "
                    />
                </div>
            </div>

            <div class="col-md-10 col-lg-7 col-xl-6">
            <!-- DETAIL -->
                <h3 class="text-primary mb-3"> 
                    <?php echo htmlspecialchars($nama_partner); ?>
                </h3>

                <p style="line-height: 1.6; color: #555;">
                    <?php echo nl2br(htmlspecialchars($deskripsi)); ?>
                </p>
                
            </div>

        </div>
    </div>
</section>


<?php include __DIR__ . '/../layout/footer.php'; ?>