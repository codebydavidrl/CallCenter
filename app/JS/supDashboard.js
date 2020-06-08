async function init() {
    console.log("Initializing page...");

    //show nav and header
    initMenu();
    //Active and queued calls refreshed every 3 seconds
    setInterval(async () => {
        await createTableActive();
        await createTableQueue();
    }, 3000);
}

async function getCallsOnQueue() {
    const req = await fetch(`${config.localHost}/calls/queue/`);
    const answer = await req.json();
    return answer;
}
async function getCallsActive() {
    const req = await fetch(`${config.localHost}/calls/active/`);
    const answer = await req.json();
    return answer;
}

async function createTableQueue() {
    console.log("Creating table calls on queue");
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
async function createTableActive() {
    console.log("Creating table active calls");
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
                <td class="td-agent"> <img class='img' src='${agent.photo}' with=50px /> <div class='m-1'>${agent.name}<div/> </td>
                <td>${workstation.description} </td>
            </tr>`
        );
    });
}
