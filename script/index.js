let sideBarisOpen = true;

toggle_btn.addEventListener('click',(event) => {
  event.preventDefault();

  if(sideBarisOpen){
    sideBar.style.width = '10%';
    sideBar.style.transition = '0.5s all';
    content_container.style.width = '90%';
    DashBoardLogo.style.fontSize = '50px';
    userImage.style.width = '50px';
    document.getElementById('menuTxt1').style.display = 'none';
    document.getElementById('menuTxt2').style.display = 'none';
    document.getElementById('menuTxt3').style.display = 'none';
    document.getElementById('menuTxt4').style.display = 'none';
    // document.getElementById('menuTxt5').style.display = 'none';
    // document.getElementById('menuTxt6').style.display = 'none';

  // 	let submenuTxt = document.getElementsByClassName('submenuTxt');
  // 	for(let i=0; i < submenuTxt; i++){
  // 		submenuTxt[i].style.display = 'none';
  // }
  sideBarisOpen = false;
  } else {
    sideBar.style.width = '20%';
    content_container.style.width = '80%';
    DashBoardLogo.style.fontSize = '80px';
    userImage.style.width = '80px';
    document.getElementById('menuTxt1').style.display = 'inline-block';
    document.getElementById('menuTxt2').style.display = 'inline-block';
    document.getElementById('menuTxt3').style.display = 'inline-block';
    document.getElementById('menuTxt4').style.display = 'inline-block';
    // document.getElementById('menuTxt5').style.display = 'inline-block';
    // document.getElementById('menuTxt6').style.display = 'inline-block';

    // let submenuTxt = document.getElementsByClassName('submenuTxt');
    // for(let i=0; i < submenuTxt; i++){
    // 	submenuTxt[i].style.display = 'inline-block';
    // }
    sideBarisOpen = true;
  }

})

// Hide / Show sub-menus
document.addEventListener('click',function(e) {
  let clickedE1 = e.target

  if (clickedE1.classList.contains('showHideSubMenu')) {
    let subMenu = clickedE1.closest('li').querySelector('.submenus')
    let submenuIcon = clickedE1.closest('li').querySelector('.menuIconArrow')

    // close all submenus
    let subMenus = document.querySelectorAll('.submenus')
    subMenus.forEach((sub) => {
      if (subMenu !== sub) {
        sub.style.display = 'none'
      }
    });

    // call function to hide/show side menu
    showHideSubMenu(subMenu,submenuIcon)

  }
});
// fun to show/hide subMenus
function showHideSubMenu(subMenu,submenuIcon) {
  // check if there is a submenu
  if (subMenu != null) {
    if (subMenu.style.display === 'block'){
      subMenu.style.display = 'none'
      submenuIcon.classList.remove('fa-angle-down')
      submenuIcon.classList.add('fa-angle-left')
    }
    else{
      subMenu.style.display = 'block'
      submenuIcon.classList.remove('fa-angle-left')
      submenuIcon.classList.add('fa-angle-down')
    }
  }
}

// add/hide active class to menu
let pathArr = window.location.pathname.split('/')
let currentFile = pathArr[pathArr.length - 1]

let currentNav = document.querySelector('a[href="./'+ currentFile + '"]')
let mainNav = currentNav.closest('li.liMainMenu')
mainNav.style.background = '#f685a1'

let subMenu = currentNav.closest('.submenus')
let submenuIcon = mainNav.querySelector('i.menuIconArrow')

//now we can call showHideSubMenu fun
showHideSubMenu(subMenu,submenuIcon)

// add style to the active link
currentNav.classList.add('subMenuActive')
