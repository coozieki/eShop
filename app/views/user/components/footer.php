</div>
<footer class="footer">
    <div class="refs-inner">
        <div class="container">
            <div class="refs-inner-col">
                <div class="refs-inner-col-name">Information</div>
                <a href="/brand" class="refs-inner-col-item">The Brand</a>
                <a href="#" class="refs-inner-col-item">Local stores</a>
                <a href="#" class="refs-inner-col-item">Customer service</a>
                <a href="#" class="refs-inner-col-item">Privacy & Cookies</a>
            </div>
            <div class="refs-inner-col">
                <div class="refs-inner-col-name">Why Buy From Us</div>
                <a href="#" class="refs-inner-col-item">Shipping & returns</a>
                <a href="#" class="refs-inner-col-item">Secure shopping</a>
                <a href="#" class="refs-inner-col-item">Testimonials</a>
                <a href="#" class="refs-inner-col-item">Award winning</a>
                <a href="#" class="refs-inner-col-item">Ethical trading</a>
            </div>
            <div class="refs-inner-col">
                <div class="refs-inner-col-name">Your Account</div>
                <?php 
                    if ($_SESSION['auth']==false)
                        echo '<a href="/signin" class="refs-inner-col-item">Sign in</a>
                            <a href="/signup" class="refs-inner-col-item">Register</a>';
                    else
                        echo '
                        <a onclick="$(\'.header-basket\').trigger(\'click\')" class="refs-inner-col-item">View cart</a>
                        <a href="#" class="refs-inner-col-item">View your lookbook</a>
                        <a href="#" class="refs-inner-col-item">Track an order</a>
                        <a href="#" class="refs-inner-col-item">Update information</a>';
                ?>
            </div>
            <div class="refs-inner-col">
                <div class="refs-inner-col-name">Lookbook</div>
                <a href="#" class="refs-inner-col-item">Latest posts</a>
                <a href="#" class="refs-inner-col-item">Men's lookbook</a>
                <a href="#" class="refs-inner-col-item">Women's lookbook</a>
                <a href="#" class="refs-inner-col-item">Lookbooks RSS feed</a>
                <a href="#" class="refs-inner-col-item">View your lookbook</a>
                <a href="#" class="refs-inner-col-item">Delete your lookbook</a>
            </div>
            <div class="refs-inner-col">
                <div class="refs-inner-col-name">Contact Details</div>
                <a class="refs-inner-col-item no-hover">Head Office: Avenue Fashion, 180-182 Regent Street, London.</a>
                <a class="refs-inner-col-item no-hover">Telephone: 0123-456-789</a>
                <a class="refs-inner-col-item no-hover">Email: support@yourwebsite.com</a>
            </div>
        </div>
    </div>
    <div class="footer-inner flex--row">
        <div class="container">
            <span><i class="fa fa-copyright" aria-hidden="true"></i> 2016 Avenue Fashion <sup><i class="fa fa-trademark" aria-hidden="true"></i></sup></span>
            
            <div class="footer-inner-right">
                <span>Design by RobbyDesigns.com</span>
                <span>Dev by Loremipsum.com</span>
            </div>
        </div>
    </div>
</footer>


<script src="<?=ASSETS?>js/jquery-1.12.4.min.js"></script>
<script src="<?=ASSETS?>js/main.js"></script>