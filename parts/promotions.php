<?php
$promotions = ['PromotionI.jpg', 'PromotionII.jpg', 'PromotionIII.jpg'];
?>
<div id="carouselPromotion" class="carousel slide">
  <div class="carousel-indicators">
    <button type="button" data-bs-target="#carouselPromotion" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
    <button type="button" data-bs-target="#carouselPromotion" data-bs-slide-to="1" aria-label="Slide 2"></button>
    <button type="button" data-bs-target="#carouselPromotion" data-bs-slide-to="2" aria-label="Slide 3"></button>
  </div>
  <div class="carousel-inner">
    <?php foreach ($promotions as $promotion): ?>
      <div class="carousel-item <?= $promotion === $promotions[0] ? 'active' : '' ?>">
        <img src="../images/promotions/<?= $promotion ?>" class="d-block w-100">
      </div>
    <?php endforeach; ?>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselPromotion" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselPromotion" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>