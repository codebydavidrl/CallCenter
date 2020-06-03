function init() {
    console.log("Initializing page...");

    //show nav and header
    initMenu();

    //creating an instance of callsimulador
    const Simulator = new CallSimulator();
    //run simulator
    Simulator.run();
}
