<?php
global $PATH, $PRODUCT;
echo "<a class='sm' href='$PATH/'><img id='banner' src='$PATH/views/gfx/img/logo-sm.png' alt='$PRODUCT'></a>";
echo "<a class='lg' href='$PATH/'><img id='banner' src='$PATH/views/gfx/img/logo.png' alt='$PRODUCT'></a>";
if (isset($_COOKIE['didit-user'])){ echo "<a id='logout' href='$PATH/logout/'>Logout</a>"; }
?>