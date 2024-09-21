import BaseRest from "./BaseRest.js";

export class RespondBroadcastClient extends BaseRest{
    constructor(respondId, callback) {
        super();
        const socket =new WebSocket(this.getWSFullUrl("ws/respond/" +respondId))
        socket.onmessage = (event) => {
            callback(event);
        };
    }
}
