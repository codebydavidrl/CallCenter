async function init() {
    console.log("Initializing page...");

    //Ejecuted all when loaded
    await createTableQueue();
    console.log("Creating table active calls");
    await createTableActive();
    console.log("Creating table calls on queue");
    await createTableHourlyTotals();
    console.log("Creating table Totals hourly");

    //show nav and header
    initMenu();
    //Active and queued calls refreshed every 3 seconds
    setInterval(async () => {
        await createTableQueue(); 
        await createTableActive(); 
    }, 1000);

    //Hourly detils update every minute
    setInterval(async () => {
        await createTableHourlyTotals(); 
    }, 60000);
}

// Get Calls on queue
async function getCallsOnQueue() {
    const req = await fetch(`${config.localHost}/calls/queue/`);
    const answer = await req.json();
    return answer;
}
// Get active calls
async function getCallsActive() {
    const req = await fetch(`${config.localHost}/calls/active/`);
    const answer = await req.json();
    return answer;
}
// Get hourly totals
async function getHourlyTotals() {
    const req = await fetch(`${config.localHost}/totalsHours/`);
    const answer = await req.json();
    return answer;
}

// Create calls on queue table
async function createTableQueue() {
    const { queue } = await getCallsOnQueue();
    const body = document.getElementById("tbody-calls-queue");
    body.textContent = "";
    queue.forEach((call) => {
        const time = call.metrics.waitTime.split(":");
        const waitTime = (
            parseFloat(time[0] * 60) +
            parseFloat(time[1]) +
            parseFloat(time[2] / 60)
        ).toFixed(2);
        let tdWait;
        if (waitTime > 5) {
            tdWait = `<div class="warning-td">
                        <p class="warning-text">${waitTime} minutes</p>
                        <i class="fas fa-warning fa-exclamation-triangle"></i>
                    </div>`;
        } else {
            tdWait = `<div style="margin: 0 0.55rem;">${waitTime} minutes </div>`;
        }

        body.insertAdjacentHTML(
            "afterbegin",
            `<tr>
                <td>${call.dateTime.received}</td>
                <td>${call.phone}</td>
                <td>${tdWait} </td>
            </tr>`
        );
    });
}
// Create active calls table
async function createTableActive() {
    const { active } = await getCallsActive();
    const body = document.getElementById("tbody-active-calls");
    body.textContent = "";
    active.forEach((call) => {
        const { dateTime, metrics, session, status, id, phone } = call;
        const { waitTime, handleTime } = metrics;
        const { received, answered } = dateTime;
        const { agent, workstation } = session;
        const time = handleTime.split(":");
        const ht = (
            parseFloat(time[0] * 60) +
            parseFloat(time[1]) +
            parseFloat(time[2] / 60)
        ).toFixed(2);

        let tdHandleTime;
        if (ht > 5) {
            tdHandleTime = `<div class="warning-td">
                        <p class="warning-text">${ht} minutes</p>
                        <i class="fas fa-warning fa-exclamation-triangle"></i>
                    </div>`;
        } else {
            tdHandleTime = `<div style="margin: 0 0.55rem;">${ht} minutes </div>`;
        }

        body.insertAdjacentHTML(
            "afterbegin",
            `<tr>
                <td>${phone}</td>
                <td>${received}</td>
                <td>${answered} </td>
                <td>${waitTime} </td>
                <td>${tdHandleTime} </td>
                <td class="td-agent" style='width:100%'> 
                    <img class='img' src='${agent.photo}' with=50px /> 
                    <div style='margin-left:.4rem;'>${agent.name}<div/> 
                </td>
                <td>${workstation.description} </td>
            </tr>`
        );
    });
}

//create hourly totals table
async function createTableHourlyTotals() {
    const body = document.getElementById("tbody-hourly-totals");
    body.textContent = "";
    const { totalsHour } = await getHourlyTotals();
    totalsHour.forEach((total) => {
        const { id, day, hour, metrics, time } = total;
        const { callsReceived, callsAnswered, callsEnded } = metrics;
        const { averageWaitTime, averageHandleTime } = time;
        //Average handle time neccessary variables
        const timeAHT = averageHandleTime.split(":");
        const AHT = (
            parseFloat(timeAHT[0] * 60) +
            parseFloat(timeAHT[1]) +
            parseFloat(timeAHT[2] / 60)
        ).toFixed(2);
        //Average wait time neccessary variables
        const timeAWT = averageWaitTime.split(":");
        const AWT = (
            parseFloat(timeAWT[0] * 60) +
            parseFloat(timeAWT[1]) +
            parseFloat(timeAWT[2] / 60)
        ).toFixed(2);

        //td for avgHandeTime and avgWaitTime
        let averageHandleTimetd, averageWaitTimetd;

        //Average Handle Time td
        if (AHT > 8) {
            averageHandleTimetd = `<div class="warning-td">
            averageHandleTimetd<p class="warning-text">${AHT} minutes</p>
                        <i class="fas fa-warning fa-exclamation-triangle"></i>
                    </div>`;
        } else {
            averageHandleTimetd = `<div style="margin: 0 0.55rem;">${AHT} minutes </div>`;
        }
        //Average Wait Time td
        if (AWT > 5) {
            averageWaitTimetd = `<div class="warning-td">
            averageHandleTimetd<p class="warning-text">${AWT} minutes</p>
                        <i class="fas fa-warning fa-exclamation-triangle"></i>
                    </div>`;
        } else {
            averageWaitTimetd = `<div style="margin: 0 0.55rem;">${AWT} minutes </div>`;
        }

        body.insertAdjacentHTML(
            "afterbegin",
            `
        <tr>
            <td>${day}</td>
            <td>${hour}</td>
            <td>${callsReceived} </td>
            <td>${callsAnswered} </td>
            <td>${callsEnded} </td>
            <td>${averageWaitTimetd} </td>
            <td>${averageHandleTimetd} </td>
        </tr>`
        );
    });
}
