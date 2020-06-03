let callLimits = [
    {
        hour: 0,
        min: 60,
        max: 120,
    },
    {
        hour: 1,
        min: 60,
        max: 120,
    },
    {
        hour: 2,
        min: 60,
        max: 120,
    },
    {
        hour: 3,
        min: 60,
        max: 120,
    },
    {
        hour: 4,
        min: 60,
        max: 120,
    },
    {
        hour: 5,
        min: 30,
        max: 60,
    },
    {
        hour: 6,
        min: 30,
        max: 60,
    },
    {
        hour: 7,
        min: 0,
        max: 30,
    },
    {
        hour: 8,
        min: 0,
        max: 30,
    },
    {
        hour: 9,
        min: 0,
        max: 30,
    },
    {
        hour: 10,
        min: 0,
        max: 30,
    },
    {
        hour: 11,
        min: 0,
        max: 30,
    },
    {
        hour: 12,
        min: 0,
        max: 30,
    },
    {
        hour: 13,
        min: 0,
        max: 30,
    },
    {
        hour: 14,
        min: 0,
        max: 30,
    },
    {
        hour: 15,
        min: 0,
        max: 30,
    },
    {
        hour: 16,
        min: 0,
        max: 30,
    },
    {
        hour: 17,
        min: 0,
        max: 30,
    },
    {
        hour: 18,
        min: 30,
        max: 60,
    },
    {
        hour: 19,
        min: 30,
        max: 60,
    },
    {
        hour: 20,
        min: 30,
        max: 60,
    },
    {
        hour: 21,
        min: 30,
        max: 60,
    },
    {
        hour: 22,
        min: 60,
        max: 120,
    },
    {
        hour: 24,
        min: 60,
        max: 120,
    },
];

class CallSimulator {
    constructor() {
        this.hour = this.getHour();
        this.updateHourInterval();
    }

    run() {
        this.setCallTimeout();
    }
    stop() {
        clearTimeout(this.timeout);
    }
    getTimeInterval() {
        //return a random number between min and max
        callLimits.forEach((limit) => {
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
        // //uncomment to make this work
        // const request = await fetch(
        //     `http://localhost/CALLCENTER2020/api/calls/receive/${this.cellphone}`,
        //     {
        //         method: "POST",
        //     }
        // );
        // const answer = await request.json();
        // console.log(answer)
    }
    generateCellNumber() {
        const number = Math.floor(Math.random() * 10000000);
        switch (number.toString().length) {
            case 5:
                this.generateCellNumber();
                break;
            case 6:
                this.cellphone = `6648${number}`;
                break;
            case 7:
                this.cellphone = `664${number}`;
                break;
        }
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
        const d = new Date();
        return {
            cellphone: this.cellphone,
            currentTime: `${d.getHours()}:${d.getMinutes()}:${d.getSeconds()}`,
            nextCallIn: `${this.timeInterval / 1000} seconds`,
        };
    }
}
