const form = document.getElementById("login__container");
const API = "http://localhost/CALLCENTER2020/api/users/";
sessionStorage = null;
form.addEventListener("submit", async (event) => {
    event.preventDefault();
    const name = document.getElementById("name").value;
    const password = document.getElementById("password").value;
    //isLogged has user's information
    const isLogged = await login(name, password);
    if (isLogged) {
        handleHasLogged();
    } else {
        //invalid user
        alert("Invalid user or password");
    }
});

//callback execute if receive information
//and it's all goood
const login = async (name, password) => {
    try {
        const request = await fetch(`${API}login/`, {
            method: "GET",
            headers: {
                username: name,
                password: password,
            },
        });
        const { user, status } = await request.json();
        if (status === 200) {
            sessionStorage.loggedIn = true;
            sessionStorage.userInfo = JSON.stringify(user);
            return true;
        } else throw new Error(`${status.errorMessage}. Error Code: ${status}`);
    } catch (error) {
        console.error(error);
        return;
    }
};

const getUserInfo = async (error, id, token) => {
    if (error) return;
    const request = await fetch(`${API}${id}`, {
        header: {
            username: id,
            token: token,
        },
    });
    const userInfo = await request.json();
    return userInfo;
};

const handleHasLogged = () => {
    sessionStorage.loggedIn = true;
    //redirecting them to home
    window.location = "./index.html";
};
