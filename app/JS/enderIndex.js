function init() {
    console.log("Initializing page...");

    //show nav and header
    initMenu();

    // //creating an instance of callsimulador
    const Ender = new CallsEnder();

    //Get buttons
    const btnStart = document.getElementById("start-simulation");
    const btnStop = document.getElementById("stop-simulation");
    const lightStatus = document.getElementById("light-status");

    //add listeners
    btnStart.addEventListener("click", () => {
        //run simulator
        Ender.run();
        lightStatus.style.color = "#66BB6A";
        lightStatus.title = "On";
        console.log("Calls Ender running...")
    });
    btnStop.addEventListener("click", () => {
        //run simulator
        Ender.stop();
        lightStatus.style.color = "#F44336";
        lightStatus.title = "Off";
        console.log("Calls Ender stopped")
    });
}
