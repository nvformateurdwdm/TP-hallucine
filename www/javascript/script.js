const sort = document.querySelector("#sort");
sort.addEventListener(EventNames.CHANGE, sortChangeHandler);

function sortChangeHandler(evt){
    window.location.href = "index.php?page=movies&sort=" + sort.value;
}

class AbstractSection extends EventTarget{

    constructor(sectionDiv){
        super();
        this.sectionDiv = sectionDiv;
    }

    init(){
        this.dispatchEvent(new CustomEvent(EventNames.INIT));
    }

}

class MovieSection extends AbstractSection {

    constructor(sectionDiv){
        super(sectionDiv);
        console.log("Movie Section",this.sectionDiv);
    }

    init(){
        this.initProgressBar();
        // console.log(this.sectionDiv);
        super.init();
    }
    initProgressBar(){
        const progressBar = new ProgressBar(this.sectionDiv.querySelector("#ratings"));
        console.log("log progressBar",progressBar);
    }

    init(){
        this.initPogressBar();
        super.init();
    }

    initPogressBar(){
        const progressBar = new ProgressBar(this.sectionDiv.querySelector("#rating"));
        progressBar.start();
    }

}

class ProgressBar extends AbstractUIComponent{

    constructor(UIView, disableColor = "#CCCCCC"){
        super(UIView);
        this.disableColor = disableColor;
        this.valueComponent = this.UIView.getAttribute("data-attr");
        this.intervalID;
        this.timer = 0;
        this.boundProgress = this.progress.bind(this);
    }

    start(){
        const progressBarDiv = this.UIView.querySelector("#progressBar");
        progressBarDiv.setAttribute("style", "width:" + 0);
        if(super.value != -1){
            this.intervalID = setInterval(this.boundProgress, 5);
        }else{
            progressBarDiv.setAttribute("style", "width:" + 100 + "%");
            progressBarDiv.style.backgroundColor = this.disableColor;
        }
    }

    stop(){
        clearInterval(this.intervalID);
        this.timer = 0;
    }

    progress(){
        const limit = 1000;
        const percent = Math.round(this.timer / limit * 100);
        console.log(percent);
        
        this.timer += 5;
        const progressBarDiv = this.UIView.querySelector("#progressBar");
        progressBarDiv.setAttribute("style", "width:" + percent.toString() + "%");
        if(percent >= super.value){
            this.stop();
        }
        
    }

}

const movieSectionDiv = document.querySelector("#movie_section");
if(movieSectionDiv){
    const movieSection = new MovieSection(movieSectionDiv);
    movieSection.init();
}