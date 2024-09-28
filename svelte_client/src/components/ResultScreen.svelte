
<script>
    import * as d3 from "d3"
    import {respond} from "../store/RespondStore.js";
    import {replace} from "svelte-spa-router";
    import {onDestroy, onMount} from "svelte";
    import {createRespondBroadcastClient} from "../rests/RespondBroadcastClient.js";
    import {notifications} from "../store/notifications.js";

    let topPlace ="Ожидаем результат";
    let answersPie=null;
    let answersBar=null;

    var data = [
        {correct:1, askId:10, counted:20},
        {correct:0, askId:11, counted:22},
        {correct:0, askId:13, counted:40},
        {correct:1, askId:15, counted:30},
        {correct:1, askId:20, counted:40},
        {correct:1, askId:40, counted:50}
    ];

    function drawBar(){
        const width=500;
        const height = Math.min(500, width / 2);
        const margin ={
            left:30,right:0,top:0,bottom:40
        }

        const svg = d3.select(answersBar)
                .attr("width", width + margin.left + margin.right)
                .attr("height", height + margin.top + margin.bottom)
                .append("g")
                .attr("transform",
                    "translate(" + margin.left + "," + margin.top + ")");

            // Add X axis
            var x = d3.scaleLinear()
                .domain([0, 3])
                .range([ 0, width]);

            svg.append("g")
                .attr("transform", "translate(0," + height + ")")
                .call(d3.axisBottom(x))
                .selectAll("text")
                .attr("transform", "translate(-10,0)rotate(-45)")
                .style("text-anchor", "end");

            // Y axis
            var y = d3.scaleBand()
                .range([ 0, height ])
                .domain(data.map(function(d) { return d.askId; }))
                .padding(.1);
            svg.append("g")
                .call(d3.axisLeft(y))

            //Bars
            svg.selectAll("myRect")
                .data(data)
                .enter()
                .append("rect")
                .attr("x", x(0) )
                .attr("y", function(d) { return y(d.askId); })
                .attr("width", function(d) { console.log(d); return x(d.correct+1); })
                .attr("height", y.bandwidth() )
                .attr("fill",function(d) { return d.correct ==0 ? "red": "green" })

    }

    function drawPie(){
        const width=300;
        const height = Math.min(300, width / 2);
        const outerRadius = height / 2 - 10;
        const innerRadius = outerRadius * 0.75;
        const tau = 2 * Math.PI;
        const color = d3.scaleOrdinal(d3.schemeObservable10);

        const svg = d3.select(answersPie)
            .attr("viewBox", [-100, height/4, width, height]);

        const arc = d3.arc()
            .innerRadius(0)
            .outerRadius(Math.min(width, height) / 2 - 1);

        const pie = d3.pie().value((d)=>d.counted).sort(null);

        let g = svg.append("g")
            .attr("transform", "translate(50, 110)");

        // Grouping different arcs
        var arcs = g.selectAll("arc")
            .data(pie(data))
            .attr("stroke", "white")
            .enter()
            .append("g");

        arcs.append("path")
            .attr("fill", (d, i) => {if(data[i].correct ==1) return color(i); else return "#FF0000"; })
            .attr("d", arc)
            .attr("stroke-width", 5)
            .attr("stroke", "white")
            .each(function(d) { this._current = d; });
        arcs.append("text")
            .attr("fill", d => "white")
            .attr("transform", d => `translate(${arc.centroid(d)})`)
            .text(d => `${d.data.counted}`);
    }

    onMount(()=>{
        createRespondBroadcastClient().addCallback(getTopPlaceRespond);

        drawPie();
        drawBar();
    })
    onDestroy(()=>{
        createRespondBroadcastClient().removeCallback(getTopPlaceRespond);
    })

    function getTopPlaceRespond(message){
        const body =JSON.parse(message.data);
        if(body.message.type =="topPlace"){
            topPlace ="Вы заняли " +body.message.rank +" место среди всех ответивших";
            console.log(topPlace);
        }
    }

    function resetHandler(){
        respond.reset();
        replace("/")
    }
</script>

<div class="panel">
    <svg bind:this={answersPie}></svg>
    <svg width=500 height=500 bind:this={answersBar}></svg>
    <div class="panel-row">
        {topPlace}
    </div>
    <a href="#" on:click|preventDefault={resetHandler}>Вернуться в начало</a>
</div>
