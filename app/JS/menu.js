var userInfo = {
    id: "1001",
    userName: "mjones",
    fullName: "Mary Jones",
    photo: "users/1001.png",
    role: {
        id: "SA",
        name: "System Administrator",
    },
    language: {
        id: "EN",
        name: "English",
    },
    theme: "dark",
    menuOptions: [
        {
            icon: "home",
            title: {
                EN: "Home",
                SP: "Inicio",
            },
            url: "index.html",
        },
        {
            icon: "users",
            title: {
                EN: "Agents",
                SP: "Agentes",
            },
            url: "agents.html",
        },
        {
            icon: "phone",
            title: {
                EN: "Calls",
                SP: "Llamadas",
            },
            url: "calls.html",
        },
        {
            icon: "chart-bar",
            title: {
                EN: "Charts",
                SP: "Gráficas",
            },
            url: "charts.html",
        },
        {
            icon: "wrench",
            title: {
                EN: "Settings",
                SP: "Configuración",
            },
            url: "settings.html",
        },
        {
            icon: "sign-out-alt",
            title: {
                EN: "Logout",
                SP: "Cerrar Sesión",
            },
            url: "logout.html",
        },
    ],
};
let toggle = false;
const BODY_ID = "body";

function init() {
    showMenu();
    showInfoUser();
}

function showMenu() {
    const menu_icon_div = document.querySelector(`#hamburger`);
    //Get language
    const language = userInfo.language.id;
    //insert some initial content
    menu_icon_div.insertAdjacentHTML(
        "afterbegin",
        `<i  class="fas fa-bars large-icon ${userInfo.theme}-icon" 
        id='menu-main'></i> `
    );
    //main content inserted
    const menu = document.getElementById("nav");
    menu.style.display = "none";
    userInfo.menuOptions.forEach((option) => {
        menu.insertAdjacentHTML(
            "beforeend",
            `<i style='margin-left: 1rem;' class="fas fa-${option.icon} icon ${userInfo.theme}-icon menu-item"> ${option.title[language]}</i>  `
        );
    });
    //menu event listener
    hm = document.getElementById("menu-main");
    hm.addEventListener("click", () => {
        toggleMenu();
    });
}

function showInfoUser() {
    const img_src = `${window.location.pathname}/../${userInfo.photo}`;
    const info_div = document.getElementById("user");
    const header = document.getElementsByTagName('header'); 
    header[0].classList.add(`${userInfo.theme}`) 
    
    info_div.insertAdjacentHTML(
        "afterbegin",
        `<div style='margin:1rem;' class='${userInfo.theme}'>
            <div class='name'>${userInfo.fullName}</div>
            <div>${userInfo.role.name}</div>
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
