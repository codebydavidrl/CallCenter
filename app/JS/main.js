var hours = [];
async function init2() {
    console.log("Init page..");
    console.log(JSON.parse(sessionStorage.userInfo));
    //Prepareing now information
    await prepareNowSection();
    //Preparing charts
    prepareTodaySection();

    //set time out of 1 minute
    //All page information is fetched
    setInterval(() => {
        prepareNowSection();
        prepareTodaySection();
    }, 60000);
}

async function prepareNowSection() {
    const now = await getNowInfo();
    const { agents, calls, averageTime } = now;
    updateAgents(agents);
    updateCalls(calls);
    updateAverageTime(averageTime);
}
//updates agents label on dashboard
function updateAgents(agents) {
    const active_label = document.getElementById("agents-active-label");
    const available_label = document.getElementById("agents-available-label");

    active_label.innerHTML = agents.active;
    available_label.innerHTML = agents.available;
}
//updates calls label on dashboard
function updateCalls(calls) {
    console.log("Updating now calls information.");
    const active_label = document.getElementById("calls-active-label");
    const queue_label = document.getElementById("calls-queue-label");

    active_label.innerHTML = calls.active;
    queue_label.innerHTML = calls.onQueue;
}
//Creates their charts
function updateAverageTime(avgTime) {
    const { handle, wait } = avgTime;
    if (handle.time == null) {
        handle.time = "00:00";
    } else {
        const time = handle.time.split(":");
        handle.time = `${time[1]}:${time[2]}`;
    }

    if (wait.time == null) {
        wait.time = "00:00";
    } else {
        const time = wait.time.split(":");
        wait.time = `${time[1]}:${time[2]}`;
    }
    //Handle time
    drawGauge({
        parentDiv: "avg-handle-div",
        data: handle.minutes,
        dataString: handle.time,
        max: 8,
        title: "Average Handle Time",
        color: "#FEBC2C",
    });
    //Wait time
    drawGauge({
        parentDiv: "avg-wait-div",
        data: wait.minutes,
        dataString: wait.time,
        max: 4,
        title: "Average Wait Time",
        color: "#FD413C",
    });
}
//get now information
async function getNowInfo() {
    const request = await fetch(`${config.localHost}/nows/`, {
        headers: {
            username: config.headers.username,
            token: config.headers.token,
        },
    });
    const json = await request.json();
    const { now } = json;
    return now;
}

//get values and assign them to arrays
async function prepareTodaySection() {
    console.log("Preparing charts");
    //create hours array
    for (let i = 0; i < 24; i++) {
        hours.push(`${i}:00`);
    }
    const { today } = await getData();
    const { agents, calls, averageHandleTime, averageWaitTime } = today;
    showCallsChart(calls);
    showAgentsChart(agents);
    showAvgHT(averageHandleTime);
    showAvgWT(averageWaitTime);
}

//Show agents chart
function showAgentsChart(agents) {
    console.log("showing agents charts...");
    //Calls Chart
    drawColumnChart({
        parentDiv: "chart-agents",
        chartTitle: "Active agents by hour",
        yAxis: {
            minValue: 0,
            title: "Quantity",
        },
        xAxis: {
            title: "hour",
            categories: hours,
        },
        values: [
            {
                name: "Agents",
                data: agents,
                animation: false,
                color: "#00d26a",
            },
        ],
    });
}
//show calls chart
function showCallsChart(calls) {
    console.log("showing calls charts...");
    //Calls Chart
    drawColumnChart({
        parentDiv: "chart-calls",
        chartTitle: "calls received by hour",
        yAxis: {
            minValue: 0,
            title: "Quantity",
        },
        xAxis: {
            title: "hour",
            categories: hours,
        },
        values: [
            {
                name: "calls",
                data: calls,
                animation: false,
                color: "#3a78f2",
            },
        ],
    });
}
//Show average handle time chart
function showAvgHT(averageHandleTime) {
    console.log("showing average handle time...");
    //Calls Chart
    drawColumnChart({
        parentDiv: "chart-avg-HandTime",
        chartTitle: "Average handle time by hour",
        yAxis: {
            minValue: 0,
            title: "Quantity",
        },
        xAxis: {
            title: "hour",
            categories: hours,
        },
        values: [
            {
                name: "average",
                data: averageHandleTime,
                animation: false,
                color: "#FEBC2C",
            },
        ],
    });
}
//Show average wait time chart
function showAvgWT(averageWaitTime) {
    console.log("showing avg wait time...");
    //Calls Chart
    drawColumnChart({
        parentDiv: "chart-avg-WaitTime",
        chartTitle: "Average handle time by hour",
        yAxis: {
            minValue: 0,
            title: "Quantity",
        },
        xAxis: {
            title: "hour",
            categories: hours,
        },
        values: [
            {
                name: "average",
                data: averageWaitTime,
                animation: false,
                color: "#FD413C",
            },
        ],
    });
}

//draw colum chart
function drawColumnChart(settings) {
    console.log("Drawing column chart ...");

    Highcharts.chart(settings.parentDiv, {
        chart: { type: "column" },
        title: {
            text: settings.chartTitle,
            style: { "font-size": "10pt", color: "#fff" },
        },
        yAxis: {
            min: settings.yAxis.minValue,
            title: {
                text: settings.yAxis.title,
                style: { "font-size": "8pt", color: "#fff" },
            },
            labels: { style: { "font-size": "6pt", color: "#aaa" } },
        },
        xAxis: {
            categories: settings.xAxis.categories,
            title: {
                text: settings.xAxis.title,
                style: { "font-size": "8pt", color: "#fff" },
            },
            labels: { style: { "font-size": "6pt", color: "#aaa" } },
        },
        legend: { enabled: false },
        series: settings.values,
    });
}

//get information from API
async function getData() {
    const data = await fetch(`${config.localHost}/todays/`, {
        headers: {
            username: config.headers.username,
            token: config.headers.token,
        },
    });
    const json = await data.json();
    return json;
}

function drawGauge(settings) {
    var gaugeOptions = {
        chart: {
            type: "solidgauge",
        },

        title: null,

        pane: {
            center: ["50%", "85%"],
            size: "150%",
            startAngle: -90,
            endAngle: 90,
            background: {
                backgroundColor:
                    Highcharts.defaultOptions.legend.backgroundColor || "#EEE",
                innerRadius: "60%",
                outerRadius: "100%",
                shape: "arc",
            },
        },

        exporting: {
            enabled: false,
        },

        tooltip: {
            enabled: false,
        },

        // the value axis
        yAxis: {
            stops: [
                [0.1, "#55BF3B"], // green
                [0.5, "#DDDF0D"], // yellow
                [0.9, "#DF5353"], // red
            ],
            lineWidth: 0,
            tickWidth: 0,
            minorTickInterval: null,
            tickAmount: 2,
            title: {
                y: -70,
            },
            labels: {
                y: 16,
            },
        },

        plotOptions: {
            solidgauge: {
                dataLabels: {
                    y: 5,
                    borderWidth: 0,
                    useHTML: true,
                },
            },
        },
    };

    // The speed gauge
    var chartSpeed = Highcharts.chart(
        settings.parentDiv,
        Highcharts.merge(gaugeOptions, {
            yAxis: {
                min: 0,
                max: settings.max,
                title: {
                    text: settings.title,
                },
            },

            credits: {
                enabled: false,
            },

            series: [
                {
                    name: "avgHandleTime",
                    data: [settings.data],
                    dataLabels: {
                        format: `<div  style="text-align:center">
                                <span style="font-size:10px;opacity:0.7;color:#ccc;">${settings.title}</span><br/><br/><br/>
                                <span style="font-size:10px;color:${settings.color};">${settings.dataString} min</span>
                            </div>`,
                    },
                },
            ],
        })
    );
}
