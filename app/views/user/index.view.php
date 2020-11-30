<?php 
require(USER_VIEWS . HEADER);
?>

    <div class="intro-home-page">
        <img class="man-img" src="<?=ASSETS?>img/1-Home.jpg" alt="">
    </div>

    <section class="section-slider">
        <div class="container">
            <div class="section-slider-inner">
                <div class="slider-controls">
                    <span id="control-1" class="control active">Popular</span>
                    <span id="control-2" class="control">New Arrivals</span>
                    <span id="control-3" class="control">Best Sellers</span>
                    <span id="control-4" class="control">Special Offers</span>
                    <span id="control-5" class="control">Coming Soon</span>
                </div>
                <div class="custom-select">
                    <div class="select-selected">
                        <span id="order-1" name="control-1">Popular</span>
                    </div>
                    <div class="select-other flex--col">
                        <span id="order-2" name="control-2">New Arrivals</span>
                        <span id="order-3" name="control-3">Best Sellers</span>
                        <span id="order-4" name="control-4">Special Offers</span>
                        <span id="order-5" name="control-5">Coming Soon</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="slides-wrapper">
        <div class="container">
            <div class="slides-container flex--row">
                <div id="slide-1" class="slide flex--row">
    
                </div>
                <div id="slide-2" class="slide flex--row">
                    
                </div>
                <div class="slide">
                </div>
                <div class="slide">
                </div>
                <div class="slide">
                </div>
            </div>
        </div>
	</div>
	
	<div class="to-cart-options-container">
		<div class="container">
			<div class="product-inner-main-info-options">
				<div class="choose-options">Choose options</div>
				<div class="product-popup-options-container flex--row">
					<div class="product-inner-main-info-options-color">
						<span class="option-name">Color</span>
						<div class="options-box">
							<div id="to-cart-color" class="option-text">Select Color</div>
							<div class="options">
								<span class="option">White</span>
								<span class="option">Black</span>
								<span class="option">Yellow</span>
							</div>
						</div>
					</div>

					<div class="product-inner-main-info-options-size">
						<span class="option-name">Size</span>
						<div class="options-box">
							<div id="to-cart-size" class="option-text">Select Size</div>
							<div class="options">
								<span class="option">Large</span>
								<span class="option">Medium</span>
								<span class="option">Small</span>
							</div>
						</div>
					</div>

					<div class="product-counter">
						<span class="option-name">QTY</span>
						<div class="product-counter-inner">
							<div contenteditable="true" class="counter to-cart-qty">1</div>
							<div class="counter-controls">
								<div class="counter-controls-up"></div>
								<div class="counter-controls-down"></div>
							</div>
						</div>
					</div>
				</div>

				<div class="product-ok-button">
					OK
				</div>
			</div>
		</div>
	</div>

    <section class="section-lookbooks" scroll>
        <div class="section-lookbooks-inner flex--row">
            <div class="lookbooks-item flex--row">
                <div class="lookbooks-image">
                    <img src="app/assets/img/home-lookbooks-items/2.jpg" alt="" id="lookbooks-image-1">
                </div>
                <div class="lookbooks-description flex--col">
                    <h1><b>Men's</b></h1>
                    <h1>Lookbook</h1>
                    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Sunt vitae porro nesciunt harum molestiae aliquam voluptatem. Exercitationem harum dignissimos tenetur?</p>
                    <div class="common-btn"><span class="common-btn-text">View Now</span></div>
                </div>
            </div>
            <div class="lookbooks-item flex--row">
                <div class="lookbooks-image">
                    <img src="app/assets/img/home-lookbooks-items/1.jpg" alt="" id="lookbooks-image-2">
                </div>
                <div class="lookbooks-description flex--col">
                    <h1><b>Women's</b></h1>
                    <h1>Lookbook</h1>
                    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Sunt vitae porro nesciunt harum molestiae aliquam voluptatem. Exercitationem harum dignissimos tenetur?</p>
                    <div class="common-btn"><span class="common-btn-text">View Now</span></div>
                </div>
            </div>
            <div class="lookbooks-item flex--row">
                <div class="lookbooks-image">
                    <img src="app/assets/img/home-lookbooks-items/3.jpg" alt="" id="lookbooks-image-3">
                </div>

                <div class="lookbooks-description flex--col">
                    <h1><b>Your</b></h1>
                    <h1>Lookbook</h1>
                    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Sunt vitae porro nesciunt harum molestiae aliquam voluptatem. Exercitationem harum dignissimos tenetur?</p>
                    <div class="common-btn"><span class="common-btn-text">View Now</span></div>
                </div>
            </div>
        </div>
    </section>

    <?php require(USER_VIEWS . FOOTER_JS) ?>
</body>
</html>