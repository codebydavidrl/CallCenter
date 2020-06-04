function init() {
    console.log("Initializing page...");

    //show nav and header
    initMenu();

    // //creating an instance of callsimulador
    const Simulator = new CallSimulator();
    //set table blody id
    Simulator.setTableId("body");

    //Get buttons
    const btnStart = document.getElementById("start-simulation");
    const btnStop = document.getElementById("stop-simulation");
    const lightStatus = document.getElementById("light-status");

    //add listeners
    btnStart.addEventListener("click", () => {
        //run simulator
        Simulator.run();
        lightStatus.style.color = "#66BB6A";
        lightStatus.title="On"
    });
    btnStop.addEventListener("click", () => {
        //run simulator
        Simulator.stop();
        lightStatus.style.color = "#F44336";
        lightStatus.title="Off"
    });
}
