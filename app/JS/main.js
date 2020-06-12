var hours = [];
async function init2() {
    console.log("Initi page..");
    //Preparing charts
    prepareCharts();
}

//get values and assign them to arrays
async function prepareCharts() {
    console.log("Preparing charts");
    const { today } = await getData();
    const { agents, calls, averageHandleTime, averageWaitTime } = today;
    showCallsChart(calls);
    showAgentsChart(agents);
    showAvgHT(averageHandleTime);
    showAvgWT(averageWaitTime);
}
//show calls chart
function showCallsChart(calls) {
    console.log("showing calls charts...");
    //Calls Chart
    drawColumnChart({
        parentDiv: "chart-calls",
        chartTitle: "calls received per hour",
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
                data: calls,
                animation: false,
                color: "#FF9800",
            },
        ],
    });
}
//Show agents chart
function showAgentsChart(agents) {
    console.log("showing agents charts...");
    //Calls Chart
    drawColumnChart({
        parentDiv: "chart-agents",
        chartTitle: "calls received per hour",
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
                data: agents,
                animation: false,
                color: "#FF9800",
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
        chartTitle: "calls received per hour",
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
                data: averageHandleTime,
                animation: false,
                color: "#FF9800",
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
        chartTitle: "calls received per hour",
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
                data: averageWaitTime,
                animation: false,
                color: "#FF9800",
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
            style: { "font-size": "10pt", color: "#555" },
        },
        yAxis: {
            min: settings.yAxis.minValue,
            title: {
                text: settings.yAxis.title,
                style: { "font-size": "8pt", color: "#777" },
            },
        },
        xAxis: {
            categories: settings.xAxis.categories,
            title: {
                text: settings.xAxis.title,
                style: { "font-size": "8pt", color: "#777" },
            },
            labels: { style: { "font-size": "6pt", color: "#777" } },
        },
        legend: { enabled: false },
        series: settings.values,
    });
}

//get information from API
async function getData() {
    const data = await fetch("http://localhost/CALLCENTER2020/api/todays/");
    const json = await data.json();
    return json;
}
