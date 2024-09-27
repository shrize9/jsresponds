
<script>
    import * as d3 from "d3"
    import {respond} from "../store/RespondStore.js";
    import {replace} from "svelte-spa-router";
    import {onDestroy, onMount} from "svelte";
    import {createRespondBroadcastClient} from "../rests/RespondBroadcastClient.js";
    import {notifications} from "../store/notifications.js";

    let topPlace ="Ожидаем результат";
    let answersPie=null;

    var data = [30, 86, 168, 281, 303, 365];

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

        const pie = d3.pie().sort(null);

        const path = svg.datum(data).selectAll("path")
            .data(pie)
            .join("path")
            .attr("fill", (d, i) => color(i))
            .attr("d", arc)
            .each(function(d) { this._current = d; }); // store the initial angles

        d3.select(answersPie).append(svg);
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
