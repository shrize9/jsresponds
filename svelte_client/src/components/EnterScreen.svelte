<script>
    import RangeSlider from "svelte-range-slider-pips";
    import {respond, Respond} from "../store/RespondStore.js"
    import {onMount} from "svelte";
    import {RespondRest} from "../rests/RespondRest.js";
    import {replace} from "svelte-spa-router";

    let respondName = $respond.name
    let maxAsks =100;
    let countQuestion =10;
    let askText ="";

    onMount(()=>{
        if($respond.respondId ==="") {
            (new RespondRest()).empty().then((json) => {
                maxAsks = json.data.totalAsks;
                countQuestion = json.data.avgResponseAsks;
                askText = json.data.randomAsk;
            })
        }else{
            //need load
            console.log("need navigate to ask");
            (new RespondRest()).load($respond.respondId).then((json) => {
                if(json.error ==false) {
                    respond.load(json.data.respond);
                    if (($respond.currentAskId !== undefined) && ($respond.currentAskId != "undefined")) {
                        replace("/ask/" + $respond.currentAskId);
                    } else
                        replace("/ask");
                }else{

                }
            })
        }
    })

    function createHandler(){
        if(respondName.trim().length ==0){
            alert("Не указано имя");
            return;
        }
        (new RespondRest()).create(respondName, countQuestion).then((json)=> {
            if(json.error ==false){
                let newRespond =new Respond();
                newRespond.respondId     =json.data.token;
                newRespond.name          =respondName;
                newRespond.countQuestion =countQuestion;
                respond.create(newRespond);
                replace("/ask")
            }else{

            }
        })
    }
</script>

<div class="panel">
    <div class="panel-row">
        <label for="name">Имя</label><input class="form-control" id="name" bind:value={respondName}>
    </div>
    <div class="panel-row">
        <label for="range">Количество вопросов</label>
        <RangeSlider float values={[countQuestion]} max={maxAsks} min={1} pips on:change={(value)=> countQuestion=value.detail.value}></RangeSlider>
    </div>
    <div class="panel-row">
        <h4>Пример вопроса:</h4><span class="ask-helper">{askText}</span>
    </div>
    <div class="panel-row">
        <a href="#" class="btn btn-success" on:click={createHandler}>Перейти к вопросам</a>
    </div>
</div>
