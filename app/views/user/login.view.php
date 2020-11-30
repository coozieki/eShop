<?php
if ($_SESSION['auth']==true)
    header("Location: /home");
?>

<?php 
    require(USER_VIEWS . HEADER);
    ?>

    <section class="section-intro margin-from-header">
        <div class="intro-inner">
            <h1 class="flex--row"><b>Welcome</b><span>To Ave</span></h1>
            <h2>Sign In Or Register</h2>
        </div>
    </section>

    <div class="section-login">
        <div class="container-small">
            <div class="login-inner flex--row">
                <form id="log-form">
					<input type="hidden" name="token" value="<?=$_SESSION['token']?>">
                    <div class="form-inner flex--col">
                        <h3 class="form-caption">Sign In</h3>
                        <span id="log-error-message" class="login-error-message">Error</span>
                        <input placeholder="Your login" type="text" name="login" id="log-login">
                        <input placeholder="Your password" type="password" name="password" id="log-pass">
                        <div class="submit-container flex--row">
                            <button type="submit" class="common-btn"><span class="common-btn-text">Sign In</span></button>
                            <a href="#">Forgot Your Password <i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
                        </div>
                    </div>
                </form>
                <form id="reg-form">
					<input type="hidden" name="token" value="<?=$_SESSION['token']?>">
                    <div class="form-inner flex--col">
                        <h3 class="form-caption">Register</h3>
                        <span id="reg-error-message" class="login-error-message">Error</span>
                        <input placeholder="Your email" type="text" name="email" id="reg-email">
                        <input placeholder="Your password" type="password" name="password" id="reg-pass">
                        <input placeholder="Confirm password" type="password" name="conf-password" id="reg-confirm-pass">
                        <div id="reg-recaptcha" class="g-recaptcha" data-sitekey="6LcfzrwZAAAAANoqo98EYQSMuHI8rYv2rjkeB7fK"></div>
                        <div class="submit-container flex--row">
                            <span>By clicking ‘Create Account’, you 
                                agree to our <a href="#"> Privacy Policy <i class="fa fa-long-arrow-right" aria-hidden="true"></i></a></span>
                            <button type="submit" class="common-btn"><span class="common-btn-text">Create Account</span></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <p id="text"></p>
    
    <?php echo $_SESSION['name'] ?>

    <?php require(USER_VIEWS . FOOTER_JS) ?>
</body>
</html>