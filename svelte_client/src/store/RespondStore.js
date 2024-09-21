import {writable, get} from "svelte/store";

export class Respond{
    constructor(respond) {
        if(respond !== undefined) {
            this.respondId = respond.respondId;
            this.name = respond.name;
            this.countQuestion = respond.countQuestion;
            this.created = respond.created;
            this.answers = respond.answers;
            this.currentAskId =0;
        }else{
            this.respondId =localStorage.getItem("respondId") || "";
            this.name="";
            this.countQuestion="";
            this.created=0;
            this.answers=[];
            this.currentAskId =localStorage.getItem("currentAskId") || "";
        }
    }
}

function createRespond() {
    const {subscribe, update, set} = writable(new Respond());

    return {
        subscribe,
        reset:()=>{
            let respond =new Respond();
            localStorage.removeItem("respondId");
            localStorage.removeItem("currentAskId");
            respond.respondId ="";
            set(respond)
        },
        create:(respond)=>{
            localStorage.setItem("respondId", respond.respondId);
            set(respond);
        },
        load:(respond)=>{
            set(respond);
        },
        nextAsk:(ask)=>{
            console.log(ask);
            localStorage.setItem("currentAskId", ask.id);
            update((respond)=> {
                respond.currentAskId = ask.id;
                return respond;
            });
        },
        addAnswer:(answer)=>{
            update((respond)=>{
                respond.answers =[...respond.answers, answer];
                return respond;
            })
        },
        canGetNextAsk:()=>{
            const _respond =get(respond);
            return _respond.answers.length < _respond.countQuestion;
        },
        countAnswers:()=>{
            const _respond =get(respond);
            return _respond.answers.length;
        }
    }
}

export const respond =createRespond()
