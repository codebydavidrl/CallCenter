var userInfo = {};
let toggle = false;
const BODY_ID = "body";

function initMenu() {
    const isLogged = Boolean(sessionStorage.loggedIn);
    if (isLogged == true) {
        userInfo = JSON.parse(sessionStorage.userInfo);
        showMenu();
        showInfoUser();
        return;
    }
    //redirect to login
    const nav = document.querySelector("#nav");
    nav.style.display = "none";
    window.location = "login.html";
}

function showMenu() {
    //Div menu
    const nav = document.querySelector("#nav");
    //icon hamburger
    const menu_icon_div = document.querySelector(`#hamburger`);
    //Logo
    const logo = document.getElementById("logo");
    //Get language
    const language = "en";
    //add current class to div
    nav.classList.add(userInfo.theme);
    //insert some initial content
    menu_icon_div.insertAdjacentHTML(
        "afterbegin",
        `<i  class="fas fa-bars large-icon ${userInfo.theme}-icon" 
        id='menu-main'></i> `
    );
    //Insert logo
    logo.insertAdjacentHTML(
        "beforeend",
        `<img src="https://datacom.global/wp-content/uploads/2018/11/datcom-global-blanco.png" alt="" srcset="" />`
    );

    //main content inserted
    const menu = document.getElementById("nav");
    menu.style.display = "none";
    userInfo.menu.forEach((option) => {
        menu.insertAdjacentHTML(
            "beforeend",
            `<i style='margin-left: 1rem;' class="fas fa-${option.icon} icon ${
                userInfo.theme
            }-icon menu-item"> &nbsp <span class="quicksand">
            ${option["title" + language]}</span></i>  `
        );
        if (option.subOptions) {
            option.subOptions.forEach((subOption) => {
                //console.log(subOption);
            });
        }
        //console.log(option);
    });
    menu.insertAdjacentHTML(
        "beforeend",
        `<i style='margin-left: 1rem;' id='btn-logout' class="fas fa-sign-out-alt icon ${userInfo.theme}-icon menu-item"> &nbsp <span class="quicksand" > 
    Log out</span></i> `
    );
    //logout
    handleLogout();
    //menu event listener
    hm = document.getElementById("menu-main");
    hm.addEventListener("click", () => {
        toggleMenu();
    });
}

function showInfoUser() {
    const img_src = `${userInfo.photo}`;
    const info_div = document.getElementById("user");
    const header = document.getElementsByTagName("header");
    header[0].classList.add(`${userInfo.theme}`);
    info_div.insertAdjacentHTML(
        "afterbegin",
        ` 
        <div style='margin:1rem;' class='${userInfo.theme}'>
            <div class='quicksand'>${userInfo.name}</div>
            <div class ='quicksand'>${userInfo.roles[0].name}</div>
        </div>
        <div style='margin:1rem;'>
            <div class=photo><img class='photo' src='${img_src}' width='50px' /></div>
            
        </div>`
    );
}

function toggleMenu() {
    if (!toggle) {
        document.getElementById("nav").style.display = "flex";
    } else {
        document.getElementById("nav").style.display = "none";
    }

    toggle = !toggle;
}

function handleLogout() {
    const logout_btn = document.querySelector("#btn-logout");

    logout_btn.addEventListener("click", () => {
        sessionStorage.loggedIn = null;
        sessionStorage.userInfo = null;

        window.location = "login.html";
    });
}
