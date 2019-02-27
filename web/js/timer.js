function setTimers(selector) {
    let timers = $(selector);
    $.each(timers, (key, value) => {
        new Timer($(value));
    });
}

class Timer {
    constructor(element) {
        this.seconds = Number(element.data('seconds'));
        this.element = element;
        $(this.element).text(this.getFormattedTime(this.seconds));

        if (this.seconds > 0) {
            this.timer = setInterval(this.tick.bind(this), 1000);
        }
    }

    reset() {
        clearInterval(this.timer);
        this.seconds = 0;
    }

    tick() {
        if (this.seconds <= 0) {
            this.reset();
        }

        let formattedTime = this.getFormattedTime(this.seconds);
        $(this.element).text(formattedTime);
        this.seconds--;
    }

    getFormattedTime(seconds) {
        let hours = Math.floor(seconds / 3600);
        let minutes = Math.floor(seconds / 60) % 60;
        let remainingSeconds = seconds % 60;
        return `${this.padLeft(hours)}:${this.padLeft(minutes)}:${this.padLeft(remainingSeconds)}`;
    }

    padLeft(num) {
        num = '00' + num;
        return num.substring(num.length, num.length - 2);
    }
}