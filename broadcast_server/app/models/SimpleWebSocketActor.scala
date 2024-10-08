package models

import akka.actor._
import com.typesafe.config.ConfigFactory
import play.api.libs.json._
import play.api.libs.json.Json

import java.io.InputStream
import java.net.{HttpURLConnection, URL, URLEncoder}
import java.nio.charset.StandardCharsets
import javax.net.ssl.{HostnameVerifier, HttpsURLConnection, SSLSession}
import scala.concurrent.{ExecutionContext, Future}
import scala.util.Try

object SimpleWebSocketActor {
    val logger = play.api.Logger(getClass)
    // DOCS: Props is a ActorRef configuration object, that is immutable, so it is
    // thread-safe and fully sharable. Used when creating new actors through
    // ActorSystem.actorOf and ActorContext.actorOf.
    def props(service:String, id:String, clientActorRef: ActorRef) = {
        cachedWS.put(key(service,id), clientActorRef)
        Props(new SimpleWebSocketActor(service, id, clientActorRef))
    }

    implicit val ec:ExecutionContext =ExecutionContext.Implicits.global

    private lazy val cachedWS:scala.collection.mutable.HashMap[String, ActorRef]=new scala.collection.mutable.HashMap[String, ActorRef]()

    private def key(service:String, id:String):String={
        s"$service@@$id"
    }

    protected def removeWS(service:String, id:String) ={
        logger.info(s"need remove: ${key(service, id)}")
        cachedWS.remove(key(service,id))
    }

    def sendToWS(service:String, id:String, message:JsValue):Boolean={
        cachedWS.get(key(service,id)).map(ws=>{
            ws ! (message)
        }).isDefined
    }

    def sendToAllWS(service:String, message:JsValue){
        cachedWS.values.foreach(ws=>{
            ws ! (message)
        })
    }
}

class SimpleWebSocketActor(service:String, id:String, clientActorRef: ActorRef) extends Actor {
    import SimpleWebSocketActor.ec
    val logger = play.api.Logger(getClass)

    logger.info(s"SimpleWebSocketActor class started")

    // Получение сообщения от клиента
    def receive = {
        case jsValue: JsValue =>
            logger.info(s"JS-VALUE: $jsValue")
            val clientMessage = getMessage(jsValue)
    }

    // parse the "message" field from the json the client sends us
    def getMessage(json: JsValue): String = (json \ "message").as[String]

    override def preStart(): Unit = {
        super.preStart()
    }

    override def postStop(): Unit = {
        super.postStop()
        SimpleWebSocketActor.removeWS(service,id)
    }
}
