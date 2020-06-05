class CallSimulator {
    constructor() {
        this.hour = this.getHour();
        this.updateHourInterval();
    }

    run() {
        console.log("Running simulator...");
        this.setCallTimeout();
        return true;
    }
    stop() {
        console.log("Simulator was stopped.");
        clearTimeout(this.timeout);
        return true;
    }
    getTimeInterval() {
        //return a random number between min and max
        config.simulator.limits.forEach((limit) => {
            if (limit.hour === this.hour) {
                this.timeInterval =
                    Math.floor(Math.random() * (limit.max - limit.min)) * 1000;
                return;
            }
        });
    }
    setCallTimeout() {
        this.timeout = setTimeout(() => {
            this.getTimeInterval();
            this.generateCellNumber();
            this.sendCall();
            this.setCallTimeout();
        }, this.timeInterval);
    }
    async sendCall() {
        console.log(this.showInformation());
        //uncomment to make this work
        const request = await fetch(
            `${config.localHost}/calls/receive/${this.cellphone}`,
            {
                method: "POST",
            }
        );
        const answer = await request.json();
        if (answer.status == 0) {
            //call added succesfully
            this.insertToTable();
            console.log(answer);
        } else {
            throw new Error(answer.message);
        }
    }
    generateCellNumber() {
        const number =  Math.floor(Math.random() * (6649999999 - 6640000000)) + 6640000000;
        return number;
    }
    getHour() {
        const date = new Date();
        return date.getHours();
    }
    updateHourInterval() {
        setInterval(() => {
            const date = new Date();
            this.hour = date.getHours();
        }, 5000);
    }
    showInformation() {
        return {
            cellphone: this.cellphone,
            currentTime: this.getCurrentTime(),
            nextCallIn: `${this.timeInterval / 1000} seconds`,
        };
    }
    getCurrentTime() {
        const d = new Date();
        const getSeconds = () => {
            if (d.getSeconds() < 10) return `0` + d.getSeconds();
            else return d.getSeconds();
        };
        const getMinutes = () => {
            if (d.getMinutes() < 10) return `0` + d.getMinutes();
            else return d.getMinutes();
        };
        const getHours = () => {
            if (d.getHours() < 10) return `0` + d.getHours();
            else return d.getHours();
        };

        return `${getHours()}:${getMinutes()}:${getSeconds()}`;
    }
    setTableId(id) {
        id ? (this.tableId = id) : console.error("Table Body id missing");
    }

    insertToTable() {
        const table = document.getElementById(this.tableId);
        table.insertAdjacentHTML(
            "afterbegin",
            `<tr>
                <td>${this.cellphone}</td>
                <td>${this.getCurrentTime()}</td>
                <td>${this.timeInterval / 1000} seconds</td>
            </tr>`
        );
    }
}
