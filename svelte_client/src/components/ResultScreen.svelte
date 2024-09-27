
<script>
    import * as d3 from "d3"
    import {respond} from "../store/RespondStore.js";
    import {replace} from "svelte-spa-router";
    import {onDestroy, onMount} from "svelte";
    import {createRespondBroadcastClient} from "../rests/RespondBroadcastClient.js";
    import {notifications} from "../store/notifications.js";

    let topPlace ="Ожидаем результат";
    let answersPie=null;

    var data = [
        {correct:1, askId:10, counted:20},
        {correct:0, askId:11, counted:22},
        {correct:0, askId:13, counted:40},
        {correct:1, askId:15, counted:30},
        {correct:1, askId:20, counted:40},
        {correct:1, askId:40, counted:50}
    ];

    onMount(()=>{
        createRespondBroadcastClient().addCallback(getTopPlaceRespond);

        const width=500;
        const height = Math.min(500, width / 2);
        const outerRadius = height / 2 - 10;
        const innerRadius = outerRadius * 0.75;
        const tau = 2 * Math.PI;
        const color = d3.scaleOrdinal(d3.schemeObservable10);

        const svg = d3.select(answersPie)
            .attr("viewBox", [-width/2, -height/2, width, height]);

        const arc = d3.arc()
            .innerRadius(innerRadius)
            .outerRadius(outerRadius);

        const pie = d3.pie().value((d)=>d.counted).sort(null);

        let g = svg.append("g")
            .attr("transform", "translate(150, 120)");

        // Grouping different arcs
        var arcs = g.selectAll("arc")
            .data(pie(data))
            .enter()
            .append("g");

        arcs.append("path")
            .attr("fill", (d, i) => {if(data[i].correct ==1) return color(i); else return "#FF0000"; })
            .attr("d", arc)
            .each(function(d) { this._current = d; }); // store the initial angles

        console.log(svg);
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
    <svg width=500 height=500 bind:this={answersPie}></svg>
    <div class="panel-row">
        {topPlace}
    </div>
    <a href="#" on:click|preventDefault={resetHandler}>Вернуться в начало</a>
</div>
