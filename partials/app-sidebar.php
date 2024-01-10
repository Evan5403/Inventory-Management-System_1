<?php

  $user = $_SESSION['user'];

 ?>

<div class="sideBar" id="sideBar">
  <h3 class="DashBoardLogo" id="DashBoardLogo">IMS</h3>
  <div class="sideBarUser">
    <img src="images/index.png" alt="User Image" id="userImage">
    <span class=""><?= $user['first_name']." ".$user['last_name'] ?></span>
  </div>
  <div class="sideBarMenus">
    <ul class="DashBoardLists">
      <!-- class="menuActive" -->
      <li class="liMainMenu">
        <a href="./dashboard.php"><i class="fa fa-dashboard"></i> <span id="menuTxt1">DASHBOARD</span></a>
      </li>

      <li class="liMainMenu showHideSubMenu">
        <a href="javascript:void(0);" class="showHideSubMenu"> <!-- prevents the page from loading-->
          <i class="fa fa-tag showHideSubMenu" data-submenu="usermgt"></i>
          <span id="menuTxt2" class="showHideSubMenu">PRODUCT MGT</span>
          <i class="fa fa-angle-left menuIconArrow showHideSubMenu"></i>
        </a>
        <ul class="submenus">
          <li><a href="./product-view.php">
            <i class="fa-regular fa-circle"></i>
            <span class="submenuTxt">View product</span>
          </a></li>
          <li><a href="./product-add.php">
            <i class="fa-regular fa-circle"></i>
            <span class="submenuTxt">add product</span>
          </a></li>
          <!-- <li><a href="./product-order.php">
            <i class="fa-regular fa-circle"></i>
            <span class="submenuTxt">order product</span>
          </a></li> -->
        </ul>
      </li>

      <li class="liMainMenu showHideSubMenu">
        <a href="javascript:void(0);" class="showHideSubMenu"> <!-- prevents the page from loading-->
          <i class="fa fa-truck showHideSubMenu" data-submenu="usermgt"></i>
          <span id="menuTxt3" class="showHideSubMenu">SUPPLIER MGT</span>
          <i class="fa fa-angle-left menuIconArrow showHideSubMenu"></i>
        </a>
        <ul class="submenus">
          <li><a href="./supplier-view.php">
            <i class="fa-regular fa-circle"></i>
            <span class="submenuTxt">View suppliers</span>
          </a></li>
          <li><a href="./supplier-add.php">
            <i class="fa-regular fa-circle"></i>
            <span class="submenuTxt">add suppliers</span>
          </a></li>
        </ul>
      </li>

      <li class="liMainMenu showHideSubMenu">
        <a href="javascript:void(0);" class="showHideSubMenu"> <!-- prevents the page from loading-->
          <i class="fa fa-shopping-cart showHideSubMenu" data-submenu="usermgt"></i>
          <span id="menuTxt3" class="showHideSubMenu">PURCHASE ORDER</span>
          <i class="fa fa-angle-left menuIconArrow showHideSubMenu"></i>
        </a>
        <ul class="submenus">
          <li><a href="./product-order.php">
            <i class="fa-regular fa-circle"></i>
            <span class="submenuTxt">create order</span>
          </a></li>
          <li><a href="./view-order.php">
            <i class="fa-regular fa-circle"></i>
            <span class="submenuTxt">view orders</span>
          </a></li>
        </ul>
      </li>

      <li class="liMainMenu showHideSubMenu">
        <a href="javascript:void(0);" class="showHideSubMenu"> <!-- prevents the page from loading-->
          <i class="fa fa-user-plus showHideSubMenu" data-submenu="usermgt"></i>
          <span id="menuTxt4" class="showHideSubMenu">USER MGT</span>
          <i class="fa fa-angle-left menuIconArrow showHideSubMenu"></i>
        </a>
        <ul class="submenus">
          <li><a href="./users-view.php" class="submenuLink">
            <i class="fa-regular fa-circle"></i>
            <span class="submenuTxt">View Users</span>
          </a></li>
          <li><a href="./user-add.php" class="submenuLink">
            <i class="fa-regular fa-circle"></i>
            <span class="submenuTxt">add Users</span>
          </a></li>
        </ul>
      </li>


  <!-- <a href="#"><span id="menuTxt3">REVENUE MANAGEMENT</span></a>
</li>
<li>
  <a href="#"><span id="menuTxt4">ACCOUNTS RECEIVABLE</span></a>
</li>
<li>
  <a href="#"><span id="menuTxt5">CONFIGURATION</span></a>
</li>
<li>
  <a href="#"><span id="menuTxt6">STATS</span></a>
</li> -->
    </ul>
  </div>
</div>
