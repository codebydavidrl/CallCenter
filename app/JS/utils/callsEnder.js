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
            console.log(this.calls);
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
            const session = call.session.id;
            const random = parseFloat(Math.random().toFixed(2));
            //if greater than 9 end immediately
            if (handleTime <= 9) {
                const { probability } = this.filterByHandleTime(handleTime);
                if (random <= probability) {
                    //end call
                    await this.endCall(session);
                }
            } else {
                await this.endCall(session);
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
    async endCall(session) {
        try {
            const request = await fetch(
                `${config.localHost}/sessions/endcall/${session}`,
                { method: "POST" }
            );
            const answer = await request.json();
            const info = {
                message: answer.message,
                idSession: session,
            };
            if (answer.status == 0) console.log(info);
            else throw new error(answer.message);
        } catch (error) {
            console.error(error);
        }
    }
}
