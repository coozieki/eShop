<?php
if ($_SESSION['auth']==true)
    header("Location: /home");
?>

<?php 
require(USER_VIEWS . HEADER);
?>


    <form id="reg-form" class="margin-from-header">
        <input type="text" name="email" id="email">
        <input type="password" name="password">
        <div class="g-recaptcha" data-sitekey="6LcfzrwZAAAAANoqo98EYQSMuHI8rYv2rjkeB7fK"></div>
        <input type="submit" value="Submit">
    </form>

    <p id="text"></p>
    <?php echo $_SESSION['name'] ?>
    
    <?php require(USER_VIEWS . FOOTER_JS) ?>
</body>
</html>