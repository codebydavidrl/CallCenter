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

    //add listeners
    btnStart.addEventListener("click", () => {
        //run simulator
        let activated = Simulator.run();
    });
    btnStop.addEventListener("click", () => {
        //run simulator
        let stopped = Simulator.stop();
    });
}
