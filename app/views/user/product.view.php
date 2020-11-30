<?php

$title = $params['title'];
$item = $params['item'];
$reviews = $params['reviews'];

require(USER_VIEWS . HEADER);
?>

<section class="section-intro margin-from-header">
	<div class="intro-inner">
		<h1 class="flex--row"><b>Product</b><span>View</span></h1>
		<div class="intro-product-category"><?= $title ?></div>
	</div>
</section>

<section class="section-product">
	<div class="container">
		<div class="product-inner">
			<div class="error-message hide">

			</div>
			<div class="product-inner-main">
				<div class="product-inner-main-slider">
					<div id="product-content" class="product-inner-main-slider-content">
						<? foreach ($item['view_images'] as $key => $value) {
                            echo '<div id="product-item" class="product-inner-main-slider-content-item">
                                    <img src="' . $value . '" alt="">
                            </div>';
                        }
                        ?>
					</div>
					<div id="product-left" class="product-inner-main-slider-left_control">

					</div>
					<div id="product-right" class="product-inner-main-slider-right_control">

					</div>
				</div>

				<div class="product-inner-main-info">
					<span class="product-inner-main-info-name"><?= $item['name'] ?></span>

					<div class="product-inner-main-info-refs">
						<div class="product-inner-main-info-refs-rating">
							<?php
							$sum = 0;
							foreach ($reviews as $key => $value) {
								$sum += $value['rating'];
							}
							if (count($reviews) > 0)
								$sum = round($sum / count($reviews));

							for ($i=0;$i<$sum;$i++)
								echo '<div class="star"><i class="fa fa-star" aria-hidden="true"></i></div>';
							for ($i=0;$i<5-$sum;$i++)
								echo '<div class="star"><i class="fa fa-star-o" aria-hidden="true"></i></div>';
							?>
						</div>
						<span class="product-inner-main-info-refs-revcount"><span id="count"><?= count($reviews) ?></span> Review(s)</span>
						<a onclick="setTimeout(function(){$('#extra-reviews-button').trigger('click')}, 200)" href="#extra-reviews-button" class="product-inner-main-info-refs-addreview">Add a Review</a>
						<div class="product-inner-main-info-refs-social">
							<span>Share:</span>
							<a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a>
							<a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a>
							<a href="#"><i class="fa fa-google-plus" aria-hidden="true"></i></a>
							<a href="#"><i class="fa fa-pinterest-p" aria-hidden="true"></i></a>
							<a href="#"><i class="fa fa-instagram" aria-hidden="true"></i></a>
							<a href="#"><i class="fa fa-envelope-o" aria-hidden="true"></i></a>
						</div>
					</div>

					<div class="product-inner-main-info-price">
						<span class="product-inner-main-info-price-main<?= $item['sale_gbp'] > 0 ? ' withsale' : '' ?>">
							<span id="product-price-usd"><sup>$</sup><?= $item['cost_usd'] ?></span>
							<span id="product-price-gbp"><sup>£</sup><?= $item['cost_gbp'] ?></span>
						</span>
						<span class="product-inner-main-info-price-sale<?= $item['sale_gbp'] > 0 ? '' : ' nosale' ?>">
							<span id="product-sale-usd"><sup>$</sup><?= $item['sale_usd'] ?></span>
							<span id="product-sale-gbp"><sup>£</sup><?= $item['sale_gbp'] ?></span>
						</span>
					</div>

					<div class="product-inner-main-info-available">Availability: <span id="product_available"><?= $item['count'] > 0 ? 'In stock' : 'Out of stock' ?></span></div>
					<div class="product-inner-main-info-code">Product Code: <span id="product_code">#<?= $item['id'] ?></span></div>
					<div class="product-inner-main-info-tags">Tags:
						<span id="product_tags">
							<?php
							$tags = explode(' ', $item['search_tags']);
							$string = '';
							foreach ($tags as $key => $value) {
								$string .= '<a href="#">' . $value . '</a>, ';
							}
							$string = rtrim($string, ', ');
							echo $string;
							?>
						</span>
					</div>

					<div class="product-inner-main-info-options">
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

					<div class="product-inner-main-info-buttons">
						<div id="to-cart-anim"><i class="fa fa-cart-plus" aria-hidden="true"></i></div>
						<div class="product-inner-main-info-buttons-tocart tocart"><i class="fa fa-shopping-cart" aria-hidden="true"></i> Add To Cart</div>
						<div class="product-inner-main-info-buttons-tolookbook"><i class="fa fa-heart-o" aria-hidden="true"></i> Add To Lookbook</div>
					</div>

					<div class="product-inner-main-info-compare">
						<div class="circle"><i class="fa fa-compress" aria-hidden="true"></i></div> Add To Compare
					</div>
				</div><!--PRODUCT-INNER-MAIN-INFO-->
				
			</div><!--PRODUCT-INNER-MAIN-->
				
			<div class="product-extra-container">
				<div class="product-inner-extra">
					<div class="extra-item">
						<div id="extra-description-button" class="extra-button active">
							Description
						</div>
					</div>
					<div class="extra-item">
						<div id="extra-video-button" class="extra-button">
							Video
						</div>
					</div>
					<div class="extra-item">
						<div id="extra-size-button" class="extra-button">
							Size & Specs
						</div>
					</div>
					<div class="extra-item">
						<div id="extra-delivery-button" class="extra-button">
							Delivery & Returns
						</div>
					</div>
					<div class="extra-item">
						<div id="extra-reviews-button" class="extra-button">
							Reviews
						</div>
					</div>
				</div>
	
				<div class="product-inner-extra-text">
					<div id="extra-description-text" class="extra-text active">
						<?=$item['text']?>
					</div>
					<div id="extra-video-text" class="extra-text">
						Lorem ipsum, dolor sit amet consectetur adipisicing elit. Veniam delectus ipsum facere molestias beatae assumenda commodi modi at iusto illo ab nemo ipsam impedit facilis, quam, laborum quibusdam cum nihil aliquid. Tenetur rem praesentium aspernatur quisquam fuga vel, harum asperiores.
					</div>
					<div id="extra-size-text" class="extra-text">
						Lorem ipsum, dolor sit amet consectetur adipisicing elit. Laboriosam maxime et corrupti optio quia ab vero odio eum, mollitia ipsa blanditiis similique atque alias nam nulla voluptatum nobis fugiat odit?
					</div>
					<div id="extra-delivery-text" class="extra-text">
						Lorem ipsum, dolor sit amet consectetur adipisicing elit. Laboriosam maxime et corrupti optio quia ab vero odio eum, mollitia ipsa blanditiis similique atque alias nam nulla voluptatum nobis fugiat odit?
					</div>
					<div id="extra-reviews-text" class="extra-text">
						<b><?=count($reviews)?> Review(s)</b> <br><br>
						<?php
							for ($j=0;$j<(count($reviews) < REVIEWS_ON_PAGE ? count($reviews) : REVIEWS_ON_PAGE);$j++) {
								$value = $reviews[$j];
								echo '<div class="review">	
											<div class="review-user">
												<div class="review-user-name">' . $value['login'] . '</div>
												<div class="review-user-date">' . $value['date'] . '</div>
												<div class="review-user-rating">';
												for ($i=0;$i<$value['rating'];$i++)
													echo '<div class="star"><i class="fa fa-star" aria-hidden="true"></i></div>';
												for ($i=0;$i<5-$value['rating'];$i++)
													echo '<div class="star"><i class="fa fa-star-o" aria-hidden="true"></i></div>'; 
												echo '</div>
											</div>
											<div class="review-text">' . $value['text'] . '</div>
										</div>';	
							}
						?>
						<div class="reviews-pages">
							<div class="reviews-pages-previous"><i class="fa fa-long-arrow-left" aria-hidden="true"></i> Previous</div>
							<div class="reviews-pages-numbers">
								<div class="reviews-page active"><span>1</span></div>
								<?php
									$count = floor((count($reviews)-1)/REVIEWS_ON_PAGE);
									for ($i=0;$i<$count;$i++)
										echo '<div class="reviews-page"><span>' . ($i+2) . '</span></div>';
								?>
							</div>
							<div class="reviews-pages-next">Next <i class="fa fa-long-arrow-right" aria-hidden="true"></i></div>
						</div>

						<?php 
							if ($_SESSION['auth']==true)
							echo '<form id="form-add-review" class="reviews-add">
							<input type="hidden" name="token" value="' . $_SESSION['token'] .'">
							<div class="reviews-add-title">Your review</div>

							<input id="add-review-rating" type="hidden" name="rating" value="5">
							<div class="reviews-add-rating">
								<div id="add-review-star-1" class="reviews-add-rating-star">
									<i class="fa fa-star" aria-hidden="true"></i>
									<i class="fa fa-star-o" aria-hidden="true"></i>
								</div>
								<div id="add-review-star-2" class="reviews-add-rating-star">
									<i class="fa fa-star" aria-hidden="true"></i>
									<i class="fa fa-star-o" aria-hidden="true"></i>
								</div>
								<div id="add-review-star-3" class="reviews-add-rating-star">
									<i class="fa fa-star" aria-hidden="true"></i>
									<i class="fa fa-star-o" aria-hidden="true"></i>
								</div>
								<div id="add-review-star-4" class="reviews-add-rating-star">
									<i class="fa fa-star" aria-hidden="true"></i>
									<i class="fa fa-star-o" aria-hidden="true"></i>
								</div>
								<div id="add-review-star-5" class="reviews-add-rating-star">
									<i class="fa fa-star" aria-hidden="true"></i>
									<i class="fa fa-star-o" aria-hidden="true"></i>
								</div>
								<span>- Rate the product</span>
							</div>

							<textarea placeholder="Your opinion..." type="text" name="text" class="reviews-add-text"></textarea>
							<input class="reviews-add-submit" type="submit" name="submit" value="Submit">
						</form>';
						else 
							echo '<div class="reviews-no-auth"><a href="/signin">Sing in</a> to leave review</div>';
						?>
						<div class="reviews-add-error"></div>
					</div>
				</div>
			</div><!--PRODUCT-EXTRA-CONTAINER-->
		</div><!--PRODUCT-INNER-->
	</div><!--CONTAINER-->
</section>

<?php require(USER_VIEWS . FOOTER_JS) ?>
</body>

</html>