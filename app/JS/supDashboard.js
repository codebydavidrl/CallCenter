async function init() {
    console.log("Initializing page...");

    //show nav and header
    initMenu();
    await createTableQueue();
    await createTableActive();
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
    const { queue } = await getCallsOnQueue();
    const body = document.getElementById("tbody-calls-queue");
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
    const { active } = await getCallsActive();
    const body = document.getElementById("tbody-active-calls");
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
                <td> <img src='${agent.photo}' with=50px />${agent.name} </td>
                <td>${workstation.description} </td>
            </tr>`
        );
    });
    console.log(active);
}
