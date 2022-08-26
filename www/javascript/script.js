const sort = document.querySelector("#sort");
sort.addEventListener(EventNames.CHANGE, sortChangeHandler);

function sortChangeHandler(evt){
    window.location.href = "index.php?page=movies&sort=" + sort.value;
}

class AbstractSection extends EventTarget {

    constructor(sectionDiv) {
        super();
        this.sectionDiv = sectionDiv;
    }

    init() {
        this.dispatchEvent(new CustomEvent(EventNames.INIT));
    }

}

class MovieSection extends AbstractSection {
    
    constructor(sectionDiv) {
        super(sectionDiv);
    }

    init() {
        super.init();
        this.initProgressBar();
    }

    initProgressBar() {
        const progressBar = new ProgressBar(this.sectionDiv.querySelector("#rating"));
        // console.log(progressBar);
        progressBar.start();
    }

}

class ProgressBar extends AbstractUIComponent {

    constructor(UIView, disableColor = "#CCCCCC") {
        super(UIView);
        this.disableColor = disableColor;
        this.valueComponent = this.UIView.getAttribute("data-attr");
        this.intervalID;
        this.boundProgress = this.progress.bind(this);
        this.timer = 0;
    }

    start() {
        const ProgressBarDiv = this.UIView.querySelector("#progressBar");
        ProgressBarDiv.setAttribute("style", "width: 0");

        if (super.value != -1) {
            this.intervalID = setInterval(this.boundProgress, 20);
        } else {
            ProgressBarDiv.setAttribute("style", "width: 100%;"+" background-color: "+this.disableColor);
        }
    }

    stop() {
        clearInterval(this.intervalID);
        this.timer = 0;
    }

    progress() {
        const limit = 1000;
        const percent = Math.round(this.timer/limit * 100);
        
        
        this.timer += 50;
        const ProgressBarDiv = this.UIView.querySelector("#progressBar");
        ProgressBarDiv.setAttribute("style", "width:" + percent.toString() + "%");
        // console.log(percent);

        if (percent >= super.value) {
            this.stop();
        }
    }

}

const movieSectionDiv = document.querySelector("#movie_section");
console.log(movieSectionDiv);
if (movieSectionDiv) {
    const movieSection = new MovieSection(movieSectionDiv);
    movieSection.init();
}