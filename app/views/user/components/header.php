<html>
<head>
    <title>Avenue Fashion</title>
    <?php require(USER_VIEWS . META_LINKS) ?>
    <script async src="https://www.google.com/recaptcha/api.js?hl=en"></script>
    <script src="<?=ASSETS?>js/beforeload.js"></script>
</head>
<body>
    <header id="header">
        <div class="container">
            <div class="header-inner flex--row">
                <div class="currency">
                    <label for="cur-options">Currency:</label>
                    <select name="currency" id="currency">
                        <option class="option" value="£">GBP</option>
                        <option class="option" value="$">USD</option>
                    </select>
                </div>
                
                <div class="header-nav-container flex--row">
                <?php if ($_SESSION['auth']==false)
                            echo
                                "<div class='profile-container flex--row'> <a class='profile-email flex--row'>" . '<i class="fa fa-user" aria-hidden="true"></i><span>Account</span>'  . "</a>
                                    <div class='profile-menu'>
                                    <nav class='flex--col'>
                                        <a href='/signup' class='profile-menu-item flex--row'><i class='fa fa-user-plus' aria-hidden='true'></i> <span class='profile-menu-item-text'>Sign Up</span></a>
                                        <a href='/signin' class='profile-menu-item flex--row'><i class='fa fa-sign-out' aria-hidden='true'></i>  <span class='profile-menu-item-text'>Sign In</span></a>
                                    </nav>
                                    </div>
                                </div>";
                        else 
                            echo "<div class='profile-container flex--row'> <a class='profile-email'>" . substr($_COOKIE['login'], 0, EMAIL_LEN) . (strlen($_COOKIE['login'])>EMAIL_LEN ? '...' : '')  . "</a>
                                    <div class='profile-menu'>
                                        <nav class='flex--col'>
                                            <a href='/logout' class='profile-menu-item flex--row'><i class='fa fa-user' aria-hidden='true'></i> <span class='profile-menu-item-text'>Profile</span></a>
                                            <a href='/logout' class='profile-menu-item flex--row'><i class='fa fa-sign-out' aria-hidden='true'></i>  <span class='profile-menu-item-text'>Log out</span></a>
                                        </nav>
                                    </div>
                                  </div>"
                     ?>
                    

                    <div class="header-basket flex--row">
                        <div><i class="fa fa-shopping-cart" aria-hidden="true"></i></div>
                        <div id="cart-count"><sup>$</sup>0.00</div>
                        <div class="header-basket-arrow"></div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="cart-close"></div>
    
    <div class="cart">
        <div class="container">
            <div class="cart-inner">

            </div>
        </div>
    </div>

    <div class="menu">
        <div class="container">
            <div class="menu-inner flex--row">
                <a href="/home" class="company-name"><b>Avenue</b> Fashion</a>
                <a href="/home" class="company-name-small"><b>AVE</b></a>

                <div class="menu-nav flex--row">
                    <ul class="flex--row">
                        <li class="nav-item"><a class="nav-item-caption"></a></li>
                        <li class="nav-item"><a class="nav-item-caption"></a></li>
                        <li class="no-after"><a class="nav-item-caption" href="/brand">The Brand</a></li>
                        <li class="no-after"><a class="nav-item-caption" href="#">Stores</a></li>
                        <li id="look-book" class="nav-item"><a class="nav-item-caption"></a></li>

                        <div class="close-button"><i class="fa fa-arrow-up" aria-hidden="true"></i></div>
                    </ul>

                    <form id="search_form">
                        <input id="search-form-text" type="text" placeholder="Search.." name="search">
                        <div class="search-results"></div>
                        <button id="search-button" type="submit"><i class="fa fa-search"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="content">