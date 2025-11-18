<!-- Testimonial Start -->
<div class="container-fluid py-6">
    <div class="container">
        <div class="text-center wow bounceInUp" data-wow-delay="0.1s">
            <small
                class="d-inline-block fw-bold text-dark text-uppercase bg-light border border-primary rounded-pill px-4 py-1 mb-3">Testimonial</small>
            <h1 class="display-5 mb-5">What Our Customers says!</h1>
        </div>
        
        <?php if (!empty($testimonials_data)): ?>
        <div class="owl-carousel owl-theme testimonial-carousel testimonial-carousel-1 mb-4 wow bounceInUp"
            data-wow-delay="0.1s">
            <?php foreach ($testimonials_data as $testimonial): ?>
            <div class="testimonial-item rounded bg-light" itemscope itemtype="http://schema.org/Review">
                <div class="d-flex mb-3">
                    <img src="<?php echo htmlspecialchars($testimonial['image']); ?>" 
                         class="img-fluid rounded-circle flex-shrink-0"
                         alt="<?php echo htmlspecialchars($testimonial['name']); ?>" 
                         itemprop="image">
                    <div class="position-absolute" style="top: 15px; right: 20px;">
                        <i class="fa fa-quote-right fa-2x"></i>
                    </div>
                    <div class="ps-3 my-auto">
                        <h4 class="mb-0"><span itemprop="author"><?php echo htmlspecialchars($testimonial['name']); ?></span></h4>
                        <p class="m-0" itemprop="jobTitle"><?php echo htmlspecialchars($testimonial['position']); ?></p>
                    </div>
                </div>
                <div class="testimonial-content">
                    <div class="d-flex">
                        <?php for ($i = 0; $i < $testimonial['rating']; $i++): ?>
                        <i class="fas fa-star text-primary"></i>
                        <?php endfor; ?>
                    </div>
                    <p class="fs-5 m-0 pt-3" itemprop="reviewBody">"<?php echo htmlspecialchars($testimonial['review']); ?>"</p>
                    <div itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating" class="d-none">
                        <meta itemprop="ratingValue" content="<?php echo $testimonial['rating']; ?>" />
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Learn More Button -->
        <div class="text-center mt-4 wow bounceInUp" data-wow-delay="0.3s">
            <a href="testimonial.php" class="btn btn-primary rounded-pill py-3 px-5">
                View All Reviews <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
        <?php else: ?>
        <div class="text-center">
            <p class="text-muted">No testimonials available at the moment.</p>
        </div>
        <?php endif; ?>
    </div>
</div>
<!-- Testimonial End -->
