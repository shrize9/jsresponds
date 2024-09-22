import BaseRest from "./BaseRest.js";

class RespondBroadcastClient extends BaseRest{

    constructor() {
        super();

        this.socket =null;
        this.callbacks=[];
    }

    open(respondId){
        if(this.socket !=null)
            this.socket.close();

        this.socket =new WebSocket(this.getWSFullUrl("ws/respond/" +respondId))
        this.socket.onmessage = (event) => {
            console.log(this.callbacks);
            for (const index in this.callbacks) {
                this.callbacks[index](event);
            }
        };
        return this;
    }

    addCallback(callback){
        console.log("append websocket callback")
        this.callbacks =[...this.callbacks, callback];
        return this;
    }

    removeCallback(callback){
        console.log("remove websocket callback")
        this.callbacks =this.callbacks.filter((item)=>item != callback);
        return this;
    }
}

export function createRespondBroadcastClient(){
    if(!window.hasOwnProperty("RespondBroadcastClient"))
        window.RespondBroadcastClient =new RespondBroadcastClient();

    return window.RespondBroadcastClient;
}
