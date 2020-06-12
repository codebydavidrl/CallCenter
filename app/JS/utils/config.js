const config = {
    localHost: "http://localhost/CALLCENTER2020/api",
    simulator: {
        limits: [
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
        ],
    },
    ender: {
        timeInterval: 60000,
        probabilities: [
            {
                from: 0,
                to: 1,
                probability: 0.05,
            },
            {
                from: 1,
                to: 2,
                probability: 0.1,
            },
            {
                from: 2,
                to: 3,
                probability: 0.2,
            },
            {
                from: 3,
                to: 4,
                probability: 0.4,
            },
            {
                from: 4,
                to: 5,
                probability: 0.6,
            },
            {
                from: 5,
                to: 6,
                probability: 0.8,
            },
            {
                from: 6,
                to: 7,
                probability: 0.85,
            },
            {
                from: 7,
                to: 8,
                probability: 0.9,
            },
            {
                from: 8,
                to: 9,
                probability: 0.95,
            },
        ],
    },
};
