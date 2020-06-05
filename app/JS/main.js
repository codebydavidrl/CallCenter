var hours = [];
var jsonData;
async function init2() {
    console.log("Initi page..");
    jsonData = await getData();
    prepareCharts();
}

//get values and assign them to arrays
function prepareCharts() {
    console.log("Preparing charts");
    for (let h = 0; h < 24; h++) {
        hours.push(h + ":00");
    }
    showCharts();
}
//show charts
function showCharts() {
    console.log("showing charts...");
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
                data: jsonData.today.calls,
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
