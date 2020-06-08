class CallsEnder {
    constructor() {
        this.calls = [];
    }

    run() {
        this.setCallsInterval();
    }
    stop() {
        clearInterval(this.interval);
    }
    setCallsInterval() {
        //Interval every minute
        this.interval = setInterval(async () => {
            await this.getCallsActive();
            this.analizeCalls();
        }, config.ender.timeInterval);
    }
    async getCallsActive() {
        try {
            const request = await fetch(`${config.localHost}/calls/active/`);
            const answer = await request.json();
            answer.status == 0
                ? (this.calls = answer.active)
                : console.error("Request Failed, Status: ", answer.status);
        } catch (error) {
            throw new Error(error);
        }
    }

    analizeCalls() {
        this.calls.forEach(async (call) => {
            const a = call.metrics.handleTime.split(":");
            const seconds = a[0] * 60 * 60 + +a[1] * 60 + +a[2];
            const handleTime = seconds / 60;

            const random = parseFloat(Math.random().toFixed(2));
            //if greater than 9 end immediately
            if (handleTime < 9) {
                const { probability } = this.filterByHandleTime(handleTime);
                if (random <= probability) {
                    //end call
                    await this.endCall(call);
                }
            } else {
                await this.endCall(call);
            }
        });
    }
    filterByHandleTime(handleTime) {
        for (let i = 0; i < config.ender.probabilities.length; i++) {
            const obj = config.ender.probabilities[i];
            if (handleTime >= obj.from && handleTime <= obj.to) {
                return obj;
            }
        }
    }
    async endCall(call) {
        const session = call.session.id;
        try {
            const request = await fetch(
                `${config.localHost}/sessions/endcall/${session}`,
                { method: "POST" }
            );
            const answer = await request.json();
            const info = {
                message: answer.message,
                call: call,
            };
            if (answer.status == 999) throw new Error(answer.message);
            else console.log(call);
        } catch (error) {
            console.error(error);
            console.error("id call: ", call.id);
            debugger;
        }
    }
}
